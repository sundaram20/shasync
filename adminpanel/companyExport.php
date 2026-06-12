<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'view');

//---------------Excel Sheet Generator Based on search---------------
if($_REQUEST['Download'] == 'Generate'){
	$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

	error_reporting(1);
	$sql = " SELECT * FROM `".TBL_COMPANY."` WHERE id_shop='".addslashes($_SESSION['shop'])."'  ";

	if($_REQUEST['search_name'] != ''){
		$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
	}
	if($_REQUEST['id_area'] != ''){
		$sql .= " AND `area` = '".addslashes($_REQUEST['id_area'])."'";
	}
	if($_REQUEST['status'] != ''){
		$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."'";
	}
	if($_REQUEST['id_default_group'] != ''){
		$sql .= " AND `id_default_group` = '".addslashes($_REQUEST['id_default_group'])."'";
	}
	if($_REQUEST['id_email'] != ''){
		$sql .= " AND `email` LIKE '%".addslashes($_REQUEST['id_email'])."%' ";
	}
	if($_REQUEST['id_phone'] != ''){
		$sql .= " AND `phone` LIKE '%".addslashes($_REQUEST['id_phone'])."%' ";
	}
	if($_REQUEST['order'] != ''){
		$sql .= " ORDER BY `date_created` DESC";
	}else{
		$sql .= " ORDER BY `date_created` DESC";
	}
	$res = mysqli_query($conn,$sql);
	if($res){
		$numRows = mysqli_num_rows($res);
	}
	
	
	

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")
								 ->setLastModifiedBy("Hitesh Aloney")
								 ->setTitle("Company Export")
								 ->setSubject("Company Export")
								 ->setDescription("Company Export")
								 ->setKeywords("Company Export")
								 ->setCategory("Report");
	
	if($numRows > 0){
	$counter = 1;
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A2', 'Company Report');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
	$head_hotel_row = 3;
	$head_cntr_column = "A";$head_hotel_column = "A";
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
		->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')
		->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Group')
		->setCellValue($head_cntr_column++.$head_hotel_row, 'Area')
		->setCellValue($head_cntr_column++.$head_hotel_row, 'Status');

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

$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($styleArray_1);
 $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(
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
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '000'),
		),
	),
);	



	
$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(33);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(18);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(18);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(18);	


$head_hotel_row++;
					
					
						
	
	
	$Serialno=1;
	$connew = 4;
	while($row = mysqli_fetch_object($res)){
	
	echo "<br>";
	print_r($row);
	
	$head_order_data1 = "A";
	$head_order_data = "A"; 
	$statusTxt ='';

	if($row->status==1)
		$statusTxt='Active';
	else
		$statusTxt='Inactive';


	

	
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $connew, $Serialno++)
->setCellValue($head_order_data++ . $connew, $row->name)
->setCellValue($head_order_data++ . $connew, selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$row->id_default_group."'"))
->setCellValue($head_order_data++ . $connew, selectColumn(TBL_AREAS,'name'," WHERE `id` = '".$row->area."'"))
->setCellValue($head_order_data++ . $connew, $statusTxt);
	
$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$head_hotel_row.':E' .$connew)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($styleThinBlackBorderOutline);


$connew++;	
	}
	$forTotal = 'C';
	$totalArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 12,
        'name'  => 'Verdana'
    ));
		
	$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':E'.$connew)->applyFromArray($totalArray);
				
}//exit;
	$objPHPExcel->getActiveSheet()->setTitle('Company Export');

	
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="company.xls"');
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



//---------------Excel Sheet Generator Based on search End-------------
?>