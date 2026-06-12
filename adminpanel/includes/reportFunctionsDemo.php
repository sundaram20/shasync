<?php
//error_reporting(E_ALL);
error_reporting(0);


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
			$signature = '/var/www/vhosts/roomstatushub.in/httpdocs/sync/uploaded_files/shop/'.$logo;
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

		 

			$signature = '/var/www/vhosts/roomstatushub.in/httpdocs/sync/uploaded_files/shop/'.$logo;

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
if($rowData->modified_team!=''){
					$newTemaName	=selectColumn(TBL_TEAM,'name','WHERE id IN ('.$rowData->modified_team.')');
					}else{$newTemaName	='';
						}
						
			if($rowData->team!=''){			
			$tearmExe   =   	selectColumn(TBL_TEAM,'name','WHERE id IN ('.$rowData->team.')');		
			}else{ $tearmExe='-'; } 
		      $head_cntr_column='A';

		      $objPHPExcel->setActiveSheetIndex(0)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $sno)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, date('d/m/Y',strtotime($rowData->date_created)))

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->company)

		        ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->executive)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $tearmExe)

		        ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->area_desc)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->modified_by)

		        ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->modified_by)

		        ->setCellValue($head_cntr_column++.$head_hotel_row,$newTemaName)

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

				$objWriter->save('/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/Company_Addition_Report '.$startDate.'.xls');



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

				$objWriter->save('/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/'.$fileName.'.xls');

			}

		}

	}	

	// Hotel Wise Contract Report End



	

	//Executive Portfoilo Report Yearly

	function executivePortFolioYearlyReportDemo($cron,$id_shop,$id_user,$fy_date,$fileName="",$conn="",$objPHPExcel=""){

		
		/*$date = date('Y-m-d',strtotime($fy_date));

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

		$prevYear2End = date('Y-m-d',strtotime('+1 years',strtotime($prevYear2)));*/
		
		
		
		$date = date('Y-m-d',strtotime($fy_date));
		$month = date('m',strtotime($date));

		if($month <= 3){

			$date = strtotime($date);
			$date = strtotime("-1 year",$date);
			$date = date('Y-04-01',$date);
		}else{

			$date =date('Y-04-01');
		}



		if(date('m')<=3){
			$period = date('Y',strtotime('-1 years',strtotime(date('Y')))).'-'.date('y');
		}else{
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
//echo $date.'===='.$end;



		$prevYear = strtotime($date);

		$prevYear = strtotime("-1 year",$prevYear);

		$prevYear = date('Y-04-01',$prevYear);

		$prevYearEnd = date('Y-03-31',strtotime('+1 years',strtotime($prevYear)));

		

		$prevYear2 = strtotime($date);

		$prevYear2 = strtotime("-2 year",$prevYear2);

		$prevYear2 = date('Y-04-01',$prevYear2);

		$prevYear2End = date('Y-03-31',strtotime('+1 years',strtotime($prevYear2)));

		

	$prysql = "SELECT  id  FROM `".TBL_AREAS."`  WHERE `id_shop` = '".addslashes($id_shop)."' 	 AND user_id = '".addslashes($id_user)."' ";
		$userPrimaryAreaArr	=	array();
		$rowpry	= mysqli_query($conn,$prysql);
		while($result	=	mysqli_fetch_object($rowpry)){
			 array_push($userPrimaryAreaArr,$result->id);
		}
		
		
		
	
	$sql .=" SELECT  a.id_company,a.id_default_group,a.name,b.name AS area,b.id as id_area,c.id as id_user,c.name AS executive,c.id AS id_executive  FROM `".TBL_COMPANY."` AS a


			LEFT JOIN `".TBL_AREAS."` AS b ON a.area=b.id

			LEFT JOIN `".TBL_USERS."` AS c ON FIND_IN_SET(c.id,b.ids_unit_user)
			WHERE a.`id_shop` = '".addslashes($id_shop)."' 
			AND c.id = '".addslashes($id_user)."'";
		

/*$sql = "SELECT  a.id_company,a.id_default_group,a.name,b.name AS area,c.id as id_user,c.name AS executive  FROM `".TBL_COMPANY."` AS a

			LEFT JOIN `".TBL_AREAS."` AS b ON a.area=b.id

			LEFT JOIN `".TBL_USERS."` AS c ON b.user_id=c.id

			WHERE a.`id_shop` = '".addslashes($id_shop)."' ";



		echo $sql .= " AND c.id = '".addslashes($id_user)."' AND id_company IN (".$id_Company_array.") ";

		*/
//echo $sql;
		//die;



		$res = mysqli_query($conn,$sql);



		if($res){

			$numRows = mysqli_num_rows($res);

		}


		



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

			$signature = '/var/www/vhosts/roomstatushub.in/httpdocs/sync/uploaded_files/shop/'.$logo;

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

						->setCellValue('A6', 'Executive Portfoilo - RSO Report Year  :'.date('Y',strtotime($financial_year_to)).'-'.date('Y',strtotime($financial_year_from)));



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
							
			

			$tempTableArr=array();$kk=1;
			$reportArrayPortfolio=array();
			while($row = mysqli_fetch_object($res)){

			$sqlPIckupBase = " SELECT 

			SUM(CASE WHEN  month between '".$date."' and '".$end."'  and id_company='".$row->id_company."' THEN ROUND(qty,0) ELSE 0 END ) AS achieved,
			SUM(CASE WHEN  month between '".$prevYear2."' and '".$prevYear2End."'and id_company='".$row->id_company."' THEN ROUND(qty,0) ELSE 0 END ) AS prevAch2,   
			SUM(CASE WHEN  month between '".$prevYear."' and '".$prevYearEnd."'  and id_company='".$row->id_company."' THEN ROUND(qty,0) ELSE 0 END ) AS prevAch 
						
				
		 FROM `".TBL_AGENT_ACHIEVED."`  
		
		where `".TBL_AGENT_ACHIEVED."`.`id_shop` = '".$id_shop."'";
		$resultPIckupBase = mysqli_query($conn,$sqlPIckupBase);
      
		$rowPIckupBase = mysqli_fetch_object($resultPIckupBase);
		$prevAch	=	$rowPIckupBase->prevAch;
		$prevAch2	=	$rowPIckupBase->prevAch2;		
		$achieved	=	$rowPIckupBase->achieved;	
            if (in_array($row->id_area, $userPrimaryAreaArr))
			{
			//echo "Match found";
			$companyselect1=1;
			
			//$companyselect2==1;
			 //$companyselect3==1;
			
			}else{
 
					$companyselect=0;
					//Removed USER ID $budget = selectColumn(TBL_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."' AND `from`= '".$date."' AND `to`= '".date('Y-03-31',strtotime($end))."' AND id_user='".$id_user."' ");
					//$budget = selectColumn(TBL_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."' AND `from`= '".$date."' AND `to`= '".date('Y-03-31',strtotime($end))."'  ");
				//	$budget = selectColumn(TBL_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."'   ");  Commented ON 13-02-2021
				
					$budget = selectColumn(TBL_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."' AND id_user='".$id_user."'  "); 


					//JUMP
                    //Removed USER ID
                    // $achieved =selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$date."' and '".$end."' and id_shop='".$id_shop."' and id_company='".$row->id_company."'  AND id_user='".$id_user."'  ");
                    //$achieved =selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$date."' and '".$end."' and id_shop='".$id_shop."' and id_company='".$row->id_company."'   ");
					
					//$achieved =selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE  id_shop='".$id_shop."' and id_company='".$row->id_company."'   "); Commented ON 13-02-2021
					
					//$achieved =selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE  id_shop='".$id_shop."' and id_company='".$row->id_company."' AND id_user='".$id_user."'  "); 
			       


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
		$ArrtempTableVisit	=sizeof($tempTableVisit);
		
					
 //echo "Match not found";
  $companyselect1=0;
	if($budget>0){
		$companyselect1=1;	
	}
	if($achieved>0){
		$companyselect1=1;
	}
	if($ArrtempTableVisit>0){
		$companyselect1=1;	
	}
			
			    
			}
			
	
				
			if($companyselect1==1){
				//$achieved =selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$date."' and '".$end."' and id_shop='".$id_shop."' and id_company='".$row->id_company."'   ");

//Removed uSER ID 
//$budget = selectColumn(TBL_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."' AND `from`= '".$date."' AND `to`= '".date('Y-03-31',strtotime($end))."' AND id_user='".$id_user."' ");
                //$budget = selectColumn(TBL_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."' AND `from`= '".$date."' AND `to`= '".date('Y-03-31',strtotime($end))."'  ");
//echo '<br><br><br>====='."SELECT 'room_nights' FROM `".TBL_AGENT_BUDGET."` WHERE `id_company` = '".$row->id_company."' AND `from`= '".$date."' AND `to`= '".date('Y-03-31',strtotime($end))."'  ";				

//Removed USERID
//$prevAch2 = selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear2."' and '".$prevYear2End."' and id_shop='".$id_shop."' and id_company='".$row->id_company."' AND id_user='".$id_user."' ");
				//$prevAch2 = selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear2."' and '".$prevYear2End."' and id_shop='".$id_shop."' and id_company='".$row->id_company."'  ");

//Removed USER ID
//$prevAch = selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear."' and '".$prevYearEnd."' and id_shop='".$id_shop."' and id_company='".$row->id_company."'  AND id_user='".$id_user."' ");
				//$prevAch = selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear."' and '".$prevYearEnd."' and id_shop='".$id_shop."' and id_company='".$row->id_company."'  ");

/*$sqlPIckupBase = " SELECT 

			SUM(CASE WHEN  month between '".$date."' and '".$end."'  and id_company='".$row->id_company."' THEN ROUND(qty,0) ELSE 0 END ) AS achieved,
			SUM(CASE WHEN  month between '".$prevYear2."' and '".$prevYear2End."'and id_company='".$row->id_company."' THEN ROUND(qty,0) ELSE 0 END ) AS prevAch2,   
			SUM(CASE WHEN  month between '".$prevYear."' and '".$prevYearEnd."'  and id_company='".$row->id_company."' THEN ROUND(qty,0) ELSE 0 END ) AS prevAch 
						
				
		 FROM `".TBL_AGENT_ACHIEVED."`  
		
		where `".TBL_AGENT_ACHIEVED."`.`id_shop` = '".$id_shop."'";
		$resultPIckupBase = mysqli_query($conn,$sqlPIckupBase);
      
		$rowPIckupBase = mysqli_fetch_object($resultPIckupBase);
		$prevAch	=	$rowPIckupBase->prevAch;
		$prevAch2	=	$rowPIckupBase->prevAch2;		
		$achieved	=	$rowPIckupBase->achieved;*/
	//echo $kk."====";print_r($rowPIckupBase);;
				/*$sumSql = "SELECT rate_name FROM ".TBL_RATE."  WHERE id_shop='".$id_shop."' AND company_id='".$row->id_company."' AND date(start_date)='".date('Y-04-01',strtotime($date))."' ";

				



				$winSql = "SELECT  rate_name FROM ".TBL_RATE."  WHERE id_shop='".$id_shop."' AND company_id='".$row->id_company."' AND date(start_date)='".date('Y-10-01',strtotime($date))."' ";



				$yearSql="SELECT  rate_name FROM ".TBL_RATE."  WHERE id_shop='".$id_shop."' AND company_id='".$row->id_company."' AND (date(start_date)='".date('Y-01-01',strtotime($date))."' AND date(end_date)='".date('Y-12-31',strtotime($date))."' ) ";



				$resSum = mysqli_query($conn,$sumSql);	

				$resWin = mysqli_query($conn,$winSql);

				$resYear = mysqli_query($conn,$yearSql);



				$sumRow=mysqli_fetch_object($resSum);

				$winRow=mysqli_fetch_object($resWin);	

				$yearRow=mysqli_fetch_object($resYear);*/
				
				
				
				/* $sqlRateName = " SELECT 
			(CASE WHEN  date(start_date)='".date('Y-04-01',strtotime($date))."'  THEN rate_name ELSE 0 END ) AS sumSql,
			(CASE WHEN  company_id='".$row->id_company."' AND date(start_date)='".date('Y-10-01',strtotime($date))."' THEN rate_name ELSE 0 END ) AS winSql,   
			(CASE WHEN  company_id='".$row->id_company."' AND (date(start_date)='".date('Y-01-01',strtotime($date))."' AND date(end_date)='".date('Y-12-31',strtotime($date))."' ) THEN rate_name ELSE 0 END ) AS yearSql 
					
				
		 FROM `".TBL_RATE."`  
		
		where `".TBL_RATE."`.`id_shop` = '".$id_shop."'";
		
		$resultRateName = mysqli_query($conn,$sqlRateName);
      
		$rowRateName = mysqli_fetch_object($resultRateName);
		$sumRow	=	$rowRateName->sumSql;
		$winRow	=	$rowRateName->winSql;		
		$yearRow	=	$rowRateName->yearSql;*/	

				//$v2b = ($achieved-$budget);

				
 

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
		        
		       array_push($tempTableArr,' INSERT INTO temp_executive_port VALUES('.$Serialno++.',"'.$row->id_executive.'","'.$row->executive.'","'.str_replace('&amp;','&', $row->name).'","'.trim($companyGroupName).'","'.$row->area.'","'.$sumRow.'","'.$winRow.'","'.$yearRow.'","'.$prevAch2.'","'.$prevAch.'","'.$achieved.'","'.$budget.'","'.$v2b.'","'.$tempTableVisit[0].'","'.$tempTableVisit[1].'","'.$tempTableVisit[2].'","'.$tempTableVisit[3].'","'.$tempTableVisit[4].'")');                                   

		      //		echo '<br><br>======'.$kk++.' INSERT INTO temp_executive_port VALUES('.$Serialno++.',"'.$row->id_executive.'","'.$row->executive.'","'.str_replace('&amp;','&', $row->name).'","'.trim($companyGroupName).'","'.$row->area.'","'.$sumRow->rate_name.'","'.$winRow->rate_name.'","'.$yearRow->rate_name.'","'.$prevAch2.'","'.$prevAch.'","'.$achieved.'","'.$budget.'","'.$v2b.'","'.$tempTableVisit[0].'","'.$tempTableVisit[1].'","'.$tempTableVisit[2].'","'.$tempTableVisit[3].'","'.$tempTableVisit[4].'")';
$sn = $Serialno++;
$reportArrayPortfolio[$sn]['sno']=$sn;
$reportArrayPortfolio[$sn]['id_executive']=$row->id_executive;
$reportArrayPortfolio[$sn]['executive']=$row->executive;
$reportArrayPortfolio[$sn]['company_name']=str_replace('&amp;','&', $row->name);
$reportArrayPortfolio[$sn]['company_group']=trim($companyGroupName);
$reportArrayPortfolio[$sn]['area']=$row->area;
$reportArrayPortfolio[$sn]['summer_rate']=$sumRow;

$reportArrayPortfolio[$sn]['winter_rate']=$winRow;
$reportArrayPortfolio[$sn]['year_rate']=$yearRow;
$reportArrayPortfolio[$sn]['year_pre_2']=$prevAch2;
$reportArrayPortfolio[$sn]['year_pre']=$prevAch;
$reportArrayPortfolio[$sn]['year']=$achieved;
$reportArrayPortfolio[$sn]['budg_year']=$budget;
$reportArrayPortfolio[$sn]['v2b']=$v2b;
$reportArrayPortfolio[$sn]['visit1']=$tempTableVisit[0];
$reportArrayPortfolio[$sn]['visit2']=$tempTableVisit[1];
$reportArrayPortfolio[$sn]['visit3']=$tempTableVisit[2];
$reportArrayPortfolio[$sn]['visit4']=$tempTableVisit[3];
$reportArrayPortfolio[$sn]['visit5']=$tempTableVisit[4];


			}		

			}
			//echo debugData($reportArrayPortfolio);
			//echo '<br>----';
			die;
			
			foreach ($tempTableArr as $key => $query) {
				mysqli_query($conn,$query);
			}
		//	echo 'INSERT COMPLETED - Testing Under Process';
		//	die;
			//$tempTableFetch = "SELECT * FROM  temp_executive_port where id_executive='".$id_user."' AND (year !=0 OR budg_year!=0 OR visit1!='0000-00-00')  order by year desc";
			$tempTableFetch = "SELECT * FROM  temp_executive_port where id_executive='".$id_user."'  order by year desc";
			
			$fetchRes = mysqli_query($conn,$tempTableFetch);
			$Serial=1;
			while($fetchTempRow = mysqli_fetch_object($fetchRes)){
				
				
				$head_order_data1 = "A";
				$head_order_data = "A";       

				$objPHPExcel->setActiveSheetIndex(0)

						->setCellValue('A6', ''.$fetchTempRow->executive.' Portfoilo Report Year  :'.date('Y',strtotime($date)).'-'.date('y',strtotime($end)));

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







	
//$FileName=  $fetchTempRow->executive.'-'.date('Y',strtotime($date)).'-'.date('y',strtotime($end));
				

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
$ExeName = selectColumn(TBL_USERS,'name'," WHERE id='".$id_user."' ");
$fileName=	$ExeName." Portfoilo Report Year_".$period;
			ob_end_clean();

			// Redirect output to a client’s web browser (Excel2007)

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

			

			if($numRows>0){	

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

				//local

				//$objWriter->save('../mailattach/'.$fileName.'.xls');

					

				//cron server

				$objWriter->save('/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/'.$fileName.'.xls');

			}		

		}	

				



	}
			
?>