<?php include_once("../../config/auto_loader.php");



$resShop  	=  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");

$rowShop 	= $db->fetch_object2($resShop);

$logo		=	$rowShop->image;

$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));



	error_reporting(1);

	$sql  =  "SELECT * FROM `".TBL_PROMO_CODE."` as a where  a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id='".$Newrate_id."'";

	





	$db->query($sql);

	$numRows	= $db->num_rows();

	$total 		= $db->num_rows();

	

	$row 		= $db->fetch_object();

	$datawisearrayFinal = array();			

	

	$objPHPExcel->getProperties()->setCreator("Evoucher")

								 ->setLastModifiedBy("Evoucher");



		// Add some data



	

	if($total > 0){

		

		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('A2', 'Evoucher');

		$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');

		//$objPHPExcel->getActiveSheet()->mergeCells('B4:B5');

		//$objPHPExcel->getActiveSheet()->mergeCells('C6:D6');

		//$objPHPExcel->getActiveSheet()->mergeCells('E6:F6');



		$head_hotel_row = 5;		

		$head_hotel_row2 = 5;

		$head_cntr_column2 = "B";

		

		$head_cntr_column = "A";

		$head_hotel_column = "A";		

		

				

		$objPHPExcel->setActiveSheetIndex(0)

		

			->setCellValue(A4, 'S.No.')
			->setCellValue(B4, 'Emp_id')
			->setCellValue(C4, 'Employee Name')

			//->setCellValue(B4, 'Promo Code')

			->setCellValue(D4, 'Url');

		

			



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

 $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->applyFromArray(

    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

);



$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray($styleArray_1);

 $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getAlignment()->applyFromArray(

    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

);



$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

);



$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->applyFromArray(

    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

);

/*$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(

    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

);*/







	

 $styleThinBlackBorderOutline = array(

	'borders' => array(

		'allborders' => array(

			'style' => PHPExcel_Style_Border::BORDER_THIN,

			'color' => array('argb' => '000'),

		),

	),

);	







	



	//$objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setWidth(15);	

	$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setWidth(100);
	$objPHPExcel->getActiveSheet(0)->getColumnDimension('C')->setWidth(25);	

	

			 



	$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getFont()->setBold(true);

	



	$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getAlignment()->applyFromArray(

    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	//$head_hotel_row++;

	

	$connew	=$head_hotel_row;

	

	$promocodeDetailsSQL = executeSql("SELECT * FROM `".TBL_PROMO_CODE_DETAILS."` as a where  a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.promo_code_id='".$row->id."'") ;

	

	$Serialno=1;

	while($promocodeDetailsRecord = $db->fetch_object2($promocodeDetailsSQL)){



	

	$head_order_data1 = "A";

	$head_order_data = "A";       

	$DetailPromoid	=	encryptor('encrypt',$promocodeDetailsRecord->promo_code);

	//jump

	$objPHPExcel->setActiveSheetIndex(0)

	->setCellValue($head_order_data++ . $connew, $promocodeDetailsRecord->serial_no)
	->setCellValue($head_order_data++ . $connew, $promocodeDetailsRecord->emp_id)
	->setCellValue($head_order_data++ . $connew, $promocodeDetailsRecord->emp_title." ".$promocodeDetailsRecord->employee_name)

//	->setCellValue($head_order_data++ . $connew, $promocodeDetailsRecord->promo_code)

	->setCellValue($head_order_data . $connew, 'https://welcomheritagehotels.in/active_promocode.php?pid='.$DetailPromoid);

	

		

	

	$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$head_hotel_row.':'.$head_order_data .$connew)->applyFromArray($styleThinBlackBorderOutline);

	$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray($styleThinBlackBorderOutline);

 	$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getAlignment()->applyFromArray(

    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);





$connew++;			}	}//end of while

	

	$head_order_data = "C";

	$styleTotal = array(

    'font'  => array(

        'bold'  => true,

        'color' => array('rgb' => '1e51bf'),

        'size'  => 12,

        'name'  => 'Verdana'

    ));

	

	$objPHPExcel->getActiveSheet()->setTitle('Evoucher');



	// Set active sheet index to the first sheet, so Excel opens this as the first sheet

	$objPHPExcel->setActiveSheetIndex(0);



	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

	ob_end_clean();

	// Redirect output to a client’s web browser (Excel2007)

	$type=".xls"; 

	header('Content-Type: application/vnd.ms-excel');



	header('Content-Disposition: attachment;filename="'.time().$type.'"');

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

 



	