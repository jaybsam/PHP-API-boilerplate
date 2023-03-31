<?php
class Context
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/Connection.php';
        $db = new Connection();
        $this->con = $db->connect();
    }

	/*
	* The create operation
	* When this method is called a new record is created in the database
	*/
	function createPerson($name, $email, $location, $contact){
		$stmt = $this->con->prepare("INSERT INTO persons (name, email, location, contact) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $name, $email, $location, $contact);
		if($stmt->execute())
			return true;
		return false;
	}

	/*
	* The read operation
	* When this method is called it is returning all the existing record of the database
	*/
	function getPersons(){
		$stmt = $this->con->prepare("SELECT id, name, email, location, contact FROM persons");
		$stmt->execute();
		$stmt->bind_result($id, $name, $email, $location, $contact);

		$heroes = array();

		while($stmt->fetch()){
			$hero  = array();
			$hero['id'] = $id;
			$hero['name'] = $name;
			$hero['email'] = $email;
			$hero['location'] = $location;
			$hero['contact'] = $contact;

			array_push($heroes, $hero);
		}

		return $heroes;
	}

	/*
	* The update operation
	* When this method is called the record with the given id is updated with the new given values
	*/
	function updatePerson($id, $name, $email, $location, $contact){
		$stmt = $this->con->prepare("UPDATE persons SET name = ?, email = ?, location = ?, contact = ? WHERE id = ?");
		$stmt->bind_param("ssssi", $name, $email, $location, $contact, $id);
		if($stmt->execute())
			return true;
		return false;
	}


	/*
	* The delete operation
	* When this method is called record is deleted for the given id
	*/
	function deletePerson($id){
		$stmt = $this->con->prepare("DELETE FROM persons WHERE id = ? ");
		$stmt->bind_param("i", $id);
		if($stmt->execute())
			return true;

		return false;
	}
}
