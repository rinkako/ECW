<?php
/**
 * 学生展示模型类
 * @author Rinka
 */
class Ecw_pre_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

 	/**
	 * 加入一个新的展示记录
	 * @param paras - 参数列表
	 * @return 这个课程的id
	 */
	public function create_pre($paras) {
		if (isset($paras)) {
			$this->db->insert('ecw_pres', $paras);
			return $this->db->insert_id();
		}
		else {
			return false;
		}
	}

	/**
	 * 用id取展示信息
	 * @param id - 展示id
	 */
	public function get_pre($id) {
		if (isset($id)) {
			$this->db->where(array('preid'=>$id));
			$res = $this->db->get('ecw_pres')->result();
			return $res[0];
		}
		else {
			return false;
		}
	}

	/**
	 * 删除/恢复一个展示
	 * @param id - 展示id
	 */
	public function deal_pre($id, $isrecovere = false) {
		if(isset($id)) {
			$this->db->where(array('preid'=>$id));
			if (!$isrecovere) {
				$this->db->update('ecw_pres', array('status'=>1));
			}
			else {
				$this->db->update('ecw_pres', array('status'=>0));
			}
			return $this->db->affected_rows();
		}
		else {
			return false;
		}
	}

    /**
	 * 取某课程某一周的所有有效展示
	 * @param courseId - 课程id
	 * @param currentWeek - 周数
	 */
	public function get_pre_by_week($courseId, $currentWeek) {
		if (isset($courseId) and isset($currentWeek)) {
			$this->db->where(array('cid'=>$courseId, 'week'=>'currentWeek', 'status'=>0));
			return $this->db->get('ecw_pres')->result();
		}
		else {
			return false;
		}
	}

    /**
	 * 取某课程的有效展示
	 * @param courseId - 课程id
	 * @param nums - 最大条目
	 * @param offset - 偏移量
	 */
	public function get_all_pre($courseId, $nums = NULL, $offset = 0) {
		if (isset($courseId)) {
			$this->db->where(array('name'=>$courseId, 'status'=>0));
			if (!is_null($nums)) {
				$this->db->limit($nums, $offset);
			}
			return $this->db->get('ecw_courses')->result();
		}
		else {
			return false;
		}
	}
}