<?php
require_once "./config/Connection.php";
require_once "./models/Global.php";
require_once "./models/Get.php";
require_once "./models/Auth.php";
require_once "./models/Procedural.php";
require_once "./models/Post.php";

$db = new Connection();
$pdo = $db->connect();

$auth = new Auth($pdo);
$get = new Get($pdo);
$post = new Post($pdo);

if (isset($_REQUEST['request'])) {
	$req = explode('/', rtrim($_REQUEST['request'], '/'));
} else {
	$req = array("errorcatcher");
}

switch ($_SERVER['REQUEST_METHOD']) {
case "POST":
	switch ($req[0]) {

	########################################
	# 	GET METHODS
	########################################
	case "login":
		$d = jd(base64_decode(file_get_contents("php://input")));
		echo je($auth->student_login($d));
		break;

	//forum related request
	case "mainforum":
		if ($auth->authorized()) {
			echo je($get->get_forumcategory());
		} else {
			echo errMsg(401);
		}
		break;

	case "subforum":
		if ($auth->authorized()) {
			echo je($get->get_subforum($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "forumcontent":
		if ($auth->authorized()) {
			echo je($get->get_forumcontent($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "addforumcontent":
		$d = jd(base64_decode(file_get_contents("php://input")));
		if ($auth->authorized()) {
			echo je($post->add_forumcontent($d));
		} else {
			echo errMsg(401);
		}
		break;
	//end of forum related requests

	//announcement module requests
	case "announcements":
		if ($auth->authorized()) {
			echo je($get->get_announcement());
		} else {
			echo errMsg(401);
		}
		break;

	case "commentsannounce":
		if ($auth->authorized()) {
			echo je($get->get_commentsannounce($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "addannouncement":
		$d = jd(base64_decode(file_get_contents("php://input")));
		if ($auth->authorized()) {
			echo je($post->add_commentsannounce($d));
		} else {
			echo errMsg(401);
		}
		break;

	case "addcommentsannounce":
		$d = jd(base64_decode(file_get_contents("php://input")));
		if ($auth->authorized()) {
			echo je($post->add_commentsannounce($d));
		} else {
			echo errMsg(401);
		}
		break;

	//end of announcement related request

	//student request

	case "students":
		if ($auth->authorized()) {
			echo je($get->get_student());
		} else {
			echo errMsg(401);
		}
		break;

	case "studentinfo":
		if ($auth->authorized()) {
			echo je($get->get_studentinfo($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "changestudentpass":
		// $d = jd(file_get_contents("php://input"));
		$d = jd(base64_decode(file_get_contents("php://input")));
		echo je($auth->update_studentpass($d));
		break;

	case "editstudent":
		$d = jd(base64_decode(file_get_contents("php://input")));
		// $d = jd(file_get_contents("php://input"));

		if ($auth->authorized()) {
			echo je($post->edit_studentinfo($d));
		} else {
			echo errMsg(401);
		}

		break;

	//end of student request

	//start of new api CLASSROOM MODULES

	case "submitactivity":
		$d = jd(base64_decode(file_get_contents("php://input")));

		if ($auth->authorized()) {
			echo je($post->submit_files($d));
		} else {
			echo errMsg(401);
		}
		break;

	case "classactivity":
		if ($auth->authorized()) {
			echo je($get->get_activities($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "joinclass":

		$d = jd(base64_decode(file_get_contents("php://input")));

		if ($auth->authorized()) {
			echo je($post->joinclass($d));
		} else {
			echo errMsg(401);
		}
		break;

	case "classrequest":

		$d = jd(base64_decode(file_get_contents("php://input")));

		if ($auth->authorized()) {
			echo je($post->add_class($d));
		} else {
			echo errMsg(401);
		}
		break;

	case "declineclass":

		$d = jd(base64_decode(file_get_contents("php://input")));

		if ($auth->authorized()) {
			echo je($post->declineclass($d));
		} else {
			echo errMsg(401);
		}
		break;

	case "classroom":
		if ($auth->authorized()) {
			echo je($get->get_studentclass($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "classroompost":
		if ($auth->authorized()) {
			echo je($get->get_classpost($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "classcomments":
		if ($auth->authorized()) {
			echo je($get->get_classroomcomments($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "addclasspost":
		$d = jd(base64_decode(file_get_contents("php://input")));
		// echo je($d);
		if ($auth->authorized()) {
			echo je($post->add_classpost($d));
		} else {
			echo errMsg(401);
		}
		break;

	case "addcommentclasspost":

		$d = jd(base64_decode(file_get_contents("php://input")));

		if ($auth->authorized()) {
			echo je($post->add_classcomments($d));
		} else {
			echo errMsg(401);
		}
		break;

	//END OF CLASSROM MODULES!

	//Testing of upload file

	case "uploadfile":
		if ($auth->authorized()) {
			echo je($post->process_upload($req[1]));
		} else {
			echo errMsg(401);
		}
		break;
	//end of upload file test

	default:
		echo errMsg(400);
		break;
	}
	break;

default:
	echo errMsg(403);
	break;
}
?>