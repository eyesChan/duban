/**
 * Created by pactera on 2016/5/12.
 */

$(function () {
    var BasicJS = {
        //基础操作
        init: function () {
            //监听右键事件，创建右键菜单
            $('#tt').tabs({
                onContextMenu: function (e, title, index) {
                    e.preventDefault();
                    if (index >= 0) {
                        $('#mm').menu('show', {
                            left: e.pageX,
                            top: e.pageY
                        }).data("tabTitle", title);
                    }
                }
            });
            //右键菜单click
            $("#mm").menu({
                onClick: function (item) {
                    BasicJS.closeTab(this, item.name);
                }
            });
            function getMenuData() {
                var zNodes;
                $.ajax({
                    url: "/Manage/Index/getMenuData",
                    type: 'post',
                    async: false,
                    success: function (res) {
                        zNodes = res;
                    }
                });
                return zNodes;
            }
            //treeNodes,初始化左侧树形菜单栏

            var zNodes = getMenuData();
            console.log(zNodes);

            // 设置ztree
            var setting = {
                view: {
                    selectedMulti: false,
                    dblClickExpand: false,
                    showLine: false
                },
                callback: {
                    onClick: BasicJS.addTabs
                }
            };
            $.fn.zTree.init($("#webMenu_list"), setting, zNodes);
            // $(document).on("click",".tabs li",function(){
            //     var $html = $(".tabs-selected").find(".tabs-title").html();
            //     var $index = $(this).index();
            //     if($("#webMenu_list_"+$index).find("webMenu_list_"+$index+"_span").html() == $html){
            //         $("#webMenu_list_"+$index+"_a").children("a.level0").addClass("curSelectedNode");
            //     }
            // });
        },
        //添加tab操作
        addTabs: function (event, treeId, treeNode, clickFlag) {
            // 单击展开
            var zTree = $.fn.zTree.getZTreeObj("webMenu_list");
            zTree.expandNode(treeNode);
            if (treeNode.click == false) {   //click为false不可以进行点击
                return;
            } else if (!$('#tt').tabs('exists', treeNode.name)) {
                $('#tt').tabs('add', {
                    id: treeId,
                    title: treeNode.name,
                    selected: true,
                    href: treeNode.dataurl,
                    //content: '<iframe src="' + treeNode.dataurl + '" width="100%" height="100%" frameborder="0"></iframe>',
                    closable: true
                });
            } else
                $('#tt').tabs('select', treeNode.name);

        },

        //关闭tab操作
        closeTab: function (menu, type) {
            var allTabs = $("#tt").tabs('tabs');
            var allTabtitle = [];
            $.each(allTabs, function (i, n) {
                var opt = $(n).panel('options');
                if (opt.closable)
                    allTabtitle.push(opt.title);
            });
            var curTabTitle = $(menu).data("tabTitle");
            var curTabIndex = $("#tt").tabs("getTabIndex", $("#tt").tabs("getTab", curTabTitle));
            switch (type) {
                case 1:
                    $("#tt").tabs("close", curTabIndex);
                    return false;
                    break;
                case 2:
                    for (var i = 0; i < allTabtitle.length; i++) {
                        $('#tt').tabs('close', allTabtitle[i]);
                    }
                    break;
                case 3:
                    for (var i = 0; i < allTabtitle.length; i++) {
                        if (curTabTitle != allTabtitle[i])
                            $('#tt').tabs('close', allTabtitle[i]);
                    }
                    $('#tt').tabs('select', curTabTitle);
                    break;
                case 4:
                    for (var i = curTabIndex; i < allTabtitle.length; i++) {
                        $('#tt').tabs('close', allTabtitle[i]);
                    }
                    $('#tt').tabs('select', curTabTitle);
                    break;
                case 5:
                    for (var i = 0; i < curTabIndex - 1; i++) {
                        $('#tt').tabs('close', allTabtitle[i]);
                    }
                    $('#tt').tabs('select', curTabTitle);
                    break;
                case 6: //刷新
                    var panel = $("#tt").tabs("getTab", curTabTitle).panel("refresh");
                    break;
            }
        }
    };
    BasicJS.init();
});








