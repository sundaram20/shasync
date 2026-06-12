<?php
include_once("../../config/auto_loader.php");
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
	if(isset($_REQUEST['id_company']) && $_REQUEST['id_company']!=''){
		$_REQUEST['areaId']=selectColumn(TBL_COMPANY,'area','WHERE id_company="'.$_REQUEST['id_company'].'" ');	
	}

	if($_REQUEST['areaId'] !=0 && $_REQUEST['areaId']!=''){
		$sql="SELECT B.name,A.description FROM `fs_areas_assign` AS A
			  LEFT JOIN `fs_users` AS B ON A.user_id=B.id
			  WHERE A.id='".$_REQUEST['areaId']."' ";
		$res = mysqli_query($conn,$sql);
		
		if($res){
			$data = mysqli_fetch_object($res);
			if($data->name==""){
				echo "Executive not assigned<br>";
				echo "Area Description : ".$data->description;
			}
			else{
				echo "Sales Executive : ".$data->name."<br>";
				echo "Area Description : ".$data->description;
			}
		}	  
	}
	else{
		echo "";
	}

?>