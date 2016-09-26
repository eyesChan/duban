<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Controller;

use Manage\Controller\CommonApi\MeetingUpload as MeetingUplod;

/**
 *  Description   会谈会见台账管理
 *  @author       huanggang <gang.huang2@pactera.com>
 *  Date          2016/09/26
 */
class LedgerMeetingController extends AdminController {
    
    private $ledger_meeting;
    public function __construct() {
        parent::__construct();
        $this->ledger_meeting = D('LedgerMeeting');
    }
    
    /**
     * 对数组进行转义
     * 
     * @param array 需要转义的数组
     * @return array 返回转义后的数组
     */
    public function escape($param) {
        foreach ($param as $k => $val) {
            $param[$k] = str_replace("_", "\_", $val);
        }

        return $param;
    }
    /**
     *  显示会谈会见台账列表
     */
    public function index() {   
        $param = I();
        //处理查询条件：会谈名称、主持人、日期
        $param['led_meeting_name'] != '' ? $where['led_meeting_name'] = array('like', '%' . $param['led_meeting_name'] . '%') : '';
        $param['led_meeting_host'] != '' ? $where['led_meeting_host'] = array('like', '%' . $param['led_meeting_host'] . '%') : '';
        if (!empty($param['led_meeting_date'])) {
            $where['led_meeting_date'] = array('EQ', $param['led_meeting_date']);
        }
        $where['led_status'] = array('EQ', '0');
        $where = $this->escape($where);
        $count = $this->ledger_meeting->getLedgerCount($where);
        $page = new \Think\Page($count, 5);
        $list = $this->ledger_meeting->getLedgerList($where, $page->firstRow, $page->listRows);
        foreach ($param as $key => $val) {
            $page->parameter[$key] = $val;
        }
        $show = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('param', $param);
        $this->display();
    }
    
     /**
     *  会谈会见台账创建
     */
    public function addLedger() {
         $data = I();
         if(!empty($data)){
             $result = $this->ledger_meeting->addLedger($data);
              if ($result['code'] == 200) {
                    $this->success($result['status'], 'index');
                } else {
                    $this->success($result['status'], 'addLedger');
                }
         }
        $this->display();
    }
    
    /*
     * 会谈会见台账详情
     * 
     */
    public function detailsLedger(){
        $led_meeting_id = I('led_meeting_id');
        $result = $this->ledger_meeting->detailsLedger($led_meeting_id);
        $this->assign('list', $result);
        $this->display();
    }
    
    /*
     * 
     * 会谈会见台账编辑
     */
    public function saveLedger(){
         if(IS_GET){
             $led_meeting_id = I('led_meeting_id');
             $result = $this->ledger_meeting->detailsLedger($led_meeting_id);
             $this->assign('list', $result);
             $this->display();
         }
         if(IS_POST){
             $data=I();
             if(!empty($data)){
                $result = $this->ledger_meeting->saveLedger($data,$data['led_meeting_id']);
                if ($result['code'] == 200) {
                       $this->success($result['status'], U('LedgerMeeting/index'));
                  }else{
                       $this->success($result['status'], U('LedgerMeeting/addLedger'));
                }
            }
         }       
    }
    
    /*
     * 会谈会见删除
     */
    public function delLedger(){
        $led_meeting_id = I('led_meeting_id');
        $result = $this->ledger_meeting->delLedger($led_meeting_id);
        if ($result['code'] == 200) {
             $this->success($result['status'], U('LedgerMeeting/index'));
          }else{
             $this->success($result['status'], U('LedgerMeeting/index'));
        }
    
    }
}
