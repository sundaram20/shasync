<?php include_once("../config/auto_loader.php");



checkUserLevelPermission($_SESSION['userLevel'],'company_consilidated_visit_history','view');



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





if($_REQUEST['Download']=='Download'){



  if($_REQUEST['report_date'] != ''){

   // list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
$report_date1= explode(" to ",$_REQUEST['report_date']);
	$checkin = $report_date1['0'];
	$checkout = $report_date1['1'];
	
  }

	 

  

if($_REQUEST['search_name'] != ''){



	$sqlConnect = " AND `".TBL_VISIT."`.id_company = '".addslashes($_REQUEST['search_name'])."'";



}



//$sql="SELECT *,sum(Total) as NewTotal, sum(entertainment) as Newentertainment, sum(lunch) as Newlunch FROM ".TBL_VISIT." WHERE dated BETWEEN '".date('Y-m-d',strtotime($checkin))."' AND '".date('Y-m-d',strtotime($checkout))."'  AND ".TBL_VISIT.".id_shop='".$_SESSION['shop']."' $sqlConnect ";



   $sql="SELECT count(id) as TotalRecord,".TBL_VISIT.".id_company,sum(Total) as NewTotal, sum(entertainment) as Newentertainment, sum(lunch) as Newlunch FROM ".TBL_VISIT." LEFT JOIN ".TBL_COMPANY." ON ".TBL_VISIT.".id_company = ".TBL_COMPANY.".id_company WHERE dated BETWEEN '".date('Y-m-d',strtotime($checkin))."' AND '".date('Y-m-d',strtotime($checkout))."'  AND ".TBL_VISIT.".id_shop='".$_SESSION['shop']."' $sqlConnect AND ".TBL_COMPANY.".area IN (".$_SESSION['teamMyAreas'].") ";



    /*if($_REQUEST['teamId'] != ''){

        $sql .= " AND FIND_IN_SET(B.ids_team,'".$_REQUEST['teamId']."') ";

    }   



      

    else{

      if($_SESSION['userLevel'] !=1){

       $sql .= " AND B.`id` = '".$_SESSION['userId']."'";

      }

    } */

    $sql .= " GROUP BY `".TBL_VISIT."`.id_company ORDER BY TotalRecord DESC"; 

    $sql;

  

    $resCom = mysqli_query($connNew,$sql); 

    $numRows = mysqli_num_rows($resCom);



  /*if($_REQUEST['usernameid'] != ''){

    $sql .= " AND `id_user` = '".addslashes($_REQUEST['usernameid'])."'";

  }  

  else{

    $sql .= " AND `id_user` = '".$_SESSION['userId']."'";

  }*/ 



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

      $objDrawing->setCoordinates('C1');        //set image to cell

      /*$objDrawing->setWidth(200);                 //set width, height



      $objDrawing->setHeight(150);*/  



      $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());



      }  //save



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

      $objPHPExcel->setActiveSheetIndex(0)

      ->setCellValue('A6', ' '.$companyName.'  Company Visited Report from  '.$_REQUEST['report_date']);

      

    

      $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);

      //$objPHPExcel->getActiveSheet()->getStyle('H9')->getAlignment()->setWrapText(true);



       $objPHPExcel->getActiveSheet()->mergeCells('A6:G6');

      

       $objPHPExcel->getActiveSheet()->getStyle("A6:G7")->getFont()->setBold( true );

      $head_hotel_row = 7;



      $head_cntr_column = "A";$head_hotel_column = "A";



      $objPHPExcel->setActiveSheetIndex(0)



        ->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')

      

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Total Visits')

        

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Conveyance')

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Entertainment')

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Lunch')

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Total Expense');      



        







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







    function cellColor($cells,$color){

      global $objPHPExcel;

      $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(

          'type' => PHPExcel_Style_Fill::FILL_SOLID,

          'startcolor' => array(

          'rgb' => $color

        )

      ));

  }



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



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );



    $objPHPExcel->getActiveSheet()->getStyle('I6')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)



    );

    $objPHPExcel->getActiveSheet()->getStyle('I7')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)



    );

    $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );



    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );



    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );



    $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );

 $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );

    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );

    $objPHPExcel->getActiveSheet()->getStyle('F9')->getAlignment()->applyFromArray(



        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)



    );

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

        $head_cntr_column='A';

		

		 $objPHPExcel->getActiveSheet()->getStyle('B'.$head_hotel_row)->getAlignment()->applyFromArray(



        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)



    );

        $objPHPExcel->setActiveSheetIndex(0)



          ->setCellValue($head_cntr_column++.$head_hotel_row, $sno)

          ->setCellValue($head_cntr_column++.$head_hotel_row, html_entity_decode(selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$rowData->id_company.'"')).' - '.selectColumn(TBL_COMPANY,'city','WHERE id_company="'.$rowData->id_company.'"'))

          

          ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->TotalRecord)	

          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->NewTotal)

          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->Newentertainment)

          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->Newlunch)

          ->setCellValue($head_cntr_column++.$head_hotel_row++,($rowData->NewTotal+$rowData->Newentertainment+$rowData->Newlunch)); 

          $sno++;

      } 

      $objPHPExcel->getActiveSheet()->getStyle('B'.$head_hotel_row)->getAlignment()->applyFromArray(



        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)



    );

      $objPHPExcel->setActiveSheetIndex(0)

          ->setCellValue('B'.$head_hotel_row,'Total');
      $objPHPExcel->setActiveSheetIndex(0)

          ->setCellValue('C'.$head_hotel_row,'=SUM(C8:C'.($head_hotel_row-1).')');

      $objPHPExcel->setActiveSheetIndex(0)

          ->setCellValue('D'.$head_hotel_row,'=SUM(D8:D'.($head_hotel_row-1).')');

      $objPHPExcel->setActiveSheetIndex(0)

          ->setCellValue('E'.$head_hotel_row,'=SUM(E8:E'.($head_hotel_row-1).')');

      $objPHPExcel->setActiveSheetIndex(0)

          ->setCellValue('F'.$head_hotel_row,'=SUM(F8:F'.($head_hotel_row-1).')'); 

      $objPHPExcel->setActiveSheetIndex(0)

          ->setCellValue('G'.$head_hotel_row,'=SUM(G8:G'.($head_hotel_row-1).')');     



      $objPHPExcel->getActiveSheet()->getStyle('B'.$head_hotel_row.':G'.$head_hotel_row)->getFont()->setBold( true );



      



        $objPHPExcel->getActiveSheet()->mergeCells('C'.$head_hotel_row.':C'.$head_hotel_row);             

      cellColor('B'.$head_hotel_row.':G'.$head_hotel_row++,'b5d8ab');

      





      $objPHPExcel->getActiveSheet()->getStyle('A6:G'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);

    



      



    $objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(10);  



    $objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(45); 



    $objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(15); 



    $objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);

    

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(20);

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(20);

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(15);

 

      



      $forTotal = 'C';



      $totalArray = array(



        'font'  => array(



            'bold'  => true,



            'color' => array('rgb' => '1e51bf'),



            'size'  => 12,



            'name'  => 'Verdana'



        ));



      

    }

    $objPHPExcel->getActiveSheet()->setTitle('Company Visited Report');





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

      header('Content-Disposition: attachment;filename="Company_Visited_Report_'.date('d-m-Y H:i:s').'.xls"');



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

        Company Visited Report

        <small></small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Sales Report/Company Visited Report</li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">		

	

      <div class="row">

        <div class="col-xs-12">		     

          <!-- /.box -->

          <div class="box">

            <div class="box-header">

              <h3 class="box-title">Company Visited Report</h3>

            </div>

                    <form name="searchForm" action="" method="get">

                      <input type="hidden" value="1" name="searchFormSubmit" />

                      <div class="box-body">

                        <div class="row">

                          <div class="form-group col-sm-6">



                              <label for="reservation_date">From - To </label>



                              <div class="input-group">



                                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>



                                <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  automcomplete="off">

                         </div>     



                              </div>



                        <div class="form-group col-sm-6">
 <label for="id_company">Company Name - City </label>

                  <select class="form-control select2 itemName" name="search_name" id="search_name"  required="required" >

                  </select>

              </div>  

                                                   

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
<script>
  //COMPANY AUTO COMPLETE START==================================================================
	comCheck = () =>{
		window.location.href='https://www.roomstatushub.in/sync/adminpanel/index.php';
	}
     $('.itemName').select2({
        placeholder: 'Select Company',
        ajax: {
          url: "ajax/ajaxSearchCompanyName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
	  //COMPANY AUTO COMPLETE END==================================================================
 </script>
