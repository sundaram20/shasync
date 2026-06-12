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

//echo $target_dir = $_SERVER['DOCUMENT_ROOT']."/sales/import/";

	$target_dir = "/var/www/vhosts/roomstatushub.in/httpdocs/sales/import/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

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
	
	
	 $userlevelId=$emapData[0];
	  $myownteam_id=$emapData[1]; 
	 $team=$emapData[2]; 
	 
	$name=$emapData[3]; 
	$email=$emapData[4]; 
	$Designation=$emapData[5]; 
	$Mobile=$emapData[6]; 
	$Phone=$emapData[7];
	//$paymentmode=$emapData[8]; 
	$Address=$emapData[9];  
	$city=$emapData[10];  
	$state=$emapData[11];
	$Zipcode=$emapData[12];
	
	$username= strtolower(str_replace(' ', '', $name));
	$password='123';
   
$addSql = "   	INSERT INTO `".TBL_USERS."` SET 

							`id_shop` = '2',

							`id_shop_group` = '1',
							
			                `ids_zone` = '27',

							`user_level` = '".addslashes($userlevelId)."',

							`name` = '".addslashes($name)."',

							`email` = '".addslashes($email)."',

							`username` = '".addslashes($username)."'";

			$addSql .= "	,`password` = '".base64_encode($password)."'";

			
			$addSql .= "	,`ids_team` = '".addslashes($team)."'";
			
			$addSql .= "	,`myownteam_id` = '".$myownteam_id."'";

			$addSql .= "	,`designation` = '".trim(addslashes($Designation))."'";

			$addSql .= "	,`phone` = '".addslashes($Phone)."'";

			$addSql .= "	,`mobile` = '".addslashes($Mobile)."'";

			$addSql .= "	,`company` = '".addslashes($_POST['company'])."'";

			$addSql .= "	,`address` = '".addslashes($Address)."'";

			
			$addSql .= "	,`city` = '".addslashes($city)."'";

		
			$addSql .= "	,`zip` = '".addslashes($Zipcode)."'";
			$addSql .= "	,`location` = '".addslashes($state)."'";

			
		

			$addSql .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							,`status` = '1'";
echo '<br><br>'.$addSql;
		
							//	executeSql($addSql);
								//$id_company = $db->insert_id();

	
	
	
	
    }                                             
}
echo "Sucessful";

}





?>