<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$seriesId=$_REQUEST['seriesId'];
$operatorId=$_REQUEST['operatorId'];
$seriestype	= $_REQUEST['book_type'];
$serieshotel_id	= $_REQUEST['hotel_id'];
$id_company	= $_REQUEST['id_company'];
/*print_r($_SESSION);
print_r($_REQUEST);*/
$_SESSION['series']['series']		=	$seriesId;				  
$_SESSION['series']['operator']		=	$operatorId;
$_SESSION['series']['type']			=	$seriestype;
$_SESSION['hotel_id']			=	$serieshotel_id;
$_SESSION['id_company']			=	$id_company;

//if($seriesId!='' && $operatorId !=''){
//	AND id_hotel	= '".addslashes($serieshotel_id)."'


$resOrder = executeSql("SELECT * from `".TBL_ORDERS."` where series_id='".addslashes($seriesId)."' and operator_id='".addslashes($operatorId)."' and id_company='".addslashes($id_company)."'  and type='S' order by id_order desc limit 0,1");
if(num_rows($resOrder) > 0){
		 $row 		= $db->fetch_object2($resOrder);
		//echo "<pre>"; print_r($row);			echo "</pre>";
		 $companyId 	= $row->id_company.'|||';
		 $companyperson = $row->id_company_person.'|||';
		 $guestId 		= $row->id_customer.'|||';
		 $id_hotel 		= $row->id_hotel.'|||';		
		 echo date('d-m-Y',strtotime($row->checkin)).' to '.date('d-m-Y',strtotime($row->checkout));// date('d-m-Y',strtotime($row->checkin)).' to '.date('d-m-Y',strtotime($row->checkout));
		
}else{
		echo  date('d-m-Y').' to '.date('d-m-Y');
	}
		?>	