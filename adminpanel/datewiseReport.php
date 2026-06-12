<?php include_once("../config/auto_loader.php");
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');
	
	// ----------cate---------
	$cond = "  where `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";
	if($_REQUEST['search_name'] != ''){
	$cond .= " AND (`reference` LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(reference,'-', code) LIKE '%".addslashes($_REQUEST['search_name'])."%' )";
	}


	if($_REQUEST['hotelId'] != ''){
		$hotel_ids = implode(',',$_REQUEST['hotelId']);
		$cond 	   .=	" AND `".TBL_ORDERS."`.`id_hotel` in (".$hotel_ids.")";
		$condlunch .= 	"  `".TBL_ORDERS."`.`id_hotel` in (".$hotel_ids.") AND ";
	}else{
		$cond 	   .=	" AND `".TBL_ORDERS."`.`id_hotel` in (".$_SESSION['ActiveListHotelPerLogin'].")";
		$condlunch .= 	"  `".TBL_ORDERS."`.`id_hotel` in (".$_SESSION['ActiveListHotelPerLogin'].") AND ";
	}

	if($_REQUEST['booking_status'] != ''){
		$booking_status_arr = implode(',',$_REQUEST['booking_status']);
		$cond .= " AND `".TBL_ORDERS."`.`booking_status` in (".$booking_status_arr.") ";
	}

	if($_REQUEST['company_id'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`id_company` = '".addslashes($_REQUEST['company_id'])."'";
	}

	if($_REQUEST['guest'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`id_customer` = '".addslashes($_REQUEST['guest'])."'";
	}

	if($_REQUEST['payment_status'] != ''){
		$payment_status_arr = implode(',',$_REQUEST['payment_status']);
		$cond .= " AND `".TBL_ORDERS."`.`payment_status` in (".$payment_status_arr.") ";
	}

	//checkin_radio

	if($_REQUEST['reservation_date'] != ''){
		list($checkin,$checkout) = split(" to ",$_REQUEST['reservation_date']);	
		//$cond .= " AND `".TBL_ORDERS."`.`checkin` = '".date('Y-m-d',strtotime($checkin))."' and `".TBL_ORDERS."`.`checkout` = '".date('Y-m-d',strtotime($checkout))."'";
		if(strtotime($checkin)!=strtotime($checkout)){
			$tillcheckout = date ("Y-m-d", strtotime("-1 day", strtotime($checkout)));
			$cond .= " AND `".TBL_ORDER_DETAIL."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
			$condlunch .= " `".TBL_ORDERS."`.`checkin` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
		}else{
			$cond .= " AND `".TBL_ORDER_DETAIL."`.`dated`='".date('Y-m-d',strtotime($checkin))."'";
			$condlunch .= " `".TBL_ORDERS."`.`checkin`='".date('Y-m-d',strtotime($checkin))."'";
		}
		$datewise_array = array();
		$checkinDate = date('Y-m-d',strtotime($checkin));
		$checkoutDate = date('Y-m-d',strtotime($checkout));
		while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	
			$datewise_array[] = $checkinDate;
			$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
		}
	}	

	if($_REQUEST['id_executive'] != ''){
		$cond .= " AND `fs_users`.`id` = '".addslashes($_REQUEST['id_executive'])."'";		
	}







    $sql = " SELECT `".TBL_ORDERS."`.*,`".TBL_USERS."`.name as name_executive, `".TBL_ORDER_DETAIL."`.room_id ,sum(`".TBL_ORDER_DETAIL."`.room_quantity * `".TBL_ORDER_DETAIL."`.adults) as adults,sum(`".TBL_ORDER_DETAIL."`.room_quantity * `".TBL_ORDER_DETAIL."`.infants) as infants,sum(`".TBL_ORDER_DETAIL."`.room_quantity * `".TBL_ORDER_DETAIL."`.child) as child  ,sum(`".TBL_ORDER_DETAIL."`.room_quantity) as room_quantity 

     FROM `".TBL_ORDERS."`  

     LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company

     LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id

     LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id  

     INNER JOIN `".TBL_ORDER_DETAIL."` on ".TBL_ORDERS.".id_order=".TBL_ORDER_DETAIL.".id_order 

     ".$cond." group by `".TBL_ORDER_DETAIL."`.room_id, `".TBL_ORDER_DETAIL."`.id_order,`".TBL_ORDER_DETAIL."`.dated order by `".TBL_ORDERS."`.checkin, `".TBL_ORDERS."`.id_hotel";



if($_POST['Download'] == 'Generate'){
	error_reporting(1);
	$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
	$rowShop = $db->fetch_object2($resShop);
	$db->query($sql);
	$numRows= $db->num_rows();
	//$pagging = new pagingClass($sql,$setpage);
	//$db->query($pagging->getQuery());
	$total = $db->num_rows();
	$datawisearrayFinal = array();	
	$company_array=array();
	$agentwise_array=array();
	$hotel_array=array();
	$hotelwise_array=array();
	$executive_array=array();
	$executivewise_array=array();	

	if($total > 0){
		$cntr_order= 0;
		while($row = $db->fetch_object()){
			foreach($datewise_array as $checkinDatearr){			
				if(strtotime($checkinDatearr)>=strtotime($row->checkin) && strtotime($checkinDatearr)<strtotime($row->checkout)){
					if(!in_array($row->id_company, $company_array, true)){
						array_push($company_array, $row->id_company);							
					}
					if(!in_array($row->id_hotel, $hotel_array, true)){
						array_push($hotel_array, $row->id_hotel);												
					}

					if(!in_array($row->id_executive, $executive_array, true)){
						array_push($executive_array, $row->id_executive);														
					}

					if($row->booking_status==2){
						$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["noofdays_hold"] = $row->room_quantity;
					}


					if($row->booking_status==4){									
						$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["noofdays_withdraw"] = $row->room_quantity;
					}


					if($row->booking_status==1){									
						 $datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["noofdays_confirm"]=$row->room_quantity;
					}


					if($row->booking_status==3){
						$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["noofdays_waitlist"]=$row->room_quantity;
					}

					if($row->booking_status==1 || $row->booking_status==2 ){
						$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["noofdays_totalbooked"]=$row->room_quantity;
					}

					if($row->booking_status==1 || $row->booking_status==2 || $row->booking_status==4 ){

						if($row->booking_status==1 || $row->booking_status==2){
							$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["noofdays_netbooked"]=$row->room_quantity;
						}else{
							$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["noofdays_netbooked"]=$row->room_quantity;
						}
					}



								//--End of Agent wise arrray build for Report

								//Hotel wise arrray build for Report
					if($row->booking_status==4){									
						$hotelwise_array[$row->id_hotel][$row->id_company]["noofdays_withdraw"]=$row->room_quantity;
					}

					if($row->booking_status==1){									
						$hotelwise_array[$row->id_hotel][$row->id_company]["noofdays_confirm"]=$row->room_quantity;
					}
					if($row->booking_status==2){
						$hotelwise_array[$row->id_hotel][$row->id_company]["noofdays_hold"]=$row->room_quantity;
					}

					if($row->booking_status==3){
						$hotelwise_array[$row->id_hotel][$row->id_company]["noofdays_waitlist"]=$row->room_quantity;
					}



					if($row->booking_status==1 || $row->booking_status==2 ){
						$hotelwise_array[$row->id_hotel][$row->id_company]["noofdays_totalbooked"]=$row->room_quantity;
					}

					if($row->booking_status==1 || $row->booking_status==2 || $row->booking_status==4 ){
						if($row->booking_status==1 || $row->booking_status==2){
							$hotelwise_array[$row->id_hotel][$row->id_company]["noofdays_netbooked"]=$row->room_quantity;
						}else{
							$hotelwise_array[$row->id_hotel][$row->id_company]["noofdays_netbooked"] = $row->room_quantity;
						}

					}


					//--End of Hotel wise arrray build for Report
					//Executive wise arrray build for Report
					if($row->booking_status==4){									
						$executivewise_array[$row->id_executive][$row->id_hotel]["noofdays_withdraw"]=$row->room_quantity;							
					}


					if($row->booking_status==1){									
						$executivewise_array[$row->id_executive][$row->id_hotel]["noofdays_confirm"]=$row->room_quantity;
					}


					if($row->booking_status==2){
						$executivewise_array[$row->id_executive][$row->id_hotel]["noofdays_hold"]=$row->room_quantity;
					}

					if($row->booking_status==3){
						$executivewise_array[$row->id_executive][$row->id_hotel]["noofdays_waitlist"]=$row->room_quantity;
					}

					if($row->booking_status==1 || $row->booking_status==2  ){
						$executivewise_array[$row->id_executive][$row->id_hotel]["noofdays_totalbooked"]=$row->room_quantity;
					}

					if($row->booking_status==1 || $row->booking_status==2 || $row->booking_status==4 ){
						if($row->booking_status==1 || $row->booking_status==2){
							$executivewise_array[$row->id_executive][$row->id_hotel]["noofdays_netbooked"]=$row->room_quantity;
						}else{

							$executivewise_array[$row->id_executive][$row->id_hotel]["noofdays_netbooked"] = $row->room_quantity;
						}
					}

						//--End of Executive wise arrray build for Report



					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["id_order"]=$row->id_order;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["id_hotel"]=$row->id_hotel;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["room_id"]=$row->room_id;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["lunch_type"]=$row->type==L?"Yes":"No";
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["lunch_count"]=$row->type==L?$row->adults:0;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["reference"]=$row->reference;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["company"]=$row->id_company;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["customer"]=$row->id_customer;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["payment_status"]=$row->payment_status;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["booking_status"]=$row->booking_status;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["invoice_date"]=$row->invoice_date;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["name_executive"]=$row->name_executive;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["checkin"]=$row->checkin;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["checkout"]=$row->checkout;

					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["no_of_days"]=$row->no_of_days;

					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["total_adults"]=$row->adults;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["total_infants"]=$row->infants;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["total_child"]=$row->child;
					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["total_products"]=$row->room_quantity;
				}
			}
		}
	}

	// Set document properties

	$objPHPExcel->getProperties()->setCreator("Akhil")


								 ->setLastModifiedBy("Akhil")
								 ->setTitle("Date wise Booking Report")
								 ->setSubject("Date wise Booking Report")
								 ->setDescription("Date wise Booking Report")
								 ->setKeywords("Date wise Booking Report")
								 ->setCategory("Report");


	if(isset($_POST['checkin_radio']) && $_POST['checkin_radio']=='1'){
		$from_text = "Checkin From";
		$totext = "Checkin To";
		$from_date =$checkinDate;
		$to_date = $checkoutDate;
	}else{
		$from_text = "Booking From";
		$totext = "Booking To";
		$from_date =dateformat_date($checkin);
		$to_date = dateformat_date($checkout);
	}

function cellColor1($cells,$color){
    global $objPHPExcel;
    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
    )
   ));
}



	

	// Add some data

	if($total > 0){$counter = 1;







	$head_hotel_row = 1;

		foreach($datawisearrayFinal as $dateCheckin=>$dateData){
			$ConfirmAndTenditiveDoubleRooms		= array();
			$waitlistedDouble2					= array();	
			$CancelledDoubleRooms				= array();
			$ConfirmAndTenditiveSingleNew		= array();
			$CancelledTrible2					= array();
			$waitlistedTrible2					= array();
			$ConfirmAndTenditiveTrible11		= array();	
			$CancelledSingle2					= array();
		//$RoomCountDoubel1	=array();



echo "<br>".dateformat_date($dateCheckin);
		$head_cntr_column = "A";$head_hotel_column = "A";
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_cntr_column.$head_hotel_row, 'Date');
		$objPHPExcel->getActiveSheet()->getStyle($head_cntr_column++.$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getFont()->setBold(true);


			cellColor1('A'.$head_hotel_row.":P".$head_hotel_row, 'FFFF66');

			$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue($head_cntr_column.$head_hotel_row++, dateformat_date($dateCheckin))
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Hotel Name')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Reservation Id')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Room Type')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Guest Name')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Source')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Payment Status')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Booking Status')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Booking Date')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Executive Name')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'Checkin-Checkout')
			->setCellValue($head_hotel_column++.$head_hotel_row, 'No of Days');
	
	if(isset($_POST['total_products']) && $_POST['total_products']==1){
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($head_hotel_column.$head_hotel_row, 'Rooms');
			$head_hotel_column++;
	}
	
	if(isset($_POST['total_adults']) && $_POST['total_adults']==1){
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_hotel_column.$head_hotel_row, 'Adults');
		$head_hotel_column++;
	}
	if(isset($_POST['total_infants']) && $_POST['total_infants']==1){
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_hotel_column.$head_hotel_row, 'Infants');
		$head_hotel_column++;
	}
    if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_hotel_column.$head_hotel_row, 'Lunch Count');
		$head_hotel_column++;
    }
    if(isset($_POST['total_child']) && $_POST['total_child']==1){
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_hotel_column.$head_hotel_row, 'Child');
		$head_hotel_column++;
    }

	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_hotel_column++.$head_hotel_row, 'Single')
		->setCellValue($head_hotel_column++.$head_hotel_row, 'Double')
		->setCellValue($head_hotel_column++.$head_hotel_row, 'Triple');
		$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_hotel_column.$head_hotel_row)->getFont()->setBold(true);
			$head_hotel_row++;
	foreach($dateData as $hotelcheckarr=>$order_data1){
		foreach($order_data1 as $room_idfromarr=>$order_data){
		$RoomCountDoubel1	=array();
    	$head_order_data = "A";
		
		$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$order_data['id_hotel']."'"))
			
			->setCellValue($head_order_data++ . $head_hotel_row,  $order_data['reference'])
			
			->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$order_data['room_id']."'"))
			
			->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_CUSTOMER,'CONCAT(first_name," ",last_name)'," WHERE `id_customer` = '".$order_data['customer']."'"))
			
			->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$order_data['company']."'"))
			
			->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".$order_data['payment_status']."'"))
			
			->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".$order_data['booking_status']."'"))
			
			->setCellValue($head_order_data++ . $head_hotel_row,dateformat_date($order_data['invoice_date']))
			
			->setCellValue($head_order_data++ . $head_hotel_row,$order_data['name_executive'])
			
			->setCellValue($head_order_data++ . $head_hotel_row, dateformat_date($order_data['checkin'])." - ".dateformat_date($order_data['checkout']))
			
			->setCellValue($head_order_data++ . $head_hotel_row,$order_data['no_of_days']);
	
	if(isset($_POST['total_products']) && $_POST['total_products']==1){							

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($head_order_data++ .$head_hotel_row,round($order_data['total_products']));
	}
	if(isset($_POST['total_adults']) && $_POST['total_adults']==1){								$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_order_data++ .$head_hotel_row,round($order_data['total_adults']));
	}

	if(isset($_POST['total_infants']) && $_POST['total_infants']==1){							$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_order_data++ .$head_hotel_row,round($order_data['total_infants']));
	}
	if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){					$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_order_data++ .$head_hotel_row,round($order_data['lunch_count']));
	}
	if(isset($_POST['total_child']) && $_POST['total_child']==1){								$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_order_data++ .$head_hotel_row,round($order_data['total_child']));
	}


	if($head_order_data){
		$head_order_data_total0	= $head_order_data;
		$head_order_data_total1	= $head_order_data;
		$head_order_data_total3	= $head_order_data;
	}
	echo "<br>";



