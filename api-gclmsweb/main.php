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
		echo je($auth->faculty_login($d));
		break;

	#forum related request
	#...
	#end of forum related requests

	#announcement module requests
	#..

	case "announcements":
		if ($auth->authorized()) {
			echo je($get->get_announcement());
		} else {
			echo errMsg(401);
		}
		break;

	// case "addannouncement":
	// 	$d = jd(base64_decode(file_get_contents("php://input")));
	// 	if ($auth->authorized()) {
	// 		echo je($post->add_announcement($d));
	// 	} else {
	// 		echo errMsg(401);
	// 	}

	case "addannouncement":
		$d = jd(file_get_contents("php://input"));
		if ($auth->authorized()) {
			echo je($post->add_announcement($d));
		} else {
			echo errMsg(401);
		}

		break;

	case "editannouncement":
		$d = jd(base64_decode(file_get_contents("php://input")));
		if ($auth->authorized()) {
			echo je($post->edit_announcement($d));
		} else {
			echo errMsg(401);
		}
		break;

	case "uploadfile":
		if ($auth->authorized()) {
			echo je($post->process_upload());
		} else {
			echo errMsg(401);
		}
		break;

	#start of new api CLASSROOM MODULES
	#

	case "facultyclass":
		if ($auth->authorized()) {
			echo je($get->get_facultyclassroom($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "classpost":
		if ($auth->authorized()) {
			echo je($get->get_classpost($req[1]));
		} else {
			echo errMsg(401);
		}
		break;

	case "addclasspost":
		$d = jd(base64_decode(file_get_contents("php://input")));
		if ($auth->authorized()) {
			echo je($post->add_classpost($d));
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

	case "classcomments":
		if ($auth->authorized()) {
			echo je($get->get_classroomcomments($req[1]));
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

	#END OF CLASSROM MODULES!

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