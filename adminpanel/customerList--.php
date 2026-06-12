<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'view');

/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		 $statusSql = "	UPDATE `".TBL_CUSTOMER."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_customer` = '".addslashes(encryptor('decrypt',$_REQUEST['activeId']))."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		 $statusSql = "	UPDATE `".TBL_CUSTOMER."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_customer` = '".addslashes(encryptor('decrypt',$_REQUEST['inactiveId']))."'";
	}
		
	if(executeSql($statusSql)){
		$err = 0;		
		$_SESSION['successMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != '' && $_SESSION['userLevel']==1){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'delete');
	$delSql = "DELETE FROM `".TBL_CUSTOMER."` WHERE `id_customer` = '".$_REQUEST['delId']."'";
	$sqlDelUsers = selectRow(TBL_CUSTOMER," WHERE `id_customer` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;		
		$_SESSION['successMsg'] = 'One Guest '.selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'").' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete contact '.selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'");
	}
}

///////////////
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_CUSTOMER."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_customer` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_CUSTOMER."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_customer` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_CUSTOMER."` WHERE `id_customer` IN (".addslashes($deleteIds).")";	
	if(executeSql($delSql)){		
		$err = 0;		
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}	

// ----------cate---------
 $sql = "SELECT * FROM `fs_customer` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ";

if($_REQUEST['search_name'] != ''){
	$sql .= " AND (`first_name` LIKE '%".addslashes($_REQUEST['search_name'])."%' ||  `last_name` LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(`first_name`,' ',`last_name`)  LIKE '%".addslashes($_REQUEST['search_name'])."%')";
}

if($_REQUEST['search_mobile']!=''){
	$sql .= "AND (`mobile` LIKE '%".addslashes($_REQUEST['search_mobile'])."%')";
}

if (!empty($_REQUEST['search_company'])) {
    $sql .= " AND `id_company`= '".addslashes($_REQUEST['search_company'])."' ";
}
	
$sql .= "ORDER BY `fs_customer`.`id_customer` DESC ";

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
        Customer Manager
        <small>Manage Customers</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage customers</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">	
      <div class="row">
        <div class="col-xs-12">	
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
							  <a type="button" class="btn btn-success" href="editCustomerList.php" >Add Customer</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_CUSTOMER.'_1';?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								
							  </ul>
							</div>
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label>Customer Name</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->  
			  <div class="col-md-6">
              <div class="form-group">
                <label>Mobile</label>				
				<input type="text" name="search_mobile" id="search_mobile" value="<?php echo trim($_REQUEST['search_mobile']);?>" class="form-control" />
              </div>
              
            </div>
			
			  <div class="col-md-6">
                <div class="form-group">
                  <label>Company</label>
                    <select class="form-control select2 itemName" name="search_company" id="search_name"   >

                  </select>
                 </div> 
              </div>
			  
		  
          <!-- /.row -->
        </div>
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" />
         <!--<a name="Search" href="exportTable.php?fileType=xls&tableName=<?php //echo TBL_CUSTOMER.'_1';?>&start=<?php //echo $start;?>&end=<?php echo $end;?>" type="submit"  class="btn btn-primary" value="Search"  >Generate</a>-->

        </div>
		</form>		
      </div>
               
			
			   
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Customer List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%"><!-- <input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' /> --> S.No.&nbsp;</th>
				  <th>Customer Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
					<th>Company</th>
					<!--<th>Country</th>-->
                  <th>Designation</th>    
                  <!--<th>Status</th>-->
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
                  <td><!-- <input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_customer;?>"/> --> <?php echo $counter++;?>.&nbsp;</td>
				  <td><?php echo $row->first_name.' '.$row->last_name;   ?></td>
                  <td><?php echo $row->email;   ?></td>
                  <td><?php echo $row->mobile;   ?></td>
					<td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'");   ?></td>
                  <td><?php echo selectColumn(TBL_DESIGNATION_MASTER,'name'," WHERE `id` = '".$row->designation."'");   ?></td>
					
                  <!--<td><?=$row->status=='1'?'<span onclick="location.href=\'manageGuests.php?inactiveId='.encryptor('encrypt',$row->id_customer).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageGuests.php?activeId='.encryptor('encrypt',$row->id_customer).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>	-->
					
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCustomerList.php?id=<?=encryptor('encrypt',$row->id_customer)?>&action=edit&page=<?=$_REQUEST['page']?>';" />
				  &nbsp;&nbsp;&nbsp;&nbsp;	
					  
					  <?php if($_SESSION['userLevel']==1){ ?>
					  
				  <img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id_customer;?>" onClick="deleteMe(this.id,this.name);"/></td>
					  <?php } ?>
                </tr>
               <?php }?> 
			    <!-- <tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr> -->
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr>                
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="4">---- No Record Found ---- </td>
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
  		      		window.location.href='customerList.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_guest="+id, true);
  		  xhttp.send();
  	}
  </script>    

<?php include_once("includes/footer.php")?>  

<script>
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
</script>