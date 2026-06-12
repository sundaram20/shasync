<?php include_once("../config/auto_loader.php");?>

<?php 
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');

//fetching dates
if($_SESSION['hotel_access']!=''){
	$condhotelAccess .= "  AND `fs_order_detail`.hotel_id='".$_SESSION['hotel_access']."'";
}
$setFormat = date_create($_REQUEST["flash_date"]);
$current_date = $setFormat->format('Y-m-d');
$last_year_current_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
//MTD
$from = date_create($current_date);
$from_month_to_date = date_create($from->format('Y-m-01'));
$from_month_to_date = $from_month_to_date->format('Y-m-d');
$to_month_to_date = $current_date;
$last_year_to_month_to_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
$from = date_create($last_year_to_month_to_date);
$last_year_from_month_to_date = date_create($from->format('Y-m-01'));
$last_year_from_month_to_date = $last_year_from_month_to_date->format('Y-m-d');

//YTD
$to_year_to_date = $current_date;
$from = date_create($current_date);
$from_year_to_date = date_create($from->format('Y-04-01'));
$from_year_to_date = $from_year_to_date->format('Y-m-d');
$last_year_to_year_to_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
$from = date_create($last_year_to_year_to_date);
$last_year_from_year_to_date = date_create($from->format('Y-04-01'));
$last_year_from_year_to_date = $last_year_from_year_to_date->format('Y-m-d');

//printing dates
/*echo "Today";
echo "<br>1)Current_date :".$current_date;
echo "<br>2)Last_year_current_date :".$last_year_current_date;
echo "<br><br>MTD";
echo "<br>3) From_month_to_date :".$from_month_to_date;
echo "<br>4)To_month_to_date :".$to_month_to_date;
echo "<br>5)Last_year_to_month_to_date :".$last_year_to_month_to_date;
echo "<br>6)Last_year_from_month_to_date : ".$last_year_from_month_to_date;
echo "<br><br>YTD";
echo "<br>7)from_year_to_date :".$from_year_to_date;
echo "<br>8)to_year_to_date :".$to_year_to_date;
echo "<br>9)Last_year_to_year_to_date :".$last_year_to_year_to_date;
echo "<br>10)Last_year_from_year_to_date :".$last_year_from_year_to_date;*/

//jump
 $sql	=" SELECT `fs_orders`.id_order,`fs_order_detail`.room_id ,`fs_order_detail`.id_order_detail ,`fs_order_detail`.`dated` ,`fs_orders`.booking_status,`fs_hotels`.name as `HotelName`, `fs_order_detail`.hotel_id, 

sum(case when (`booking_status` = '1' || `booking_status` = '2') and (  `fs_order_detail` .dated = '".$current_date."') then `fs_order_detail`.room_quantity else 0 end) as `ThisYearConfirmandTend`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and (  `fs_order_detail` .dated = '".$current_date."') then `fs_order_detail`.tarrif_price else 0 end) as `ValueThisYearConfirmandTend`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( `fs_order_detail` .dated = '".$last_year_current_date."') then `fs_order_detail`.room_quantity else 0 end) as `LastYearConfirmandTend`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and (  `fs_order_detail` .dated = '".$last_year_current_date."') then `fs_order_detail`.tarrif_price else 0 end) as `ValueLastYearConfirmandTend`,



sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$from_month_to_date."' and '".$to_month_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `MTDthisyear`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$from_month_to_date."' and '".$to_month_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `ValueMTDthisyear`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$last_year_from_month_to_date."' and '".$last_year_to_month_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LastYearMTD`,
sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$last_year_from_month_to_date."' and '".$last_year_to_month_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `ValueLastYearMTD`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `YTDthisyear`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `ValueYTDthisyear`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$last_year_from_year_to_date."' and '".$last_year_to_year_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LastYearYTD`,
sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$last_year_from_year_to_date."' and '".$last_year_to_year_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `ValueLastYearYTD`

FROM `fs_orders` right join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order  and (`fs_orders` .invoice_date <= '".$current_date."') left join `fs_hotels` on `fs_hotels`.id=`fs_order_detail`.hotel_id where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' $condhotelAccess group by `fs_order_detail`.hotel_id  ORDER BY `fs_order_detail`.`dated` ASC";




