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

		print_r($response);
		//echo json_encode($response);	
	}

	function GetSortCategory($mySort){
		$response = array();
		
		while ( ($row = $mySort -> fetch_assoc()) != false ){
			$response["sort"] = $row["sort"];
		}

		//$response["success"] = 1;
		//echo json_encode($response);	
		return($response);
	}

		//$ValueRequest = "Предметы интерьера";
		$ValueRequest = "Подарки к праздникам";

		if ($ValueRequest == "0"){
			require 'db_config.php';
			$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			$mysqli -> query ("SET NAMES 'utf8'");

			$MyData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `parent_id` = '0' ");
			PrintData_from_database($MyData);

			$mysqli -> close();
		}
		else{
			require 'db_config.php';
			$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			$mysqli -> query ("SET NAMES 'utf8'");

			$mySort = $mysqli -> query ("SELECT `sort` FROM `catalog` WHERE `name` = '$ValueRequest' ");
			$mySorts = array();
			$countSlesh = 0;
			$mySorts = GetSortCategory($mySort);

			print_r($mySorts);
			echo "<br>";

			$ourSort = $mySorts["sort"];
			$countSlesh = substr_count($ourSort, '/');
			/*for ($i=0; $i < strlen($ourSort) ; $i++) { 
				if ($ourSort[i] == '/'){
					$countSlesh = $countSlesh + 1;
				}
			}*/
			if ($countSlesh == 1){
				$ourSort = $ourSort . "/__________";
				//echo $ourSort;
				//echo "<br>";
				$myData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `sort` LIKE '$ourSort' ");
				PrintData_from_database($myData);
			}
			elseif ($countSlesh == 2) {
				$ourSort = $ourSort ."/%";
				$myData = $mysqli -> query ("SELECT `name` FROM `catalog` WHERE `sort` LIKE '$ourSort' ");
				PrintData_from_database($myData);
			}
			$mysqli -> close();	
		}

?>