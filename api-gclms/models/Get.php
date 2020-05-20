<?php
class Get {
	protected $gm;

	public function __construct(\PDO $pdo) {
		$this->gm = new GlobalMethods($pdo);
	}

	public function get_forumcategory() {
		$sql = "SELECT * FROM forums_tbl WHERE isdel=0 ORDER BY fo_title";
		$res = $this->gm->execute_query($sql, "No records found");
		if ($res['code'] == 200) {
			$payload = $res['data'];
			$remarks = "success";
			$message = "Successfully retrieved requested data";
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function get_subforum($param) {
		$sql = "SELECT * FROM subforum_tbl WHERE sf_forumcode=$param AND isdel=0 ORDER BY sf_title";
		$res = $this->gm->execute_query($sql, "No content found");
		if ($res['code'] == 200) {
			$payload = $res['data'];
			$remarks = "success";
			$message = "Successfully retrieved requested data";
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function get_forumcontent($param) {
		$sql = "SELECT forumcontent_tbl.*, students_tbl.s_studnum, students_tbl.s_fname, students_tbl.s_lname, students_tbl.s_course FROM forumcontent_tbl INNER JOIN students_tbl ON forumcontent_tbl.fc_studnum=students_tbl.s_studnum WHERE fc_subcode='$param' AND forumcontent_tbl.isdel=0 ORDER BY forumcontent_tbl.fc_timestamp";
		$res = $this->gm->execute_query($sql, "No content found");
		if ($res['code'] == 200) {
			$payload = $res['data'];
			$remarks = "success";
			$message = "Successfully retrieved requested data";
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	//get method for announcements

	public function get_commentsannounce($param) {
		$sql = "SELECT commentsannounce_tbl.*, students_tbl.s_fname, students_tbl.s_lname
			FROM commentsannounce_tbl
			INNER JOIN students_tbl
			ON commentsannounce_tbl.co_studnum=students_tbl.s_studnum
			WHERE commentsannounce_tbl.co_announcecode=$param AND commentsannounce_tbl.isdel=0
			ORDER BY commentsannounce_tbl.co_timestamp";

		$res = $this->gm->execute_query($sql, "Failed to get data");

		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Successfully retrieved requested data";

		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}

		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function get_announcement() {
		$sql = "SELECT * FROM announcements_tbl WHERE isdel=0";

		$res = $this->gm->execute_query($sql, "Failed to get data");

		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Successfully retrieved requested data";

		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}

		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	//get method for students

	public function get_studentinfo($param) {
		$sql = "SELECT * FROM students_tbl WHERE isdel=0 AND s_studnum=$param";

		$res = $this->gm->execute_query($sql, "Failed to get data");

		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Successfully retrieved requested data";

		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}

		return $this->gm->api_result($payload, $remarks, $message, $res['code']);

	}

	public function get_student() {
		$sql = "SELECT * FROM students_tbl WHERE isdel=0";

		$res = $this->gm->execute_query($sql, "Failed to get data");

		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Successfully retrieved requested data";

		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}

		return $this->gm->api_result($payload, $remarks, $message, $res['code']);

	}

	//GET METHODS FOR CLASSES MODULES
	public function get_activities($param) {
		$sql = "SELECT * FROM activity_tbl WHERE act_classcode = $param AND isdel=0 ORDER BY act_actdate DESC";

		$res = $this->gm->execute_query($sql, "Failed to get data");

		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Successfully retrieved requested data";
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $code);
	}

	public function get_studentclass($param) {
		$sql = "SELECT enrolled_tbl.e_request, enrolled_tbl.e_classcode, enrolled_tbl.e_accepted, classes_tbl.cl_desc, classes_tbl.cl_subjcode, classes_tbl.cl_block FROM enrolled_tbl INNER JOIN classes_tbl ON e_classcode=cl_classcode WHERE enrolled_tbl.isdel=0 AND enrolled_tbl.e_studnum=$param ORDER BY classes_tbl.cl_desc";
		$res = $this->gm->execute_query($sql, "Failed to load");
		if ($res['code'] == 200) {

			$remarks = "success";
			$message = "Success Getting Data";
			$payload = $res['data'];
		} else {
			$code = $res['code'];
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);

	}

	public function get_classroom() {
		$sql = "SELECT * FROM classes_tbl WHERE isdel=0 ORDER BY cl_desc";
		$res = $this->gm->execute_query($sql, "Failed to load");
		if ($res['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Success Getting Data";
			$payload = $res['data'];
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $code);
	}

	public function get_classpost($param) {
		$sql = "SELECT classpost_tbl.* , students_tbl.s_fname , students_tbl.s_lname FROM classpost_tbl INNER JOIN students_tbl ON classpost_tbl.cp_studnum = students_tbl.s_studnum WHERE cp_classcode = '$param' AND classpost_tbl.isdel = 0 ORDER BY classpost_tbl.cp_timestamp DESC ";
		$res = $this->gm->execute_query($sql, "Failed to load");
		if ($res['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Success Getting Data";
			$payload = $res['data'];
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $code);
	}

	public function get_classroomcomments($param) {
		$sql = "SELECT classcomments_tbl.*, students_tbl.s_fname, students_tbl.s_lname FROM classcomments_tbl INNER JOIN students_tbl ON classcomments_tbl.cc_studnum = students_tbl.s_studnum WHERE classcomments_tbl.cc_postcode = $param AND classcomments_tbl.isdel = 0 ORDER BY classcomments_tbl.cc_timestamp DESC";

		$res = $this->gm->execute_query($sql, "Failed to get data");

		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Successfully retrieved requested data";
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function get_submitfiles() {
		$sql = "SELECT * FROM submissions_tbl WHERE isdel=0";
		$res = $this->gm->execute_query($sql, "No records found");
		if ($res['code'] == 200) {
			$payload = $res['data'];
			$remarks = "success";
			$message = "Successfully retrieved requested data";
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

}
?>