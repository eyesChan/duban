$('#export').click(function(){
   
   var  db_pre_name = $('#db_pre_name').val();
   var  db_assign_name = $('#db_assign_name').val();
   var  db_assign_date = $('#date8').val();
   $.post('/Manage/Presentation/exportPresent',{db_pre_name:db_pre_name,db_assign_name:db_assign_name,db_assign_date:db_assign_date},function(obj){
   });
   
})
$('#export1').click(function(){
   
   var  db_pre_name = $('#db_pre_name').val();
   var  db_assign_name = $('#db_assign_name').val();
   var  db_assign_date = $('#date8').val();
   $.post('/Manage/Presentation/exportPresents',{db_pre_name:db_pre_name,db_assign_name:db_assign_name,db_assign_date:db_assign_date},function(obj){
   });
   
})
   

