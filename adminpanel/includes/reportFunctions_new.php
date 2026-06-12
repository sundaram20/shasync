<?php
	set_time_limit(150000);
	//error_reporting(E_ALL);



	//WARNING : If you don't know how to indent a code kindly learn that first : 



	//Color Cell Function

	function cellColor($cells,$color){

	    global $objPHPExcel;

		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(

	        'type' => PHPExcel_Style_Fill::FILL_SOLID,

	        'startcolor' => array(

	        'rgb' => $color

	    	)	

	    ));

	}

	//Color Cell End



	//Hotel wise contract report

	function hotelWiseContractReportNEW($cron,$id_shopCron,$hotelIdCron,$seasonIdCron,$db,$objPHPExcel){



/*$styleThinBlackBorderlatest = array(

			'borders' => array(

			'outline' => array(

			'style' => PHPExcel_Style_Border::BORDER_THIN,

			'color' => array('argb' => '000'),

			),

			),

		);*/
$styleThinBlackBorderlatest =array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '000'),
		),
	),
);			

		//cellColor('A7:N7','585858');

		//cellColor('A8:N8','585858');

		

		$default_border = array(

    'style' => PHPExcel_Style_Border::BORDER_THIN,

    'color' => array('argb'=>'000')

);

$style_header = array(

    'borders' => array(

        'bottom' => $default_border,

        'left' => $default_border,

        'top' => $default_border,

        'right' => $default_border,

    ),

    'fill' => array(

        'type' => PHPExcel_Style_Fill::FILL_SOLID,

        'color' => array('rgb'=>'585858'),

    ),

    'font' => array(

        'bold' => true,

    )

);

$style_header_border = array(

    'borders' => array(

        'bottom' => $default_border,

        'left' => $default_border,

        'top' => $default_border,

        'right' => $default_border,

    )

);

 

$objPHPExcel->getActiveSheet()->getStyle('A7:N7')->applyFromArray( $style_header );

