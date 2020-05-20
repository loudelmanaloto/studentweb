<?php
	class Auth {
		protected $gm;
		protected $pdo;

		public function __construct(\PDO $pdo) {
			$this->gm = new GlobalMethods($pdo);
			$this->pdo = $pdo;
		}
		
		########################################
		# 	USER AUTHORIZATION RELATED METHODS
		########################################
		protected function generateHeader() {
			$h=[
				"typ"=>"JWT",
				"alg"=>'HS256',
				"app"=>"GCECC POS",
				"dev"=>"Melner Balce, Loudel Manaloto, Owen Vargas"
			];
			return str_replace(['+','/','='],['-','_',''], base64_encode(json_encode($h)));
		}

		protected function generatePayload($uc, $ue, $up, $ito) {
			$p = [   
				'uc'=>$uc,
				'ue'=>$ue,
				'up'=>$up,
				'ito'=>$ito,
				'iby'=>'Melner Balce',
				'ie'=>'melnerbalce@techmatesph.com',
				'idate'=>date_create()
			];
			return str_replace(['+','/','='],['-','_',''], base64_encode(json_encode($p)));
		}

		protected function generateToken($code, $email, $course, $fullname) {
			$header = $this->generateHeader();
			$payload = $this->generatePayload($code, $email, $course, $fullname);
			$signature = hash_hmac('sha256', "$header.$payload", "www.gordoncollege.edu.ph");
			return str_replace(['+','/','='],['-','_',''], base64_encode($signature));
		}

		public function authorized() {
			$hdrs = apache_request_headers();
			return true;
		}

		########################################
		# 	USER AUTHENTICATION RELATED METHODS
		########################################
		protected function encrypt_password($pword) {
			$hashFormat="$2y$10$";
	    $saltLength=22;
	    $salt=$this->generate_salt($saltLength);
	    return crypt($pword,$hashFormat.$salt);
		}

		protected function generate_salt($len) {
			$urs=md5(uniqid(mt_rand(), true));
	    $b64String=base64_encode($urs);
	    $mb64String=str_replace('+','.', $b64String);
	    return substr($mb64String,0,$len);
		}

		public function pword_check($pword, $existingHash) {
			$hash=crypt($pword, $existingHash);
			if($hash===$existingHash){
				return true;
			}
			return false;
		}


		public function update_studentpass($param){

			$un = $param->studnum;
			$pw = $param->s_oldpass;
			$payload = "";
			$remarks = "";
			$message = "";
			$code = 403;

			$sql = "SELECT * FROM students_tbl WHERE s_studnum='$un' AND isdel=0 LIMIT 1";
			$res = $this->gm->execute_query($sql, "Incorrect username or password");	


			if($res['code'] == 200) {
				if($this->pword_check($pw, $res['data'][0]['s_pword'])) {
					
					$npw = $this->encrypt_password($param->s_newpass);
					$sql = "UPDATE students_tbl SET s_pword='$npw' WHERE s_studnum=$un";
					$this->gm->execute_query($sql, '');
								
					$code = 200;
					$remarks = "success";
					$message = "Changed Password Successfully";
					$payload = null;
					
				}
				else{
					$payload = null; 
					$remarks = "failed"; 
					$message = "Incorrect password or failed to update";
				}
			}
			else {
				$payload = null; 
				$remarks = "failed"; 
				$message = $res['errmsg'];
			}
			
			return $this->gm->api_result($payload, $remarks, $message, $code);
		}

		public function student_login($param){
			$un = $param->param1;
			$pw = $param->param2;
			$payload = "";
			$remarks = "";
			$message = "";
			$code = 403;

			$sql = "SELECT * FROM students_tbl WHERE s_studnum='$un' AND isdel=0 LIMIT 1";
			$res = $this->gm->execute_query($sql, "Incorrect username or password");

			if($res['code'] == 200) {
				if($this->pword_check($pw, $res['data'][0]['s_pword'])) {
					$uc = $res['data'][0]['s_studnum'];
					$ue = $res['data'][0]['s_email'];
					$up = $res['data'][0]['s_course'];
					$fn = $res['data'][0]['s_fname'].' '.$res['data'][0]['s_lname'];
					$tk = $this->generateToken($uc, $ue, $up, $fn);

					$sql = "UPDATE students_tbl SET s_token='$tk' WHERE s_recno='$uc'";
					$this->gm->execute_query($sql, "");

					$code = 200;
					$remarks = "success";
					$message = "Logged in successfully";
					$payload = array("id"=>$uc, "fullname"=>$fn, "key"=>$tk, "role"=>"0");
				} else {
					$payload = null; 
					$remarks = "failed"; 
					$message = "Incorrect username or password";
				}
			}	else {
				$payload = null; 
				$remarks = "failed"; 
				$message = $res['errmsg'];
			}
			return $this->gm->api_result($payload, $remarks, $message, $code);
		}
	}
?>