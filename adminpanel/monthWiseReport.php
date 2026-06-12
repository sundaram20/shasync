<?php 
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],month_wise_export,'export');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

// Set document properties
	
	error_reporting(1);
	$hotelCon = "";
	if(isset($_REQUEST['hotel_access']) AND $_REQUEST['hotel_access'] != ""){
		$hotelCon = "AND HID IN (".$_REQUEST['hotel_access'].")";
	}elseif($_SESSION['hotel_access']){
		$hotelCon = "AND HID IN (".$_SESSION['hotel_access'].")";
	}
	$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")
								 ->setLastModifiedBy("Hitesh Aloney")
								 ->setTitle("Month Wise Report")
								 ->setSubject("Month Wise Report")
								 ->setDescription("Month Wise Report")
								 ->setKeywords("Month Wise Report")
								 ->setCategory("Report");
	
	if(isset($_REQUEST['flash_date']) AND $_REQUEST['flash_date'] != ""){
	  $dates = explode(' to ',$_REQUEST['flash_date']);
	  $start = $dates[0];
	  $end = $dates[1];
	  $startMo = date('m',strtotime($start));
	  $endMo = date('m',strtotime($end));
	  $startYr = date('Y',strtotime($start));
	  $endYr = date('Y',strtotime($end));
	  $diff = abs(($endMo-$startMo));
	  $diffYr = ($endYr-$startYr);
	}
	else{

	 $start = date("Y-m-d");
    $end =  date("Y-m-d",strtotime("+5 months", strtotime($start)));
    $startMo = date('m',strtotime($start));
    $endMo = date('m',strtotime($end));
    $startYr = date('Y',strtotime($start));
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);	
	  /*$start = date("Y-m-d");
	  $end =  date("Y-m-d",strtotime("+1 months", strtotime($start)));
	  $startMo = date('m',strtotime($start));
	  if($startMo < 3){
	  	$startMo = 04;
	  	$endMo = 03;
	  }
	  
	  $startYr = date('Y',strtotime("-1 year",$start));
	  $endYr = date('Y',strtotime($end));
	  $diff = abs(($endMo-$startMo));
	  $diffYr = ($endYr-$startYr);*/
	}  
	  
	
	$head_hotel_row = 4;
	$head_cntr_column = "C";
	$head_hotel_column = "A";
	$bug_cntr_column = "C";
	$bug_cntr_columnValue = "C";
	$mer = "C";
	$head_order_data = "A";

	if($diffYr > 0){
	  $diff = abs(($endMo+12-$startMo));
	}
	$sqlGrp = "SELECT Hotel";
	for($i = 0 ; $i <= $diff ; $i++){

		$sqlGrp .= "
			,SUM(CASE WHEN MONTH(Date)=".$startMo." AND YEAR(Date)=".$startYr." AND `DOC_TYPE`='BUDGET' THEN RN ELSE 0 END) AS BUDGETNIGHTS,
			SUM(CASE WHEN MONTH(Date)=".$startMo." AND YEAR(Date)=".$startYr." AND `DOC_TYPE`='ACHEIVED' THEN RN ELSE 0 END) AS ROOMNIGHTS
			 
			 ";
	  
			 $monthName =  DateTime::createFromFormat('!m', $startMo);
	  		$monthName = $monthName->format('F');
			 $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($head_cntr_column++.$head_hotel_row, $monthName."(".$startYr.")");
		if($startMo >= 12 ){
		    $startYr++;
		    $startMo = 0;
		}
	  	  
	  
	  $startMo++;  

	  $objPHPExcel->getActiveSheet()->mergeCells(''.$mer++.'4:'.$mer++.'4');
	 
	  
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($bug_cntr_column++.($head_hotel_row+1), "Budget");	
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($bug_cntr_column.($head_hotel_row+1), "Achieved");

		$head_cntr_column++;
		$bug_cntr_column++;

	}
	// Add some data
	$sqlGrp .="FROM `budget_acheived`
				WHERE shop_id = ".$_SESSION['shop']."  ".$hotelCon."  AND Hotel != '' GROUP BY Hotel";

	$res = mysqli_query($conn,$sqlGrp);
	$rowNum = mysqli_num_rows($res);
	if($res){
		$rowchng = 6;
		$colchng = "A";
		$i=1;
		$j=0;
		$counter=0;	
		while ($rn = mysqli_fetch_row($res)) {
			$counter=0;
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue(''.$colchng++.$rowchng.'', $i);
			for($j=0 ; $j <= count($rn) ;$j++){
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(''.$colchng++.$rowchng.'', $rn[$j]);
					$counter++;

				}	
				
				$rowchng++;
				$i++;
				$colchng = "A";
			}
			$totalStyle = array(
			    'font'  => array(
			        'bold'  => true,
			        'color' => array('rgb' => '1e51bf'),
			        'size'  => 14,
			        'name'  => 'Verdana'
			   ));

			$totalvalueStyle = array(
			    'font'  => array(
			        'bold'  => true,
			        'color' => array('rgb' => '1e51bf'),
			        'size'  => 12,
			        'name'  => 'Verdana'
			   ));

				$objPHPExcel->getActiveSheet()->mergeCells('A'.$rowchng.':B'.$rowchng);
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$rowchng.'', 'TOTAL')
				->getStyle('A'.$rowchng.'')->applyFromArray($totalStyle);
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowchng.':B'.$rowchng)->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
				//****Total LOGIC**//
					$totalCol = "C";

					for($i=3; $i <= $counter;$i++){
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(''.$totalCol.$rowchng.'','=SUM('.$totalCol.'6:'.$totalCol++.($rowchng-1).')');
					}

					$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.$rowchng.':'.$totalCol.$rowchng)->applyFromArray($totalvalueStyle);	
				//****End***//
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', 'Month Wise Report ( Budget VS Achieved)')
					->setCellValue('A3', 'Room Nights')
					->setCellValue('B4', 'Hotel Name')
					->setCellValue('A5', 'S.no');
			
				
				$objPHPExcel->getActiveSheet()->mergeCells('B4:B5');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$mer.'2');
				$objPHPExcel->getActiveSheet()->mergeCells('A3:'.$mer.'3');

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


		$styleArray_2 = array(
		    'font'  => array(
		        'bold'  => true,
		        'color' => array('rgb' => '1e51bf'),
		        'size'  => 12,
		        'name'  => 'Verdana'
		    ));

		 $styleThinBlackBorderOutline = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => '000'),
				),
			),
		);	

		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
		 $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray_2);
		 $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		); 

		$objPHPExcel->getActiveSheet()->getStyle('A4:'.$mer.'4')->applyFromArray($styleArray_1);
		 $objPHPExcel->getActiveSheet()->getStyle('A4:'.$mer.'4')->getAlignment()->applyFromArray(
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


		 

			
		$objPHPExcel->getActiveSheet()->getStyle('A4:'.$mer.'5')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()
		    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(33);					 
			$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getFont()->setBold(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('A5:'.$mer.'5')->getAlignment()->applyFromArray(
		    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getAlignment()->applyFromArray(
		    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);
			$head_hotel_row++;
			
			$objPHPExcel->getActiveSheet()->getStyle('A4:'.$mer.$rowchng)->applyFromArray($styleThinBlackBorderOutline);
	}

	
	




	$objPHPExcel->getActiveSheet()->setTitle('Month Wise Report');

								
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="MonthWiseRoomNights_report.xls"');
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
	mysqli_close($conn);
	exit;
 

?>