$objPHPExcel->getActiveSheet()->getStyle('A8:N8')->applyFromArray( $style_header );





		$objPHPExcel->getActiveSheet()->getStyle('A7:N7')->getFont()->setBold(true)

	                                ->setName('Calibri')

	                                ->setSize(14)

	                                ->getColor()->setRGB('ffffff');

		$objPHPExcel->getActiveSheet()->getStyle('A8:N8')->getFont()->setBold(true)

	                                ->setName('Calibri')

	                                ->setSize(14)

	                                ->getColor()->setRGB('ffffff');							

		$cond='';

		$resShop  =  executeSQl("SELECT id,image,name FROM `".TBL_SHOP."` WHERE id= '".addslashes($id_shopCron)."'");

		$rowShop = $db->fetch_object2($resShop);

		$logo	=	$rowShop->image;

	 	$NewHotel_id	= $hotelIdCron;



		$weekEndAvailable = selectColumn(TBL_HOTELS,'excel_display_weekday','WHERE id_shop="'.$id_shopCron.'" AND id="'.$NewHotel_id.'" ');



		//$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($id_shopCron)."'");



		//$rowShop = $db->fetch_object2($resShop);





		

		if($seasonIdCron != '' && $seasonIdCron !='null' && $seasonIdCron !='0' ){	

			$session = $seasonIdCron;		

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

		if(!isset($cron)){

			$signature = '../uploaded_files/shop/'.$logo;

		}

		else{

			$signature = '/var/www/vhosts/roomstatushub.in/httpdocs/sync/uploaded_files/shop/'.$logo;

		}

		if(file_exists($signature)){

			$objDrawing->setName('Paid');

			$objDrawing->setDescription('Paid');

			$objDrawing->setPath($signature);

			$objDrawing->setCoordinates('F2');

			$objDrawing->setOffsetX(0);

			$objDrawing->setRotation(0);

			$objDrawing->getShadow()->setVisible(true);

			$objDrawing->getShadow()->setDirection(0);

			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		}

	    



		





		$head_cntr = "C";

		$setcellcount	=8;

		$HotesCount=$setcellcount;

		$Comy	=	$setcellcount;

		$Comy++;

		

		$objPHPExcel->setActiveSheetIndex(0)

					->setCellValue('A7', "HOTELWISE CONTRACTED RATES");

		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:N7');





					



		$con=$setcellcount;

		$HotelSql =  executeSQl("SELECT id,name,city FROM `".TBL_HOTELS."` WHERE `".TBL_HOTELS."`.`id_shop` = '".addslashes($id_shopCron)."'  ".$condHotel." order by ".TBL_HOTELS.".id asc");



		$HotelRecords=	$db->fetch_object2($HotelSql);	



		if(!isset($cron)){

			 $rateSql="SELECT `".TBL_RATE_DETAILS."`.*,`".TBL_RATE."`.*, `".TBL_RATE."`.id  as detail_id ,cmp.area,cmp.name as companyname,cmp.id_state,cmp.city,cmp.id_default_group,cmp.company_credibility

			FROM `".TBL_RATE_DETAILS."` 

			

			right join `".TBL_RATE."` on `".TBL_RATE."`.id=`".TBL_RATE_DETAILS."`.rate_id  

			

			LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE.".company_id  
			
			LEFT JOIN ".TBL_ASSIGN_HOTEL_ROOM." ON `".TBL_RATE_DETAILS."`.room_id=".TBL_ASSIGN_HOTEL_ROOM.".room_id AND ".TBL_ASSIGN_HOTEL_ROOM.".hotel_id='".$HotelRecords->id."'
			

			WHERE 1=1 AND `".TBL_RATE."`.status=1 ".$cond."  order by ".TBL_RATE.".seasonId ,cmp.name,".TBL_ASSIGN_HOTEL_ROOM.".display_order asc";

				

		}

		else{

			$rateSql="SELECT `".TBL_RATE_DETAILS."`.*,`".TBL_RATE."`.*, `".TBL_RATE."`.id  as detail_id,cmp.area,cmp.name as companyname,cmp.id_state,cmp.city,cmp.id_default_group,cmp.company_credibility 

			FROM `".TBL_RATE_DETAILS."` 

			

			right join `".TBL_RATE."` on `".TBL_RATE."`.id=`".TBL_RATE_DETAILS."`.rate_id

			

			LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE.".company_id  

			LEFT JOIN ".TBL_ASSIGN_HOTEL_ROOM." ON `".TBL_RATE_DETAILS."`.room_id=".TBL_ASSIGN_HOTEL_ROOM.".room_id AND ".TBL_ASSIGN_HOTEL_ROOM.".hotel_id='".$HotelRecords->id."'

			WHERE 1=1 AND `".TBL_RATE."`.status=1 AND ".TBL_RATE.".company_id!=0  ".$cond."  order by ".TBL_RATE.".seasonId ,cmp.name asc";	

		}


		

//SELECT DISTINCT `".TBL_RATE_DETAILS."`.* FROM `".TBL_RATE_DETAILS."` LEFT JOIN `".TBL_ASSIGN_HOTEL_ROOM."` ON `".TBL_RATE_DETAILS."`.room_id = `".TBL_ASSIGN_HOTEL_ROOM."`.room_id  WHERE  fs_rate_details.rate_id=".$RatecheckCountRecords->id."   and `".TBL_RATE_DETAILS."`.`hotel_id` in (".$HotelRecords->id.") order by `".TBL_ASSIGN_HOTEL_ROOM."`.display_order asc





		$RatecheckCountSql =  executeSQl($rateSql);

		$rsoCount=$HoteNumValue	=	$db->num_rows2($RatecheckCountSql);





		if($HoteNumValue>0){

			$fetchHotelName = $HotelRecords->name;

			$fetchHotelCity =	$HotelRecords->city;



			$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue('A'.$con, strtoupper($fetchHotelName).','.strtoupper($fetchHotelCity));

			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':N'.$con);

			

		

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



			$InCount2=0;



			$con++;

			$InCount=1;

			while($RatecheckCountRecords=	$db->fetch_object2($RatecheckCountSql)){

				$Company1	=$RatecheckCountRecords->companyname;



				if($Company1!=''){

					$id_state = $RatecheckCountRecords->id_state;

					$stateName = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$id_state."'");

					$city = $RatecheckCountRecords->city;

					$Company=$RatecheckCountRecords->companyname.','.$city;



					$id_default_group=$RatecheckCountRecords->id_default_group;



					$CompanyDefaultGroupType=selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$id_default_group."'");

				}



				if($Company1==''){

					$Company= selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$RatecheckCountRecords->rate_level_id."'");

					$CompanyDefaultGroupType	='-';

				}		



				$Market	=	selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$RatecheckCountRecords->market."'");



				$seasonName	=	selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RatecheckCountRecords->seasonId."'");



				$RateName	=	$RatecheckCountRecords->rate_name;



				$rateLevelName	=	selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$RatecheckCountRecords->rate_level_id."'");



				

				$InCount2++;

				$RowCount	=	$con;



				//$area = $RatecheckCountRecords->area;

				//$user = selectColumn(TBL_AREAS,'user_id'," WHERE `id` = '".$area."'");

				$userName = selectColumn(TBL_USERS,'name'," WHERE `id` = '".$RatecheckCountRecords->last_modified_by."'");



				



				$creadibiltiy =$RatecheckCountRecords->company_credibility;

				if($creadibiltiy==1){

					$credTxt = 'Creadit Allowed';

				}

				else if($creadibiltiy==2){

					$credTxt = 'Advance & Direct Payment';

				}

				else{

					$credTxt = "";

				}



					



					if($InCount==1){

						$assianCompany	=	'';

						}

		if($Company!=$assianCompany){

					cellColor('A'.$RowCount.':N'.$RowCount,'c6c6c6');

			

					$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A'.$con, $InCount++)

						->setCellValue('B'.$con, $Company)

						->setCellValue('C'.$con, $Market)

						->setCellValue('D'.$con, $seasonName)

						->setCellValue('L'.$con,$credTxt)
						->setCellValue('N'.$con,$RatecheckCountRecords->hotel_remarks)

						->setCellValue('M'.$con,ucwords($userName));

		}else{

				$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A'.$con, '')

						->setCellValue('B'.$con, '')

						->setCellValue('C'.$con, '')

						->setCellValue('D'.$con, '')

						->setCellValue('L'.$con, '')
						->setCellValue('N'.$con,'')

						->setCellValue('M'.$con, '');

						$assianTeamId	=	'';

			}



					if($weekEndAvailable==1){



						$singlePriceTxt=($RatecheckCountRecords->single_pax_price!=0?$RatecheckCountRecords->single_pax_price :'Rate on Request').'/'.($RatecheckCountRecords->double_pax_price!=0?$RatecheckCountRecords->double_pax_price:'Rate on Request');



						$doublePrixeTxt=($RatecheckCountRecords->weekend_single_pax_price!=0?$RatecheckCountRecords->weekend_single_pax_price :'Rate on Request').'/'.($RatecheckCountRecords->weekend_double_pax_price!=0?$RatecheckCountRecords->weekend_double_pax_price:'Rate on Request');

					}	

					else{

						$singlePriceTxt=($RatecheckCountRecords->single_pax_price!=0?$RatecheckCountRecords->single_pax_price :'Rate on Request');

						$doublePrixeTxt=($RatecheckCountRecords->double_pax_price!=0?$RatecheckCountRecords->double_pax_price:'Rate on Request');

					}

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->applyFromArray($styleThinBlackBorderlatest);

