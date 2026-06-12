<?php include_once("../config/auto_loader.php");

//checkUserLevelPermission($_SESSION['userLevel'],TBL_OTHER,'view');


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
  
  $sql="SELECT A.name AS company,A.city,A.company_credibility AS credit_status,A.credit_limit,B.name AS executive, B.ids_team AS team FROM ".TBL_COMPANY." AS A
      LEFT JOIN ".TBL_AREAS."  ON A.area = ".TBL_AREAS.".id
      LEFT JOIN ".TBL_USERS." AS B ON ".TBL_AREAS.".user_id= B.id 
      WHERE A.id_shop='".$_SESSION['shop']."' AND ".TBL_AREAS.".id IN (".$_SESSION['teamMemberAreas'].") ";

    if($_REQUEST['teamId'] != ''){
        $sql .= " AND FIND_IN_SET(B.ids_team,'".$_REQUEST['teamId']."') ";
    }   

    if($_REQUEST['credit_status'] != ''){
        $sql .= " AND A.`company_credibility` = '".addslashes($_REQUEST['credit_status'])."'";
    }  

    if($_REQUEST['usernameid'] != ''){
        $sql .= " AND B.`id` = '".addslashes($_REQUEST['usernameid'])."'";
    }  
    else{
      if($_SESSION['userLevel'] !=1){
       $sql .= " AND B.`id` = '".$_SESSION['userId']."'";
      }
    } 
    $sql .= " ORDER BY A.city,B.name "; 
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
                     ->setTitle("Credit REPORT")
                     ->setSubject("Credit REPORT")
                     ->setDescription("Credit REPORT")
                     ->setKeywords("Credit REPORT")
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
      $objDrawing->setCoordinates('D1');        //set image to cell
      /*$objDrawing->setWidth(200);                 //set width, height

      $objDrawing->setHeight(150);*/  

      $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

      }  //save


      if($numRows > 0){

      $counter = 1;

      $objPHPExcel->setActiveSheetIndex(0)

      ->setCellValue('A6', 'Credit Report as on '.date('d F Y'));
        $objPHPExcel->getActiveSheet()->mergeCells('A6:G6');

      $head_hotel_row = 7;

      $head_cntr_column = "A";$head_hotel_column = "A";

      $objPHPExcel->setActiveSheetIndex(0)

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'City')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive Name')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Team')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Credit Status')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Credit Limit');      

        



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
        $objPHPExcel->setActiveSheetIndex(0)

          ->setCellValue($head_cntr_column++.$head_hotel_row, $sno)
          ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->company)
          ->setCellValue($head_cntr_column++.$head_hotel_row, $rowData->city)
          ->setCellValue($head_cntr_column++.$head_hotel_row,$rowData->executive)
          ->setCellValue($head_cntr_column++.$head_hotel_row, selectColumn(TBL_TEAM,'name','WHERE id IN ('.$rowData->team.')'))
          ->setCellValue($head_cntr_column++.$head_hotel_row, ($rowData->credit_status==1?'Credit Allowed':'Credit Not Allowed'))
          ->setCellValue($head_cntr_column++.$head_hotel_row++, $rowData->credit_limit .' Lakhs'); 
          $sno++;
      }


      $objPHPExcel->getActiveSheet()->getStyle('A6:G'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()

        ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(10);  

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(25); 

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20); 

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(22);
    
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(20);

      

      $forTotal = 'C';

      $totalArray = array(

        'font'  => array(

            'bold'  => true,

            'color' => array('rgb' => '1e51bf'),

            'size'  => 12,

            'name'  => 'Verdana'

        ));

      
    }
    $objPHPExcel->getActiveSheet()->setTitle('Credit Report');



      

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
      header('Content-Disposition: attachment;filename="Sales_Summary_Report'.date('d-m-Y H:i:s').'.xls"');

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
        <small>Credit Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales Report/Credit Report</li>
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
    <?php/*<form name="searchForm" action="" method="get">
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
              <h3 class="box-title">Credit Report</h3>
            </div>
                    <form name="searchForm" action="" method="get">
                      <input type="hidden" value="1" name="searchFormSubmit" />
                      <div class="box-body">
                        <div class="row">
                        <!--<div class="form-group col-sm-3">
                           <label>Company Name - City</label>       
         <?php $categoryDropDown = '<select class="form-control select2" name="search_name" id="search_name">
                          <option value="">Select Company </option>';
                        $resCat = selectSql(TBL_COMPANY," where  id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' AND FIND_IN_SET(area,'".$_SESSION['teamMemberAreas']."')  ",' ORDER BY `name`');
                        if($db->num_rows2($resCat)){
                          while($resultCat = $db->fetch_object2($resCat)){
                          if($_REQUEST['search_name'] == $resultCat->name){
                            $selected = 'selected="selected"';
                          }else{
                            $selected = '';
                          }
                          $categoryDropDown .= '<option '.$selected.' value="'.htmlentities($resultCat->name).'">'.ucfirst($resultCat->name.'-'.$resultCat->city).'</option>';
                        }
                        }
                        echo $categoryDropDown .= '</select>';
                        ?>
              </div> -->  
                                                   
                          <div class="col-md-4">
                            <div class="form-group">
                            <label>Sales Executive</label>
                            <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
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
                          </div>
                          <!-- /.col -->
                           <div class="form-group col-sm-4">
                              <label>Credit Status</label>  
                              <select name="credit_status" class="form-control select2">
                                <option value="">Select Status</option>
                                <option value="1">Credit Allowed</option>
                                <option value="2">Credit Not Allowed</option>
                              </select>     
                            </div>  

                            <div class="col-md-4">
                            <div class="form-group">
                            <label>Team</label>
                            <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                           <?php $categoryDropDown = '<select class="form-control select2" name="teamId" id="teamId">
                        <option value="">All</option>';
                          $resUserLevel = selectSql(TBL_TEAM," WHERE `status` = '1'  AND  id IN (".$_SESSION['teamId'].") AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                                    if($db->num_rows2($resUserLevel)){
                                      while($resultUserLevel = $db->fetch_object2($resUserLevel)){
                                      if($_REQUEST['teamId'] == $resultUserLevel->id){
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
                          </div>
                          
                          <!-- /.row -->
                        </div>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                       <!-- <input name="Search" type="submit" class="btn btn-primary" value="Search" />-->
                        <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />
                        </div>
                        
                    </form>

      <?php/* <form name="listingForm" action="" method="post">

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
      </form>*/?>
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