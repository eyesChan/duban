<?php

namespace Manage\Controller;

use Manage\Controller\AdminController;

/**
 * 签约仪式台账信息：列表展示、按条件查询、添加、编辑、修改状态、删除（修改状态）
 *
 * @author chengyayu
 */
class CeremoneyAccountController extends AdminController {

    private $mod_ceremoney_account;

    public function __construct() {
        parent::__construct();
        $this->mod_ceremoney_account = D('CeremoneyAccount');
    }

    /**
     * 查询列表展示
     */
    public function index() {

        $params = I('param.');
        if ($params['flag_search_form'] == 1) {
            $this->makeExcel($params);
        } else {
            $data_for_list = $this->mod_ceremoney_account->getDataForList($params);
            $this->assign('list', $data_for_list['ceremoney_account']);
            $this->assign('page', $data_for_list['page_show']);
            $this->assign('remember_search', $params);
            $this->assign('s_number', 1); //初始序号
            $this->display();
        }
    }

    /**
     * 添加
     */
    public function add() {

        if (IS_POST) {
            $params = I('param.');
            $res_info_add = $this->mod_ceremoney_account->doAdd($params);
            if ($res_info_add['code'] == 200) {
                $this->success($res_info_add['status'], U('CeremoneyAccount/index'));
            } else {
                $this->error($res_info_add['status'], U('CeremoneyAccount/index'));
            }
        } else {
            $this->display('add');
        }
    }

    /**
     * 编辑
     */
    public function edit() {

        if (IS_POST) {
            $params = I('param.');
            $res_info_edit = $this->mod_ceremoney_account->doEdit($params);
            if ($res_info_edit['code'] == 200) {
                $this->success($res_info_edit['status'], U('CeremoneyAccount/index'));
            } else {
                $this->error($res_info_edit['status'], U('CeremoneyAccount/index'));
            }
        } else {
            $ca_id = I('param.ca_id');
            $data_for_edit = $this->mod_ceremoney_account->getDataById($ca_id);
            $this->assign('ca_info', $data_for_edit);
            $this->display('edit');
        }
    }

    /**
     * 详情
     */
    public function detail() {

        $ca_id = I('param.ca_id');
        $data_for_edit = $this->mod_ceremoney_account->getDataById($ca_id);
        $this->assign('ca_info', $data_for_edit);
        $this->display('detail');
    }

    /**
     * 删除（修改状态为0）
     */
    public function delete() {

        $ca_id = I('ca_id');
        $res_info_delete = $this->mod_ceremoney_account->doDelete($ca_id);
        if ($res_info_delete['code'] == 200) {
            $this->success($res_info_delete['status'], U('CeremoneyAccount/index'));
        } else {
            $this->error($res_info_delete['status'], U('CeremoneyAccount/index'));
        }
    }

    /**
     * 导出excel
     */
    public function makeExcel($params) {

        $data_ca = $this->mod_ceremoney_account->getExecl($params);

        $headArr = array(
            '时间',
            '地点',
            '签约名称',
            '签约双方',
            '主持人',
            '发言领导',
            '证签领导',
            '签约领导',
            '参会人员',
            '密级',
            '着装',
            '保障人员',
            '保障时长',
            '议程责任人',
            '议程报批情况',
            '协议责任人',
            '协议报批情况',
            '发送会议通知',
            '通知材料准备',
            '通知服务单位保障',
            '准备会议议程',
            '准备领导讲话稿',
            '准备来访人简历',
            '准备主持人词',
            '准备签约文本',
            '准备电脑',
            '准备录音笔',
            '准备麦克风',
            '准备宣传材料',
            '准备签约文本',
            '准备签约笔',
            '准备签约音乐',
            '准备香槟',
            '礼品',
            '准备桌花',
            '制作背景板',
            '制作签约台卡',
            '制作KT板',
            '准备桌牌',
            '准备静帧画面',
            '准备易拉宝',
            '完成场地布置',
            '完成背景板搭建',
            '测试话筒',
            '测试音频',
            '通知接待保障',
            '接待责任人',
            '保障人员',
            '传译人员',
            '是否差错',
            '原因分析',
            '拟写今日海航',
            '发文时间',
            '责任人',
            '交接单位',
            '存档地址',
            '改进建议',
        );
        getExcel($headArr, $data_ca);
    }

    /**
     * 导入excel
     */
    public function importExcel() {

        if (!empty($_FILES['filename'])) {
            $mod_upload = new CommonApi\MeetingUpload();
            $param = $_FILES['filename'];
            $files = $mod_upload->normalUpload($param);
            $fileName = $files['rootPath'] . $files['info']['filename']['savepath'] . $files['info']['filename']['savename'];
            $data = importExcel($fileName);
            $res_info_import = $this->mod_ceremoney_account->import($data);
            if ($res_info_import['code'] == 200) {
                unlink($fileName);
                $this->success($res_info_import['status'], U('CeremoneyAccount/index'));
            } else {
                unlink($fileName);
                $this->error($res_info_import['status'], U('CeremoneyAccount/index'));
            }
        } else {
            $this->display('import');
        }
    }

}
