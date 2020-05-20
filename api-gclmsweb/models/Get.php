<?php
class Get {
	protected $gm;

	public function __construct(\PDO $pdo) {
		$this->gm = new GlobalMethods($pdo);
	}

	public function get_announcement() {
		$sql = "SELECT * FROM announcements_tbl WHERE isdel=0 ORDER BY an_timestamp DESC";

		$res = $this->gm->execute_query($sql, "Failed to get data");

		if ($res['code'] == 200) {

			for ($i = 0; $i < count($res['data']); $i++) {
				$count = $this->get_commentcount($res['data'][$i]['an_announcecode']);
				$res['data'][$i]['commentcount'] = $count['count'];
			}

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

	public function get_commentcount($param) {
		$sql = "SELECT COUNT(co_commentcode) FROM commentsannounce_tbl WHERE co_announcecode=$param";
		$res = $this->gm->execute_query($sql, 'testing');
		$data = array("count" => $res['data'][0]['COUNT(co_commentcode)']);
		return $data;
	}

	###################CLASSROOM MODULES#########################

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

	public function get_facultyclassroom($param) {
		$sql = "SELECT classes_tbl.*, faculty_tbl.f_empno, faculty_tbl.f_fname, faculty_tbl.f_mname, faculty_tbl.f_lname, faculty_tbl.f_nameext, faculty_tbl.f_img, faculty_tbl.f_dept FROM faculty_tbl INNER JOIN classes_tbl ON faculty_tbl.f_empno = classes_tbl.cl_empno WHERE faculty_tbl.f_empno = '$param' AND classes_tbl.isdel = 0";
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

	// public function get_classpost_faculty($param) {

	// 	$sql = "SELECT classpost_tbl.*, faculty_tbl.f_fname AS s_fname, faculty_tbl.f_lname AS s_lname FROM classpost_tbl INNER JOIN faculty_tbl ON classpost_tbl.cp_studnum = faculty_tbl.f_empno WHERE cp_classcode = '$param' AND classpost_tbl.isdel = 0 ORDER BY classpost_tbl.cp_timestamp DESC";

	// 	$res = $this->gm->execute_query($sql, "Failed to load");

	// 	if ($res['code'] == 200) {
	// 		$code = 200;
	// 		$remarks = "success";
	// 		$message = "Success Getting Data";
	// 		$payload = $res['data'];
	// 	} else {
	// 		$payload = null;
	// 		$remarks = "failed";
	// 		$message = $res['errmsg'];
	// 	}
	// 	return $this->gm->api_result($payload, $remarks, $message, $code);
	// }

	public function get_classpost($param) {

		$sql2 = "SELECT classpost_tbl.* , students_tbl.s_fname , students_tbl.s_lname FROM classpost_tbl INNER JOIN students_tbl ON classpost_tbl.cp_studnum = students_tbl.s_studnum WHERE cp_classcode = '$param' AND classpost_tbl.isdel = 0 ORDER BY classpost_tbl.cp_timestamp DESC";
		$sql1 = "SELECT classpost_tbl.*, faculty_tbl.f_fname AS s_fname, faculty_tbl.f_lname AS s_lname FROM classpost_tbl INNER JOIN faculty_tbl ON classpost_tbl.cp_studnum = faculty_tbl.f_empno WHERE cp_classcode = '$param' AND classpost_tbl.isdel = 0 ORDER BY classpost_tbl.cp_timestamp DESC";

		$res1 = $this->gm->execute_query($sql1, "Failed to load");
		$res2 = $this->gm->execute_query($sql2, "Failed to load");
		if ($res1['code'] && $res2['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Success Getting Data";
			$payload = array_merge($res1['data'], $res2['data']);
			// $payload = $res1;
		} else {
			$payload = null;
			$remarks = "failed";
			$message = array_merge($res1['errmsg'], $res2['errmsg']);
		}
		return $this->gm->api_result($payload, $remarks, $message, $code);
	}

	public function get_classroomcomments($param) {
		$sql = "SELECT classcomments_tbl.*, faculty_tbl.f_fname, faculty_tbl.f_lname FROM classcomments_tbl INNER JOIN faculty_tbl ON classcomments_tbl.cc_studnum = faculty_tbl.f_empno WHERE classcomments_tbl.cc_postcode = '$param' AND classcomments_tbl.isdel = 0 ORDER BY classcomments_tbl.cc_timestamp ASC";

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

}
