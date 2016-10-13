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
            $users = implode(',',$param['undefined']);
            $data['worksheet_rule_person'] = $users;
            $data['worksheet_rule_name'] = $this->showUsers($users);
            $data['worksheet_describe'] = $param['worksheet_describe'];
            $data['worksheet_done_persent'] = 0;
            $data['worksheet_state'] = $workDay['state'];
            $data['worksheet_detele'] = 1;
            $data['worksheet_date'] = $workDay['days'];
            $data['worksheet_parcent_day'] = $workDay['parcent'];
            $data['worksheet_state_id'] = $workDay['state_id'];
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
                ->where('meeting_state = 1')
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
              ->field('name,worksheet_rule_name,worksheet_state,worksheet_state_id,worksheet_id,worksheet_end_date,worksheet_name,worksheet_rule_person,worksheet_done_persent,worksheet_state,meeting_name,worksheet_abandoned_reason,worksheet_describe,worksheet_creat_person')
              ->join('db_meeting on db_worksheet.worksheet_relate_meeting = db_meeting.meeting_id')
              ->join('db_member on db_worksheet.worksheet_rule_person = db_member.uid')
              ->where("worksheet_id = $param")
              ->find();
        
        $users = $work['worksheet_rule_person'];
        $work['username'] = $this->selectUsers($users);
        return $work;
    }
    
    /*
     * 查询姓名
     */
    public function selectUsers($users){
        $where['uid']=array("in",$users);
        $users = D('member')->where($where)->getField('uid,name');
        return $users;
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
        $usernew = $this->usersselect($param['worksheet_rule_person'],$param['undefined']);
        $data['worksheet_rule_person'] = $usernew;
        $data['worksheet_done_persent'] = $param['worksheet_done_persent']; 
        $states = $this->workState($param['worksheet_state'],$param['worksheet_id'],$param['worksheet_done_persent']);
        $data['worksheet_state'] = $states['state'];
        $data['worksheet_state_id'] = $states['id'];
        $data['worksheet_rule_name'] = $this->showUsers($usernew);
       // echo $data['worksheet_rule_name'];die;
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
     * 查询责任人加入库
     */
     public function showUsers($usernew){
        //header("Content-Type:text/html; charset=UTF-8");
        $where['uid']=array("in",$usernew);
        $users = D('member')->field('name')->where($where)->select();
        //P($users);die;
         foreach($users as $key=>$val){
            $workuser .="," .$val['name']; 
            $workuser = ltrim($workuser,",");
       }
      
        return $workuser;
    }
    /*
     * 编辑拼接责任人
     */
    public function usersselect($params,$newparam){
        for($i=0;$i<=count($params);$i++){
            $newuser .= ",".$params[$i];
            $newusers = ltrim($newuser,",");
            $newusers = rtrim($newusers,",");
        }
        for($i=0;$i<=count($newparam);$i++){
            $newuserss.= ",".$newparam[$i];
            $newusersss = rtrim($newuserss,",");
        }
        $enduser = $newusers.$newusersss;
        return $enduser;
    }
    
    /*
     * 计算单个工单几天和每天%
     * @author xiaohui
     * $start 开始时间
     * $stop 结束时间
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
            $workDay['state_id'] = 2;
        }
        elseif($catime < $time-80000){
            $workDay['state'] = "延迟";
            $workDay['state_id'] = 3;
        }
        else{
            $workDay['state'] = "正常";
            $workDay['state_id'] = 6;
        }
        return $workDay;
    }
    /*
     * 判断当前状态
     * @state 是废弃和挂起
     * @$id 工作单id
     * @parcent 完成工作比
     * 
     */
    public function workState($state,$id,$parcent){
       
        $states = array();
        if($parcent == "100" || $parcent == "100%"){
            $states['state'] = "办结";
            $states['id'] = 1;
            return $states;
        }else{
            $work = D('worksheet')
                    ->field('worksheet_parcent_day,worksheet_end_date,worksheet_state,worksheet_start_date,worksheet_done_persent')
                    ->where("worksheet_id = $id")
                    ->find();
            if($state == '0'){
                $time = time();
                $starttime = strtotime($work['worksheet_start_date']);
                $cation =  $time - $starttime;
                $day = floor($cation/3600/24);
                $surplus = $day * $work['worksheet_parcent_day'];
                if($parcent >= $surplus){
                    $states['state'] = "正常";
                    $states['id'] = 6;
                    return $states;
                }else{
                    $states['state'] = "延迟";
                    $states['id'] = 3;
                    return $states;
                }   
            }
 
            elseif($state == '1' || $state == '2'){
                $time = time();
                $stoptime = strtotime($work['worksheet_end_date']);
                $starttime = strtotime($work['worksheet_start_date']);
                if($stoptime > $time){
                    $cation =  $time - $starttime;
                    $day = floor($cation/3600/24);
                    $surplus = $day * $work['worksheet_parcent_day'];
                    $sum = $surplus + $work['worksheet_done_persent'];    
                    
                    if($starttime > $time){        
                        $states['state']="未启动";
                        $states['id'] = 2;
                        return $states;
                    }
                    elseif ($day == '0') {
                        $states['state'] = "正常";
                        $states['id'] = 6;
                        return $states;
                    }else{
                        if($sum < 100){
                            $states['state'] = "延迟";
                            $states['id'] = 3;
                            return $states;
                        }
                    
                    else{
                            $states['state'] = "正常";
                            $states['id'] = 6;
                            return $states;
                        }
                    }
                }else{
                    $states['state'] = "延迟";
                    $states['id'] = 3;
                    return $states;
                }
            }
            
        }
        $states['state'] = $state;
        $states['id'] = 4;
        return $states;
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
                    $state_id = 1;
                    $this->saveOneOrder($val['worksheet_id'],$state,$state_id);
                }
            }
            if($val['worksheet_state'] == "正常"){
                $time = time();
                $starttime = strtotime($val['worksheet_start_date']);
                $cation =  $time - $starttime;
                $day = floor($cation/3600/24);
                $sum = $day * $val['worksheet_parcent_day'];
                if($val['worksheet_done_persent'] >= $sum){
                    $state = "正常";
                    $state_id = 6;
                    $this->saveOneOrder($val['worksheet_id'],$state,$state_id);
                }else{
                    $state = "延迟";
                    $state_id = 3;
                    $this->saveOneOrder($val['worksheet_id'],$state,$state_id);
                }
            }
        }
        return $list;        
    }
    /*
     * 修改单个状态
     * @author xiao hui
     */
    public function saveOneOrder($id,$state,$state_id){
        $data['worksheet_state'] = $state;
        $data['worksheet_state_id'] = $state_id;
        return D('worksheet')->where("worksheet_id = $id")->save($data);
    }
    /*
     * 导出execl 查询
     * @author xiao hui
     */
    public function getOrderExcel($param){
        $param['worksheet_name'] != '' ? $where['worksheet_name'] = array('like', '%' . $param['worksheet_name'] . '%') : '';
        $param['meeting_name'] != '' ? $where['meeting_name'] = array('like', '%' . $param['meeting_name'] . '%') : '';
        $param['worksheet_rule_person'] != '' ? $where['worksheet_rule_person'] = array('like', '%' . $param['worksheet_rule_person'] . '%') : '';
        $param['worksheet_start_date'] != '' ? $where['worksheet_start_date'] = array('like', '%' . $param['worksheet_start_date'] . '%') : '';
        $order = M('worksheet');
        $where['worksheet_detele'] = 1;
        $list = $order
            ->field('worksheet_name,meeting_name,worksheet_rule_name,worksheet_end_date,worksheet_describe,worksheet_state,worksheet_abandoned_reason,worksheet_done_persent')
            ->join('db_meeting on db_worksheet.worksheet_relate_meeting = db_meeting.meeting_id')
            ->join('db_member on db_worksheet.worksheet_rule_person = db_member.uid')
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
    /*
     * 发送邮件
     * 
     */
    public function userPerson($param){
        $work = $this->selectWork($param);
        $user = $work['worksheet_rule_person'];
        $where['uid']=array("in",$user);
        $userEmail = D('member')->field('email,name')->where($where)->select();
        $content = "关于“".$work['meeting_name']."”会议“".$work['worksheet_name']."”的工作单进度延迟,请在".substr($work['worksheet_end_date'],0,11)."尽快处理，并尽快调整工作单进度状态；";
        $userEmail['content'] = $content;
        return $userEmail;
    }
}
