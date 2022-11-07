<?php
$cmd=$_POST["cmd"];
$data=$_POST["data"];
$lng=$_COOKIE['lng'];
require_once'./tools/conn.php';
require_once'./tools/libtools.php';
require_once'./data/AppData.php';
$returnString='{"state":0,"data":{}}';

switch($cmd){
	case "GetAppList"://获取App列表
		GetAppList();
		break;
	case "GetSearchAppList"://获取搜索App列表
		GetSearchAppList();
		break;
	case "BrowUrl"://用户浏览App，添加浏览次数
		BrowUrl();
		break;
	default:
		header("Content-type:text/html;charset=utf-8");
		echo "{cmd:".$cmd.",data:".$data."}".$data['pagenum'].$data['typeindex'].$data['pagesize'];
		break;
}

?>