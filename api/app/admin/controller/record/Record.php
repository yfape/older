<?php
/*
 * @Author: your name
 * @Date: 2021-02-18 20:51:21
 * @LastEditTime: 2021-03-02 17:22:03
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\admin\controller\record\Record.php
 */
namespace app\admin\controller\record;

use think\Request;

use app\common\model\Record as RecordModel;

class Record
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * @records: 
     * @param {*} $sid
     * @return {*}
     */
    public function list($sid, $limit, $page)
    {
        $records = RecordModel::with('user')->where([
            'sid' => $sid,
            'valid' => 1,
            'delete_time' => null
        ])
        ->order('uid', 'asc')
        ->paginate([
            'list_rows' => $limit,
            'page' => $page
        ]);
        return json($records);
    }

    /**
     * @put: 
     * @param {*} $rid
     * @param {*} $field
     * @param {*} $value
     * @return {*}
     */
    public function put($rid, $field, $value)
    {
        $record = RecordModel::find($rid);
        $record[$field] = $value;
        $record->save();
        return json(['msg' => '更新成功']);
    }
}