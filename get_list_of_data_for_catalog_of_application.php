<?php
	
	$response = array();

	function PrintData_from_database($MyData, $CountSlesh){
		
		$response["categories"] = array();
		
		while ( ( $row = $MyData -> fetch_assoc() ) != false ){

			$category = $row["name"];
			array_push($response["categories"], $category);

		}

		if ( empty($response["categories"]) == false ){
			$response["CountSlesh"] = $CountSlesh;
			echo json_encode($response);
		}
		else{
			$response["Error"] = "-1";
			echo json_encode($response);
		}
			
	}

	function GetSortCategory($mySort){
		$response = "";
		
		while ( ($row = $mySort -> fetch_assoc()) != false ){
			$response = $row["sort"];
		}

		return $response;
	}

	
	$POST_is_here = isset($_POST);
	if ($POST_is_here == true){
		
		$ValuePOST = file_get_contents('php://input');
		$MyArray = json_decode($ValuePOST, true);
		$NameCategory = $MyArray['Name'];
		$CountSlesh = $MyArray['CountSlesh'];

		if ($NameCategory == "0"){
			require 'db_config.php';
			$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			$mysqli -> query ("SET NAMES 'utf8'");

			$MyData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `parent_id` = '0' ");
			$CountSlesh = "1";
			PrintData_from_database($MyData, $CountSlesh);

			$mysqli -> close();
		}
		switch ($CountSlesh) {
			case '1':
				require 'db_config.php';
				$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
				$mysqli -> query ("SET NAMES 'utf8'");

				$mySort = $mysqli -> query("SELECT `sort` FROM `catalog` WHERE `name` = '$NameCategory' AND `sort` LIKE '0/__________' ");
				$sortCategory = GetSortCategory($mySort);
				$sortCategory = $sortCategory . "/__________";
				$myData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `sort` LIKE '$sortCategory' ");
				$CountSlesh = "2";
				PrintData_from_database($myData, $CountSlesh);

				$mysqli -> close();
				break;

			case '2':
				require 'db_config.php';
				$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
				$mysqli -> query ("SET NAMES 'utf8'");

				$mySort = $mysqli -> query("SELECT `sort` FROM `catalog` WHERE `name` = '$NameCategory' AND `sort` LIKE '0/__________/__________' ");
				$sortCategory = GetSortCategory($mySort);
				$sortCategory = $sortCategory . "/__________";
				$myData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `sort` LIKE '$sortCategory' ");
				$CountSlesh = "3";
				PrintData_from_database($myData, $CountSlesh);

				$mysqli -> close();
				break;

			case '3':
				require 'db_config.php';
				$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
				$mysqli -> query ("SET NAMES 'utf8'");

				$response["Error"] = "-1";
				echo json_encode($response);

				$mysqli -> close();
				break;
		}
	}	
	

?>