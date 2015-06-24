<?php
/**
 * 课程模型类
 * @author Rinka
 */
class ecw_course_model extends CI_Model {
 	/**
	 * 加入一个新的课程
	 * @param paras - 参数列表
	 * @return 这个课程的id
	 */
	public function create_course($paras) {
		if (isset($paras)) {
			$this->db->insert('ecw_courses', $paras);
			return $this->db->insert_id();
		}
		else {
			return false;
		}
	}

	/**
	 * 用id取课程信息
	 * @param id - 课程id
	 */
	public function get_course($id) {
		if (isset($id)) {
			$this->db->where(array('cid'=>$id));
			$res = $this->db->get('ecw_courses')->result();
			return $res[0];
		}
		else {
			return false;
		}
	}

	/**
	 * 删除/恢复一个课程
	 * @param id - 课程id
	 */
	public function deal_course($id, $isrecovere = false) {
		if(isset($id)) {
			$this->db->where(array('cid'=>$id));
			if (!$isrecovere) {
				$this->db->update('ecw_courses', array('status'=>1));
			}
			else {
				$this->db->update('ecw_courses', array('status'=>0));
			}
			return $this->db->affected_rows();
		}
		else {
			return false;
		}
	}

    /**
	 * 取某课室的所有课程记录
	 * @param iclassroom - 教室字符串
	 */
	public function get_course_by_classroom($iclassroom) {
		if (isset($iclassroom)) {
			$this->db->where(array('classroom'=>$iclassroom));
			return $this->db->get('ecw_courses')->result();
		}
		else {
			return false;
		}
	}

    /**
	 * 取指定课程名的有效记录
	 * @param courseName - 课程名称
	 * @param nums - 最大条目
	 * @param offset - 偏移量
	 */
	public function get_course_by_name($courseName, $nums = NULL, $offset = 0) {
		if (isset($courseName)) {
			$this->db->where(array('name'=>$courseName, 'status'=>0));
			if (!is_null($nums)) {
				$this->db->limit($nums, $offset);
			}
			return $this->db->get('ecw_courses')->result();
		}
		else {
			return false;
		}
	}

	/**
	 * 判断该课程是否有效 
	 * @param courseId - 课程id
	 * @param currentWeek - 当前周
	 * @return 是否有效
	 */
	public function is_course_valid($courseId, $currentWeek) {
		if (isset($courseId) and isset($currentWeek)) {
			$dataobj = $this->get_course($courseId);
			return $dataobj->startWeek <= $currentWeek and $dataobj->endWeek >= $currentWeek; 
		}
		else {
			return false;
		}
	}

	/**
	 * 当前时间当前课室是什么课 
	 * @param currentWeekday - 当前星期几(1-7)
	 * @param currentTimePeriod - 当前时间段(1-15)
	 * @param currentRoom - 当前课室
	 * @return 课程id
	 */
	public function get_course_by_time_room($currentWeekday, $currentTimePeriod, $currentRoom) {
		if (isset($currentTimePeriod) and isset($currentRoom) and isset($currentWeekday)) {
			$t = 'SELECT * FROM ecw_courses WHERE status = 0 AND classroom = \''. $currentRoom . '\' AND startPeriod <= ' . $currentTimePeriod . ' AND endPeriod >= ' . $currentTimePeriod . ' AND ' . $currentWeekday . ' = weekday';
			$sqlquery = $this->db->query($t);
			$dataarr = $sqlquery->result();
			return $dataarr[0]->cid;
		}
		else {
			return false;
		}
	}


}