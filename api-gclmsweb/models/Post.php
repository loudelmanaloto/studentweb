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

		public function add_announcement($dt){

			$sql = "SELECT an_announcecode FROM announcements_tbl ORDER BY an_recno DESC LIMIT 1";
			$res = $this->gm->execute_query($sql, 'Invalid Request');

			if($res['code']===200){

				$num = intval($res['data'][0]['an_announcecode']);
				$newnum = strval($num+1);
				$dt->an_announcecode = $newnum;

				$res = $this->gm->insert('announcements_tbl', $dt);

				if($res['code']===200){
					return $this->get->get_announcement();
				}
				else {
				$payload = null;
				$remarks = "failed";
				$message = $res['errmsg'];
			}
		}
			return $this->gm->api_result($payload, $remarks, $message, $res['code']);

	}

	public function edit_announcement($dt){
			$res = $this->gm->update("announcements_tbl", $dt, "an_announcecode = $dt->an_announcecode");
			if($res['code']==200){
				return $this->get->get_announcement();
			} else {
				$payload = null;
				$remarks = "failed";
				$message = $res['errmsg'];
			}
			return $this->gm->api_result($payload, $remarks, $message, $res['code']);

	}

	public function process_upload(){

			$target_path = "uploads/";

			if(!is_dir($target_path)){
				mkdir($target_path, 0777, true);
			}
			
		 
			$target_path = $target_path . basename( $_FILES['file']['name']);

			 
			if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
			    header('Content-type: application/json');
			    $data = ['filepath'=>$target_path, 'success' => true, 'message' => 'Upload and move success'];
			    return $data;
			} else{
			    $data = ['filepath'=> null,'success' => false, 'message' => 'There was an error uploading the file, please try again!'];
			    return $data;
			}
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
		

}

?>
