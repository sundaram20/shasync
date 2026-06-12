<?php include_once("../../config/auto_loader.php");
//////////////////////////////////////executing query////////////////////////////////////////////////////


$HoletListID	=	addslashes($_REQUEST['HoletListID']);
$SortValue		=	addslashes($_REQUEST['SortValue']);
$tableName		=	addslashes($_REQUEST['tableName']);


				  $insertOrder = "UPDATE ".$tableName." SET 	
				`display_order`='".addslashes($SortValue)."'
				 where id='".$HoletListID."' and `id_shop` = '".addslashes($_SESSION['shop'])."'";
				 executeSql($insertOrder);
				 
				 
			


?>