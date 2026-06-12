<?php
include_once("../../config/auto_loader.php");
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

if(!empty($_FILES["companyImport"]["tmp_name"])){		
		
		$filename=$_FILES["companyImport"]["tmp_name"];		
  
		if($_FILES["companyImport"]["size"] > 0){
		  	$file = fopen($filename, "r");
		  	$x=0;
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){
	         	if($x==0){
	         		//just to skip the first row
	         	}
	            else{
	            	//setting Values to insert



	            	$id_shop_group = 1;
	            	$id_shop = $_SESSION['shop'];
	            	$id_lang =1;	            	
	            	$companyName = htmlentities(str_replace('�','',trim($getData[0])));
	            	$dealsIn = htmlentities(str_replace('�','',trim($getData[2])));
	            	$email =htmlentities(str_replace('�','',trim($getData[10]))) ;
	            	$secondaryEmail = htmlentities(str_replace('�','',trim($getData[11])));
	            	$postCode = htmlentities(str_replace('�','',trim($getData[6])));
	            	$city = htmlentities(str_replace('�','',trim($getData[5])));
	            	$address = htmlentities(str_replace('�','',trim($getData[7])));
	            	$phone =  htmlentities(str_replace('�','',trim($getData[12])));
	            	$mobile = htmlentities(str_replace('�','',trim($getData[13])));
	            	$details  = htmlentities(str_replace('�','',trim($getData[14])));
	            	$status = 1;
	            	$date_created= date('Y-m-d');
	            	$creditValue = htmlentities(str_replace('�','',trim($getData[15])));
	            	$lastModified = date('Y-m-d');

	            	
	            	
	            	/*echo "<br>".$x++." )".$id_shop = $_SESSION['shop'];
	            	echo "<br>".$x++."   )".$id_lang =1;	            	
	            	echo "<br>".$x++."   )".$companyName = trim($getData[0]);
	            	echo "<br>".$x++."   )".$dealsIn = trim($getData[2]);
	            	echo "<br>".$x++."   )".$email =trim($getData[10]) ;
	            	echo "<br>".$x++."   )".$secondaryEmail = trim($getData[10]);
	            	echo "<br>".$x++."   )".$postCode = trim($getData[6]);
	            	echo "<br>".$x++."   )".$city = trim($getData[5]);
	            	echo "<br>".$x++."   )".$address = trim($getData[7]);
	            	echo "<br>".$x++."   )".$phone =  trim($getData[12]);
	            	echo "<br>".$x++."   )".$mobile = trim($getData[13]);
	            	echo "<br>".$x++."   )".$details  = trim($getData[14]);
	            	echo "<br>".$x++."   )".$status = 1;
	            	echo "<br>".$x++."   )".$date_created= date('Y-m-d');
	            	echo "<br>".$x++."   )".$creditValue = trim($getData[15]);*/ 

	            	//echo "<br><br>";
	            	//$x=0;

	            	//fetching id default group
	            	$default_group_name = trim($getData[1]);

	            	$sql = "SELECT id_group FROM `fs_company_group` WHERE `id_shop`=".$id_shop." AND `name`='".$default_group_name."' ";
	            	$res = mysqli_query($conn,$sql);

	            	
	            	if($res){
	            		if(mysqli_num_rows($res)>0){
	            			$resData = mysqli_fetch_object($res);
	            			$id_group_got = $resData->id_group;
	            		}
	            		else{
	            			echo "<br>Error : at : ".$x."th data :Company Group :  <span style='color:red;'>".$default_group_name. "</span>  Not Found";
	            			exit;
	            		}
	            		
	            	}
	            	else{
	            		echo "SQL QUERY NOT EXECUTED !";
	            		exit;
	            	}

	            	
	            	//fetching id country

	            	$country = trim($getData[3]);

	            	$sql1 = "SELECT id_country FROM `fs_country_lang` WHERE  `name`='".$country."' ";
	            	$res1 = mysqli_query($conn,$sql1);

	            	
	            	if($res1){
	            		if(mysqli_num_rows($res1)>0){
	            			$resData1 = mysqli_fetch_object($res1);
	            			$id_country_got = $resData1->id_country;
	            		}
	            		else{
	            			echo "<br>Error : at : ".$x."th Data :Country : <span style='color:red;'>".$country. "</span>  Not Found";
	            			exit;
	            		}
	            		
	            	}
	            	else{
	            		echo "SQL QUERY NOT EXECUTED 1!";
	            		exit;
	            	}

	            	//fecthing id state

	            	$state = trim($getData[4]);

	            	$sql2 = "SELECT id_state FROM `fs_state` WHERE  `name`='".$state."' AND id_country=".$id_country_got." ";

	            	$res2 = mysqli_query($conn,$sql2);

	            	
	            	if($res2){
	            		if(mysqli_num_rows($res2)>0){
	            			$resData2 = mysqli_fetch_object($res2);
	            			$id_state_got = $resData2->id_state;
	            		}
	            		else{
	            			echo "<br>Error : at : ".$x."th Data : State : <span style='color:red;'>".$state. " For Country ".$country."</span>  Not Found";
	            			exit;
	            		}
	            		
	            	}
	            	else{
	            		echo "SQL QUERY NOT EXECUTED 2 !";
	            		exit;
	            	}



	            	//fetching area 

	            	$area = trim($getData[8]);

	            	$sql3 = "SELECT id FROM `fs_areas_assign` WHERE `id_shop`=".$id_shop." AND `name`='".$area."' ";

	            	$res3 = mysqli_query($conn,$sql3);

	            	
	            	if($res3){
	            		if(mysqli_num_rows($res3)>0){
	            			$resData3 = mysqli_fetch_object($res3);
	            			$id_area_got = $resData3->id;
	            		}
	            		else{
	            			echo "<br>Error : at : ".$x."th Data : Area : <span style='color:red;'>".$area. "</span>  Not Found";
	            			exit;
	            		}
	            		
	            	}
	            	else{
	            		echo "TABLE AREA : SQL QUERY NOT EXECUTED !";
	            		exit;
	            	}


                  echo $email;
	            	//Inserting Records

	            	if($id_group_got != "" && $id_country_got !="" && $id_area_got !="" && $id_state_got !=""){
	            		echo $insertSql = "INSERT INTO `fs_company` 

	            					(`id_shop_group`,`id_shop`,`id_default_group`,`id_lang`,`name`,`email`,`secondary_email`,`id_country`,`id_state`,`postcode`,`city`,`address`,`phone`,`mobile`,`area`,`company_credibility`,`deals_in`,`details`,`status`,`date_created`,`last_modified`) 
	            					VALUES (".$id_shop_group.",".$id_shop.",".$id_group_got.",".$id_lang.",'".$companyName."','".$email."','".$secondaryEmail."',".$id_country_got.",".$id_state_got.",'".$postCode."','".$city."','".$address."','".$phone."','".$mobile."',".$id_area_got.",'".$creditValue."','".$dealsIn."','".$details."','".$status."','".$date_created."','".$lastModified."')	
	            					";
	            		$finalRes = mysqli_query($conn,$insertSql);
	            		$insertId = mysqli_insert_id($finalRes);
	            		if($finalRes){
	            			echo "<br>".$x.")     ".$companyName;
	            		}
	            		else{
	            			echo "<br>Insertion Failed at ===".$x."Problem In below Query <br>".$insertSql."";
	            			exit;
	            		}

	            					
	            	}
	            	else{
	            		echo "one of the Ids are missing";
	            	}
	            	
	            }
	        	$x++;
	        }
			
	         fclose($file);	
	         echo json_encode("<br>Number of Records Imported Successfully : ".($x-1));
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