<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/

/*debugData($_REQUEST);
exit;*/

if($_REQUEST['id']=='' && $_REQUEST['Save']=='Add' ){
	$chkSql = "SELECT * FROM ".TBL_AGENT_ACHIEVED." WHERE id_user='".$_REQUEST['hotelId']."' AND seasonId='".$_REQUEST['seasonId']."' AND id_shop='".$_SESSION['shop']."' ";

	$resChk=mysqli_query($connNew,$chkSql);

	if(mysqli_num_rows($resChk)>0){
		echo "Achievements already added for this season.Use edit option to make changes to it.";
		exit;
	}
}

$BugetuserId = $_REQUEST['hotelId'];

$data_id	=	$_POST['data_id'];


	
if($_POST['Save']){		
	$err = 0;	
	
		
	if($err == 0){//No error	
	
	
		if(($_POST['Save'] == 'Add') && empty($_REQUEST['id'])){//add
			foreach($data_id as $data =>$value){
					$dateStart =selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");;
						$month = strtotime($dateStart);
				foreach($_REQUEST['buget_qty|'.$value] as $data2 =>$value2){
					//$EditId		=	$_REQUEST['id|'.$value][$data2];
					if($EditId>0){
	
			  			$editRate = " UPDATE `".TBL_AGENT_ACHIEVED."` SET 
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`qty` = '".addslashes($value2)."',		
							`seasonId`	= '".addslashes($_POST['seasonId'])."',		
							`month_value` = '".addslashes($_REQUEST['buget_value|'.$value][$data2])."'";
						   $editRate .= "
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										WHERE `id` = '".$EditId	."'";	
						executeSql($editRate);	
													
					}else{
						$DateValue	=	date('Y-m-d', $month);
	
	 				$addRate = "INSERT INTO `".TBL_AGENT_ACHIEVED."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',
									`id_company` = '".addslashes($value)."',
									`id_user` = '".addslashes($_POST['hotelId'])."',
									`qty` = '".addslashes($value2)."',
									`seasonId`	= '".addslashes($_POST['seasonId'])."',
									`month` = '".$DateValue."'							
								";
					$addRate .= ",`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`last_modified_by` = '".$_SESSION['userId']."'
								,`status` = '1'";	
															
					executeSql($addRate);
					$addRate_id= $db->insert_id();	
					$month = strtotime("+1 month", $month);
					}
						
				}
	
					//echo $_REQUEST['buget_qty|65'].$value;
			}
			echo '<p class="help-block">User Budget has been updated sucessfully.</p>';
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){
				foreach($data_id as $data =>$value){
					$dateStart =selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");;
					$month = strtotime($dateStart);
					//echo "<pre>";print_r($_REQUEST['buget_qty|'.$value]);

					$EditId	=selectColumn(TBL_AGENT_ACHIEVED,'id',"WHERE `id_company`='".addslashes($value)."' AND `id_user` ='".addslashes($_POST['hotelId'])."' AND seasonId = '".addslashes($_POST['seasonId'])."' ");

					foreach($_REQUEST['buget_qty|'.$value] as $data2 =>$value2){

					

					if($EditId>0){
						$DateValue	=	date('Y-m-d', $month);
	 					$addRate = "    UPDATE  `".TBL_AGENT_ACHIEVED."` SET 
									`qty` = '".addslashes($value2)."'
									
										";
						$addRate .= "	
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1' WHERE `id_company`='".addslashes($value)."' AND `id_user` ='".addslashes($_POST['hotelId'])."' AND seasonId = '".addslashes($_POST['seasonId'])."'
							AND month='".$DateValue."' ";	
						
						executeSql($addRate);									
						$addRate_id= $db->insert_id();	
								$month = strtotime("+1 month", $month);

						///////////
					}else{
	
						$DateValue	=	date('Y-m-d', $month);
	
	 				$addRate = "INSERT INTO `".TBL_AGENT_ACHIEVED."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',
									`id_company` = '".addslashes($value)."',
									`id_user` = '".addslashes($_POST['hotelId'])."',
									`qty` = '".addslashes($value2)."',
									`seasonId`	= '".addslashes($_POST['seasonId'])."',
									`month` = '".$DateValue."'							
								";
					$addRate .= ",`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`last_modified_by` = '".$_SESSION['userId']."'
								,`status` = '1'";	
															
					executeSql($addRate);
					$addRate_id= $db->insert_id();	
					$month = strtotime("+1 month", $month);	
							
	
					}
						
				}//end of nested foreach
										
					
					//echo $_REQUEST['buget_qty|65'].$value;
				
			}//end of foreach
			echo '<p class="help-block">User Budget has been updated sucessfully.</p>';
		}//end of else if
	
	
	}else{//Error
		$err++;
		echo 'Budget details has not been saved. Please make corrections.';
	}
}

?>