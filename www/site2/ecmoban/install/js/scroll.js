(function($){
	$.fn.jScroll = function(settings){
		var settings = $.extend();
		var defaults = {
			id: "scrollBody"
		};
		return this.each(function(defaults,settings){
			var $this = $(this);
			var Bool=false;
			var Scrbody = $this.find(".scrollBody");
			var Scro=Scrbody.find(".scrollBar");
			var Scrp=Scro.find("p");
			var Scrobd=Scrbody.find("#scrollMap");
			var Scroul=Scrobd.find("ul");
			var letter = Scrbody.prev();
			var Scrp_Height = Scrp.outerHeight()/2;
			var Num2=Scro.outerHeight()-Scrp.outerHeight();
			var offsetX=0;
			var offsetY=0;
			
			Scrp.mousedown(function(e){  
				Bool=true;
			});
			$(document).mouseup(function(){
				Bool=false;
			});
			$(document).mousemove(function(e){
				if(Bool){
					var top = Scro.offset().top;
					var topHeight = top +Scro.outerHeight();
					var value = $this.offset().top;
					if(e.pageY>=top && e.pageY <= topHeight){
						var Num1= e.pageY - value - Scro.position().top;
						var y = Num1 - Scrp_Height;
					}
					if(y<=1){
						Scrll(0);
						Scrp.css("top",1);
					}else if(y>=Num2){
						Scrp.css("top",Num2);
						Scrll(Num2);
					}else{
						Scrll(y);
					}
				}
			});
			function Scrll(y){
				Scrp.css("top",y);
				Scroul.css("margin-top",-(y/(Scro.outerHeight()-Scrp.outerHeight()))*(Scroul.outerHeight()-Scrobd.height()));
			}
			if(document.getElementById(settings.id).addEventListener) 
			document.getElementById(settings.id).addEventListener('DOMMouseScroll',wheel,true);
			document.getElementById(settings.id).onmousewheel=wheel;
			var Distance=Num2*0.1;
			function wheel(e){
				var evt = e || window.event;
				var wheelDelta = evt.wheelDelta || evt.detail;
				if(wheelDelta == -120 || wheelDelta == 3){
					var Distances=Scrp.position().top+Distance;
					if(Distances>=Num2){
						Scrp.css("top",Num2);
						Scrll(Num2);
					}else{
						Scrll(Distances);
					}
					return false;
				}else if (wheelDelta == 120 || wheelDelta == -3){
					var Distances=Scrp.position().top-Distance;
					if(Distances<=1){
						Scrll(0);
						Scrp.css("top",1);
					}else{
						Scrll(Distances);
					}
					return false;
				}   
			}
		});
	}
})(jQuery);