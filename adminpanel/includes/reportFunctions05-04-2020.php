<?php

error_reporting(E_ALL);



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

	function loginLogoutReport($id_shop, $startDate, $endDate, $id_executive, $cron=false){
		
		global $connNew;
		global $objPHPExcel;

		$totalArray = array(
	      		'font'  => array(
		        'bold'  => true,
		        'color' => array('rgb' => '1e51bf'),
		        'size'  => 12,
		        'name'  => 'Verdana'
        ));


		$styleArray = array(
			    'font'  => array(
	          	'bold'  => true,
	          	'color' => array('rgb' => 'FFFFFF'),
		        'size'  => 12,
			    'text-transform'=>'uppercase',
		        'name'  => 'Calibri'
		      	)  
        );



		$styleArray_1 = array(
			    'font'  => array(
	            'bold'  => true,
   	            'color' => array('rgb' => '000'),
	            'size'  => 12,
 			    'text-transform'=>'uppercase'
	   		)
	    );



		$styleThinBlackBorderOutline = array(
			    'borders' => array(
		        'allborders' => array(
		        'style' => PHPExcel_Style_Border::BORDER_THIN,
		        'color' => array('argb' => '000'),
     		 	),
		    ),
		); 


		$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")
			        ->setLastModifiedBy("Hitesh Aloney")
			        ->setTitle("Attendace REPORT")
			        ->setSubject("Attendace REPORT")
			        ->setDescription("Attendace REPORT")
				    ->setKeywords("Attendace REPORT")
				    ->setCategory("Report");

		$objDrawing = new PHPExcel_Worksheet_Drawing();   
		$objDrawing->setName('Logo');  
		$objDrawing->setDescription('Logo');



		$logo = selectColumn('fs_shop','image'," WHERE  id=".$id_shop." ");

		if(!isset($cron)){
			$signature = "../uploaded_files/shop/".$logo."";
		}
		else{
			$signature = '/home/inroomhub/public_html/sync/uploaded_files/shop/'.$logo;
		}


		if(file_exists($signature)){

		    $objDrawing->setPath($signature);
		    $objDrawing->setOffsetX(25);                       //setOffsetX works properly
		    $objDrawing->setOffsetY(10);                       //setOffsetY works properly
		    $objDrawing->setCoordinates('B1');        //set image to cell
		    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

	    }  

	    $sql = "SELECT DATE(created_at) AS date, (CASE WHEN type=1 THEN TIME_FORMAT(MIN(created_at), '%h:%i %p')  ELSE 'Not logged in' END ) AS login,(CASE WHEN  type=1 THEN TIME_FORMAT(MAX(created_at), '%h:%i %p')  ELSE 'Not logged out' END ) AS logout
				FROM sales_executive_locations  WHERE id_user=".$id_executive." AND id_shop=".$id_shop."  AND DATE(created_at) BETWEEN '".$startDate."' AND '".$endDate."' GROUP BY DATE(created_at)";

			
		$res = mysqli_query($connNew, $sql);		
		$numRows = mysqli_num_rows($res);
		if($numRows > 0){

		    $counter = 1;

		    $objPHPExcel->setActiveSheetIndex(0)
					    ->setCellValue('A6', ''.selectColumn('fs_users','name','WHERE id="'.$id_executive.'" ').' Attendace Report From '.date('d M',strtotime($startDate)).' Till '.date('d M Y',strtotime($endDate)) );

		    $objPHPExcel->getActiveSheet()->mergeCells('A6:D6');



		    $head_hotel_row = 7;
		    $head_cntr_column = "A";
		    $head_hotel_column = "A";



			$objPHPExcel->setActiveSheetIndex(0)
					    ->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
					    ->setCellValue($head_cntr_column++.$head_hotel_row, 'Date')
					    ->setCellValue($head_cntr_column++.$head_hotel_row, 'Login')
					    ->setCellValue($head_cntr_column++.$head_hotel_row, 'Logout');      


			cellColor('A6:D6','254061');
			cellColor('A7:D7','75923c');



			$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A6:D6')->getAlignment()->applyFromArray(
			      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	 	    );

			$objPHPExcel->getActiveSheet()->getStyle('A7:D7')->applyFromArray($styleArray_1);
		    $objPHPExcel->getActiveSheet()->getStyle('A7:D7')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);



			$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		  	);

		    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(
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

			$objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->applyFromArray(
		        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		    );

			$objPHPExcel->getActiveSheet()->getStyle('G:D')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			$head_hotel_row=8;
	   		$sno=1;

		    

		    while($row = mysqli_fetch_object($res)){

		      $head_cntr_column='A';

		      $objPHPExcel->setActiveSheetIndex(0)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $sno)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, date('d-M-Y', strtotime($row->date)))

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $row->login)

		        ->setCellValue($head_cntr_column++.$head_hotel_row++, $row->logout);	 

		        $sno++;

		    }





		  $objPHPExcel->getActiveSheet()->getStyle('A6:D'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);

		  $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()->setWrapText(true);

		  $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);

		  $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setWrapText(true);



		  $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()
				      ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(10);  
		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(15); 
		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20); 
		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);

		}

		$objPHPExcel->getActiveSheet()->setTitle('Login Logout Report');
	    $objPHPExcel->setActiveSheetIndex(0);





		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);



		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.10);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.15);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1); 
		$objPHPExcel->getActiveSheet()->getPageSetup()
					->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);



		$objPHPExcel->getActiveSheet()->getPageSetup()
				    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		
		    ob_end_clean();
		    // Redirect output to a client’s web browser (Excel2007)
		    header('Content-Type: application/vnd.ms-excel');
		    header('Content-Disposition: attachment;filename="Login_Logout_Report '.date('d-m-Y H:i:s').'.xls"');
		    header('Cache-Control: max-age=0');
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

	// Company Addition Report Start

	function companyAdditionReport($id_shop,$startDate,$endDate,$connNew,$teamMembers,$cron=NULL){



		global $objPHPExcel;

	 	$totalArray = array(

		      'font'  => array(

	          'bold'  => true,

	          'color' => array('rgb' => '1e51bf'),

	          'size'  => 12,

	          'name'  => 'Verdana'

	        ));



	 	$styleArray = array(

		    'font'  => array(

	          	'bold'  => true,

	          	'color' => array('rgb' => 'FFFFFF'),

		        'size'  => 14,

			    'text-transform'=>'uppercase',

		        'name'  => 'Calibri'

	      	)  

	      );



	 	$styleArray_1 = array(

		    'font'  => array(

	          'bold'  => true,

	          'color' => array('rgb' => '000'),

	          'size'  => 12,

		      'text-transform'=>'uppercase'

		    )

		  );



	 	$styleThinBlackBorderOutline = array(

		    'borders' => array(

		      'allborders' => array(

		        'style' => PHPExcel_Style_Border::BORDER_THIN,

		        'color' => array('argb' => '000'),

	     		 ),

		    ),



		);  



	 	



		$sql="SELECT A.last_modified,A.date_created,A.name AS company,A.city,B.name AS executive, B.ids_team AS team,".TBL_AREAS.".description AS area_desc,C.name AS modified_by,C.ids_team AS modified_team FROM ".TBL_COMPANY." AS A

		    LEFT JOIN ".TBL_AREAS."  ON A.area = ".TBL_AREAS.".id

		    LEFT JOIN ".TBL_USERS." AS B ON ".TBL_AREAS.".user_id= B.id 

		    LEFT JOIN ".TBL_USERS." AS C ON A.last_modified_by= C.id

		    WHERE A.id_shop='".$id_shop."' AND ((DATE(A.date_created)

		    BETWEEN '".date('Y-m-d',strtotime($startDate))."' AND '".date('Y-m-d',strtotime($endDate))."') OR (DATE(A.last_modified)

		    BETWEEN '".date('Y-m-d',strtotime($startDate))."' AND '".date('Y-m-d',strtotime($endDate))."')) AND FIND_IN_SET(A.area,'".$teamMembers."') ";

		   

		  		  

		$sql .= " ORDER BY A.date_created,A.name DESC"; 

		  /*echo $sql;

		  exit; */ 

		$resCom = mysqli_query($connNew,$sql); 

		$numRows = mysqli_num_rows($resCom);



		

	    $objPHPExcel->getProperties()->setCreator("Hitesh Aloney")

	        ->setLastModifiedBy("Hitesh Aloney")

	        ->setTitle("Company Addition REPORT")

	        ->setSubject("Company Addition REPORT")

	        ->setDescription("Company Addition REPORT")

		    ->setKeywords("Company Addition REPORT")

		    ->setCategory("Report");







		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing

		$objDrawing->setName('Logo');        //set name to image

		$objDrawing->setDescription('Logo'); //set description to image



		$logo = selectColumn('fs_shop','image'," WHERE  id=".$id_shop." ");

		if(!isset($cron)){

			$signature = "../uploaded_files/shop/".$logo."";

		}

		else{

		 

			$signature = '/home/inroomhub/public_html/sync/uploaded_files/shop/'.$logo;

		       //Path to signature .jpg file

		}

		if(file_exists($signature)){

		    $objDrawing->setPath($signature);

		    $objDrawing->setOffsetX(25);                       //setOffsetX works properly

		    $objDrawing->setOffsetY(10);                       //setOffsetY works properly

		    $objDrawing->setCoordinates('E1');        //set image to cell

		    /*$objDrawing->setWidth(200);                 //set width, height

		    $objDrawing->setHeight(150);*/  

		    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

	    }  //save





		if($numRows > 0){

		    $counter = 1;

		    $objPHPExcel->setActiveSheetIndex(0)

		    ->setCellValue('A6', 'Company Log Report From '.date('d F',strtotime($startDate)).' Till '.date('d F Y',strtotime($endDate)) );

		    $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');



		    $head_hotel_row = 7;



		    $head_cntr_column = "A";$head_hotel_column = "A";



		    $objPHPExcel->setActiveSheetIndex(0)

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Created Date')

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive Name')

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive Team')

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Area Description')

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Added By')

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Modified By')

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Modified Team')

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Modified Date');      

  



		cellColor('A6:J6','254061');

		cellColor('A7:J7','75923c');



		$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

	   $objPHPExcel->getActiveSheet()->getStyle('A6:J6')->getAlignment()->applyFromArray(

		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

 	    );

		  $objPHPExcel->getActiveSheet()->getStyle('A7:J7')->applyFromArray($styleArray_1);



		   $objPHPExcel->getActiveSheet()->getStyle('A7:J7')->getAlignment()->applyFromArray(



		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



		  );



		  $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(



		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



		  );



		  $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(



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

		  $objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->applyFromArray(



		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



		  );

		  $objPHPExcel->getActiveSheet()->getStyle('G:J')->getAlignment()->applyFromArray(



		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



		  );

		 



		   

		    

		    

		    $head_hotel_row=8;

		    

		    $sno=1;

		    

		    while($rowData = mysqli_fetch_object($resCom)){

		      $head_cntr_column='A';

		      $objPHPExcel->setActiveSheetIndex(0)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $sno)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, date('d/m/Y',strtotime($rowData->date_created)))

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->company)

		        ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->executive)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, selectColumn(TBL_TEAM,'name','WHERE id IN ('.$rowData->team.')'))

		        ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->area_desc)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->modified_by)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->modified_by)

		        ->setCellValue($head_cntr_column++.$head_hotel_row,

		        	($rowData->modified_team!=''?

		        	selectColumn(TBL_TEAM,'name','WHERE id IN ('.$rowData->modified_team.')'):''))

		        ->setCellValue($head_cntr_column++.$head_hotel_row++, date('d/m/Y',strtotime($rowData->last_modified)));	 

		        $sno++;

		    }





		    $objPHPExcel->getActiveSheet()->getStyle('A6:J'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);

		  $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()->setWrapText(true);

		  $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);

		  $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setWrapText(true);



		    $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()



		      ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(10);  



		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(15); 



		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20); 



		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);

		  

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(20);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(25);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(20);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(20);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(20);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(15); 

		    



		    $forTotal = 'C';



		    



		    

		  }

		  $objPHPExcel->getActiveSheet()->setTitle('Company Addition Report');







		    



		  $objPHPExcel->setActiveSheetIndex(0);





		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);



		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);



		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		/*$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);  

		  



		  */    



		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.50);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.10);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.15);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1); 

		$objPHPExcel->getActiveSheet()->getPageSetup()

		  ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);



		$objPHPExcel->getActiveSheet()->getPageSetup()

		    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		   if(!isset($cron)){

		    ob_end_clean();



		    // Redirect output to a client’s web browser (Excel2007)



		    header('Content-Type: application/vnd.ms-excel');

		    header('Content-Disposition: attachment;filename="Company_Addition_Report '.date('d-m-Y H:i:s').'.xls"');



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

				

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

				//$objWriter->save('../mailattach/Company_Addition_Report '.$startDate.'.xls');



				//cron server

				$objWriter->save('/home/inroomhub/public_html/sync/adminpanel/mailattach/Company_Addition_Report '.$startDate.'.xls');



			}

	}

	//Company Addition Report End



	//Comparison Report

	function camparisonReport($id_shop,$fyDate,$id_company){

		global $objPHPExcel;

		global $connNew;

	 	$totalArray = array(

		      'font'  => array(

	          'bold'  => true,

	          'color' => array('rgb' => '1e51bf'),

	          'size'  => 12,

	          'name'  => 'Verdana'

	        ));

	 	





	 	$styleArray = array(

		    'font'  => array(

	          	'bold'  => true,

	          	'color' => array('rgb' => 'FFFFFF'),

		        'size'  => 14,

			    'text-transform'=>'uppercase',

		        'name'  => 'Calibri'

	      	)  

	      );



	 	$styleArray_1 = array(

		    'font'  => array(

	          'bold'  => true,

	          'color' => array('rgb' => '000'),

	          'size'  => 12,

		      'text-transform'=>'uppercase'

		    )

		  );



	 	$styleThinBlackBorderOutline = array(

		    'borders' => array(

		      'allborders' => array(

		        'style' => PHPExcel_Style_Border::BORDER_THIN,

		        'color' => array('argb' => '000'),

	     		 ),

		    ),



		);  



	 	/*function cellColor($cells,$color){

	 		global $objPHPExcel;

		    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(

		        'type' => PHPExcel_Style_Fill::FILL_SOLID,

		        'startcolor' => array(

		        'rgb' => $color

		      )

		    ));

		}*/

		// setting periods

		if(date('m',strtotime($fyDate))<=3){

			$start = date('Y-04-01',strtotime('-1 years',strtotime($fyDate)));

			$end = date('Y-03-31',strtotime($fyDate)); 

		}

		else{

			$start = date('Y-04-01',strtotime($fyDate)); 

			$end = date('Y-03-31',strtotime('+1 years',strtotime($fyDate))); 

		}



		echo "<br>".$start;

		echo "<br>".$end;

		echo "<br>".$prevYearStart = date('Y-04-01',strtotime('-1 years',strtotime($start)));

		echo "<br>".$prevYearEnd = date('Y-03-31',strtotime('-1 years',strtotime($end)));



		echo "<br>".$prevYearStart2 = date('Y-04-01',strtotime('-1 years',strtotime($prevYearStart)));

		echo "<br>".$prevYearEnd2 = date('Y-03-31',strtotime('-1 years',strtotime($prevYearEnd)));



		/*echo "-----Rate IDs----";

		echo "<br>SUm :".$id_rate_summer = selectColumn(TBL_RATE,'id','WHERE company_id='.$id_company.' AND DATE(start_date)="'.$start.'" ');

		echo "<br>WIn:".$id_rate_winter = selectColumn(TBL_RATE,'id','WHERE company_id='.$id_company.' AND DATE(start_date)="'.date('Y-10-01',strtotime($start)).'" ');

		echo "<br>SUm :".$id_rate_summer_prev = selectColumn(TBL_RATE,'id','WHERE company_id='.$id_company.' AND DATE(start_date)="'.$prevYearStart.'" ');

		echo "<br>WIn:".$id_rate_winter_prev = selectColumn(TBL_RATE,'id','WHERE company_id='.$id_company.' AND DATE(start_date)="'.date('Y-10-01',strtotime($prevYearStart)).'" ');*/

					 

		  		  

		//$sql .= " ORDER BY A.date_created,A.name DESC"; 

		/*echo $sql;

		exit; */ 



		/*echo $sql= "SELECT hotel_id,display_order,

				SUM(CASE

    			WHEN DATE(date) = '".$start."' AND company_id=".$id_company." THEN single_pax_price 

    					ELSE '0'

					END) AS single_pax_sum,	

				SUM(CASE

    			WHEN DATE(date) = '".$start."' AND company_id=".$id_company." THEN double_pax_price 

    					ELSE '0'

					END) AS double_pax_sum,

				SUM(CASE

    			WHEN DATE(date) = '".date('Y-10-01',strtotime($start))."' AND company_id=".$id_company." THEN single_pax_price 

    					ELSE '0'

					END) AS single_pax_win,	

				SUM(CASE

    			WHEN DATE(date) = '".date('Y-10-01',strtotime($start))."' AND company_id=".$id_company." THEN double_pax_price 

    					ELSE '0'

					END) AS double_pax_win		

			 FROM rate_view WHERE company_id=".$id_company." AND  id_shop=".$id_shop." AND display_order=1 GROUP BY hotel_id,room_id,rate_plan_id  ";

		exit;

		$resCom = mysqli_query($connNew,$sql); 

		$numRows = mysqli_num_rows($resCom);*/



		

	    $objPHPExcel->getProperties()->setCreator("Hitesh Aloney")

	        ->setLastModifiedBy("Hitesh Aloney")

	        ->setTitle("Comparison REPORT")

	        ->setSubject("Comparison REPORT")

	        ->setDescription("Comparison REPORT")

		    ->setKeywords("Comparison REPORT")

		    ->setCategory("Report");





		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing

		$objDrawing->setName('Logo');        //set name to image

		$objDrawing->setDescription('Logo'); //set description to image



		$logo = selectColumn('fs_shop','image'," WHERE  id=".$id_shop." ");



		$signature = "../uploaded_files/shop/".$logo.""; 



		       //Path to signature .jpg file

		if(file_exists($signature)){

		    $objDrawing->setPath($signature);

		    $objDrawing->setOffsetX(25);                       //setOffsetX works properly

		    $objDrawing->setOffsetY(10);                       //setOffsetY works properly

		    $objDrawing->setCoordinates('E1');        //set image to cell

		    /*$objDrawing->setWidth(200);                 //set width, height

		    $objDrawing->setHeight(150);*/  

		    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

	    }  //save



	    $numRows = 0;

		if($numRows == 0){

		    $counter = 1;

		    $id_area =selectColumn(TBL_COMPANY,'area','WHERE id_company='.$id_company.' ');

		    $id_user =selectColumn(TBL_AREAS,'user_id','WHERE id='.$id_area.' ');

		    $objPHPExcel->setActiveSheetIndex(0)

		    ->setCellValue('A6', 'Company Name : '.selectColumn(TBL_COMPANY,'name','WHERE id_company='.$id_company.' '))

			->setCellValue('A7', 'Address : '.selectColumn(TBL_COMPANY,'address','WHERE id_company='.$id_company.' '))

			->setCellValue('H6', 'Company Handeled By : '.selectColumn(TBL_USERS,'name','WHERE id='.$id_user.' '))

		    ->setCellValue('A8', 'Comparison Report From '.date('Y',strtotime($prevYearStart)).' To '.date('Y',strtotime($end)) );

		    $objPHPExcel->getActiveSheet()->mergeCells('A8:K8');

		    $objPHPExcel->getActiveSheet()->mergeCells('A6:C6');

		    $objPHPExcel->getActiveSheet()->mergeCells('A7:C7');

		    $objPHPExcel->getActiveSheet()->mergeCells('H6:K6');

		    $objPHPExcel->getActiveSheet()->getStyle("A6:K7")->getFont()->setBold( true );

		    $head_hotel_row = 9;

		    $head_cntr_column = "A";$head_hotel_column = "A";



		    $objPHPExcel->setActiveSheetIndex(0)

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Hotel Name')	

		      ->setCellValue($head_cntr_column++.$head_hotel_row, '')	

		      

		      ->setCellValue($head_cntr_column++.$head_hotel_row, 'Room Type')

		      ->setCellValue($head_cntr_column.$head_hotel_row, date('Y',strtotime($prevYearStart)).'-'.date('Y',strtotime($prevYearEnd)))

		      ->setCellValue($head_cntr_column++.($head_hotel_row+1), 'Summer Rates')

		      ->setCellValue($head_cntr_column++.($head_hotel_row+1), 'Plan')

		      ->setCellValue($head_cntr_column++.($head_hotel_row+1), 'Winter Rates')

		      ->setCellValue($head_cntr_column++.($head_hotel_row+1), 'Plan')

		      ->setCellValue($head_cntr_column.$head_hotel_row, date('Y',strtotime($start)).'-'.date('Y',strtotime($end)))

		      ->setCellValue($head_cntr_column++.($head_hotel_row+1), 'Summer Rates')

		      ->setCellValue($head_cntr_column++.($head_hotel_row+1), 'Plan')

		      ->setCellValue($head_cntr_column++.($head_hotel_row+1), 'Winter Rates')

		      ->setCellValue($head_cntr_column++.($head_hotel_row+1), 'Plan');      

  

		$objPHPExcel->getActiveSheet()->mergeCells('A9:B10'); 

		$objPHPExcel->getActiveSheet()->mergeCells('C9:C10'); 

		//$objPHPExcel->getActiveSheet()->mergeCells('B9:B10'); 

		$objPHPExcel->getActiveSheet()->mergeCells('D9:G9');  

		$objPHPExcel->getActiveSheet()->mergeCells('H9:K9');     

		cellColor('A8:K8','254061');

		cellColor('A9:K10','75923c');

		$objPHPExcel->getActiveSheet()->getStyle('A8:K10')->applyFromArray($styleThinBlackBorderOutline);



		$objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($styleArray);

	   $objPHPExcel->getActiveSheet()->getStyle('A8:K10')->getAlignment()->applyFromArray(

		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

 	    );

		  $objPHPExcel->getActiveSheet()->getStyle('A9:K10')->applyFromArray($styleArray_1);



		   $objPHPExcel->getActiveSheet()->getStyle('C9:K10')->getAlignment()->applyFromArray(



		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



		  );



		  $objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->applyFromArray(



		          array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



		      );



		  $objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->applyFromArray(



		          array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



		      );



		  $objPHPExcel->getActiveSheet()->getStyle('C9')->getAlignment()->applyFromArray(



		          array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



		      );

		  $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(



		      array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)



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

		  $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(



		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



		  );

		  $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(



		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



		  );

		  $objPHPExcel->getActiveSheet()->getStyle('G:I')->getAlignment()->applyFromArray(



		      array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



		  );

		 



		   

		    

		    

		    $head_hotel_row=8;

		    

		    $sno=1;



		    /*$sqlHotel = "SELECT DISTINCT B.hotel_id FROM ".TBL_RATE." A RIGHT JOIN ".TBL_RATE_DETAILS." B	ON A.id=B.rate_id

				WHERE A.id_shop=".$id_shop." AND A.company_id=".$id_company." ";*/

			$sqlHotel = "SELECT  id AS hotel_id FROM ".TBL_HOTELS."

				WHERE id_shop=".$id_shop." AND status=1 ORDER BY display_order  ";	

			$resHotel = mysqli_query($connNew,$sqlHotel);

			$sno=1;

			$head_hotel_row=11;

			while($rowHotel = mysqli_fetch_object($resHotel)){

			$sql = "SELECT A.id,B.rate_plan_id,B.room_id,B.hotel_id,

					SUM(CASE

    					WHEN DATE(A.start_date) = '".$start."' THEN B.single_pax_price 

    					ELSE '0'

					END) AS single_pax_sum,

					SUM(CASE

    					WHEN DATE(A.start_date) = '".$start."' THEN B.double_pax_price 

    					ELSE '0'

					END) AS double_pax_sum,

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($start))."' THEN B.single_pax_price 

    					ELSE '0'

					END) AS single_pax_win,					

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($start))."' THEN B.double_pax_price 

    					ELSE '0'

					END) AS double_pax_win,

					SUM(CASE

    					WHEN DATE(A.start_date) = '".$prevYearStart."' THEN B.single_pax_price 

    					ELSE '0'

					END) AS single_pax_sum_prev,

					

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($prevYearStart))."' THEN B.double_pax_price 

    					ELSE '0'

					END) AS double_pax_sum_prev,					

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($prevYearStart))."' THEN B.single_pax_price 

    					ELSE '0'

					END) AS single_pax_win_prev,

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($prevYearStart))."' THEN B.double_pax_price 

    					ELSE '0'

					END) AS double_pax_win_prev,





					SUM(CASE

    					WHEN DATE(A.start_date) = '".$start."' THEN B.weekend_single_pax_price 

    					ELSE '0'

					END) AS weekend_single_pax_sum,

					SUM(CASE

    					WHEN DATE(A.start_date) = '".$start."' THEN B.weekend_double_pax_price 

    					ELSE '0'

					END) AS weekend_double_pax_sum,

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($start))."' THEN B.weekend_single_pax_price 

    					ELSE '0'

					END) AS weekend_single_pax_win,					

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($start))."' THEN B.weekend_double_pax_price 

    					ELSE '0'

					END) AS weekend_double_pax_win,

					SUM(CASE

    					WHEN DATE(A.start_date) = '".$prevYearStart."' THEN B.weekend_single_pax_price 

    					ELSE '0'

					END) AS weekend_single_pax_sum_prev,

					

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($prevYearStart))."' THEN B.weekend_double_pax_price 

    					ELSE '0'

					END) AS weekend_double_pax_sum_prev,					

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($prevYearStart))."' THEN B.weekend_single_pax_price 

    					ELSE '0'

					END) AS weekend_single_pax_win_prev,

					SUM(CASE

    					WHEN DATE(A.start_date) = '".date('Y-10-01',strtotime($prevYearStart))."' THEN B.weekend_double_pax_price 

    					ELSE '0'

					END) AS weekend_double_pax_win_prev

					

				FROM

				".TBL_RATE." A

				RIGHT JOIN ".TBL_RATE_DETAILS." B	ON A.id=B.rate_id

				LEFT  JOIN ".TBL_ASSIGN_HOTEL_ROOM." C ON B.room_id = C.room_id AND C.hotel_id = ".$rowHotel->hotel_id."

				WHERE C.display_order=1 AND A.id_shop=".$id_shop." AND A.company_id=".$id_company." AND B.hotel_id=".$rowHotel->hotel_id." GROUP BY B.hotel_id,B.room_id,B.rate_plan_id  ";

			$resDetails = mysqli_query($connNew,$sql);

			

			

			while($rowRate=mysqli_fetch_object($resDetails)){

				$head_cntr_column='A';

				$objPHPExcel->getActiveSheet()->mergeCells('A'.$head_hotel_row.':B'.$head_hotel_row); 

				$objPHPExcel->setActiveSheetIndex(0)

				->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_HOTELS,'CONCAT(name,",",city)','WHERE id="'.$rowRate->hotel_id.'" '))					

				  ->setCellValue($head_cntr_column++.$head_hotel_row,'')

				  

				  ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_ROOM_TYPE,'name','WHERE id="'.$rowRate->room_id.'" '))

				  ->setCellValue($head_cntr_column++.$head_hotel_row,$rowRate->single_pax_sum_prev.'/'.$rowRate->double_pax_sum_prev)

				  ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$rowRate->rate_plan_id.'" '))

				  ->setCellValue($head_cntr_column++.$head_hotel_row,$rowRate->single_pax_win_prev.'/'.$rowRate->double_pax_win_prev)

				  ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$rowRate->rate_plan_id.'" '))

				  ->setCellValue($head_cntr_column++.$head_hotel_row,$rowRate->single_pax_sum.'/'.$rowRate->double_pax_sum)

				  ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$rowRate->rate_plan_id.'" '))

				  ->setCellValue($head_cntr_column++.$head_hotel_row,$rowRate->single_pax_win.'/'.$rowRate->double_pax_win)

				  ->setCellValue($head_cntr_column++.$head_hotel_row++,selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$rowRate->rate_plan_id.'" '));	



			}

			

		}

		    

		    

		  $objPHPExcel->getActiveSheet()->getStyle('A8:K'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);

		  $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()->setWrapText(true);

		  $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);

		  $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);



		    $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()



		      ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(8);  



		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(25); 



		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(15); 



		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(15);

		  

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(10);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(10);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(15);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(10);

		  $objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(15);

		   $objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(10);

		   $objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(20);

		   $objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(10);



		    



		    $forTotal = 'C';



		    



		    

		}

		$objPHPExcel->getActiveSheet()->setTitle('Comparison Report');





		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.50);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.10);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.15);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1); 

		$objPHPExcel->getActiveSheet()->getPageSetup()

		  ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		$objPHPExcel->getActiveSheet()->getPageSetup()

		    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		    ob_end_clean();

		    // Redirect output to a client’s web browser (Excel2007)

		header('Content-Type: application/vnd.ms-excel');

		header('Content-Disposition: attachment;filename="Comparison_Report '.date('d-m-Y H:i:s').'.xls"');

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

	//Comparison Report End



	



	//Hotel wise contract report

	function hotelWiseContractReport($cron,$id_shopCron,$hotelIdCron,$seasonIdCron,$db,$objPHPExcel){

		$cond='';

		$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($id_shopCron)."'");

		$rowShop = $db->fetch_object2($resShop);

		$logo	=	$rowShop->image;

	 	$NewHotel_id	= $hotelIdCron;



		$weekEndAvailable = selectColumn(TBL_HOTELS,'excel_display_weekday','WHERE id_shop="'.$id_shopCron.'" AND id="'.$NewHotel_id.'" ');



		$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($id_shopCron)."'");



		$rowShop = $db->fetch_object2($resShop);





		

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

			$signature = '/home/inroomhub/public_html/sync/uploaded_files/shop/'.$logo;

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

		$HotelSql =  executeSQl("SELECT * FROM `".TBL_HOTELS."` WHERE `".TBL_HOTELS."`.`id_shop` = '".addslashes($id_shopCron)."'  ".$condHotel." order by ".TBL_HOTELS.".id asc");



		$HotelRecords=	$db->fetch_object2($HotelSql);	



		if(!isset($cron)){

			$rateSql="SELECT `".TBL_RATE_DETAILS."`.*,`".TBL_RATE."`.*, `".TBL_RATE."`.id  as detail_id FROM `".TBL_RATE_DETAILS."` join `".TBL_RATE."` on fs_rate.id=fs_rate_details.rate_id  LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE.".company_id  WHERE 1=1 AND `".TBL_RATE."`.status=1 AND FIND_IN_SET(cmp.area,'".$_SESSION['teamMemberAreas']."') ".$cond."  group by ".TBL_RATE.".id  order by ".TBL_RATE.".seasonId ,cmp.name asc";	

		}

		else{

			$rateSql="SELECT `".TBL_RATE_DETAILS."`.*,`".TBL_RATE."`.*, `".TBL_RATE."`.id  as detail_id FROM `".TBL_RATE_DETAILS."` join `".TBL_RATE."` on fs_rate.id=fs_rate_details.rate_id  LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE.".company_id  WHERE 1=1 AND `".TBL_RATE."`.status=1 AND ".TBL_RATE.".company_id!=0  ".$cond."  group by ".TBL_RATE.".id  order by ".TBL_RATE.".seasonId ,cmp.name asc";	

		}

		



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

