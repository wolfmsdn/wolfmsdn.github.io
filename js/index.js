var App_Main={};
$(document).ready(function() {

	/**绑定事件*/
	$(".App_Tools_Btn").on('click',function(e,s) {
		var clsobj=e.target.classList.toString();
		$.each($('.App_Tools_Btn'), function(i,it) {
			var objname=it.classList.toString();
			objname=objname.replace('App_Tools_img','').replace('App_Tools_Btn','').replace('App_Tools_Default','').replace('App_Tools_Sel','').trim();
			$('#'+objname)[0].classList.remove('App_Tools_Sel');
		});

		clsobj=clsobj.replace('App_Tools_img','').replace('App_Tools_Btn','').replace('App_Tools_Default','').replace('App_Tools_Sel','').trim();
		$('#'+clsobj)[0].classList.add('App_Tools_Sel');
		eval('mod_'+clsobj+'();');
		/**阻止事件冒泡*/
		event.stopPropagation();
	});
	$("#Search_txt").on('focus',function(e){
		$(this).on('click',function(e){e.preventDefault}).select();
	});
	/**绑定搜索按钮事件*/
	$("#Search_img").on('click',function(){
		mod_App_Search();
		/**阻止事件冒泡*/
		event.stopPropagation();
	});
	/**关闭主菜单*/
	$(".ThisApp_Menu").on('click',function(e){
		this.style.display='none';
	});
	/**打开主菜单*/
	$(".Mnu_img").on('click',function(e){
		$(".ThisApp_Menu").css('display','block');
	});

	/**载入主页*/
	function mod_App_Tools_Home(e){
		$.getScript("./js/mod/Home.js",function(){
// 			console.log("加载文件Home.js完成");
 		});
	}
	/**载入个人中心*/
	function mod_App_Tools_My(e){
		$.getScript("./js/mod/My.js",function(){
// 			console.log("加载文件My.js完成");
 		});
	}
	/**载入收藏夹*/
	function mod_App_Tools_Favorites(e){
//		console.log('App_Tools_Favorites');
		$.getScript("./js/mod/Favorites.js",function(){
// 			console.log("加载文件Favorites.js完成");
 		});
	}
	/**载入发布站点页面*/
	function mod_App_Tools_Post(e){
//		console.log('App_Tools_Post');
		$.getScript("./js/mod/Post.js",function(){
// 			console.log("加载文件Post.js完成");
 		});
	}
	/**载入搜索页面*/
	function mod_App_Search(){
		$.getScript("./js/mod/Search.js",function(){
// 			console.log("加载文件Search.js完成");
 		});
	}
	/*初始化主页的Home*/
	mod_App_Tools_Home();

	/*初始化PI SDK信息开始*/
	const Pi = window.Pi;
	Pi.init({
		version: "2.0"
	});

	async function auth() {
		try {
			// Identify the user with their username / unique network-wide ID, and get permission to request payments from them.
			const scopes = ['username', 'payments'];

			function onIncompletePaymentFound(payment) {

			}; // Read more about this in the SDK reference

			Pi.authenticate(scopes, onIncompletePaymentFound).then(function(auth) {
				$("#username").html(" " + auth.user.username);
			}).catch(function(error) {
				Pi.openShareDialog("Error", error);
				alert(err);
				console.error(error);
			});
		} catch(err) {
			Pi.openShareDialog("Error", err);
			alert(err);
			console.error(err);
			// Not able to fetch the user
		}
	}

	auth();
	/*初始化PI SDK信息结束*/
});
function PageModRefresh(){
	/*执行语言刷新时，需要从后台获取数据的刷新处理*/
//	console.log($.cookie('CurrPage'));
	var currpage=$.parseJSON($.cookie('CurrPage'));
//	console.log(currpage.Page);
//	console.log(currpage.TypeSel);
	switch(currpage.Page){
		case 'Home':
//			console.log('home post');
			Home_State.AppTypeSel=currpage.TypeSel;
			loadPageList();
			break;
		case 'Search':
			Search_State.AppTypeSel=currpage.TypeSel;
			loadSearchList();
			break;
	}
}