//echo "Select * from `".TBL_ORDER_DETAIL."` where id_order='".addslashes($order_data['id_order'])."' group by room_quantity,adults";

echo "Select *  from `".TBL_ORDER_DETAIL."` where id_order='".addslashes($order_data['id_order'])."' and room_id='".addslashes($order_data['room_id'])."' group by unique_code,room_id,room_quantity,adults,child,rate_plan_id";



$sqlOrderDetail_1 = executeSql("Select *  from `".TBL_ORDER_DETAIL."` where id_order='".addslashes($order_data['id_order'])."' and room_id='".addslashes($order_data['room_id'])."' group by  unique_code,room_id,room_quantity,adults,child");



while($row1 = $db->fetch_assoc2($sqlOrderDetail_1)){
  $RoomCountSingleDoubelTrible	=	$row1['adults'];
 if($RoomCountSingleDoubelTrible	=='1'){		
	$RoomCountSingle1[]	=	round($row1['room_quantity']);	
	if($order_data['booking_status']=='1' || $order_data['booking_status']=='2'){
		echo "single=".$row1['room_quantity'];		
		$ConfirmAndTenditiveSingleNew[]=	round($row1['room_quantity']);						
	}
	if($order_data['booking_status']=='3'){
		$waitlistedSingle2[]	=	round($row1['room_quantity']);
	}
	if($order_data['booking_status']=='4'){
		$CancelledSingle2[]	=	round($row1['room_quantity']);
	}
}
elseif($RoomCountSingleDoubelTrible	=='2'){
	echo $RoomCountDoubel1[]	=	round($row1['room_quantity']);	
	if($order_data['booking_status']=='1' || $order_data['booking_status']=='2'){	
		echo "Doubel=".$row1['room_quantity'];							
		$ConfirmAndTenditiveDoubleRooms[]=	round($row1['room_quantity']);
	}
	if($order_data['booking_status']=='3'){
		echo "Doubel=".$row1['room_quantity'];
		$waitlistedDouble2[]	=	round($row1['room_quantity']);
	}

	if($order_data['booking_status']=='4'){
		$CancelledDoubleRooms[]	=	round($row1['room_quantity']);
	}
}
elseif($RoomCountSingleDoubelTrible	=='3'){
	$RoomCountTrible1[]	=	round($row1['room_quantity']);									
	if($order_data['booking_status']=='1' || $order_data['booking_status']=='2'){
		$ConfirmAndTenditiveTrible11[]	=	round($row1['room_quantity']);
	}
	if($order_data['booking_status']=='3'){
		$waitlistedTrible2[]	=	round($row1['room_quantity']);
	}
	if($order_data['booking_status']=='4'){
		$CancelledTrible2[]	=	round($row1['room_quantity']);
	}
}


}