$city = selectColumn(TBL_COMPANY,'city'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");

					$Company=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'").','.$city;



					$id_default_group=selectColumn(TBL_COMPANY,'id_default_group'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");



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



				$Date	=	dateformat_date($RatecheckCountRecords->date_created);	



			



				$RateFetchSql ="SELECT DISTINCT `".TBL_RATE_DETAILS."`.* FROM `".TBL_RATE_DETAILS."` LEFT JOIN `".TBL_ASSIGN_HOTEL_ROOM."` ON `".TBL_RATE_DETAILS."`.room_id = `".TBL_ASSIGN_HOTEL_ROOM."`.room_id  WHERE  fs_rate_details.rate_id=".$RatecheckCountRecords->id."   and `".TBL_RATE_DETAILS."`.`hotel_id` in (".$HotelRecords->id.") order by `".TBL_ASSIGN_HOTEL_ROOM."`.display_order asc";	





				$RateSql 	=  executeSQl($RateFetchSql);



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

					->setCellValue('J'.$con, ($RateDetailsRecords->lunch_price!=0?$RateDetailsRecords->lunch_price:'Rate on Request'))

					->setCellValue('K'.$con, ($RateDetailsRecords->dinner_price!=0?$RateDetailsRecords->dinner_price:'Rate on Request'));

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

		$HotelSql =  executeSQl("SELECT * FROM `".TBL_HOTELS."` WHERE `".TBL_HOTELS."`.`id_shop` = '".addslashes($id_shopCron)."'  ".$condHotel." order by ".TBL_HOTELS.".id asc");



		$HotelRecords=	$db->fetch_object2($HotelSql);	



		if(!isset($cron)){

			$rateSql="SELECT `".TBL_RATE_DETAILS_UNIT."`.*,`".TBL_RATE_UNIT."`.*, `".TBL_RATE_UNIT."`.id  as detail_id FROM `".TBL_RATE_DETAILS_UNIT."` join `".TBL_RATE_UNIT."` on `".TBL_RATE_UNIT."`.id=`".TBL_RATE_DETAILS_UNIT."`.rate_id  LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE_UNIT.".company_id  WHERE 1=1 AND FIND_IN_SET(cmp.area,'".$_SESSION['teamMemberAreas']."') ".$cond."  group by ".TBL_RATE_UNIT.".id  order by ".TBL_RATE_UNIT.".seasonId ,cmp.name asc";	

		}

		else{

			$rateSql="SELECT `".TBL_RATE_DETAILS_UNIT."`.*,`".TBL_RATE_UNIT."`.*, `".TBL_RATE_UNIT."`.id  as detail_id FROM `".TBL_RATE_DETAILS_UNIT."` join `".TBL_RATE_UNIT."` on `".TBL_RATE_UNIT."`.id=`".TBL_RATE_DETAILS_UNIT."`.rate_id  LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE_UNIT.".company_id  WHERE 1=1 AND ".TBL_RATE_UNIT.".company_id!=0  ".$cond."  group by ".TBL_RATE_UNIT.".id  order by ".TBL_RATE_UNIT.".seasonId ,cmp.name asc";	

		}

		



		$RatecheckCountSql =  executeSQl($rateSql);

		$HoteNumValue	=	$db->num_rows2($RatecheckCountSql);





		if($HoteNumValue>0){

			$fetchHotelName = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$HotelRecords->id."'");

			$fetchHotelCity =selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$HotelRecords->id."'");



			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':N'.$con);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':N'.$con)->applyFromArray($styleThinBlackBorderOutline);

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

