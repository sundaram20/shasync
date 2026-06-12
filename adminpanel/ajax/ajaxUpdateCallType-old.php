<?php include_once("../../config/auto_loader.php");


			$editSql1 = "    UPDATE `call` SET 
							
							`calls_type` = '".addslashes($_REQUEST['id_calls_type'])."'	
								
							WHERE `id` = '".addslashes($_REQUEST['id_calls'])."'";

							

			if(executeSql($editSql1)){

				
					
				
						
if($_REQUEST['id_calls_type'] == '1' || $_REQUEST['id_calls_type'] == '3' || $_REQUEST['id_calls_type'] == '4' || $_REQUEST['id_calls_type'] == '5'){$filename="'editTask.php?id_calls=".encryptor('encrypt',$_REQUEST['id_calls'])."'";
						$urlLink='<img src="images/assign.jpg" style="height:23px;cursor:pointer;" onClick="window.location.href='.$filename.'" title="Assign To" />';
					}
					if($_REQUEST['id_calls_type'] == '2'){$filename="'editEnquiry.php?id_calls=".encryptor('encrypt',$_REQUEST['id_calls'])."'";
						$urlLink='<img src="images/assign.jpg" style="height:23px;cursor:pointer;" onClick="window.location.href='.$filename.'" title="Assign To" />';
						}
				
$Array=array();
			$Array['url']=$urlLink;
			$Array['id_calls']	=$_REQUEST['id_calls'];

		$Array['errorMsg'] = 'Company details has not been saved. Please make corrections.';
		
echo json_encode($Array);
	}

?>

                