/*$objPHPExcel->getActiveSheet()->getStyle('B'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('C'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('D'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('E'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('F'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('G'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('H'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('I'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('J'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('K'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('L'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('M'.$con)->applyFromArray( $style_header_border );

$objPHPExcel->getActiveSheet()->getStyle('N'.$con)->applyFromArray( $style_header_border );*/


$objPHPExcel->setActiveSheetIndex(0)
				

->setCellValue('E'.$con, selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$RatecheckCountRecords->room_id."' "))

->setCellValue('F'.$con, selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = '".$RatecheckCountRecords->rate_plan_id."'"))

->setCellValue('G'.$con,$singlePriceTxt)

->setCellValue('H'.$con,$doublePrixeTxt)

->setCellValue('I'.$con, ($RatecheckCountRecords->extra_bed_price!=0?$RatecheckCountRecords->extra_bed_price:'Rate on Request'))

->setCellValue('J'.$con, ($RatecheckCountRecords->lunch_price!=0?$RatecheckCountRecords->lunch_price:'Rate on Request'))

->setCellValue('K'.$con, ($RatecheckCountRecords->dinner_price!=0?$RatecheckCountRecords->dinner_price:'Rate on Request'))	;









		$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':N'.$con)->getAlignment()->setWrapText(true);             

						$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true); 

					

				

					$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->getAlignment()->applyFromArray(

						array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)

					);







				$assianCompany=$Company;

					$con++;

					//$InCount	='';

					$Company	='';

					$Date		='';  

					$Market		='';

					$RateName	='';

					$seasonName	='';

					$rateLevelName	='';

					$CompanyDefaultGroupType	='';

					$RowCount='';

					

				//}

				//$RowCount=$con;

				//$InCount	=$InCount2;

			}

		}//num





		

