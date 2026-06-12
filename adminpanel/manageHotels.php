<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
$image_path = $UPLOAD_FILES.'/hotel_gallery/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

if($_SESSION['unit_user']==2){
	$displayHide = 'style="display:none;"';
	$displayReadOnly = 'readonly="readonly"';
}
//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_HOTELS."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($_REQUEST['activeId'])."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_HOTELS."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($_REQUEST['inactiveId'])."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = ''.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = ''.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'delete');
	$delSql = "DELETE FROM `".TBL_HOTELS."` WHERE `id` = '".$_REQUEST['delId']."'";
	$sqlDelUserLevel = selectRow(TBL_HOTELS," WHERE `id` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;
		if(file_exists($image_path.$sqlDelUserLevel['image'])){
			@unlink($image_path.$sqlDelUserLevel['image']);
			@unlink($image_path.'small-'.$sqlDelUserLevel['image']);
			@unlink($image_path.'medium-'.$sqlDelUserLevel['image']);
		}
		$_SESSION['successMsg'] = 'One Hotel '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete hotel '.$sqlDelUserLevel["name"];
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_HOTELS."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_HOTELS."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_HOTELS."` WHERE `id` IN (".addslashes($deleteIds).")";
	$delSqlImage = selectSql(TBL_HOTELS,"where `id` in (".addslashes($deleteIds).") ",'');	
	if(executeSql($delSql)){		
		$err = 0;
		while($delResultImage = mysqli_fetch_array($delSqlImage)){
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
$sql = " SELECT * FROM `".TBL_HOTELS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ";
if(!empty($_SESSION['hotel_access'])){
$sql .= " AND `id` in (".addslashes($_SESSION['hotel_access']).")";
}
if($_REQUEST['search_name'] != ''){
	//$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
	$sql .= " AND `id` = '".addslashes($_REQUEST['search_name'])."'";
}
if($_REQUEST['status'] != ''){
	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
}
if($_REQUEST['hotel_category'] != ''){
	$sql .= " AND `hotel_category` = '".addslashes($_REQUEST['hotel_category'])."'";
}
if($_REQUEST['zonal'] != ''){
	$sql .= " AND `zonal` = '".addslashes($_REQUEST['zonal'])."'";
}
if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `display_order` asc";
}else{
	$sql .= " ORDER BY `display_order` asc";
}
//echo $sql;

$db->query($sql);
$numRows= $db->num_rows();
//$pagging = new pagingClass($sql,$setpage);
//$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Product Manager
        <small>Manage Products</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Products</li>
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
							  <a type="button" class="btn btn-success" href="editHotels.php" >Add Product</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_HOTELS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_HOTELS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							 <li><a title="Export to excel file" href="exportHotelTable.php?fileType=xls&tableName=<?php echo TBL_HOTELS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export Product Profile  </a></li> 
							  </ul>
							</div>
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <!--<div class="form-group">
                <label>Product Name</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>-->
              
              

                <label for="seasonId">Product </label>

                <?php $hotelDropDown = '<select class="form-control select2" name="search_name" id="search_name" '.$disabledHotel.'>

														  <option value="">Select Product</option>';

														if(empty($_SESSION['hotel_access'])){

															$resCat = selectSql(TBL_HOTELS," where  id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');		

														  }else{

														  $resCat = selectSql(TBL_HOTELS," where  id_shop='".addslashes($_SESSION['shop'])."' and find_in_set(id,'".$_SESSION['hotel_access']."') ",' ORDER BY `name`');												}

														  if($db->num_rows2($resCat)){

															while($resultCat = $db->fetch_object2($resCat)){

																if($resultCat->id == $row->hotelId){

																	$selected = 'selected="selected"';

																}else if($_REQUEST['search_name']== $resultCat->id){

																	$selected = 'selected="selected"';

																}else{

																	$selected = '';

																}	

																$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' - '.strtoupper($resultCat->city).'</option>';

															}

														  }

															echo $hotelDropDown .= '</select>';

														  ?>

             
              <!-- /.form-group -->
            </div>
            <!-- /.col -->  
			<div class="col-md-6">
              <div class="form-group">
                <label>Product Category</label>				
				 <?php $categoryDropDown = '<select class="form-control select2" name="hotel_category">
											    <option value="">Select Product Category</option>';
											  $resCat = selectSql(TBL_HOTELS," where id_shop='".$_SESSION['shop']."' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['hotel_category'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
              </div>
			  			
          </div>


        <div class="col-md-6">
	        <div class="form-group" <?php echo $displayHide;?>>
	                  <label for="zonal">Zone<font color="#FF0000">*</font></label>
	                  <?php $stateDropDown = '<select class="form-control select2" name="zonal">
						<option value="">Select zone</option>';
												  $resCat = selectSql(TBL_ZONAL," ",' ORDER BY `name`');
												  if($db->num_rows2($resCat)){
												  	while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['zonal'] == $resultCat->id){
															$selected = 'selected="selected"';
														}
														else{
															$selected = '';
														}
														$stateDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
												 	echo $stateDropDown .= '</select>';
												  ?>
					
					
	           </div>	
        </div>
			
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
              <h3 class="box-title">Product List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
                  <th>Product Name</th>
				  <th>Product Category</th>
				  <th>Zone</th>

				    <th>Display Order</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){
				  $HoletListID	=	  $row->id;?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/>--> <?php echo $counter++;?>.&nbsp;</td>
                  <td><?=$row->name.' - '.strtoupper($row->city);?></td>
				  <td><?php echo selectColumn(TBL_HOTEL_TYPE,'name'," WHERE `id` = '".$row->hotel_category."'");   ?></td>
				  <td><?php echo selectColumn(TBL_ZONAL,'name'," WHERE `id` = '".$row->zonal."'");   ?></td>

				  <td style="width:10%;" >
				      <input <?php echo $displayReadOnly; ?> type="text" class="form-control"  name="display_order|<?php echo $HoletListID;	?>" id="display_order|'.$OtherChargesuniqueCode.'" value="<?php echo $row->display_order;   ?>"  onKeyUp="UpdateDisplayOrder(this.value,<?php echo $HoletListID;?>,'<?php echo TBL_HOTELS; ?>');" style="width:60px;"></td>
				  
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageHotels.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageHotels.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editHotels.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);"/></td>
                </tr>
               <?php }?> 
			    <!--<tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
				<tr>	 
					  <td align="right" colspan="5"><?php  //echo $pagging->getLinks();?> </td>
                 </tr>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="5">---- No Record Found ---- </td>
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

  <script type="text/javascript">
  function UpdateDisplayOrder(SortValue,HoletListID,TableName){
	 
	  
	  $.ajax({
		   type: "POST",
		   url: 'ajax/ajaxUpdateDisplayOrder.php',
		   data: 'HoletListID='+HoletListID+'&SortValue='+SortValue+'&tableName='+TableName, 
		   success: function (result) {			  	    
				//alert(result);
			}
		})
		
			
	 
	  }
  	function deleteMe(id,name){
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    if (this.readyState == 4 && this.status == 200) {
  		    	console.log(this.responseText);
  		      if(this.responseText == 1){
  		      	alert("Transaction Found In the Table");
  		      }
  		      else{
  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){
  		      		window.location.href='manageHotels.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_hotel="+id, true);
  		  xhttp.send();
  	}
  </script> 

<?php include_once("includes/footer.php")?>  