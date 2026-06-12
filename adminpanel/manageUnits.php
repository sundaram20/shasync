<?php include_once("../config/auto_loader.php");

//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');

//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		//checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_ATTRIBUTES."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($_REQUEST['activeId'])."'";
	}elseif($_REQUEST['inactiveId'] != ''){
	//	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_ATTRIBUTES."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($_REQUEST['inactiveId'])."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = ''.selectColumn(TBL_ATTRIBUTES,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = ''.selectColumn(TBL_ATTRIBUTES,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'delete');
	$delSql = "DELETE FROM `".TBL_ATTRIBUTES."` WHERE `id` = '".$_REQUEST['delId']."'";
	$sqlDelUserLevel = selectRow(TBL_ATTRIBUTES," WHERE `id` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;
		if(file_exists($image_path.$sqlDelUserLevel['image'])){
			@unlink($image_path.$sqlDelUserLevel['image']);
			@unlink($image_path.'small-'.$sqlDelUserLevel['image']);
			@unlink($image_path.'medium-'.$sqlDelUserLevel['image']);
		}
		$_SESSION['successMsg'] = 'One Unit '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Unit '.$sqlDelUserLevel["name"];
	}
}

// ----------cate---------
$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `table_name` = 'unit'  ";

if($_REQUEST['search_name'] != ''){
	$sql .= " AND `field_value` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
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

$db->query($sql);
$numRows= $db->num_rows();
// $pagging = new pagingClass($sql,$setpage);
// $db->query($pagging->getQuery());
$total = $db->num_rows();

//var_dump($total);
?>


<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<?php //include_once("../ajax/ajaxCheckTransactions.php");?>
<div class="content-wrapper">
 <section class="content-header">
      <h1>
        Unit Manager
        <small>Manage Units</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Units</li>
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
							  <a type="button" class="btn btn-success" href="editUnits.php" >Add Unit</a>
							 <!-- <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ATTRIBUTES;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ATTRIBUTES;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>-->
							</div>
          
        </div>


        <!--form starts-->
        <form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <!--<div class="form-group">
                <label>Unit Name</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>-->
              
              

                <label for="seasonId">Unit </label>

                <?php $UnitDropDown = '<select class="form-control select2" name="search_name" id="search_name" '.$disabledUnit.'>

														  <option value="">Select Unit</option>';

													

															$resCat = selectSql(TBL_ATTRIBUTES," where  id_shop='".addslashes($_SESSION['shop'])."' AND  `table_name`='unit'  ",' ORDER BY `field_value`');	


														

																						

														  if($db->num_rows2($resCat)){

															while($resultCat = $db->fetch_object2($resCat)){

																if($resultCat->id == $row->id){

																	$selected = 'selected="selected"';

																}else if($_REQUEST['search_name']== $resultCat->id){

																	$selected = 'selected="selected"';

																}else{

																	$selected = '';

																}	

																$UnitDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';

															}

														  }

															echo $UnitDropDown .= '</select>';

														  ?>

             
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
              <h3 class="box-title">Unit List</h3>
            </div> 
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">

            	<table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
                  <th width="10%"> S.No.&nbsp;</th>
                  <th>Unit Name</th>
				  <th>Unit Description</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
		          </thead>
		        <tbody>  
		        	<?php 
		        	$i=1;		 
				  while($row = $db->fetch_object()){ ?>
                  <tr>

                  <td><?php echo $i++;?></td>
                  <td><?php echo $row->field_value;?></td>
                  <td><?php echo $row->field_description;?></td>  

                  
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageUnits.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageUnits.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editUnits.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);"/></td>
                </tr>

              
               <?php } ?>  
				 
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
  		      		window.location.href='manageUnits.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_sub_group="+id, true);
  		  xhttp.send();
  	}
  </script> 

<?php include_once("includes/footer.php")?>  