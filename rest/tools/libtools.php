<?php


//自动转换特殊字符穿，可选是否转换链接
function autoLinkchange($text,$isLink,$linkColor){
	$text=str_replace("&","&amp;",$text);
	$text=str_replace("<","&lt;",$text);
	$text=str_replace(">","&gt;",$text);
	$text=str_replace("\n","<br>",$text);
	if($isLink==true)
		$text=autoLink($text, $linkColor);
	return $text;
}



//自动添加超级链接
function autoLink($text,$color){
		if($color=="")$color="blue";
        $domainList = array('com','net','cn','xyz','top','tech','org','gov','edu','ink','int','pub','red','biz','cc','tv','info','name','mobi','travel','pro','museum','coop','aero','onion');
        $domainReg = implode("|", $domainList);

        $urlReg = "/(((http|https|ftp|ftps)\:\/\/)?[a-zA-Z0-9\-\.]+\.($domainReg)(\/\S*)?)/";

        if(preg_match($urlReg, $text)) {
            $text = preg_replace($urlReg, "<a style=\"color:".$color.";\" href=\"http://$1\" target=\"_blank\">$1</a>", $text);
        }

        $text = str_replace("http://http://", "http://", $text);
        $text = str_replace("http://https://", "https://", $text);
        $text = str_replace("http://ftp://", "ftp://", $text);
        $text = str_replace("http://ftps://", "ftps://", $text);

        return $text;
    }
//获取表网链接出现次数
function getinterneturlnum($text){
	$allnum=0;
	$temp=$text;
	$domainarr=['.com','.net','.cn','.xyz','.top','.tech','.org','.gov','.edu','.ink','.int','.pub','.red','.biz','.cc','.tv','.info','.name','.mobi','.travel','.pro','.museum','.coop','.aero'];
//	$temp=preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9-.]#u', '', $temp);//中文字母数字和.以外

	$temp=preg_replace('#[^A-Za-z0-9-.]#u', '', $temp);//字母和数字以外
	for($i=0;$i<count($domainarr);$i++){
			$allnum+=substr_count($temp,$domainarr[$i]);
	}
	return $allnum;
}
//echo "========".getinterneturlnum("\"'aklsdjf  .comadfa!@#@!%$#&%^%*&*!!#$fdsa.dfjklaj拉手极度疯狂了。是大家弗兰克斯节哀等")."<br>";
//获取暗网链接出现次数
function getonionurlnum($text){
	$allnum=0;
	$temp=$text;
	$domainarr=['.onion'];
	$temp=preg_replace('#[^A-Za-z0-9-.]#u', '', $temp);//字母和数字以外
	for($i=0;$i<count($domainarr);$i++){
			$allnum+=substr_count($temp,$domainarr[$i]);
	}
	return $allnum;
}
//echo "onion=========".getonionurlnum('fjdlksajkldfj.onionlakdsjfkljdslakjfk.$$on  i   on  lkasdjfklsajdf');
//保存图片文件
function upPicfile($filename,$tmpPath,$tfilename){
//	echo "---------------------".$filename."======".$tmpPath."+++++++++++++".$tfilename;
	global $_FILES;
//	echo $_FILES[$filename]["name"];
	$filePath=$tmpPath;
	$tmpPath=$tmpPath.mt_rand(1,100).time().$_FILES[$filename]["name"];
	//保存文件
	if ((($_FILES[$filename]["type"] == "image/gif")
	|| ($_FILES[$filename]["type"] == "image/jpeg")
	|| ($_FILES[$filename]["type"] == "image/png")
	|| ($_FILES[$filename]["type"] == "image/pjpeg")
	|| ($_FILES[$filename]["type"] == "image/bmp"))
	&& ($_FILES[$filename]["size"] < 2000000))
	{
	  	if ($_FILES[$filename]["error"] > 0)
	    {
	    	echo "null";
	    	return 'null';
	    }
	  	else
	    {
		    $handle = fopen($_FILES[$filename]["tmp_name"], "r");
		    $file_size = filesize($_FILES[$filename]["tmp_name"]);
		    $res = fread($handle,$file_size);
		    $chk=strpos($res, '<?php');
		    if($chk){
		    	return 'attack';
		    }
			else{
				$finfo=pathinfo($_FILES[$filename]["name"]);
//				echo "文件名".$tmpPath.$tfilename.$finfo['extension'];
				move_uploaded_file($_FILES[$filename]["tmp_name"],$filePath.$tfilename.'.'.$finfo['extension']);
//				return $tmpPath;
				return $tfilename.'.'.$finfo['extension'];
			}
		}
	}
}
/**
 * 生成GUID
 */
