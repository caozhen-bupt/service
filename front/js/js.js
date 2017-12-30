$(function() {
	//购物车切换图片
	var isHover = false
	var timer1 = null;
	$(".buy_car_img").hover(function() {
		isHover = true;
		$(".buy_car_img").attr("src", "images/shopcarhover.png");
		$(".buy_car_spec").animate({
			height: 100
		}, 200, function() {
			$(".buy_car p").html("购物车中还没有商品，赶紧选购吧！");
		});
	}, function() {
		isHover = false;
		$(this).stop(timer1);
		timer1 = setTimeout(function() {
			if (!isHover) {
				$(".buy_car_spec").animate({
					height: 0
				}, 200, function() {
					$(".buy_car p").html("");
				});
				$(".buy_car_img").attr("src", "images/shopcar.png");
			}
		}, 200);
	});
	$(".buy_car_spec").hover(function() {
		isHover = true;
		$(".buy_car_img").attr("src", "images/shopcarhover.png");
		$(".buy_car_spec").animate({
			height: 100
		}, 200, function() {
			$(".buy_car p").html("购物车中还没有商品，赶紧选购吧！");
		});
	}, function() {
		isHover = false;
		$(this).stop(timer1);
		timer1 = setTimeout(function() {
			if (!isHover) {
				$(".buy_car_spec").animate({
					height: 0
				}, 200, function() {
					$(".buy_car p").html("");
				});
				$(".buy_car_img").attr("src", "images/shopcar.png");
			}
		}, 200);
	});

	//热门

	//搜索框点击，移入，移出动画
	$(document).click(function() {
		$(".search_extra").hide();
		$(".search_txt").css("border", "1px solid #e0e0e0");
		$(".search_btn").css("border", "1px solid #e0e0e0");
		$(".search_btn").css("border-left", "none");
		$(".hot_word1,.hot_word2").show();
		$(".hot_word1,.hot_word2").animate({
			opacity: 100
		}, 300);
	});
	$(".search_box").click(function() {
		$(".search_extra").show();
		$(".search_txt").css("border", "1px solid #ff6700");
		$(".search_txt").css("border-bottom", "none");
		$(".search_btn").css("border", "1px solid #ff6700");
		$(".search_btn").css("border-left", "none");
		$(".hot_word1,.hot_word2").animate({
			opacity: 0
		}, 300);
		return false;
	});

	// 导航栏显示隐藏的div
	var isHoverNav = false;
	var timer2 = null;

	function changeStateDown(index) {
		switch (index) {
			case 0:
				$(".nav_menu_show1").slideDown(400);
				break;
			case 1:
				$(".nav_menu_show2").slideDown(400);
				break;
			case 2:
				$(".nav_menu_show3").slideDown(400);
				break;
			case 3:
				$(".nav_menu_show4").slideDown(400);
				break;
			case 4:
				$(".nav_menu_show5").slideDown(400);
				break;
			case 5:
				$(".nav_menu_show6").slideDown(400);
				break;
		}
	}

	//首页大图切换
	$(".category_move span").click(function(){
		$(".category_move span").removeClass("cur_move");
		$(this).addClass("cur_move");

		var index = $(this).parent().index();
		$(".category_hot_list > li").css("display","none");
		$(".category_hot_list > li:eq("+index+")").css("display","block");
	});

	function changeStateUp(index) {
		switch (index) {
			case 0:
				$(".nav_menu_show1").slideUp(400);
				break;
			case 1:
				$(".nav_menu_show2").slideUp(400);
				break;
			case 2:
				$(".nav_menu_show3").slideUp(400);
				break;
			case 3:
				$(".nav_menu_show4").slideUp(400);
				break;
			case 4:
				$(".nav_menu_show5").slideUp(400);
				break;
			case 5:
				$(".nav_menu_show6").slideUp(400);
				break;
		}
	}
	var preIndex = -1,
		curIndex = -1;
	$(".nav .nav_list li").mouseover(function() {
		curIndex = $(this).index();
		isHoverNav = true;
		changeStateDown(curIndex);
		if (preIndex != -1 && preIndex != curIndex) changeStateUp(preIndex);
		preIndex = curIndex;
		$(this).mouseout(function() {
			isHoverNav = false;
			//$(this).stop(timer2);
			timer2 = setTimeout(function() {
				if (!isHoverNav) {
					changeStateUp(preIndex);
				}
			}, 100);
			return false;
		});
		return false;
	});
	$(".navMenu").mouseover(function() {
		isHoverNav = true;
		changeStateDown(curIndex);
		$(this).mouseout(function() {
			isHoverNav = false;
			//$(this).stop(timer2);
			timer2 = setTimeout(function() {
				if (!isHoverNav) {
					changeStateUp(preIndex);
				}
			}, 100);
			return false;
		})
		return false;
	});

	//明星单品切换
	var dir = 0; //0:left,1:right
	$(".left_img").click(function() {
		if (dir) {
			$(".left_img > img").attr("src", "images/icon/left2.png");
			$(".right_img > img").attr("src", "images/icon/right1.png");
			$(".star_spec .spec_item_list").animate({
				left: "0"
			}, 200);
			dir = 0;
		}
	});
	$(".right_img").click(function() {
		if (dir == 0) {
			$(".left_img > img").attr("src", "images/icon/left1.png");
			$(".right_img > img").attr("src", "images/icon/right2.png");
			$(".star_spec .spec_item_list").animate({
				left: "-1234px"
			}, 200);
			dir = 1;
		}
	});

	//category栏的category_item_box显示隐藏
	$(".category_item").hover(function(){
		var index = $(this).index();
		$(".category_item_box:eq("+index+")").css("display","block");
			var category_item_list = $(this).find(".category_item_box").children(".category_item_list");
			var width = $(this).index() == 0 ? 270 : 220;
			var len = category_item_list.length;
			category_item_list.width(width);
			width = len == 1 ? len*width : len*width + 70;
			$(".category_list .category_item_box").width(width);
			console.log(len*width);
	},function(){
		var index = $(this).index();
		$(".category_item_box:eq("+index+")").css("display","none");
	})

	// function loadXMLDoc(var searchtxt)
	// {
	// 	var xmlhttp;
	// 	if (window.XMLHttpRequest)
	// 	{// code for IE7+, Firefox, Chrome, Opera, Safari
	// 	    xmlhttp=new XMLHttpRequest();
	// 	}
	// 	else
	// 	{// code for IE6, IE5
	// 	    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	// 	}
	// 	xmlhttp.onreadystatechange=function()
	// 	{
	// 	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
	// 	    {
	// 	    // document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
	// 	    	console.log(xmlhttp.responseText)
	// 	    }
	// 	}
	// 	xmlhttp.open("GET","http://111.230.233.124/market/index.php/home/goods/searchGoods"+searchtxt,true);
	// 	xmlhttp.send();
	// }

	$(".search_btn").click(function(){
		var searchtxt = document.searchForm.searchcontent;
		console.log(searchtxt.value);
		var data=JSON.stringify(searchtxt.value);
		// loadXMLDoc(data);
		// var jqxhr = $.ajax('http://111.230.233.124/market/index.php/home/goods/searchGoods', {
		//     dataType: 'json'
		// }).done(function (data) {
  //   		var data=JSON.stringify(data);
		// 	console.log(data)
			
  //   	})
		
	});

	

	// function addurl() {
 //    var js = document.createElement('script'),
 //        head = document.getElementsByTagName('head')[0];
 //    js.src = 'http://111.230.233.124/market/index.php/home/goods/searchGoods';
 //    head.appendChild(js);
// }


});