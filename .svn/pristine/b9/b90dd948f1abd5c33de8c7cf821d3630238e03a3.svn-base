<?php
/**
 * 提取码模型类
 * @author Rinka
 */
class Ecw_code_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

 	/**
	 * 加入一条新的分享
	 * @param paras - 参数列表
	 * @return 该code的id
	 */
	public function create_cwcode($paras) {
		if (isset($paras)) {
			$this->db->insert('ecw_cwcode', $paras);
			return $this->db->insert_id();
		}
		else {
			return false;
		}
	}

	/**
	 * 删除/恢复一条分享记录
	 * @param cwid - 分享id
	 */
	public function deal_cwcode($cwid, $isrecovere = false) {
		if(isset($cwid)) {
			$this->db->where(array('id'=>$cwid));
			if (!$isrecovere) {
				$this->db->update('ecw_cwcode', array('status'=>1));
			}
			else {
				$this->db->update('ecw_cwcode', array('status'=>0));
			}
			return $this->db->affected_rows();
		}
		else {
			return false;
		}
	}

 	/**
	 * 从一个提取码获得一条分享记录
	 * @param cwcode - 提取码
	 */
	public function create_cwcode($cwcode) {
		if (isset($cwcode)) {
			$this->db->where(array('code'=>$cwcode));
			return $this->db->get('ecw_cwcode')->result();
		}
		else {
			return false;
		}
	}

    /**
	 * 取某课室所有分享
	 * @param iclassroom - 教室字符串
	 */
	public function get_cwcode_by_classroom($iclassroom) {
		if (isset($iclassroom)) {
			$sqlquery = $this->db->query('SELECT * FROM (ecw_cwcode INNER JOIN ecw_courses ON ecw_cwcode.cid = ecw_courses.cid) WHERE ecw_courses.classroom = $iclassroom AND ecw_cwcode.status = 0');
			return $sqlquery->result();
		}
		else {
			return false;
		}
	}

    /**
	 * 取某课程的所有分享
	 * @param courseId - 课程id
	 * @param nums - 最大条目
	 * @param offset - 偏移量
	 */
	public function get_cwcode_by_course($courseId, $nums = NULL, $offset = 0) {
		if (isset($courseId)) {
			$this->db->where(array('cid'=>$courseId, 'status'=>0));
			if (!is_null($nums)) {
				$this->db->limit($nums, $offset);
			}
			return $this->db->get('ecw_cwcode')->result();
		}
		else {
			return false;
		}
	}

	/**
	 * 取一个课程当前合法的所有课件code 
	 * @param courseId - 课程id
	 * @param currentWeek - 当前周数
	 */
	public function get_cwcode_in_valid_week($courseId, $currentWeek) {
		if (isset($courseId) && isset($currentWeek)) {
			$sqlquery = $this->db->query('SELECT * FROM ecw_cwcode WHERE cid = $courseId AND status = 0 AND beginat <= $currentWeek AND till >= $currentWeek');
			return $sqlquery->result();
		}
		else {
			return false;
		}
	}
}