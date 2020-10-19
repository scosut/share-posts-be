<?php
	header('Access-Control-Allow-Origin: *');

	# load config
	require_once "../config/index.php";

	# load Database
	require_once "../classes/Database.php";

	# load Post
	require_once "../classes/Post.php";

	$rest_json = file_get_contents("php://input");
	$_POST     = json_decode($rest_json, true);
	$id        = $_POST["id"];

	$obj = new Post();

	if ($obj->deletePost($id)) {
		echo json_encode(['succeeded' => true, 'message' => 'Post successfully removed.']);
	}
	else {
		echo json_encode(['succeeded' => false, 'message' => 'Problem deleting post.']);
	}
?>