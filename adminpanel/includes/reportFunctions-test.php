<?php 

error_reporting(E_ALL);

	function cellColor($cells,$color){

	    global $objPHPExcel;

		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(

	        'type' => PHPExcel_Style_Fill::FILL_SOLID,

	        'startcolor' => array(

	        'rgb' => $color

	    	)	

	    ));

	}

function unitExecutivePortFolioYearlyReport($cron,$id_shop,$id_user,$fy_date,$fileName="",$conn="",$objPHPExcel=""){

		

$sql = "SELECT  a.id_company,a.id_default_group,a.name,b.name AS area,c.id as id_user,c.name AS executive ,c.id as id_executive FROM `".TBL_COMPANY."` AS a

			LEFT JOIN `".TBL_AREAS."` AS b ON a.area=b.id

			LEFT JOIN `".TBL_USERS."` AS c ON FIND_IN_SET(c.id,b.ids_unit_user)

			WHERE a.`id_shop` = '".addslashes($id_shop)."' ";



		$sql .= " AND c.id = '".addslashes($id_user)."'  ";

/*$sql = "SELECT  a.id_company,a.id_default_group,a.name,b.name AS area,c.id as id_user,c.name AS executive  FROM `".TBL_COMPANY."` AS a

			LEFT JOIN `".TBL_AREAS."` AS b ON a.area=b.id

			LEFT JOIN `".TBL_USERS."` AS c ON b.user_id=c.id

			WHERE a.`id_shop` = '".addslashes($id_shop)."' ";



		echo $sql .= " AND c.id = '".addslashes($id_user)."' AND id_company IN (".$id_Company_array.") ";

		*/

		//die;

//echo $sql;
//die;
		$res = mysqli_query($conn,$sql);



		if($res){

			$numRows = mysqli_num_rows($res);

		}



		$numRows;



		/* setting dates for Achieved values*/



		$date = date('Y-m-d',strtotime($fy_date));

		$month = date('m',strtotime($date));



		if($month <= 3){

			$date = strtotime($date);

			$date = strtotime("-1 year",$date);

			$date = date('Y-04-01',$date);

		}

		else{

			$date =date('Y-04-01');

		}



		if(date('m')<=3){

			$period = date('Y',strtotime('-1 years',strtotime(date('Y')))).'-'.date('y');

		}

		else{

			$period = date('Y').'-'.date('y',strtotime('+1 years',strtotime(date('Y'))));	

		}

			

		// $end = strtotime($date);

		// $end = strtotime("+1 year",$end);

	$fy_date_year = date('Y',strtotime($fy_date));
    $fy_date_month = date('m',strtotime($fy_date));
    
    $financial_year_to = (date($fy_date_month) > 3) ? date($fy_date_year) +1 : date($fy_date_year);
	$financial_year_from = $financial_year_to - 1;
	
    $date = date("".$financial_year_from."-04-01");
    $end =  date("".$financial_year_to."-03-31");


		$prevYear = strtotime($date);

		$prevYear = strtotime("-1 year",$prevYear);

		$prevYear = date('Y-04-01',$prevYear);

		$prevYearEnd = date('Y-03-31',strtotime('+1 years',strtotime($prevYear)));

		

		$prevYear2 = strtotime($date);

		$prevYear2 = strtotime("-2 year",$prevYear2);

		$prevYear2 = date('Y-04-01',$prevYear2);

		$prevYear2End = date('Y-03-31',strtotime('+1 years',strtotime($prevYear2)));

		

		// Set document properties


  
		$objPHPExcel->getProperties()

					->setCreator("Hitesh Aloney")

					->setLastModifiedBy("Hitesh Aloney")

					->setTitle("Executive Portfoilo Report")

					->setSubject("Executive Portfoilo Report")

					->setDescription("Executive Portfoilo Report")

					->setKeywords("Executive Portfoilo Report")

					->setCategory("Report");				 	



		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing



		$objDrawing->setName('Logo');       //set name to image



		$objDrawing->setDescription('Logo'); //set description to image



		$logo = selectColumn('fs_shop','image'," WHERE  id=".$id_shop." ");



		if(!isset($cron)){

			$signature = '../uploaded_files/shop/'.$logo;

		}

		else{

			$signature = '/home/inroomhub/public_html/sync/uploaded_files/shop/'.$logo;

		}



		

		 //Path to signature .jpg file



		if(file_exists($signature)){

			$objDrawing->setPath($signature);

			$objDrawing->setOffsetX(20);                       //setOffsetX works properly

			$objDrawing->setOffsetY(10);                       //setOffsetY works properly

			$objDrawing->setCoordinates('H1');        //set image to cell

			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		}  //save							 



				

		if($numRows > 0){

			$counter = 1;

			$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A6', 'Executive Portfoilo - Unit Report Year :'.$period);



			$objPHPExcel->getActiveSheet()->mergeCells('A6:R6');

			$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('F7', 'Rate Number');



			$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('I7', 'Achieved');



			$objPHPExcel->getActiveSheet()->mergeCells('H7:H8');

			$objPHPExcel->getActiveSheet()->mergeCells('I7:K7');

			//$objPHPExcel->getActiveSheet()->mergeCells('J7:M7');

			$objPHPExcel->getActiveSheet()->mergeCells('A7:A8');

			$objPHPExcel->getActiveSheet()->mergeCells('B7:B8');

			$objPHPExcel->getActiveSheet()->mergeCells('C7:C8');

			$objPHPExcel->getActiveSheet()->mergeCells('D7:D8');

			$objPHPExcel->getActiveSheet()->mergeCells('E7:E8');

			$objPHPExcel->getActiveSheet()->mergeCells('F7:F8');

			$objPHPExcel->getActiveSheet()->mergeCells('G7:G8');

			$objPHPExcel->getActiveSheet()->mergeCells('M7:M8');			

				

			$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('L7', 'Budget');



			$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('N7', 'LAST FIVE VISITS');



			$objPHPExcel->getActiveSheet()->mergeCells('N7:R7');



			$head_hotel_row = 7;

			$head_cntr_column = "A";$head_hotel_column = "A";



			$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Group')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Area')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Summer Rate No.')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Winter Rate No.')

						->setCellValue($head_cntr_column++.$head_hotel_row++, 'Year Rate No.')

						->setCellValue($head_cntr_column++.$head_hotel_row, date('Y',strtotime($prevYear2)).'-'.date('y',strtotime($prevYear)))

						->setCellValue($head_cntr_column++.$head_hotel_row, date('Y',strtotime($prevYear2End)).'-'.date('y',strtotime($date)))

						->setCellValue($head_cntr_column++.$head_hotel_row, date('Y',strtotime($date)).'-'.date('y',strtotime($end)))

						->setCellValue($head_cntr_column++.$head_hotel_row,date('Y',strtotime($date)).'-'.date('y',strtotime($end)))

						->setCellValue($head_cntr_column++.'7', 'V2B')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 1')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 2')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 3')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 4')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 5');





			$styleArray = array(

					    'font'  => array(

				        'bold'  => true,

				        'color' => array('rgb' => 'ffffff'),

				        'size'  => 14,

				        'name'  => 'Verdana'

					    ));



			$styleArray_1 = array(

			    			'font'  => array(

					        'bold'  => true,

					        'color' => array('rgb' => '000000'),

					        'size'  => 10,

					        'name'  => 'Verdana'

		    				));



			$subHeading = array(

					    'font'  => array(

				        'bold'  => true,

				        'color' => array('rgb' => '000'),

				        'size'  => 10,

				        'name'  => 'Verdana'

		    ));

			 



			cellColor('A6:R6','254061');

			cellColor('A7:R8','75923c');



			$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);



			$objPHPExcel->getActiveSheet()->getStyle('A6:R6')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);





			$objPHPExcel->getActiveSheet()->getStyle('A7:Q7')->applyFromArray($styleArray_1);



			$objPHPExcel->getActiveSheet()->getStyle('H8:R8')->applyFromArray($styleArray_1);



			$objPHPExcel->getActiveSheet()->getStyle('A7:R7')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('F7:R7')->applyFromArray($subHeading);



			$objPHPExcel->getActiveSheet()->getStyle('F7:R7')->getAlignment()->applyFromArray(

				    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('C7')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('D7')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->applyFromArray(

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



			$objPHPExcel->getActiveSheet()->getStyle('A7:H8')->getAlignment()->applyFromArray(



			    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)



			);



			$objPHPExcel->getActiveSheet()->getStyle('M7:M8')->getAlignment()->applyFromArray(



			    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)



			);



			$objPHPExcel->getActiveSheet()->getStyle('L7')->getAlignment()->applyFromArray(



			    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)



			);



			$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(



			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



			);



			$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(



			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



			);



			$objPHPExcel->getActiveSheet()->getStyle('L:R')->getAlignment()->applyFromArray(



			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



			);

			$objPHPExcel->getActiveSheet()->getStyle('N8:R8')->getAlignment()->applyFromArray(



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

	



			$objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->setWrapText(true);

			$objPHPExcel->getActiveSheet()->getStyle('G7')->getAlignment()->setWrapText(true);

			$objPHPExcel->getActiveSheet()->getStyle('H7')->getAlignment()->setWrapText(true);

			$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()

			    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			



			$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(20);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(25);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(10);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(10);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(10);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(10);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(10);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(10);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(10);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(6);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(12);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('O')->setWidth(12);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('P')->setWidth(12);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('Q')->setWidth(12);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('R')->setWidth(12);

			$head_hotel_row++;



			$Serialno=1;

			$connew = $head_hotel_row;
			
			mysqli_query($conn,"DELETE FROM temp_executive_port WHERE id_executive='".$id_user."' ");
							
			

			$tempTableArr=array();
$sha=1;
		while($row = mysqli_fetch_object($res)){
echo'<br>'.$sha++;

				$achieved =selectColumn(TBL_UNIT_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$date."' and '".$end."' and id_shop='".$id_shop."' and id_company='".$row->id_company."' AND id_user='".$id_user."'  ");
				//$budget = selectColumn(TBL_UNIT_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."' AND month between '".$date."' and '".date('Y-03-31',strtotime($end))."' AND id_user='".$id_user."'  ");
                  $budget = selectColumn(TBL_UNIT_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."' AND `from`= '".$date."' AND `to`= '".date('Y-03-31',strtotime($end))."' AND id_user='".$id_user."' ");
				
				$prevAch2 = selectColumn(TBL_UNIT_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear2."' and '".$prevYear2End."' and id_shop='".$id_shop."' and id_company='".$row->id_company."' AND id_user='".$id_user."'  ");
				$prevAch = selectColumn(TBL_UNIT_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear."' and '".$prevYearEnd."' and id_shop='".$id_shop."' and id_company='".$row->id_company."' AND id_user='".$id_user."'  ");
				$sumSql = "SELECT rate_name FROM ".TBL_RATE_UNIT."  WHERE id_shop='".$id_shop."' AND company_id='".$row->id_company."' AND date(start_date)='".date('Y-04-01',strtotime($date))."' ";
				$winSql = "SELECT  rate_name FROM ".TBL_RATE_UNIT."  WHERE id_shop='".$id_shop."' AND company_id='".$row->id_company."' AND date(start_date)='".date('Y-10-01',strtotime($date))."' ";
				$yearSql="SELECT  rate_name FROM ".TBL_RATE_UNIT."  WHERE id_shop='".$id_shop."' AND company_id='".$row->id_company."' AND (date(start_date)='".date('Y-01-01',strtotime($date))."' AND date(end_date)='".date('Y-12-31',strtotime($date))."' ) ";

				$resSum = mysqli_query($conn,$sumSql);	
				$resWin = mysqli_query($conn,$winSql);
				$resYear = mysqli_query($conn,$yearSql);


				$sumRow=mysqli_fetch_object($resSum);
				$winRow=mysqli_fetch_object($resWin);	
				$yearRow=mysqli_fetch_object($resYear);	

				$sqlDate ="SELECT * FROM `".TBL_DAILYVISIT."` WHERE id_company =".$row->id_company." and id_user =".$row->id_executive." Order BY dated desc  LIMIT 5 "; 

		        $resDate = mysqli_query($conn,$sqlDate);

		        $tempTableCount = 0;
		        $tempTableVisit = array();


		        if($resDate){ 
		        	//$dateCol = 'N';
		        	while($resData = mysqli_fetch_object($resDate)){
		        		$tempTableVisit[$tempTableCount]=date('Y-m-d',strtotime($resData->dated));     		
		        		$tempTableCount++;
		        	}
		        	
		        }		

		        $companyGroupName = selectColumn(TBL_GROUP,'name'," WHERE id_group='".$row->id_default_group."' ");
		        		        echo ' INSERT INTO temp_executive_port VALUES('.$Serialno++.',"'.$row->id_executive.'","'.$row->executive.'","'.str_replace('&amp;','&', $row->name).'","'.trim($companyGroupName).'","'.$row->area.'","'.$sumRow->rate_name.'","'.$winRow->rate_name.'","'.$yearRow->rate_name.'","'.$prevAch2.'","'.$prevAch.'","'.$achieved.'","'.$budget.'","'.$v2b.'","'.$tempTableVisit[0].'","'.$tempTableVisit[1].'","'.$tempTableVisit[2].'","'.$tempTableVisit[3].'","'.$tempTableVisit[4].'")';
		        array_push($tempTableArr,' INSERT INTO temp_executive_port VALUES('.$Serialno++.',"'.$row->id_executive.'","'.$row->executive.'","'.str_replace('&amp;','&', $row->name).'","'.trim($companyGroupName).'","'.$row->area.'","'.$sumRow->rate_name.'","'.$winRow->rate_name.'","'.$yearRow->rate_name.'","'.$prevAch2.'","'.$prevAch.'","'.$achieved.'","'.$budget.'","'.$v2b.'","'.$tempTableVisit[0].'","'.$tempTableVisit[1].'","'.$tempTableVisit[2].'","'.$tempTableVisit[3].'","'.$tempTableVisit[4].'")');
			}

			
			foreach ($tempTableArr as $key => $query) {
				mysqli_query($conn,$query);
				debugData(mysqli_error($conn));
			}
			 $tempTableFetch = "SELECT * FROM  temp_executive_port where id_executive='".$id_user."' order by year desc";
			  //$tempTableFetch = "SELECT * FROM  temp_executive_port where id_executive='".$id_user."' AND  (year!=0  OR budg_year!=0 OR visit1!='0000-00-00') order by year desc";

			$fetchRes = mysqli_query($conn,$tempTableFetch);
			

			
			$Serial=1;
			while($fetchTempRow = mysqli_fetch_object($fetchRes)){
				echo $fetchTempRow->company_name.'<br>';

				$head_order_data1 = "A";
				$head_order_data = "A";       

				$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A6', ''.$fetchTempRow->executive.' Portfoilo Report Year :'.$period);

				$objPHPExcel->setActiveSheetIndex(0)

							->setCellValue($head_order_data++ . $connew,$Serial++)

							->setCellValue($head_order_data++ . $connew, $fetchTempRow->executive)

							->setCellValue($head_order_data++ . $connew, $fetchTempRow->company_name)

							->setCellValue($head_order_data++ . $connew, $fetchTempRow->company_group)

							->setCellValue($head_order_data++ . $connew, $fetchTempRow->area)

							->setCellValue($head_order_data++ . $connew, ($fetchTempRow->summer_rate!=''?$fetchTempRow->summer_rate:'-'))

							->setCellValue($head_order_data++ . $connew, ($fetchTempRow->winter_rate!=''?$fetchTempRow->winter_rate:'-'))

							->setCellValue($head_order_data++ . $connew, ($fetchTempRow->year_rate!=''?$fetchTempRow->year_rate:'-'))

							->setCellValue($head_order_data++ . $connew,($fetchTempRow->year_pre_2==''?0:$fetchTempRow->year_pre_2) )

							->setCellValue($head_order_data++ . $connew,($fetchTempRow->year_pre==''?0:$fetchTempRow->year_pre))

							->setCellValue($head_order_data++ . $connew,$fetchTempRow->year==''?0:$fetchTempRow->year)

							->setCellValue($head_order_data++ . $connew,$fetchTempRow->budg_year==''?0:$fetchTempRow->budg_year);



				$v2b = ($fetchTempRow->year-$fetchTempRow->budg_year);

				

				if($v2b<0)
					cellColor($head_order_data.$connew,'f72e2e');
				else
					cellColor($head_order_data.$connew,'65ed5e');

				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($head_order_data++ . $connew,$v2b);

				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($head_order_data++ . $connew,($fetchTempRow->visit1=='0000-00-00'?'':date('d-M-Y',strtotime($fetchTempRow->visit1))));
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($head_order_data++ . $connew,($fetchTempRow->visit2=='0000-00-00'?'':date('d-M-Y',strtotime($fetchTempRow->visit2))));
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($head_order_data++ . $connew,($fetchTempRow->visit3=='0000-00-00'?'':date('d-M-Y',strtotime($fetchTempRow->visit3))));
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($head_order_data++ . $connew,($fetchTempRow->visit4=='0000-00-00'?'':date('d-M-Y',strtotime($fetchTempRow->visit4))));
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($head_order_data++ . $connew,($fetchTempRow->visit5=='0000-00-00'?'':date('d-M-Y',strtotime($fetchTempRow->visit5))));		
				$connew++;				
			}
			die;
			$objPHPExcel->getActiveSheet()->getStyle('A6:R8')->applyFromArray($styleThinBlackBorderOutline);

			$objPHPExcel->getActiveSheet()->getStyle('H8:R8')->applyFromArray($styleThinBlackBorderOutline);


				//Grand total start here

			$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':R'.$connew)->getFont()->setBold( true );

			cellColor('A'.$connew.':R'.$connew,'b5d8ab');

			$objPHPExcel->getActiveSheet()->mergeCells('A'.$connew.':G'.$connew);

			$objPHPExcel->setActiveSheetIndex(0)

			   			->setCellValue('A'.$connew, 'TOTAL')

			   			->setCellValue('I'.($connew),'=SUM(I9:I'.($connew-1).')')

						->setCellValue('J'.($connew),'=SUM(J9:J'.($connew-1).')')

						->setCellValue('K'.($connew),'=SUM(K9:K'.($connew-1).')')

						->setCellValue('L'.($connew),'=SUM(L9:L'.($connew-1).')')
						->setCellValue('M'.($connew),'=SUM(M9:M'.($connew-1).')');



			$objPHPExcel->getActiveSheet()->getStyle('A9:R'.$connew)->applyFromArray($styleThinBlackBorderOutline);



			$forTotal = 'C';

			

			$totalArray = array(

			    'font'  => array(

		        'bold'  => true,

		        'color' => array('rgb' => '1e51bf'),

		        'size'  => 12,

		        'name'  => 'Verdana'

		    ));



		}


		$objPHPExcel->getActiveSheet()->setTitle('Executive Portfolio Yearly');

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		//$objPHPExcel->getActiveSheet()->getStyle('B10:B999')

				   // ->getAlignment()->setWrapText(true);	



		$objPHPExcel->getActiveSheet()

				    ->getPageMargins()->setTop(0.50);



		$objPHPExcel->getActiveSheet()

				    ->getPageMargins()->setRight(0.10);



		$objPHPExcel->getActiveSheet()

				    ->getPageMargins()->setLeft(0.15);



		$objPHPExcel->getActiveSheet()

				    ->getPageMargins()->setBottom(1);	



		$objPHPExcel->getActiveSheet()

				    ->getPageSetup()

				    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);



		$objPHPExcel->getActiveSheet()

				    ->getPageSetup()

				    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		

		$objPHPExcel->getActiveSheet()->setTitle('Executive Portfolio Yearly');

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		//$objPHPExcel->getActiveSheet()->getStyle('B10:B999')
				   // ->getAlignment()->setWrapText(true);	



		$objPHPExcel->getActiveSheet()

				    ->getPageMargins()->setTop(0.50);



		$objPHPExcel->getActiveSheet()

				    ->getPageMargins()->setRight(0.10);



		$objPHPExcel->getActiveSheet()

				    ->getPageMargins()->setLeft(0.15);



		$objPHPExcel->getActiveSheet()

				    ->getPageMargins()->setBottom(1);	



		$objPHPExcel->getActiveSheet()

				    ->getPageSetup()

				    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);



		$objPHPExcel->getActiveSheet()

				    ->getPageSetup()

				    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		

		if(!isset($cron)){

			ob_end_clean();

			// Redirect output to a client’s web browser (Excel2007)

			header('Content-Type: application/vnd.ms-excel');

			header('Content-Disposition: attachment;filename="Executive Portfolio Yearly.xls"');

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

			

			if($numRows>0){	

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

				//local

				//$objWriter->save('../mailattach/'.$fileName.'.xls');

					

				//cron server

				$objWriter->save('/home/inroomhub/public_html/sync/adminpanel/mailattach/'.$fileName.'.xls');

			}		

		}	

				



	}?>