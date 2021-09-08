<?php
/*
 * @Author: your name
 * @Date: 2021-02-18 13:07:29
 * @LastEditTime: 2021-02-18 21:08:27
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\admin\controller\section\Section.php
 */
namespace app\admin\controller\section;

use think\Request;
use think\facade\Db;

use app\common\model\Section as SectionModel;
use app\common\model\Record as RecordModel;

class Section
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function get()
    {

    }

    /**
     * @list 
     * @param {*}
     * @return {*}
     */
    public function list($tid, $limit, $page)
    {
        $data = SectionModel::where('tid', $tid)->order([
            'date' => 'desc',
            'sid' => 'desc',
            'create_time' => 'desc'
        ])->paginate([
            'list_rows' => $limit,
            'page' => $page
        ]);
        return json($data);
    }

    /**
     * @put
     * @param {*} $sid
     * @param {*} $field
     * @param {*} $value
     * @return {*}
     */
    public function put($sid, $field, $value=null)
    {
        $section = SectionModel::find($sid);
       
        if($field == 'valid'){
            $section->status == 0 || abort(420);
            $section->valid = $section->valid ? 0 : 1;
            $section->valid || $section->tip = $this->request->param('tip');
        }else{
            $section[$field] = $value;
        }
        $section->save();
        return json(['msg' => '更新成功']);
    }

    /**
     * @update: 
     * @param {*}
     * @return {*}
     */
    public function update($sid)
    {
        $section = SectionModel::find($sid);
        $section->save($this->request->only(['start', 'end', 'count', 'tip']));
        return json(['msg' => '更新成功']);
    }

    /**
     * @delete: 
     * @param {*}
     * @return {*}
     */
    public function delete($sid)
    {
        Db::startTrans();
        try{
            SectionModel::destroy($sid);
            RecordModel::destroy(function($q)use($sid){
                $q->where('sid', $sid);
            });
            Db::commit();
        } catch(\Exception $e){
            Db::rollback();
            abort(540, $e->getMessage());
        }
        return json(['msg' => '删除成功']);
    }
    
    
}