$ConfirmAndTenditiveSingle=array_sum($ConfirmAndTenditiveSingleNew);
$ConfirmAndTenditiveDouble=array_sum($ConfirmAndTenditiveDoubleRooms);
$ConfirmAndTenditiveTrible=array_sum($ConfirmAndTenditiveTrible11);

$waitlistedSingle1=array_sum($waitlistedSingle2);
$waitlistedDouble1=array_sum($waitlistedDouble2);
$waitlistedTrible1=array_sum($waitlistedTrible2);




$CancelledSingle1=array_sum($CancelledSingle2);
$CancelledDouble1=array_sum($CancelledDoubleRooms);
$CancelledTrible1=array_sum($CancelledTrible2);



$RoomCountSingle1 = array_sum($RoomCountSingle1);
$RoomCountDoubel1 = array_sum($RoomCountDoubel1);
$RoomCountTrible1 = array_sum($RoomCountTrible1);


$RoomCountSingle1;

echo $RoomCountDoubel1;
$RoomCountTrible1;

$objPHPExcel->setActiveSheetIndex(0)

	->setCellValue($head_order_data++ . $head_hotel_row,isset($RoomCountSingle1)?$RoomCountSingle1:0)
	->setCellValue($head_order_data++ . $head_hotel_row,isset($RoomCountDoubel1)? $RoomCountDoubel1:0)	
	->setCellValue($head_order_data++ . $head_hotel_row,isset($RoomCountTrible1)?$RoomCountTrible1:0);
	$head_hotel_row++;
}
/**********************************************************/
/**********************************************************/
	$RoomCountSingle1	=array();		
	$RoomCountTrible1	=array();
}					//echo "===".$ConfirmAndTenditiveDouble;							//$head_order_data_total	='k';

	
//die;

	if(isset($ConfirmAndTenditiveSingle) || isset($ConfirmAndTenditiveDouble) || isset($ConfirmAndTenditiveTrible)){
	
		$confirmedTotal =  ($ConfirmAndTenditiveSingle+$ConfirmAndTenditiveDouble+$ConfirmAndTenditiveTrible) ;
	}
	else{
		$confirmedTotal = 0;
	}

	if(isset($waitlistedSingle1) || isset($waitlistedDouble1) || isset($waitlistedTrible1)){
		$waitlistTotal =  ($waitlistedSingle1+$waitlistedDouble1+$waitlistedTrible1) ;
	}
	else{
		$waitlistTotal = 0;
	}

	if(isset($CancelledSingle1) || isset($CancelledDouble1) || isset($CancelledTrible1)){
		$cancelledTotal =  ($CancelledSingle1+$CancelledDouble1+$CancelledTrible1) ;
	}
	else{
		$cancelledTotal = 0;
	}
	
	cellColor1('I'.($head_hotel_row+1).":L".($head_hotel_row+1), 'FFFF66');
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue($head_order_data_total1. $head_hotel_row++, '')
	->setCellValue('I'. $head_hotel_row, 'Confirmed And Tentative')

	->setCellValue('L'. $head_hotel_row,$confirmedTotal)

	->setCellValue($head_order_data_total1++. $head_hotel_row,isset($ConfirmAndTenditiveSingle)?$ConfirmAndTenditiveSingle:0)

	->setCellValue($head_order_data_total1++.$head_hotel_row,isset($ConfirmAndTenditiveDouble)?$ConfirmAndTenditiveDouble:0)
	->setCellValue($head_order_data_total1++.$head_hotel_row,isset($ConfirmAndTenditiveTrible)?$ConfirmAndTenditiveTrible:0)
	->setCellValue($head_order_data_total1++. $head_hotel_row++, '');
	
	cellColor1('I'.$head_hotel_row.":L".$head_hotel_row, 'FFFF66');
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('i' .$head_hotel_row,'waitlisted')
	->setCellValue('L'. $head_hotel_row,$waitlistTotal)
	->setCellValue($head_order_data_total0++.$head_hotel_row,isset($waitlistedSingle1)?$waitlistedSingle1:0)
	->setCellValue($head_order_data_total0++ .$head_hotel_row,isset($waitlistedDouble1)?$waitlistedDouble1:0)
	->setCellValue($head_order_data_total0++ .$head_hotel_row,isset($waitlistedTrible1)?$waitlistedTrible1:0)
	->setCellValue($head_order_data_total0++. $head_hotel_row++, '');
	cellColor1('I'.$head_hotel_row.":L".$head_hotel_row, 'FFFF66');
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('i' .$head_hotel_row,'Cancelled')
		->setCellValue('L'. $head_hotel_row,$cancelledTotal)
		->setCellValue($head_order_data_total3++ .$head_hotel_row,$CancelledSingle1)
		->setCellValue($head_order_data_total3++ .$head_hotel_row,$CancelledDouble1)
		->setCellValue($head_order_data_total3++ .$head_hotel_row,$CancelledTrible1)
		->setCellValue($head_order_data_total3++. $head_hotel_row++, '')
		->setCellValue($head_order_data_total3++. $head_hotel_row++, '');

		$ConfirmAndTenditiveDouble	=array();
		$ConfirmAndTenditiveSingle	=array();
		$ConfirmAndTenditiveTrible	=array();
		$waitlistedSingle2	=array();
		$waitlistedTrible2	=array();
		$CancelledSingle	=array();
		$CancelledDouble	=array();
		$CancelledTrible	=array();
}

	//die;

