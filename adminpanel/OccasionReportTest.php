<?php include_once("../config/auto_loader.php");



checkUserLevelPermission($_SESSION['userLevel'],'occasion_report','view');



if($_SESSION['userLevel']!=1){

$perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";

$resPer = mysqli_query($connNew,$perSql);



if($resPer){

    $perData  = mysqli_fetch_object($resPer);

    if($perData->calendar_user_list_approved == 0){

     $UserRestriction =" AND id='".$_SESSION['userId']."'"; 

    }

}

}

if($_SESSION['teamMembers'] !=""){

  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";

}

else{

  $teamMembers ="";

}



$areaCond = "AND ".TBL_AREAS.".id IN (".$_SESSION['teamMyAreas'].") ";




if($_REQUEST['Download']=='Download'){



  if($_REQUEST['report_date'] != ''){

    //list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
		$report_date= explode(" to ",$_REQUEST['report_date']);
		$checkin = $report_date['0'];
		$checkout = $report_date['1'];


  }

	 



if($_REQUEST['search_name'] != ''){



	$sqlConnect = " AND `".TBL_CUSTOMER."`.id_company = '".addslashes($_REQUEST['search_name'])."'";



}

$sql = " SELECT `".TBL_CUSTOMER."`.* FROM `".TBL_CUSTOMER."` LEFT JOIN ".TBL_COMPANY." ON `".TBL_CUSTOMER."`.id_company=".TBL_COMPANY.".id_company
LEFT JOIN ".TBL_AREAS." ON ".TBL_COMPANY.".area=".TBL_AREAS.".id where `".TBL_COMPANY."`.`id_shop` = '".addslashes($_SESSION['shop'])."' $sqlConnect $areaCond";


//echo $sql;
$sql_DOA = " SELECT `".TBL_CUSTOMER."`.* FROM `".TBL_CUSTOMER."` LEFT JOIN ".TBL_COMPANY." ON `".TBL_CUSTOMER."`.id_company=".TBL_COMPANY.".id_company
LEFT JOIN ".TBL_AREAS." ON ".TBL_COMPANY.".area=".TBL_AREAS.".id  where `".TBL_CUSTOMER."`.`id_shop` = '".addslashes($_SESSION['shop'])."' $sqlConnect $areaCond";



if($checkin != ''){



	 $sql .= " AND (  (`".TBL_CUSTOMER."`.`dateofBirthday` BETWEEN  '".date('d',strtotime($checkin))."' AND '".date('d',strtotime($checkout))."' AND `".TBL_CUSTOMER."`.`dateofBirthMonth` BETWEEN  '".date('m',strtotime($checkin))."' AND '".date('m',strtotime($checkout))."')  )";





$sql_DOA .= " AND (  (`".TBL_CUSTOMER."`.`dateofanniversaryday` BETWEEN  '".date('d',strtotime($checkin))."' AND '".date('d',strtotime($checkout))."' AND `".TBL_CUSTOMER."`.`dateofanniversaryMonth` BETWEEN  '".date('m',strtotime($checkin))."' AND '".date('m',strtotime($checkout))."')  )";



}



   $sql .= " ORDER BY dateofBirthMonth,dateofBirthday"; 
  

    $sql_DOA  .= " ORDER BY dateofanniversaryMonth,dateofanniversaryday";

    if($_REQUEST['type']==1){
      $sql_DOA='';
    }
    elseif($_REQUEST['type']==2){
      $sql='';
    }

  // echo $sql_DOA;die;
    $resCom = mysqli_query($connNew,$sql); 

    $numRows = mysqli_num_rows($resCom);



 

      $objPHPExcel->getProperties()->setCreator("Hitesh Aloney")

                     ->setLastModifiedBy("Hitesh Aloney")

                     ->setTitle("Company Visit History Report")

                     ->setSubject("Company Visit History Report")

                     ->setDescription("Company Visit History Report")

                     ->setKeywords("Company Visit History Report")

                     ->setCategory("Report");







      $objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing

     $objDrawing->setName('Logo');        //set name to image

     $objDrawing->setDescription('Logo'); //set description to image



      $logo = selectColumn('fs_shop','image'," WHERE  id=".$_SESSION['shop']." ");



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

      function cellColor($cells,$color){

      global $objPHPExcel;

      $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(

          'type' => PHPExcel_Style_Fill::FILL_SOLID,

          'startcolor' => array(

          'rgb' => $color

        )

      ));

  }

      $numRows=0;

      if($numRows == 0){



      $counter = 1;



      $area_id = selectColumn(TBL_COMPANY,'area','

        WHERE id_company="'.$_REQUEST['search_name'].'"');

      $user_id = selectColumn(TBL_AREAS,'user_id','

        WHERE id="'.$area_id.'"');

      $executive = selectColumn(TBL_USERS,'name','

        WHERE id="'. $user_id.'"');

      $companyName = selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$_REQUEST['search_name'].'" ');

 if($_REQUEST['type']!=2 OR $_REQUEST['type']==''){
      $objPHPExcel->setActiveSheetIndex(0)

      ->setCellValue('A6', ' '.$companyName.' Date Of Birth from  '.$_REQUEST['report_date']);

      

    

      //$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);

      //$objPHPExcel->getActiveSheet()->getStyle('H9')->getAlignment()->setWrapText(true);



       $objPHPExcel->getActiveSheet()->mergeCells('A6:H6');

      

       $objPHPExcel->getActiveSheet()->getStyle("A6:H7")->getFont()->setBold( true );

      $head_hotel_row = 7;



      $head_cntr_column = "A";$head_hotel_column = "A";

     

      $objPHPExcel->setActiveSheetIndex(0)



        ->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')



		->setCellValue($head_cntr_column++.$head_hotel_row, 'Date')

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')

      

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Person')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Designation')

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Email Id')

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Mobile Number')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive');      
        cellColor('A6:H6','254061');



  cellColor('A7:H7','75923c');
      }//END OF COND

        







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







    



  











    $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);



     $objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->applyFromArray(



        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



    );

    $objPHPExcel->getActiveSheet()->getStyle('A7:H7')->applyFromArray($styleArray_1);



     $objPHPExcel->getActiveSheet()->getStyle('A7:H7')->getAlignment()->applyFromArray(



        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



    );



    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );





    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );



    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );



    $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );

 /*$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );*/
 $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );

    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );

    /*$objPHPExcel->getActiveSheet()->getStyle('F9')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );*/

    $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );



  



     $styleThinBlackBorderOutline = array(



      'borders' => array(



        'allborders' => array(



          'style' => PHPExcel_Style_Border::BORDER_THIN,



          'color' => array('argb' => '000'),



        ),



      ),



    );  

      /*echo date('d-m-Y',strtotime($checkin));

      echo "<br>";

      echo date('d-m-Y',strtotime($checkout));

      echo "<br>";*/

      

      $head_hotel_row=8;

      

      $sno=1;

      while($rowData = mysqli_fetch_object($resCom)){

       $DateOfAnnBirth= strtotime($rowData->dateofBirthday.'-'.$rowData->dateofBirthMonth.-2000);

       //$DateOfAnnBirth= date('d ',strtotime($rowData->dateofBirthday)).date('F',strtotime($rowData->dateofBirthMonth));
       


		$head_cntr_column='A';

		 $objPHPExcel->getActiveSheet()->getStyle('B'.$head_hotel_row.':E'.$head_hotel_row)->getAlignment()->applyFromArray(



        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)



    );

        $objPHPExcel->setActiveSheetIndex(0)



          ->setCellValue($head_cntr_column++.$head_hotel_row, $sno)

		  ->setCellValue($head_cntr_column++.$head_hotel_row, date('d F',$DateOfAnnBirth))

          ->setCellValue($head_cntr_column++.$head_hotel_row, selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$rowData->id_company.'"'))

          

          ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->first_name.' '.$rowData->last_name)	
          ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$rowData->designation.'" ')) 

          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->email)

          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->mobile)
          ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_USERS,'name','WHERE id="'.$rowData->last_modified_by.'"'));

          $sno++;
          $objPHPExcel->getActiveSheet()->getStyle('A6:H'.$head_hotel_row)->applyFromArray($styleThinBlackBorderOutline);
		  $head_hotel_row++;

      } 

      

	  

	  

	  

	  

	  

	  

	  

	  $resCom_DOA = mysqli_query($connNew,$sql_DOA); 

    $numRows_DOA = mysqli_num_rows($resCom_DOA);

	if($_REQUEST['type']==2)
    $head_hotel_row=$head_hotel_row-2;
  else
    $head_hotel_row=$head_hotel_row+1;

	

