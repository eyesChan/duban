 var rightBtn = '<input class="btn btn-red mr10 submit rightBtn" type="button" onclick="openNew()" value="会谈会见台账创建"/>';
            $(rightBtn).appendTo(".location");
            function openNew(){
            location.href = '/Manage/LedgerMeeting/addLedger';
                
        }

$('#export').click(function(){
   var  led_meeting_name = $('#led_meeting_name').val();
   var  led_meeting_host = $('#led_meeting_host').val();
   var  led_meeting_date = $('#date8').val();
   $.post('/Manage/LedgerMeeting/exportLedgerMeeting',{led_meeting_name:led_meeting_name,led_meeting_host:led_meeting_host,led_meeting_date:led_meeting_date},function(obj){
   })
   
})