$objPHPExcel->getActiveSheet(0)->setTitle('Date wise Booking Report');

		/*====================================================================================================================*/

}	

//lunch report

$objPHPExcel->createSheet(1);

  $sql_lunch = " SELECT `".TBL_ORDERS."`.*,`".TBL_USERS."`.name as name_executive 
  FROM `".TBL_ORDERS."`  

  LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company

  LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id

  LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id  

  LEFT JOIN `".TBL_ORDER_DETAIL."` on ".TBL_ORDERS.".id_order  =  ".TBL_ORDER_DETAIL.".id_order 

  WHERE `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND  ".$condlunch." AND `fs_orders`.`type` = 'L' 
   order by `".TBL_ORDERS."`.checkin, `".TBL_ORDERS."`.id_hotel";

$db->query($sql_lunch);


	$numRowsLunck= $db->num_rows();

	//$pagging = new pagingClass($sql,$setpage);

	//$db->query($pagging->getQuery());

	$total = $db->num_rows();

	$datawisearrayFinal = array();	

	$company_array=array();

	$agentwise_array=array();

	$hotel_array=array();

	$hotelwise_array=array();
	$executive_array=array();
	$executivewise_array=array();

	if($total > 0){

		$cntr_order= 0;

		while($LunchRecord = $db->fetch_object()){
				foreach($datewise_array as $checkinDatearr){			


				if(strtotime($checkinDatearr)>=strtotime(date('Y-m-d',strtotime($LunchRecord->checkin))) && strtotime($checkinDatearr)<=strtotime(date('Y-m-d',strtotime($LunchRecord->checkout)))){


					if(!in_array($LunchRecord->id_company, $company_array, true)){


								array_push($company_array, $LunchRecord->id_company);			

							}


						if(!in_array($LunchRecord->id_hotel, $hotel_array, true)){

								array_push($hotel_array, $LunchRecord->id_hotel);														

							}


						if(!in_array($LunchRecord->id_executive, $executive_array, true)){


								array_push($executive_array, $LunchRecord->id_executive);		

							}

								if($LunchRecord->booking_status==2){


										$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["noofdays_hold"] = $LunchRecord->room_quantity;
								}

						if($LunchRecord->booking_status==4){									



										$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["noofdays_withdraw"] = $LunchRecord->room_quantity;


						}


						if($LunchRecord->booking_status==1){									

							 $datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["noofdays_confirm"]=$LunchRecord->room_quantity;

						}

						if($LunchRecord->booking_status==3){

							$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["noofdays_waitlist"]=$LunchRecord->room_quantity;
						}


						if($LunchRecord->booking_status==1 || $LunchRecord->booking_status==2 ){
							$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["noofdays_totalbooked"]=$LunchRecord->room_quantity;

						}

						if($LunchRecord->booking_status==1 || $LunchRecord->booking_status==2 || $LunchRecord->booking_status==4 ){

						if($LunchRecord->booking_status==1 || $LunchRecord->booking_status==2){
							$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["noofdays_netbooked"]=$LunchRecord->room_quantity;
						}else{

							$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["noofdays_netbooked"]=$LunchRecord->room_quantity;
						}

					}



					//--End of Agent wise arrray build for Report
					//Hotel wise arrray build for Report

					if($LunchRecord->booking_status==4){									
						$hotelwise_array[$LunchRecord->id_hotel][$LunchRecord->id_company]["noofdays_withdraw"]=$LunchRecord->room_quantity;
					}







								if($LunchRecord->booking_status==1){									







									







										$hotelwise_array[$LunchRecord->id_hotel][$LunchRecord->id_company]["noofdays_confirm"]=$LunchRecord->room_quantity;







								}







								if($LunchRecord->booking_status==2){







									







										$hotelwise_array[$LunchRecord->id_hotel][$LunchRecord->id_company]["noofdays_hold"]=$LunchRecord->room_quantity;







								}







								if($LunchRecord->booking_status==3){







									







										$hotelwise_array[$LunchRecord->id_hotel][$LunchRecord->id_company]["noofdays_waitlist"]=$LunchRecord->room_quantity;







								}







								if($LunchRecord->booking_status==1 || $LunchRecord->booking_status==2 ){







								







										$hotelwise_array[$LunchRecord->id_hotel][$LunchRecord->id_company]["noofdays_totalbooked"]=$LunchRecord->room_quantity;







								}







								if($LunchRecord->booking_status==1 || $LunchRecord->booking_status==2 || $LunchRecord->booking_status==4 ){







									







									if($LunchRecord->booking_status==1 || $LunchRecord->booking_status==2){







									







											$hotelwise_array[$LunchRecord->id_hotel][$LunchRecord->id_company]["noofdays_netbooked"]=$LunchRecord->room_quantity;







									}else{







										







											$hotelwise_array[$LunchRecord->id_hotel][$LunchRecord->id_company]["noofdays_netbooked"] = $LunchRecord->room_quantity;







									}







								}







								//--End of Hotel wise arrray build for Report







								







								//Executive wise arrray build for Report







								







								if($LunchRecord->booking_status==4){									







									







										$executivewise_array[$LunchRecord->id_executive][$LunchRecord->id_hotel]["noofdays_withdraw"]=$LunchRecord->room_quantity;							







										







								}







								if($LunchRecord->booking_status==1){									







									







										$executivewise_array[$LunchRecord->id_executive][$LunchRecord->id_hotel]["noofdays_confirm"]=$LunchRecord->room_quantity;







									}







								if($LunchRecord->booking_status==2){







									







										$executivewise_array[$LunchRecord->id_executive][$LunchRecord->id_hotel]["noofdays_hold"]=$LunchRecord->room_quantity;







								}







								if($LunchRecord->booking_status==3){







									







										$executivewise_array[$LunchRecord->id_executive][$LunchRecord->id_hotel]["noofdays_waitlist"]=$LunchRecord->room_quantity;







								}







								if($LunchRecord->booking_status==1 || $LunchRecord->booking_status==2  ){







									







										$executivewise_array[$LunchRecord->id_executive][$LunchRecord->id_hotel]["noofdays_totalbooked"]=$LunchRecord->room_quantity;







								}







								if($LunchRecord->booking_status==1 || $LunchRecord->booking_status==2 || $LunchRecord->booking_status==4 ){







									







									if($LunchRecord->booking_status==1 || $LunchRecord->booking_status==2){







										







											$executivewise_array[$LunchRecord->id_executive][$LunchRecord->id_hotel]["noofdays_netbooked"]=$LunchRecord->room_quantity;







									}else{







										







											$executivewise_array[$LunchRecord->id_executive][$LunchRecord->id_hotel]["noofdays_netbooked"] = $LunchRecord->room_quantity;







									}







								}







								//--End of Executive wise arrray build for Report







								







			







			//echo "<pre>"; print_r($LunchRecord);


					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["id_hotel"]=$LunchRecord->id_hotel;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["room_id"]=$LunchRecord->room_id;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["lunch_type"]=$LunchRecord->type==L?"Yes":"No";

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["lunch_count"]=$LunchRecord->type==L?$LunchRecord->adults:0;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["reference"]=$LunchRecord->reference;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["company"]=$LunchRecord->id_company;


					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["customer"]=$LunchRecord->id_customer;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["payment_status"]=$LunchRecord->payment_status;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["booking_status"]=$LunchRecord->booking_status;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["invoice_date"]=$LunchRecord->invoice_date;


					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["name_executive"]=$LunchRecord->name_executive;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["checkin"]=$LunchRecord->checkin;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["checkout"]=$LunchRecord->checkout;


					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["no_of_days"]=$LunchRecord->no_of_days;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["total_adults"]=$LunchRecord->total_adults;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["total_infants"]=$LunchRecord->infants;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["total_child"]=$LunchRecord->child;

					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["total_products"]=$LunchRecord->room_quantity;
					$datawisearrayFinal[$checkinDatearr][$LunchRecord->id_order][$LunchRecord->room_id]["tarrif_price"]=$LunchRecord->tarrif_price;


				}

			}

	}
	}

	// Set document properties







	$objPHPExcel->getProperties()->setCreator("Akhil")


								 ->setLastModifiedBy("Akhil")

								 ->setTitle("Date wise Booking Report")

								 ->setSubject("Date wise Booking Report")

								 ->setDescription("Date wise Booking Report")

								 ->setKeywords("Date wise Booking Report")

								 ->setCategory("Report");



	if(isset($_POST['checkin_radio']) && $_POST['checkin_radio']=='1'){


	$from_text = "Checkin From";


	$totext = "Checkin To";

	$from_date =$checkinDate;


	$to_date = $checkoutDate;

}else{

	$from_text = "Booking From";

	$totext = "Booking To";


	$from_date =dateformat_date($checkin);

	$to_date = dateformat_date($checkout);


}

	function cellColor($cells,$color){


    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(


        'type' => PHPExcel_Style_Fill::FILL_SOLID,

        'startcolor' => array(

             'rgb' => $color

        )

    ));

}



	if($total > 0){$counter = 1;

	$head_hotel_row = 1;

		foreach($datawisearrayFinal as $dateCheckin=>$dateData){
			$head_cntr_column = "A";$head_hotel_column = "A";
			$objPHPExcel->setActiveSheetIndex(1)
				->setCellValue($head_cntr_column.$head_hotel_row, 'Date');

			$objPHPExcel->getActiveSheet()->getStyle($head_cntr_column++.$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getFont()->setBold(true);
			

			$objPHPExcel->setActiveSheetIndex(1)
				->setCellValue($head_cntr_column.$head_hotel_row++, dateformat_date($dateCheckin))
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Hotel Name')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Reservation Id')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Guest Name')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Source')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Payment Status')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Booking Status')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Booking Date')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Lunch Date')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'No Of Lunch')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Lunch Price/Pax');


			$objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row.":J".$head_hotel_row)->getFont()->setBold(true);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row.":J".$head_hotel_row)->getFont()->setBold(true)

                                ->setName('Calibri')



                                ->setSize(10)



                                ->getColor()->setRGB('00000');















cellColor('A'.$head_hotel_row.":J".$head_hotel_row, 'c2d69a');

				$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":J".$head_hotel_row)->getFont()->setBold(true);

				$head_hotel_row++;

					foreach($dateData as $hotelcheckarr=>$order_data1){


						foreach($order_data1 as $room_idfromarr=>$order_data){


										$head_order_data = "A";
											$objPHPExcel->setActiveSheetIndex(1)

											->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$order_data['id_hotel']."'"))


											->setCellValue($head_order_data++ . $head_hotel_row,  $order_data['reference'])

											->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_CUSTOMER,'CONCAT(first_name," ",last_name)'," WHERE `id_customer` = '".$order_data['customer']."'"))

											->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$order_data['company']."'"))

											->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".$order_data['payment_status']."'"))

											->setCellValue($head_order_data++ . $head_hotel_row, selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".$order_data['booking_status']."'"))
											->setCellValue($head_order_data++ . $head_hotel_row,dateformat_date($order_data['invoice_date']))

											//->setCellValue($head_order_data++ . $head_hotel_row,$order_data['name_executive'])

											->setCellValue($head_order_data++ . $head_hotel_row, dateformat_date($order_data['checkin']))







											->setCellValue($head_order_data++ . $head_hotel_row, $order_data['total_adults'])







											







											->setCellValue($head_order_data++ . $head_hotel_row, $order_data['tarrif_price']);







											







											







												







										$head_hotel_row++;







						







									}







																







									







									







									







												







					}												







											







								







			}







			







			







		







		



$objPHPExcel->getActiveSheet(1)->setTitle('Lunch Booking Report');
		}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	ob_end_clean();

	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="date_wise_booking_report.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}







if($_POST['Search'] == 'Search'){







	







	







	$db->query($sql);







	$numRows= $db->num_rows();







	//$pagging = new pagingClass($sql,$setpage);







	//$db->query($pagging->getQuery());







	$total = $db->num_rows();







	







	







	$datawisearrayFinal = array();			







	if($total > 0){







		







		$cntr_order= 0;







		while($row = $db->fetch_object()){







			







			







			







				foreach($datewise_array as $checkinDatearr){			







				







				







				if(strtotime($checkinDatearr)>=strtotime($row->checkin) && strtotime($checkinDatearr)<strtotime($row->checkout)){







					







					







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["id_hotel"]=$row->id_hotel;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["room_id"]=$row->room_id;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["lunch_type"]=$row->type==L?"Yes":"No";







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["lunch_count"]=$row->type==L?$row->adults:0;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["reference"]=$row->reference;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["company"]=$row->id_company;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["customer"]=$row->id_customer;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["payment_status"]=$row->payment_status;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["booking_status"]=$row->booking_status;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["invoice_date"]=$row->invoice_date;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["name_executive"]=$row->name_executive;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["checkin"]=$row->checkin;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["checkout"]=$row->checkout;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["no_of_days"]=$row->no_of_days;







					







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["total_adults"]=$row->adults;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["total_infants"]=$row->infants;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["total_child"]=$row->child;







					$datawisearrayFinal[$checkinDatearr][$row->id_order][$row->room_id]["total_products"]=$row->room_quantity;







					







				}







				







			}







		}







	}







}







?>

<?php include_once("includes/header.php")?>

  <?php include_once("includes/left.php")?>

  <div class="content-wrapper"> 

    

    <!-- Content Header (Page header) -->

    

    <section class="content-header">

      <h1> Hotel Booking Manager <small>Date Wise Bookings</small> </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Report Manager</li>

      </ol>

    </section>

    

    <!-- Main content -->

    

    <section class="content">

      <div class="box box-default">

        <div class="form-group has-error" align="center">

          <?php if($_SESSION['errorMsg']){?>

          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

          <?php unset($_SESSION['successMsg']);}?>

        </div>

        <div class="box-header with-border">

          <h3 class="box-title">Date wise Booking Reports &nbsp;</small> </h3>

          <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="booknow.php?type=N" >Book Now</a>

            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>

            <ul class="dropdown-menu" role="menu">

              <?php /*?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ORDERS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>







								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ORDERS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>

            </ul>

          </div>

        </div>

        

        <!-- /.box-header -->

        

        <form name="searchForm" action="" method="post">

          <input type="hidden" value="1" name="searchFormSubmit" />

          <div class="box-body">

            <div class="row">

              <div class="form-group col-sm-4">

                <label for="reservation_date">Checkin Date : From - To </label>

                <div class="input-group">

                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>

                  <input type="text" class="form-control pull-right dateRangeEdit" id="reservation_date" placeholder="Enter Checkin date" name="reservation_date" id="reservation_date" data-parsley-required value="<?php if(isset($_REQUEST['reservation_date'])) echo $_REQUEST['reservation_date'];?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">

                </div>

                

                <!-- /.input group --> 

                

                <span id="reservation_dateError"></span> </div>
                <div class="col-md-4">

                <div class="form-group">

                  <label>Source</label>

                  <?php $companyDropDown = '<select class="form-control select2" name="company_id">
                  	    <option value="">Select Source</option>';
                  	    $resCat = selectSql(TBL_COMPANY," where id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
	               	      if($db->num_rows2($resCat)){
							  	while($resultCat = $db->fetch_object2($resCat)){
									if($_REQUEST['company_id'] == $resultCat->id_company){
										$selected = 'selected="selected"';
									}else{
										$selected = '';
									}

									$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
								}
							}
						 	echo $companyDropDown .= '</select>';
			  ?>
               </div>
                <!-- /.form-group --> 
             </div>

              <div class="col-md-4">

                <div class="form-group">

                  <label>Executive</label>

                  <?php 
					 $executiveDropDown = '<select class="form-control select2" name="id_executive">
										    <option value="">Select Executive</option>';
											  $resCat = selectSql(TBL_USERS," where id_shop='".addslashes($_SESSION['shop'])."' AND status = 1 ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if(isset($_REQUEST['id_executive']) && $_REQUEST['id_executive']!="" && trim($_REQUEST['id_executive']) == $resultCat->id_executive){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$executiveDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' '.ucfirst($resultCat->last_name).'</option>';
												}
											  }
										 	echo $executiveDropDown .= '</select>';

				  ?>

                </div>
             </div>
              <div class="col-md-4">

                <div class="form-group">

                  <label>Hotel</label>

                  <?php $hotelDropDown = '<select class="form-control " name="hotelId[]" 										multiple="multiple" id="hotelOpId"
												onclick="reportSelection(this.value,this.id);">
										    <option value="0">Select All</option>';
								  $resCat = selectSql(TBL_HOTELS,"where id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');

												  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if(isset($_REQUEST['hotelId']))
													if(in_array($resultCat->id,$_REQUEST['hotelId'])){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}

												$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

												}
											  }
										 	echo $hotelDropDown .= '</select>';

									  ?>

                </div>

                

                <!-- /.form-group --> 

                

              </div>

              

              <!-- /.col -->

              

              <!--<div class="col-md-4">

                <div class="form-group">

                  <label>Guest</label>

                  <?php $guestDropDown = '<select class="form-control select2" name="guest">







											    <option value="">Select Guest</option>';







											  $resCat = selectSql(TBL_CUSTOMER," where type='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `first_name`');







											  if($db->num_rows2($resCat)){







											  	while($resultCat = $db->fetch_object2($resCat)){







													if(isset($_REQUEST['guest']) && $_REQUEST['guest']!="" && $_REQUEST['guest'] == $resultCat->guest){







														$selected = 'selected="selected"';







													}else{







														$selected = '';







													}







													$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">'.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).'</option>';







												}







											  }







											 	echo $guestDropDown .= '</select>';







											  ?>

                </div>

              </div>-->

              

              <div class="col-md-4">

                <div class="form-group">

                  <label>Payment Status</label>

                  <?php $paymentDropDown = '<select class="form-control" name="payment_status[]" multiple="multiple" id="paymentOpStatus" onclick="reportSelection(this.value,this.id);">







											    <option value="0">Select All</option>';







											  $resCat = selectSql(TBL_ORDER_STATE," ",' ORDER BY `name`');







											  if($db->num_rows2($resCat)){







											  	while($resultCat = $db->fetch_object2($resCat)){







													if(isset($_REQUEST['payment_status']))







													if(in_array($resultCat->id_order_state,$_REQUEST['payment_status'])){







														$selected = 'selected="selected"';







													}else{







														$selected = '';







													}







													







													$paymentDropDown .= '<option '.$selected.' value="'.$resultCat->id_order_state.'">'.ucfirst($resultCat->name).'</option>';







												}







											  }







											 	echo $paymentDropDown .= '</select>';







											  ?>

                </div>

                

                <!-- /.form-group --> 

                

              </div>

              <div class="col-md-4">

                <div class="form-group">

                  <label>Booking Status</label>

                  <?php $bookDropDown = '<select class="form-control" name="booking_status[]" multiple="multiple" id="bookOpStatus" oncLick="reportSelection(this.value,this.id);">







											    <option value="0">Select All</option>';







											  $resCat = selectSql(TBL_HTL_BOOKING_STATUS," ",' ORDER BY `name`');







											  if($db->num_rows2($resCat)){







											  	while($resultCat = $db->fetch_object2($resCat)){

													if(isset($_REQUEST['booking_status']))







													if(in_array($resultCat->id,$_REQUEST['booking_status'])){







														$selected = 'selected="selected"';







													}else{







														$selected = '';







													}







													$bookDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';







												}







											  }







											 	echo $bookDropDown .= '</select>';







											  ?>

                </div>

                

                <!-- /.form-group --> 

                

              </div>

              

              <!-- /.row --> 

              

            </div>

            <div class="form-group ">

              <label for="total_products" class="col-sm-2" style="display: none !important; visibility: hidden !important;">

                <input type="checkbox" checked="checked"  class="flat-red" id="total_products" name="total_products" value="1" <?php if($_POST['total_products']=='1'){echo 'checked="checked"'; }?>>

                &nbsp;&nbsp; Rooms </label>

              <label for="total_adults" class="col-sm-2">

                <input type="checkbox" checked="checked" class="flat-red" id="total_adults	" name="total_adults" value="1" <?php if($_POST['total_adults']=='1'){echo 'checked="checked"'; }?>>

                &nbsp;&nbsp; Adults </label>

              <!--<label for="total_infants" class="col-sm-2">

                <input type="checkbox"  class="flat-red" id="	total_infants" name="total_infants" value="1" <?php if($_POST['total_infants']=='1'){echo 'checked="checked"'; }?>>

                &nbsp;&nbsp;Infants </label>

              <label for="total_child"  class="col-sm-2">

                <input type="checkbox"  class="flat-red" id="total_child	" name="total_child" value="1" <?php if($_POST['total_child']=='1'){echo 'checked="checked"'; }?>>

                &nbsp;&nbsp;Child </label>-->

              

              <!-- <label for="lunch_booking_chk"  class="col-sm-2">		







                  <input type="checkbox"  class="flat-red" id="lunch_booking_chk" name="lunch_booking_chk" value="1" <?php if($_POST['lunch_booking_chk']=='1'){echo 'checked="checked"'; }?>>&nbsp;&nbsp;Lunch Booking







				  </label> --> 

              

            </div>

          </div>

          

          <!-- /.box-body -->

          

          <div class="box-footer">

            <input name="Search" type="submit" class="btn btn-primary" value="Search" />

            <input name="Download" type="submit" class="btn btn-primary" value="Generate" />

          </div>

        </form>

      </div>

      <style>







	  #example2 tbody tr td, #example2 tbody tr th{padding:2px;}







	  </style>

      <div class="row">

        <div class="col-xs-12"> 

          

          <!-- /.box -->

          

          <div class="box">

            <div class="box-header">

              <h3 class="box-title">Hotel List</h3>

            </div>

            <form name="listingForm" action="" method="post">

              <input type="hidden" value="" name="act" />

              <div id="listingDiv"></div>

              

              <!-- /.box-header -->

              

              <div class="box-body table-responsive">

                <table id="example2" class="table table-bordered table-striped">

                  <thead>

                  </thead>

                  <tbody>

                    <?php 		







				







				if($total > 0){$counter = 1;







				







				foreach($datawisearrayFinal as $dateCheckin=>$dateData){?>

                    <tr>

                      <th colspan=1 style="background-color:#01B9F5; color: white;">Date: <?php echo dateformat_date($dateCheckin)?></th>

                    </tr>

                    <tr>

                      <td colspan=11><table >

                          <tr>

                            <th>Hotel Name</th>

                            <th>Reservation Id</th>

                            <th>Room Type</th>

                            <th>Guest Name</th>

                            <th>Source</th>

                            <th>Payment Status</th>

                            <th>Booking Status</th>

                            <th>Booking Date</th>

                            <th>Executive Name</th>

                            <th>Checkin-Checkout</th>

                            <th>No of Nights</th>

                            <?php if(isset($_POST['total_products']) && $_POST['total_products']==1){







?>

                            <th>Rooms</th>

                            <?php } ?>

                            <?php if(isset($_POST['total_adults']) && $_POST['total_adults']==1){







?>

                            <th>Adults</th>

                            <?php } ?>

                            <?php if(isset($_POST['total_infants']) && $_POST['total_infants']==1){







?>

                            <th>Infants</th>

                            <?php } ?>

                            <?php if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){







?>

                            <th>Lunch Booking</th>

                            <?php } ?>

                            <?php if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){







?>

                            <th>Lunch Count</th>

                            <?php } ?>

                            <?php if(isset($_POST['total_child']) && $_POST['total_child']==1){







?>

                            <th>Child</th>

                            <?php } ?>

                          </tr>

                          <?php







					foreach($dateData as $hotelcheckarr=>$order_data1){







						







					







						foreach($order_data1 as $room_idfromarr=>$order_data){







						







						?>

                          <tr>

                            <td><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$order_data['id_hotel']."'");?></td>

                            <td><?php echo $order_data['reference']?></td>

                            <td><?php echo selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$order_data['room_id']."'");?></td>

                            <td><?php echo selectColumn(TBL_CUSTOMER,'CONCAT(first_name," ",last_name)'," WHERE `id_customer` = '".$order_data['customer']."'");?></td>

                            <td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$order_data['company']."'");?></td>

                            <td><?=selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".$order_data['payment_status']."'");?></td>

                            <td><?=selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".$order_data['booking_status']."'");?></td>

                            <td><?=dateformat_date($order_data['invoice_date']);?></td>

                            <td><?php echo $order_data['name_executive']?></td>

                            <td><?=dateformat_date($order_data['checkin'])." - ".dateformat_date($order_data['checkout']);?></td>

                            <td><?php echo $order_data['no_of_days']?></td>

                            <?php if(isset($_POST['total_products']) && $_POST['total_products']==1){







								?>

                            <td><?=round($order_data['total_products']);?></td>

                            <?php } ?>

                            <?php if(isset($_POST['total_adults']) && $_POST['total_adults']==1){







							?>

                            <td><?=$order_data['total_adults'];?></td>

                            <?php } ?>

                            <?php if(isset($_POST['total_infants']) && $_POST['total_infants']==1){







					?>

                            <td><?=$order_data['total_infants'];?></td>

                            <?php } ?>

                            <?php if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){







						?>

                            <td><?php echo $order_data['lunch_type']?></td>

                            <?php } ?>

                            <?php if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){







						?>

                            <td><?php echo $order_data['lunch_count']?></td>

                            <?php } ?>

                            <?php if(isset($_POST['total_child']) && $_POST['total_child']==1){







					?>

                            <td><?=$order_data['total_child'];?></td>

                            <?php } ?>

                              </tr/>

                            <?php







						}







					}







				?>

                        </table></td>

                    </tr>

                    <?php







				}







				?>

                    <tr>

                      <td align="left" colspan="8">&nbsp;&nbsp;&nbsp;&nbsp; </td>

                    </tr>

                    <tr>

                      <td align="right" colspan="5"><?php  //echo $pagging->getLinks();?></td>

                    </tr>

                    <?php }else {?>

                    <tr>

                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>

                    </tr>

                    <?php }?>

                  </tbody>

                </table>

              </div>

            </form>

            

            <!-- /.box-body --> 

            

          </div>

          

          <!-- /.box --> 

          

        </div>

        

        <!-- /.col --> 

        

      </div>

      

      <!-- /.row --> 

      

      <!-- /.row --> 

      

    </section>

    

    <!-- /.content --> 

    

  </div>
  <!-- Below script will select all the option in reports section
  -->
    <script type="text/javascript">
    	function reportSelection(op,id){
     		if(document.getElementById(id).options[0].selected == true){
    			console.log("selected");
    			selectAll(id,true);
    		}
    	}
    		
  	
    	function selectAll(selectBox,selectAll) { 

    		 if (typeof selectBox == "string") { 
    		    selectBox = document.getElementById(selectBox);
    		   }
    		
    		   for (var i = 0; i < selectBox.options.length; i++) { 
    		       selectBox.options[i].selected = selectAll; 
    		    }		  		    
    	}

    </script>
   <!-- End script -->
  <?php include_once("includes/footer.php")?>

