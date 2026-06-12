<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<pre>";print_r($_REQUEST);echo "</pre>";

 adminLoginCheck();
 $value = $_REQUEST['value'];
 $hotelid = $_REQUEST['hotelid'];
 //echo$DateValue = $_REQUEST['month'];
 $EditId = $_REQUEST['editid'];
 $type = $_REQUEST['type'];
$seasonId=$_REQUEST['seasonId'];
$fieldvalue=$_REQUEST['fieldvalue'];
$userId=$_REQUEST['userId'];
$DateValue	 =	date('Y-m-d', $_REQUEST['month']);

if($EditId==0){
	
	
	$EditId	=selectColumn(TBL_BUDGET_MASTER,'id',"WHERE `id_hotel`='".addslashes($hotelid)."' AND  seasonId = '".addslashes($seasonId)."' and  `id_user` = '".addslashes($userId)."'  and  `type` = '".addslashes($type)."' and  `month` = '".addslashes($DateValue)."'  ");

if($EditId>0){
			$editRate = " UPDATE `".TBL_BUDGET_MASTER."` SET
							
								
		`".$fieldvalue."` = '".addslashes($value)."'";
										
						   $editRate .= "
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										WHERE `id` = '".$EditId."' and `type` = '".$type."'";
				
			executeSql($editRate);	
				
}else{
	$dateStart  =	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
	$dateEnd    =	selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
	$month 	  = 	strtotime($dateStart);
			

	while (strtotime($dateStart) <= strtotime($dateEnd)) {
               
			  
                if($dateStart==$DateValue){
						$recordvalue = $value;
					}else{
						$recordvalue = 0;
						}
				$addAchieved = "INSERT INTO `".TBL_BUDGET_MASTER."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',
									`type` = '".$type."',
									`id_hotel` = '".addslashes($hotelid)."',
									`id_user` = '".addslashes($userId)."',
								`".$fieldvalue."` = '".addslashes($recordvalue)."',	
									
									`seasonId`	= '".addslashes($seasonId)."',
									`month` = '".$dateStart."'																
								";
				$addAchieved .= ",`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`last_modified_by` = '".$_SESSION['userId']."'
								,`status` = '1'";								
				executeSql($addAchieved);
				
			    $dateStart = date ("Y-m-d", strtotime("+1 month", strtotime($dateStart)));
	}
					

					

	
	 				
}
	
	}
if($EditId>0){
			  $editRate = " UPDATE `".TBL_BUDGET_MASTER."` SET
							
								
		`".$fieldvalue."` = '".addslashes($value)."'";
										
						   $editRate .= "
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										WHERE `id` = '".$EditId."' and `type` = '".$type."'";
				
			executeSql($editRate);	
													

}
		//echo 'Budget details has not been saved. Please make corrections.';
	


?>