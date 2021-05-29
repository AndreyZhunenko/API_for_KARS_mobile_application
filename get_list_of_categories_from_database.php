<?php

	function PrintData_from_database($MyData){
		$response = array();

		$response["categories"] = array();
		
		while ( ($row = $MyData -> fetch_assoc()) != false ){
			$category = array();
			$category["name"] = $row["name"];

			array_push($response["categories"], $category);

			
			
		}

		$response["success"] = 1;
		echo json_encode($response);
		
	}
	
	$POST_is_here = isset($_POST);
	if ($POST_is_here == true){
		
		$ValuePOST = file_get_contents('php://input');
		$MyArray = json_decode($ValuePOST, true);
		$ValueRequest = $MyArray['Priority_1'];

		if ($ValueRequest == "0"){
			require 'db_config.php';
			$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			$mysqli -> query ("SET NAMES 'utf8'");

			$MyData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `parent_id` = '0' ");
			PrintData_from_database($MyData);

			$mysqli -> close();
		}
	}	


?>