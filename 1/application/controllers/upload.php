<?php
header("Content-type: text/html; charset=utf-8");

// 定义FTP连接参数
define('FTP_HOSTNAME', '222.200.172.5/ecwroot');
define('FTP_USERNAME', 'ecwadmin');
define('FTP_PASSWORD', 'ecw123456');

Class upload extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('ecw_code_model');
		$this->load->model('ecw_course_model');
		$this->load->model('ecw_pre_model');
		$this->load->helper(array('form', 'url'));
	}
	
	public function index() {
		// 获取课室列表
		$classrooms = $this->get_classrooms();
		$this->load->view('pre_upload_form', array("roomList" => $classrooms));	
	}
	
	/* 课件上传     需要课件上传表单数� */
	public function cw_upload() {
		//$classroom = $_POST['classroom'];
		
		// $config['upload_path'] = './uploads/';
// 		$config['allowed_types'] = 'doc|docx|xls|xlsx|ppt|pptx|pdf|gif|jpg|png|zip|rar';
// 		$config['max_size'] = '10240';		
// 		$this->load->library('upload', $config);

// 		$cwname = '新建文本文档.txt';
		
// 		$this->load->library('ftp');
		
// 		$config['hostname'] = FTP_HOSTNAME;
// 		$config['username'] = FTP_USERNAME;
// 		$config['password'] = FTP_PASSWORD;
// 		$config['debug'] = TRUE;
// 		$t = $this->ftp->connect($config);
// 		var_dump($t);
		

// 		$this->ftp->upload('C:/Users/�Desktop/新建文本文档.txt', 'frproot/C�C102/'.$cwname, 'ascii', 0775);	
// 		$this->ftp->close();

		$conn_id = ftp_connect('222.200.172.5/ecwroot') or die("Couldn't connect");
		var_dump($conn_id);
		
//     tutrhrutjtjt	
// 		if (!$this->upload->do_upload()) {
// 			$error = array('error' => $this->upload->display_errors());	
// 			$this->load->view('ecw_upload_form', $error);
// 		} else {
// 			$code = $this->generate_code(6);			
// 			$para['uri'] = ;
// 			$para['code'] = $code;
// 			$para['cid'] = ;
// 			$para['cwname'] = ;
// 			$para['time'] = date('Y-m-d H:i:s');
// 			$para['till'] = ;
// 			$para['status'] = 0;
// 			$para['beginat'] = ;	
// 			$this->ecw_code_model->create_cwcode($para);
// 			// 返回一个课件分享码到上传成功页�
// 			$this->load->view('ecw_upload_success', $code);
// 		}
	}
	
	/* 展示上传     需要展示上传表单数�*/
	public function pre_upload() {
		if (isset($_POST['classroom']) && !isset($_POST['course_name'])) {
			$classroom = $_POST['classroom'];
			$courses = $this->ecw_course_model->get_course_by_classroom($classroom);
			echo json_encode(array('status' => true, 'data' => $courses));
			return;
		} else if (isset($_POST['classroom']) && isset($_POST['course_name']) && 
				isset($_POST['pre_week'])) {
			$pre_author = $_POST['pre_author'];
			$pre_remark = $_POST['pre_remark'];
			$pre_week = $_POST['pre_week'];
			
			// 按course_name获取课程记录
			$course_name = $_POST['course_name'];
			$courses = $this->ecw_course_model->get_course_by_name($course_name);
			$course_id = $courses[0]->cid;
			
			// 上传配置
			$config['upload_path'] = 'ecw/pre_upload_files/';
			$config['allowed_types'] = 'txt|doc|docx|xls|xlsx|ppt|pptx|pdf|gif|jpg|png|zip|rar';
			$config['max_size'] = '10240';
			$this->load->library('upload', $config);
			

			if (!$this->upload->do_upload()) {
				// print_r($this->upload->display_errors());
				$error = array('error' => $this->upload->display_errors());
				// print_r($error);
				$this->load->view('pre_upload_form', array('error' => $error ));
			} else {
				// data包含所上传文件的信�
				$data = array('upload_data' => $this->upload->data());
				//上传文件成功后的URL路径
				$pre_paras['uri'] =  $data['upload_data']['file_url'];
				$pre_paras['name'] = $data['upload_data']['file_name'];
				// 相关pre记录信息参数列表
				$pre_paras['intro'] = $pre_author . '--' . $pre_remark;
				$pre_paras['ip'] = $this->input->ip_address();
				$pre_paras['week'] = $pre_week;
				$pre_paras['cid'] = $course_id;
				$pre_paras['time'] = date('Y-m-d H:i:s');
				// 将新pre记录写入数据�
				$res = $this->ecw_pre_model->create_pre($pre_paras);
				if (!$res) {
					$error = array('error' => 'Fail to create the new pre record in database!');
					$this->load->view('pre_upload_form', array('error' => $error ));	
				} else {
					$this->load->view('pre_upload_success');
				}
			}		
		} 
	}
	
	/* 客户端上�*/
	public function client_upload() {
		// 创建分享的参数列�
		if (!isset($_POST['uri']) || !isset($_POST['cwcode']) || !isset($_POST['cwname']) || 
				!isset($_POST['classroom']) || !isset($_POST['cur_period'])) {
			echo 'POST paras lost!';
			echo 'POST paras lost!';
		}
		$code_paras['uri'] = $_POST['uri'];
		$code_paras['code'] = $_POST['cwcode'];
		$code_paras['cwname'] = $_POST['cwname'];
		$code_paras['time'] = date('Y-m-d H:i:s');
		$code_paras['till'] = 0;
		$code_paras['beginat'] = 0;
		// 课程信息
		$cur_classroom = $_POST['classroom'];
		$cur_period = $_POST['cur_period'];
		$cur_weekday = date("w") == 0 ? 7 : date("w");
		// 获取课程id
		$cid = $this->ecw_course_model->get_course_by_time_room($cur_weekday, $cur_period, $cur_classroom);
		if (!$cid){
			echo 'get cid failed! '.'<br/>'.'change to public course.';
			// cid�表示公共容错课程
			$cid = 1;
		}
		$code_paras['cid'] = $cid;
		echo $cid.'<br/>';
		// 创建新分�
		$insert_id = $this->ecw_code_model->create_cwcode($code_paras);
		
		// 写入memcache
		// value = "share_id#share_name#share_time#original_uri#classroom#course_name"
		$course = $this->ecw_course_model->get_course($cid);
		$course_name = $course->name;
		$key = $_POST['cwcode'];
		
		// 写入memcache
		// value = "share_id#share_name#share_time#original_uri#classroom#course_name"
		$course = $this->ecw_course_model->get_course($cid);
		$course_name = $course->name;
		$key = $_POST['cwcode'];
		$value = $insert_id.'#'.$_POST['cwname'].'#'.$code_paras['time'].'#'.
			$_POST['uri'].'#'.$_POST['classroom'].'#'.$course_name;
		$mmc = memcache_init();
		if($mmc == false)
			echo "mc init failed\n";
		else {
			memcache_set($mmc, $key, $value);
		}
	}
	
	/* 客户端请求提取码 */
	public function client_request_code() {
		$code = $this->generate_code(6);
		echo $code;
	}
	
	/* 客户端请求课程名�*/
	public function client_request_cname() {
		if (isset($_POST['classroom']) && isset($_POST['cur_period'])) {
			$cur_classroom = $_POST['classroom'];
			$cur_period = $_POST['cur_period'];
			$cur_weekday = date("w") == 0 ? 7 : date("w");
			// 获取课程id
			$cid = $this->ecw_course_model->get_course_by_time_room($cur_weekday, $cur_period, $cur_classroom);
			if (!$cid){
				// cid�表示公共容错课程
				$cid = 1;
			}
			$course = $this->ecw_course_model->get_course($cid);
			$course_name = $course->name;
			$cur_week = $this->cal_week();
			echo $cur_week.'|'.$course_name;
		}
	}
	
	/* 获取课件提取�*/
	public function generate_code($length = 6) {
		// 密码字符集，可任意添加需要的字符
		$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
		$code = '';
		do {
			for ( $i = 0; $i < $length; $i++ ) {
				// 这里提供两种字符获取方式
				// 第一种是使用 substr 截取$chars中的任意一位字符；
				// 第二种是取字符数�$chars 的任意元�
				// $code .= substr($chars, mt_rand(0, strlen($chars) �1), 1);
				$code .= $chars[ mt_rand(0, strlen($chars) - 1) ];
			}
			$res = $this->ecw_code_model->get_cwcode($code);
		} while (count($res) != 0);
		return $code;
	}
	
	/* 获取所有课室列�*/
	private function get_classrooms() {
		$classrooms = array('A101', 'A102', 'A103', 'A104', 'A105', 'A201', 'A202', 
				            'A203', 'A204', 'A205', 'A206', 'A207', 'A301', 'A302', 
				            'A303', 'A304', 'A305', 'A306', 'A401', 'A402', 'A403', 
				            'A404', 'A405', 'A501', 'A502', 'A503', 'A504', 'A505', 
				'B101', 'B102', 'B103', 'B104', 'B201', 'B202', 'B203', 
				'B204', 'B205', 'B301', 'B302', 'B303', 'B304', 'B305', 
				'B401', 'B402', 'B403', 'B501', 'B502', 'B503', 
				            'C101', 'C102', 'C103', 'C104', 'C105', 'C201', 'C202', 
				            'C203', 'C204', 'C205', 'C206', 'C207', 'C301', 'C302', 
				            'C303', 'C304', 'C305', 'C306', 'C401', 'C402', 'C403', 
				            'C404', 'C405', 'C501', 'C502', 'C503', 'C504', 'C505', 
				'D101', 'D102', 'D103', 'D104', 'D201', 'D202', 'D203', 
				'D204', 'D205', 'D301', 'D302', 'D303', 'D304', 'D305', 
				'D401', 'D402', 'D403', 'D501', 'D502', 'D503', 
				            'E101', 'E102', 'E103', 'E104', 'E105', 'E106', 'E201', 
				            'E202', 'E203', 'E204', 'E205', 'E301', 'E302', 'E303', 
				            'E304', 'E305', 'E401', 'E402', 'E403', 'E404', 'E405', 
				            'E501', 'E502', 'E503', 'E504', 'E505'			
		);
		return  $classrooms;
	}
	
	/* 计算当前周数 */
	private function cal_week() {
		// 周日为一周的第一�
		// 周日为一周的第一�
		$cur_week = date('W') - 9;
		$cur_week = date("w") == 0 ? $cur_week + 1 : $cur_week;
		$cur_week = date("w") == 0 ? $cur_week + 1 : $cur_week;
		return $cur_week;
	}

}