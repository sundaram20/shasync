<?php 
if(!isset($cron)){
  include_once("../config/auto_loader.php");
  checkUserLevelPermission($_SESSION['userLevel'],'activity_log','view');
  //error_reporting(E_ALL);
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

  if($_REQUEST['report_date'] != ''){
      //list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
	   $report_date= explode(" to ",$_REQUEST['report_date']);
		$checkin = $report_date['0'];
		$checkout = $report_date['1'];
    }

  $checkin = date('01-m-Y',strtotime($checkin));
  $checkout = date('t-m-Y',strtotime($checkout));
  
 

  

}

//checkUserLevelPermission($_SESSION['userLevel'],TBL_OTHER,'view');
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/




if($_REQUEST['Download']=='Download'){
	
    if($_REQUEST['report_date'] != ''){
    //  list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
	  
	  $report_date= explode(" to ",$_REQUEST['report_date']);
		$checkin = $report_date['0'];
		$checkout = $report_date['1'];
    }

    $checkin = date('01-m-Y',strtotime($checkin));
    $checkout = date('t-m-Y',strtotime($checkout));

   

    function cellColor($cells,$color){
      global $objPHPExcel;
      $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
          'type' => PHPExcel_Style_Fill::FILL_SOLID,
          'startcolor' => array(
          'rgb' => $color
        )
      ));
    }

 function EmailLogReport($checkin,$checkout,$id_user,$id_shop,$cron,$connNew,$objPHPExcel,$fileName){


      if($_REQUEST['usernameid'] != ''){
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

       // if($_REQUEST['team'] != ''){
       // $sql .= " AND `id_user` = '".addslashes($_REQUEST['usernameid'])."'";
       // $teamName = selectColumn(TBL_TEAM,'name','WHERE id="'.$_REQUEST['team'].'" ');
      //}
      
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

      $objDrawing->setOffsetX(10);                       //setOffsetX works properly

      $objDrawing->setOffsetY(10);                       //setOffsetY works properly



      $objDrawing->setCoordinates('C2');        //set image to cell



      /*$objDrawing->setWidth(200);                 //set width, height

      $objDrawing->setHeight(150);*/  

      $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

      }  //save





      if($numRows == 0){

      $counter = 1;

      $objPHPExcel->setActiveSheetIndex(0)

      ->setCellValue('A8', 'Email Log Report from '.date('d-F',strtotime($checkin)).' to '.date('d-F-Y',strtotime($checkout)))
       ->setCellValue('A6', 'Activity Type : Sales Visit');
      //->setCellValue('B6', $exeName);
     // ->setCellValue('B6', 'Sa');
        //if($_REQUEST['team']!=''){
       // ->setCellValue('A7', 'Team Name : '.$teamName);
          //}

      $objPHPExcel->getActiveSheet()->getStyle("A6:B6")->getFont()->setBold( true );

      $objPHPExcel->getActiveSheet()->mergeCells('A8:D8');

      $head_hotel_row = 9;

      $head_cntr_column = "A";$head_hotel_column = "A";

      $objPHPExcel->setActiveSheetIndex(0)

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Date')

        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Day')
        ->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive Name')

       ->setCellValue($head_cntr_column++.$head_hotel_row, 'Team Name');      

        



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

        if($_REQUEST['usernameid'] != ''){
          $cond .= " AND e.`id_user` = '".addslashes($id_user)."'";    
        }
        /*else{
          $cond .= " AND `id_user` = '".$id_user."'";
        } */
        else{
          $cond .= " AND e.`id_user`!='' ";
        } 

      if($_REQUEST['teamId'] != ''){
         $cond .= " AND u.`myownteam_id` = '".addslashes($_REQUEST['teamId'])."'";    
     }

          $teamMembers = "AND u.id IN (".$_SESSION['teamMembers'].")";

        // selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_user."'")

     //  $query =" SELECT e.* from email_log  as e LEFT JOIN  ".TBL_USERS." AS u ON  e.id_user=u.id WHERE e.dated='".date('Y-m-d.',strtotime($checkin))."' ".$cond." ";
    //  $query =" SELECT e.*,u.* from email_log  as e LEFT JOIN  ".TBL_USERS." AS u ON  e.id_user=u.id WHERE  e.id_user!='' AND  e.dated  BETWEEN  '".date('Y-m-d.',strtotime($checkin))."' AND '".date('Y-m-d.',strtotime($checkin))."' ".$cond." ";
        $query =" SELECT e.*,u.* from email_log  as e LEFT JOIN  ".TBL_USERS." AS u ON  e.id_user=u.id WHERE  e.id_user!='' AND  e.dated  BETWEEN  '".date('Y-m-d.',strtotime($checkin))."' AND '".date('Y-m-d.',strtotime($checkin))."' ".$cond."   ".$teamMembers."  ";

   // echo $query;die();

       $resQue = mysqli_query($connNew,$query);
        $rowCount = mysqli_num_rows($resQue);
       while($rowData=mysqli_fetch_object($resQue)){
      $name =   selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowData->id_user."'");
       $teamName =   selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowData->myownteam_id."'");

  //  echo  'Team :'.$teamName;
   // die();
         $objPHPExcel->setActiveSheetIndex(0)

           ->setCellValue($head_cntr_column++.$head_hotel_row, date('d/m/Y',strtotime($checkin)))

           ->setCellValue($head_cntr_column++.$head_hotel_row, date('l',strtotime($checkin)))
         // ->setCellValue($head_cntr_column++.$head_hotel_row,('Sales Visit') )

           ->setCellValue($head_cntr_column++.$head_hotel_row,($name))

           ->setCellValue($head_cntr_column++.$head_hotel_row++, ($teamName)); 
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

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(30);  

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(30); 

    $objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(30); 

     $objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(50);
    

      

      $forTotal = 'C';

      $totalArray = array(

        'font'  => array(

            'bold'  => true,

            'color' => array('rgb' => '1e51bf'),

            'size'  => 12,

            'name'  => 'Verdana'

        ));

      
    }
    $objPHPExcel->getActiveSheet()->setTitle('Email Log Report');



      

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
      if(!isset($cron)){
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="Email_Log_Report'.date('d-m-Y H:i:s').'.xls"');

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
if(!isset($cron)){
if($_REQUEST['Download']=='Download')  
emailLogReport($checkin,$checkout,$_REQUEST['usernameid'],$_SESSION['shop'],$cron,$connNew,$objPHPExcel,$fileName);
  

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
        <small>Log Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales Report/Log Report</li>
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
		<?php /*  ?> 
		<form name="searchForm" action="" method="get">
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
		</form><?php  */ ?>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Log Report</h3>
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

                            <!-- /.input group --> 

                            <span id="reservation_dateError"></span> </div>
                                                   
                     

                          <!--team starts-->
                        <div class="col-md-4">
                            <div class="form-group ">
                               <label>Team</label>
                            <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                           <?php $categoryDropDown = '<select class="form-control select2" name="teamId"  onchange="getExeOfRso(this.value);"  id="teamId">
                              <option value="">All</option>';

                          $resUserLevel = selectSql(TBL_TEAM," WHERE `status` = '1' AND  id IN (".$_SESSION['teamId'].")  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
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

                       <?php  echo $_REQUEST['teamId']; ?>
                        <!--team ends-->
                          <!-- /.col -->

                               <div class="col-md-4">
                            <div class="form-group">
                            <label>Sales Executive</label>
                           
                            <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                           <?php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">
                              <option value="">All</option>';
                            
                          $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1'  AND `sales_status_active` = '1'  ".$teamMembers."  $UserRestriction AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
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
		  </form>*/ ?>
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
                                      
<?php include_once("includes/footer.php");
}
?>  

<script type="text/javascript">
  function getExeOfRso(id) {
    $.ajax({
      type : 'POST',
      url  : 'ajax/ajaxGetExeOfRso.php',
      data : 'rso_id='+id,
      success : function (result) {
        if(result !=0)
          $("#usernameid").html(result);
      }
    });
  }
</script>