if($_REQUEST['type']!=1 OR $_REQUEST['type']==''){
    

	 $objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row.':H'.$head_hotel_row)->applyFromArray($styleThinBlackBorderOutline);

	  $objPHPExcel->setActiveSheetIndex(0)

      ->setCellValue('A'.$head_hotel_row, ' '.$companyName.' Date of Anniversary from  '.$_REQUEST['report_date']);

      

    

      $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);

      //$objPHPExcel->getActiveSheet()->getStyle('H9')->getAlignment()->setWrapText(true);



       $objPHPExcel->getActiveSheet()->mergeCells('A'.$head_hotel_row.':H'.$head_hotel_row);

      

	  

	        $objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row.':H'.$head_hotel_row)->getFont()->setBold( true );

  cellColor('A'.$head_hotel_row.':H'.$head_hotel_row,'254061');



    $objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row)->applyFromArray($styleArray);



     $objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row.':H'.$head_hotel_row)->getAlignment()->applyFromArray(



        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



    );





  $head_hotel_row++;

  $head_cntr_column='A';

  $objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row.':H'.$head_hotel_row)->applyFromArray($styleThinBlackBorderOutline);

  cellColor('A'.$head_hotel_row.':H'.$head_hotel_row,'75923c');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row.':H'.$head_hotel_row)->applyFromArray($styleArray_1);



     $objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row.':H'.$head_hotel_row)->getAlignment()->applyFromArray(



        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



    );



  $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
    		->setCellValue($head_cntr_column++.$head_hotel_row, 'Date')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Person')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Designation')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Email Id')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Mobile Number')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive');
    		$head_hotel_row++;
  }

	  

      

		

      $sno_DOA=1;

      while($rowData_DOA = mysqli_fetch_object($resCom_DOA)){

       $DateOfAnn= strtotime($rowData_DOA->dateofanniversaryday.'-'.$rowData_DOA->dateofanniversaryMonth.-2000);

		$head_cntr_column='A';

		

		

     $objPHPExcel->getActiveSheet()->getStyle('B'.$head_hotel_row.':E'.$head_hotel_row)->getAlignment()->applyFromArray(



        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)



    );

        $objPHPExcel->setActiveSheetIndex(0)



          ->setCellValue($head_cntr_column++.$head_hotel_row, $sno_DOA)

		  ->setCellValue($head_cntr_column++.$head_hotel_row, date('d F', $DateOfAnn))

          ->setCellValue($head_cntr_column++.$head_hotel_row, selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$rowData_DOA->id_company.'"'))

          

          ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData_DOA->first_name.' '.$rowData_DOA->last_name)	
          ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$rowData_DOA->designation.'" ')) 

          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData_DOA->email)

          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData_DOA->mobile)
          ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_USERS,'name','WHERE id="'.$rowData_DOA->last_modified_by.'"'));
        

          $sno_DOA++;
          $objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row.':H'.$head_hotel_row)->applyFromArray($styleThinBlackBorderOutline);
		      $head_hotel_row++;

      } 




    $objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(10);  
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(12); 
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(40); 
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(30);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(35);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(18);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(18);



      $forTotal = 'C';



      $totalArray = array(



        'font'  => array(



            'bold'  => true,



            'color' => array('rgb' => '1e51bf'),



            'size'  => 12,



            'name'  => 'Verdana'



        ));



      

    }

    $objPHPExcel->getActiveSheet()->setTitle('Occasion Report Report');





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

      ob_end_clean();



      // Redirect output to a client’s web browser (Excel2007)



      header('Content-Type: application/vnd.ms-excel');

      header('Content-Disposition: attachment;filename="Occasion_Report_'.date('d-m-Y H:i:s').'.xls"');



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

