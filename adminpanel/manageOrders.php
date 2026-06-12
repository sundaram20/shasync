<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');
?>


<?php 
//---------------------------------------------------------------------------------------------------------
 if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_ORDERS."` WHERE `id_order` IN (".addslashes($deleteIds).")";
	$delSqlImage = selectSql(TBL_ORDERS,"where `id_order` in (".addslashes($deleteIds).") ",'');	
	if(executeSql($delSql)){		
		$err = 0;
		while($delResultImage = $db->fetch_array2($delSqlImage)){
			if(file_exists($image_path.$delResultImage['image'])){
				@unlink($image_path.$delResultImage['image']);
				@unlink($image_path.'small-'.$delResultImage['image']);
				@unlink($image_path.'medium-'.$delResultImage['image']);
			}
		}
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}

// ----------cate---------
$sql = " SELECT * FROM `".TBL_ORDERS."` where `id_shop` = '".addslashes($_SESSION['shop'])."'";

if($_REQUEST['search_name'] != ''){
	$sql .= " AND (`reference` LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(reference,'-', code) LIKE '%".addslashes($_REQUEST['search_name'])."%' )";
}

if($_REQUEST['other_reference'] != ''){
	$sql .= " AND (`other_reference` LIKE '%".addslashes($_REQUEST['other_reference'])."%' || concat(other_reference,'-', code) LIKE '%".addslashes($_REQUEST['other_reference'])."%' )";
}
if($_REQUEST['hotelId'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 
	$sql .= " AND FIND_IN_SET (".addslashes($_REQUEST['hotelId']).",`".TBL_ORDERS."`.`id_hotel`)";
}

if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 
	 $sql .= " AND `".TBL_ORDERS."`.`id_hotel` IN  (".addslashes($_SESSION['HotelUserPermission']).")";
}
if($_REQUEST['booking_status'] != ''){
	$sql .= " AND `".TBL_ORDERS."`.`booking_status` = '".addslashes($_REQUEST['booking_status'])."%'";
}
if($_REQUEST['company_id'] != ''){
	$sql .= " AND `".TBL_ORDERS."`.`id_company` = '".addslashes($_REQUEST['company_id'])."'";
}
if($_REQUEST['guest'] != ''){
	$sql .= " AND `".TBL_ORDERS."`.`id_customer` = '".addslashes($_REQUEST['guest'])."'";
}
if($_REQUEST['payment_status'] != ''){
	$sql .= " AND `".TBL_ORDERS."`.`payment_status` = '".addslashes($_REQUEST['payment_status'])."'";
}

if($_REQUEST['booking_date'] != ''){
	$sql .= " AND `".TBL_ORDERS."`.`invoice_date` = '".date('Y-m-d',strtotime($_REQUEST['booking_date']))."'";
}

if($_REQUEST['checkin_date'] != ''){
	$sql .= " AND `".TBL_ORDERS."`.`checkin` = '".date('Y-m-d',strtotime($_REQUEST['checkin_date']))."'";
}

