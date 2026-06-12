<?php include_once("../config/fron_autoload.php"); 

?>

<!DOCTYPE html>
<html>
<body>
<div style="text-align:center;">
<lable>ORDER DETAILS</lable><br/><br/>
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

	$target_dir = "/home/admingcs/public_html/crs/import/";
	//$target_dir = $_SERVER['DOCUMENT_ROOT']."/crs/import/";
	
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }



	  $csv_file  = $_FILES['fileToUpload']['name'];
	//$csv_file  = "fernhillsDETAILS01-04-2018.csv";

				$fieldseparator = ",";
				$lineseparator = "\n";
				
				
				$file = fopen($csv_file, "r");
//$sql_data = "SELECT * FROM prod_list_1 ";

			 $count = 1;                               
          // add this line

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

while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
	{
    
    $count++;                                      // add this line
	if($count>1){   
	//print_r($emapData);
	
	$reference =	$emapData[0];$bookingdate	=	$emapData[1]; $roomtype	=	$emapData[2];  $roomquantity	=	$emapData[3];  $roomrate	=	$emapData[4];  $adult	=	$emapData[5];  $hotelname	=	$emapData[6];  $rateplanid	=	$emapData[7]; 
	

 $exp	=	explode('/',$bookingdate);
 $Day	=	$exp[0];
 $Month	=	$exp[1];
 $year	=	$exp[2];
	

 $dated  = ($year.'-'.$Month.'-'.$Day);	
 								
$room_quantity		=	$roomquantity;
$adults				=	$adult;
					
$rate_plan_id		=	$rateplanid;
$Base_AmountBeforeTax			=$roomrate;
 
		 $query1 		= "SELECT * FROM  fs_orders WHERE other_reference ='".$reference."'" ;
		$result1 		= executeSql($query1,$link);
			
			$query1data = mysql_fetch_array($result1);
					$id_order 			= 	trim(stripslashes($query1data['id_order']));					
					$id_shop 			= 	trim(stripslashes($query1data['id_shop']));
					$id_hotels			=	trim(stripslashes($query1data['id_hotel']));
					$id_rate			=	trim(stripslashes($query1data['id_rate']));
					$booking_status		=	trim(stripslashes($query1data['booking_status']));	
					$no_of_days		=	trim(stripslashes($query1data['no_of_days']));	
						
					
		$subtotal =($roomrate*no_of_days);
		$total_price		=	($roomrate*no_of_days);
 		$tarrif_price		=	$roomrate;
		
											
					

	
	 

			$query3 		= "SELECT * FROM fs_rate_assign_details WHERE hotel_id ='".$id_hotels."' and rate_id='".$id_rate."' " ;	
			$result3 		= executeSql($query3,$link);						
			$query3data 			= mysql_fetch_array($result3);
			$fs_rate_assign_id		= trim(stripslashes($query3data['id']));
			 
			
			$query4 		= "SELECT * FROM fs_room_type WHERE  id_shop='".trim(stripslashes($id_shop))."' and name ='".$roomtype."'";
			 $result42 		= executeSql($query4,$link);
			
				$query4data = mysql_fetch_array($result42);
				
				$room_id = $query4data['id'];
			  
			//die;//
			
			
			if($id_order!='' && $reference!='' && $id_shop!='' && $id_hotels!='' &&  $room_id!='' ){

				$queryUP 		= "SELECT * FROM fs_order_detail WHERE  id_shop='".trim(stripslashes($id_shop))."' and id_order ='".$id_order."' AND room_id='".$room_id."'";
			 $resultUP 		= executeSql($queryUP,$link);
			
				$query4data = mysql_fetch_array($resultUP);
				
				echo "<br>";
				echo "UPDATE  fs_order_detail  SET  rate_plan_id='".$rate_plan_id."' WHERE id_shop='".trim(stripslashes($id_shop))."' and id_order ='".$id_order."' AND room_id='".$room_id."'";
				$updateprice = executeSql("UPDATE  fs_order_detail  SET  rate_plan_id='".$rate_plan_id."' WHERE id_shop='".trim(stripslashes($id_shop))."' and id_order ='".$id_order."' AND room_id='".$room_id."'");
		}	
		
		
		
		
	
	
	}else{
	
	//echo  '<br><div style="font-color:red;"><br>'.$count.'Not Iserted ID='.$in_last_id."reference=>".$reference."id_order=>".$id_order." Rate id=>".$id_rate."rate_plan_id==>".$rate_plan_id."fs_rate_assign_id==>".$fs_rate_assign_id."room_id==>".$room_id.'room_quantity'.$room_quantity.'</div>';
	
	}
	
	
	
	}                                             
}echo "Sucessful";

echo "Sucessful";

	//	}

?>