<?php
	header('Access-Control-Allow-Origin: *');

	# load config
	require_once "config/index.php";

	# load Database
	require_once "classes/Database.php";

	# load Post
	require_once "classes/Post.php";

	$obj = new Post();
	$posts = $obj->getPosts();

	foreach($posts as $post) {
		$post->postCreatedAt = date("n/j/Y g:i a", strtotime($post->postCreatedAt));
	}

	echo json_encode($posts);
?>