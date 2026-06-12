<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');
//$_REQUEST['id']='T1czSndXdzVuL3NyM0hMNjhBQmxIQT09';
	$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));
	

	
	

	error_reporting(1);
	$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
	$rowShop = $db->fetch_object2($resShop);

		




    $objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Paid');
	$objDrawing->setDescription('Paid');
	$objDrawing->setPath('../uploaded_files/shop/'.$rowShop->image);
	$objDrawing->setCoordinates('A1');
	$objDrawing->setOffsetX(0);
	$objDrawing->setRotation(0);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(0);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

	
	 $objDrawing1 = new PHPExcel_Worksheet_Drawing();
	$objDrawing1->setName('Paid');
	$objDrawing1->setDescription('Paid');
	$objDrawing1->setPath('../uploaded_files/shop/'.$rowShop->image_logo2);
	$objDrawing1->setCoordinates('C1');
	$objDrawing1->setOffsetX(0);
	$objDrawing1->setRotation(0);
	$objDrawing1->getShadow()->setVisible(true);
	$objDrawing1->getShadow()->setDirection(0);
	$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet());	
		

	 $objDrawing2 = new PHPExcel_Worksheet_Drawing();
	$objDrawing2->setName('Paid');
	$objDrawing2->setDescription('Paid');
	$objDrawing2->setPath('../uploaded_files/shop/'.$rowShop->image_logo3);
	$objDrawing2->setCoordinates('F1');
	$objDrawing2->setOffsetX(0);
	$objDrawing2->setRotation(0);
	$objDrawing2->getShadow()->setVisible(true);
	$objDrawing2->getShadow()->setDirection(0);
	$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());		
			
	//==============================================================================================		
			
$stateCountSql =  executeSQl("SELECT *  FROM `".TBL_HOTELS."` WHERE status='1'  group by state");
while($resstateCountSql=	$db->fetch_object2($stateCountSql)){
$CountrowRoomCount[] = $resstateCountSql->id;
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
$objPHPExcel->getActiveSheet()->getStyle("A8:J8")->getFont()->setBold(true)
                                ->setName('Calibri')
                                ->setSize(9)
                                ->getColor()->setRGB('FFFFF');
$objPHPExcel->getActiveSheet()->getStyle("A7:J7")->getFont()->setBold(true)
                                ->setName('Calibri')
                                ->setSize(9)
                                ->getColor()->setRGB('FFFFF');
								
$objPHPExcel->getActiveSheet()->getStyle("A6:J6")->getFont()->setBold(true)
                                ->setName('Calibri')
                                ->setSize(9)
                                ->getColor()->setRGB('FFFFF');	
$objPHPExcel->getActiveSheet()->getStyle("A9:J9")->getFont()->setBold(true)
                                ->setName('Calibri')
                                ->setSize(9)
                                ->getColor()->setRGB('FFFFF');																								



cellColor('A8:G8', '4f6228');
cellColor('A7:G7', '4f6228');
cellColor('A9:G9','4f6228');
cellColor('A6:G6','254061');
						
	$objPHPExcel->setActiveSheetIndex(0)
					//->setCellValue('A9', 'CITY')	
					->setCellValue('A9', 'HOTEL/RESORT')	
					//->setCellValue('C9', 'ROOMS')	
					->setCellValue('B9', 'ROOM  CATEGORY')	
					->setCellValue('C9', 'PLAN')	   
					->setCellValue('D9', 'SINGLE')	
					->setCellValue('E9', 'DOUBLE')	
					->setCellValue('F9', 'EXTRA BED')
					//->setCellValue('I9', 'INCLUSIONS')
					->setCellValue('G9', 'MEALS/TAXES');	
					
					$objPHPExcel->getActiveSheet(1)->getStyle('A9:G9')->getFont()->setBold(true);
					
					
						   		
$setcellcount=10;

$resHotelDetail =  executeSQl("SELECT * FROM `".TBL_HOTELS."` where status='1'  ORDER BY `state`");


$resTitleSQL = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id!='0' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."'") ;
$RateTitle = $db->fetch_object2($resTitleSQL);





$StartDate	=	selectColumn(TBL_RATE,'start_date'," WHERE `rate_name` = '".$RateTitle->rate_name."'");
$EndDate	=	selectColumn(TBL_RATE,'end_date'," WHERE `rate_name` = '".$RateTitle->rate_name."'");
if($RateTitle->company_id==0){
						   
						  $Agent	=	 'Template Rate';
						   }else {
							    $Agent	=	selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RateTitle->company_id."'");
							   
							    }