//IF DOWNLOAD
if($_POST['Download'] == 'Download'){
	error_reporting(1);
	$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
	$rowShop = $db->fetch_object2($resShop);


	$db->query($sql);
	$numRows= $db->num_rows();
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

	
	if($total > 0){
		$counter = 1;
	
	
	
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A2', 'FLASH REPORT ON  '.date("d-m-Y",strtotime($current_date)));
		$objPHPExcel->getActiveSheet()->mergeCells('A2:O2');
		$objPHPExcel->getActiveSheet()->mergeCells('B4:B5');
		//$objPHPExcel->getActiveSheet()->mergeCells('C6:D6');
		//$objPHPExcel->getActiveSheet()->mergeCells('E6:F6');
		$objPHPExcel->getActiveSheet()->mergeCells('C4:D4');
		$objPHPExcel->getActiveSheet()->mergeCells('E4:F4');
		$objPHPExcel->getActiveSheet()->mergeCells('G4:H4');
		$objPHPExcel->getActiveSheet()->mergeCells('I4:J4');
		$objPHPExcel->getActiveSheet()->mergeCells('K4:L4');
		$objPHPExcel->getActiveSheet()->mergeCells('M4:N4');
		$head_hotel_row = 5;
		
		$head_hotel_row2 = 5;
		$head_cntr_column2 = "B";
		
		$head_cntr_column = "A";
		$head_hotel_column = "A";
			
			
			
				
		$objPHPExcel->setActiveSheetIndex(0)
		
			->setCellValue(A5, 'S.No.')
			->setCellValue(B4, 'Hotel Name')
			->setCellValue(C4, 'Today')
			->setCellValue(E4, 'Same Day Last Year')
			->setCellValue(G4, 'MTD This Year')
			->setCellValue(I4, 'MTD Last Year')
			->setCellValue(K4, 'YTD This Year')
			->setCellValue(M4, 'YTD Last Year')
			->setCellValue(O4, 'Variance Vs LY')

			->setCellValue(B5, 'Hotel Name')
			->setCellValue(C5, 'R N')
			->setCellValue(D5, 'Value (in Lacs)')
			->setCellValue(E5, 'R N')
			->setCellValue(F5, 'Value (in Lacs)')
			->setCellValue(G5, 'R N')
			->setCellValue(H5, 'Value (in Lacs)')
			->setCellValue(I5, 'R N')
			->setCellValue(J5, 'Value (in Lacs)')
			->setCellValue(K5, 'R N')
			->setCellValue(L5, 'Value (in Lacs)')
			->setCellValue(M5, 'R N')
			->setCellValue(N5, 'Value (in Lacs)')
			->setCellValue(O5, 'Value (in Lacs)');

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
 $objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('A4:O4')->applyFromArray($styleArray_1);
 $objPHPExcel->getActiveSheet()->getStyle('A4:O4')->getAlignment()->applyFromArray(
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
$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->applyFromArray(
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

	/*$objPHPExcel->getActiveSheet()->getStyle('C6:D6')->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('C6:D6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);*/
/*$objPHPExcel->getActiveSheet()->getStyle('C6:D6')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('E6:F6')->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('E6:F6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);*/
$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getFont()->setBold(true);
	
	
	$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);


	
$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(33);	

$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	

$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);	

$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('O')->setWidth(20);				 

				$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":G".$head_hotel_row)->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$head_hotel_row++;
	
				
					
						
	
	$connew	=$head_hotel_row;
	$Serialno=1;
	$sumThisValue =0;
	$sumLastValue =0;
	$sumThisMTDValue =0;
	$sumLastMTDValue =0;
	$sumThisYTDValue =0;
	$sumLastYTDValue =0;
	$sumThisRn =0;
	$sumLastRn =0;
	$sumThisMTDRn =0;
	$sumLastMTDRn =0;
	$sumThisYTDRn =0;
	$sumLastYTDRn =0;
	while($row = $db->fetch_object()){

	$sumThisValue +=$row->ValueThisYearConfirmandTend;
	$sumLastValue +=$row->ValueLastYearConfirmandTend;
	$sumThisMTDValue +=$row->ValueMTDthisyear;
	$sumLastMTDValue +=$row->ValueLastYearMTD;
	$sumThisYTDValue +=$row->ValueYTDthisyear;
	$sumLastYTDValue +=$row->ValueLastYearYTD;
	$sumThisRn += $row->ThisYearConfirmandTend;
	$sumLastRn +=$row->LastYearConfirmandTend;
	$sumThisMTDRn += $row->MTDthisyear;
	$sumLastMTDRn +=$row->LastYearMTD;
	$sumThisYTDRn +=$row->YTDthisyear;
	$sumLastYTDRn +=$row->LastYearYTD;	
	
	if($row->ValueLastYearYTD != 0){				
		$Variance = ((($row->ValueYTDthisyear-$row->ValueLastYearYTD)/$row->ValueLastYearYTD)*100);
	}
	else{
		$Variance = 0;
	}	
	
	$head_order_data1 = "A";
	$head_order_data = "A";       
	
	//jump
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $connew, $Serialno++)
->setCellValue($head_order_data++ . $connew, $row->HotelName)
->setCellValue($head_order_data++ . $connew, $row->ThisYearConfirmandTend)
->setCellValue($head_order_data++ . $connew, round($row->ValueThisYearConfirmandTend/100000,2))
->setCellValue($head_order_data++ . $connew, $row->LastYearConfirmandTend)
->setCellValue($head_order_data++ . $connew, round($row->ValueLastYearConfirmandTend/100000,2))
->setCellValue($head_order_data++ . $connew, $row->MTDthisyear)
->setCellValue($head_order_data++ . $connew, round($row->ValueMTDthisyear/100000,2))
->setCellValue($head_order_data++ . $connew, $row->LastYearMTD)
->setCellValue($head_order_data++ . $connew, round($row->ValueLastYearMTD/100000,2))
->setCellValue($head_order_data++ . $connew, $row->YTDthisyear)
->setCellValue($head_order_data++ . $connew, round($row->ValueYTDthisyear/100000,2))
->setCellValue($head_order_data++ . $connew, $row->LastYearYTD)
->setCellValue($head_order_data++ . $connew, round($row->ValueLastYearYTD/100000,2))
->setCellValue($head_order_data . $connew, round($Variance,2)." %");
	
	
	$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$head_hotel_row.':'.$head_order_data .$connew)->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('A5:G5')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);