/*if($_REQUEST['report_date'] != ''){

 

  list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);

  

  $sql .= " AND dated BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";  

}*/

/*if($_REQUEST['status'] != ''){

	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";

}

if($_REQUEST['order'] != ''){

	$sql .= " ORDER BY `date_created` DESC";

}else{

	$sql .= " ORDER BY `date_created` DESC";

}*/

//echo $sql;

$db->query($sql);

$numRows= $db->num_rows();

$pagging = new pagingClass($sql,$setpage);

$db->query($pagging->getQuery());

$total = $db->num_rows();

?>

<?php include_once("includes/header.php")?>

<?php include_once("includes/left.php")?>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Sales Report

        <small>Occasion Report </small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Sales Report/Occasion Report </li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">		

	

      <div class="row">

        <div class="col-xs-12">		     

          <!-- /.box -->

          <div class="box">

            <div class="box-header">

              <h3 class="box-title">Occasion Report </h3>

            </div>



                    <form name="searchForm" action="" method="get">

                      <input type="hidden" value="1" name="searchFormSubmit" />

                      <div class="box-body">

                        <div class="row">

                          <div class="form-group col-sm-4">



                              <label for="reservation_date">From - To </label>



                              <div class="input-group">



                                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>



                                 <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  automcomplete="off">



                              </div>

                         </div>    

                         <div class="form-group col-sm-4">



                              <label for="reservation_date">Occasion Type</label>



                             

                                <select name="type" id="" class="select2 form-control">
                                  <option value="">Select Type</option>
                                  <option value="1">D.O.B</option>
                                  <option value="2">D.O.A</option>
                                </select>                                



                         </div>   



                        <!--<div class="form-group col-sm-6">

                           <label>Company Name - City</label>       

         <?php $categoryDropDown = '<select  class="form-control select2" name="search_name" id="search_name">

                          <option value="">Select Company </option>';

                        $resCat = selectSql(TBL_COMPANY," where  id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' AND FIND_IN_SET(area,'".$_SESSION['teamMemberAreas']."')  ",' ORDER BY `name`');

                        if($db->num_rows2($resCat)){

                          while($resultCat = $db->fetch_object2($resCat)){

                          if($_REQUEST['search_name'] == $resultCat->name){

                            $selected = 'selected="selected"';

                          }else{

                            $selected = '';

                          }

                          $categoryDropDown .= '<option '.$selected.' value="'.htmlentities($resultCat->id_company).'">'.ucfirst($resultCat->name.'-'.$resultCat->city).'</option>';

                        }

                        }

                        echo $categoryDropDown .= '</select>';

                        ?>

              </div> --> 

                                                   

                          <!--<div class="col-md-4">

                            <div class="form-group">

                            <label>Sales Executive</label>

                            

                           <?php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">

                        <option value="">All</option>';

                          $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1'   ".$teamMembers."  $UserRestriction AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

                                    if($db->num_rows2($resUserLevel)){

                                      while($resultUserLevel = $db->fetch_object2($resUserLevel)){

                                      if($_REQUEST['usernameid'] == $resultUserLevel->id){

                                        $selected = 'selected="selected"';

                                      }else{

                                        $selected = '';

                                      }

                                      $categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';

                                    }

                                    }

                                    echo $categoryDropDown .= '</select>';

                                    ?>

                                                          

                                                          </div>

                          </div>-->

                          <!-- /.col -->

                           

                          

                          <!-- /.row -->

                        </div>

                      </div>

                      <!-- /.box-body -->

                      <div class="box-footer">

                       <!-- <input name="Search" type="submit" class="btn btn-primary" value="Search" />-->

                        <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />

                        </div>

                        

                    </form>



			

            <!-- /.box-body -->

          </div>

          <!-- /.box -->

        </div>

        <!-- /.col -->

      </div>

      <!-- /.row -->

    </section>

    <!-- /.content -->

  </div>

                                      

<?php include_once("includes/footer.php")?>  