$city = selectColumn(TBL_COMPANY,'city'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");

					$Company=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'").','.$city;



					$id_default_group=selectColumn(TBL_COMPANY,'id_default_group'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");



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



				$Date	=	dateformat_date($RatecheckCountRecords->date_created);	



			



				$RateFetchSql ="SELECT DISTINCT `".TBL_RATE_DETAILS_UNIT."`.* FROM `".TBL_RATE_DETAILS_UNIT."` LEFT JOIN `".TBL_ASSIGN_HOTEL_ROOM."` ON `".TBL_RATE_DETAILS_UNIT."`.room_id = `".TBL_ASSIGN_HOTEL_ROOM."`.room_id  WHERE  `".TBL_RATE_DETAILS_UNIT."`.rate_id=".$RatecheckCountRecords->id."   and `".TBL_RATE_DETAILS_UNIT."`.`hotel_id` in (".$HotelRecords->id.") order by `".TBL_ASSIGN_HOTEL_ROOM."`.display_order asc";	





				$RateSql 	=  executeSQl($RateFetchSql);



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



				$remarks = selectColumn(TBL_RATE_DETAILS_UNIT,'hotel_remarks','WHERE rate_id="'.$RatecheckCountRecords->rate_id.'" ');	



				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$con,$credTxt);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$con,$remarks);



				while($RateDetailsRecords=	$db->fetch_object2($RateSql)){

		

					$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A'.$con, $InCount++)

						->setCellValue('B'.$con, $Company);

	



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

					->setCellValue('J'.$con, ($RateDetailsRecords->lunch_price!=0?$RateDetailsRecords->lunch_price:'Rate on Request'))

					->setCellValue('K'.$con, ($RateDetailsRecords->dinner_price!=0?$RateDetailsRecords->dinner_price:'Rate on Request'));

					

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

		//===========Rate UNIT END===================================================================>	





		

		/*paste here*/

		$con++;

		$seasonSql = "SELECT * FROM ".TBL_RATE_SEASON." WHERE id IN (".$seasonIdCron.") ORDER BY id";

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

			$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->applyFromArray($styleThinBlackBorderOutline);

		

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

			$objPHPExcel->getActiveSheet()->getStyle('A'.$boldCon.':D'.($con-1))->applyFromArray($styleThinBlackBorderOutline);

			

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

				

		$setcellcount	=	$con;

		//Protecting File

		$objPHPExcel->getSecurity()->setLockWindows(true);

        $objPHPExcel->getSecurity()->setLockStructure(true);

        $objPHPExcel->getSecurity()->setWorkbookPassword("FreeBlocking");

        $objPHPExcel->getActiveSheet()->getProtection()->setPassword('FreeBlocking');

        $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);

        // This should be enabled in order to enable any of the following!

        $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);

        $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);			

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

		}	

		else{

			if($HoteNumValue>0){

				$seasonName = selectColumn(TBL_RATE_SEASON,'name','WHERE id="'.$seasonIdCron.'" ');

				$fileName=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$HotelRecords->id."'").' Contracted Rate Report for '.ucwords(strtolower($seasonName));;

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

					//local

					//$objWriter->save('../mailattach/'.$fileName.'.xls');

					

					//cron server

				$objWriter->save('/home/inroomhub/public_html/sync/adminpanel/mailattach/'.$fileName.'.xls');

			}

		}

	}	

	// Hotel Wise Contract Report End



	

	//Executive Portfoilo Report Yearly

	function executivePortFolioYearlyReport($cron,$id_shop,$id_user,$fy_date,$fileName="",$conn="",$objPHPExcel=""){

		
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

		$end = date('Y-m-d',strtotime($fy_date));



		$prevYear = strtotime($date);

		$prevYear = strtotime("-1 year",$prevYear);

		$prevYear = date('Y-04-01',$prevYear);

		$prevYearEnd = date('Y-m-d',strtotime('+1 years',strtotime($prevYear)));

		

		$prevYear2 = strtotime($date);

		$prevYear2 = strtotime("-2 year",$prevYear2);

		$prevYear2 = date('Y-04-01',$prevYear2);

		$prevYear2End = date('Y-m-d',strtotime('+1 years',strtotime($prevYear2)));

		

		$sql = "SELECT  a.id_company,a.id_default_group,a.name,b.name AS area,c.id as id_user,c.name AS executive,c.id AS id_executive  FROM `".TBL_COMPANY."` AS a


			LEFT JOIN `".TBL_AREAS."` AS b ON a.area=b.id

			LEFT JOIN `".TBL_USERS."` AS c ON FIND_IN_SET(c.id,b.ids_unit_user)
			WHERE a.`id_shop` = '".addslashes($id_shop)."' ";



		$sql .= " AND c.id = '".addslashes($id_user)."' ";
		

/*$sql = "SELECT  a.id_company,a.id_default_group,a.name,b.name AS area,c.id as id_user,c.name AS executive  FROM `".TBL_COMPANY."` AS a

			LEFT JOIN `".TBL_AREAS."` AS b ON a.area=b.id

			LEFT JOIN `".TBL_USERS."` AS c ON b.user_id=c.id

			WHERE a.`id_shop` = '".addslashes($id_shop)."' ";



		echo $sql .= " AND c.id = '".addslashes($id_user)."' AND id_company IN (".$id_Company_array.") ";

		*/

		//die;



		$res = mysqli_query($conn,$sql);



		if($res){

			$numRows = mysqli_num_rows($res);

		}



		$numRows;



		/* setting dates for Achieved values*/



		
		

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

						->setCellValue('A6', 'Executive Portfoilo - RSO Report Year  :'.$period);



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

			
			//JUMP
			mysqli_query($conn,"DELETE FROM temp_executive_port WHERE id_executive='".$id_user."' ");
							
			

			$tempTableArr=array();
			while($row = mysqli_fetch_object($res)){

				

				$achieved =selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$date."' and '".$end."' and id_shop='".$id_shop."' and id_company='".$row->id_company."'    ");



				$budget = selectColumn(TBL_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."' AND `from`= '".$date."' AND `to`= '".date('Y-03-31',strtotime($end))."' AND id_user='".$id_user."' ");



				$prevAch2 = selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear2."' and '".$prevYear2End."' and id_shop='".$id_shop."' and id_company='".$row->id_company."'    ");

				$prevAch = selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear."' and '".$prevYearEnd."' and id_shop='".$id_shop."' and id_company='".$row->id_company."'    ");



				

				$sumSql = "SELECT rate_name FROM ".TBL_RATE."  WHERE id_shop='".$id_shop."' AND company_id='".$row->id_company."' AND date(start_date)='".date('Y-04-01',strtotime($date))."' ";

				



				$winSql = "SELECT  rate_name FROM ".TBL_RATE."  WHERE id_shop='".$id_shop."' AND company_id='".$row->id_company."' AND date(start_date)='".date('Y-10-01',strtotime($date))."' ";



				$yearSql="SELECT  rate_name FROM ".TBL_RATE."  WHERE id_shop='".$id_shop."' AND company_id='".$row->id_company."' AND (date(start_date)='".date('Y-01-01',strtotime($date))."' AND date(end_date)='".date('Y-12-31',strtotime($date))."' ) ";



				$resSum = mysqli_query($conn,$sumSql);	

				$resWin = mysqli_query($conn,$winSql);

				$resYear = mysqli_query($conn,$yearSql);



				$sumRow=mysqli_fetch_object($resSum);

				$winRow=mysqli_fetch_object($resWin);	

				$yearRow=mysqli_fetch_object($resYear);	

				$v2b = ($achieved-$budget);

				

				

			//	$sqlDate ="SELECT dated FROM `".TBL_DAILYVISIT."` WHERE id_company =".$row->id_company." Order BY dated desc  LIMIT 5 "; 
