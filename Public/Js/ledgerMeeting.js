 var rightBtn = '<input class="btn btn-red mr10 submit rightBtn" type="button" onclick="openNew()" value="会谈会见台账创建"/>';
            $(rightBtn).appendTo(".location");
            function openNew(){
            location.href = '/Manage/LedgerMeeting/addLedger';
                
        }

