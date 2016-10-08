 var rightBtn = '<input class="btn btn-red mr10 submit rightBtn" type="button" onclick="openNew()" value="创建驻各地发展情况台账" style="width:180px"/>';
            $(rightBtn).appendTo(".location");
            function openNew(){
            location.href = '/Manage/ResidentMeeting/addResident';
                
        }

$('#export').click(function(){
   
   var  resident_country = $('#resident_country').val();
   var  resident_person = $('#resident_person').val();
   var  resident_collect_time = $('#date8').val();
   $.post('/Manage/ResidentMeeting/exportResident',{resident_country:resident_country,resident_person:resident_person,resident_collect_time:resident_collect_time},function(obj){
   });
   
})

