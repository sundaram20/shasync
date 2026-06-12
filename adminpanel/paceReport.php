<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');

	// ----------cate---------
	
	//print_r($_SESSION);
	$cond = "  where `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";
	
	if($_REQUEST['search_name'] != ''){
		$cond .= " AND (`reference` LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(reference,'-', code) LIKE '%".addslashes($_REQUEST['search_name'])."%' )";
	}
	if($_REQUEST['hotelId'] != ''){
		
		$hotel_ids = implode(',',$_REQUEST['hotelId']);
		$cond .= " AND `".TBL_ORDERS."`.`id_hotel` in (".$hotel_ids.")";
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
	
	if($_SESSION['hotel_access']!=''){
		
		$condhotelAccess .= "  AND `fs_order_detail`.hotel_id='".$_SESSION['hotel_access']."'";
		}
	//checkin_radio
	
if($_REQUEST['reservation_date'] != ''){
		//list($checkin,$checkout) = split(" to ",$_REQUEST['reservation_date']);	
				$reservation_date= explode(" to ",$_REQUEST['reservation_date']);
		$checkin = $reservation_date['0'];
		$checkout = $reservation_date['1'];

		//$cond .= " AND `".TBL_ORDERS."`.`checkin` = '".date('Y-m-d',strtotime($checkin))."' and `".TBL_ORDERS."`.`checkout` = '".date('Y-m-d',strtotime($checkout))."'";
		if(strtotime($checkin)!=strtotime($checkout)){
			$tillcheckout = date ("Y-m-d", strtotime("-1 day", strtotime($checkout)));
			$cond .= " AND `".TBL_ORDERS."`.`checkin` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
		}else{
			$cond .= " AND `".TBL_ORDERS."`.`checkin`='".date('Y-m-d',strtotime($checkin))."'";
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
		$cond .= " AND `".TBL_ORDERS."`.`id_executive` = '".addslashes($_REQUEST['id_executive'])."'";		
		
	}
	
	
	
	$start_date	=	date('Y-m-d',strtotime($_REQUEST['start_date']));
	$end_date	=	date('Y-m-d',strtotime($_REQUEST['end_date']));
	$pace_date	=	date('Y-m-d',strtotime($_REQUEST['pace_date']));
	

	$date=date_create($start_date);
	date_add($date,date_interval_create_from_date_string("-1 year"));
	$LYstart_date	=	date_format($date,"Y-m-d");
	
	
	$date_end=date_create($end_date);
	date_add($date_end,date_interval_create_from_date_string("-1 year"));
	$LYend_date	=	date_format($date_end,"Y-m-d");


	$LYstart_date	=date('Y-m-d',strtotime($LYstart_date));
	$LYend_date	=	date('Y-m-d',strtotime($LYend_date));

$ts1 = strtotime($start_date);
$ts2 = strtotime($end_date);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);

$month1 = date('m', $ts1);
$month2 = date('m', $ts2);

$diff = (($year2 - $year1) * 12) + ($month2 - $month1);

	  	//"SELECT `fs_orders`.id_order,`fs_order_detail`.room_id ,`fs_order_detail`.id_order_detail ,`fs_order_detail`.`dated` ,`fs_orders`.booking_status,`fs_hotels`.name as HotelName, `fs_order_detail`.hotel_id,  sum(`fs_order_detail`.room_quantity) as room_quantity, sum(case when `booking_status` = '1' then 1 else 0 end) as `Confirm`,sum(case when `booking_status` = '2' then 1 else 0 end) as `Tentative`,sum(case when `booking_status` = '3' then 1 else 0 end) as `Waitlisted`,sum(case when `booking_status` = '4' then 1 else 0 end) as `Cancelled` FROM `fs_orders`  right join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order  and  ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) and (`fs_orders` .invoice_date <= '".$pace_date."') left join `fs_hotels` on `fs_hotels`.id=`fs_order_detail`.hotel_id where  `fs_orders`.`id_shop` = '2'    group by `fs_order_detail`.hotel_id and `fs_order_detail`.hotel_id=120 ORDER BY `fs_order_detail`.`dated` ASC";
	  
	  
	  
	   $sql	=" SELECT `fs_orders`.id_order,`fs_order_detail`.room_id ,`fs_order_detail`.id_order_detail ,`fs_order_detail`.`dated` ,`fs_orders`.booking_status,`fs_hotels`.name as HotelName, `fs_order_detail`.hotel_id, 
	 
	  
	  sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$LYstart_date."' and '".$LYend_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LY_ConfirmandTend`,
	  
	  sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) then `fs_order_detail`.room_quantity else 0 end) as `ConfirmandTend`,
	 
	  sum(case when `booking_status` = '3' and ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`,
	  sum(case when `booking_status` = '4' and ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled` 
	  
	  
	  FROM `fs_orders` right join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order  and (`fs_orders` .invoice_date <= '".$pace_date."') left join `fs_hotels` on `fs_hotels`.id=`fs_order_detail`.hotel_id where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' $condhotelAccess group by `fs_order_detail`.hotel_id  ORDER BY `fs_order_detail`.`dated` ASc";
	  
	  
	  
	   $sql22	=" SELECT `fs_orders`.id_order,`fs_order_detail`.room_id ,`fs_order_detail`.id_order_detail ,`fs_order_detail`.`dated` ,`fs_orders`.booking_status,`fs_hotels`.name as HotelName, `fs_order_detail`.hotel_id, 
	 
	  
	  sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$LYstart_date."' and '".$LYend_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LY_ConfirmandTend`,
	  
	  sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) then `fs_order_detail`.room_quantity else 0 end) as `ConfirmandTend`,
	 
	  sum(case when `booking_status` = '3' and ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`,
	  sum(case when `booking_status` = '4' and ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled` 
	  
	  
			  FROM 
	  				`fs_orders` 
	    RIGHT JOIN
	  				`fs_order_detail` 
				ON
					 fs_orders.id_order=fs_order_detail.id_order  
					 AND (`fs_orders` .invoice_date <= '".$pace_date."') 
	  	 LEFT JOIN	
					`fs_hotels` 
  				ON 
					`fs_hotels`.id=`fs_order_detail`.hotel_id 
					
			WHERE `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' group by `fs_order_detail`.hotel_id  ORDER BY `fs_order_detail`.`dated` ASc";
	  //"SELECT `fs_orders`.id_order,`fs_order_detail`.room_id ,`fs_orders` .invoice_date,`fs_order_detail`.id_order_detail ,`fs_order_detail`.`dated`, `fs_order_detail`.`room_quantity` ,`fs_orders`.booking_status,`fs_hotels`.name as HotelName, `fs_order_detail`.hotel_id FROM `fs_orders` right join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order and ( ( `fs_order_detail` .dated between '2017-01-01' and '2018-04-25')) and (`fs_orders` .invoice_date <= '2018-04-25') left join `fs_hotels` on `fs_hotels`.id=`fs_order_detail`.hotel_id where `fs_orders`.`id_shop` = '2' AND `fs_orders`.`booking_status` in (1,2) AND `fs_order_detail`.hotel_id=120 ORDER BY `fs_orders`.`id_order`,`fs_order_detail`.`dated` ASC";
	  
	  
	  //"SELECT `fs_orders`.id_order,`fs_order_detail`.room_id ,`fs_order_detail`.id_order_detail ,`fs_order_detail`.`dated` ,`fs_orders`.booking_status,`fs_hotels`.name as HotelName, `fs_order_detail`.hotel_id,  count(*) as `subtotal`, sum(case when `booking_status` = '1' then 1 else 0 end) as `Confirm`,sum(case when `booking_status` = '2' then 1 else 0 end) as `Tentative`,sum(case when `booking_status` = '3' then 1 else 0 end) as `Waitlisted`,sum(case when `booking_status` = '4' then 1 else 0 end) as `Cancelled` FROM `fs_orders`  right join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order  and  ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) and (`fs_order_detail` .dated <= '".$pace_date."') left join `fs_hotels` on `fs_hotels`.id=`fs_order_detail`.hotel_id where  `fs_orders`.`id_shop` = '2'    group by `fs_order_detail`.hotel_id  ORDER BY `fs_orders`.`id_order` ASC";
	
	
	//"SELECT * FROM  `".TBL_ORDER_DETAIL."`  WHERE  ((dated <=  '".$start_date."' and dated >= '".$end_date."') OR ( dated between '".$start_date."' and '".$end_date."')) and (dated <=  '".$pace_date."') and id_shop='".addslashes($_SESSION['shop'])."' ";
	 /*
	 SELECT `fs_orders`.booking_status,`fs_orders`.id_order, `fs_order_detail`.hotel_id , `fs_order_detail`.id_order_detail ,`fs_order_detail`.dated, count(*) as Total FROM `fs_orders` right join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order and `fs_order_detail` .dated BETWEEN DATE_SUB( '2018-04-01' ,INTERVAL 3 MONTH ) AND DATE_SUB( '2018-04-01' ,INTERVAL 0 MONTH ) where `fs_orders`.`id_shop` = '2' AND `fs_orders`.`id_hotel` in (120) AND `fs_orders`.`booking_status` in (1,2) ORDER BY `fs_order_detail`.`dated` DESC







	 FINAL 
	 
	 SELECT `fs_orders`.id_order,`fs_order_detail`.room_id ,`fs_order_detail`.id_order_detail ,`fs_order_detail`.`dated` ,`fs_orders`.booking_status,`fs_hotels`.name as HotelName, `fs_order_detail`.hotel_id,  count(*) as `subtotal`, sum(case when `booking_status` = '1' then 1 else 0 end) as `Confirm`,sum(case when `booking_status` = '2' then 1 else 0 end) as `Tentative`,sum(case when `booking_status` = '3' then 1 else 0 end) as `Waitlisted`,sum(case when `booking_status` = '4' then 1 else 0 end) as `Cancelled` FROM `fs_orders`  right join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order  and  ((`fs_order_detail` .dated <= '2018-02-01' and `fs_order_detail` .dated >= '2018-03-31') OR ( `fs_order_detail` .dated between '2018-02-01' and '2018-03-31')) and (`fs_order_detail` .dated <= '2018-03-30') left join `fs_hotels` on `fs_hotels`.id=`fs_order_detail`.hotel_id where  `fs_orders`.`id_shop` = '2'    group by `fs_order_detail`.hotel_id  ORDER BY `fs_orders`.`id_order` ASC
	 */

	//echo $sql;
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
	if($total > 0){
		
		$cntr_order= 0;
		
	}
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Akhil")
								 ->setLastModifiedBy("Akhil")
								 ->setTitle("Date wise Booking Report")
								 ->setSubject("Date wise Booking Report")
								 ->setDescription("Date wise Booking Report")
								 ->setKeywords("Date wise Booking Report")
								 ->setCategory("Report");



	
		// Add some data

	
	if($total > 0){$counter = 1;
	
	
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A2', 'PACE REPORT ON  '.date("d-m-Y",strtotime($pace_date)))
	->setCellValue('B4', 'Period '.date("d-m-Y",strtotime($start_date)).' to '.date("d-m-Y",strtotime($end_date)))
	->setCellValue('C6', 'Confirmed RN')
	->setCellValue('E6', 'This Year');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
	$objPHPExcel->getActiveSheet()->mergeCells('B4:F4');
	$objPHPExcel->getActiveSheet()->mergeCells('C6:D6');
	$objPHPExcel->getActiveSheet()->mergeCells('E6:F6');
	$head_hotel_row = 7;

		//foreach($datawisearrayFinal as $dateCheckin=>$dateData){
			$head_cntr_column = "A";$head_hotel_column = "A";
				
			
			/*$objPHPExcel->getActiveSheet()->getStyle('B9')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->mergeCells('B12:B1');
			$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
			*/
			
			
			
				
				$objPHPExcel->setActiveSheetIndex(0)
				
				->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'UNIT NAME')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Year')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Confirm')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Waitlisted')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Cancelled')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Variance Vs LY');

$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 15,
        'name'  => 'Verdana'
    ));

$styleArray_1 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));





$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
 $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleArray_1);
 $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);




$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('C11:G11')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
	
 $styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '000'),
		),
	),
);	

	$objPHPExcel->getActiveSheet()->getStyle('C6:D6')->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('C6:D6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('C6:D6')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('E6:F6')->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('E6:F6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('A6:G6')->getFont()->setBold(true);
	
	
	$objPHPExcel->getActiveSheet()->getStyle('A6:G6')->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('A6:G6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);


	
$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(33);	

$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	

$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);	

$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(15);				 

				$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":G".$head_hotel_row)->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('A5:G5')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$head_hotel_row++;
					
					
						
	
	$connew	=$head_hotel_row;
	$Serialno=1;
	while($row = $db->fetch_object()){
	
	
	
	$head_order_data1 = "A";
	$head_order_data = "A";       
	
	
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $connew, $Serialno++)
->setCellValue($head_order_data++ . $connew, $row->HotelName)
->setCellValue($head_order_data++ . $connew, $row->LY_ConfirmandTend)
->setCellValue($head_order_data++ . $connew, $row->ConfirmandTend)
->setCellValue($head_order_data++ . $connew, $row->Waitlisted)
->setCellValue($head_order_data++ . $connew, $row->Cancelled)
->setCellValue($head_order_data . $connew, $row->ConfirmandTend-$row->LY_ConfirmandTend);
	
	
	$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$head_hotel_row.':'.$head_order_data .$connew)->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('A5:G5')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);


$connew++;				}
						
									
					
		
		
			//}
	}
	$objPHPExcel->getActiveSheet()->setTitle('Pace Report');

								
	//$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);



	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="pace_report.xls"');
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
	
	
	

}


?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Pace Report Manager
        <small>Pace Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pace Report Manager</li>
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
          <h3 class="box-title">Pace Report &nbsp;</small> </h3>
		  <!--<div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="booknow.php?type=N" >Book Now</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php /*?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ORDERS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ORDERS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
							  
							  </ul>
							</div>-->
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="post">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
		  <div class="form-group col-sm-4">
                   
                    <div class="form-group">
                  <label for="start_date">Checkin Date from</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="start_date" name="start_date" value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
                    <!-- /.input group -->
                    <span id="reservation_dateError"></span> </div>
					
            <div class="col-md-4">
              <div class="form-group">
               
					
				
                  <label for="end_date">Checkin Date To</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="end_date" name="end_date" value="<?php if($_POST) echo $_POST['end_date'];elseif($row->end_date) echo stripslashes(date('d-m-Y',strtotime($row->end_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_end_date;?>
                </div>
              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->  
			
		  
		  
			<div class="col-md-4">
              <div class="form-group">
                  <label for="start_date">AS On</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="pace_date" name="pace_date" value="<?php if($_POST) echo $_POST['pace_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
              <!-- /.form-group -->
            </div>
			
			
			
			
			
          <!-- /.row -->
        </div>
		
			
			
		
					
				
			
				
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
       <!--<input name="Search" type="submit" class="btn btn-primary" value="Search" />-->
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
              <!--<h3 class="box-title">Pace Report List</h3>-->
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
				
				?>
				<tr><th colspan=5>Pace Report On : <?php echo dateformat_date($pace_date)?></th><td colspan=1></td></tr>
				
				
				<tr>
                  <th>Hotel Name</th>
				  <th>Last Year</th>
				  <th>Confirm</th>
				  
                  
				  <th>Waitlisted</th>
				  <th>Cancelled</th>
				  <th>Variance Vs LY</th> 
				    



                </tr>
				<?php
				while($row = $db->fetch_object()){
						
						
						
						
						?>

							<tr>
								<td><?php echo $row->HotelName;?></td>
                                <td><?php echo $row->LY_ConfirmandTend; ?></td>
								<td><?php echo $row->ConfirmandTend;?></td>
								<td><?php echo $row->Waitlisted;?></td>								
								<td><?php echo $row->Cancelled;?></td>
								<td><?php echo $row->ConfirmandTend-$row->LY_ConfirmandTend;?></td>
								
								
							
							</tr/>

						<?php
				}
				?>
				
				
					
				<?php
				
				?>
			   
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr>               
				<?php }else {?>
				
				 <!--<tr>
                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>
                 </tr>-->                 
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
<?php include_once("includes/footer.php")?>  