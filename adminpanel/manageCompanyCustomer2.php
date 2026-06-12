<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'view');
//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_CUSTOMER."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_customer` = '".addslashes($_REQUEST['activeId'])."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_CUSTOMER."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_customer` = '".addslashes($_REQUEST['inactiveId'])."'";
	}
		
	if(executeSql($statusSql)){
		$err = 0;		
		$_SESSION['successMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'delete');
	$delSql = "DELETE FROM `".TBL_CUSTOMER."` WHERE `id_customer` = '".$_REQUEST['delId']."'";
	
	$sqlDelUsers = selectRow(TBL_CUSTOMER," WHERE `id_customer` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;		
		$_SESSION['successMsg'] = 'One Contact '.selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".encryptor('decrypt',$_REQUEST['delId'])."'").' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete contact '.selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".encryptor('decrypt',$_REQUEST['delId'])."'");
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



if($_REQUEST['search_name'] == '' && $_REQUEST['status'] == ''){
$connect	=		" AND 

 B.date_created >= DATE_FORMAT( CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01' ) 
AND
    B.date_created < DATE_FORMAT( CURRENT_DATE, '%Y/%m/01' )";
    

}


// ----------cate---------
$sql = "SELECT * FROM `".TBL_COMPANY."` A LEFT JOIN `".TBL_CUSTOMER."` B ON A.id_company=B.id_company WHERE type=2  and A.id_shop='".addslashes($_SESSION['shop'])."'  ".str_replace('id_state', 'A.id_state', $_SESSION['Ids_user_access_Company'])." $connect2  ";

if($_REQUEST['search_name'] != ''){
	$sql .= " AND (B.`first_name` LIKE '%".addslashes($_REQUEST['search_name'])."%' || B.last_name LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(B.first_name,' ', B.last_name) LIKE '%".addslashes($_REQUEST['search_name'])."%' )";
}
if($_REQUEST['status'] != ''){
	$sql .= " AND B.`status` = '".addslashes($_REQUEST['status'])."%'";
}

if($_REQUEST['companyId'] != ''){
	$sql .= " AND A.`id_company` = '".addslashes($_REQUEST['companyId'])."'";
}

if($_REQUEST['id_email'] != ''){
	$sql .= " AND B.`email` = '".addslashes($_REQUEST['id_email'])."' ";
}

if($_REQUEST['id_mobile'] != ''){
	$sql .= " AND B.`mobile` LIKE '%".addslashes($_REQUEST['id_mobile'])."%' ";
}

if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY A.`date_created` DESC ";
}else{
	$sql .= " ORDER BY A.`date_created` DESC";
}

//echo $sql;
//exit;
$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $numRows;

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Company Manager
        <small>Manage Company</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Company</li>
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
                  <a type="button" class="btn btn-success" href="editCompany.php">Add Company</a>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                   <?php ?> <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_CUSTOMER;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_CUSTOMER;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
                  
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
                <label>Contact Name</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>
              
            </div>
           	<?php /*?><div class="col-md-6">
              <div class="form-group">
                <label>Contact Name</label>	
                <input type="text" name="search_name" class="form-control searchContact">			
				 <?php $categoryDropDown = '<select class="form-control select2" name="searchContactName" id="searchContactName">
											    <option value="">Select Contact Name</option>';
											  $resCat = selectSql(TBL_CUSTOMER," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND type='2' ",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_company'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.ucfirst($resultCat->first_name.' '.$resultCat->last_name).'">'.ucfirst($resultCat->first_name.' '.$resultCat->last_name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
              </div>
			  			
          </div><?php */?>



			<div class="col-md-6">
              <div class="form-group">
                <label>Company - City</label>
                 <select class="form-control select2 itemName" name="companyId" id="companyId"   >

                  </select>				
				 <!--<?php $categoryDropDown = '<select class="form-control select2" name="id_company">
											    <option value="">Select Company</option>';
											  $resCat = selectSql(TBL_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ".$_SESSION['Ids_user_access_Company']."  ",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_company'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?> -->
              </div>
			  			
          </div>

          <div class="col-md-6">
              <div class="form-group">
                <label>Contact Email</label>
                <select class="form-control select2 contactEmail" name="id_email" id="id_email">
                </select>				
				 <?php /*$categoryDropDown = '<select class="form-control select2 contactEmail" name="id_email" id="id_email">
											    <option value="">Select Email</option>';
											  $resCat = selectSql(TBL_CUSTOMER," where type='2' and status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_email'] == ''.$resultCat->email.''){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->email.'">'.ucfirst($resultCat->email).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											*/  ?>
              </div>
			  			
          </div>


          <div class="col-md-3">
              <div class="form-group">
                <label>Contact Mobile</label>	
				<input type="text"	 name="id_mobile" id="id_mobile" value="<?php echo trim($_REQUEST['id_mobile']);?>" class="form-control">		
				<?php /*?> <?php $categoryDropDown = '<select class="form-control select2" name="id_mobile">
											    <option value="">Select Mobile</option>';
											  $resCat = selectSql(TBL_CUSTOMER," where type='2' and status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_mobile'] == ''.$resultCat->mobile.''){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->mobile.'">'.ucfirst($resultCat->mobile).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?><?php */?>
              </div>
			  			
          </div>
		  
		  <div class="col-md-3">
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
              <h3 class="box-title">Company List</h3>
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
                  <th>Contact Name</th>
				  <th>Company - City </th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                  <td><?=$row->first_name.' '.$row->last_name;?></td>
				  <td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'").'-'.selectColumn(TBL_COMPANY,'city'," WHERE `id_company` = '".$row->id_company."'");   ?></td>
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageCompanyCustomer.php?inactiveId='.encryptor('encrypt',$row->id_customer).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCompanyCustomer.php?activeId='.encryptor('encrypt',$row->id_customer).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCustomer.php?eId=<?=encryptor('encrypt',$row->id_company)?>&id=<?=encryptor('encrypt',$row->id_customer)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<!--<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id_customer;?>" onClick="deleteMe(this.id,this.name);"/>--></td>
                </tr>
               <?php }?> 
			    <!--<tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
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
  		      		window.location.href='manageCompanyCustomer.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_customer="+id, true);
  		  xhttp.send();
  	}


</script>
<?php include_once("includes/footer.php")?>  
<script>
  	//search company names
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
	  
	  
	  	//search company names
  	     $('.contactEmail').select2({
        placeholder: 'Select Email',
        ajax: {
          url: "ajax/ajaxSearchContactEmail.php",
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

