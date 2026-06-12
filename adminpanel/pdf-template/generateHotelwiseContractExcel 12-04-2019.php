<?php 
	include_once("../../config/auto_loader.php"); 
	$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

	$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");

	$rowShop = $db->fetch_object2($resShop);

	$logo	=	$rowShop->image;

 	$NewHotel_id	= $_REQUEST['id'];

	$weekEndAvailable = selectColumn(TBL_HOTELS,'excel_display_weekday','WHERE id_shop="'.$_SESSION['shop'].'" AND id="'.$NewHotel_id.'" ');

	$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");

	$rowShop = $db->fetch_object2($resShop);


	if($_REQUEST['company_id'] != ''){

		$cond .= " AND `".TBL_RATE."`.`company_id` = '".addslashes($_REQUEST['company_id'])."' ";
	}
	if($_REQUEST['session'] != '' && $_REQUEST['session'] !='null' && $_REQUEST['session'] !='0' ){	
		$session = $_REQUEST['session'];		
		$cond .= " AND `".TBL_RATE."`.`seasonId` IN  (".addslashes($session).")";
	}



	if($NewHotel_id != ''){		
		//$hotel_ids = implode(',',$_REQUEST['hotelId']);		
		if($NewHotel_id!=''){
			$cond .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` in ('".$NewHotel_id."')";
			$condHotel = " AND `".TBL_HOTELS."`.`id` in ('".$NewHotel_id."')  ";
		}else{			

			if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 
			 $cond .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` IN  (".addslashes($_SESSION['HotelUserPermission']).")";

			 $condHotel = " AND  `".TBL_HOTELS."`.`id` in (".$_SESSION['HotelUserPermission'].")  ";

			}

		}
	}else{

		if($_SESSION['HotelUserPermission'] != ''){//
			$cond .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` IN  (".addslashes($_SESSION['HotelUserPermission']).")";
			$condHotel = " AND `".TBL_HOTELS."`.`id` in (".addslashes($_SESSION['HotelUserPermission']).")";
		}
	}

	



	error_reporting(1);

	$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Booking Report")
								 ->setSubject("Booking Report")
								 ->setDescription("Booking Report")
								 ->setKeywords("Booking Report")
								 ->setCategory("Report");




	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Paid');
	$objDrawing->setDescription('Paid');
	$objDrawing->setPath('../../uploaded_files/shop/'.$logo);
	$objDrawing->setCoordinates('F2');
	$objDrawing->setOffsetX(0);
	$objDrawing->setRotation(0);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(0);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

    function cellColor($cells,$color){
    	global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
    			)	
    	));
	}







	cellColor('A7:N7','585858');
	$objPHPExcel->getActiveSheet()->getStyle('A7:N7')->getFont()->setBold(true)
                                ->setName('Calibri')
                                ->setSize(14)
                                ->getColor()->setRGB('ffffff');

	// Add some data



	$head_cntr = "C";
	$setcellcount	=8;
	$HotesCount=$setcellcount;
	$Comy	=	$setcellcount;
	$Comy++;
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A7', "HOTELWISE CONTRACTED RATES");
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:N7');



 $styleThinBlackBorderOutline = array(
	'borders' => array(
	'outline' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN,
	'color' => array('argb' => '000'),
	),
	),
 );







$objPHPExcel->getActiveSheet()->getStyle('A7:N7')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



	);
							

$con=$setcellcount;



$HotelSql =  executeSQl("SELECT * FROM `".TBL_HOTELS."` WHERE `".TBL_HOTELS."`.`id_shop` = '".addslashes($_SESSION['shop'])."'  ".$condHotel." order by ".TBL_HOTELS.".id asc");



//while($HotelRecords=	$db->fetch_object2($HotelSql)){

	$HotelRecords=	$db->fetch_object2($HotelSql);	

	$rateSql="SELECT `".TBL_RATE_DETAILS."`.*,`".TBL_RATE."`.*, `".TBL_RATE."`.id  as detail_id FROM `".TBL_RATE_DETAILS."` join `".TBL_RATE."` on fs_rate.id=fs_rate_details.rate_id  LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE.".company_id  WHERE 1=1 AND FIND_IN_SET(cmp.area,'".$_SESSION['teamMemberAreas']."') ".$cond."  group by ".TBL_RATE.".id  order by ".TBL_RATE.".seasonId ,cmp.name asc";	

	

$RatecheckCountSql =  executeSQl($rateSql);

$HoteNumValue	=	$db->num_rows2($RatecheckCountSql);



if($HoteNumValue>0){

	$fetchHotelName = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$HotelRecords->id."'");
$fetchHotelCity =selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$HotelRecords->id."'");

	$objPHPExcel->setActiveSheetIndex(0)


->setCellValue('A'.$con, strtoupper($fetchHotelName).','.strtoupper($fetchHotelCity));



$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':N'.$con);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getAlignment()->applyFromArray(
		array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->getFont()->setBold(true)
        ->setName('Calibri')
        ->setSize(14)
        ->getColor()->setRGB('ffffff');

	//cellColor('A8:K8','254061');

	cellColor('A'.$con.':N'.$con,'585858');		

	

	$con++;	
	if($weekEndAvailable==1){
		$singleTxt="Weekday (Single/Double)";
		$doubleTxt="Weekend (Single/Double)";
		$wrapTxt = true;
	}	
	else{
		$singleTxt="Single";
		$doubleTxt="Double";
		$wrapTxt = false;
	}

	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'S:No.')
			->setCellValue('B'.$con, 'Agent Name')
			->setCellValue('C'.$con, 'Market')
			->setCellValue('D'.$con, 'Season')
			->setCellValue('E'.$con, 'Room Category')
			->setCellValue('F'.$con, 'Plan')				
			->setCellValue('G'.$con, $singleTxt)				
			->setCellValue('H'.$con, $doubleTxt)
			->setCellValue('I'.$con, 'Extra Bed')				
			->setCellValue('J'.$con, 'Lunch')
			->setCellValue('K'.$con, 'Dinner')
			->setCellValue('M'.$con, 'Sales Person')
			->setCellValue('L'.$con, 'Creadibiltiy')
			->setCellValue('N'.$con, 'Remarks');

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->getFont()->setBold(true);	



$InCount=0;

$InCount2=0;

$con++;

while($RatecheckCountRecords=	$db->fetch_object2($RatecheckCountSql)){

	

$Company1	=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");

if($Company1!=''){
	$id_state = selectColumn(TBL_COMPANY,'id_state'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");
	$stateName = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$id_state."'");

	$Company=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'").','.$stateName;

	$id_default_group=selectColumn(TBL_COMPANY,'id_default_group'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");

	$CompanyDefaultGroupType=selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$id_default_group."'");
	
		
		
			
		


}if($Company1==''){



$Company= selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$RatecheckCountRecords->rate_level_id."'");





$CompanyDefaultGroupType	='-';



}		

$Market	=	selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$RatecheckCountRecords->market."'");

$seasonName	=	selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RatecheckCountRecords->seasonId."'");

$RateName	=	$RatecheckCountRecords->rate_name;

$rateLevelName	=	selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$RatecheckCountRecords->rate_level_id."'");

$Date	=	dateformat_date($RatecheckCountRecords->date_created);	

		

$RateFetchSql ="SELECT DISTINCT `".TBL_RATE_DETAILS."`.* FROM `".TBL_RATE_DETAILS."` LEFT JOIN `".TBL_ASSIGN_HOTEL_ROOM."` ON `".TBL_RATE_DETAILS."`.room_id = `".TBL_ASSIGN_HOTEL_ROOM."`.room_id  WHERE  fs_rate_details.rate_id=".$RatecheckCountRecords->id."   and `".TBL_RATE_DETAILS."`.`hotel_id` in (".$HotelRecords->id.") order by `".TBL_ASSIGN_HOTEL_ROOM."`.display_order asc";	


$RateSql 	=  executeSQl($RateFetchSql);

//$RateRecords=	$db->fetch_object2($RateSql);

	

$CountRateDetails	=	$db->num_rows2($RateSql);



$InCount++;

$InCount2++;

$RowCount	=	$con;

cellColor('A'.$RowCount.':N'.$RowCount,'c6c6c6');

$area = selectColumn(TBL_COMPANY,'area'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");
$user = selectColumn(TBL_AREAS,'user_id'," WHERE `id` = '".$area."'");

$userName = selectColumn(TBL_USERS,'name'," WHERE `id` = '".$user."'");

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$con,ucwords($userName));

$creadibiltiy =selectColumn(TBL_COMPANY,'company_credibility'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");


		if($creadibiltiy==1){
			$credTxt = 'Creadit Allowed';
		}
		else if($creadibiltiy==2){
			$credTxt = 'Advance & Direct Payment';
		}
		else{
			$credTxt = "";
		}

$remarks = selectColumn(TBL_RATE_DETAILS,'hotel_remarks','WHERE rate_id="'.$RatecheckCountRecords->rate_id.'" ');	

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$con,$credTxt);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$con,$remarks);

while($RateDetailsRecords=	$db->fetch_object2($RateSql)){

		

	

		$objPHPExcel->setActiveSheetIndex(0)



->setCellValue('A'.$con, $InCount++)



->setCellValue('B'.$con, $Company);




//->setCellValue('C'.$con, $CompanyDefaultGroupType)



//->setCellValue('D'.$con, $Date)

if($weekEndAvailable==1){

	$singlePriceTxt=($RateDetailsRecords->single_pax_price!=0?$RateDetailsRecords->single_pax_price :'Rate on Request').'/'.($RateDetailsRecords->double_pax_price!=0?$RateDetailsRecords->double_pax_price:'Rate on Request');

	$doublePrixeTxt=($RateDetailsRecords->weekend_single_pax_price!=0?$RateDetailsRecords->weekend_single_pax_price :'Rate on Request').'/'.($RateDetailsRecords->weekend_double_pax_price!=0?$RateDetailsRecords->weekend_double_pax_price:'Rate on Request');
}	
else{
	$singlePriceTxt=($RateDetailsRecords->single_pax_price!=0?$RateDetailsRecords->single_pax_price :'Rate on Request');
	$doublePrixeTxt=($RateDetailsRecords->double_pax_price!=0?$RateDetailsRecords->double_pax_price:'Rate on Request');
}

$objPHPExcel->setActiveSheetIndex(0)

->setCellValue('C'.$con, $Market)
->setCellValue('D'.$con, $seasonName)
->setCellValue('E'.$con, selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$RateDetailsRecords->room_id."'"))
->setCellValue('F'.$con, selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = '".$RateDetailsRecords->rate_plan_id."'"))
->setCellValue('G'.$con,$singlePriceTxt)
->setCellValue('H'.$con,$doublePrixeTxt)
->setCellValue('I'.$con, ($RateDetailsRecords->extra_bed_price!=0?$RateDetailsRecords->extra_bed_price:'Rate on Request'))



//->setCellValue('N'.$con, $RateDetailsRecords->breakfast_price)



->setCellValue('J'.$con, ($RateDetailsRecords->lunch_price!=0?$RateDetailsRecords->lunch_price:'Rate on Request'))



->setCellValue('K'.$con, ($RateDetailsRecords->dinner_price!=0?$RateDetailsRecords->dinner_price:'Rate on Request'));

//->setCellValue('M'.$con, selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$row->market."'"));

$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(8);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(8);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(8);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(8);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getRowDimension(20)->setRowHeight(-1); 


$objPHPExcel->getActiveSheet()->getStyle('A10:N10')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('G9')->getAlignment()->setWrapText($wrapTxt);
$objPHPExcel->getActiveSheet()->getStyle('H9')->getAlignment()->setWrapText($wrapTxt);
$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true); 

$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true); 

$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setWrapText(true); 

$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true); 

$objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('E9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('F9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('G9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('H9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('I9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('J9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('K9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('L9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('N9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getRowDimension(20)->setRowHeight(-1); 

$objPHPExcel->getActiveSheet()->getStyle('C8')->getAlignment()->setWrapText(true);             

$objPHPExcel->getActiveSheet()->getRowDimension(20)->setRowHeight(-1); 

$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);             

$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('B'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('C'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('D'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('E'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('F'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('G'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('H'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('I'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('J'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('K'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('L'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('M'.$con)->applyFromArray($styleThinBlackBorderOutline);
									

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->applyFromArray($styleThinBlackBorderOutline);



$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->getAlignment()->applyFromArray(



array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)



);

		$con++;
		$InCount	='';
		$Company	='';
		$Date		='';
		$Market		='';
		$RateName	='';
		$seasonName	='';
		$rateLevelName	='';
		$CompanyDefaultGroupType	='';
		$RowCount='';

	}

		//$RowCount=$con;

	$InCount	=$InCount2;
}

	


}//num

	
	/*paste here*/
	$con++;
	$seasonSql = "SELECT * FROM ".TBL_RATE_SEASON." WHERE id IN (".$_REQUEST['session'].") ORDER BY id";
	$boldCon =$con;
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$con, 'Note : ');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, 'Season Description');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, 'From');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con, 'To');
	
	cellColor('A'.$boldCon.':D'.$con,'c6c6c6');
	$con++;

	if($_REQUEST['session']=='65,66'){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, 'SUMMER 2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, '01-Apr-2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con++, '30-Sep-2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, 'WINTER 2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, '01-Oct-2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con++, '31-Mar-2020');
		//$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->applyFromArray($styleThinBlackBorderOutline);
	
	}
	else if($_REQUEST['session']=='65,66,67'){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, 'SUMMER 2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, '01-Apr-2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con++, '30-Sep-2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, 'WINTER 2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, '01-Oct-2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con++, '31-Mar-2020');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, 'YEAR 2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, '01-Jan-2019');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con++, '31-Dec-2020');
		//$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->applyFromArray($styleThinBlackBorderOutline);
		
	}
	else{
		$resSea = mysqli_query($conn,$seasonSql);
		while($rowSea = mysqli_fetch_assoc($resSea)){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, $rowSea['name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, date('d-M-Y',strtotime($rowSea['start_date'])));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con, date('d-M-Y',strtotime($rowSea['end_date'])));
			$con++;
		}

		//$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->getFont()->setBold(true);	
		$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->applyFromArray($styleThinBlackBorderOutline);
	}	
	/*paste here*/


//} //Hotel Sql End

$col2	=	$con-1;	
$objPHPExcel->getActiveSheet()->getStyle('A8:N8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A9:N9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A8:N8')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);			

$objPHPExcel->getActiveSheet()->getStyle('A'.$setcellcount.':A'.$col2)->getAlignment()->applyFromArray(
	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('L9')->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);
$objPHPExcel->getActiveSheet()->getStyle('M9')->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);
$objPHPExcel->getActiveSheet()->getStyle('N9')->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);







$objPHPExcel->getActiveSheet()->getStyle('C'.$setcellcount.':C'.$col2)->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);



$objPHPExcel->getActiveSheet()->getStyle('D'.$setcellcount.':D'.$col2)->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);





$objPHPExcel->getActiveSheet()->getStyle('F'.$setcellcount.':F'.$col2)->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);







$objPHPExcel->getActiveSheet()->getStyle('G'.$setcellcount.':G'.$col2)->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);



$objPHPExcel->getActiveSheet()->getStyle('H'.$setcellcount.':H'.$col2)->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);







$objPHPExcel->getActiveSheet()->getStyle('I'.$setcellcount.':I'.$col2)->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);



$objPHPExcel->getActiveSheet()->getStyle('J'.$setcellcount.':J'.$col2)->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);



$objPHPExcel->getActiveSheet()->getStyle('K'.$setcellcount.':K'.$col2)->getAlignment()->applyFromArray(



array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



);







	







	



			/*$objPHPExcel->getActiveSheet()->getStyle('A'.$setcellcount.':M'.$col2)->getFill()

    			

    			->getStartColor()->setARGB('FFFF0000');*/



	



				$setcellcount	=	$con;




	// Rename worksheet



	$objPHPExcel->getActiveSheet()->setTitle('Hotel Wise Contract Report');



$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);



$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);



$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);



$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);



$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);



$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);







$objPHPExcel->getActiveSheet()



    ->getPageMargins()->setTop(0.25);



$objPHPExcel->getActiveSheet()



    ->getPageMargins()->setRight(0.25);



$objPHPExcel->getActiveSheet()



    ->getPageMargins()->setLeft(0.25);



$objPHPExcel->getActiveSheet()



    ->getPageMargins()->setBottom(0.25);







	// Set active sheet index to the first sheet, so Excel opens this as the first sheet



	$objPHPExcel->setActiveSheetIndex(0);





$objPHPExcel->getActiveSheet()

    ->getPageSetup()

    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

$objPHPExcel->getActiveSheet()

    ->getPageSetup()

    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	ob_end_clean();



	$HotelName=	str_replace(' ', '_',selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$HotelRecords->id."'")).'_'.selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$HotelRecords->id."'").'_'.date('Y-m-d');

		

	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$HotelName.'".xls"');
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
?>
