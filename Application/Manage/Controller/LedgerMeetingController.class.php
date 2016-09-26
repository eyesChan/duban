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
     *  显示会谈会见台账列表
     */
    public function index() {   
        
        $this->display();
    }
    
     /**
     *  会谈会见台账创建
     */
    public function addLedger() {
         $data = I();
         if(!empty($data)){
             $result = $this->ledger_meeting->addFile($data);
              if ($result['code'] == 200) {
                    $this->success($result['status'], 'index');
                } else {
                    $this->success($result['status'], 'addLedger');
                }
         }
        $this->display();
    }
}
