<?php
//屏蔽错误显示
set_error_handler('errorfnc');
function errorfnc() {
}

//连接数据库方式1
//define("P_HOSTNAME","localhost");
//define("P_USER","root");
//define("P_PASSWORD","wolfmsdn");
//define("P_DATABASENAME","pi_app_list");
define("P_HOSTNAME","caf08d9e0e77.c.methodot.com:33428");
define("P_USER","root");
define("P_PASSWORD","wolfmsdn");
define("P_DATABASENAME","pi_app_list");

$conn = new mysqli(P_HOSTNAME, P_USER, P_PASSWORD, P_DATABASENAME);
$conn -> query("SET NAMES utf8");

if ($conn -> connect_errno) {

	printf("Connect failed: %s\n", $conn -> connect_error);
	exit();

}
function connopen() {
	global $conn;
	$conn = new mysqli(P_HOSTNAME, P_USER, P_PASSWORD, P_DATABASENAME);
	if ($conn -> connect_errno) {
		printf("Connect failed: %s\n", $conn -> connect_error);
		exit();
	}
}

function login($user, $pass, $md5) {
	$chk = chksqlkey($user . $pass . $md5);
	if ($chk) {
		return 1;
	}
	if (!$md5) {
		$pass = md5($pass);
	}
	$nowdate=date("Y-m-d",time());
	$sql = "select * from user where name='" . $user . "' and password='" . $pass . "' and isban='0'";
	global $conn;
	$tblist = $conn -> query($sql);
	$isrec = $tblist -> num_rows;
	if ($isrec > 0) {
		return 0;
	} else {
		return 2;
	}
}

/**
 * 获取一页数据
 */
function getpagedata() {
	global $conn;

}

/**
 * 获取数据条数，并计算
 */
function getdatanum() {

}

/*
 *获得一个记录集(sql语句,基本信息)返回$query
 */
function getdata($sql, $baseinfo) {
	global $conn;
	if ($sql != "") {
		$query = $conn -> query($sql);
		return $query;
	} else {
		return null;
	}
}
/**
 * 表/查询
 */
class GetTableInfo {
	/**
	 * sql语句
	 */
	public $sql;
	/**
	 * 获取记录数量
	 */
	public $num;
	/**
	 * 总页数
	 */
	public $pageall;
	/**
	 * 每页条数
	 */
	public $pagesize;
	/**
	 * 当前页码
	 */
	public $page;
	/**
	 * 源数据查询
	 */
	public $query;
	/**
	 * 数据连接
	 */
	public $conn;
	/**
	 * 字段数组
	 */
	public $fields;
	/**
	 * 分页数据
	 */
	public $pagequery;
	public $pagefields;
	function __construct($sql, $pagesize) {
		global $conn;
		$this->sql=$sql;
		$this -> conn = $conn;
		$this -> pagesize = $pagesize;
		$this -> page = 0;
		if ($sql != "") {
			$this -> query = $this -> conn -> query($sql);
			$this->pagequery=$this->conn->query($sql." limit 0,".$pagesize);
			if ($this -> query) {
				$this -> num = $this -> query -> num_rows;
				$this -> pageall = ceil($this -> num / $pagesize);
			} else {
				$this -> num = 0;
			}
		} else {
			$this -> $query = null;
			$this->$pagequery=null;
		}
	}

	/**
	 * 获取一页数据
	 * $page 页码
	 * $template 渲染模板，每条记录的渲染html模板
	 * 模板中使用[@字段名]代表需要填充什么样的值
	 */
	function getpage($page, $template) {
		$this -> page = $page;
		$this->pagequery=$this->conn->query($this -> sql." limit ".($page*$this->pagesize).",".$this->pagesize);
		$this->getpagerowfdname();
		$context='';
		$allcontext='';
		$i = 0;
		while ($row = $this -> pagequery -> fetch_array()) {
			$i = 0;
			$context=$template;
			while ($str = $row[$i]) {
				$key='[@'.$this->pagefields[$i].']';
				$context=str_replace($key, $str,$context );
				$i++;
			}
			$allcontext.=$context;
		}
		return $allcontext;
	}

	/**
	 * 获取字段名并且存入数组fields;
	 */
	function getrowfdname() {
		$this -> fields = array();
		while ($field = $this -> query -> fetch_field()) {
			array_push($this -> fields, $field -> orgname);
		}
	}
	/**
	 * 获取字段名并且存入数组fields;
	 */
	function getpagerowfdname() {
		$this -> pagefields = array();
		while ($pagefield = $this -> pagequery -> fetch_field()) {
			array_push($this -> pagefields, $pagefield -> orgname);
		}
	}
	/**
	 * 从指针获取一条记录
	 */
	function getrow() {
		return $this -> query -> fetch_array();
	}
	/**
	 * 从指针获取一条记录
	 */
	function getpagerow() {
		return $this -> pagequery -> fetch_array();
	}
	/**
	 * 重置数据读取指针
	 */
	function reset() {
		$this -> query -> data_seek(0);
	}

	/**
	 * 重置分页数据读取指针
	 */
	function pagereset() {
		$this -> pagequery -> data_seek(0);
	}
	/**
	 * 关闭数据连接
	 */
	function close() {
		return $this -> conn -> close();
	}

}
?>