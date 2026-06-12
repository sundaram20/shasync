<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'view');
//$hotelId='1';
/////////////////////////////////////////////////////////////////////////////////////
//print_r($_REQUEST);


if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	
	checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'delete');
	//$delSql = "DELETE FROM `".TBL_RATE_DETAILS."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'";
	$delSql = "DELETE FROM `".TBL_RATE."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'";
	$sqlDelUsers = selectRow(TBL_RATE," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'");
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'One Hotel Room assigned '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$sqlDelUsers['hotel_id']."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$sqlDelUsers['room_id']."'").' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete hotel Room assign '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$sqlDelUsers['hotel_id']."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$sqlDelUsers['room_id']."'");
	}
}

$sql = " SELECT  * FROM `".TBL_TAX_CONFIGURATION_TWO."`  WHERE `".TBL_TAX_CONFIGURATION_TWO."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";
if($_REQUEST['searchFormSubmit'] =='1'){


if($_REQUEST['seasonId'] != ''){
	$sql .= " AND `".TBL_TAX_CONFIGURATION_TWO."`.`seasonId` = '".addslashes($_REQUEST['seasonId'])."'";
}
if($_REQUEST['hotelId'] != ''){
	$sql .= " AND `".TBL_TAX_CONFIGURATION_TWO."`.`id_hotel` = '".addslashes($_REQUEST['hotelId'])."'";
}
}
	//$sql .= "group by `".TBL_RATE_ASSIGN_DETAILS."`.rate_id order by id desc";

// $sql;
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
    <h1> Tax Configuration Manager <small>Tax Configuration Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Tax Configuration Master</li>
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
          <div class="btn-group  pull-right"><a type="button" class="btn btn-success" href="editTaxConfigurationTwo.php" >Add Tax Configuration</a>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
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
              <div class="form-group col-sm-6">
                <label for="seasonId">Hotel <font color="#FF0000">*</font></label>
                <?php $hotelDropDown = '<select class="form-control select2" name="hotelId" id="hotelId" '.$disabledHotel.'>
														  <option value="">Select Hotel</option>';
														if(empty($_SESSION['hotel_access'])){
															$resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');		
														  }else{
														  $resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and find_in_set(id,'".$_SESSION['hotel_access']."') ",' ORDER BY `name`');												}
														  if($db->num_rows2($resCat)){
															while($resultCat = $db->fetch_object2($resCat)){
																if($resultCat->id == $row->hotelId){
																	$selected = 'selected="selected"';
																}else if($_REQUEST['hotelId']== $resultCat->id){
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
              
              
              <!-- /.col -->
              <div class="form-group col-sm-6">
                <label for="seasonId">Season<font color="#FF0000">*</font></label>
                <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" >
											  <option value="">Select Season</option>';
											  $resCat = selectSql(TBL_RATE_SEASON," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($resultCat->id == $_REQUEST['seasonId']){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}	
													$seasonDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $seasonDropDown .= '</select>';
											  ?>
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <input name="Search" type="submit" class="btn btn-primary" value="Search" />
          </div>
        </form>
        
        <!-- /.box -->
       


 
 

 
       
 

  
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Rate List</h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />
                      Check All&nbsp;</th>
                    
                   
                    <th>Hotel Name </th>
                  
                    <th>Market</th>
                    <th>Season</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
				  
							 				
				if($total > 0){$counter = 1;
				
				$Expand = 1;
				  while($row = $db->fetch_object()){
					  $Expand;
					  ?>
                  <div data-role="header">
                  <tr>
                    <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/>
                      <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                    
                    <td><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_hotel."'");   ?></td>
                    
                    
                    <td><?php echo selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$row->room_id."'");   ?></td>
                    <td><?php echo selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$row->seasonId."'");   ?></td>
                    <td><?php echo dateformat_date($row->start_date).'-'.dateformat_date($row->end_date);   ?></td>
                    <td>
                 
 





  
                  
					  
                     
                    &nbsp;&nbsp; <a href="editTaxConfigurationTwo.php?hotelId=<?php echo encryptor('encrypt',$hotelId); ?>&id=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a>
                     </td>
                  </tr>
                  
                 
                 
                 
                  <?php $Expand++;
				  
				  	}?>
                  <tr>
                    <td align="right" colspan="10"><?php  echo $pagging->getLinks();?>
                    </td>
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
  <div id="duplicate" class="well" style="display:none;">
    
  </div>
  <?php include_once("includes/footer.php")?>
<script>
function duplicate(id){
	var Id = id;
	$('#dupId').val(Id);
	
}
</script>