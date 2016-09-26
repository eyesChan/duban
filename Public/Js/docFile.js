
    var rightBtn = '<input class="btn btn-red mr10 submit rightBtn" type="button" onclick="openNew()" value="文档发布创建"/>';
    $(rightBtn).appendTo(".location");
    function openNew(){
        location.href = '/Manage/File/addFile';
    }

    //撤回操作
    function withdraw(id){
       var page= $('#go_page').val();
       $.post('/Manage/File/delFile',{doc_id:id},function(obj){
           // location.href = '{:U(index)}'; 
           if(obj==1){
               alert('撤回成功');
               location.href = '/Manage/File/index';
           }
       })
    }
    
    

