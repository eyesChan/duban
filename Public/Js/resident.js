$('#export').click(function(){
   
   var  resident_country = $('#resident_country').val();
   var  resident_person = $('#resident_person').val();
   var  resident_collect_time = $('#date8').val();
   $.post('/Manage/ResidentMeeting/exportResident',{resident_country:resident_country,resident_person:resident_person,resident_collect_time:resident_collect_time},function(obj){
   });
   
})

