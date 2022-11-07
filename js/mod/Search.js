var Search_State = {
	AppTypeSel: '0',
	PageSize: '20',
	CurrPage: '0',
	Lng: isEng
}
loading_open();
App_Main = $('.App_Main');
loadModHtml(App_Main, './js/mod/Search.html', {}, loadSearchList);

/**
 * PiAPPIconObj_Html	应用程序对象Html
 * ^IMAGEPATH^	应用图标必须是正方形
 * ^AppText^	应用名称
 * ^MEMO^		引用备注
 * ^AppClickNumber^	应用被点击浏览的数量
 */
var PiAPPIconObj_Html = '<div class="AppICO_List_Item">' +
	'<div class="AppICO_List_Item_div">' +
	'	<img class="AppICO_List_Item_img" src="^IMAGEPATH^" />' +
	'	<div class="AppICO_List_Item_title">^AppText^</div>' +
	'	<div class="AppICO_List_Item_memo">^MEMO^</div>' +
	'	<div class="AppICO_List_Item_clicknum"><div class="app_coin_click" data-locale="AppCionClick"></div><div id="AppClickNumber" class>(^AppClickNumber^)</div><img src="./img/favorite_add.png" webname="^Favorites^" class="Favorites"></div>' +
	'</div>' +
	'<div id="ItemUrl" style="display:none">^URL^</div>' +
	'</div>';

/**搜索App数据*/
function loadSearchList(data) {
	//	console.log(data);
	Search_State.CurrPage = '0';
	Search_State.AppTypeSel = $('#Search_txt').val();
	console.log(Search_State.AppTypeSel);
	$.cookie('CurrPage', JSON.stringify({
		Page: 'Search',
		TypeSel: Search_State.AppTypeSel
	}));

	if(Search_State.AppTypeSel == '') {
		/**查询字符串不能为空*/
		$('.AppICO_List').html('<div class="SearchTip" data-locale="SearchStrNull"></div>');
		loadCallback();
		return;
	}

	loadSearchPageList();
}
/**载入应用图标列表*/
function loadSearchPageList() {
	var senddata = {
		cmd: 'GetSearchAppList',
		data: {
			typeindex: Search_State.AppTypeSel,
			pagenum: Search_State.CurrPage,
			pagesize: Search_State.PageSize
		}
	}
	$.ajax({
		type: 'Post',
		url: RESTFull_Base,
		data: senddata,
		failed: function(code, msg) {
			console.log(msg);
		},
		success: function(data) {
			var datajson = $.parseJSON(data);
			if(datajson.state == 0) {
				var appnum = '0';
				if(datajson.data.reccount != undefined) {
					var objhtmltmp = '';
					appnum = datajson.data.reccount;
					if(datajson.data.reccount >= 0) {
						for(var i = 0; i < datajson.data.reccount; i++) {
							var objtmp = PiAPPIconObj_Html;
							var datatmp = datajson.data.data;
							objtmp = objtmp.replace('^IMAGEPATH^', './appicons/' + datatmp[i].logo);
							objtmp = objtmp.replace('^AppText^', datatmp[i].name);
							objtmp = objtmp.replace('^MEMO^', datatmp[i].memo);
							objtmp = objtmp.replace('^AppClickNumber^', datatmp[i].num);
							objtmp = objtmp.replace('^URL^', datatmp[i].url);
							objtmp = objtmp.replace('^Favorites^', datatmp[i].url);
							objhtmltmp += objtmp;
						}
					}
					$('.AppICO_List').html(objhtmltmp);
					/**绑定事件*/
					$('.Favorites').on('click', function(e) {

						console.log("添加到个人收藏夹:" + e.currentTarget.attributes['webname'].nodeValue);
						/**阻止事件冒泡*/
						event.stopPropagation();
					});
					$('.AppICO_List_Item').on('click', function(e) {
						/*准备跳转的地址*/
						var target_url = e.currentTarget.childNodes[1].innerHTML;
						console.log("跳转到" + target_url);
						event.stopPropagation();
					});
				} else {
					console.log("---:" + datajson.data.reccount);
				}
				console.log($('.AppICO_Name'));
				$('.AppICO_Name').html('<span class="CtypeName" >' + Search_State.AppTypeSel + '</span>-<span class="CtypeNum" data-locale="SearchRet"></span>-<span class="CtypeNum">[' + appnum + ']</span>');

				loadCallback(data);
			}

		}
	});
}