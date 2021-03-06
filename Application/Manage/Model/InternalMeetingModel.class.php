<?php
namespace Manage\Model;

use Think\Model;

/**
 * 内部会议模型类：增、删、改、查、导入、导出、统计数量
 * @author xiaohui
 * @Date    2016/09/18
 */
class InternalMeetingModel extends Model {

    protected $tableName = 'internalmeeting';

    /*
     * 添加
     */
    public function addInternal($param) {

        $internal = M('internalmeeting');
        $data = $param;
        $data['internal_username'] = session('S_USER_INFO.UID');
        //处理时间如果为空去除
        $data = $this->timeDispose($data);

        if ($internal->add($data)) {
            return C('COMMON.SUCCESS_ADD');
        } else {
            return C('COMMON.ERROR_ADD');
        }
    }

    /*
     * 处理时间
     */
    public function timeDispose($data) {
        foreach ($data as $key => $val) {
            if (empty($val)) {
                $data[$key] = NULL;
            }
        }
        return $data;
    }

    /*
     * 统计数量
     */
    public function getInternalCount($where) {
        $where['internal_delete'] = 1;
        $count = D('internalmeeting')
                        ->join('db_member on db_internalmeeting.internal_username = db_member.uid')
                        ->where($where)->count();
        return $count;
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
        $order = M('internalmeeting');
        $where['internal_delete'] = 1;
        $list = $order
                ->join('db_member on db_internalmeeting.internal_username = db_member.uid')
                ->where($where)
                ->limit($first_rows, $list_rows)
                ->order('internal_id desc')
                ->select();
        return $list;
    }

    /*
     * 查询单个内部会议
     * @author xiaohui
     * @return array 成功返回列表
     */
    public function getOneInternalMeeting($id) {
        $internal = D('internalmeeting')
                ->where("internal_id = $id")
                ->find();
        return $internal;
    }

    /**
     * 获取查询条件数组
     * 
     * @param array $params
     * @return array
     */
    public function makeWhereForSearch($params) {

        $where = array();

        $where['internal_delete'] = 1; //排除‘已删除状态’
        if (!empty($params['internal_name'])) {
            $where['internal_name'] = array('LIKE', "%" . $params['internal_name'] . "%");
        }
        if (!empty($params['name'])) {
            $where['name'] = array('LIKE', "%" . $params['name'] . "%");
        }
        if (!empty($params['internal_meeting_date'])) {
            $where['internal_meeting_date'] = date('Y-m-d', strtotime($params['internal_meeting_date']));
        }

        return $where;
    }

    /*
     * 导出公司execl
     */
    public function getExecl($params) {

        $where = $this->makeWhereForSearch($params);
        $internal = D('internalmeeting')->where($where)->order('internal_id desc')->select();
        $count = count($internal);
        for ($i = 0; $i <= $count; $i++) {//去除不要的数据
            unset($internal[$i]['internal_id']);
            unset($internal[$i]['internal_username']);
            unset($internal[$i]['internal_delete']);
            unset($internal[$i]['internal_save_time']);
            unset($internal[$i]['internal_add_time']);
        }
        return $internal;
    }

    /*
     * 导出集团execl
     */
    public function groupExecl($params) {

        $where = $this->makeWhereForSearch($params);
        $internal = D('internalmeeting')->where($where)->order('internal_id desc')->select();
        $count = count($internal);
        for ($i = 0; $i <= $count; $i++) {//去除不要的数据
            unset($internal[$i]['internal_username']);
            unset($internal[$i]['internal_id']);
            unset($internal[$i]['internal_meeting_level']);
            unset($internal[$i]['internal_notice_start_time']);
            unset($internal[$i]['internal_delete']);
            unset($internal[$i]['internal_save_time']);
            unset($internal[$i]['internal_add_time']);
        }
        return $internal;
    }