$objPHPExcel->setActiveSheetIndex(0)	
			->setCellValue('A6',$rowShop->name.'-'.selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$RateTitle->market."'").'-'.selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RateTitle->seasonId."'"))
			->setCellValue('A7','TRAVEL AGENCY NAME:'.$Agent)
			->setCellValue('F7','RATES VALIDITY:'.dateformat_date($StartDate).'-'.dateformat_date($EndDate))
			->setCellValue('I7','Ref:'.$RateTitle->rate_name.' Date:'.date("d-m-Y"));


 $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A6:G6');
 
 $styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'fff'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('A9:G9')->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('A9:G9')->getAlignment()->applyFromArray(
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



 $objPHPExcel->setActiveSheetIndex(0)	
			->setCellValue('A8','RATES ISSUED TO:')
			->setCellValue('F8','RATES ISSUED BY:'.selectColumn(TBL_USERS,'name'," WHERE `id` = '".$RateTitle->last_modified_by."'"));
			
			

			while($resultHotelDetail = $db->fetch_object2($resHotelDetail)){ 
			
			  $resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id='".addslashes($resultHotelDetail->id)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."'") ;
		while($RateHotelDetail = $db->fetch_object2($resCat_rooms)){
			 
 $CountroomSql =  executeSQl("SELECT sum(inventory) as roomcount FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE  hotel_id= '".$resultHotelDetail->id."'");
	 $rowRoomCount = $db->fetch_object2($CountroomSql);
	 
	 
	$objPHPExcel->getActiveSheet()->getStyle('A10:A100')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('A10:A100')->getAlignment()
		->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	
	$objPHPExcel->getActiveSheet()->getStyle('B10:B100')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B10:B100')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

	$objPHPExcel->getActiveSheet()->getStyle('C10:C100')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('C10:C100')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);


	$objPHPExcel->getActiveSheet()->getStyle('D10:D100')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('D10:D100')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	
		
	$objPHPExcel->getActiveSheet()->getStyle('E10:E100')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('E10:E100')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	
	$objPHPExcel->getActiveSheet()->getStyle('F10:F100')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('F10:F100')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	
	$objPHPExcel->getActiveSheet()->getStyle('G10:G100')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('G10:G100')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	

	
	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(35);


$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);

$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(35);

  
	 
			 $objPHPExcel->setActiveSheetIndex(0)	
			 ->setCellValue('A' . $setcellcount, $resultHotelDetail->city.'-'.$resultHotelDetail->name."\n ".$resultHotelDetail->special_notes
				."\n Address: ".$resultHotelDetail->address."\n Rooms: ".$rowRoomCount->roomcount );
			//->setCellValue('B' . $setcellcount, $resultHotelDetail->city.','.$resultHotelDetail->address)
			
			
			//->setCellValue('C' . $setcellcount, $rowRoomCount->roomcount);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$setcellcount)->getAlignment()->setWrapText(true);
	 //$objPHPExcel->getActiveSheet(1)->getRowDimension(1)->setRowHeight(-1);		
			
			$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1); 
$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->getRowDimension(20)->setRowHeight(-1); 
$objPHPExcel->getActiveSheet()->getStyle('D8')->getAlignment()->setWrapText(true);



			$resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id='".addslashes($resultHotelDetail->id)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."'");

  
