<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Model;

use Think\Model;

/**
 * Description of WorkOrderModel
 *
 * @author xiaohui
 * @Date    2016/09/18
 */
class WorkSheetModel  extends Model{
    
    protected $trueTableName = 'db_worksheet';

    protected $_validate  =  array(//这里必须定义为$_validata用来验证

        array('worksheet_describe','require',''),
        array('worksheet_name','require',''),
        array('worksheet_relate_meeting','require',''),
        array('worksheet_start_date','require',''),
        array('worksheet_end_date','require',''),
        array('worksheet_rule_person','require','')
    );
    
   /*
    * 工作单添加
    * 
    * @ahthor xiaohui
    * @param string $param['workname']; 工作单名称
    * @param string $param['association']; 关联会议id
    * @param string $param['start_time']; 开始时间
    * @param string $param['stop_time']; 结束时间
    * @param string $param['personliable']; 负责人
    * @return object 添加成功或失败
    */
    public function addWork($param){
        if($param['association']!='0'){
            $order = M('worksheet');
            //计算天数和每天百分比
            $workDay = $this->workDay($param['start_time'],$param['stop_time']);
            $data['worksheet_name'] = $param['workname'];
            $data['worksheet_relate_meeting'] = $param['association'];
            $data['worksheet_start_date'] = $param['start_time'];
            $data['worksheet_end_date'] = $param['stop_time'];
            $data['worksheet_creat_person'] = session('S_USER_INFO.UID');
            $data['worksheet_rule_person'] = $param['personliable'];
            $data['worksheet_describe'] = $param['worksheet_describe'];
            $data['worksheet_done_persent'] = 0;
            $data['worksheet_state'] = $workDay['state'];
            $data['worksheet_detele'] = 0;
            $data['worksheet_date'] = $workDay['days'];
            $data['worksheet_parcent_day'] = $workDay['parcent'];
            $msg_sys_data = $this->create($data);
            if($msg_sys_data){
                $res = $order->add($msg_sys_data);
                if (FALSE === $res) {
                    writeOperationLog('添加“' . $data['worksheet_name'] . '”工作单', 1);
                    return C('COMMON.ERROR_EDIT');
                } else {
                     writeOperationLog('添加“' . $data['worksheet_name'] . '”工作单', 0);
                    return C('COMMON.SUCCESS_EDIT');
                }  
            }
        }else{
                writeOperationLog('添加“' . $data['worksheet_name'] . '”工作单', 0);
                return C('COMMON.ERROR_EDIT');
        }
    }
    /*
     * 统计数量
     */
     public function getWorkOrderCount($where) {
        $where['worksheet_detele'] = 1;
        $count = D('worksheet')
                ->join('db_meeting on db_worksheet.worksheet_relate_meeting = db_meeting.meeting_id')
                ->join('db_member on db_worksheet.worksheet_rule_person = db_member.uid')
                ->where($where)->count();
        return $count;
    }
    
    /*
     * 查询会议名称
     * @author xiaohui
     * @return array 会议名称
     */
    public function listMeeting(){
        $meeting = M('meeting')
                ->field('meeting_id,meeting_name')
                ->where('meeting_delete = 1')
                ->order('meeting_id desc')
                ->select();
        return $meeting;
    }
    
    /**
     * 分页查询操作
     * 
     * @author xiaohui
     * @param array $where 查询条件
     * @param int 查询开始位置
     * @param int 查询条数
     * @return array 成功返回列表
     */
    public function getList($where, $first_rows, $list_rows) {
      $order = M('worksheet');
      $where['worksheet_detele'] = 1;
      $list = $order
              ->join('db_meeting on db_worksheet.worksheet_relate_meeting = db_meeting.meeting_id')
              ->join('db_member on db_worksheet.worksheet_rule_person = db_member.uid')
              ->where($where)
              ->limit($first_rows, $list_rows)
              ->order('worksheet_id desc')
              ->select();
      $newList = $this->getState($list);
      return $newList;
    }
    
    /*
     * 查询单个工作单
     * @author xiaohui
     * @param int 工作单号
     * @return array 成功返回列表
     */
    
    public function selectWork($param){
        $where['worksheet_detele'] = 1;
        $work = D('worksheet')
              ->field('name,worksheet_state,worksheet_id,worksheet_end_date,worksheet_name,worksheet_rule_person,worksheet_done_persent,worksheet_state,meeting_name,worksheet_abandoned_reason,worksheet_describe')
              ->join('db_meeting on db_worksheet.worksheet_relate_meeting = db_meeting.meeting_id')
              ->join('db_member on db_worksheet.worksheet_rule_person = db_member.uid')
              ->where("worksheet_id = $param")
              ->find();
        return $work;
    }
    
    /*
     * 修改工单状态
     * @author xiaohui
     * @param int 工作单号
     * @return array 成功返回
     */
    
