<?php
include_once("../../config/auto_loader.php");
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

if(!empty($_FILES["excelImport"]["tmp_name"])){		
		
		$filename=$_FILES["excelImport"]["tmp_name"];		
  
		if($_FILES["excelImport"]["size"] > 0){
		  	$file = fopen($filename, "r");
		  	$x=0;
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){
	         	if($x==0){
	         		//just to skip the first row
	         	}
	            else{
	            	//setting Values to insert
	            	
	            	$shopId = $_SESSION['shop'];
	            	$userName = trim($getData[0]);
	            	$userId = 0 ;
	            	$hotelName = trim($getData[1]);
	            	$hotelId = 0 ;
	            	$month = date("Y-d-m",strtotime($getData[2]));
	            	$roomNights = trim($getData[3]);
	            	$value = trim($getData[4]);
	            	$createdDate = date("Y-m-d h:i:sa", strtotime("today"));
	            	$modifiedBy = $_SESSION['userId'];
	            	$seasonId = $_REQUEST['seasonImp'];
	            	
	            	//Getting UserID through UserName
	            	$sql = "SELECT `id` FROM `fs_users` WHERE `username` = '".$userName."' AND id_shop=".$_SESSION['shop']." ";
	            	$res = mysqli_query($conn,$sql);
	            	if(mysqli_num_rows($res) > 0){
	            		$row= mysqli_fetch_row($res);
	            		$userId = $row[0];
	            	}
	            	else{
	            		echo json_encode("User or Executive name Invalid OR Empty : ".$userName." in Row : ".$x." Number Of records Impoted Succefuly : ".($x-1));
	            		exit;
	            	}

	            	//Getting Hotel ID Through Hotel Name
	            	$sql = "SELECT `id` FROM `fs_hotels` WHERE `name` = '".$hotelName."' AND id_shop =".$_SESSION['shop']." ";
	            	$res = mysqli_query($conn,$sql);
	            	if(mysqli_num_rows($res) > 0){
	            		$row = mysqli_fetch_row($res);
	            		$hotelId = $row[0];
	            	}
	            	else{
	            		echo json_encode("Hotel Name ".$hotelName." is Invalid OR Empty in Row : ".$x." Number Of records Impoted Succefuly : ".($x-1));
	            		exit;
	            	}
	            	
	            	//Inserting Data
	               $sql = "INSERT INTO `fs_budget_master`(`id_shop`, `id_hotel`, `id_user`, `seasonId`, `qty`, `month_value`, `month`, `date_created`, `last_modified`, `last_modified_by`, `status`)
	            		VALUES(".$shopId.",".$hotelId.",".$userId.",".$seasonId.",".$roomNights.",".$value.",'".$month."','".$createdDate."','".$createdDate."',".$modifiedBy.",1)";
	            	
	            	$resFinal = mysqli_query($conn,$sql);
	            	
	            	if(!$resFinal){
	            		echo json_encode("There is some problem while importing data of Row: ".$x." Number Of records Impoted Succefuly : ".($x-1));
	            		exit;
	            	}
	            }
	        	$x++;
	        }
			
	         fclose($file);	
	         echo json_encode("Number of Records Impoterd Successfully : ".($x-1));
		}
		else{
			echo json_encode("File is empty ! Kindly Check");
		}
}
else{
	echo json_encode("You Have not selected any file !");
} 
mysqli_close($conn);
?>