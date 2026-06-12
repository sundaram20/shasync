<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_OTHER,'view');

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


if($_REQUEST['Search']=='Search' || $_REQUEST['Download']=='Download'){
	$sql = "SELECT * FROM `".TBL_OTHER."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'  ";
}


if($_REQUEST['usernameid'] != ''){
	$sql .= " AND `id_user` = '".addslashes($_REQUEST['usernameid'])."'";
}
else{
  if($_SESSION['userLevel'] !=1){
    $sql .= " AND `id_user` = '".$_SESSION['userId']."'";
  }  
}


if($_REQUEST['report_date'] != ''){
 
  list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
  
  $sql .= " AND dated BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";  
}
if($_REQUEST['status'] != ''){
	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
}
if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `date_created` DESC";
}else{
	$sql .= " ORDER BY `date_created` DESC";
}
//echo $sql;
if($_REQUEST['eId'] !="" && $_REQUEST['action']=='delete'){
  
  //checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILYVISIT,'delete');
  $dsrNoDays  =selectColumn(TBL_USERS,'dsr_num_days'," WHERE `id` = '".$_SESSION['userId']."'");
  //echo "<br>";

  $validDate = abs(date('d',$_REQUEST['dated'])-date('d',strtotime(date('Y-m-d'))));

  //exit;

  if($validDate<=$dsrNoDays || $_SESSION['userLevel']==1){
    $deleteCal = "DELETE FROM `".TBL_OTHER."` WHERE id_shop='".$_SESSION['shop']."' AND id='".$_REQUEST['eId']."' ";

    if(mysqli_query($connNew,$deleteCal)){
      $_SESSION['errorMsg'] = "Entry Deleted successfully";
    }
    
  }
  else{
    $_SESSION['errorMsg'] = "You can't delete Entry created ".$dsrNoDays." days ago.";
  } 


}


$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();



if($_REQUEST['Download']=='Download'){

	

	function cellColor($cells,$color){

	    global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(

	        'type' => PHPExcel_Style_Fill::FILL_SOLID,

	        'startcolor' => array(

             'rgb' => $color

	       )

	    ));

	}

	

		 error_reporting(1);

	

		



	$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

	
	$shop_id	=	$_SESSION['shop'];

		 $EnqSql = mysqli_query($conn,$sql);

		if($EnqSql){

			$numRows = mysqli_num_rows($EnqSql);

		}

		//		die;

		// Set document properties

		$objPHPExcel->getProperties()->setCreator("Shafeer")

									 ->setLastModifiedBy("Shafeer")

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

			$signature = "/home/admingcs/public_html/sync/uploaded_files/shop/".$logo.""; 

		}

		

		if(file_exists($signature)){

		$objDrawing->setPath($signature);

		$objDrawing->setOffsetX(25);                       //setOffsetX works properly

		$objDrawing->setOffsetY(10);                       //setOffsetY works properly

		$objDrawing->setCoordinates('C1');        //set image to cell

		 

		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		}  //save

		if($numRows > 0){

		$counter = 1;

		if(!isset($cron)){

			$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue('A6', 'Sales Activity Summary Report   '.$CreationOrBookingDate);

		}

		else{

			$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue('A6', 'Sales Activity Summary  '.date('M-Y',strtotime($checkin)).' To '.date('M-Y',strtotime($checkout)));

		}	

				 	 				 	 



		



		$objPHPExcel->getActiveSheet()->mergeCells('A6:E6');

		$head_hotel_row = 7;

		$head_cntr_column = "A";$head_hotel_column = "A";

		$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Generation Date')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Activity Type')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Description')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive');

			
	

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

  

cellColor('A6:E6','254061');

cellColor('A7:E7','75923c');

	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

	 $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7:E7')->applyFromArray($styleArray_1);

	 $objPHPExcel->getActiveSheet()->getStyle('A7:E7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

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

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	

	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(

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

	$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);		

	$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()

	    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	    

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(6);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(20);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(30);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(40);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(30);	

	
	

	$head_hotel_row++;

	$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

						

				

		$Serialno=1;

		$connew = 8;

		$head_cntr_column = "A";$head_hotel_column = "A";

		

		while($row = mysqli_fetch_object($EnqSql)){

		
		$head_order_data1 = "A";

		$head_order_data = "A";       





		

		

		$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY)

	);

		$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY)

	);

		$objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);



	

	

	    $mergeCityrow	=	$connew;



		//$SCount	=	$Serialno++;

	



            
                    

 $objPHPExcel->getActiveSheet()->getStyle('A')->getFont()->setBold(false);

$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':E'.$connew)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->setActiveSheetIndex(0)

->setCellValue($head_order_data++ . $connew, $Serialno++)

->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($row->dated)))
->setCellValue($head_order_data++ . $connew, selectColumn(TBL_OTHER_ACTIVITY,'name','WHERE id="'.$row->id_other_activity.'" AND id_shop="'.$_SESSION['shop'].'" '))
->setCellValue($head_order_data++ . $connew, $row->description) 
->setCellValue($head_order_data++ . $connew, selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_user.'"'));




   

$objPHPExcel->getActiveSheet()->getStyle('A6:E6')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A7:E7')->applyFromArray($styleThinBlackBorderOutline);



	$connew++;	

		

 

 

		

		//$UserCityReAssin  =$UserCity2;

		//$Serialno =$EndSerialno++;

	}

		$forTotal = 'C';

		$totalArray = array(

	    'font'  => array(

	        'bold'  => true,

	        'color' => array('rgb' => '1e51bf'),

	        'size'  => 12,

	        'name'  => 'Verdana'

	    ));

				

	}//exit;

		$objPHPExcel->getActiveSheet()->setTitle('Sales Activity Summary');

		

		$objPHPExcel->setActiveSheetIndex(0);

		

		

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

/*$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);	

		

	*/		

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

	

	$fileName = 'Sales_Activity_Summary_'.date('d_M_Y');

		ob_end_clean();

		if(!isset($cron)){

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

}



?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sales Report
        <small>Sales Activity List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales Report/Sales Activity</li>
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

                            <!-- /.input group --> 

                            <span id="reservation_dateError"></span> </div>
                                                   
                          <div class="col-md-6">
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

                          
                          
                          <!-- /.row -->
                        </div>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <input name="Search" type="submit" class="btn btn-primary" value="Search" style="float:left;" />
                        <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" style="margin-left:10px;float:left;" />

          <a title="Clear" class="pull-left btn btn-success" href="manageInHouse.php" style="margin-left:10px;color:#fff;font-weight:bold; float:left;">&nbsp;Clear</a>   

                        </div>
                        
                    </form>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Sales Activity</h3>
              <a title="Add Other Entry" class="btn btn-success pull-right" href="otherEntry.php" style="color:#fff;font-weight:bold;">&nbsp;ADD SALES ACTIVITY</a>
            </div>
                    

			<form name="listingForm" action="" method="post">

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
                  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='otherEntry.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);"/></td>
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
<script type="text/javascript">
  function deleteMe(id){
    window.location.href="manageInHouse.php?&eId="+id+"&action=delete";
  }
</script>
