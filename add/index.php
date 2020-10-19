<?php
	header('Access-Control-Allow-Origin: *');

	# load config
	require_once "../config/index.php";

	# load Database
	require_once "../classes/Database.php";

	# load Post
	require_once "../classes/Post.php";

	# load Validate
	require_once "../classes/Validate.php";

	$rest_json = file_get_contents("php://input");
	$_POST     = json_decode($rest_json, true);
	$_POST     = filter_var_array($_POST, FILTER_SANITIZE_STRING);

	$data = Validate::setProperties(array_keys($_POST), $_POST);

	Validate::isNotEmpty($data->title, "Please enter title.");
				
	Validate::isNotEmpty($data->body, "Please enter body.");

	if (Validate::isValid($data)) {
		$obj = new Post();
		
		if ($obj->addPost($data)) {
			echo json_encode(['succeeded' => true, 'message' => 'Post successfully added.']);
		}
		else {
			echo json_encode(['succeeded' => false, 'message' => 'Problem adding post.']);
		}
	}
	else {
		echo json_encode(['succeeded' => false, 'message' => 'Errors', 'errors' => $data, 'target' => Validate::getFirstError($data)]);
	}
?>