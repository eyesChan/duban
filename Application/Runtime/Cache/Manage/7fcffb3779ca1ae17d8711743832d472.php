<?php if (!defined('THINK_PATH')) exit();?>    <div class="container-padding">
        <div class="wrap-table mt10">
            <form method="POST" action="<?php echo U(lst);?>" name="listForm">
                <div class="wrap-table">
                    <table class="tbl tbl-list">
                        <colgroup>
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="20%">
                            <col width="20%">
                            <col width="30%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>排序</th>
                                <th>ID</th>
                                <th>父ID</th>
                                <th>模块名称</th>
                                <th>模块地址</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($module_list as $k => $v): ?>
                            <tr>
                                <td><?php echo $v['sort']; ?></td>
                                <td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['pid']; ?></td>
                                <td><?php echo $v['title']; ?></td>
                                <td><?php echo $v['name']; ?></td>
                                <td>
                                     <a class="fl mr5 moduleAss" href="javascript:;" address="<?php echo U('child?pid='.$v['id']);?>">
                                        <input type="button" class="btn btn-c btn-xl button" value="关联子项">
                                    </a>
                                     <a class="fl operate-btn mr5 moduleDel" title="删除" onClick="return confirm('确定要删除吗？');" href="javascript:;" address="<?php echo U('delete?id='.$v['id']);?>">
                                        <input type="button" class="btn btn-c btn-xl button" value="删除">
                                    </a>
                                   <a class="fl operate-btn mr5 moduleEdit" title="编辑" href="javascript:;" address="<?php echo U('save?id='.$v['id']);?>">
                                        <input type="button" class="btn btn-c btn-xl button" value="编辑">
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="pagination">
                <ul>
                    <?php echo ($page); ?>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/Public/Js/baseOperate.js"></script>
    <script type="text/javascript" src="/Public/Js/moduleManage.js"></script>