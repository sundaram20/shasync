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
	$target_dir = "/home/admingcs/public_html/crs/import/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
		
//$csv_file = "maharaniP01-04-2018.csv";

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
	
	
	 $hotelname=$emapData[0]; $bookingnumber=$emapData[1];  $payment_status=$emapData[34];
 
 
  
 
 
 

 $query4 = "SELECT reference,booking_status,payment_status,other_reference,date_created FROM fs_orders WHERE   other_reference='".$bookingnumber."' and date_created >='2018-04-07 18:23:16'
AND date_created <'2018-04-07 21:23:16'";
	$result42 		= executeSql($query4,$link);

	$query4data = mysql_fetch_array($result42);
	echo "<pre>";print_r($query4data);
	
echo "UPDATE  fs_orders  SET  payment_status='".$payment_status."' WHERE other_reference='".$bookingnumber."' and date_created >='2018-04-07 18:23:16'
AND date_created <'2018-04-07 21:23:16' ";
								 
			echo "Record Count".$count."Insert Value = ".$updateprice = executeSql("UPDATE  fs_orders  SET  payment_status='".$payment_status."' WHERE other_reference='".$bookingnumber."' and date_created >='2018-04-07 18:23:16'
AND date_created <'2018-04-07 21:23:16'");
    
	
	
			
		
		
		
		
    }                                             
}
echo "Sucessful";

}





?>