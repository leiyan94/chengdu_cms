$(function() {
	$(".appStore").click(function() {
		alert("敬请期待")
	})

	
	
	function flashChecker() {
		var hasFlash = 0; //是否安装了flash
		var flashVersion = 0; //flash版本
		if(document.all) {
			var swf = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
			if(swf) {
				hasFlash = 1;
				VSwf = swf.GetVariable("$version");
				flashVersion = parseInt(VSwf.split(" ")[1].split(",")[0]);
			}
		} else {
			if(navigator.plugins && navigator.plugins.length > 0) {
				var swf = navigator.plugins["Shockwave Flash"];
				if(swf) {
					hasFlash = 1;
					var words = swf.description.split(" ");
					for(var i = 0; i < words.length; ++i) {
						if(isNaN(parseInt(words[i]))) continue;
						flashVersion = parseInt(words[i]);
					}
				}
			}
		}
		return { f: hasFlash, v: flashVersion };
	}
	var fls = flashChecker();
	var s = "";
	if(fls.f) $(".fla").css("display" , "block");
	else  $(".fla").css("display" , "none");
})