//===========Rate UNIT Start===================================================================>			

$cond='';

	

		if($seasonIdCron != '' && $seasonIdCron !='null' && $seasonIdCron !='0' ){	

			$session = $seasonIdCron;		

			$cond .= " AND `".TBL_RATE_UNIT."`.`seasonId` IN  (".addslashes($session).")";

		}







		if($NewHotel_id != ''){		

			//$hotel_ids = implode(',',$_REQUEST['hotelId']);		

			if($NewHotel_id!=''){

				$cond .= " AND `".TBL_RATE_DETAILS_UNIT."`.`hotel_id` in ('".$NewHotel_id."')";

				$condHotel = " AND `".TBL_HOTELS."`.`id` in ('".$NewHotel_id."')  ";

			}else{			



				if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 

				 $cond .= " AND `".TBL_RATE_DETAILS_UNIT."`.`hotel_id` IN  (".addslashes($_SESSION['HotelUserPermission']).")";



				 $condHotel = " AND  `".TBL_HOTELS."`.`id` in (".$_SESSION['HotelUserPermission'].")  ";



				}



			}

		}else{



			if($_SESSION['HotelUserPermission'] != ''){//

				$cond .= " AND `".TBL_RATE_DETAILS_UNIT."`.`hotel_id` IN  (".addslashes($_SESSION['HotelUserPermission']).")";

				$condHotel = " AND `".TBL_HOTELS."`.`id` in (".addslashes($_SESSION['HotelUserPermission']).")";

			}

		}



		

		



		$con=$con+1;

		$HotelSql =  executeSQl("SELECT id,name,city FROM `".TBL_HOTELS."` WHERE `".TBL_HOTELS."`.`id_shop` = '".addslashes($id_shopCron)."'  ".$condHotel." order by ".TBL_HOTELS.".id asc");



		$HotelRecords=	$db->fetch_object2($HotelSql);	



		if(!isset($cron)){

			 $rateSql="SELECT `".TBL_RATE_DETAILS_UNIT."`.*,`".TBL_RATE_UNIT."`.*, `".TBL_RATE_UNIT."`.id  as detail_id ,cmp.area,cmp.name as companyname,cmp.id_state,cmp.city,cmp.id_default_group,cmp.company_credibility

			FROM `".TBL_RATE_DETAILS_UNIT."` 

			

			right join `".TBL_RATE_UNIT."` on `".TBL_RATE_UNIT."`.id=`".TBL_RATE_DETAILS_UNIT."`.rate_id  

			

			LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE_UNIT.".company_id  

			LEFT JOIN ".TBL_ASSIGN_HOTEL_ROOM." ON `".TBL_RATE_DETAILS_UNIT."`.room_id=".TBL_ASSIGN_HOTEL_ROOM.".room_id AND ".TBL_ASSIGN_HOTEL_ROOM.".hotel_id='".$HotelRecords->id."'

			WHERE 1=1 AND `".TBL_RATE_UNIT."`.status=1  ".$cond."  order by ".TBL_RATE_UNIT.".seasonId ,cmp.name asc";

				

		}

		else{

			$rateSql="SELECT `".TBL_RATE_DETAILS_UNIT."`.*,`".TBL_RATE_UNIT."`.*, `".TBL_RATE_UNIT."`.id  as detail_id,cmp.area,cmp.name as companyname,cmp.id_state,cmp.city,cmp.id_default_group,cmp.company_credibility 

			FROM `".TBL_RATE_DETAILS_UNIT."` 

			

			right join `".TBL_RATE_UNIT."` on `".TBL_RATE_UNIT."`.id=`".TBL_RATE_DETAILS_UNIT."`.rate_id

			

			LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE_UNIT.".company_id  

			LEFT JOIN ".TBL_ASSIGN_HOTEL_ROOM." ON `".TBL_RATE_DETAILS_UNIT."`.room_id=".TBL_ASSIGN_HOTEL_ROOM.".room_id AND ".TBL_ASSIGN_HOTEL_ROOM.".hotel_id='".$HotelRecords->id."'

			WHERE 1=1 AND `".TBL_RATE_UNIT."`.status=1 AND ".TBL_RATE_UNIT.".company_id!=0  ".$cond."  order by ".TBL_RATE_UNIT.".seasonId ,cmp.name asc";	

		}




		$RatecheckCountSql =  executeSQl($rateSql);

		$unitCount=$HoteNumValue	=	$db->num_rows2($RatecheckCountSql);





		if($HoteNumValue>0){

			

			

			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':N'.$con);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->applyFromArray($styleThinBlackBorderlatest);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getAlignment()->applyFromArray(

				array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->getFont()->setBold(true)

		        ->setName('Calibri')

		        ->setSize(14)

		        ->getColor()->setRGB('ffffff');



			cellColor('A'.$con.':N'.$con,'585858');		







			$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue('A'.$con++, 'Rate Letter Issued By Unit');

			

			$fetchHotelName = $HotelRecords->name;

			$fetchHotelCity =	$HotelRecords->city;



			$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue('A'.$con, strtoupper($fetchHotelName).','.strtoupper($fetchHotelCity));

			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':N'.$con);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->applyFromArray($styleThinBlackBorderlatest);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getAlignment()->applyFromArray(

				array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->getFont()->setBold(true)

		        ->setName('Calibri')

		        ->setSize(14)

		        ->getColor()->setRGB('ffffff');



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







			$InCount2=0;



			$con++;

			$InCount=1;

			while($RatecheckCountRecords=	$db->fetch_object2($RatecheckCountSql)){

				$Company1	=$RatecheckCountRecords->companyname;



				if($Company1!=''){

					$id_state = $RatecheckCountRecords->id_state;

					$stateName = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$id_state."'");

					$city = $RatecheckCountRecords->city;

					$Company=$RatecheckCountRecords->companyname.','.$city;



					$id_default_group=$RatecheckCountRecords->id_default_group;



					$CompanyDefaultGroupType=selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$id_default_group."'");

				}



				if($Company1==''){

					$Company= selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$RatecheckCountRecords->rate_level_id."'");

					$CompanyDefaultGroupType	='-';

				}		



				$Market	=	selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$RatecheckCountRecords->market."'");



				$seasonName	=	selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RatecheckCountRecords->seasonId."'");



				$RateName	=	$RatecheckCountRecords->rate_name;



				$rateLevelName	=	selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$RatecheckCountRecords->rate_level_id."'");



				$InCount2++;

				$RowCount	=	$con;



				



				//$area = $RatecheckCountRecords->area;

				//$user = selectColumn(TBL_AREAS,'user_id'," WHERE `id` = '".$area."'");

				$userName = selectColumn(TBL_USERS,'name'," WHERE `id` = '".$RatecheckCountRecords->last_modified_by."'");



				



				$creadibiltiy =$RatecheckCountRecords->company_credibility;





				if($creadibiltiy==1){

					$credTxt = 'Creadit Allowed';

				}

				else if($creadibiltiy==2){

					$credTxt = 'Advance & Direct Payment';

				}

				else{

					$credTxt = "";

				}



				$remarks = $RatecheckCountRecords->hotel_remarks;	



				//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$con,$credTxt);

				//22$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$con,$remarks);



				//while($RateDetailsRecords=	$db->fetch_object2($RateSql)){

					if($InCount==1){

						$assianCompany	=	'';

						}

		if($Company!=$assianCompany){

					cellColor('A'.$RowCount.':N'.$RowCount,'c6c6c6');

			

					$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A'.$con, $InCount++)

						->setCellValue('B'.$con, $Company)

						->setCellValue('C'.$con, $Market)

						->setCellValue('D'.$con, $seasonName)
						->setCellValue('N'.$con,$remarks)
						->setCellValue('M'.$con,ucwords($userName))
						->setCellValue('L'.$con,$credTxt);

		}else{

				$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A'.$con, '')

						->setCellValue('B'.$con, '')

						->setCellValue('C'.$con, '')

						->setCellValue('D'.$con, '')
						->setCellValue('N'.$con,'')
						->setCellValue('M'.$con,'')
						->setCellValue('L'.$con,'');

						$assianTeamId	=	'';

			}



					if($weekEndAvailable==1){



						$singlePriceTxt=($RatecheckCountRecords->single_pax_price!=0?$RatecheckCountRecords->single_pax_price :'Rate on Request').'/'.($RatecheckCountRecords->double_pax_price!=0?$RatecheckCountRecords->double_pax_price:'Rate on Request');



						$doublePrixeTxt=($RatecheckCountRecords->weekend_single_pax_price!=0?$RatecheckCountRecords->weekend_single_pax_price :'Rate on Request').'/'.($RatecheckCountRecords->weekend_double_pax_price!=0?$RatecheckCountRecords->weekend_double_pax_price:'Rate on Request');

					}	

					else{

						$singlePriceTxt=($RatecheckCountRecords->single_pax_price!=0?$RatecheckCountRecords->single_pax_price :'Rate on Request');

						$doublePrixeTxt=($RatecheckCountRecords->double_pax_price!=0?$RatecheckCountRecords->double_pax_price:'Rate on Request');

					}



					$objPHPExcel->setActiveSheetIndex(0)



					

	->setCellValue('E'.$con, selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$RatecheckCountRecords->room_id."'"))

	->setCellValue('F'.$con, selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = '".$RatecheckCountRecords->rate_plan_id."'"))

	->setCellValue('G'.$con,$singlePriceTxt)

	->setCellValue('H'.$con,$doublePrixeTxt)

	->setCellValue('I'.$con, ($RatecheckCountRecords->extra_bed_price!=0?$RatecheckCountRecords->extra_bed_price:'Rate on Request'))

	->setCellValue('J'.$con, ($RatecheckCountRecords->lunch_price!=0?$RatecheckCountRecords->lunch_price:'Rate on Request'))

	->setCellValue('K'.$con, ($RatecheckCountRecords->dinner_price!=0?$RatecheckCountRecords->dinner_price:'Rate on Request'));

				

						$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':N'.$con)->getAlignment()->setWrapText(true);             

						$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true); 

					

				

					$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->getAlignment()->applyFromArray(

						array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)

					);