$connew++;				}//end of while
	
	$head_order_data = "C";
	$styleTotal = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));
	$objPHPExcel->setActiveSheetIndex(0)				
		->setCellValue("A". $connew, "Total")
		->setCellValue($head_order_data++ . $connew, $sumThisRn)
		->setCellValue($head_order_data++ . $connew, round($sumThisValue/100000,2))
		->setCellValue($head_order_data++ . $connew, $sumLastRn)
		->setCellValue($head_order_data++ . $connew, round($sumLastValue/100000,2))
		->setCellValue($head_order_data++ . $connew, $sumThisMTDRn)
		->setCellValue($head_order_data++ . $connew, round($sumThisMTDValue/100000,2))
		->setCellValue($head_order_data++ . $connew, $sumLastMTDRn)
		->setCellValue($head_order_data++ . $connew, round($sumLastMTDValue/100000,2))
		->setCellValue($head_order_data++ . $connew, $sumThisYTDRn)
		->setCellValue($head_order_data++ . $connew, round($sumThisYTDValue/100000,2))
		->setCellValue($head_order_data++ . $connew, $row->$sumLastYTDRn)
		->setCellValue($head_order_data++ . $connew++, round($sumLastYTDValue/100000,2));		
		$objPHPExcel->getActiveSheet()->mergeCells("A".($connew-1).":B".($connew-1));
		$objPHPExcel->getActiveSheet()->getStyle("A".($connew-1).":O".($connew-1))->applyFromArray($styleTotal);
	}
	$objPHPExcel->getActiveSheet()->setTitle('FLASH MTD YTD Report');

								
	



	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="flash_report.xls"');
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

