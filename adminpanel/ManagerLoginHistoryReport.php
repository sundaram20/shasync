<?php 
if(!isset($cron)){
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],'login_report','view');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
if($_REQUEST['report_date'] != ''){
	//list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
		$report_date= explode(" to ",$_REQUEST['report_date']);
		$checkin = $report_date['0'];
		$checkout = $report_date['1'];

}	
					  
}
		$shop_id=$_SESSION['shop'];			  

?>
<?php

	if($_REQUEST['Search'] == 'Search'){
	$db->query($sql);
	$numRows= $db->num_rows();
	$pagging = new pagingClass($sql,$setpage);
	$db->query($pagging->getQuery());
	$total = $db->num_rows();
 }?>
<?php
if($_REQUEST['Download'] == 'Download'){
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
		
		$sql = "SELECT a.*,count(a.id_user) as last30days ,DATE_FORMAT(a.login_date, '%m/%d/%Y'), b.hotel_access,c.name as HotelName,b.user_level,b.city,c.display_order
FROM `mst_login_history` as a 
LEFT JOIN fs_users as b ON b.id=a.id_user
LEFT JOIN fs_hotels as c ON c.id=b.hotel_access
WHERE a.`id_shop` = '".$shop_id."'";

if($_REQUEST['executive_user_id'] != ''){
				$sql .= " AND a.`id_user` = '".addslashes($_REQUEST['executive_user_id'])."'";
			}
  $sql .=  "AND a.login_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()";

 $sql .=  " group by a.id_user ORDER BY last30days,c.display_order ,b.city ,b.name ASC";
 
 //echo $sql;die; //ss

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
			$signature = "/home/admingcs/public_html/sync/uploaded_files/shop/".$logo.""; 
		}
		
		if(file_exists($signature)){
		$objDrawing->setPath($signature);
		$objDrawing->setOffsetX(25);                       //setOffsetX works properly
		$objDrawing->setOffsetY(10);                       //setOffsetY works properly
		$objDrawing->setCoordinates('D1');        //set image to cell
		 
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		}  //save
		if($numRows > 0){
		$counter = 1;
		if(!isset($cron)){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A6', 'Login History Report  from Last 30 Days ');
		}
		else{
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A6', 'Login History Report  Of Month '.date('M-Y',strtotime($checkout)));
		}	
		$objPHPExcel->getActiveSheet()->mergeCells('A6:G6');
		$head_hotel_row = 7;
		$head_cntr_column = "A";$head_hotel_column = "A";
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Hotel')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'City')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive Name')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Level')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Total Login in last 30 days')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Login Date');
			
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
	
	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
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
			
	$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()
	    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	    
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(6);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(35);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(25);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(22);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(22);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(20);	
	
	$head_hotel_row++;
	$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
						
				
		$Serialno=1;
		$connew = 8;
		while($row = mysqli_fetch_object($res)){
		$HotelAccess2=array();
		
		 $ExecutiveName	 =	selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->id_user." and id_shop=".$row->id_shop." ");
		$HotelAccess		=	selectColumn(TBL_USERS,'hotel_access'," WHERE `id` =".$row->id_user." and id_shop=".$row->id_shop." ");
		$UserLevelName		=	selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` =".$row->user_level." and id_shop=".$row->id_shop." ");
		
		$head_order_data1 = "A";
		$head_order_data = "A";       
		 $HotelAccess=explode(',',$HotelAccess);
		
foreach($HotelAccess as $hotelids){
    
    $HotelAccess2[]	=	$hotelids;
}
		
		
$UserSelectedHotelList     = implode(',',$HotelAccess2);
if($UserSelectedHotelList==''){
		
    $UserSelectedHotelList  ='RSO';
	
}

		
		$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY)
	);
	$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY)
	);
	$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY)
	);
	$UserCity	=	$row->city;	
		$UserCityName	=	$row->city;	
	if($UserCity==$UserCityReAssin){

	 $SCount	=	'';
	}else{
	    $mergeCityrow	=	$connew;

		$SCount	=	$Serialno++;
		}	
 $date_created	=	selectColumn( TBL_LOGIN_HISTORY,'date_created'," WHERE `id_user` =".$row->id_user." and id_shop=".$row->id_shop." order by id desc ");
 if($UserSelectedHotelList=='RSO'){
$UserSelectedHotelList='RSO';


 }else{
$UserSelectedHotelList	= selectColumn(TBL_HOTELS,'concat(name)'," WHERE `id` = '".$UserSelectedHotelList."'");
 }
 $objPHPExcel->getActiveSheet()->getStyle('A')->getFont()->setBold(false);
$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':G'.$connew)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $connew, $SCount)

->setCellValue($head_order_data++ . $connew, $UserSelectedHotelList)
->setCellValue($head_order_data++ . $connew, ucwords($UserCityName))
->setCellValue($head_order_data++ . $connew, $ExecutiveName)
->setCellValue($head_order_data++ . $connew, $UserLevelName)
->setCellValue($head_order_data++ . $connew, $row->last30days)
->setCellValue($head_order_data++ . $connew, date('d-m-Y h:i:s', strtotime($date_created)));    
$objPHPExcel->getActiveSheet()->getStyle('A6:G6')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A7:G7')->applyFromArray($styleThinBlackBorderOutline);

	$connew++;	
		
 $HotelAccess2='';
 
		
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
		$objPHPExcel->getActiveSheet()->setTitle('Login History Report');
		
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
	
	$fileName = 'Login_History_Report'.date('d_M_Y');
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
		else{
			if($numRows>0){
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				
				//local
				//$objWriter->save('../mailattach/'.$fileName.'.xls');
				
				//cron server
				$objWriter->save('/home/admingcs/public_html/sync/adminpanel/mailattach/'.$fileName.'.xls');
			}	
		}	

}
 
?>
<?php 
if(!isset($cron)){
	if($_REQUEST['Download']=='Download'){
		feedbackReport($conn,$_SESSION['shop'],$checkin,$checkout,$_REQUEST['executive_user_id'],$_REQUEST['feedType'],$_REQUEST['lead_status'],$objPHPExcel,$_SESSION['teamMembers'],$cron,$fileName);
	}
include_once("includes/header.php");?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper"> 
  
  <!-- Content Header (Page header) -->
  
  <section class="content-header">
    <h1> Login History Manager<small>Login History Report</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Login History Report</li>
    </ol>
  </section>
  
  <!-- Main content -->
  
  <section class="content">
    <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom">
        <div class="form-group has-error" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
        <div class="box-header with-border">
          <h3 class="box-title">Search <small>Total Records: (
            <?=$numRows;?>
            ) &nbsp;</small> </h3>
          <div class="btn-group  pull-right"><!--<a type="button" class="btn btn-success" href="editRateLetters.php" >Add Rate</a>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>-->
            
            <ul class="dropdown-menu" role="menu">
              <?php /*?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_RATE;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_RATE;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
            </ul>
          </div>
        </div>
        
        <!-- /.box-header -->
        
        <form name="searchForm" action="" method="get">
          <input type="hidden" value="1" name="searchFormSubmit" />
          <div class="box-body">
            <div class="row">
             
              
              
              <?php 
			 /* if($_SESSION['userLevel']==1){
				 	
				  $ConditonUserLevel = "";
				  }else{
					  $ConditonUserLevel= "  `".TBL_USERS."`.`id` = '".addslashes($_SESSION['userId'])."' AND ";
					  }*/
					  $perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
					  $resPer = mysqli_query($connNew,$perSql);
					  if($resPer){
					    	$perData	=	mysqli_fetch_object($resPer);
					      if($perData->calendar_user_list_approved == 0){
					  	   $UserRestriction	=" AND id='".$_SESSION['userId']."'";	
					      }
					  }
					  if($_SESSION['teamMembers'] !=""){
					    $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";
					  }
					  else{
					    $teamMembers ="";
					  }
			  ?>
              <div class="col-md-6">
                <div class="form-group">
                  <label>User</label>
                  
                  <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                  
                  <?php 
                        
         
                         $categoryDropDown = '<select class="form-control select2 " name="executive_user_id" id="executive_user_id" >
                                                        <option value="">All users</option>';
                                       
                                        $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                                        
                                        if($db->num_rows2($resUserLevel)){
                                          while($resultUserLevel = $db->fetch_object2($resUserLevel)){
                                          if($_SESSION['userId'] == $resultUserLevel->id){
                                            $selected = 'selected="selected"';
                                          }else{
                                            $selected = '';
                                          }
                                          $categoryDropDown .= '<option  value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
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
            
            <!--<input name="Search" type="submit" class="btn btn-primary" value="Search" />-->
            
            <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />
          </div>
        </form>
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"></h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
            
            <!-- /.box-header -->
            
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped text-center">
                
                <!--<thead>
                            <tr>
                              <th>S.No.&nbsp;</th>
                              <th>Hotel</th>
                              <th>Assign By</th> 
                               
                              <th>Assigned On</th> 
                              <th>Assign To</th>
                               
            				  <th>Feed Back  Date</th>                             
            				            				  
                              <th>Feed Back Summary</th>
                              <th>Status</th>
                             
                              <th>Action</th>
                              
            				  
                            </tr>
                            </thead>-->
                
                <tbody>
                  <?php 				 				
            				if($total > 0){$counter = 1;
            				  while($row = $db->fetch_object()){?>
                  <tr>
                    <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                    <td style="text-align:left;"><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'");  ?></td>
                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->id_user." and id_shop=".$_SESSION['shop']." ");  
                               ?></td>
                    <td><?php echo date('d M Y',strtotime($row->date_created));?></td>
                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->assign_user_id." and id_shop=".$_SESSION['shop']." ");  
                               ?></td>
                    <td><?=date('d M Y',strtotime($row->dated));?></td>
                    <td><?php echo selectColumn(TBL_FEEDBACK_DETAILS_EXPLOAD,'summary'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$row->id."' AND `visit_id` = '".$row->visit_id."'"); ?></td>
                    <td ><!--<button  class="btn <?php echo $row->lead_status==1?'btn-success':'btn-danger';?>" type="button"   ></button>--> 
                      <?php echo $row->lead_status==1?'Open':'Close';?></td>
                    
                    <!--<td>&nbsp;&nbsp;<img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='addreport.php?eId=<?=encryptor('encrypt',$row->visit_id)?>&action=edit&page=<?=$_REQUEST['page']?>';" /></td>--> 
                    
                  </tr>
                  <?php }?>
                  <tr>
                    <td align="right" colspan="13"><?php  echo $pagging->getLinks();?></td>
                  </tr>
                  <?php }else {?>
                  <tr> 
                    
                    <!--<td height="200" align="center" colspan="13">---- No Record Found ---- </td>--> 
                    
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
<div id="duplicate" class="well" style="display:none;"> </div>
<?php 
	
  include_once("includes/footer.php");
}?>