//JUMP
$sqlDate ="SELECT * FROM `".TBL_DAILYVISIT."` WHERE id_company =".$row->id_company." and id_user =".$row->id_user." Order BY dated desc  LIMIT 5 "; 

		        $resDate = mysqli_query($conn,$sqlDate);


		        $tempTableCount = 0;
		        $tempTableVisit = array();
		        if($resDate){ 

		        	//$dateCol = 'N';

		        	

		        	while($resData = mysqli_fetch_object($resDate)){

		        		$tempTableVisit[$tempTableCount] = date('Y-m-d',strtotime($resData->dated));

				       $tempTableCount++;	

		        	}



		        }	

		        
		        $companyGroupName = selectColumn(TBL_GROUP,'name'," WHERE id_group='".$row->id_default_group."' ");
		        
		        array_push($tempTableArr,' INSERT INTO temp_executive_port VALUES('.$Serialno++.',"'.$row->id_executive.'","'.$row->executive.'","'.str_replace('&amp;','&', $row->name).'","'.trim($companyGroupName).'","'.$row->area.'","'.$sumRow->rate_name.'","'.$winRow->rate_name.'","'.$yearRow->rate_name.'","'.$prevAch2.'","'.$prevAch.'","'.$achieved.'","'.$budget.'","'.$v2b.'","'.$tempTableVisit[0].'","'.$tempTableVisit[1].'","'.$tempTableVisit[2].'","'.$tempTableVisit[3].'","'.$tempTableVisit[4].'")');

		        		

				

			}
			foreach ($tempTableArr as $key => $query) {
				mysqli_query($conn,$query);
			}
			
			$tempTableFetch = "SELECT * FROM  temp_executive_port where id_executive='".$id_user."' AND (year !=0 OR budg_year!=0 OR visit1!='0000-00-00')  order by year desc";
			
			$fetchRes = mysqli_query($conn,$tempTableFetch);
			$Serial=1;
			while($fetchTempRow = mysqli_fetch_object($fetchRes)){
				
				
				$head_order_data1 = "A";
				$head_order_data = "A";       

				$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A6', ''.$fetchTempRow->executive.' Portfoilo Report Year  :'.$period);

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

		$objPHPExcel->getActiveSheet()->getStyle('B10:B999')

				    ->getAlignment()->setWrapText(true);	



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

				



	}

	//Executive Portfoilo Report End





	//Sales Summary Report

	function salesReport($conn,$objPHPExcel,$session_shop,$fileName,$cron,$teamId,$teamMemberLevel='',$teamMember,$user_id){

		

		

			error_reporting(1);



			if($_REQUEST['report_date'] !=''){

				$fromDate = date('Y-m-d',strtotime($_REQUEST['report_date']));

				$toDate =  date('Y-m-d',strtotime($_REQUEST['report_date']));

			}

			else{

				$fromDate = date('Y-m-d');

				$toDate = date('Y-m-d');

			}



			$mtdfrom = date('Y-m-01',strtotime($fromDate));



			if(date('m',strtotime($fromDate)) <= 3){

				$ytdfrom = date('Y-04-01',strtotime('-1 years',strtotime($fromDate)));

			}

			else{

				$ytdfrom = date('Y-04-01');

			}



			if($_SESSION['userLevel'] !=1){

				if($teamMemberLevel!=1){

					$cond = "AND `".TBL_DAILYVISIT."`.id_user='".$user_id."' " ;

				}

				else{

					$cond = "AND `".TBL_DAILYVISIT."`.id_user IN (".$teamMember.") " ;

				}

			}



			if($teamId!=''){

				$cond.=" AND  FIND_IN_SET ('".$teamId."',".TBL_USERS.".ids_team)";

			}



			$sql = "SELECT `".TBL_DAILYVISIT."`.id_user,".TBL_USERS.".ids_team,

						COUNT(IF(dated BETWEEN '".$fromDate."' AND '".$toDate."',`".TBL_DAILYVISIT."`.id, NULL) ) AS	visit,

						SUM(CASE WHEN (dated BETWEEN '".$fromDate."' AND '".$toDate."') THEN ROUND(Total,0) else 0 end) AS	conveyance,

						SUM(CASE WHEN (dated BETWEEN '".$fromDate."' AND '".$toDate."') THEN ROUND(entertainment,0) else 0 end) AS	entertainment,

						SUM(CASE WHEN (dated BETWEEN '".$fromDate."' AND '".$toDate."') THEN ROUND(lunch,0) else 0 end) AS	lunch,

						COUNT(IF(dated BETWEEN '".$mtdfrom."' AND '".$toDate."',`".TBL_DAILYVISIT."`.id, NULL)) AS	visitMtd,

						SUM(CASE WHEN (dated BETWEEN '".$mtdfrom."' AND '".$toDate."') THEN ROUND(Total,0) else 0 end) AS	conveyanceMtd,

						SUM(CASE WHEN (dated BETWEEN '".$mtdfrom."' AND '".$toDate."') THEN ROUND(entertainment,0) else 0 end) AS	entertainmentMtd,

						SUM(CASE WHEN (dated BETWEEN '".$mtdfrom."' AND '".$toDate."') THEN ROUND(lunch,0) else 0 end) AS	lunchMtd,

						COUNT(IF(dated BETWEEN '".$ytdfrom."' AND '".$toDate."',`".TBL_DAILYVISIT."`.id, NULL)) 			AS	visitYtd,

						SUM(CASE WHEN (dated BETWEEN '".$ytdfrom."' AND '".$toDate."') THEN ROUND(Total,0) else 0 end) AS	conveyanceYtd,

						SUM(CASE WHEN (dated BETWEEN '".$ytdfrom."' AND '".$toDate."') THEN ROUND(entertainment,0) else 0 end) AS	entertainmentYtd,

						SUM(CASE WHEN (dated BETWEEN '".$ytdfrom."' AND '".$toDate."') THEN ROUND(lunch,0) else 0 end) AS	lunchYtd



						FROM `".TBL_DAILYVISIT."`

						LEFT JOIN ".TBL_USERS." 

						ON `".TBL_DAILYVISIT."`.id_user=".TBL_USERS.".id

						WHERE `".TBL_DAILYVISIT."`.id_shop=".$session_shop." ".$cond." GROUP BY `".TBL_DAILYVISIT."`.id_user ORDER BY ".TBL_USERS.".ids_team,".TBL_USERS.".name";		

						

				





				$res = mysqli_query($conn,$sql);

				if($res){

					$numRows = mysqli_num_rows($res);

				}

			

			

			

			// Set document properties

			$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")

										 ->setLastModifiedBy("Hitesh Aloney")

										 ->setTitle("Company Portfoilo Report")

										 ->setSubject("Company Portfoilo Report")

										 ->setDescription("Company Portfoilo Report")

										 ->setKeywords("Company Portfoilo Report")

										 ->setCategory("Report");



			$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing

			$objDrawing->setName('Logo');       //set name to image

			$objDrawing->setDescription('Logo'); //set description to image

			$logo = selectColumn('fs_shop','image'," WHERE  id=".$session_shop." ");

			

			if($cron!=''){

				$signature = "/home/inroomhub/public_html/sync/uploaded_files/shop/".$logo.""; 

			}

			else{

				$signature = "../uploaded_files/shop/".$logo.""; 

			}



		   //Path to signature .jpg file

			if(file_exists($signature)){

				$objDrawing->setPath($signature);

				$objDrawing->setOffsetX(20);                       //setOffsetX works properly

				$objDrawing->setOffsetY(10);                       //setOffsetY works properly

				$objDrawing->setCoordinates('G1');        //set image to cell

				/*$objDrawing->setWidth(300);                 //set width, height

				$objDrawing->setHeight(130);*/  

				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));

			}  //save										 

			

			if($numRows > 0){

				$counter = 1;

				$objPHPExcel->setActiveSheetIndex(0)

				->setCellValue('A6', 'Sales Summary Report As On '.date('d-M-Y',strtotime($fromDate)));

				$objPHPExcel->getActiveSheet()->mergeCells('A6:M6');

				$objPHPExcel->getActiveSheet()->mergeCells('A7:A8');

				$objPHPExcel->getActiveSheet()->mergeCells('B7:B8');

				$objPHPExcel->getActiveSheet()->mergeCells('C7:C8');

				$objPHPExcel->getActiveSheet()->mergeCells('D7:H7');

				$objPHPExcel->getActiveSheet()->mergeCells('I7:M7');

				$head_hotel_row = 7;

				$head_cntr_column = "A";$head_hotel_column = "A";

				



				$objPHPExcel->setActiveSheetIndex(0)

					->setCellValue('A'.$head_hotel_row, 'S.No.')

					->setCellValue('B'.$head_hotel_row, 'Team')

					->setCellValue('C'.$head_hotel_row, 'Executive')

					->setCellValue('D'.$head_hotel_row, 'Month to date')

					->setCellValue('I'.$head_hotel_row++, 'Year to date');



				$objPHPExcel->setActiveSheetIndex(0)

					->setCellValue('D8', 'Visit')

					->setCellValue('E8', 'Conveyance')

					->setCellValue('F8', 'Entertainment')

					->setCellValue('G8', 'Lunch')

					->setCellValue('H8', 'Total Expense')

					->setCellValue('I8', 'Visit')

					->setCellValue('J8', 'Conveyance')

					->setCellValue('K8', 'Entertainment')

					->setCellValue('L8', 'Lunch')

					->setCellValue('M8', 'Total Expense');

						



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

			



				cellColor('A6:M6','254061');



				cellColor('A7:M8','75923c');









				$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

				 $objPHPExcel->getActiveSheet()->getStyle('A6:M6')->getAlignment()->applyFromArray(

				    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

				);



				$objPHPExcel->getActiveSheet()->getStyle('A7:M8')->applyFromArray($styleArray_1);

				 $objPHPExcel->getActiveSheet()->getStyle('A7:M8')->getAlignment()->applyFromArray(

				    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

				);





				$objPHPExcel->getActiveSheet()->getStyle('A7:A7')->getAlignment()->applyFromArray(

				    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)

				);

				  $objPHPExcel->getActiveSheet()->getStyle('B7:B8')->getAlignment()->applyFromArray(

				    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)

				);

				  $objPHPExcel->getActiveSheet()->getStyle('C7:C8')->getAlignment()->applyFromArray(

				    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)

				);







				$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

				    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

				);

				$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(

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

			



				/*$objPHPExcel->getActiveSheet()->getStyle('C11:K11')->getAlignment()->applyFromArray(

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







				

			

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(20);	

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(22);	

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(10);

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(18);	

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(10);	

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(20);	

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(15);	

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(18);	

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(20);	

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(10);		

				$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(20);

				$head_hotel_row++;

								

								

									

					$visit =0;

					$conveyance =0;

					$entertainment =0;

					$lunch =0;



					$visitMtd =0;

					$conveyanceMtd =0;

					$entertainmentMtd =0;

					$lunchMtd =0;



					$visitYtd =0;

					$conveyanceYtd =0;

					$entertainmentYTd =0;

					$lunchYtd =0;

				

					$Serialno=1;

					$connew = 9;

					while($row = mysqli_fetch_object($res)){

					

						$teamName = selectColumn(TBL_TEAM,'name','where id="'.$row->ids_team.'" ');

						



						$head_order_data1 = "A";

						$head_order_data = "A";       

						$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue($head_order_data++ . $connew, $Serialno++)

						->setCellValue($head_order_data++ . $connew, ucwords($teamName))

						->setCellValue($head_order_data++ . $connew, ucwords(selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_user."' ")))

						->setCellValue($head_order_data++ . $connew, $row->visitMtd)

						->setCellValue($head_order_data++ . $connew, $row->conveyanceMtd)

						->setCellValue($head_order_data++ . $connew, $row->entertainmentMtd)

						->setCellValue($head_order_data++ . $connew, $row->lunchMtd)

						->setCellValue($head_order_data++ . $connew, ($row->entertainmentMtd+$row->conveyanceMtd+$row->lunchMtd))

						->setCellValue($head_order_data++ . $connew, $row->visitYtd)

						->setCellValue($head_order_data++ . $connew, $row->conveyanceYtd)

						->setCellValue($head_order_data++ . $connew, $row->entertainmentYtd)

						->setCellValue($head_order_data++ . $connew, $row->lunchYtd)

						->setCellValue($head_order_data++ . $connew, ($row->lunchYtd+$row->entertainmentYtd+$row->conveyanceYtd));



						$objPHPExcel->getActiveSheet()->getStyle('A6:M'.$connew)->applyFromArray($styleThinBlackBorderOutline);



						

						$visitMtd +=$row->visitMtd;

						$conveyanceMtd +=$row->conveyanceMtd;

						$entertainmentMtd +=$row->entertainmentMtd;

						$lunchMtd +=$row->lunchMtd;

						$visitYtd +=$row->visitYtd;

						$conveyanceYtd +=$row->conveyanceYtd;

						$entertainmentYTd +=$row->entertainmentYtd;

						$lunchYtd +=$row->lunchYtd;

				

						$connew++;	

					}	

				$forTotal = 'D';

				$totalArray = array(

			    'font'  => array(

			        'bold'  => true,

			        'color' => array('rgb' => '1e51bf'),

			        'size'  => 12,

			        'name'  => 'Verdana'

			    ));

				cellColor('A'.$connew.':M'.$connew,'cbf9b8');

			    $objPHPExcel->getActiveSheet()->mergeCells('A'.$connew.':C'.$connew);

				$objPHPExcel->getActiveSheet(0)->setCellValue('A'.$connew,'Grand Total');

				$objPHPExcel->getActiveSheet(0)->setCellValue('D'.$connew,$visitMtd);

				$objPHPExcel->getActiveSheet(0)->setCellValue('E'.$connew,$conveyanceMtd);

				$objPHPExcel->getActiveSheet(0)->setCellValue('F'.$connew,$entertainmentMtd);

				$objPHPExcel->getActiveSheet(0)->setCellValue('G'.$connew,$lunchMtd);

				$objPHPExcel->getActiveSheet(0)->setCellValue('H'.$connew,($conveyanceMtd+$entertainmentMtd+$lunchMtd));

				$objPHPExcel->getActiveSheet(0)->setCellValue('I'.$connew,$visitYtd);

				$objPHPExcel->getActiveSheet(0)->setCellValue('J'.$connew,$conveyanceYtd);

				$objPHPExcel->getActiveSheet(0)->setCellValue('K'.$connew,$entertainmentYTd);

				$objPHPExcel->getActiveSheet(0)->setCellValue('L'.$connew,$lunchYtd);

				$objPHPExcel->getActiveSheet(0)->setCellValue('M'.$connew,($conveyanceYtd+$entertainmentYTd+$lunchYtd));

				$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($totalArray);

				$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($styleThinBlackBorderOutline);

			

			}

			$objPHPExcel->getActiveSheet()->setTitle('Sales Summary Report');



			

			$objPHPExcel->setActiveSheetIndex(0);



			ob_end_clean();

			// Redirect output to a client’s web browser (Excel2007)

			if(!isset($cron)){

			header('Content-Type: application/vnd.ms-excel');



			header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');

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

				if($numRows > 0){

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

					//local

					//$objWriter->save('../mailattach/'.$fileName.'.xls');

					

					//server

					$objWriter->save('/home/inroomhub/public_html/sync/adminpanel/mailattach/'.$fileName.'.xls');	

				}

			}

		

	} 

	//sales summary end





	//executive log report

	function activityLogReport($checkin,$checkout,$id_user,$id_shop,$cron,$connNew,$objPHPExcel,$fileName){

		$dataExits=0;



	    if(isset($_REQUEST['usernameid'])){

	      $sql .= " AND `id_user` = '".addslashes($_REQUEST['usernameid'])."'";

	      $exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$_REQUEST['usernameid'].'" ');

	    }

	    elseif(isset($cron)){

	      $sql .= " AND `id_user` = '".$id_user."'";

	      echo $exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$id_user.'" ');

	      

	    }

	    else{

	      $sql .= " AND `id_user` = '".$_SESSION['userId']."'";

	      $exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ');

	    }

	      

	    $objPHPExcel->getProperties()->setCreator("Hitesh Aloney")

	                   ->setLastModifiedBy("Hitesh Aloney")

	                   ->setTitle("LOG REPORT")

	                   ->setSubject("LOG REPORT")

	                   ->setDescription("LOG REPORT")

	                   ->setKeywords("LOG REPORT")

	                   ->setCategory("Report");

	         



	    $objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing

	    $objDrawing->setName('Logo');        //set name to image

	    $objDrawing->setDescription('Logo'); //set description to image

	    $logo = selectColumn('fs_shop','image'," WHERE  id=".$id_shop." ");

	    $signature = "../uploaded_files/shop/".$logo."";  



        //Path to signature .jpg file



	    if($cron!=''){

	      $path = getcwd().'/public_html/sales';

	      //server

	      $signature = $path."/uploaded_files/shop/".$logo.""; 

	      //local

	      //$signature = "../../uploaded_files/shop/".$logo.""; 

	    }

	    else{

	      $signature = "../uploaded_files/shop/".$logo.""; 

	    }

	    



	    if(file_exists($signature)){

		    $objDrawing->setPath($signature);

		    $objDrawing->setOffsetX(25);                       

		    $objDrawing->setOffsetY(10);                       

		    $objDrawing->setCoordinates('D2');        

		    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());



	    }



	    if($numRows == 0){



	      $counter = 1;



	      $objPHPExcel->setActiveSheetIndex(0)



	      ->setCellValue('A8', 'Activity Log Report from '.date('d-F',strtotime($checkin)).' to '.date('d-F-Y',strtotime($checkout)))

	      ->setCellValue('A6', 'Executive Name : ')

	      ->setCellValue('B6', $exeName);

	      $objPHPExcel->getActiveSheet()->getStyle("A6:B6")->getFont()->setBold( true );



	      $objPHPExcel->getActiveSheet()->mergeCells('A8:D8');



	      $head_hotel_row = 9;



	      $head_cntr_column = "A";$head_hotel_column = "A";



	      $objPHPExcel->setActiveSheetIndex(0)

	        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Date')

	        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Day')

	        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Activity Type')

	        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Activity Description');      



		    $styleArray = array(

		        'font'  => array(

	            'bold'  => true,

	            'color' => array('rgb' => 'FFFFFF'),

	            'size'  => 14,

		        'text-transform'=>'uppercase',

	            'name'  => 'Calibri'

	        ));







	    	$styleArray_1 = array(

		        'font'  => array(

	            'bold'  => true,

	            'color' => array('rgb' => '000'),

	            'size'  => 12,

		        'text-transform'=>'uppercase'

	        ));



		  	cellColor('A8:D8','254061');

  		  	cellColor('A9:D9','75923c');



	   		$objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($styleArray);

		   	

		   	$objPHPExcel->getActiveSheet()->getStyle('A8:D8')->getAlignment()->applyFromArray(

	        	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	    	);

	    	

	    	$objPHPExcel->getActiveSheet()->getStyle('A9:D9')->applyFromArray($styleArray_1);



	     	$objPHPExcel->getActiveSheet()->getStyle('A9:D9')->getAlignment()->applyFromArray(

		        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

		    );

	    	$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

		        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

		    );

	   		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(

		        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

		    );



		    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(

		        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

		    );



	    	$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(

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

	     

	      

	      $head_hotel_row=10;

	    	

	    	while(strtotime($checkin)<=strtotime($checkout)){

		        $head_cntr_column='A';

		        if(isset($_REQUEST['usernameid'])){

		          $cond .= " AND `id_user` = '".addslashes($id_user)."'";    

	    	    }

	        	else{

	          	  $cond .= " AND `id_user` = '".$id_user."'";

	        	}



	     	  		$query =" SELECT  'VISIT' AS 'DOC',count(id) AS CHK,CONCAT(count(id),' Visits') AS 'Description','Sales Calls' AS 'ACTIVITY_TYPE'

	       			FROM ".TBL_DAILYVISIT."

	        		WHERE id!='' AND dated='".date('Y-m-d.',strtotime($checkin))."' AND  `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($id_shop)."' ".$cond." 

			        UNION All



	        		SELECT  'VISIT' AS 'DOC',1 AS CHK,description AS Description,".TBL_OTHER_ACTIVITY.".name AS 'ACTIVITY_TYPE' 

			        FROM ".TBL_OTHER." LEFT JOIN ".TBL_OTHER_ACTIVITY." ON ".TBL_OTHER.".id_other_activity=".TBL_OTHER_ACTIVITY.".id

			        WHERE description!='' AND dated='".date('Y-m-d.',strtotime($checkin))."' AND `".TBL_OTHER."`.`id_shop` = '".addslashes($id_shop)."' ".$cond."   



			         ";



	       			$resQue = mysqli_query($connNew,$query);

	       			 $rowCount = mysqli_num_rows($resQue);

		       	while($rowData=mysqli_fetch_object($resQue)){

		        	 $objPHPExcel->setActiveSheetIndex(0)

		           ->setCellValue($head_cntr_column++.$head_hotel_row, date('d/m/Y',strtotime($checkin)))

		           ->setCellValue($head_cntr_column++.$head_hotel_row, date('l',strtotime($checkin)))

		           ->setCellValue($head_cntr_column++.$head_hotel_row,($rowData->CHK==0?'':$rowData->ACTIVITY_TYPE) )

		           ->setCellValue($head_cntr_column++.$head_hotel_row++, ($rowData->CHK==0?'':$rowData->Description)); 

		           $head_cntr_column='A'; 

		              

			    }



	       		$checkin=date('d-m-Y',strtotime('+1 day',strtotime($checkin)));

	    	} 

	         

		    $objPHPExcel->getActiveSheet()->getStyle('A8:D'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);

		    $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()->setWrapText(true);

		    $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);

		    $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);



		    $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()



		        ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



		    $objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(18);  



		    $objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(18); 



		    $objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20); 



		    $objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(75);

	    



	      



		    $forTotal = 'C';



		    $totalArray = array(

		        'font'  => array(

	            'bold'  => true,

	            'color' => array('rgb' => '1e51bf'),

	            'size'  => 12,

	            'name'  => 'Verdana'

	        ));

		}

	

		$objPHPExcel->getActiveSheet()->setTitle('Activity Log Report');

	    $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.50);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.10);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.15);

		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1); 

		$objPHPExcel->getActiveSheet()->getPageSetup()

		  ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);  



		$objPHPExcel->getActiveSheet()->getPageSetup()

		    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		ob_end_clean();



	      // Redirect output to a client’s web browser (Excel2007)

		if(!isset($cron)){

		  header('Content-Type: application/vnd.ms-excel');

		  header('Content-Disposition: attachment;filename="Acitivity_Log_Report'.date('d-m-Y H:i:s').'.xls"');



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

			if($rowCount>0){

			  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

			  //local

			  //$objWriter->save('../mailattach/'.$fileName.'.xls');

			  //server

			  $objWriter->save($path.'/adminpanel/mailattach/'.$fileName.'.xls');

			}      

		}

	}	

	//executive log end





	//Feedback Report

	function feedbackReport($conn,$shop_id,$checkin,$checkout,$id_user,$feedType,$lead_status,$objPHPExcel,$teamMembers,$cron,$fileName,$RsoUserChecked,$UserHotelAccesid){

	

			

		$sql = " SELECT  *  FROM `".TBL_DAILYVISIT_FEEDBACK."`  WHERE `".TBL_DAILYVISIT_FEEDBACK."`.`id_shop` = '".$shop_id."' ";



		if($checkin != '' && $checkout != ''){	

		$sql .= " AND `".TBL_DAILYVISIT_FEEDBACK."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";

		}



		if($id_user != ''){

			$sql .= " AND `".TBL_DAILYVISIT_FEEDBACK."`.`id_user` = '".addslashes($id_user)."'";

		}

		else{

		   /* if($RsoUserChecked!='' && $RsoUserChecked=='1'){

    			if(isset($_SESSION['teamMemberLevel']) && $_REQUEST['usernameid']==''){

    				$sql .= " AND `".TBL_DAILYVISIT_FEEDBACK."`.`id_user` IN (".$teamMembers.") ";

    			}

    			else{

    				$sql .= " AND `".TBL_DAILYVISIT_FEEDBACK."`.`id_user` IN (".$teamMembers.") ";

    			}

    		}*/

    		

    		 if($RsoUserChecked=='' && $UserHotelAccesid==''){

    			if(isset($_SESSION['teamMemberLevel']) && $_REQUEST['usernameid']==''){

    				$sql .= " AND `".TBL_DAILYVISIT_FEEDBACK."`.`id_user` IN (".$teamMembers.") ";

    			}

    			else{

    				$sql .= " AND `".TBL_DAILYVISIT_FEEDBACK."`.`id_user` IN (".$teamMembers.") ";

    			}

    		}

    		

    		

		 

		}

		if($RsoUserChecked!='' && $RsoUserChecked=='2'){

		    if($UserHotelAccesid!=''){

		        	$sql .= " AND `".TBL_DAILYVISIT_FEEDBACK."`.`hotel_id` IN (".$UserHotelAccesid.") ";

		        	

		    }

		    

		}



		if($feedType != ''){

			$sql .= " AND `".TBL_DAILYVISIT_FEEDBACK."`.`conclusion_type` = '".addslashes($feedType)."'";

		}





		if($lead_status != ''){

			$sql .= " AND `".TBL_DAILYVISIT_FEEDBACK."`.`lead_status` = '".$lead_status."'";

		}	

				

				

		   $sql .=  "  ORDER BY date_created DESC";

		

		

		$res = mysqli_query($conn,$sql);



		if($res){

			$numRows = mysqli_num_rows($res);

		}

			

		// Set document properties



		$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")

					->setLastModifiedBy("Hitesh Aloney")

 				 	->setTitle("Conveyance Report")

					->setSubject("Conveyance Report")

	   			    ->setDescription("Conveyance Report")

				 	->setKeywords("Conveyance Report")

					->setCategory("Report");

		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing

		$objDrawing->setName('Logo');       //set name to image

		$objDrawing->setDescription('Logo'); //set description to image

		$logo = selectColumn('fs_shop','image'," WHERE  id=".$shop_id." ");

		

		if(!isset($cron)){

			$signature = "../uploaded_files/shop/".$logo.""; 

		}

		else{

		   //Path to signature .jpg file

			//local

			//$signature = "../../uploaded_files/shop/".$logo."";

			//server cron

			$signature = "/home/inroomhub/public_html/sync/uploaded_files/shop/".$logo.""; 

		}	



		if(file_exists($signature)){

			$objDrawing->setPath($signature);

			$objDrawing->setOffsetX(25);                       //setOffsetX works properly

			$objDrawing->setOffsetY(10);                       //setOffsetY works properly

			$objDrawing->setCoordinates('E1');        //set image to cell

			//$objDrawing->setWidth(200);                 //set width, height



			//$objDrawing->setHeight(120);  

			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		}	



		if($numRows > 0){

			$counter = 1;

			if(!isset($cron)){

				$objPHPExcel->setActiveSheetIndex(0)

							->setCellValue('A6', 'Feed Back Report  from '.$_REQUEST['report_date']);

			}

			else{

				$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A6', 'Feed Back Report  '.date('M-Y',strtotime($checkin)).' To '.date('M-Y',strtotime($checkout)));

			}	





			$objPHPExcel->getActiveSheet()->mergeCells('A6:G6');

			$head_hotel_row = 7;

			$head_cntr_column = "A";$head_hotel_column = "A";

			$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Date')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Hotel')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Company')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive')

						//->setCellValue($head_cntr_column++.$head_hotel_row, 'Assigned On')

						//->setCellValue($head_cntr_column++.$head_hotel_row, 'Assigned To')

						//->setCellValue($head_cntr_column++.$head_hotel_row, 'Follow up Date')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Type')

						->setCellValue($head_cntr_column++.$head_hotel_row, 'Feedback Summary');



				//->setCellValue($head_cntr_column++.$head_hotel_row, 'Status');			

			$styleArray = array(

			    'font'  => array(

		        'bold'  => true,

		        'color' => array('rgb' => 'FFFFFF'),

		        'size'  => 14,

				'text-transform' => 'uppercase',

		        'name'  => 'Calibri'

		    ));







			$styleArray_1 = array(

			    'font'  => array(

		        'bold'  => true,

		        'color' => array('rgb' => '000'),

		        'size'  => 10,

				'text-transform'=>'uppercase'

		    ));

			cellColor('A6:G6','254061');

			cellColor('A7:G7','75923c');



			$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

			$objPHPExcel->getActiveSheet()->getStyle('A6:G6')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('A7:G7')->applyFromArray($styleArray_1);

			 $objPHPExcel->getActiveSheet()->getStyle('A7:G7')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);



			$objPHPExcel->getActiveSheet()->getStyle('C7')->getAlignment()->applyFromArray(

		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);



			$objPHPExcel->getActiveSheet()->getStyle('D7')->getAlignment()->applyFromArray(

		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);



			$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->applyFromArray(

		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);







			$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->applyFromArray(

		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);







			$objPHPExcel->getActiveSheet()->getStyle('G7')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);



			$objPHPExcel->getActiveSheet()->getStyle('H7')->getAlignment()->applyFromArray(

		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);



			$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(

		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);



			$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(

		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)



			);



			$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(

		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);



			$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(

		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

			);



			$styleThinBlackBorderOutline = array(

				'borders' => array(

				'allborders' => array(

				'style' => PHPExcel_Style_Border::BORDER_THIN,

				'color' => array('argb' => '000'),

				),

				),

			);	



			$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);

			$objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()

					    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



			$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(6);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(15);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(30);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(25);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(60);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(0);



			$head_hotel_row++;

			$Serialno=1;

			$connew = 8;



			while($row = mysqli_fetch_object($res)){



				$FollowupHotelName	=	selectColumn(TBL_HOTELS,'concat(name,",",city)'," WHERE `id` = '".$row->hotel_id."'");

				$id_company = selectColumn(TBL_VISIT,'id_company'," WHERE `id` = '".$row->visit_id."'");

				$FollowupCompanyName	=	selectColumn(TBL_COMPANY,'concat(name,",",city)'," WHERE `id_company` = '".$id_company."'");



				$feedSql = "SELECT  `".TBL_FEEDBACK_DETAILS_EXPLOAD."`.*  FROM `".TBL_FEEDBACK_DETAILS_EXPLOAD."`  WHERE `".TBL_FEEDBACK_DETAILS_EXPLOAD."`.`id_shop` = ".$shop_id." AND `details_id` = '".$row->id."' AND `visit_id` = '".$row->visit_id."' ";



				$NextFolloupBAckUpSql = mysqli_query($conn,$feedSql);



				if(mysqli_num_rows($NextFolloupBAckUpSql) > 0){

					$FeedBackupNext=1;

					

					while($NextFollowUPBackRow = mysqli_fetch_assoc($NextFolloupBAckUpSql)){

						$ExecutiveName	=	selectColumn(TBL_USERS,'name'," WHERE `id` =".$NextFollowUPBackRow['id_user']." and id_shop=".$shop_id." ");



						$ExecutiveAssignToName	=	selectColumn(TBL_USERS,'name'," WHERE `id` =".$NextFollowUPBackRow['assign_user_id']." and id_shop=".$shop_id." ");



						$head_order_data1 = "A";



						$head_order_data = "A";       



						if($row->conclusion_type==1){ 

							$type= "Positive";

						}	

						else if($row->conclusion_type==2){

							$type= "Negative";

						}

						else{ 

							$type= "";

						}	



						$objPHPExcel->setActiveSheetIndex(0)

									->setCellValue($head_order_data++ . $connew, $Serialno++)

									->setCellValue($head_order_data++ . $connew, date('d-M-Y',strtotime($row->dated)))

									->setCellValue($head_order_data++ . $connew, $FollowupHotelName)

									->setCellValue($head_order_data++ . $connew, $FollowupCompanyName)

									->setCellValue($head_order_data++ . $connew, $ExecutiveName)

									->setCellValue($head_order_data++ . $connew, $type)

									->setCellValue($head_order_data . $connew, $NextFollowUPBackRow['summary']);

						$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$head_hotel_row.':'.$head_order_data .$connew)->applyFromArray($styleThinBlackBorderOutline);



						$objPHPExcel->getActiveSheet()->getStyle('A6:F6')->applyFromArray($styleThinBlackBorderOutline);



						$objPHPExcel->getActiveSheet()->getStyle('A7:F7')->applyFromArray($styleThinBlackBorderOutline);



						$connew++;	

					}

				}

			}



			$forTotal = 'C';



			$totalArray = array(

			    'font'  => array(

		        'bold'  => true,

		        'color' => array('rgb' => '1e51bf'),

		        'size'  => 12,

		        'name'  => 'Verdana'

		    ));

		}



		$objPHPExcel->getActiveSheet()->setTitle('Feed Back Report');

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

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

			header('Content-Disposition: attachment;filename="Feed_Back_report.xls"');

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

	}		



	//Feedback End



	//Followup report

	function followUpReport($conn,$objPHPExcel,$shop_id,$fileName,$cron,$teamMembers,$cond,$checkin,$checkout,$RsoUserChecked,$UserHotelAccesid){

		if(isset($cron)){

			$cond='';

			if($checkin != '' && $checkout != ''){



		$sql_Followup_Details .= " AND `".TBL_FOLLOWUP_DETAILS."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";



		$sql_Daily_Enqury .= " AND `".TBL_DAILY_ENQUERY."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";



		$sql_sales_Quote .= " AND `".TBL_SALES_QUOTE."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";

		

		}

			//$cond .= " AND `dated` <= '".date('Y-m-d')."'";

			$cond .= " AND `lead_status` = '1' ";

			if($RsoUserChecked=='' && $UserHotelAccesid==''){

			$cond .= " AND (`id_user` IN (".$teamMembers.") OR assign_user_id IN (".$teamMembers.") )";



			}

			if($RsoUserChecked!='' && $RsoUserChecked=='2'){

		    if($UserHotelAccesid!=''){

		        	$sql_Followup_Details .= " AND `".TBL_FOLLOWUP_DETAILS."`.`hotel_id` IN (".$UserHotelAccesid.") ";

		        	$sql_Daily_Enqury .= " AND `".TBL_DAILY_ENQUERY."`.`hotel_id` IN (".$UserHotelAccesid.") ";

		        	$sql_sales_Quote .= " AND `".TBL_SALES_QUOTE."`.`hotel_id` IN (".$UserHotelAccesid.") ";

		        	

		    }

		    

		}

		}	

		if($RsoUserChecked!='2'){	

		$sql = " SELECT  3 AS display_order,id,lead_status,'Sales Report' AS 'Source',hotel_id,id_user,assign_user_id,dated,date_created,follow_up_summary AS summary,followup_close_type_id AS id_close_type,followup_close_summary AS close_summary, '' AS id_company,visit_id AS visit_id  FROM `".TBL_FOLLOWUP_DETAILS."`  WHERE follow_up_summary !='' AND   `".TBL_FOLLOWUP_DETAILS."`.`id_shop` = '".$shop_id."'  ".$cond.$sql_Followup_Details."

			

			UNION ALL";

            }

			$sql .= "	SELECT  2 AS display_order,id,lead_status,'Lead' AS 'Source',hotel_id,id_user,assign_user_id,follow_up_date AS dated,created_date AS date_created,follow_up_summary AS summary,'' AS id_close_type,'' AS close_summary, id_company AS id_company, '' AS visit_id  FROM `".TBL_DAILY_ENQUERY."`  WHERE follow_up_summary !='' AND   `".TBL_DAILY_ENQUERY."`.`id_shop` = '".$shop_id."' ".str_replace('dated', 'follow_up_date', $cond).$sql_Daily_Enqury."



			UNION ALL



			SELECT  1 AS display_order,id,lead_status,'Quote' AS 'Source',hotel_id,id_user,assign_user_id,follow_up_date As dated,created_date AS last_created,details AS summary,'' AS id_close_type,'' AS close_summary,id_company AS id_company,'' AS visit_id  FROM `".TBL_SALES_QUOTE."`  WHERE follow_up_summary !='' AND   `".TBL_SALES_QUOTE."`.`id_shop` = '".$shop_id."' ".str_replace('dated', 'follow_up_date',$cond).$sql_sales_Quote."

		";

			



		$sql .=  " ORDER BY display_order,dated DESC";

			

		//print_r($_SESSION);	

		//echo $sql;

        //die;

		$res = mysqli_query($conn,$sql);

		if($res){

			$numRows = mysqli_num_rows($res);

		}	

			



			



			// Set document properties



		$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")

					->setLastModifiedBy("Hitesh Aloney")

					->setTitle("Follow up Report")

					->setSubject("Follow up Report")

					->setDescription("Follow up Report")

					->setKeywords("Follow up Report")

					->setCategory("Report");







		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing







		$objDrawing->setName('Logo');        //set name to image







		$objDrawing->setDescription('Logo'); //set description to image



		$logo = selectColumn('fs_shop','image'," WHERE  id=".$shop_id." ");



			

		if(!isset($cron)){

			$signature = "../uploaded_files/shop/".$logo.""; 

		}

		else{

				//local

				//$signature = "../../uploaded_files/shop/".$logo."";

				//server cron

			$signature = "/home/inroomhub/public_html/sync/uploaded_files/shop/".$logo.""; 

		}	

			   //Path to signature .jpg file



			



		if(file_exists($signature)){

			$objDrawing->setPath($signature);

			$objDrawing->setOffsetX(25);                       //setOffsetX works properly

			$objDrawing->setOffsetY(10);                       //setOffsetY works properly

			$objDrawing->setCoordinates('F2');        //set image to cell

			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		}  //save











		if($numRows > 0){

			$counter = 1;



			if(!isset($cron)){

				$objPHPExcel->setActiveSheetIndex(0)

				->setCellValue('A7', 'Follow Up Report  from '.$_REQUEST['report_date']);

			}

			else{

				$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue('A7', 'Follow Up Report  As on '.date('M-Y',strtotime($checkin)).' To '.date('M-Y',strtotime($checkout)));

			}	



			$objPHPExcel->getActiveSheet()->mergeCells('A7:K7');



			$head_hotel_row = 8;

			$head_cntr_column = "A";$head_hotel_column = "A";

			$objPHPExcel->setActiveSheetIndex(0)

					->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Hotel')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Company')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Description')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Assigned By')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Assigned On')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Assigned To')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Follow up Date')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Follow Up Summary')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Close Type')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Closing Comment')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Source')

					->setCellValue($head_cntr_column++.$head_hotel_row, 'Status');			

			$styleArray = array(

			    'font'  => array(

		        'bold'  => true,

		        'color' => array('rgb' => 'FFFFFF'),

		        'size'  => 14,

				'text-transform'=>'uppercase',

		        'name'  => 'Calibri'

	   	 	));







			$styleArray_1 = array(

			    'font'  => array(

		        'bold'  => true,

		        'color' => array('rgb' => '000'),

		        'size'  => 10,

				'text-transform'=>'uppercase'

	    	));

			cellColor('A7:M7','254061');

			cellColor('A8:M8','75923c');

			

			$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($styleArray);

			$objPHPExcel->getActiveSheet()->getStyle('A7:M7')->getAlignment()->applyFromArray(



		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



			);







			$objPHPExcel->getActiveSheet()->getStyle('A8:M8')->applyFromArray($styleArray_1);

			$objPHPExcel->getActiveSheet()->getStyle('A8:M8')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('C8')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('D8')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);

			$objPHPExcel->getActiveSheet()->getStyle('G8')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);

			$objPHPExcel->getActiveSheet()->getStyle('H8')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('I8')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(

			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)

			);



			$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->applyFromArray(

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


			$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setWrapText(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()->setWrapText(true);

			$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);

			$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);

			$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);



			$objPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()

					    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(6);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(35);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(35);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(40);

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(20);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(20);	

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(13);

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(25);

			$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(17);

						$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(20);

