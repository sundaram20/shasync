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
/*print_r($_REQUEST);
print_r($_FILES['fileToUpload']['name']);*/
	
	$target_dir = "/home/admingcs/public_html/crs/import/";
	
	//$target_dir = $_SERVER['DOCUMENT_ROOT']."/crsnew/import/";
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
		
											
					/*infants			=0;
					child					=0;			
					food_price				=0;
							
					original_product_price 	=0;
					$unique_code				='CCC';*/
					

	
	 

			$query3 		= "SELECT * FROM fs_rate_assign_details WHERE hotel_id ='".$id_hotels."' and rate_id='".$id_rate."' " ;	
			$result3 		= executeSql($query3,$link);						
			$query3data 			= mysql_fetch_array($result3);
			$fs_rate_assign_id		= trim(stripslashes($query3data['id']));
			 
			
				$query4 		= "SELECT * FROM fs_room_type WHERE  id_shop='".trim(stripslashes($id_shop))."' and name ='".$roomtype."'";
			 $result42 		= executeSql($query4,$link);
			
				$query4data = mysql_fetch_array($result42);
				
				$room_id = $query4data['id'];
			  
			//die;//
			/*echo "<br>";
			echo "order id".$id_order;
			echo "reference".$reference;
			echo "id_hotels".$id_hotels;
			echo "room_id".$room_id;*/
			
			if($id_order!='' && $reference!='' && $id_shop!='' && $id_hotels!='' &&  $room_id!='' ){

				//&& $id_rate!='' && $rate_plan_id!='' && $fs_rate_assign_id!=''){
			
				//$id_hotels."room_id=>".$room_id;
		//echo $dated	=	date('Y-m-d',strtotime($dated));
		  $Insert_into_Order_Details = "Insert into fs_order_detail(id_order,id_shop,hotel_id,room_id,rate_id,rate_plan_id,rate_assign_id,dated,type,room_quantity,adults,child,tarrif_price,food_price,extra_price,total_price,original_product_price,unique_code)Values('$id_order','$id_shop','$id_hotels','$room_id','$id_rate','$rate_plan_id','$fs_rate_assign_id','$dated','$type','$room_quantity','$adults','$child','$tarrif_price','$food_price','$extra_price','$total_price','$Base_AmountBeforeTax','$unique_code')";		
		
		$Insert_into_Order_Details_Run = executeSql($Insert_into_Order_Details);
		$in_last_id = mysql_insert_id();
		
		
		
		/* IN ORDER TABLE UPDATE TOTAL QTY */
		$SQL = "select `fs_order_detail`.`id_order` AS `ID_ORDER`,`fs_order_detail`.`dated` AS `DATED`,sum(`fs_order_detail`.`room_quantity`) AS `qty` from `fs_order_detail` where (`fs_order_detail`.`hotel_id` = '$id_hotels') and id_order='$id_order' group by `fs_order_detail`.`id_order`,`fs_order_detail`.`dated`";
		
		$result1 		= executeSql($SQL,$link);
while($query1data = mysql_fetch_array($result1)){
echo "<br>". $qty	=	$query1data['qty'];
$id_order	=	$query1data['ID_ORDER'];

echo "<br>". "UPDATE  fs_orders  SET total_products='".$qty."' WHERE id_order='".$id_order."'";

$updateprice = executeSql("UPDATE  fs_orders  SET total_products='".$qty."' WHERE id_order='".$id_order."' ");
}
		
		
		if($booking_status ==1 || $booking_status ==2){		
		$updateInventory = executeSql("UPDATE  `".TBL_INVENTORY."`  SET 
								crs_available = crs_available-'".$room_quantity."',
								blocked_hotel = blocked_hotel+'".$room_quantity."',
								online_allocation=online_allocation-'".$room_quantity."' 
								where `hotel_id`='".addslashes($id_hotels)."' and 
						  		`room_id`='".addslashes($room_id)."' and 
								allocation_date = '".addslashes(date("Y-m-d", strtotime($dated)))."'");
		}
	
		echo  "<br> <div style='background-color:green;'>$count Iserted ID".$in_last_id."</div>";
		/*echo  "<br> $count Iserted ID= ".$in_last_id."reference=>".$reference."id_order=>".$id_order." Rate id=>".$id_rate."rate_plan_id==>".$rate_plan_id."fs_rate_assign_id==>".$fs_rate_assign_id."room_id==>".$room_id.'room_quantity'.$room_quantity;*/
		
		}else{
		
		
		echo  "<br>  <div style='background-color:red;'>$count Not Iserted ID= ".$in_last_id."reference=>".$reference."id_order=>".$id_order." Rate id=>".$id_rate."rate_plan_id==>".$rate_plan_id."fs_rate_assign_id==>".$fs_rate_assign_id."room_id==>".$room_id.'room_quantity'.$room_quantity."</div>";
		
			
			}	
		
		
		
		
    
	
	
	
	}else{
	
	echo  '<div style="font-color:red;"><br>'.$count.'Not Iserted ID='.$in_last_id."reference=>".$reference."id_order=>".$id_order." Rate id=>".$id_rate."rate_plan_id==>".$rate_plan_id."fs_rate_assign_id==>".$fs_rate_assign_id."room_id==>".$room_id.'room_quantity'.$room_quantity.'</div>';
	
	}
	
	
	
	}
	echo "<br>".$count;                                             
}echo "Sucessful";

echo "Sucessful";

	//	}

?>