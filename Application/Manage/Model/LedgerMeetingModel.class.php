<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Model;

use Think\Model;

/**
 * Description of LedgerMeetingModel
 *
 * @author huanggang
 * @Date    2016/09/26
 */
class LedgerMeetingModel  extends Model{
    
    protected $trueTableName = 'db_led_meeting';
 /*
  * 会谈会见创建
  * 
  * @ahthor huanggang
  * @return object 添加成功或失败
  */
     public function addFile($param){
            $led_meeting = M('led_meeting');
            $res = $led_meeting->add($param);
            if($res){
                return C('COMMON.SUCCESS_EDIT');
            }else{
                return C('COMMON.ERROR_EDIT');
            } 
    }
}
