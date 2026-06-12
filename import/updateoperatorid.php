<?php include_once("../config/fron_autoload.php"); 

?>

<!DOCTYPE html>
<html>
<body>
<div style="text-align:center;">
<lable>FS ORDER</lable><br/><br/>
<form action="" method="post" enctype="multipart/form-data">
    Select csv to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br/><br/>
    <input type="submit" value="Upload csv" name="submit">
</form>
</div>
</body>
</html>
<?php

//print_r($_REQUEST);

if($_REQUEST['submit']	==	'Upload csv'){
print_r($_REQUEST);
print_r($_FILES['fileToUpload']['name']);

// $target_dir = $_SERVER['DOCUMENT_ROOT'].'/crs/import/';
	//$target_dir = "/home/admingcs/public_html/crs/import/";
	$target_dir = "/home/admingcs/public_html/crs/import/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	
	//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
		
//$csv_file = "fernhillsP01-04-2018.csv";

$csv_file = $_FILES['fileToUpload']['name'];

				$fieldseparator = ",";
				$lineseparator = "\n";
				
				
				$file = fopen($csv_file, "r");
//$sql_data = "SELECT * FROM prod_list_1 ";

$count = 1;                                         // add this line

				if(!file_exists($csv_file)) {
					echo "File not found. Make sure you specified the correct path.\n";
					exit;
				}
				
				$file = fopen($csv_file,"r");
				
				if(!$file) {
					echo "Error opening data file.\n";
					exit;
				}
				
				$size = filesize($csv_file);
				
				if(!$size) {
					echo "File is empty.\n";
					exit;
				}
$CountInc=1;
		while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
{
    //echo "<pre>";print_r($emapData);
    //exit();
    $count++;                                      // add this line

    if($count>1){   
	
	
	 $hotelname=$emapData[0]; $bookingnumber=$emapData[1]; $bookingdate=$emapData[2];  $checkin=$emapData[3];  $checkout=$emapData[4];  $agentname=$emapData[5];  $gustname=$emapData[6];  $countryname=$emapData[7];  $paymentmode=$emapData[8];  $planning=$emapData[9];  $status=$emapData[10];  $specialins=$emapData[11];  $single=$emapData[12]; $double=$emapData[13]; $triple=$emapData[14]; $FGCP=$emapData[15]; $ARRIVING_FROM=$emapData[16]; $DEPARTING_TO=$emapData[17];  $TRAVEL_MODE=$emapData[18];  $RATE_TYPE=$emapData[19]; $USER_Name=$emapData[20]; $NEXT_ID=$emapData[21]; $PICKYN=$emapData[22];  $BOOKING_THROUGH=$emapData[23];  $STATUS_FLAG=$emapData[24];  $no_of_days=$emapData[25];  $DEAR1=$emapData[26]; $BS=$emapData[28];
 $RoomDetails=$emapData[29]; $BOOKER_EMAIL_ID=$emapData[30];$BOOKER_CONTACT_NUMBER=$emapData[31];
   $payment_status=$emapData[34];
   $payment_date=$emapData[35];$pickup_details=$emapData[36];$pickup=$emapData[37];$amount_received=$emapData[38];$payment_reference=$emapData[39];
 	$operator_name=$emapData[40];
   $series_name	=	$emapData[41];
 
	 
	 
	 
    $id_shop=$emapData[32];
	

		
		
		
		
	

/********************************************************/	


if($operator_name !=''){
		$CheckOperaterID = "SELECT * FROM `".TBL_OPERATOR_MASTER."` WHERE `name` = '".addslashes($operator_name)."' and id_shop='".$id_shop."'" ;
		$OperatorResult = executeSql($CheckOperaterID,$link);
		$OperatorQueryCount = mysql_num_rows($OperatorResult);
		$opequery4data = mysql_fetch_array($OperatorResult);
if($OperatorQueryCount != '0'){
		   
	 $operator_id=$opequery4data['id'];
		   
}else{

		   /*$OperatorInsert = "   	INSERT INTO `".TBL_OPERATOR_MASTER."` SET 
							`name` = '".addslashes($operator_name)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1'";
			 $OperatorInsert .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
			executeSql($OperatorInsert);
					echo "222".$operator_id = $db->insert_id();*/
				
				
	}
}else{
	
	$operator_id	=0;
	}


if($series_name!=''){
 $CheckSeriesID = "SELECT * FROM `".TBL_SERIES_MASTER."` WHERE `name` = '".addslashes($series_name)."' and id_shop='".$id_shop."'" ;
		$SeriesResult = executeSql($CheckSeriesID,$link);
		 $SeriesQueryCount = mysql_num_rows($SeriesResult);
		 $Seriesquery4data = mysql_fetch_array($SeriesResult);
if($SeriesQueryCount != 0){
	
	 $series_id	=$Seriesquery4data['id'];
	
	}else{
		  
		 
		  /* 
		   $seriesSql = "   	INSERT INTO `".TBL_SERIES_MASTER."` SET 
							`name` = '".addslashes($series_name)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1'";
			 $seriesSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
			executeSql($seriesSql);
				$series_id = $db->insert_id();*/
}
}else{
	
	$series_id	=	0;
	}
/**************************************************************************/
	

			
			
	$query4 = "SELECT * FROM fs_orders WHERE   other_reference='".$bookingnumber."'";
	$result42 		= executeSql($query4,$link);

	$query4data = mysql_fetch_array($result42);

	
	if($series_id!=0 || $operator_id !=0){
			
		$type	=	'S';
		
	}else{
		
		
		echo "Type===".$query4data['type'];
		if($query4data['type']	=='L'){
			$type	=	'L';
		}else{
			$type	=	'N';
			}
		
		}
	
	
	
echo "<br>UPDATE  fs_orders  SET  series_id='".$series_id."',operator_id='".$operator_id."', type='".$type."' WHERE other_reference='".$bookingnumber."' and id_shop	='".$id_shop."'";
			
		
		
								 
			echo "Record Count".$count."Insert Value = ".$updateprice = executeSql("UPDATE  fs_orders  SET series_id='".$series_id."',operator_id='".$operator_id."', type='".$type."'  WHERE other_reference='".$bookingnumber."' and id_shop ='".$id_shop."'");
    
	//}
	
		
			
			
				
		
	

		
		
    }                                             
}
echo "Sucessful";

}





?>