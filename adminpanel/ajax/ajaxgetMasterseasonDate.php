<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$season = $_REQUEST['seasonId'];
//////////////////////////////getting and showing season date//////////////////////////////////////////////////////

if($season != ''){
$resSeason = executeSql("SELECT * from `".TBL_RATE_SEASON."` where status='1' and id='".addslashes($season)."' limit 0,1");
$rowSeason = $db->fetch_object2($resSeason);
echo date('d-m-Y',strtotime($rowSeason->start_date)).','.date('d-m-Y',strtotime($rowSeason->end_date));
$StartDate	=	 date('d-m-Y',strtotime($rowSeason->start_date));
$EndDate	=	date('d-m-Y',strtotime($rowSeason->end_date));

}else {
echo date("d-m-Y").','.date('d-m-Y',strtotime("+1 days"));
$StartDate	=	 date("d-m-Y");
$EndDate	=	date('d-m-Y',strtotime("+1 days"));
}







?>