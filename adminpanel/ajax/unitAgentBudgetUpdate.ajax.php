<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*echo "<pre>";print_r($_REQUEST);echo "</pre>";
exit;*/
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

if($_REQUEST['id']=='' && $_REQUEST['Save']=='Add' ){
	$chkSql = "SELECT * FROM ".TBL_UNIT_AGENT_BUDGET." WHERE id_user='".$_REQUEST['hotelId']."' AND season_id='".$_REQUEST['seasonId']."' AND id_shop='".$_SESSION['shop']."' AND id_hotel='".$_REQUEST['id_hotel']."' ";

	$resChk=mysqli_query($connNew,$chkSql);

	if(mysqli_num_rows($resChk)>0){
		echo "Budget is already added for this season.Use edit option to make changes it.";
		exit;
	}
}

$BugetuserId = $_REQUEST['hotelId'];
$data_id	=	$_REQUEST['data_id'];

if($_POST['Save']){		
	$err = 0;	
		
	
	if($err == 0){//No error	
		if(($_POST['Save'] == 'Add') && empty($_REQUEST['id'])){//add
			foreach($data_id as $data =>$value){
				foreach($_REQUEST['buget_qty|'.$value] as $data2 =>$value2){


					if($EditId>0){

						/*$editRate = " UPDATE `".TBL_BUDGET_MASTER."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',
									`qty` = '".addslashes($value2)."',		
									`seasonId`	= '".addslashes($_POST['seasonId'])."',		
									`month_value` = '".addslashes($_REQUEST['buget_value|'.$value][$data2])."'";
						$editRate .= "
									,`last_modified` = '".currenDateTime()."'
									,`last_modified_by` = '".$_SESSION['userId']."'
									WHERE `id` = '".$EditId	."'";	
						executeSql($editRate);*/	
																		
					}else{
						
						
						$addRate = "INSERT INTO `".TBL_UNIT_AGENT_BUDGET."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',		
									`id_company` = '".addslashes($value)."',
									`id_user` = '".addslashes($_POST['hotelId'])."',
									`id_hotel`='".$_REQUEST['id_hotel']."',
									`room_nights` = '".addslashes($value2)."',
									`from` = '".selectColumn(TBL_BUDGET_YEAR,'start_date'," 

									where id_shop='".addslashes($_SESSION['shop'])."' 
									AND id=".addslashes($_POST['seasonId'])." ",' ORDER BY `name`')."',
									`to` = '".selectColumn(TBL_BUDGET_YEAR,'end_date'," where id_shop='".addslashes($_SESSION['shop'])."' AND id=".addslashes($_POST['seasonId'])." ",' ORDER BY `name`')."',
									`season_id`	= '".addslashes($_POST['seasonId'])."',
									`value` = '".addslashes($_REQUEST['buget_value|'.$value][$data2])."'";
									$addRate .= "	,`date_created` = '".currenDateTime()."'
													,`last_modified` = '".currenDateTime()."'
													,`last_modified_by` = '".$_SESSION['userId']."'
													,`status` = '1'";	
						executeSql($addRate);
						$addRate_id= $db->insert_id();		
						
					}
				}
					
			}
			echo '<p class="help-block">User Budget has been updated sucessfully.</p>
			<script>setTimeout(function(){ window.location="manageAgentBudget.php"; }, 500);</script>';				
		}
		else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){
			
			foreach($data_id as $data =>$value){

				foreach($_REQUEST['buget_qty|'.$value] as $data2 =>$value2){

					$EditId	=selectColumn(TBL_UNIT_AGENT_BUDGET,'id','WHERE id_company="'.$value.'" AND season_id="'.$_REQUEST['seasonId'].'" AND id_user="'.$_REQUEST['hotelId'].'" AND id_hotel="'.$_REQUEST['id_hotel'].'" ');

						if($EditId>0){
							 $addRate = "UPDATE `".TBL_UNIT_AGENT_BUDGET."` SET 
										`room_nights` = '".addslashes($value2)."',
										`value` = '".addslashes($_REQUEST['buget_value|'.$value][$data2])."'";
							$addRate .= "	,`date_created` = '".currenDateTime()."'
												,`last_modified` = '".currenDateTime()."'
												,`last_modified_by` = '".$_SESSION['userId']."'
												,`status` = '1'
												WHERE id_shop=".$_SESSION['shop']." AND id_company=".$value." AND id_user=".$_REQUEST['hotelId']." AND season_id ='".$_REQUEST['seasonId']."' AND id_hotel='".$_REQUEST['id_hotel']."' ";
							//exit;					
							executeSql($addRate);	
							$addRate_id= $db->insert_id();	
																
						}else{
							 $addRate = "INSERT INTO `".TBL_UNIT_AGENT_BUDGET."` SET 
										`room_nights` = '".addslashes($value2)."',
										`value` = '".addslashes($_REQUEST['buget_value|'.$value][$data2])."'";
							$addRate .= "	,`date_created` = '".currenDateTime()."'			,`from` = '".selectColumn(TBL_BUDGET_YEAR,'start_date'," 

									where id_shop='".addslashes($_SESSION['shop'])."' 
									AND id=".addslashes($_POST['seasonId'])." ",' ORDER BY `name`')."',
									`to` = '".selectColumn(TBL_BUDGET_YEAR,'end_date'," where id_shop='".addslashes($_SESSION['shop'])."' AND id=".addslashes($_POST['seasonId'])." ",' ORDER BY `name`')."'
												,`last_modified` = '".currenDateTime()."'
												,`last_modified_by` = '".$_SESSION['userId']."'
												,`status` = '1'
												,season_id='".$_REQUEST['seasonId']."'
												,id_shop='".$_SESSION['shop']."'
												,id_hotel='".$_REQUEST['id_hotel']."'
												,id_company='".$value."'
												,id_user='".$_REQUEST['hotelId']."'
												";
												
							executeSql($addRate);	
							$addRate_id= $db->insert_id();	
							 	
						}
								
				}
							
			}
			echo '<p class="help-block">User Budget has been updated sucessfully.</p>
			<script>setTimeout(function(){ window.location="manageAgentBudget.php"; }, 500);</script>';
		}
	}
						
}else{//Error
		$err++;
		echo 'Budget details has not been saved. Please make corrections.';
	}


?>