if($_REQUEST['searchFormSubmit']!='1'){
	
	$UniqueDateFor = date ('Y-m-d h:i:s'); 
	$StartDateListFor	=	strtotime("-1 day", strtotime($UniqueDateFor));
	$UniqueDateFor = date ("Y-m-d h:i:s", $StartDateListFor); 
	
	$sql .= " AND `".TBL_ORDERS."`.`last_modified` >= '".$UniqueDateFor."'";
	 
}
	 $sql .= ' order by id_order desc';
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
        Hotel Booking Manager
        <small>Manage Bookings</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Hotels</li>
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
        <div class="box-header with-border">
          <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3>
		  <div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="editOrders.php?type=N" >Book Now</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php /*?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ORDERS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ORDERS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
							  
							  </ul>
							</div>
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
		  <div class="col-md-4">
              <div class="form-group">
                <label>Reservation Id</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" placeholder="Enter Reservation Id" />
              </div>
			  
			  <div class="form-group">
                <label>Other Reference Id</label>				
				<input type="text" name="other_reference" id="other_reference" value="<?php echo trim($_REQUEST['other_reference']);?>" class="form-control" placeholder="Enter other reference Id" />
              </div>
              <!-- /.form-group -->
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Hotel</label>				
				<?php
				
				$hotelDropDown = '<select class="form-control select2" name="hotelId">
											    <option value="">Select Hotel</option>';
  $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['hotelId'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $hotelDropDown .= '</select>';
											  ?>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->  
			<?php /*?><div class="col-md-4">
              <div class="form-group">
                <label>Guest</label>				
				 <?php $guestDropDown = '<select class="form-control select2" name="guest">
											    <option value="">Select Guest</option>';
											  $resCat = selectSql(TBL_CUSTOMER," where type='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `first_name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['guest'] == $resultCat->id_customer){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">'.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).'</option>';
												}
											  }
											 	echo $guestDropDown .= '</select>';
											  ?>
              </div>
			  			
          </div><?php */?>
		  
		  
			<?php /*?><div class="col-md-4">
              <div class="form-group">
                <label>Source</label>				
				<?php $companyDropDown = '<select class="form-control select2" name="company_id">
											    <option value="">Select Source</option>';
											  $resCat = selectSql(TBL_COMPANY," where id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['company_id'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $companyDropDown .= '</select>';
											  ?>
              </div>
              <!-- /.form-group -->
            </div><?php */?>
			<div class="col-md-4">
              <div class="form-group">
                <label>Payment Status</label>				
				<?php $paymentDropDown = '<select class="form-control select2" name="payment_status">
											    <option value="">Select Payment Status</option>';
											  $resCat = selectSql(TBL_ORDER_STATE," ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['payment_status'] == $resultCat->id_order_state){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$paymentDropDown .= '<option '.$selected.' value="'.$resultCat->id_order_state.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $paymentDropDown .= '</select>';
											  ?>
              </div>
              <!-- /.form-group -->
            </div>
			<div class="col-md-4">
              <div class="form-group">
                <label>Booking Status</label>				
				<?php $bookDropDown = '<select class="form-control select2" name="booking_status">
											    <option value="">Select Booking Status</option>';
											  $resCat = selectSql(TBL_HTL_BOOKING_STATUS," ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['booking_status'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$bookDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $bookDropDown .= '</select>';
											  ?>
              </div>
              <!-- /.form-group -->
            </div>
			<div class="form-group col-sm-4">
                    <label for="booking_date">Booking Date </label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                     <input type="text" class="form-control pickerdate" placeholder="Enter booking date" id="booking_date" name="booking_date" value="<?php if($_REQUEST) echo $_REQUEST['booking_date'];?>">
                    </div>
                    <!-- /.input group -->
                    </div>
					
					<div class="form-group col-sm-4">
                    <label for="booking_date">Checkin Date </label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                     <input type="text" class="form-control pickerdate" placeholder="Enter Checkin date" id="checkin_date" name="checkin_date" value="<?php if($_REQUEST) echo $_REQUEST['checkin_date'];?>">
                    </div>
                    <!-- /.input group -->
                    </div>
          <!-- /.row -->
        </div>
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" />
        </div>
		</form>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Hotel List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="3%"> SNo</th>
				  <th>Reservation Id</th>
				  <th>Other reference Id</th>
                  <th>Hotel Name</th>
				  <th>Guest Name</th>
				  <th>Source</th>
				  <th>Payment Status</th> 
				  <th>Booking Status</th>     
				  <th>Booking Date</th>
                  <th>Checkin Date</th>                 
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
		
<?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>		

                  <td> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
				  <td><?=$row->reference; ?></td>
				  <td><?=$row->other_reference;?></td>
                  <td><?=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_hotel."'");?></td>
				  <td><?php 
				  $NameTitle	=	selectColumn(TBL_CUSTOMER,'title'," WHERE `id_customer` = '".$row->id_customer."'");
				  
				   $CustomerName	=	selectColumn(TBL_CUSTOMER,'CONCAT(first_name," ",last_name)'," WHERE `id_customer` = '".$row->id_customer."'");
				   echo $NameTitle.' '.$CustomerName;
				   
				   ?></td>
				  <td><?=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'");?></td>
				  <td><?=selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".$row->payment_status."'");?></td>
                  <td><?=selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".$row->booking_status."'");?></td>	
				  <td><?=dateformat_date($row->invoice_date);?></td>			 
                  <td><?=dateformat_date($row->checkin);?></td>			 
				  <td>
				 

	 	           


		  <?php if($row->type!='L'){ ?>
				  
				   <a href="pdf-template/generateOrderPdf.php?id=<?=encryptor('encrypt',$row->id_order)?>" target="_blank"><i class="fa fa-file-pdf-o"></i></a>&nbsp;&nbsp;
				  <a href="mail-template/sendOrderMail.php?id=<?=encryptor('encrypt',$row->id_order)?>" target="_blank"><i class="fa fa-paper-plane"></i></a>&nbsp;&nbsp;
				  
   
 <a href="editOrders.php?eId=<?=encryptor('encrypt',$row->id_order)?>&type=<?=$row->type;?>&action=edit&page=<?=$_REQUEST['page']?>" >
 <i class="fa fa-pencil-square-o" ></i></a>


    
				   <?php }else { ?>
				   
				    <a href="pdf-template/generateOrderPdf.php?id=<?=encryptor('encrypt',$row->id_order)?>" target="_blank"><i class="fa fa-file-pdf-o"></i></a>&nbsp;&nbsp;
				  <a href="mail-template/sendOrderMail.php?id=<?=encryptor('encrypt',$row->id_order)?>" target="_blank"><i class="fa fa-paper-plane"></i></a>&nbsp;&nbsp;
				  <a href="bookLunch.php?eId=<?=encryptor('encrypt',$row->id_order)?>&action=edit&page=<?=$_REQUEST['page']?>" ><i class="fa fa-pencil-square-o" ></i></a>
				    <?php } ?>
				  </td>
                </tr>
               <?php }?> 
			    <tr>
                     <td align="left" colspan="8">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					  </td>
				</tr>
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>
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