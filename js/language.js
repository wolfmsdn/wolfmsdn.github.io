/**屏蔽右键*/
$(document).bind("contextmenu", function() {
	return false;
});
/**获取浏览器语言*/
var chk_userlanguage = function () {
        if (navigator.userLanguage) {
            //baseLang = navigator.userLanguage.substring(0, 2).toLowerCase();
            baseLang = (navigator.userLanguage+"     ").substring(0,5).toLowerCase().trim();
        } else {
        	baseLang = (navigator.language+"      ").substring(0,5).toLowerCase().trim();
            //baseLang = navigator.language.substring(0, 2).toLowerCase();
        }
        switch(baseLang){
        	case 'zh-cn':
        	case 'zh-sg':
        		return 'cj';
        		break;
        	case 'zh-hk':
        	case 'zh-mo':
        	case 'zh-chs':
			case 'zh-tw':
			case 'zh-cht':
        		return 'cf';
        		break;
        	default:
        		{
        			baseLang=baseLang.substring(0,2);
        			switch(baseLang){
        				case 'en':
        					return 'en';
        					break;
        				case 'zh':
        					return 'cj';
        					break;
        				case '':
        					return '';
        					break;
        				default:
        					return '--';
        					break;
        			}
        		}
        		break;
        }
};

/**处理语言模块*/
let isEng = getCookie('lng')
lng_Close = function(e) {
	$('#Lng_menu').css('display', 'none');
}
lng_Open = function(e) {
	if($('#Lng_menu').css('display') == 'none') {
		$('#Lng_menu').css('display', 'block');
		$('#Lng_menu').css('z-index', '999');
	} else {
		$('#Lng_menu').css('display', 'none');
	}
}
$('#Lng_menu').on('click',function(e){
	$('#Lng_menu').css('display','none');
});
lng_Sel = function(e, s,cb) {
	$.cookie('lng', s, {
		expires: 365
	});
	isEng = getCookie('lng');
	switch(isEng) {
		case 'en':
			loadProperties('en');
			break;
		case 'cj':
			loadProperties('cj');
			break;
		case 'cf':
			loadProperties('cf');
			break;
		case 'ko':
			loadProperties('ko');
			break;
	}
	/*特殊刷新函数*/
	if(cb)cb();
	lng_Close();
}

function loadProperties(lang) {
	$.i18n.properties({
		name: 'lang', //资源文件名称 ， 命名格式： 文件名_国家代号.properties
		path: 'i18n/', //资源文件路径，注意这里路径是你属性文件的所在文件夹,可以自定义。
		mode: 'map', //用 Map 的方式使用资源文件中的值
		language: lang, //这就是国家代号 name+language刚好组成属性文件名：strings+zh -> strings_zh.properties
		callback: function() {
			$("[data-apptitle]").each(function(){
				document.title=$.i18n.prop($(this).data("apptitle"));
			});
			/**修改属性*/
			$("[data-placeholder]").each(function(){
				$(this).attr('placeholder',$.i18n.prop($(this).data("placeholder")));
			});
			/**修改所有标签*/
			$("[data-locale]").each(function() {
				$(this).html($.i18n.prop($(this).data("locale")));

			});
		}
	});
}
var lng_cookie=$.cookie('lng');
if(lng_cookie==undefined){
	lng_cookie=chk_userlanguage();
	$.cookie('lng',lng_cookie,{
		expires: 365
	});
}
loadProperties(lng_cookie); //调用