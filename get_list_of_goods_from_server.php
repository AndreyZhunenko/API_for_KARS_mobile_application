<?php
	
	require 'ProductStructure.php';
	$response = array();

	
	function Get_goods_from_server($myGoods){
		$response["goods"] = array();
		
		while ( ( $row = $myGoods -> fetch_assoc() ) != false ){

			$My_product = new ProductStructure();
			$My_product -> name = $row["name"];
			$My_product -> pnt = $row["pnt"];
			$My_product -> price = $row["price"];

			array_push($response["goods"], $My_product);

		}

		if ( empty($response["goods"]) == false ){
			echo json_encode($response);
		}
		else{
			$response["Error"] = "-1";
			echo json_encode($response);
		}
	}


	$POST_is_here = isset($_POST);
	if ($POST_is_here == true){
		
		$ValuePOST = file_get_contents('php://input');
		$MyArray = json_decode($ValuePOST, true);

		$idCategory = $MyArray['IDcategory'];
		//$idCategory = "855";


		require 'db_config.php';
		$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$mysqli -> query ("SET NAMES 'utf8'");

		$myGoods = $mysqli -> query("SELECT 
			`goods`.`name`, `goods`.`pnt`, `goods`.`price`
 									FROM 
			`goods`, `catalog`, `goods_in_catalog` 
									WHERE 
			`goods`.`pnt` = `goods_in_catalog`.`pnt` 
									AND 
			`catalog`.`id` = '$idCategory' 
									AND 
			`catalog`.`id` = `goods_in_catalog`.`catalog` ");


		Get_goods_from_server($myGoods);

	}










?>