$con=$setcellcount;
$NumValue	=	$db->num_rows2($resCat_rooms);
while($rowInclusion = $db->fetch_object2($resCat_rooms)){


			
			
			$taxExtra	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = ".$rowInclusion->rate_plan_id);
			 //$content .= $taxExtra;
			 if($taxExtra	=='2'){
			 $t	='+Taxes';			 
			 }else{
			 $t	='';

			 }
if($rowInclusion->breakfast_price>0){
$BreakFast	=	' B : INR '.$rowInclusion->breakfast_price.'|';
}
if($rowInclusion->lunch_price>0){
$lunch	=	'| L : INR '.$rowInclusion->lunch_price;
}

if($rowInclusion->dinner_price>0){
$Dinner	=	'| D : INR '.$rowInclusion->dinner_price;
}
if($rowInclusion->single_pax_price>0){
	
	$single_pax_price	=	'INR '.$rowInclusion->single_pax_price.' '.$t;
	}else{
	$single_pax_price	=	"Rates available on request";
		}
if($rowInclusion->double_pax_price>0){
	
	$double_pax_price	=	'INR '.$rowInclusion->double_pax_price.' '.$t;
	}else{
	$double_pax_price	=	"Rates available on request";
		}
if($rowInclusion->extra_bed_price>0){
	
	$extra_bed_price	=	'INR '.$rowInclusion->extra_bed_price.' '.$t;
	}else{
	$extra_bed_price	=	"Rates available on request";
		}		

			 $objPHPExcel->setActiveSheetIndex(0)	
			->setCellValue('B' . $con, selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = ".$rowInclusion->room_id))
			->setCellValue('C' . $con, selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = ".$rowInclusion->rate_plan_id))
			->setCellValue('D' . $con, $single_pax_price)
			->setCellValue('E' . $con, $double_pax_price)
			->setCellValue('F' . $con, $extra_bed_price)			
			//->setCellValue('G' . $con, selectColumn(TBL_RATE_PLAN,'remarks'," WHERE `id` = ".$rowInclusion->rate_plan_id))
			->setCellValue('G' . $con, $BreakFast.$lunch.$Dinner." \n GST will be levied extra, as applicable");			
			$objPHPExcel->getActiveSheet()->getStyle('J'.$con)->getAlignment()->setWrapText(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('D'.$con.':D'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$con.':E'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$con.':F'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$con.':G'.$con)->applyFromArray($styleThinBlackBorderOutline);
			//$objPHPExcel->getActiveSheet()->getStyle('H'.$con.':H'.$con)->applyFromArray($styleThinBlackBorderOutline);
			//$objPHPExcel->getActiveSheet()->getStyle('J'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
			//$objPHPExcel->getActiveSheet()->getStyle('I'.$con.':I'.$con)->applyFromArray($styleThinBlackBorderOutline);
			
						
			
			
			$con++;
							}
												
		 $col2	=	$con-1;										
	 
	 $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$setcellcount.':A'.$col2);
	 //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$setcellcount.':B'.$col2);
 	 //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$setcellcount.':C'.$col2);
	 $objPHPExcel->getActiveSheet()->getStyle('A9:G9')->applyFromArray($styleThinBlackBorderOutline);
	 
$objPHPExcel->getActiveSheet()->getStyle('A7:C7')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('D7:G7')->applyFromArray($styleThinBlackBorderOutline);
//$objPHPExcel->getActiveSheet()->getStyle('H7:I7')->applyFromArray($styleThinBlackBorderOutline);


$objPHPExcel->getActiveSheet()->getStyle('A8:E8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('F8:G8')->applyFromArray($styleThinBlackBorderOutline);

	 
$objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('E9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('F9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('G9')->applyFromArray($styleThinBlackBorderOutline);

	 

$objPHPExcel->getActiveSheet()->getStyle('A6:G6')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('B'.$setcellcount.':B'.$col2)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('B'.$setcellcount.':B'.$col2)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('C'.$setcellcount.':C'.$col2)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('C'.$setcellcount.':C'.$col2)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('D'.$setcellcount.':D'.$col2)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('D'.$setcellcount.':D'.$col2)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('E'.$setcellcount.':E'.$col2)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('E'.$setcellcount.':E'.$col2)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);


$objPHPExcel->getActiveSheet()->getStyle('A'.$setcellcount.':A'.$col2)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A'.$setcellcount.':A'.$col2)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

	 
	
	 $setcellcount	=	$con;
	
	}


}
$resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a  where   a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id='".$Newrate_id."'") ;
		$RateHotelDetail = $db->fetch_object2($resCat_rooms);

// $additional_points	=	$RateHotelDetail->additional_points;




$GeneralTermSql = executeSql("SELECT * FROM `".TBL_GENERAL_TERMS."` where  `id_shop` = '".addslashes($_SESSION['shop'])."' ");
$RowGeneralTermSql = $db->fetch_object2($GeneralTermSql);

$Desc	=	$RowGeneralTermSql->description;
/*$y	=	strip_tags($Desc, "<table><tr><td><b><i><p><span><strong><br>");

 $objPHPExcel->setActiveSheetIndex(0)	
			->setCellValue('A11', $y);
			
$objPHPExcel->getActiveSheet()->setCellValue('A20', $y);*/
//$objPHPExcel->getActiveSheet()->getStyle('A20')->getAlignment()->setWrapText(true);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
$objWriter->setSheetIndex(0);
$objWriter->save('php://output');

/*$wizard = new PHPExcel_Helper_HTML;
$richText = $wizard->toRichTextObject($Desc);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A20', $Desc);

 $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A20:J20');*/

	$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);	
	
		
	$objPHPExcel->getActiveSheet(0)->setTitle('Rate Letter');
//-----End Agent wise sheet

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);

$objPHPExcel->getActiveSheet()
    ->getPageMargins()->setTop(0.50);
$objPHPExcel->getActiveSheet()
    ->getPageMargins()->setRight(0.25);
$objPHPExcel->getActiveSheet()
    ->getPageMargins()->setLeft(0.25);
$objPHPExcel->getActiveSheet()
    ->getPageMargins()->setBottom(1);

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	$type="xls"; 
	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename='.time().".".$type."");
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
 


?><?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<?php include_once("includes/footer.php")?>  