    public function saveWork($param){
        $order = M('worksheet');
        $data['worksheet_creat_person'] = session('S_USER_INFO.UID');
        $data['worksheet_rule_persont'] = $param['personliable'];
        $data['worksheet_done_persent'] = $param['worksheet_done_persent']; 
        $data['worksheet_state'] = $this->workState($param['worksheet_state'],$param['worksheet_id'],$param['worksheet_done_persent']);
        $work_id = $param['worksheet_id'];
        $data['worksheet_abandoned_reason'] = $param['worksheet_abandoned_reason'];
        $res = $order->where("worksheet_id = $work_id")->save($data);
        if($res){
            writeOperationLog('修改“' . $data['worksheet_name'] . '”工作单', 1);
            return C('COMMON.SUCCESS_EDIT');
        }else{
            writeOperationLog('修改“' . $data['worksheet_name'] . '”工作单', 0);
            return C('COMMON.ERROR_EDIT');
        } 
    }
      
    /*
     * 计算单个工单几天和每天%
     * @author xiaohui
     */
    public function workDay($start,$stop){
     
        $timeiff = strtotime($stop) - strtotime($start);
        $workDay = array();
        //计算天数
        $workDay['days'] = floor($timeiff/3600/24)+1;
        //计算每天完成%
        $workDay['parcent'] = number_format(100/$workDay['days'],0);
        
        $time = time();
        $catime = strtotime($start);
        if($catime > $time){
            $workDay['state'] = "未启动";
        }else{
            $workDay['state'] = "正常";
        }
        return $workDay;
    }
    /*
     * 判断当前状态
     */
    public function workState($state,$id,$parcent){
        if($parcent == "100" || $parcent == "100%"){
            $states = "办结";
            return $states;
        }else{
            $work = D('worksheet')
                    ->field('worksheet_parcent_day,worksheet_end_date,worksheet_state,worksheet_start_date,worksheet_done_persent')
                    ->where("worksheet_id = $id")
                    ->find();
            if($state == '0'){
                $states = $work['worksheet_state'];
                return $states;
            }

            elseif($state == '1' || $state == '2'){
                $time = time();
                $stoptime = strtotime($work['worksheet_end_date']);
                $starttime = strtotime($work['worksheet_start_date']);
                if($stoptime > $time){
                    $cation = $stoptime - $time;
                    $day = floor($timeiff/3600/24);
                    $surplus = $day * $work['worksheet_parcent_day'];
                    $sum = $surplus + $work['worksheet_done_persent'];
                    if($starttime > $time){
                        
                        $states="未启动";
                        return $states;
                    }else{
                        if($sum < 100){
                            $states = "延迟";
                            return $states;
                        }else{
                            $states = "正常";
                            return $states;
                        }
                    }
                }else{
                    $states = "延迟";
                    return $states;
                }
            }
            else{
                return $state;
            }  
        }
    }
    /*
     * 分页刷新工作单状态
     * @author xiao hui 
     */
    public function getState($list){
        $time = time();
        foreach($list as $key=>$val){
            $starttime = strtotime($val['worksheet_start_date']);
            if($val['worksheet_state'] == "未启动"){          
                if($time > $starttime){
                    $state = "正常";
                    $this->saveOneOrder($val['worksheet_id'],$state);
                }
            }
            if($val['worksheet_state'] == "正常"){
                if($val['worksheet_done_persent'] <= $val['worksheet_parcent_day']*$val['worksheet_date']){
                    $state = "正常";
                    $this->saveOneOrder($val['worksheet_id'],$state);
                }else{
                    $state = "延期";
                    $this->saveOneOrder($val['worksheet_id'],$state);
                }
            }
        }
        return $list;        
    }
    /*
     * 修改单个状态
     * @author xiao hui
     */
    public function saveOneOrder($id,$state){
        $data['worksheet_state'] = $state;
        return D('worksheet')->where("worksheet_id = $id")->save($data);
    }
    /*
     * 导出execl 查询
     * @author xiao hui
     */
    public function getExecl($param){
        $param['worksheet_name'] != '' ? $where['worksheet_name'] = array('like', '%' . $param['worksheet_name'] . '%') : '';
        $param['meeting_name'] != '' ? $where['meeting_name'] = array('like', '%' . $param['meeting_name'] . '%') : '';
        $param['worksheet_rule_person'] != '' ? $where['worksheet_rule_person'] = array('like', '%' . $param['worksheet_rule_person'] . '%') : '';
        $param['worksheet_start_date'] != '' ? $where['worksheet_start_date'] = array('like', '%' . $param['worksheet_start_date'] . '%') : '';
        $order = M('worksheet');
        $where['worksheet_detele'] = 1;
        $list = $order
            ->field('worksheet_name,meeting_name,worksheet_creat_person,worksheet_rule_person,worksheet_start_date,worksheet_end_date,worksheet_state,worksheet_abandoned_reason,worksheet_describe')
            ->join('db_meeting on db_worksheet.worksheet_relate_meeting = db_meeting.meeting_id')
            ->where($where)
            ->order('worksheet_id desc')
            ->select();
        return $list;
    }
    /*
     * 获取用户
     * xiao hui
     */
    public function getUser(){
        return D('member')->field('email,name,uid')->where('status = 1')->select();
    }
    public function userPerson($param){
        $work = $this->selectWork($param);
        $user = $work['worksheet_rule_person'];
        $where['uid']=array("in",$user);
        $userEmail = D('member')->field('email,name')->where($where)->select();
        $content = "关于".$work['meeting_name']."会议".$work['worksheet_name']."的工作单进度延迟，请尽快处理，并尽快调整工作单进度状态；";
        $userEmail['content'] = $content;
        return $userEmail;
    }
}

