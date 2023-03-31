<?php
	require_once '../Context.php';

	function validateRequests($params){
		$available = true;
		$missingparams = "";

		foreach($params as $param){
			if(!isset($_GET[$param]) || strlen($_GET[$param])<=0){
				$available = false;
				$missingparams = $missingparams . ", " . $param;
			}
		}

		if(!$available){
			$response = array();
			$response['error'] = true;
			$response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';

			echo json_encode($response);
			die();
		}
	}

	$response = array();

	if(isset($_GET['request'])){

		switch($_GET['request']){
			case 'add':
				validateRequests(array('name','email','location','contact'));

				$db = new Context();
				$result = $db->createPerson(
					$_GET['name'],
					$_GET['email'],
					$_GET['location'],
					$_GET['contact']
				);

				if($result){
					$response['error'] = false;
					$response['message'] = 'Person added successfully';
					$response['persons'] = $db->getPersons();
				}else{
					$response['error'] = true;
					$response['message'] = 'Some error occurred please try again';
				}

			break;
			case 'get':
				$db = new Context();
				$response['error'] = false;
				$response['message'] = 'Request successfully completed';
				$response['data'] = $db->getPersons();
			break;

			case 'update':
				validateRequests(array('id','name','email','location','contact'));
				$db = new Context();
				$result = $db->deletePerson(
					$_GET['id'],
					$_GET['name'],
					$_GET['email'],
					$_GET['location'],
					$_GET['contact']
				);

				if($result){
					$response['error'] = false;
					$response['message'] = 'Hero updated successfully';
					$response['data'] = $db->getPersons();
				}else{
					$response['error'] = true;
					$response['message'] = 'Some error occurred please try again';
				}
			break;

			case 'delete':

				if(isset($_GET['id'])){
					$db = new Context();
					if($db->deletePerson($_GET['id'])){
						$response['error'] = false;
						$response['message'] = 'Person deleted successfully';
						$response['data'] = $db->getPersons();
					}else{
						$response['error'] = true;
						$response['message'] = 'Some error occurred please try again';
					}
				}else{
					$response['error'] = true;
					$response['message'] = 'Invalid request id';
				}
			break;
		}

	}else{
		$response['error'] = true;
		$response['message'] = 'Invalid request defined';
	}

	echo json_encode($response);