function string_make_guid() {
    // 1、去掉中间的“-”，长度有36变为32
    // 2、字母由“大写”改为“小写”
    if (function_exists('com_create_guid') === true) {
        return strtolower(str_replace('-', '', trim(com_create_guid(), '{}')));
    }

    return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
function getrondcolor(){
	$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
  	return '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
}
/**
 *tg消息发送
 * $issys  1系统消息 2订单消息
 */
function sendmsg($userid,$textmsg,$issys){
	global $conn;
	//获取推送前置机域名
	$sql="select domain from sendset where servertype='1'";
	$sqlquery=$conn->query($sql);
	$sendserver=($sqlquery->fetch_array())['domain'];
	$sql="select * from tgset where userid='".$userid."'";
	$sqlquery=$conn->query($sql);
	$row=$sqlquery->fetch_array();
	$token=$row['token'];
	$talkid=$row['talkid'];
	if($issys=="1"){
		if($row["issysmtetip"]==1){
			$urlgo=$sendserver.'/sendmessage.php?token='.$token."&chat_id=".$talkid."&text=".$textmsg;
			$fp=fopen($urlgo,'r');
			fclose($fp);
		}
	}else if($issys=="2"){
		if($row['isordertip']=="1"){
			//$urlgo="https://api.telegram.org/bot".$token."/sendmessage?chat_id=".$chatid."&text=".$textmsg;
			$urlgo=$sendserver.'/sendmessage.php?token='.$token."&chat_id=".$talkid."&text=".$textmsg;
			$fp=fopen($urlgo,'r');
			fclose($fp);
		}
	}
}
/**
 * 创建文件夹
 */
function makedir($pathstr){
	$dir = iconv("UTF-8", "GBK", $pathstr);
    if (!file_exists($dir)){
       mkdir ($dir,0777,true);
       return true;
//     echo '创建文件夹bookcover成功';
    } else {
    	return false;
//     echo '需创建的文件夹bookcover已经存在';
    }
}
/**
 * 删除当前目录及其目录下的所有目录和文件
 * @param string $path 待删除的目录
 * @note  $path路径结尾不要有斜杠/(例如:正确[$path='./static/image'],错误[$path='./static/image/'])
 */
function deleteDir($path) {

    if (is_dir($path)) {
        //扫描一个目录内的所有目录和文件并返回数组
        $dirs = scandir($path);

        foreach ($dirs as $dir) {
            //排除目录中的当前目录(.)和上一级目录(..)
            if ($dir != '.' && $dir != '..') {
                //如果是目录则递归子目录，继续操作
                $sonDir = $path.'/'.$dir;
                if (is_dir($sonDir)) {
                    //递归删除
                    deleteDir($sonDir);

                    //目录内的子目录和文件删除后删除空目录
                    @rmdir($sonDir);
                } else {

                    //如果是文件直接删除
                    @unlink($sonDir);
                }
            }
        }
        @rmdir($path);
    }
}
function goUrlByPost($keyname,$value,$url){
	$timeno=time();
	echo "<form style='display:none;' id='form".$timeno."' name='form".$timeno."' method='post' action='".$url."'><input name='".$keyname."' type='text' style='display:none' value='".$value."'/>

	</form><script type='text/javascript'>function load_submit(){document.form".$timeno.".submit()}load_submit();</script>";
}
function goUrlByPosts($keyname,$value,$url,$target){
	$timeno=time();
	if($target=="")
		echo "<form style='display:none;' id='form".$timeno."' name='form".$timeno."' method='post' action='".$url."'>";
	else
		echo "<form style='display:none;' id='form".$timeno."' name='form".$timeno."' method='post' action='".$url."' target='".$target."'>";
	for($i=0;$i<count($keyname);$i++){
		echo "<input name='".$keyname[$i]."' type='text' style='display:none' value='".$value[$i]."'/>";
	}
	echo "</form><script type='text/javascript'>function load_submit(){form".$timeno.".submit()}load_submit();</script>";
}
//验证邮箱格式
function check_email($email)
{
 $result = trim($email);
 if (filter_var($result, FILTER_VALIDATE_EMAIL))
 {
 return "true";
 }
 else
 {
 return "false";
 }
}
//保留小数位
function keepNum($number,$position){
//@bainumber需要du处理的数zhidao字,@position需要保留的位数
//$ary=explode('.',(string)$number);
//if(strlen($ary[1])>$position){
//$decimal=substr($ary[1],0,$position);
//$result=$ary[0].'.'.$decimal;
//return(float)$result;
//}else{
//return$number;
//}
return number_format($number,$position);
}
/**
 * 检测注入行为
 * 返回值 true 有注入行为
 */
function chksqlkey($words){
	$arr=['\'','"',' or','or ','\'or','or\'',')or','or(',' and','and ','\'and','and\'','and(',')and','='];
	$arrl=count($arr);
	for($i=0;$i<$arrl;$i++){
		$chk=strpos($words, $arr[$i]);
		if($chk>-1){
			return true;
		}
	}
	return false;
}
function chksqlkeyEx($words){
	$arr=['\'','"',' or','or ','\'or','or\'',')or','or(',' and','and ','\'and','and\'','and(',')and','='];
	$arrl=count($arr);
	for($i=0;$i<$arrl;$i++){
		$chk=strpos($words, $arr[$i]);
		if($chk>-1){
			return '';
		}
	}
	return $words;
}
/**
 * 解析並調用httpPosts
 */
function cbkurlPost($ses64,$cbkurl){
	echo "xxx".$cbkurl;
	$arr=explode('-',$cbkurl);
	$keyarr=['ses'];
	$valarr=[$ses64];
	$lgg="";
	$pg="";
	echo count($arr);
	foreach($arr as $value){
			$carr=explode('!', $value);
		if($carr[0]!="httpPost" && $carr[0]!="lgg" && $carr[0]!="pg"){
			array_push($keyarr,$carr[0]);
			array_push($valarr,$carr[1]);
		}else{
			if($carr[0]=="lgg"){
//				$carr=explode('!', $value);
				$lgg=$carr[1];
			}
			if($carr[0]=="pg"){
//				$carr=explode('!', $value);
				$pg=$carr[1];
			}
		}
	}
	echo 'index.php?lgg='.$lgg.'&pg='.$pg;
	goUrlByPosts($keyarr,$valarr,'index.php?lgg='.$lgg.'&pg='.$pg,"_self");
}
?>