$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(15);

$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(7);

	



			$head_hotel_row++;

			$Serialno=1;			

			$connew = 9;

			$closeStatusArray=array();

			$counterStatus=0;

			while($row = mysqli_fetch_object($res)){

				print_r($row);

				//die;

				$ExecutiveName	=	selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->id_user." and id_shop=".$shop_id." ");



				$ExecutiveAssignToName	=	selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->assign_user_id." and id_shop=".$shop_id." ");



				$head_order_data1 = "A";



				$head_order_data = "A";       



				

				if(!$row->id_company){

					

					}

				

			if($row->display_order==3){

				$id_close_type = $row->id_close_type;

				$closeSummary=$row->close_summary;

				$Company_id	=	selectColumn(TBL_VISIT,'id_company'," WHERE `id` =".$row->visit_id." and id_shop=".$shop_id." ");		

				$Description	=	selectColumn(TBL_VISIT,'discussion_summary'," WHERE `id` =".$row->visit_id." and id_shop=".$shop_id." ");	

					

				$sqlFollowUpExplode1 = "SELECT * FROM `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`  WHERE `visit_id` =".$row->visit_id." and id_shop=".$shop_id." ORDER BY id DESC";



			$resQue = mysqli_query($conn,$sqlFollowUpExplode1);

			 $numRows= mysqli_num_rows($resQue);

		

			$RowFollowUpExplode=mysqli_fetch_array($resQue);

			$summary	=	$RowFollowUpExplode['summary'];

			

				}

			else{

				$Company_id	=$row->id_company;

			}



				if($row->display_order==2){

					$id_close_type = selectColumn(TBL_DAILY_ENQUERY_DETAILS,'followup_close_type_id','WHERE enquiry_id="'.$row->id.'" AND dated="'.$row->dated.'" ');

					$closeSummary=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary','WHERE enquiry_id="'.$row->id.'" AND dated="'.$row->dated.'" ');

				$Description	=	selectColumn(TBL_DAILY_ENQUERY,'details'," WHERE `id` =".$row->id." and id_shop=".$shop_id." ");		

			$summary	=	$row->summary;

			}else{

				//$Description='';

				}



				if($row->display_order==1){

					$id_close_type = selectColumn(TBL_SALES_QUOTE_FOLLOWUP,'followup_close_type_id','WHERE id_quote="'.$row->id.'" AND dated="'.$row->dated.'" ');



					$closeSummary=selectColumn(TBL_SALES_QUOTE_FOLLOWUP,'quote_close_summary','WHERE id_quote="'.$row->id.'" AND dated="'.$row->dated.'" ');

					$Description	=	$row->summary;

						

					$summary	=selectColumn(TBL_SALES_QUOTE_FOLLOWUP,'details','WHERE id_quote="'.$row->id.'" AND dated="'.$row->dated.'" ');



				}



			



				if($row->lead_status==1){

					$objPHPExcel->setActiveSheetIndex(0)

					->setCellValue($head_order_data++ . $connew, $Serialno++)

					->setCellValue($head_order_data++ . $connew,selectColumn(TBL_HOTELS,'CONCAT(name,",",city)','WHERE id="'.$row->hotel_id.'" '))

					->setCellValue($head_order_data++ . $connew,selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$Company_id.'" '))

					->setCellValue($head_order_data++ . $connew,$Description)

					->setCellValue($head_order_data++ . $connew, ucwords($ExecutiveName))

					->setCellValue($head_order_data++ . $connew, date('d-M-Y',strtotime($row->date_created)))

					->setCellValue($head_order_data++ . $connew, ucwords($ExecutiveAssignToName))



					->setCellValue($head_order_data++ . $connew, date('d-M-Y',strtotime($row->dated)))

					->setCellValue($head_order_data++ . $connew, $summary)

					->setCellValue($head_order_data++ . $connew, selectColumn(TBL_CLOSING_MASTER,'name','WHERE id="'.$id_close_type.'" AND `id_shop` = "'.$shop_id.'" '))

					->setCellValue($head_order_data++ . $connew, $closeSummary)

					->setCellValue($head_order_data++ . $connew, $row->Source)



					->setCellValue($head_order_data . $connew, ($row->lead_status==1?'Open':'Close'));



					$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$head_hotel_row.':'.$head_order_data .$connew)->applyFromArray($styleThinBlackBorderOutline);



					$objPHPExcel->getActiveSheet()->getStyle('A7:K7')->applyFromArray($styleThinBlackBorderOutline);



					$objPHPExcel->getActiveSheet()->getStyle('A8:K8')->applyFromArray($styleThinBlackBorderOutline);



					$connew++;

				}

				else{



					$closeStatusArray[$counterStatus]['sno']=5;

					$closeStatusArray[$counterStatus]['hotel']=selectColumn(TBL_HOTELS,'CONCAT(name,",",city)','WHERE id="'.$row->hotel_id.'" ');

					$closeStatusArray[$counterStatus]['CompanyName']=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$Company_id.'" ');

					$closeStatusArray[$counterStatus]['Description']=$Description;

					$closeStatusArray[$counterStatus]['ExecutiveName']=$ExecutiveName;

					$closeStatusArray[$counterStatus]['date_created']=date('d-M-Y',strtotime($row->date_created));

					$closeStatusArray[$counterStatus]['assign_to']=$ExecutiveAssignToName;

					$closeStatusArray[$counterStatus]['follow_up_date']=date('d-M-Y',strtotime($row->dated));

					$closeStatusArray[$counterStatus]['summary']=$row->summary;

					$closeStatusArray[$counterStatus]['close_summary']=$closeSummary;

					$closeStatusArray[$counterStatus]['close_type']=selectColumn(TBL_CLOSING_MASTER,'name','WHERE id="'.$id_close_type.'" AND `id_shop` = "'.$shop_id.'" ');

					$closeStatusArray[$counterStatus]['source']=$row->Source;

					$counterStatus++;

					

				}

			}



			$i=0;

			$sno=$Serialno;

			$head_order_data='A';

			if($closeStatusArray!=''){

				while($i<count($closeStatusArray)){

					$head_order_data='A';

					$head_order_data1 = "A";

					$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue($head_order_data++ . $connew, $sno++)

						->setCellValue($head_order_data++ . $connew,$closeStatusArray[$i]['hotel'])

						->setCellValue($head_order_data++ . $connew, ucwords($closeStatusArray[$i]['CompanyName']))

						->setCellValue($head_order_data++ . $connew, ucwords($closeStatusArray[$i]['Description']))

						->setCellValue($head_order_data++ . $connew, ucwords($closeStatusArray[$i]['ExecutiveName']))

						->setCellValue($head_order_data++ . $connew,$closeStatusArray[$i]['date_created'])

						->setCellValue($head_order_data++ . $connew, ucwords($closeStatusArray[$i]['assign_to']))



						->setCellValue($head_order_data++ . $connew, $closeStatusArray[$i]['follow_up_date'])





						->setCellValue($head_order_data++ . $connew,$closeStatusArray[$i]['summary'])

						->setCellValue($head_order_data++ . $connew, $closeStatusArray[$i]['close_type'])

						->setCellValue($head_order_data++ . $connew,$closeStatusArray[$i]['close_summary'])

						

						

						->setCellValue($head_order_data++ . $connew,$closeStatusArray[$i]['source'])



						->setCellValue($head_order_data . $connew, 'Close');



						$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$head_hotel_row.':'.$head_order_data .$connew)->applyFromArray($styleThinBlackBorderOutline);

					$objPHPExcel->getActiveSheet()->getStyle('A7:K7')->applyFromArray($styleThinBlackBorderOutline);

					$objPHPExcel->getActiveSheet()->getStyle('A8:K8')->applyFromArray($styleThinBlackBorderOutline);



					$connew++;

					$i++;	

				}

			}



			$forTotal = 'C';

			$totalArray = array(

			    'font'  => array(

		        'bold'  => true,

		        'color' => array('rgb' => '1e51bf'),

		        'size'  => 12,

		        'name'  => 'Verdana'

		    ));

		}

		$objPHPExcel->getActiveSheet()->setTitle('Follow up Report');

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

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

		ob_end_clean();

		

		if(!isset($cron)){

			// Redirect output to a client’s web browser (Excel2007)

			header('Content-Type: application/vnd.ms-excel');

			header('Content-Disposition: attachment;filename="Follow_Up_report'.date('d-m-Y H:i:s').'.xls"');

			header('Cache-Control: max-age=0');

			// If you're serving to IE 9, then the following may be needed

			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be eeded

			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in he past

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

	}

	//Followup End



	//Conveyance Report

	function conveyanceReport($cron,$id_shop,$idUser,$report_date,$approval_status){

		global $connNew;

		$content="

				<style>

					.body{

						margin:0px 0px 0px 0px;

						padding:0px 0px 0px 0px;	

					}

					.headings{

						margin:0px 0px 0px 0px;

						padding:2px 2px 2px 2px;

						border-left:0.2px solid black;

						border-right:0.2px solid black;

						border-bottom:0.2px solid black;

						font-size:12px;

					}

					.topHead{

						font-weight:bold;

					}

					.bottomHead{

						font-size:13px;

						text-align:center;

						font-weight:bold;

						border-left:0.2px solid black;

						border-right:0.2px solid black;

						border-bottom:0.2px solid black;



					}

					

				</style>

				";



		$logo = selectColumn('fs_shop','image'," WHERE  id=".$id_shop." ");

				

		if(!isset($cron)){

			$signature = "../uploaded_files/shop/".$logo.""; 

		}

		else{

			//local

			//$signature = "../../uploaded_files/shop/".$logo."";

			//server cron

			$signature = "/home/inroomhub/public_html/sync/uploaded_files/shop/".$logo.""; 

		}

						

		$content.="	<div width='100%' style='margin-top:5px;' ><img style='margin-left:40%;' src='".$signature."'/></div>

					";



		

		$ids_team = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$idUser.'" ');

		$team = selectColumn(TBL_TEAM,'name','WHERE id IN ('.$ids_team.')');

					

		$content.="	<div style='border:1px solid black;'><table  style='width:100%;margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;' cellspacing='0'>

						<tr>

							<td colspan='3'><b>Executive Name <span style='margin-left:15px;'>:</span> </b></td>

							<td colspan='2' style='text-align:left;'><b>".ucwords(selectColumn(TBL_USERS,'name','WHERE id="'.$idUser.'" '))."</b></td>

							<td colspan='8'></td>

						</tr>	

						<tr>

							<td colspan='3'><b>Team <span style='margin-left:90px;'>:</span></b></td>

							<td colspan='2' style='text-align:left;'><b>".ucwords($team)."</b></td>

							<td colspan='8'></td>

						</tr>

						<tr style='background-color:#254061;'>

							<td colspan='13'style='text-align:center;color:#fff;'>".strtoupper('Conveyance Report  from '.$report_date)."</td>

						</tr>

						<tr  class='trTag'  style='background-color:#c2c2c2;'>

							<td class='headings topHead' width='5%'  style='text-align:center;'>S.No.</td>

							<td class='headings topHead' width='10%'  style='text-align:center;'>Date</td>

							<td class='headings topHead' width='30%' style='text-align:center;' >Area Covered</td>

							

							<td class='headings topHead' width='20%' style='text-align:center;' >Company Visited</td>

							<td class='headings topHead' width='10%'  style='text-align:center;'>Mode</td>

							<td class='headings topHead' width='6%' style='text-align:center;' >Kms</td>

							<td class='headings topHead' width='4%'  style='text-align:center;'>Rate/Km</td>

							<!--<td class='headings topHead' width='10%'  style='text-align:center;'>Sub Total</td>-->

							<td class='headings topHead' width='5%'  style='text-align:center;'>Parking</td>

							<td class='headings topHead' width='5%'  style='text-align:center;'>Total</td>

							<td class='headings topHead' width='5%'  style='text-align:center;'>Lunch</td>

							<td class='headings topHead' width='5%'  style='text-align:center;'>Entertainment</td>

							<td class='headings topHead' width='10%'  style='text-align:center;'>Grand Total</td>

							<td class='headings topHead' width='20%'  style='text-align:center;'>Status</td>

						</tr>

					";









		//$query = "SELECT * FROM ".TBL_DAILYVISIT." WHERE id_user=".$_REQUEST['usernameid']." AND id_shop=".$_SESSION['shop']." AND StatFrom !='' ";



					



		if($idUser=="" || empty($idUser)){

			$_SESSION['errorMsg']="Please Select Executive";

			header("Location:manageConveyance.php");

		}



		if($idUser != ''){

			$cond .= " AND `id_user` = '".addslashes($idUser)."'";

		}		



		if($report_date != ''){

			list($checkin,$checkout) = split(" to ",$report_date);



			$cond .= " AND `dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'  AND (Total+entertainment+lunch) !=0 ";

		}



		if($approval_status !=''){

			$cond .=" AND conveyance_approved='".$approval_status."' ";

		}



		$query =" SELECT  id,'DSR' AS 'DOC_TYPE',dated,CONCAT(StatFrom,' ',StatTo) AS details,".TBL_COMPANY.".name AS description,Parking,lunch,id_travel_mode,KmsRun,RateKm,Total,entertainment,conveyance_approved  FROM `".TBL_DAILYVISIT."` LEFT JOIN ".TBL_COMPANY." ON `".TBL_DAILYVISIT."`.`id_company`= ".TBL_COMPANY.".id_company WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($id_shop)."' AND (Total+entertainment+lunch) !=0 ".$cond." 



		UNION All



		SELECT  id,'other' AS 'DOC_TYPE',dated,details AS details,description AS description,Parking,lunch,id_travel_mode,KmsRun,RateKm,Total,entertainment,conveyance_approved  FROM `".TBL_OTHER."` WHERE `".TBL_OTHER."`.`id_shop` = '".addslashes($id_shop)."' AND (Total+entertainment+lunch) !=0  ".$cond."  



		";



		/*if($_REQUEST['companyId'] != ''){



		$query .= " AND `".TBL_DAILYVISIT."`.`id_company` = '".addslashes($_REQUEST['companyId'])."'";



		}*/



		$query .= " ORDER BY dated,DOC_TYPE,id ASC ";

		

		

		$resQue = mysqli_query($connNew,$query);

		$numRows= mysqli_num_rows($resQue);





		if($numRows>0){

			$head_cntr_column='A';

			$head_hotel_row=11;

					

			

			$datePrint='';

			$printRow=12;

			$finalTotal=0;

			$finalGrand=0;

			$approvalText='';

			while($rowData=mysqli_fetch_object($resQue)){



							

				$printCol='A';



				if($datePrint==''){

					$datePrint=$rowData->dated;

					$sno=1;

					$sno2='123';

					$datePrint2='123';

					$bgColor='style="background-color:#e2e2e2;"';

						

				}

				else if($datePrint==$rowData->dated){

					//just to skip printing

					$sno2='';

					$datePrint2='';

					$bgColor='';

				}

				else{

					$datePrint=$rowData->dated;

					$sno++;

					$sno2='123';

					$datePrint2='123';

					$bgColor='style="background-color:#e2e2e2;"';

				}



				if($rowData->conveyance_approved==1)

					$approvalText='Approved';

				else if($rowData->conveyance_approved==2)

					$approvalText='Not Approved';

				else 

					$approvalText='<b>Pending</b>';



				$content.="		<tr class='trTag' ".$bgColor."  >

								<td class='headings' width='5%'  style='text-align:center;'>".($sno2==''?$sno2:$sno)."</td>

								<td class='headings' width='10%'  style='text-align:center;'>".($datePrint2==''?$datePrint2:date('d-M-Y',strtotime($datePrint)))."</td>

								<td class='headings' width='30%' style='text-align:left;' >".$rowData->details."</td>

								

								<td class='headings' width='20%' style='text-align:left;' >".$rowData->description."</td>

								<td class='headings' width='10%'  style='text-align:center;'>".selectColumn(TBL_TRAVEL_MODES,'name','WHERE id="'.$rowData->id_travel_mode.'" ')."</td>

								<td class='headings' width='6%' style='text-align:center;' >".$rowData->KmsRun."</td>

								<td class='headings' width='4%'  style='text-align:center;'>".$rowData->RateKm."</td>

								<!--<td class='headings' width='10%'  style='text-align:center;'>".$rowData->KmsRun*$rowData->RateKm."</td>-->

								<td class='headings' width='5%'  style='text-align:center;'>".$rowData->Parking."</td>

								

								<td class='headings' width='5%'  style='text-align:center;'>".$rowData->Total."</td>

								<td class='headings' width='5%'  style='text-align:center;'>".$rowData->lunch."</td>

								<td class='headings' width='5%'  style='text-align:center;'>".$rowData->entertainment."</td>

								<td class='headings' width='10%'  style='text-align:center;'>".($rowData->Total+$rowData->entertainment+$rowData->lunch)."</td>

								<td class='headings' width='20%'  style='text-align:center;'>".$approvalText."</td>

								

							</tr>";

							$finalTotal+=$rowData->Total;

							$finalGrand+=($rowData->Total+$rowData->lunch+$rowData->entertainment);



			}

			$content.="<tr >

						<td colspan='5' style='background-color:c2c2c2;'></td>

						<td style='color:blue;background-color:c2c2c2;' colspan='3' class='bottomHead'>Conveyance Total</td>

						<td style='color:blue;background-color:c2c2c2;' class='bottomHead'>".$finalTotal."</td>

						<td style='color:green;background-color:c2c2c2;' class='bottomHead' colspan='2'>Grand Total</td>

						<td style='color:green;background-color:c2c2c2;' class='bottomHead'>".$finalGrand."</td>

						<td style='background-color:c2c2c2;'></td>

						</tr>";







			$content.="</table></div>";



			/*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$printRow, 'Grand Total');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$printRow, '=SUM(I12:I'.($printRow-1).')');

			$objPHPExcel->getActiveSheet()->getStyle('E'.$printRow.':I'.$printRow)->applyFromArray($totalArray);

			$objPHPExcel->getActiveSheet()->mergeCells('E'.$printRow.':H'.$printRow);

			$objPHPExcel->getActiveSheet()->getStyle('A10:J'.$printRow)->applyFromArray($styleThinBlackBorderOutline);*/



		}





		$dompdf = new DOMPDF();

		//$dompdf->set_option("isPhpEnabled", true);

		$dompdf->set_paper('landscape', 'landscape');

		$dompdf->load_html($content);

		$dompdf->render();

		$font = Font_Metrics::get_font("helvetica", "bold");

		$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));

		if(!isset($cron)){

			$dompdf->output();

		//$dompdf->stream();

		$dompdf->stream('ConveyanceReport'.'_'.selectColumn(TBL_USERS,'name','WHERE id="'.$idUser.'" ').'_'.date('d-M-Y H:i:s').'.pdf', array("Attachment" => true));	

		exit;

			

		}

		else{

			if($numRows>0){

			$Filename = 'ConveyanceReport'.'_'.selectColumn(TBL_USERS,'name','WHERE id="'.$idUser.'" ');

			

			$gen = $dompdf->output();

			//$dompdf->stream($Filename.'.pdf', array("Attachment" => true));



			

			//local

			//$objWriter->save('../mailattach/'.$fileName.'.xls');

			file_put_contents('/home/inroomhub/public_html/sync/adminpanel/mailattach/'.$Filename.'.pdf', $gen);

			//file_put_contents('../mailattach/'.$Filename.'.pdf', $gen);

			//cron server

			//$objWriter->save('/home/admingcs/public_html/sync/adminpanel/mailattach/'.$fileName.'.xls');

			//echo "ok";

			}

		}

		

		

	}

	//Conveyance Report End
