<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//echo "<pre>";print_r($_REQUEST);echo "</pre>";


 $BugetuserId = $_REQUEST['hotelId'];



if($_POST['type']=='1'){
	$filename='editbudget.php';
	}else{
	$filename='editHotelBudgetAchieved.php';
		}

	$data_id	=	$_POST['data_id'];
	
if($_POST['Save']){		
	$err = 0;	
		
	
	if($err == 0){//No error	
	
	
		if(($_POST['Save'] == 'Add') && empty($_REQUEST['id'])){//add
			
			
			
			
			
				foreach($data_id as $data =>$value){
					
					//echo "<pre>";print_r($_REQUEST['buget_qty|'.$value]);
					
										foreach($_REQUEST['buget_qty|'.$value] as $data2 =>$value2){

$EditId		=	$_REQUEST['id|'.$value][$data2];

if($EditId>0){
	//echo "EDIT";
			  $editRate = " UPDATE `".TBL_BUDGET_MASTER."` SET 
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`type` = '".addslashes($_POST['type'])."',																				
										`qty` = '".addslashes($value2)."',		
										`seasonId`	= '".addslashes($_POST['seasonId'])."',														
										`month_value` = '".addslashes($_REQUEST['buget_value|'.$value][$data2])."'";
						   $editRate .= "
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										WHERE `id` = '".$EditId	."'";	
			executeSql($editRate);	
													
}else{
	
	
	 $addRate = "    INSERT INTO `".TBL_BUDGET_MASTER."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',	
										`type` = '".addslashes($_POST['type'])."',									
										`id_hotel` = '".addslashes($value)."',
										`id_user` = '".addslashes($_POST['userId'])."',
										`qty` = '".addslashes($value2)."',
										`seasonId`	= '".addslashes($_POST['seasonId'])."',
										`month` = '".addslashes($_REQUEST['MonthDate|'.$value][$data2])."',							
										`month_value` = '".addslashes($_REQUEST['buget_value|'.$value][$data2])."'";
						   $addRate .= "	,`date_created` = '".currenDateTime()."'
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										,`status` = '1'";						
						executeSql($addRate);
								$addRate_id= $db->insert_id();		
	
	}
						

										}
					
					//echo $_REQUEST['buget_qty|65'].$value;
				
					}
					echo '<p class="help-block">User Budget has been updated sucessfully.</p><script>window.setTimeout(function() {window.location.href = "'.$filename.'";}, 2000);</script>';	
 				
 				
			
					
				
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){
			
			
			
				foreach($data_id as $data =>$value){
					
					//echo "<pre>";print_r($_REQUEST['buget_qty|'.$value]);
					
										foreach($_REQUEST['buget_qty|'.$value] as $data2 =>$value2){

$EditId		=	$_REQUEST['id|'.$value][$data2];

if($EditId>0){
	//echo "EDIT";
			  $editRate = " UPDATE `".TBL_BUDGET_MASTER."` SET 
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`type` = '".addslashes($_POST['type'])."',																				
										`qty` = '".addslashes($value2)."',		
										`seasonId`	= '".addslashes($_POST['seasonId'])."',														
										`month_value` = '".addslashes($_REQUEST['buget_value|'.$value][$data2])."'";
						   $editRate .= "
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										WHERE `id` = '".$EditId	."'";	
			executeSql($editRate);	
													
}else{
	
	
	 $addRate = "    INSERT INTO `".TBL_BUDGET_MASTER."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',	
										`type` = '".addslashes($_POST['type'])."',									
										`id_hotel` = '".addslashes($value)."',
										`id_user` = '".addslashes($_POST['userId'])."',										
										`qty` = '".addslashes($value2)."',
										`seasonId`	= '".addslashes($_POST['seasonId'])."',
										`month` = '".addslashes($_REQUEST['MonthDate|'.$value][$data2])."',							
										`month_value` = '".addslashes($_REQUEST['buget_value|'.$value][$data2])."'";
						   $addRate .= "	,`date_created` = '".currenDateTime()."'
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										,`status` = '1'";						
						executeSql($addRate);
								$addRate_id= $db->insert_id();		
	
	}
						

										}
					
					//echo $_REQUEST['buget_qty|65'].$value;
				
					}
						echo '<p class="help-block">User Budget has been updated sucessfully.</p><script>window.setTimeout(function() {window.location.href = "'.$filename.'";}, 2000);</script>';	
 				
 				
			
			}
	
	
	}else{//Error
		$err++;
		echo 'Budget details has not been saved. Please make corrections.';
	}
}

?>