    /**
     *  普通文件上传
     * @param file_size 文件大小
     * @param file_path 文件路径
     * @param allow_file 允许格式
     * @author xiaohui
     * @return array
     * @date 16/09/20
     */
    public function normalUpload($param = array()) {
        if (empty($param)) {
            $result = C('COMMON.PARAMTER_ERROR');
            return $result;
        }
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = $param['FILE_SIZE']; // 设置附件上传大小
        $upload->exts = $param['ALLOW_FILE']; // 设置附件上传类型
        $upload->rootPath = C('FILE_ROOT_PATH'); // 设置附件上传根目录
        $upload->savePath = $param['FILE_PATH']; // 设置附件上传（子）目录
        // 上传文件 
        $info = $upload->upload();
      
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
            return C('COMMON.UPLOAD_ERROR');
        } else {// 上传成功 获取上传文件信息
            $result = C('COMMON.UPLOAD_SUCCESS');
            $result['info'] = $info;
            $result['rootPath'] = $upload->rootPath;
            return $result;
        }
    }

    /*
     * 导入
     */
    public function import($data) {
        $count = count($data);
        $flag = 0;
        $model = new Model();
        $model->startTrans();
        $internal = M('internalmeeting');
        $dateMod = new \PHPExcel_Shared_Date();
        for ($i = 0; $i < $count; $i++) {

            $info['internal_name'] = $data[$i][0];
            $info['internal_assigned_person'] = $data[$i][1];
            $info['internal_assigned_date'] = $data[$i][2];
            $info['internal_responsible_person'] = $data[$i][3];
            $info['internal_meeting_name'] = $data[$i][4];
            $info['internal_meeting_date'] = $data[$i][5];
            $info['internal_meeting_place'] = $data[$i][6];

            $info['internal_meeting_form'] = $data[$i][7];
            $info['internal_meeting_type'] = $data[$i][8];
            $info['internal_meeting_level'] = $data[$i][9];
            $info['internal_meeting_dense'] = $data[$i][10];
            $info['internal_call_man'] = $data[$i][11];
            $info['internal_host'] = $data[$i][12];
            $info['internal_participants'] = $data[$i][13];
            $info['internal_work_ren'] = $data[$i][14];
            $info['internal_meeting_arrange'] = $data[$i][15];
            $info['internal_meeting_start_date'] = $data[$i][16];
            $info['internal_meeting_stop_date'] = $data[$i][17];
            $info['internal_meeting_responsible_person'] = $data[$i][18];
            $info['internal_reserve_name'] = $data[$i][19];
            $info['internal_meeting_bao_state'] = $data[$i][20];
            $info['internal_send_meeting_notice'] = $data[$i][21];
            $info['internal_notice_ready'] = $data[$i][22];
            $info['internal_notice_company'] = $data[$i][23];
            $info['internal_material_person'] = $data[$i][24];
            $info['internal_notice_start_date'] = $data[$i][25];
            $info['internal_notice_start_time'] = date('H:i:s', $dateMod->ExcelToPHP($data[0][26]) - 3600 * 8);
            $info['internal_notice_stop_date'] = $data[$i][27];
            $info['internal_notice_stop_time'] = date('H:i:s', $dateMod->ExcelToPHP($data[0][28]) - 3600 * 8);
            $info['internal_misdeed'] = $data[$i][29];
            $info['internal_misdeed_type'] = $data[$i][30];
            $info['internal_get_meeting_person'] = $data[$i][31];
            $info['internal_print_person'] = $data[$i][32];
            $info['internal_bai_person'] = $data[$i][33];
            $info['internal_bei_person'] = $data[$i][34];
            $info['internal_beiban'] = $data[$i][35];
            $info['internal_hui_person'] = $data[$i][36];
            $info['internal_cehua_person'] = $data[$i][37];
            $info['internal_cevideo_person'] = $data[$i][38];
            $info['internal_cemp_person'] = $data[$i][39];
            $info['internal_cefan_person'] = $data[$i][40];
            $info['internal_recorder_person'] = $data[$i][41];
            $info['internal_ready_person'] = $data[$i][42];
            $info['internal_whether_problem'] = $data[$i][43];
            $info['internal_problem_type'] = $data[$i][44];
            $info['internal_summary_person'] = $data[$i][45];
            $info['internal_summary_speed'] = $data[$i][46];
            $info['internal_whether_issued'] = $data[$i][47];
            $info['internal_meeting_speed'] = $data[$i][48];
            $info['internal_file_person'] = $data[$i][49];
            $info['internal_proposal'] = $data[$i][51];
            $info['internal_file_time'] = $data[$i][50];
            $info['internal_username'] = session('S_USER_INFO.UID');

            $res_add = $internal->add($info);
            if ($res_add === FALSE) {
                $flag = $flag - 1;
                break;
            }
        }
        if ($flag < 0) {
            $model->rollback();
            writeOperationLog('导入“' . 'excel表格' . '”', 0);
            return C('COMMON.IMPORT_ERROR');
        } else {
            $model->commit();
            writeOperationLog('导入“' . 'excel表格' . '”', 1);
            return C('COMMON.IMPORT_SUCCESS');
        }
    }

    /*
     * 修改
     */
    public function saveInternal($param) {
        $internal = M('internalmeeting');
        $data = $param;
        $data['internal_username'] = session('S_USER_INFO.UID');
        $data = $this->timeDispose($data);

        $where['internal_id'] = $param['internal_id'];
        $res = $internal->where($where)->save($data);
        if ($res) {
            return C('COMMON.SUCCESS_EDIT');
        } else {
            return C('COMMON.ERROR_EDIT');
        }
    }

    /*
     * 删除
     */
    public function deleteInternal($id) {
        $data['internal_delete'] = 0;
        $where['internal_id'] = $id;
        $internal = M('internalmeeting')->where($where)->save($data);
        if ($internal) {
            return C('COMMON.SUCCESS_DEL');
        } else {
            return C('COMMON.ERROR_DEL');
        }
    }

}