/*$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('B'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('C'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('D'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('E'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('F'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('G'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('H'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('I'.$con)->applyFromArray($styleThinBlackBorderlatest);



$objPHPExcel->getActiveSheet()->getStyle('J'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('K'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('L'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('M'.$con)->applyFromArray($styleThinBlackBorderlatest);

$objPHPExcel->getActiveSheet()->getStyle('N'.$con)->applyFromArray($styleThinBlackBorderlatest);*/



$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->applyFromArray($styleThinBlackBorderlatest);





				$assianCompany=$Company;

					$con++;

					//$InCount	='';

					$Company	='';

					$Date		='';  

					$Market		='';

					$RateName	='';

					$seasonName	='';

					$rateLevelName	='';

					$CompanyDefaultGroupType	='';

					$RowCount='';

				//}

				//$RowCount=$con;

				//$InCount	=$InCount2;

			}

		}//num

		

		

		

//===========Rate UNIT END===================================================================>	





		

		/*paste here*/

		$con++;

		$seasonSql = "SELECT id,name,start_date,end_date FROM ".TBL_RATE_SEASON." WHERE id IN (".$seasonIdCron.") ORDER BY id";

		$boldCon =$con;

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$con, 'Note : ');

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, 'Season Description');

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, 'From');

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con, 'To');

		

		cellColor('A'.$boldCon.':D'.$con,'c6c6c6');

		$con++;



		if($seasonIdCron=='65,66'){

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, 'SUMMER 2019');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, '01-Apr-2019');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con++, '30-Sep-2019');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, 'WINTER 2019');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, '01-Oct-2019');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con++, '31-Mar-2020');

			//$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->getFont()->setBold(true);

			//$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->applyFromArray($styleThinBlackBorderOutline);

		

		}

		else if($seasonIdCron=='65,66,67'){

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

			//$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->applyFromArray($styleThinBlackBorderOutline);

			

		}

		else if(isset($cron)){

			$seasonDeatils = selectColumn(TBL_RATE_SEASON,'CONCAT(name,"|",start_date,"|",end_date)','WHERE id="'.$seasonIdCron.'" ');

			$seasonDeatilsArr=explode('|',$seasonDeatils);



			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con,$seasonDeatilsArr[0]);

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con,date('d-M-Y',strtotime($seasonDeatilsArr[1])));

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con++,date('d-M-Y',strtotime($seasonDeatilsArr[2])));

		}

		else{

			 $resSea = executeSQl($seasonSql);

			while($rowSea = $db->fetch_object2($resSea)){

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$con, $rowSea->name);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$con, date('d-M-Y',strtotime($rowSea->start_date)));

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$con, date('d-M-Y',strtotime($rowSea->end_date)));

				$con++;

			}

			//$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->getFont()->setBold(true);	

			//$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->applyFromArray($styleThinBlackBorderOutline);

		}	

		/*paste here*/





		//} //Hotel Sql End



		$col2	=	$con-1;	

		$objPHPExcel->getActiveSheet()->getStyle('A7:N9')->applyFromArray($styleThinBlackBorderlatest);

		/*$objPHPExcel->getActiveSheet()->getStyle('A8:N8')->applyFromArray($styleThinBlackBorderlatest);

		$objPHPExcel->getActiveSheet()->getStyle('A9:N9')->applyFromArray($styleThinBlackBorderlatest);*/

		



		$objPHPExcel->getActiveSheet()->getStyle('A7:N9')->getAlignment()->applyFromArray(

			array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

		);

		/*$objPHPExcel->getActiveSheet()->getStyle('A8:N8')->getAlignment()->applyFromArray(

			array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

		);			



		$objPHPExcel->getActiveSheet()->getStyle('A'.$setcellcount.':N'.$col2)->getAlignment()->applyFromArray(

			array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

		);

		$objPHPExcel->getActiveSheet()->getStyle('A9:N9')->getAlignment()->applyFromArray(

			array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

		);*/

		$objPHPExcel->getActiveSheet()->getStyle('C'.$setcellcount.':K'.$col2)->getAlignment()->applyFromArray(

			array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

		);



		/*$objPHPExcel->getActiveSheet()->getStyle('D'.$setcellcount.':D'.$col2)->getAlignment()->applyFromArray(

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

		);*/

		

		
				

		$setcellcount	=	$con;

		//Protecting File

		/*$objPHPExcel->getSecurity()->setLockWindows(true);

        $objPHPExcel->getSecurity()->setLockStructure(true);

        $objPHPExcel->getSecurity()->setWorkbookPassword("FreeBlocking");

        $objPHPExcel->getActiveSheet()->getProtection()->setPassword('FreeBlocking');

        $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);

        // This should be enabled in order to enable any of the following!

        $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);

        $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);*/			

		$objPHPExcel->getActiveSheet()->setTitle('Hotel Wise Contract Report');

		//Protecting File End



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



			

		if(!isset($cron)){
			

			$HotelName=	str_replace(' ', '_',$HotelRecords->name).'_'.$HotelRecords->city.'_'.date('Y-m-d');



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

		}	

		else{
			$fullCount=($unitCount+$rsoCount);
			if($fullCount>0){
				$seasonName = selectColumn(TBL_RATE_SEASON,'name','WHERE id="'.$seasonIdCron.'" ');

				$fileName= $HotelRecords->name.'_'.$HotelRecords->city.' Contracted Rate Report for '.ucwords(strtolower($seasonName));


				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

					//local

					//$objWriter->save('../mailattach/'.$fileName.'.xls');

					

					//cron server

				$objWriter->save('/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/'.$fileName.'.xls');

			}
		}


	}	

	// Hotel Wise Contract Report End
?>