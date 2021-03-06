<?php

namespace Manage\Controller;

use Manage\Controller\AdminController;

class InternalMeetingController extends AdminController {

    private $mod_internalmeeting;

    public function __construct() {
        parent::__construct();

        $this->mod_internalmeeting = D('InternalMeeting');
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

    /*
     * 首页
     */

    public function index() {
        $param = I();
        if ($param['hiddenform'] == '1') {
            $this->companyGetExecl($param);
        } elseif ($param['hiddenform'] == '2') {
            $this->groupGetExecl($param);
        } else {
            //处理查询条件：会议名称，创建人，开始时间
            $param['internal_name'] != '' ? $where['internal_name'] = array('like', '%' . $param['internal_name'] . '%') : '';
            $param['name'] != '' ? $where['name'] = array('like', '%' . $param['name'] . '%') : '';
            $param['internal_meeting_date'] != '' ? $where['internal_meeting_date'] = array('like', '%' . $param['internal_meeting_date'] . '%') : '';
            $where = $this->escape($where);

            $count = $this->mod_internalmeeting->getInternalCount($where);

            $page = new \Think\Page($count, 10);
            $list = $this->mod_internalmeeting->getList($where, $page->firstRow, $page->listRows);
            foreach ($param as $key => $val) {
                $page->parameter[$key] = $val;
            }
            $show = $page->show();
            $this->assign('list', $list);
            $this->assign('page', $show);
            $this->assign('param', $param);
            $this->assign('s_number', 1); //初始序号
            $this->display();
        }
    }

    /*
     * 添加
     * @author xiao hui
     */

    public function add() {
        if (IS_POST) {
            $param = I('post.');
            $result = $this->mod_internalmeeting->addInternal($param);
            if ($result['code'] == 200) {
                $this->success($result['status'], U('InternalMeeting/index'));
            } else {
                $this->error($result['status'], U('InternalMeeting/index'));
            }
            return true;
        }
        $uid = session('S_USER_INFO.UID');
        $this->assign('uid', $uid);
        $this->display();
    }

    /*
     * 查看详情
     */

    public function details() {
        $id = I('get.id');
        $oneData = $this->mod_internalmeeting->getOneInternalMeeting($id);
        $this->assign('onedata', $oneData);
        $this->display();
    }

    /*
     * 编辑
     */

    public function save() {
        if (IS_POST) {
            $param = I('post.');
            $result = $this->mod_internalmeeting->saveInternal($param);
            if ($result['code'] == 200) {
                $this->success($result['status'], U('InternalMeeting/index'));
            } else {
                $this->error($result['status'], U('InternalMeeting/index'));
            }
        } else {
            $id = I('get.id');
            $oneData = $this->mod_internalmeeting->getOneInternalMeeting($id);
            $this->assign('onedata', $oneData);
            $this->display();
        }
        return true;
    }

    /*
     * 公司导出execl
     */

    public function companyGetExecl($param) {

        $internal = $this->mod_internalmeeting->getExecl($param);

        $headArr = array(
            '项目名称',
            '交办人',
            '交办时间',
            '负责人',
            '会议名称',
            '会议日期',
            '会议地点',
            '会议形式',
            '会议类型',
            '会议级别',
            '会议密集',
            '召集人',
            '主持人',
            '参会人员',
            '工作人员',
            '议程安排',
            '开始时间',
            '结束时间',
            '议程责任人',
            '是否预定会议室',
            '议程报批状况',
            '发送会议通知',
            '通知材料准备',
            '通知服务单位保障',
            '材料收集',
            '通知日期',
            '通知时间',
            '通知截止日期',
            '通知截止时间',
            '违规',
            '违规类别（晚交/质量）',
            '发送会议材料',
            '打印材料',
            '摆放桌牌',
            '背景板设计',
            '背景板',
            '汇报台',
            '测试话筒',
            '测试视频',
            '测试音频',
            '测试翻页器',
            '录音笔',
            '准备茶水',
            '是否出现问题',
            '问题类型',
            '纪要拟写责任人',
            '纪要报批进度',
            '是否已下发',
            '会议材料是否归档',
            '归档接收人',
            '归档时间',
            '改进建议'
        );
        getExcel($headArr, $internal);
    }

    /*
     * 导出集团execl
     */

    public function groupGetExecl($param) {

        $internal = $this->mod_internalmeeting->groupExecl($param);

        $headArr = array(
            '项目名称',
            '交办人',
            '交办时间',
            '负责人',
            '会议名称',
            '会议日期',
            '会议地点',
            '会议形式',
            '会议类型',
            '会议密集',
            '召集人',
            '主持人',
            '参会人员',
            '工作人员',
            '议程安排',
            '开始时间',
            '结束时间',
            '议程责任人',
            '是否预定会议室',
            '议程报批状况',
            '发送会议通知',
            '通知材料准备',
            '通知服务单位保障',
            '材料收集',
            '通知日期',
            '通知时间',
            '通知截止日期',
            '违规',
            '违规类别（晚交/质量）',
            '发送会议材料',
            '打印材料',
            '摆放桌牌',
            '背景板设计',
            '背景板',
            '汇报台',
            '测试话筒',
            '测试视频',
            '测试音频',
            '测试翻页器',
            '录音笔',
            '准备茶水',
            '是否出现问题',
            '问题类型',
            '纪要拟写责任人',
            '纪要报批进度',
            '是否已下发',
            '会议材料是否归档',
            '归档接收人',
            '归档时间',
            '改进建议'
        );
        getExcel($headArr, $internal);
    }

    /*
     * 导入
     */

    public function importExcel() {
        
        if (!empty($_FILES['filename']['name'])) {
            
            $mod_upload = new CommonApi\MeetingUpload();
            $param = $_FILES['filename'];
            $config_info = C();
            $file_config = $config_info['FILE_INTERNALMEETING'];
            $files = $mod_upload->normalUpload($file_config);
            $fileName = $files['rootPath'] . $files['info']['filename']['savepath'] . $files['info']['filename']['savename'];
            $data = importExcel($fileName);
            $res_info_import = $this->mod_internalmeeting->import($data);
            if ($res_info_import['code'] == 200) {
                unlink($fileName);
                $this->success($res_info_import['status'], U('InternalMeeting/index'));
            } else {
                unlink($fileName);
                $this->error($res_info_import['status'], U('InternalMeeting/index'));
            }
        } else {
            $this->display('import');
        }
    } 
    /*
     * 删除
     */

    public function delete($id) {
        $result = $this->mod_internalmeeting->deleteInternal($id);
        if ($result['code'] == 200) {
            $this->success($result['status'], U('InternalMeeting/index'));
        } else {
            $this->error($result['status'], U('InternalMeeting/index'));
        }
    }

}
