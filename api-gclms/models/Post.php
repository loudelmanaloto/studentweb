<?php
class Post {
	protected $gm;
	protected $pdo;
	protected $get;

	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
		$this->gm = new GlobalMethods($pdo);
		$this->get = new Get($pdo);
	}

	public function add_forumcontent($dt) {
		$res = $this->gm->insert("forumcontent_tbl", $dt);
		if ($res['code'] == 200) {
			return $this->get->get_forumcontent($dt->fc_subcode);
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function add_announcement($dt) {
		$res = $this->gm->insert("announcements_tbl", $dt);
		if ($res['code'] == 200) {
			return $this->get->get_forumcontent($dt->fc_subcode);
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function add_commentsannounce($dt) {

		$sql = "SELECT co_commentcode FROM commentsannounce_tbl ORDER BY co_recno DESC LIMIT 1";
		$res = $this->gm->execute_query($sql, 'Invalid Request');

		if ($res['code'] === 200) {

			$num = intval($res['data'][0]['co_commentcode']);
			$newnum = strval($num + 1);
			$dt->co_commentcode = $newnum;

			$res = $this->gm->insert('commentsannounce_tbl', $dt);

			if ($res['code'] === 200) {
				return $this->get->get_commentsannounce($dt->co_announcecode);
			} else {
				$payload = null;
				$remarks = "failed";
				$message = $res['errmsg'];
			}
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);

	}

	public function edit_studentinfo($dt) {
		$res = $this->gm->update("students_tbl", $dt, "s_studnum = $dt->s_studnum");
		if ($res['code'] == 200) {
			return $this->get->get_studentinfo($dt->s_studnum);
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);

	}

	###############################################################################
	#  POST CLASS MODULES HERE                                                    #
	#                                                                            #
	##############################################################################

	public function joinclass($dt) {

		$res = $this->gm->update("enrolled_tbl", $dt, "e_studnum = $dt->e_studnum AND e_classcode = $dt->e_classcode");

		if ($res['code'] == 200) {
			return $this->get->get_studentclass($dt->e_studnum);
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);

	}

	public function declineclass($dt) {

		$res = $this->gm->update("enrolled_tbl", $dt, "e_studnum = $dt->e_studnum AND e_classcode = $dt->e_classcode");

		if ($res['code'] == 200) {
			return $this->get->get_studentclass($dt->e_studnum);
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function add_class($dt) {
		$res = $this->gm->insert('enrolled_tbl', $dt);
		if ($res['code'] === 200) {
			return $this->get->get_studentclass($dt->e_studnum);
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function add_classpost($dt) {
		$sql = "SELECT * FROM classpost_tbl ORDER BY cp_recno DESC LIMIT 1";
		$res = $this->gm->execute_query($sql, 'Invalid Request');

		if ($res['code'] === 200) {

			$num = intval($res['data'][0]['cp_postcode']);
			$newnum = strval($num + 1);
			$dt->cp_postcode = $newnum;

			$res = $this->gm->insert('classpost_tbl', $dt);

			if ($res['code'] === 200) {
				return $this->get->get_classpost($dt->cp_classcode);
			} else {
				$payload = null;
				$remarks = "failed";
				$message = $res['errmsg'];
			}
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function add_classcomments($dt) {

		$sql = "SELECT * FROM classcomments_tbl ORDER BY cc_recno DESC LIMIT 1";
		$res = $this->gm->execute_query($sql, 'Invalid Request');

		if ($res['code'] === 200) {

			$num = intval($res['data'][0]['cc_commentcode']);
			$newnum = strval($num + 1);
			$dt->cc_commentcode = $newnum;

			$res = $this->gm->insert('classcomments_tbl', $dt);

			if ($res['code'] === 200) {
				return $this->get->get_classroomcomments($dt->cc_postcode);
			} else {
				$payload = null;
				$remarks = "failed";
				$message = $res['errmsg'];
			}
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function submit_files($dt) {
		$res = $this->gm->insert('submissions_tbl', $dt);

		if ($res['code'] === 200) {
			return $this->get->get_submitfiles();
		} else {
			$payload = null;
			$remarks = "failed";
			$message = $res['errmsg'];
		}
		return $this->gm->api_result($payload, $remarks, $message, $res['code']);
	}

	public function process_upload($param) {

		$target_path = "uploads/students/$param/";

		if (!is_dir($target_path)) {
			mkdir($target_path, 0777, true);
		}

		$target_path = $target_path . basename($_FILES['file']['name']);

		if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
			header('Content-type: application/json');
			$data = ['filepath' => $target_path, 'success' => true, 'message' => 'Upload and move success'];
			return $data;
		} else {
			$data = ['filepath' => null, 'success' => false, 'message' => 'There was an error uploading the file, please try again!'];
			return $data;
		}
	}
}
?>
