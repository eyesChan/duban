var frame = {
	'createMenu': function(aMenu) {
		var $wrapMenu = $('.sidebar');
		//菜单功能初始化
		this.menuInit();
	},
	'menuInit': function(event) {
		event=window||window.event;
		var _this = this;
		var $menuLi = $(".sys-menu > li");
		var $oh2s = $('.sys-menu > li > h2');
		var $oSeclevelUls = $('.sys-menu > li > ul');
		var $oSeclevelLis = $oSeclevelUls.children('li');
		$oh2s.click(function(e) {
			$(this).addClass("current").parent().siblings().children("h2").removeClass("current");
			e=window.event||event;
			var $showCnt=$(this).next();
			if($(this).children("a").hasClass("index-href")){
				var $href = $(this).children("a").attr("href");
				location.href = $href;
			}
			if($showCnt.is(":visible")){
				$showCnt.slideUp(200);
				$(this).removeClass("current");
				$(this).children("i").last().attr("class","ico ico-arrow-collapse");
			}else{
				$showCnt.slideDown(200);
				$(this).children("i").last().attr("class","ico ico-arrow-extend");

			}
		});
		//二级菜单点击事件
		$oSeclevelLis.click(function(ev) {
			$(this).addClass('current');
			$(this).parent().siblings().addClass('current')
			//用链接文字更改'当前位置'
			_this.setCurrentLocation($(this).find("span").text());
		});
	},
	backHome:function(){
		$('#home').click(function(){
			document.getElementById('j-location').innerHTML = '系统首页';
		})
	},
	'setCurrentLocation': function(loc) {
		// document.getElementById('').innerHTML = loc;
		$("#j-location").html(loc);
	},
	'clearFrame':function(){
		document.getElementById('main-frame').src="welcome.html"

	},
	'init': function() {
		//创建菜单
		this.createMenu();
		this.clearFrame();
	}
};
