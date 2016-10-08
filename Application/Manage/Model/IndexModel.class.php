<?php

namespace Manage\Model;

use Think\Model;

/**
 * 查询首页数据
 *
 * @author chengyayu
 */
class IndexModel extends Model {

    protected $trueTableName = 'db_meeting';
    private static $FLAG_MEETING_NUM_TODAY = 1;
    private static $FLAG_MEETING_NUM_MONTH = 2;
    private static $FLAG_MEETING_NUM_CREATE = 3;
    private static $FLAG_DOC_NUM_PUBLIC = 1;
    private static $FLAG_DOC_NUM_DELETE = 2;
    private static $FLAG_WORKSHEET_TODO = 1;
    private static $FLAG_WORKSHEET_DO = 2;
    private static $FLAG_WORKSHEET_DONE = 3;
    private static $FLAG_WORKSHEET_GUAQI = 4;
    private static $FLAG_WORKSHEET_FEIQI = 5;

    public function getDataForList() {

        $mod_meeting = M('meeting');
        $user_id = UID;
        
        $list['meeting']['num_today'] = $this->getNumMeeting(self::$FLAG_MEETING_NUM_TODAY); //当天所有会议数量
        $list['meeting']['num_month'] = $this->getNumMeeting(self::$FLAG_MEETING_NUM_MONTH); //当月所有会议数量
        $list['meeting']['num_create'] = $this->getNumMeeting(self::$FLAG_MEETING_NUM_CREATE); //所创建的会议数量
        
        $list['doc']['num_pub'] = $this->getNumDoc(self::$FLAG_DOC_NUM_PUBLIC);//所有发布状态文档数量
        $list['doc']['num_delete'] = $this->getNumDoc(self::$FLAG_DOC_NUM_DELETE);//所有撤回状态文档数量
        
        $list['worksheet']['num_todo'] = $this->getNumWorksheet(self::$FLAG_WORKSHEET_TODO);//待处理的工作单数量
        $list['worksheet']['num_do'] = $this->getNumWorksheet(self::$FLAG_WORKSHEET_DO);//已处理的工作单数量
        $list['worksheet']['num_done'] = $this->getNumWorksheet(self::$FLAG_WORKSHEET_DONE);//已完成的工作单数量
        $list['worksheet']['num_guaqi'] = $this->getNumWorksheet(self::$FLAG_WORKSHEET_GUAQI);//挂起状态的工作单数量
        $list['worksheet']['num_feiqi'] = $this->getNumWorksheet(self::$FLAG_WORKSHEET_FEIQI);//废弃状态的工作单数量
        
        return $list;
    }

    //会议相关数量计算
    public function getNumMeeting($flag) {

        $mod_meeting = M('meeting');
        $where['meeting_state'] = 1; //未被删除的会议
        switch ($flag) {
            case self::$FLAG_MEETING_NUM_TODAY:
                $where['meeting_date'] = date('Y-m-d');
                break;
            case self::$FLAG_MEETING_NUM_MONTH:
                $month_firstday = date('Y-m-01', strtotime(date("Y-m-d")));
                $month_lastday = date('Y-m-d', strtotime("$month_firstday +1 month -1 day"));
                $where['meeting_date'] = array(BETWEEN, array($month_firstday, $month_lastday));
                break;
            case self::$FLAG_MEETING_NUM_CREATE:
                break;
            default :
                break;
        }
        $count = $mod_meeting->where($where)->count();
        return $count;
    }

    //工作单相关数量计算
    public function getNumWorksheet($flag) {
        
        $mod_worksheet = M('worksheet');
        $where['worksheet_detele'] = 1; //未被删除的会议
        switch ($flag) {
            case self::$FLAG_WORKSHEET_TODO:
                $where['worksheet_state_id'] = 2;//未启动
                break;
            case self::$FLAG_WORKSHEET_DO:
                $where['worksheet_state_id'] = array('NEQ',2);//已处理
                break;
            case self::$FLAG_WORKSHEET_DONE:
                $where['worksheet_state_id'] = 1;//已完成
                break;
            case self::$FLAG_WORKSHEET_GUAQI:
                $where['worksheet_state_id'] = 4;//挂起
                break;
            case self::$FLAG_WORKSHEET_FEIQI:
                $where['worksheet_state_id'] = 5;//废弃
                break;
            default :
                break;
        }
        $count = $mod_worksheet->where($where)->count();
        return $count;
    }
    
    //文档相关数量计算
    public function getNumDoc($flag){
        
        $mod_doc = M('doc');
        switch ($flag) {
            case self::$FLAG_DOC_NUM_PUBLIC:
                $where['doc_status'] = 1;//发布状态
                break;
            case self::$FLAG_DOC_NUM_DELETE:
                $where['doc_status'] = 0;//撤回状态
                break;
            default :
                break;
        }
        $count = $mod_doc->where($where)->count();
        return $count;
    }
        

}
