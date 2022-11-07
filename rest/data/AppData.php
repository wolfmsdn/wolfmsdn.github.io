<?php
/**
 * 获取App列表，根据类别，可分页
 */
function GetAppList(){
	global $data;
	global $lng;
	global $conn;
	global $returnString;
	$typeindex=chksqlkeyEx($data['typeindex']);
	$currpage=chksqlkeyEx($data['pagenum']);
	$pagesize=chksqlkeyEx($data['pagesize']);

	$where="";

	if($typeindex=='CLITAll'||$typeindex==''){
		$where="where 1=1 ";
	}else{
		$where="where typeindex='".$typeindex."'";
	}
//	echo $where;
	if($currpage==''){
		echo $returnString;
		return;
	}
	if($pagesize==''||$pagesize==0){
		echo $returnString;
		return;
	}

	$sql="select count(id) num from ".$lng."_applist ". $where;
//	echo $sql;
	$query=$conn->query($sql);
	$row=$query->fetch_array();
	$RecNumber=$row["num"];
	if($RecNumber=='')$RecNumber=0;
//	echo 'rec:'.$RecNumber;

	$pageall=ceil($RecNumber/$pagesize);
	if($currpage<0)$currpage=0;
	if($currpage>=$pageall)$currpage=($pageall==0)?0:$pageall-1;
	$beginnum=$currpage*$pagesize;

	if($RecNumber==''){
		echo $returnString;
		return;
	}else{

		$sql="select * from ".$lng."_applist ".$where." order by id desc limit ".$beginnum.",".$pagesize;
		$query=$conn->query($sql);
		$ret= $query->num_rows;
		$retrec='';
		while($row=$query->fetch_array()){
			$retrec.="{\"id\":\"".$row['id']
			."\",\"typeindex\":\"".$row['typeindex']
			."\",\"name\":\"".$row['name']
			."\",\"logo\":\"".$row['logo']
			."\",\"memo\":\"".$row['memo']
			."\",\"num\":\"".$row['clicknum']
			."\",\"url\":\"".$row['url']."\"},";
		}
		$retrec="{\"currpage\":".$currpage.",\"pageall\":".$pageall.",\"reccount\":".$query->num_rows.",\"count\":".$RecNumber.",\"data\":[".$retrec."]}";
		$retrec=str_replace(",]","]",$retrec);
		$retrec=str_replace("{}", $retrec,$returnString);

		echo $retrec;
	}
}
/**
 * 获取App列表,用户输入的条件，可分页
 */
function GetSearchAppList(){
	global $data;
	global $lng;
	global $conn;
	global $returnString;
	$typeindex=chksqlkeyEx($data['typeindex']);
	$currpage=chksqlkeyEx($data['pagenum']);
	$pagesize=chksqlkeyEx($data['pagesize']);

	$where="";

	if($typeindex==''){
		$where="where 1=1 ";
	}else{
		$where="where name like '%".$typeindex."%' or memo like '".$typeindex."' or tag like '%".$typeindex."%'";
	}
//	echo $where;
	if($currpage==''){
		echo $returnString;
		return;
	}
	if($pagesize==''||$pagesize==0){
		echo $returnString;
		return;
	}

	$sql="select count(id) num from ".$lng."_applist ". $where;
//	echo $sql;
	$query=$conn->query($sql);
	$row=$query->fetch_array();
	$RecNumber=$row["num"];
	if($RecNumber=='')$RecNumber=0;
//	echo 'rec:'.$RecNumber;

	$pageall=ceil($RecNumber/$pagesize);
	if($currpage<0)$currpage=0;
	if($currpage>=$pageall)$currpage=($pageall==0)?0:$pageall-1;
	$beginnum=$currpage*$pagesize;

	if($RecNumber==''){
		echo $returnString;
		return;
	}else{

		$sql="select * from ".$lng."_applist ".$where." order by id desc limit ".$beginnum.",".$pagesize;
		$query=$conn->query($sql);
		$ret= $query->num_rows;
		$retrec='';
		while($row=$query->fetch_array()){
			$retrec.="{\"id\":\"".$row['id']
			."\",\"typeindex\":\"".$row['typeindex']
			."\",\"name\":\"".$row['name']
			."\",\"logo\":\"".$row['logo']
			."\",\"memo\":\"".$row['memo']
			."\",\"num\":\"".$row['clicknum']
			."\",\"url\":\"".$row['url']."\"},";
		}
		$retrec="{\"currpage\":".$currpage.",\"pageall\":".$pageall.",\"reccount\":".$query->num_rows.",\"count\":".$RecNumber.",\"data\":[".$retrec."]}";
		$retrec=str_replace(",]","]",$retrec);
		$retrec=str_replace("{}", $retrec,$returnString);

		echo $retrec;
	}
}

/**
 * 更新点击次数
 */
 function BrowUrl(){
 	global $conn;
	global $data;
	global $lng;
	global $returnString;
	$url= $data[Url];
	$where='';
	if($url==''){
		echo $returnString;
		return;
	}else {
		$where=" where t1.url='".$url.
				"' and t2.url='".$url.
				"' and t3.url='".$url.
				"' and t4.url='".$url."'";
	}

	$sql="update cj_applist t1,cf_applist t2,en_applist t3,ko_applist t4 set t1.clicknum=t1.clicknum+1,t4.clicknum=t4.clicknum+1,t2.clicknum=t2.clicknum+1,t3.clicknum=t3.clicknum+1 ". $where.";";
	$conn->query($sql);
	echo $sql;
	 }
?>