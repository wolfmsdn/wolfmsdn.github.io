/**
 * 接口调用基本路径
 */
var RESTFull_Base='./rest/'
/**
 * [封装方法]
 */
/**
 * [setCookie 设置cookie]
 * [key value t 键 值 时间(秒)]
 */
function setCookie(key, value, t) {
	var oDate = new Date();
	oDate.setDate(oDate.getDate() + t);
	document.cookie = key + "=" + value + "; expires=" + oDate.toDateString();
}
/**
 * [getCookie 获取cookie]
 */
function getCookie(key) {
	var arr1 = document.cookie.split("; "); //由于cookie是通过一个分号+空格的形式串联起来的，所以这里需要先按分号空格截断,变成[name=Jack,pwd=123456,age=22]数组类型；
	for(var i = 0; i < arr1.length; i++) {
		var arr2 = arr1[i].split("="); //通过=截断，把name=Jack截断成[name,Jack]数组；
		if(arr2[0] == key) {
			return decodeURI(arr2[1]);
		}
	}
}
/**
 * [removeCookie 移除cookie]
 */
function removeCookie(key) {
	setCookie(key, "", -1); // 把cookie设置为过期
};

function loadModHtml(outputobjname, templateurl,senddata, callback,isload_lng) {
//	console.log(outputobjname);
	$.ajax({
		url: templateurl,
		data: senddata,
		failed: function(code, msg) {
			console.log(msg);
		},
		success: function(data) {
//			console.log(data);
			outputobjname.html(data);
			if(callback)callback(data);
			if(isload_lng==undefined)
				if(isload_lng){
					loadProperties(isEng);
				}
		}
	});
}
function loadModHtml_Append(outputobjname, templateurl,senddata, callback,isload_lng) {
	console.log(outputobjname);
	$.ajax({
		url: templateurl,
		data: senddata,
		failed: function(code, msg) {
			console.log(msg);
		},
		success: function(data) {
//			console.log(data);
			outputobjname.append(data);
			if(callback)callback(data);
			if(isload_lng==undefined)
				if(isload_lng){
					loadProperties(isEng);
				}
		}
	});
}
function loading_open(){
	$("#App_Loading_div").show();
}
function loading_close(){
	$("#App_Loading_div").hide();
}
/**刷新并关闭载入界面*/
function loadCallback(data) {
	loadProperties(isEng);
	loading_close();
}
/*添加点击次数并且进行页面跳转*/
function UpdateNumAndGoUrl(url){
	console.log(url);
	var senddata = {
		cmd: 'BrowUrl',
		data: {
			Url:url
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

		}
	});
}