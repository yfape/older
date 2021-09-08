<?php
/*
 * @Author: your name
 * @Date: 2021-01-31 10:25:16
 * @LastEditTime: 2021-03-03 01:25:53
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\user\PersonInfo.php
 */
namespace app\wxapp\controller\user;

use app\wxapp\controller\user\UserCommon;

use app\wxapp\model\User;
use app\wxapp\model\Record;
use app\wxapp\model\LogSign;

class Page extends UserCommon
{

    /**
     * @是否签署承诺书: 
     * @param {*}
     * @return {*}
     */
    public function isSign()
    {
        $log = LogSign::where('uid', $this->user->uid)->findOrEmpty();
        if(!$log->isEmpty()){
            return json(['issign' => 1]);
        }else{
            return json(['issign' => 0]);
        }
    }

    /**
     * @获取承诺书数据: 
     * @param {*}
     * @return {*}
     */
    public function getSignData()
    {
        return json([
            'title' => '省老干部活动中心疫情防控承诺书',
            'content' => [
                '我自愿参加省老干部活动中心班队活动，中心工作人员已逐条向我进行解释说明，我将完全按照新型冠状病毒疫情的联防联控、群防群控相关要求参加活动，我已认真阅读并保证承诺书的真实有效，本人承诺：',
                '1.本人身体健康无异常，无发热、咳嗽、呼吸困难等症状，居住在无确诊病例小区范围内。',
                '2.本人近期未接触过感染病者或疑似感染病者，未到境外和国内中、高疫情风险区，未接触境外和国内中、高疫情风险区人员。如接触过，已满足14天医学观察期且无症状（未满14天，提供7日内核酸检测为阴性的结果证明）。',
                '3.本人家庭主要成员和社会关系人未到重点疫区，也未同重点疫区人员和疑似、确诊病例患者有接触史。',
                '4.本人严格配合入场人员的体温检测工作，凭健康通行码“绿码”通行，入场佩戴专用口罩，谈话和活动时与他人保持适度距离，避免密切接触。废弃口罩按照要求放置于专用废弃口罩收集桶。',
                '5.本人知晓国家关于违反《传染病防治法》最高可处7年徒刑的规定，将积极配合省老干部活动中心工作人员采取调查、防护隔离、消毒等疫情防控处置措施。',
                '6.如本人因主观原因隐报、谎报、乱报个人感染疫情、已接触疫情感染病患或疑似病患的相关信息，造成的一切后果由本人承担。'
            ]
        ]);
    }

    public function signBook()
    {
        LogSign::create([
            'uid' => $this->user->uid
        ]);
        abort(200);
    }

}