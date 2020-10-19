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
	$obj       = new User();

	$data = Validate::setProperties(array_keys($_POST), $_POST);

	Validate::isNotEmpty($data->name, "Please enter name.");
				
	if (Validate::isNotEmpty($data->email, "Please enter email.")) {
		if (Validate::isValidEmail($data->email, "Please enter valid email.")) {
			Validate::toggleError($data->email, $obj->findUserByEmail($data), "Email is already taken.");
		}
	}														 
				
	if (Validate::isNotEmpty($data->password, "Please enter password.")) {
		Validate::isMinLength($data->password, 6, "Password must be at least 6 characters.");
	}
				
	if (Validate::isNotEmpty($data->confirm, "Please confirm password.")) {
		Validate::doMatch($data->password, $data->confirm, "Passwords do not match.");
	}
				
	if (Validate::isValid($data)) {					
		# hash password
		$data->password->value = password_hash($data->password->value, PASSWORD_DEFAULT);
					
		# register user
		if ($obj->register($data)) {
				echo json_encode(['succeeded' => true, 'message' => 'You are registered and can log in.']);
		}
		else {
			echo json_encode(['succeeded' => false, 'message' => 'Problem registering user.']);
		}
	}
	else {
		echo json_encode(['succeeded' => false, 'message' => 'Errors', 'errors' => $data, 'target' => Validate::getFirstError($data)]);
	}
?>