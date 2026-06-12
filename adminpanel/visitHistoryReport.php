<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],'visit_history','view');



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
    //list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
	$report_date1= explode(" to ",$_REQUEST['report_date']);
	$checkin = $report_date1['0'];
	$checkout = $report_date1['1'];
  }
	 
  $rnsLastDate = date('Y-04-01',strtotime('-1 years',strtotime($checkout)));
  $rnsThisDate = date('Y-04-01',strtotime('+1 years',strtotime($checkout)));
  $rnsLast=0;
  $rnsThis=0;

  $rnsLast=selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)','WHERE id_company="'.$_REQUEST['search_name'].'" AND month BETWEEN "'.$rnsLastDate.'" AND "'.date('Y-03-31',strtotime($checkout)).'" ');

  $rnsThis=selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)','WHERE id_company="'.$_REQUEST['search_name'].'" AND month BETWEEN "'.date('Y-04-01',strtotime($checkout)).'" AND "'.date('Y-03-31',strtotime($rnsThisDate)).'" ');

  $userType=selectColumn(TBL_USERS,'user_type','WHERE id="'.$_SESSION['userId'].'"');

  if($userType==2)
    $condUserType="AND id_user='".$_SESSION['userId']."'";
   
  $sql="SELECT * FROM ".TBL_VISIT." WHERE dated BETWEEN '".date('Y-m-d',strtotime($checkin))."' AND '".date('Y-m-d',strtotime($checkout))."' AND id_company='".$_REQUEST['search_name']."' ".$condUserType." AND ".TBL_VISIT.".id_shop='".$_SESSION['shop']."' ";

    /*if($_REQUEST['teamId'] != ''){
        $sql .= " AND FIND_IN_SET(B.ids_team,'".$_REQUEST['teamId']."') ";
    }   

      
    else{
      if($_SESSION['userLevel'] !=1){
       $sql .= " AND B.`id` = '".$_SESSION['userId']."'";
      }
    } */
    $sql .= " ORDER BY dated DESC"; 
    //echo $sql;
    //exit;  
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
      $objDrawing->setCoordinates('F1');        //set image to cell
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
      ->setCellValue('A8', ' '.$companyName.' Company History Report from  '.$_REQUEST['report_date'])
      ->setCellValue('A6', 'Company Name:   '.$companyName.' ')
      ->setCellValue('I6', 'Last Year RNS:   '.$rnsLast.' ')
      ->setCellValue('I7', 'Present Year RNS: '.$rnsThis.' ')

      //->setCellValue('F6', 'Company Handeled By:  '.$executive )
      
      ->setCellValue('A7', 'Address:  '.selectColumn(TBL_COMPANY,'address','WHERE id_company="'.$_REQUEST['search_name'].'"'))
      ;
      $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);
      //$objPHPExcel->getActiveSheet()->getStyle('H9')->getAlignment()->setWrapText(true);

       $objPHPExcel->getActiveSheet()->mergeCells('A8:J8');
       $objPHPExcel->getActiveSheet()->mergeCells('A6:C6') ;    
       $objPHPExcel->getActiveSheet()->mergeCells('I6:J6') ; 
       $objPHPExcel->getActiveSheet()->mergeCells('I7:J7') ; 
       //$objPHPExcel->getActiveSheet()->mergeCells('F6:H6') ;
       $objPHPExcel->getActiveSheet()->getStyle("A6:J7")->getFont()->setBold( true );
      $head_hotel_row = 9;

      $head_cntr_column = "A";$head_hotel_column = "A";

      $objPHPExcel->setActiveSheetIndex(0)

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive Visited')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit Date')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Person Met')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Contact No')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Discussion Summary')
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

  cellColor('A8:J8','254061');

  cellColor('A9:J9','75923c');





    $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($styleArray);

     $objPHPExcel->getActiveSheet()->getStyle('A8:J8')->getAlignment()->applyFromArray(

        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

    );
    $objPHPExcel->getActiveSheet()->getStyle('A9:J9')->applyFromArray($styleArray_1);

     $objPHPExcel->getActiveSheet()->getStyle('A9:J9')->getAlignment()->applyFromArray(

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

    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(

        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)

    );
    $objPHPExcel->getActiveSheet()->getStyle('F9')->getAlignment()->applyFromArray(

        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)

    );
    $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->applyFromArray(

        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)

    );

    $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->applyFromArray(

        array('vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)

    );
    $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(

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
      
      $head_hotel_row=10;
      
      $sno=1;
      while($rowData = mysqli_fetch_object($resCom)){
        $head_cntr_column='A';
        $objPHPExcel->setActiveSheetIndex(0)

          ->setCellValue($head_cntr_column++.$head_hotel_row, $sno)
          ->setCellValue($head_cntr_column++.$head_hotel_row, selectColumn(TBL_USERS,'name','WHERE id="'.$rowData->id_user.'" '))
          ->setCellValue($head_cntr_column++.$head_hotel_row, date('d/m/Y',strtotime($rowData->dated)))
          ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_CUSTOMER,'CONCAT(title," ",first_name," ",last_name)','WHERE id_customer="'.$rowData->id_contacts.'" '))
          ->setCellValue($head_cntr_column++.$head_hotel_row,selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$rowData->id_contacts.'" '))
          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->discussion_summary)
          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->Total)
          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->entertainment)
          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->lunch)
          ->setCellValue($head_cntr_column++.$head_hotel_row++,($rowData->Total+$rowData->entertainment+$rowData->lunch)); 
          $sno++;
      }
      $objPHPExcel->getActiveSheet()->getStyle('B'.$head_hotel_row)->getAlignment()->applyFromArray(

        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)

    );
      $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('C'.$head_hotel_row,'Total');
      $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('G'.$head_hotel_row,'=SUM(G10:G'.($head_hotel_row-1).')');
      $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('H'.$head_hotel_row,'=SUM(H10:H'.($head_hotel_row-1).')');
      $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('I'.$head_hotel_row,'=SUM(I10:I'.($head_hotel_row-1).')'); 
      $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('J'.$head_hotel_row,'=SUM(J10:J'.($head_hotel_row-1).')');     

      $objPHPExcel->getActiveSheet()->getStyle('B'.$head_hotel_row.':J'.$head_hotel_row)->getFont()->setBold( true );

      

        $objPHPExcel->getActiveSheet()->mergeCells('C'.$head_hotel_row.':F'.$head_hotel_row);             
      cellColor('C'.$head_hotel_row.':J'.$head_hotel_row++,'b5d8ab');
      


      $objPHPExcel->getActiveSheet()->getStyle('A8:J'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);
    

      

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(10);  

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(20); 

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(15); 

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);
    
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(50);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(15);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(22);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(15);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(15);
      

      $forTotal = 'C';

      $totalArray = array(

        'font'  => array(

            'bold'  => true,

            'color' => array('rgb' => '1e51bf'),

            'size'  => 12,

            'name'  => 'Verdana'

        ));

      
    }
    $objPHPExcel->getActiveSheet()->setTitle('Company History Report');


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
      header('Content-Disposition: attachment;filename="Company_History_Report_'.date('d-m-Y H:i:s').'.xls"');

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
        Company History Report
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales Report/Company History Report</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">
	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
        
        <!-- /.box-header -->
		<?php /*<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Closing Type</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>			  
              <!-- /.form-group -->
            </div>
			
            <!-- /.col -->  
			<div class="col-md-6">
              <div class="form-group">
                <label>Status</label>				
				<?php 
					if($_REQUEST['status'] == '1'){
							$selected1 = 'selected="selected"';
					}elseif($_REQUEST['status'] == '0'){
							$selected0 = 'selected="selected"';
					}
				  echo $statusDropDown = '<select class="form-control select2" name="status"> <option value="">Both</option>
				  <option '.$selected1.' value="1">Active</option>
				  <option '.$selected0.' value="0">Inactive</option>
				  </select>';?>
              </div>
              <!-- /.form-group -->
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" />
        </div>
		</form>*/?>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Company History Report</h3>
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

			<?php /* <form name="listingForm" action="" method="post">

               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>


            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />-->S.No.&nbsp;</th>
                  <th>Date</th>
                  <th>Activity Type</th>
                  <th>Description</th>
                   <th>Executive</th>
				          <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 
				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                  <td><?=date('d-M-Y',strtotime($row->dated));?></td>
                  <td><?=selectColumn(TBL_OTHER_ACTIVITY,'name','WHERE id="'.$row->id_other_activity.'" AND id_shop="'.$_SESSION['shop'].'" ');?></td>
                  <td><?=$row->description;?></td>
                  <td><?=selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_user.'"');?></td>
                  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='otherEntry.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<!--<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);"/>--></td>
                </tr>
               <?php }?> 
			   <!--<tr>
                     <td align="left" colspan="4">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
				<tr>	 
					  <td align="right" colspan="6"><?php  echo $pagging->getLinks();?> </td>
                 </tr>                
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="6">---- No Record Found ---- </td>
                 </tr>                 
				<?php }?>
                </tbody>                
              </table>			  
            </div>
		  </form> */ ?>
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