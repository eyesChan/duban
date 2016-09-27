 var rightBtn = '<input class="btn btn-red mr10 submit rightBtn" type="button" onclick="openNew()" value="文稿台账创建"/>';
            $(rightBtn).appendTo(".location");
            function openNew(){
            location.href = '/Manage/Presentation/addPresent';
                
        }

//$('#export').click(function(){
//   var  led_meeting_name = $('#led_meeting_name').val();
//   var  led_meeting_host = $('#led_meeting_host').val();
//   var  date8 = $('#date8').val();
//   $.post('/Manage/LedgerMeeting/exportLedgerMeeting',{led_meeting_name:led_meeting_name,led_meeting_host:led_meeting_host,date8:date8},function(obj){
//   })
//   
//})

