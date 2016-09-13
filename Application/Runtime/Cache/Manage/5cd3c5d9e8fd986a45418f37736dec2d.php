<?php if (!defined('THINK_PATH')) exit();?>    <link rel="stylesheet" type="text/css" href="/Public/css/moduleManage.css" />
    <div class="container-padding">
        <div class="tb1 jxssp tb1-noborder">
            <form method="POST" action="<?php echo U(add);?>" name="searchForm" id="sform">
                <div class="wrap-table">
                    <table class="tbl">
                        <colgroup>
                            <col width="10%">
                            <col width="80%">
                        </colgroup>
                        <tbody>
                        <tr>
                            <td class="cap"><em>*</em>模块编码：</td>
                            <td colspan="3">
                                <input name="menu_num" type="text" size="21"  class="input-text ml15" id="menu_num">
                                <span class="error-message hide"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="cap"><em>*</em>模块名称：</td>
                            <td colspan="3">
                                <input name="menu_name" type="text" size="21"  class="input-text ml15" id="menu_name">
                                <span class="error-message hide"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="cap"><em>*</em>模块等级：</td>
                            <td colspan="3">
                                <select  id="level" name="level" class="ml15">
                                    <option value="0" selected="selected">请选择</option>
                                    <option value="1-1">顶级目录</option>
                                    <?php if(is_array($module_list)): foreach($module_list as $key=>$vo): ?><option value="2-<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option>
                                        <?php if(is_array($vo["children"])): foreach($vo["children"] as $key=>$vo2): ?><option value="3-<?php echo ($vo2["id"]); ?>">|--<?php echo ($vo2["title"]); ?></option><?php endforeach; endif; endforeach; endif; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="cap"><em>*</em>模块状态：</td>
                            <td colspan="3">
                                <input name="hide" type="radio" value="0" checked class="ml15">显示
                                <input type="radio" name="hide" value="1">隐藏
                            </td>
                        </tr>
                        <tr>
                            <td class="cap"><em>*</em>文件路径：</td>
                            <td colspan="3">
                                <input name="menu_url" type="text" placeholder="Home/Controller/action" size="21"  class="input-text ml15" id="menu_url">
                            </td>
                        </tr>
                        <tr>
                            <td class="cap"><em>*</em>图标路径：</td>
                            <td colspan="3">
                                <input name="map_url" type="text" size="21"  class="input-text ml15" id="map_url">
                            </td>

                        </tr>
                        <tr>
                            <td class="cap">功能描述：</td>
                            <td colspan="3"><textarea name="mark" cols="21" class="input-text ml15" id="mark"></textarea></td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </form>
            <div class="clear">
                <input type="button" value="保存"  onclick="sel1(2)" class="btn btn-a no_disable fl" >
            
            </div>
      </div>
    </div>
    <script type="text/javascript" src="/Public/Js/moduleManage.js"></script>