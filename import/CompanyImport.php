<?php //include_once("../config/fron_autoload.php"); 

$DB_HOST='ls-73c1d44d0baaf1a357e5233ea2688df20d6ae29b.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306';
$DB_USERNAME='sayaji_hotels';
$DB_PASSWORD='$sayaji@2021';
$DB_NAME='sayaji';
$connNew2=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);



?>
<!DOCTYPE html>
<html>
<body>
<div style="text-align:center;">
<lable>Company Import</lable><br/><br/>
<form action="" method="post" enctype="multipart/form-data">
    Select csv to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br/><br/>
    <input type="submit" value="Upload csv" name="submit">
</form>
</div>
</body>
</html>
<?php

if($_REQUEST['submit']	==	'Upload csv'){


	 //$target_dir = $_SERVER['DOCUMENT_ROOT']."/crs/import/";

	$target_dir = "/var/www/vhosts/roomstatushub.in/httpdocs/sales/import/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
	
	$csv_file = $_FILES['fileToUpload']['name'];
	$fieldseparator = ",";
	$lineseparator = "\n";
	$file = fopen($csv_file, "r");
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
    	$count++;                                      // add this line
//echo "<pre>";print_r($emapData);
   
    	if($count>=1){   
	
				$companyName=$emapData[0]; 
				$id_default_group=$emapData[1]; 
				$address=$emapData[2];  
				$area=$emapData[3];  
				
				$id_state=$emapData[4];	 
				$city=$emapData[5];  
				$id_country=$emapData[6]; 
				$postcode=$emapData[7]; 
				
				$Executive=$emapData[8];
				$email=$emapData[9];	
				$secondary_email=$emapData[10];
				$phone=$emapData[11];
				$mobile=$emapData[12];				
				$CompanyCredibility=$emapData[13];	

			
			
	  $queryCompany = "SELECT area,id_company FROM fs_company WHERE name LIKE '%".$companyName."%' and id_shop='2'" ;


$resultCompany = mysqli_query($connNew2,$queryCompany);
 $NumberCompany = mysqli_num_rows($resultCompany);

if($NumberCompany=='0'){		
			
	  $addNewCompanyName= "  INSERT INTO fs_company SET 
											`id_rate_level`='',
											`other_state`='',
											`fax`='',
											`gst_no`='',
											`credit_limit`='0',
											`deals_in`='',
											
											`id_shop`='2',
											`id_shop_group` = '1',													
											`booking` = '1'
											,`status` = '1'
											,`name` = '".strtoupper($companyName)."',
											`id_default_group` = '".$id_default_group."',
											`address` = '".$address."',
											
											`area` = '".$area."',
											`id_state` = '".$id_state."',							
											`city` = '".$city."',
											`id_country` = '110',
											`postcode` = '".$postcode."',
											
											
											`email` = '".$email."',
											`secondary_email` = '".$secondary_email."',
											`phone` = '".$phone."',
											`mobile` = '".$mobile."',	
											`company_credibility` = '".$CompanyCredibility."'
											,`details` = 'imported'
											,`id_lang` = '1'
											,`date_created` = '2021-10-06 11:13:15'
											,`created_by` = '245'
											,`last_modified` = '2021-10-06 11:13:15'
											,`last_modified_by` = '245'";
			
								$InsertSucess	=	 mysqli_query($connNew2,$addNewCompanyName);
								if($InsertSucess==1){
								echo $count.' - Sucessful Record <br>';
								}else{
								echo '<p style="color:red;font-weight:bold;">Error'.$companyName.'</p><br>';
								}


	
	
}else{
echo '<p style="color:Green;font-weight:bold;">Company Already Exist.'.$companyName.'</p><br>';
}
		
    }                                             
}
echo "Sucessful";

}


?>