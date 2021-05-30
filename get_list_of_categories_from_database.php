<?php

	function PrintData_from_database($MyData, $ParentIdReady){
		$response = array();

		$response["categories"] = array();
		
		while ( ($row = $MyData -> fetch_assoc()) != false ){
			$category = array();
			$category["name"] = $row["name"];

			array_push($response["categories"], $category);
		}

		$response["success"] = 1;
		$response["parent_id"] = $ParentIdReady;
		echo json_encode($response);	
	}



	function GetSortCategory($mySort){
		$response = array();
		
		while ( ($row = $mySort -> fetch_assoc()) != false ){
			$response["sort"] = $row["sort"];
		}

		return $response;
	}

	function GetParent_idCategory($Parent_idSend){
		$response = "";
		
		while ( ($row = $Parent_idSend -> fetch_assoc()) != false ){
			$response = $row["parent_id"];
		}

		return $response;
	}



	
	$POST_is_here = isset($_POST);
	if ($POST_is_here == true){
		
		$ValuePOST = file_get_contents('php://input');
		$MyArray = json_decode($ValuePOST, true);
		$ValueRequest = $MyArray['Name'];

		if ($ValueRequest == "0"){
			require 'db_config.php';
			$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			$mysqli -> query ("SET NAMES 'utf8'");

			$MyData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `parent_id` = '0' ");
			$Parent_idSend = $mysqli -> query ("SELECT `parent_id` FROM `catalog` WHERE `parent_id` = '0' GROUP BY `parent_id` ");
			$ParentIdReady = GetParent_idCategory($Parent_idSend);
			PrintData_from_database($MyData, $ParentIdReady);

			$mysqli -> close();
		}
		else{
			$MyParent_id = $MyArray['parent_id'];
			require 'db_config.php';
			$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			$mysqli -> query ("SET NAMES 'utf8'");


			$mySort = $mysqli -> query ("SELECT `sort` FROM `catalog` WHERE `name` = '$ValueRequest' AND `parent_id` = '$MyParent_id' ");
			$mySorts = array();
			$countSlesh = 0;
			$mySorts = GetSortCategory($mySort);
			$ourSort = $mySorts["sort"];
			$countSlesh = substr_count($ourSort, '/');
			if ($countSlesh == 1){
				$ourSort = $ourSort . "/__________";
				$myData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `sort` LIKE '$ourSort' ");
				$Parent_idSend = $mysqli -> query ("SELECT `parent_id` FROM `catalog` WHERE `sort` LIKE '$ourSort' GROUP BY `parent_id` ");
				$ParentIdReady = GetParent_idCategory($Parent_idSend);
				PrintData_from_database($myData, $ParentIdReady);
			}
			elseif ($countSlesh == 2) {
				$ourSort = $ourSort . "/%";
				$myData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `sort` LIKE '$ourSort' ");
				$Parent_idSend = $mysqli -> query ("SELECT `parent_id` FROM `catalog` WHERE `sort` LIKE '$ourSort' GROUP BY `parent_id` ");
				$ParentIdReady = GetParent_idCategory($Parent_idSend);
				PrintData_from_database($myData, $ParentIdReady);
			}
			$mysqli -> close();	
		}
	}	


?>