<?php 
header("Content-type: text/html; charset=utf-8"); 

class mytest extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }
    
    public function index() {
        echo 'Start dash in SVN!';
    }
    
    public function myfoo($para1, $para2) {
        echo 'Hello ' . $para1 . ' ,' . $para2;
    }
    
    public function getOurUri($str) {
        $str = urldecode($str);
        echo 'We get: ' . $str . '<br/><br/>';
        $str = str_replace('>','\\\\',$str);
        echo 'NOW: ' . $str . '<br/>';
		$mysql = new SaeMysql();
		$iquery = "INSERT INTO `testTable` (`uri`) values ('".$str."')";
		$mysql->runSql($iquery);
		if ($mysql->errno() != 0)
		{
			die("Error:" . $mysql->errmsg());
		}
        else
        {
            echo("提交成功！");
        }
		$mysql->closeDb();
    }
}