// Unit Executive portfolio start
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

		while($row = mysqli_fetch_object($res)){

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
		        
		        array_push($tempTableArr,' INSERT INTO temp_executive_port VALUES('.$Serialno++.',"'.$row->id_executive.'","'.$row->executive.'","'.str_replace('&amp;','&', $row->name).'","'.trim($companyGroupName).'","'.$row->area.'","'.$sumRow->rate_name.'","'.$winRow->rate_name.'","'.$yearRow->rate_name.'","'.$prevAch2.'","'.$prevAch.'","'.$achieved.'","'.$budget.'","'.$v2b.'","'.$tempTableVisit[0].'","'.$tempTableVisit[1].'","'.$tempTableVisit[2].'","'.$tempTableVisit[3].'","'.$tempTableVisit[4].'")');
			}

			
			foreach ($tempTableArr as $key => $query) {
				mysqli_query($conn,$query);
				debugData(mysqli_error($conn));
			}
			
			 //$tempTableFetch = "SELECT * FROM  temp_executive_port where id_executive='".$id_user."' AND  (year!=0  OR budg_year!=0 OR visit1!='0000-00-00') order by year desc";
		    $tempTableFetch = "SELECT * FROM  temp_executive_port where id_executive='".$id_user."'  order by year desc";	 

			$fetchRes = mysqli_query($conn,$tempTableFetch);
			

			
			$Serial=1;
			while($fetchTempRow = mysqli_fetch_object($fetchRes)){
				

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

				



	}

	//Unit Executive Portfoilo Report End

	function companyVisitedDetails($id_shop,$id_company='',$teamMembers,$from,$to,$usernameid){
		
		global $connNew;
		global $objPHPExcel;

		$styleArray = array(
		      'font'  => array(
	          'bold'  => true,
	          'color' => array('rgb' => 'FFFFFF'),
	          'size'  => 14,
		      'text-transform'=>'uppercase',
	          'name'  => 'Calibri'
	    ));

	  	$styleArray_1 = array(
		      'font'  => array(
	          'bold'  => true,
	          'color' => array('rgb' => '000'),
	          'size'  => 12,
		      'text-transform'=>'uppercase'
        ));

        $styleThinBlackBorderOutline = array(
					'borders' => array(
					'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => '000'),
					),
				),
		);	


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


		if(file_exists($signature)){
			$objDrawing->setPath($signature);
			$objDrawing->setOffsetX(20);                       
			$objDrawing->setOffsetY(10);                       
			$objDrawing->setCoordinates('F1');      
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		}

		cellColor('A6:N6','254061');
		cellColor('A7:N7','75923c');

		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A6','Company Visited Detail Report From  '.$_REQUEST['report_date']);
		
		$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setWrapText(true);
		
		$objPHPExcel->getActiveSheet()->mergeCells('A6:N6');
		$objPHPExcel->getActiveSheet()->getStyle("A6:N7")->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getStyle('A6:N7')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									    );




		$head_hotel_row = 7;

		$head_cntr_column = "A";


		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Date')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Location/City')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Title')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'First Name')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Name')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Designation')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Email')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Contact Number')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Group')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Comment')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Profile')
					->setCellValue($head_cntr_column++.$head_hotel_row++, 'Executive Name'); 

		
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(10);  
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(15); 
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(30); 
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(25);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(40);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(40);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(20);

		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									    );
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									    );

		if($id_company!='')
			$filter = ' AND id_company="'.$id_company.'"';
		else
			$filter = '';
	    
	     if($usernameid!=''){
	
		    $filter .= " AND `id_user` = '".addslashes($usernameid)."'";
	
		   //$exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$_REQUEST['usernameid'].'" ');
	
		}

		$sql = "SELECT * FROM ".TBL_DAILYVISIT." WHERE FIND_IN_SET(id_user,'".$teamMembers."')  ".$filter." AND dated BETWEEN '".$from ."' AND '".$to."' ORDER BY dated DESC";

		$res = mysqli_query($connNew,$sql);
		$sno = 1; 
		while($row = mysqli_fetch_object($res)){
			$head_cntr_column = "A";
			
			$date = date('d-M-Y',strtotime($row->dated));
			$company = selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$row->id_company.'" ');
			$idCompanyArea = selectColumn(TBL_COMPANY,'area','WHERE id_company="'.$row->id_company.'" ');
			//$companyLocation = selectColumn(TBL_AREAS,'name','WHERE id="'.$idCompanyArea.'" ');
			$companyLocation =selectColumn(TBL_COMPANY,'city','WHERE id_company="'.$row->id_company.'" ');
			$title = selectColumn(TBL_CUSTOMER,'title','WHERE id_customer="'.$row->id_contacts.'" ');
			$firstName = selectColumn(TBL_CUSTOMER,'first_name','WHERE id_customer="'.$row->id_contacts.'" ');
			$lastName = selectColumn(TBL_CUSTOMER,'last_name','WHERE id_customer="'.$row->id_contacts.'" ');
			$id_designation = selectColumn(TBL_CUSTOMER,'designation','WHERE id_customer="'.$row->id_contacts.'" ');
			$designation = selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$id_designation.'" ');
			$companyEmail = selectColumn(TBL_COMPANY,'email','WHERE id_company="'.$row->id_company.'" '); 
			$email = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$row->id_contacts.'" ');
			$mobile = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$row->id_contacts.'" ');
			$idGroup = selectColumn(TBL_COMPANY,'id_default_group','WHERE id_company="'.$row->id_company.'" ');
			$companyGroup = selectColumn(TBL_GROUP,'name','WHERE id_group="'.$idGroup.'" ');
			$description = $row->discussion_summary;
			$details = selectColumn(TBL_COMPANY,'details','WHERE id_company="'.$row->id_company.'" ');
			$exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_user.'" ');

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($head_cntr_column++.$head_hotel_row, $sno++)
						->setCellValue($head_cntr_column++.$head_hotel_row, $date)
						->setCellValue($head_cntr_column++.$head_hotel_row, $company)
						->setCellValue($head_cntr_column++.$head_hotel_row, $companyLocation)
						->setCellValue($head_cntr_column++.$head_hotel_row, $title)
						->setCellValue($head_cntr_column++.$head_hotel_row, $firstName)
						->setCellValue($head_cntr_column++.$head_hotel_row, $lastName)
						->setCellValue($head_cntr_column++.$head_hotel_row, $designation)
						->setCellValue($head_cntr_column++.$head_hotel_row, $companyEmail.', '.$email)
						->setCellValue($head_cntr_column++.$head_hotel_row, $mobile)
						->setCellValue($head_cntr_column++.$head_hotel_row, $companyGroup)
						->setCellValue($head_cntr_column++.$head_hotel_row, $description)
						->setCellValue($head_cntr_column++.$head_hotel_row, $details)
						->setCellValue($head_cntr_column++.$head_hotel_row++, $exeName); 

		}
		

		$objPHPExcel->getActiveSheet()->getStyle('A6:N'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);




		$objPHPExcel->getActiveSheet()->setTitle('Company Visited Detail Report');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.10);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.15);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1); 
		$objPHPExcel->getActiveSheet()->getPageSetup()
					->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()
				    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	    
	    ob_end_clean();

		header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="Company_Visited_Detail_Report_'.date('d-m-Y H:i:s').'.xls"');
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

	}				
?>