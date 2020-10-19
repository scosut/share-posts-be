<?php
	header('Access-Control-Allow-Origin: *');

	# load config
	require_once "../config/index.php";

	# load Database
	require_once "../classes/Database.php";

	# load User
	require_once "../classes/User.php";

	# load Validate
	require_once "../classes/Validate.php";

	$rest_json = file_get_contents("php://input");
	$_POST     = json_decode($rest_json, true);
	$_POST     = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	$obj        = new User();

	$data = Validate::setProperties(array_keys($_POST), $_POST);

	if (Validate::isNotEmpty($data->email, "Please enter email.")) {
		Validate::toggleError($data->email, !$obj->findUserByEmail($data), "No user found with that email address.");
	}

	Validate::isNotEmpty($data->password, "Please enter password.");

	if (Validate::isValid($data)) {
		// check and set logged in user
		$loggedInUser = $obj->login($data);
		
		if ($loggedInUser) {
			echo json_encode(['succeeded' => true, 'message' => 'You are logged in.', 'user' => $loggedInUser]);
		}
		else {
			Validate::toggleError($data->password, true, "Incorrect password.");
			echo json_encode(['succeeded' => false, 'message' => 'Errors', 'errors' => $data]);
		}
	}
	else {
		echo json_encode(['succeeded' => false, 'message' => 'Errors', 'errors' => $data, 'target' => Validate::getFirstError($data)]);
	}
?>