//IF SEARCH
if($_POST['Search'] == 'Search'){
	$db->query($sql);
	$numRows= $db->num_rows();
	$total = $db->num_rows();
}
?>





<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<style type="text/css">
	.headRow{
		background-color:#F6B23A;
		color: #252525;
		text-align: center;
	}
	.dateRow{
		background-color: #04D0F9;
		text-align: center;
	}
	.valueHead{
		background-color:  #88CDFD;
		width: 150px;
		text-align: center;
	}
	.roomNight{
		background-color: #5FFAEE;
		text-align: center;
	}
	.roomNightRow{
		background-color: #F6F4F7;
		text-align: center;
	}
	.rnValueRow{
		background-color: #D7D5D8;
		text-align: center;
	}
	.rnValue{
		background-color: #07C1EF;
		text-align: center;
	}
	.total{
		background-color: #61F45E;
		font-size: 3rem;
		padding-left: 65px !important;
		padding-top: 20px !important;
	}
	.totalRN{
		background-color: #5AE0FA;
	}
	.totalValue{
		background-color: #3ABBD4;
	}
	/*jump
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Flash Report Manager
        <small>Flash Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Flash Report Manager</li>
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
          <h3 class="box-title">Flash Report &nbsp;</small></h3>          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="post">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
		    
			<div class="col-md-4">
              <div class="form-group">
                  <label for="start_date">AS On</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="pace_date" name="flash_date" value="<?php if($_POST) echo $_POST['flash_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
              <!-- /.form-group -->
            </div>
	
          <!-- /.row -->
        </div>
		
				
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
       <input name="Search" type="submit" class="btn btn-primary" value="Search" /> <input name="Download" type="submit" class="btn btn-primary" value="Download" />
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
				<tr><th colspan=14 class="dateRow ">Flash Report On : <?php echo dateformat_date($current_date)?></th></tr>
				
				
				<tr>
                  <th rowspan=2 class="text-center headRow" style="padding: 25px;width: 200px;">Hotel Name</th>
				  
				  <th colspan="2" class="text-center valueHead">Today</th>
				  <th colspan="2" class="text-center valueHead">Same Day Last Year</th>
				  
				  <th colspan="2" class="text-center valueHead">MTD This Year</th>
				  <th colspan="2" class="text-center valueHead">MTD Last Year</th>
				  
				  <th colspan="2" class="text-center valueHead">YTD This Year</th>
				  <th colspan="2" class="text-center valueHead">YTD Last Year</th>
				  <th  rowspan=2 class="text-center headRow" style="padding: 15px; width: 50px;">Variance Vs LY </th> 
				</tr>

				<tr>				  
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value(in Lacs)</th>
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value(in Lacs)</th>
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value(in Lacs)</th>	  
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value(in Lacs)</th>
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value(in Lacs)</th>
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value(in Lacs)</th>

				  <th></th>
				</tr>
				<?php
				//jump
				$sumThisValue =0;
				$sumLastValue =0;
				$sumThisMTDValue =0;
				$sumLastMTDValue =0;
				$sumThisYTDValue =0;
				$sumLastYTDValue =0;
				$sumThisRn =0;
				$sumLastRn =0;
				$sumThisMTDRn =0;
				$sumLastMTDRn =0;
				$sumThisYTDRn =0;
				$sumLastYTDRn =0;
				while($row = $db->fetch_object()){
						$sumThisValue +=$row->ValueThisYearConfirmandTend;
						$sumLastValue +=$row->ValueLastYearConfirmandTend;
						$sumThisMTDValue +=$row->ValueMTDthisyear;
						$sumLastMTDValue +=$row->ValueLastYearMTD;
						$sumThisYTDValue +=$row->ValueYTDthisyear;
						$sumLastYTDValue +=$row->ValueLastYearYTD;
						$sumThisRn += $row->ThisYearConfirmandTend;
						$sumLastRn +=$row->LastYearConfirmandTend;
						$sumThisMTDRn += $row->MTDthisyear;
						$sumLastMTDRn +=$row->LastYearMTD;
						$sumThisYTDRn +=$row->YTDthisyear;
						$sumLastYTDRn +=$row->LastYearYTD; 
						if($row->ValueLastYearYTD != 0){				
							$Variance = ((($row->ValueYTDthisyear-$row->ValueLastYearYTD)/$row->ValueLastYearYTD)*100);
						}
						else{
							$Variance = 0;
						}
						?>

							<tr>
								<td><?php echo $row->HotelName;?></td>
                                <td class="roomNightRow"><?php echo $row->ThisYearConfirmandTend; ?></td>
                                <td class="rnValueRow"><?php echo round($row->ValueThisYearConfirmandTend/100000,2); ?></td>
                                <td class="roomNightRow"><?php echo $row->LastYearConfirmandTend; ?></td>
                                <td class="rnValueRow"><?php echo round($row->ValueLastYearConfirmandTend/100000,2); ?></td>
                                <td class="roomNightRow"><?php echo $row->MTDthisyear; ?></td>
                                <td class="rnValueRow"><?php echo round($row->ValueMTDthisyear/100000,2); ?></td>
                                <td class="roomNightRow"><?php echo $row->LastYearMTD; ?></td>
                                <td class="rnValueRow"><?php echo round($row->ValueLastYearMTD/100000,2); ?></td>
                                <td class="roomNightRow"><?php echo $row->YTDthisyear; ?></td>
                                <td class="rnValueRow"><?php echo round($row->ValueYTDthisyear/100000,2); ?></td>
                                <td class="roomNightRow"><?php echo $row->LastYearYTD; ?></td>
                                <td class="rnValueRow"><?php echo round($row->ValueLastYearYTD/100000,2); ?></td>
                                <td><?php echo round($Variance,2)." %"; ?></td>
							</tr>
							

						<?php
				}
				?>
							  
				  

				  				
				<tr>
				<td class="total" rowspan="4">Total</td>
				<tr>
                  
				  <th colspan="2" class="text-center valueHead">Today</th>
				  <th colspan="2" class="text-center valueHead">Same Day Last Year</th>
				  <th colspan="2" class="text-center valueHead">MTD This Year</th>
				  <th colspan="2" class="text-center valueHead">MTD Last Year</th>
				  <th colspan="2" class="text-center valueHead">YTD This Year</th>
				  <th colspan="2" class="text-center valueHead">YTD Last Year</th>
				   <th rowspan="3"></th>
				   
				</tr>
				<th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value (in Lacs)</th>
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value (in Lacs)</th>
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value (in Lacs)</th>	  
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value (in Lacs)</th>
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value (in Lacs)</th>
				  <th class="text-center roomNight">R N</th>
				  <th class="text-center rnValue">Value (in Lacs)</th>

				</tr>
				<tr>
				  <th class="text-center roomNight"><?php echo $sumThisRn; ?></th>		
				  <th class="text-center rnValue"><?php echo round($sumThisValue/10000,2); ?></th>
				  <th class="text-center roomNight"><?php echo $sumLastRn; ?></th>
				  <th class="text-center rnValue"><?php echo round($sumLastValue/100000,2); ?></th>
				  <th class="text-center roomNight"><?php echo $sumThisMTDRn; ?></th>
				  <th class="text-center rnValue"><?php echo round($sumThisMTDValue/100000,2);?></th>	  
				  <th class="text-center roomNight"><?php echo $sumLastMTDRn; ?></th>
				  <th class="text-center rnValue"><?php echo round($sumLastMTDValue/100000,2);?></th>
				  <th class="text-center roomNight"><?php echo $sumThisYTDRn; ?></th>
				  <th class="text-center rnValue"><?php echo round($sumThisYTDValue/100000,2);?></th>
				  <th class="text-center roomNight"><?php echo $sumLastYTDRn; ?></th>
				  <th class="text-center rnValue"><?php echo round($sumLastYTDValue/100000,2);?></th>

				</tr>	
				<?php
				
				?>
			   
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
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

<?php include_once("includes/footer.php")?>