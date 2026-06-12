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

print_r($_FILES['fileToUpload']['name']);

//echo $target_dir = $_SERVER['DOCUMENT_ROOT'].'/crs/import/';
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
	
	
	  $bookingnumber=$emapData[0]; 
	 
	 
	 
   
			
			
	echo "<br>";
	
	$query4 = "SELECT * FROM fs_orders WHERE   other_reference='".$bookingnumber."'";
	$result42 		= mysql_query($query4);
	$CheckResultRow	= mysql_num_rows($result42);
	if($CheckResultRow	==1){
	
	$query4data = mysql_fetch_array($result42);
	
	
//echo "UPDATE  fs_orders  SET  type='L' WHERE other_reference='".$bookingnumber."' and id_shop ='".addslashes($_SESSION['shop'])."'";

echo "Record Count=".$count."   Insert Value = ".$updateprice = executeSql("UPDATE  fs_orders  SET  type='L' WHERE other_reference='".$bookingnumber."' and id_shop ='".addslashes($_SESSION['shop'])."'");
    
	}
	
		
			
			
				
		
	

		
		
    }                                             
}
echo "Sucessful";

}





?>