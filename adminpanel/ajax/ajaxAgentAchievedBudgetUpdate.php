<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/
//exit;

/*debugData($_REQUEST);
exit;*/

if($_REQUEST['search_name']==''  ){
	

	
		echo "Please Select Company Name.";
		exit;
	
}

$BugetuserId = $_REQUEST['hotelId'];

$data_id	=	$_POST['data_id'];


if($_REQUEST['id_hotel']==''){	 //RSO USER
//echo 'RSO';
if($_POST['Save']){		
	$err = 0;	
	
		
	if($err == 0){//No error	
	
	
		if(($_POST['Save'] == 'Add') ){//add
			foreach($_REQUEST['budget_quantity']  as $data2 =>$value2){
					$dateStart =selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['selectseasonId']."'");;
					 $month = 	date('Y-m-d', $_REQUEST['month'][$data2]);
	
	 $EditId	=selectColumn(TBL_AGENT_ACHIEVED,'id',"WHERE `id_company`='".addslashes($_REQUEST['search_name'])."' and  `id_user` = '".addslashes($_REQUEST['hotelId'])."' and 
 `month` = '".$month."' AND  seasonId = '".addslashes($_REQUEST['selectseasonId'])."' ");
				//foreach($_REQUEST['buget_qty|'.$value] as $data2 =>$value2){
					//$EditId		=	$_REQUEST['id|'.$value][$data2];
					if($EditId>0){
	
			  			$editRate = " UPDATE `".TBL_AGENT_ACHIEVED."` SET 
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`qty` = '".addslashes($value2)."',		
							`seasonId`	= '".addslashes($_REQUEST['selectseasonId'])."',		
							`month_value` = '".$month."'";
						   $editRate .= "
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										WHERE `id` = '".$EditId."'";	
										//echo  $editRate;
						executeSql($editRate);	
													
					}else{
						
	 				$addRate = "INSERT INTO `".TBL_AGENT_ACHIEVED."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',
									`id_company` = '".addslashes($_REQUEST['search_name'])."',
									`id_user` = '".addslashes($_REQUEST['hotelId'])."',
									`qty` = '".addslashes($value2)."',
									`seasonId`	= '".addslashes($_REQUEST['selectseasonId'])."',
									`month` = '".$month."'							
								";
					$addRate .= ",`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`last_modified_by` = '".$_SESSION['userId']."'
								,`status` = '1'";	
						//echo $addRate;									
					executeSql($addRate);
					$addRate_id= $db->insert_id();	
					$month = strtotime("+1 month", $month);
					}
						
				//}
	
					//echo $_REQUEST['buget_qty|65'].$value;
			}
			echo '<p class="help-block">User Budget has been updated sucessfully.</p>';
		}else if(($_POST['Save'] == 'Edit')){
			
				foreach($_REQUEST['budget_quantity']  as $data2 =>$value2){
					$dateStart =selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['selectseasonId']."'");;
					 $month = 	date('Y-m-d', $_REQUEST['month'][$data2]);
	
	$EditId	=selectColumn(TBL_AGENT_ACHIEVED,'id',"WHERE `id_company`='".addslashes($_REQUEST['search_name'])."' and  `id_user` = '".addslashes($_REQUEST['hotelId'])."' and 
 `month` = '".$month."' AND  seasonId = '".addslashes($_POST['selectseasonId'])."' ");

					//foreach($_REQUEST['budget_quantity|'.$value] as $data2 =>$value2){

					

					if($EditId>0){
						$DateValue	=	date('Y-m-d', $month);
	 					$addRate = "    UPDATE  `".TBL_AGENT_ACHIEVED."` SET 
									`qty` = '".addslashes($value2)."',
									`id_user`='".addslashes($_POST['hotelId'])."'
									
										";
						$addRate .= "	
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1' WHERE `id_company`='".$_REQUEST['search_name']."'  AND seasonId = '".addslashes($_POST['selectseasonId'])."'
							AND month='".$month."' ";	
						//echo $addRate;
						executeSql($addRate);									
						$addRate_id= $db->insert_id();	
						//		$month = strtotime("+1 month", $month);

						///////////
					}else{
	
						$addRate = "INSERT INTO `".TBL_AGENT_ACHIEVED."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',
									`id_company` = '".addslashes($_REQUEST['search_name'])."',
									`id_user` = '".addslashes($_REQUEST['hotelId'])."',
									`qty` = '".addslashes($value2)."',
									`seasonId`	= '".addslashes($_REQUEST['selectseasonId'])."',
									`month` = '".$month."'							
								";
					$addRate .= ",`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`last_modified_by` = '".$_SESSION['userId']."'
								,`status` = '1'";	
						//echo $addRate;			
													
					executeSql($addRate);
					$addRate_id= $db->insert_id();	
							
	
					}
						
				//}//end of nested foreach
										
					
					//echo $_REQUEST['buget_qty|65'].$value;
				
			}//end of foreach
			echo '<p class="help-block">User Budget has been updated sucessfully.</p>';
		}//end of else if
	
	
	}else{//Error
		$err++;
		echo 'Budget details has not been saved. Please make corrections.';
	}
}

}else{//UNIT USER
	
	
	//echo 'UNIT USER';
	
	
	if($_POST['Save']){		
	$err = 0;	
	
		
	if($err == 0){//No error	
	
	
		if(($_POST['Save'] == 'Add') ){//add
			foreach($_REQUEST['budget_quantity']  as $data2 =>$value2){
					$dateStart =selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['selectseasonId']."'");;
					 $month = 	date('Y-m-d', $_REQUEST['month'][$data2]);
	
	 $EditId	=selectColumn(TBL_UNIT_AGENT_ACHIEVED,'id',"WHERE `id_company`='".addslashes($_REQUEST['search_name'])."' AND id_hotel='".$_REQUEST['id_hotel']."' and  `id_user` = '".addslashes($_REQUEST['hotelId'])."' and 
 `month` = '".$month."' AND  seasonId = '".addslashes($_REQUEST['selectseasonId'])."' ");
				//foreach($_REQUEST['buget_qty|'.$value] as $data2 =>$value2){
					//$EditId		=	$_REQUEST['id|'.$value][$data2];
					if($EditId>0){
	
			  			$editRate = " UPDATE `".TBL_UNIT_AGENT_ACHIEVED."` SET 
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`qty` = '".addslashes($value2)."',		
							`seasonId`	= '".addslashes($_REQUEST['selectseasonId'])."',		
							`month_value` = '".$month."'";
						   $editRate .= "
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										WHERE `id` = '".$EditId."'";	
									//	echo  $editRate;
						executeSql($editRate);	
													
					}else{
						
	 				$addRate = "INSERT INTO `".TBL_UNIT_AGENT_ACHIEVED."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',
									`id_company` = '".addslashes($_REQUEST['search_name'])."',
									`id_user` = '".addslashes($_REQUEST['hotelId'])."',
									`qty` = '".addslashes($value2)."',
									id_hotel = '".$_REQUEST['id_hotel']."',	
									`seasonId`	= '".addslashes($_REQUEST['selectseasonId'])."',
									`month` = '".$month."'							
								";
					$addRate .= ",`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`last_modified_by` = '".$_SESSION['userId']."'
								,`status` = '1'";	
						//echo '1233'.$addRate;									
					executeSql($addRate);
					$addRate_id= $db->insert_id();	
					$month = strtotime("+1 month", $month);
					}
						
				//}
	
					//echo $_REQUEST['buget_qty|65'].$value;
			}
			echo '<p class="help-block">User Budget has been updated sucessfully.</p>';
		}else if(($_POST['Save'] == 'Edit')){
			
				foreach($_REQUEST['budget_quantity']  as $data2 =>$value2){
					$dateStart =selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['selectseasonId']."'");;
					 $month = 	date('Y-m-d', $_REQUEST['month'][$data2]);
	
	$EditId	=selectColumn(TBL_UNIT_AGENT_ACHIEVED,'id',"WHERE `id_company`='".addslashes($_REQUEST['search_name'])."' and    id_hotel='".$_REQUEST['id_hotel']."' AND `id_user` = '".addslashes($_REQUEST['hotelId'])."' and 
 `month` = '".$month."' AND  seasonId = '".addslashes($_POST['selectseasonId'])."' ");

					//foreach($_REQUEST['budget_quantity|'.$value] as $data2 =>$value2){

					

					if($EditId>0){
						$DateValue	=	date('Y-m-d', $month);
	 					$addRate = "    UPDATE  `".TBL_UNIT_AGENT_ACHIEVED."` SET 
									`qty` = '".addslashes($value2)."',
									`id_user`='".addslashes($_POST['hotelId'])."'
									
										";
						$addRate .= "	
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1' WHERE `id_company`='".$_REQUEST['search_name']."'  AND id_hotel='".$_REQUEST['id_hotel']."' AND seasonId = '".addslashes($_POST['selectseasonId'])."'
							AND month='".$month."' ";	
						//echo $addRate;
						executeSql($addRate);									
						$addRate_id= $db->insert_id();	
						//		$month = strtotime("+1 month", $month);

						///////////
					}else{
	
						$addRate = "INSERT INTO `".TBL_UNIT_AGENT_ACHIEVED."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',
									`id_company` = '".addslashes($_REQUEST['search_name'])."',
									`id_user` = '".addslashes($_REQUEST['hotelId'])."',
									`qty` = '".addslashes($value2)."',
									id_hotel = '".$_REQUEST['id_hotel']."',	
									`seasonId`	= '".addslashes($_REQUEST['selectseasonId'])."',
									`month` = '".$month."'							
								";
					$addRate .= ",`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`last_modified_by` = '".$_SESSION['userId']."'
								,`status` = '1'";	
									
							//	echo $addRate;							
					executeSql($addRate);
					$addRate_id= $db->insert_id();	
							
	
					}
						
				//}//end of nested foreach
										
					
					//echo $_REQUEST['buget_qty|65'].$value;
				
			}//end of foreach
			echo '<p class="help-block">User Budget has been updated sucessfully.</p>';
		}//end of else if
	
	
	}else{//Error
		$err++;
		echo 'Budget details has not been saved. Please make corrections.';
	}
}
	
	
	
	
	
	
	}
?>