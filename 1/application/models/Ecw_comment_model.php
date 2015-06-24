<?php
/**
 * 评论模型类
 * @author Rinka
 */
class ecw_comment_model extends CI_Model {
 	/**
	 * 加入新评论
	 * @param paras - 参数列表
	 */
	public function create_comment($paras) {
		if (isset($paras)) {
			$this->db->insert('ecw_comments', $paras);
			return $this->db->insert_id();
		}
		else {
			return false;
		}
	}

	/**
	 * 删除/恢复评论
	 * @param commentId - 评论id
	 * @param isrecovere - 是否恢复
	 */
	public function deal_comment($commentId, $isrecovere = false) {
		if(isset($commentId)) {
			$this->db->where(array('cmid'=>$commentId));
			if (!$isrecovere) {
				$this->db->update('ecw_comments', array('status'=>1));
			}
			else {
				$this->db->update('ecw_comments', array('status'=>0));
			}
			return $this->db->affected_rows();
		}
		else {
			return false;
		}
	}

    /**
	 * 取某一IP的所有评论
	 * @param ip - 发言人IP
	 * @param nums - 最大条目
	 * @param offset - 偏移量
	 */
	public function get_comment_by_ip($ip, $nums = NULL, $offset = 0) {
		if (isset($ip)) {
			$this->db->where('ip', $ip);
			if (!is_null($nums)) {
				$this->db->limit($nums, $offset);
			}
			return $this->db->get('ecw_comments')->result();
		}
		else {
			return false;
		}
	}

    /**
	 * 取某提取码的所有未删除评论
	 * @param codeId - 分享id
	 * @param nums - 最大条目
	 * @param offset - 偏移量
	 */
	public function get_comment($codeId, $nums = NULL, $offset = 0) {
		if (isset($codeId)) {
			$this->db->where(array('id'=>$codeId, 'status'=>0));
			if (!is_null($nums)) {
				$this->db->limit($nums, $offset);
			}
			return $this->db->get('ecw_comments')->result();
		}
		else {
			return false;
		}
	}

    /**
	 * 删除某IP的所有评论
	 * @param cmid - 评论id
	 */
	public function delete_comment_by_ip($ip) {
		if(isset($ip)) {
			$this->db->where(array('ip'=>$ip));
			$this->db->update('ecw_comments', array('status'=>1));
			return $this->db->affected_rows();
		}
		else {
			return false;
		}
	}

	/**
	 * 删除某分享下所有评论
	 * @param codeId - 分享id
	 */
	public function delete_comment_by_code($codeId) {
		if(isset($codeId)) {
			$this->db->where(array('id'=>$codeId));
			$this->db->update('ecw_comments', array('status'=>1));
			return $this->db->affected_rows();
		}
		else {
			return false;
		}
	}
}