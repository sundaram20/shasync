<?php
session_start();
$_SESSION['userName'] = "whadmin";
$_SESSION['userEmail'] = "welcomheritage@welcomheritage.com";
$_SESSION['userLevel'] = 1;
$_SESSION['userLastLogin'] = "2018-10-20 12:02:03";
$_SESSION['shop'] = 2;
$_SESSION['userId'] = 10;
$_SESSION['sessionId'] = session_id();
//include_once("../config/auto_loader.php");

$autoDate = date("Y-m-d",strtotime("2018-11-09"));
//$path = getcwd()."/public_html/crs/adminpanel/autoMailerExport/booking.xls";
//mail("support@roomstatushub.com","test page",$path);
/*$_SESSION['userName'] = "whadmin";
$_SESSION['userEmail'] = "welcomheritage@welcomheritage.com";
$_SESSION['userLevel'] = 1;
$_SESSION['userLastLogin'] = "2018-10-20 12:02:03";
$_SESSION['shop'] = 2;
$_SESSION['userId'] = 10;
$_SESSION['sessionId'] = session_id();*/
//$randName = "daily_booking_report.xls";

					
//$randName .= rand(1,2000);	
//echo '<script type="text/javascript">location.href = "dayWise.php?Download=Generate&autoDate='.$autoDate.'&filePath=SET&randName='.$randName.'";</script>';
header("LOCATION:dayWise.php?Download=Generate&autoDate=$autoDate&filePath=SET&randName=$randName");
/*error_reporting(1);
$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")
								 ->setLastModifiedBy("Hitesh Aloney")
								 ->setTitle("Flash Report")
								 ->setSubject("Flash Report")
								 ->setDescription("Flash Report")
								 ->setKeywords("Flash Report")
								 ->setCategory("Report");


/*$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'S.No.');*/
/*$objPHPExcel->getActiveSheet()->setTitle('Flash Report');

	
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="time.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	
	$objWriter->save('autoMailerExport/time.xls');
	//$objPHPExcel->read('autoMailerExport/time.xls');


//echo '<script type="text/javascript">window.open("dayWise.php?Download=Generate&autoDate='.$autoDate.'&filePath=SET&randName='.$randName.'");</script>';
//mail("support@roomstatushub.com","download","download");*/
//include_once("../config/auto_loader.php");


?>