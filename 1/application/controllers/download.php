<?php
header("Content-type: text/html; charset=utf-8");

// 定义FTP连接参数
define('FTP_HOSTNAME', '222.200.172.5/ecwroot');
define('FTP_USERNAME', 'ecwadmin');
define('FTP_PASSWORD', 'ecw123456');

Class download extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('ecw_code_model');
		$this->load->model('ecw_comment_model');
		$this->load->model('ecw_course_model');
		$this->load->model('ecw_pre_model');
		$this->load->helper(array('form', 'url'));
	}
	
	public function index() {
		$this->load->view('welcome_message');
	}
	
	/* 根据提取码取得对应课件分享、课程信息以及评论       需要提取码表单数据 */
	public function get_share_course_comments() {
		// 有新评论加入
		if (isset($_POST['share_id']) && isset($_POST['content'])) {
			$paras['id'] = $_POST['share_id'];
			$paras['content'] = $_POST['content'];
			$paras['time'] = date('Y-m-d H:i:s');
			// 插入新评论
			$this->ecw_comment_model->create_comment($paras);

			echo json_encode(array("status" => true, "msg" => "评论发表成功", "time" => $paras['time']));
			return;
		}
		
		$cwcode = $_POST['cwcode'];
		// 获取分享
		$share = $this->ecw_code_model->get_cwcode($cwcode);
		if (!$share) {
			$error = "分享码无效!";
			echo json_encode(array("status" => false, "msg" => $error));
			return;
		} else {

			// 获取评论
			$share_id = $share[0]->id;
			$comments = $this->ecw_comment_model->get_comment($share_id);
			// 获取课程
			$share_cid = $share[0]->cid;
			$course = $this->ecw_course_model->get_course($share_cid);
			// 分享信息  - share_uri为ftp下载地址
			$init_uri = $share[0]->uri;
			$share_name = $share[0]->cwname;
			// uri转义
			$dirs = explode('/', substr($init_uri, 6));
			foreach ($dirs as $dir) {
				$new_list[] = rawurlencode(mb_convert_encoding($dir, 'gb2312', 'utf-8'));
			}
			$share_uri = 'ftp://' . FTP_USERNAME . ':' . FTP_PASSWORD . '@' . implode('/', $new_list);
			$paras['share_id'] = $share_id;
			$paras['share_code'] = $cwcode;
			$paras['share_uri'] = $share_uri;
			$paras['share_name'] = $share_name;
			// 评论信息
			$paras['comments'] = $comments;
			// 课程信息
			$paras['classroom'] = $course->classroom;
			$paras['course_name'] = $course->name;
			$paras['share_time'] = $share[0]->time;

			echo json_encode(array("status" => true, "msg" => "分享码有效", "data" => $paras));
		}
	}
	
	/* 客户端通过该方法获取展示文件列表 */
	public function get_pre() {
		$cur_weekday = date("w") == 0 ? 7 : date("w");
		$cur_period = $_POST['cur_period'];
		$cur_classroom = $_POST['classroom'];
		// 按当前星期几、当前时段、当前课室获取课程id
		$cid = $this->ecw_course_model->get_course_by_time_room($cur_weekday, $cur_period, $cur_classroom);
		if (!$cid){
			echo '';
		}
		$code_paras['cid'] = $cid;
		$cur_week = $this->cal_week();
		// 按cid、当前周次获取展示
		$pres = $this->ecw_pre_model->get_pre_by_week($cid, $cur_week);
		if ($pres == false) {
			echo '';
		} else if (count($pres) == 0) {
			echo '';
		} else {
			for ($i = 0; $i < count($pres); $i++) {
				echo $pres[$i]->uri . '|';
			}
		}
	}
	
	/* 计算当前周数 */
	private function cal_week() {
		// 周日为一周的第一天
		$cur_week = date('W') - 9;
		$cur_week = date("w") == 0 ? $cur_week + 1 : $cur_week;
		return $cur_week;
	}
	
}