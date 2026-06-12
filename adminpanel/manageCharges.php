<?php 
	include_once("../config/auto_loader.php");
	//include_once("functions/selectQuery.php");
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_CHARGES,'view');

	if($_REQUEST['action'] == 'change'){
		if($_REQUEST['activeId'] != ''){
			//checkUserLevelPermission($_SESSION['userLevel'],TBL_CHARGES,'activate');
			//$statusId = addslashes($_REQUEST['activeId']);
			$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
			$statusSql = "	UPDATE `".TBL_CHARGES."` 
						SET `status` = '1' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
		}elseif($_REQUEST['inactiveId'] != ''){
			//checkUserLevelPermission($_SESSION['userLevel'],TBL_CHARGES,'deactivate');
			//$statusId = addslashes($_REQUEST['inactiveId']);
			$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
			$statusSql = "	UPDATE `".TBL_CHARGES."` 
							SET `status` = '0' 
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."' 
							WHERE `id` = '".$statusId."'";

		}
		if(executeSql($statusSql)){
			$err = 0;
			$_SESSION['successMsg'] = 'Charges '.selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Charges '.selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
		}

	}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){

		//checkUserLevelPermission($_SESSION['userLevel'],TBL_CHARGES,'delete');
		//$deleteIds = encryptor(decrypt,$_REQUEST['delId']);
		$delSql = "DELETE FROM `".TBL_CHARGES."` WHERE `id` = '".$_REQUEST['delId']."'";
	   $sqlDelUsers = selectRow(TBL_CHARGES," WHERE `id` = '".$_REQUEST['delId']."'");
	   $deleteCharges = selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$_REQUEST['delId']."'");
		if(executeSql($delSql)){
			$err = 0;
			
			$_SESSION['successMsg'] = 'Charges : '.$deleteCharges.'  has been deleted sucessfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'Unable to delete Charges : '.$deleteCharges;
		}
	}


?>
<?php
// ----------cate---------
$sql = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'   ";

if($_REQUEST['search_name'] != ''){
	$sql .= " AND `id` = '".addslashes($_REQUEST['search_name'])."'";
}
if($_REQUEST['status'] != ''){
	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
}


if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `date_created` DESC";
}else{
	$sql .= " ORDER BY `date_created` DESC";
}
echo $sql;

$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total= $db->num_rows();

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<?php //include_once("../ajax/ajaxCheckTransactions.php");?>
<style type="text/css">
	.fieldset {
	  border: 2px groove #3C8DBC;
	  border-top: none;
	  padding: 0.5em;
	  margin: 1em 2px;
	}

	.fieldset>p {
	  font: 1.4em normal;
	  margin: -0.8em -0.4em 0;
	}

	.fieldset>p>span {
	  float: left;
	}

	.fieldset>p:before {
	  border-top: 3px solid #3C8DBC;
	  content: ' ';
	  float: left;
	  margin: 0.5em 2px 0 -1px;
	  width: 0.75em;
	}

	.fieldset>p:after {
	  border-top: 3px solid #3C8DBC;
	  content: ' ';
	  display: block;
	  height: 0.5em;
	  left: 2px;
	  margin: 0 1px 0 0;
	  overflow: hidden;
	  position: relative;
	  top: 0.5em;
	}

	.text{
		font-size:20px;
	}
</style>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->

		<section class="content-header">
       <h1>
          Charges
        <small>Manage Charges</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Charges</li>
      </ol>
    </section>
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
		  			<a type="button" class="btn btn-success" href="editCharges.php">Add Charges</a>
					
				</div>
        	</div>
        	       <form name="searchForm" id="phpForm" action="" method="get">
		        		<input type="hidden" value="1" name="searchFormSubmit" />
		        		<div class="box-body">
							<div class="row">
								
		              			<div class="col-md-6 col-sm-6">
					            	<div class="form-group">
					            		<label>Charges Name</label>
		                     
					                      <?php $categoryDropDown = '<select class="form-control select2" name="search_name" style="width:100%">
					                        <option value="">Select Charges name</option>';
					                        $resUserLevel = selectSql(TBL_CHARGES," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
					                        if($db->num_rows2($resUserLevel)){
					                          while($resultUserLevel = $db->fetch_object2($resUserLevel)){
					                            if($_REQUEST['search_name'] == $resultUserLevel->id){
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
		                       	
		                       		 
		            			<div class="form-group col-md-6">
				                	<label>Status</label>
				                	<?php 
										if($_REQUEST['status'] == '1'){
												$selected1 = 'selected="selected"';
										}elseif($_REQUEST['status'] == '0'){
												$selected0 = 'selected="selected"';
										}
										 echo $statusDropDown	 = '<select class="form-control select2" name="status" style="width:100%;"> <option value="">Both</option>
											<option '.$selected1.' value="1">Active</option>
											<option '.$selected0.' value="0">Inactive</option>
										</select>';
									?>
				              	</div>                  
		            		</div>
		            	
				       	</div>
						<!-- /.box-body -->
			        	<div class="box-footer">
							<div class="row">
								<div class="col-md-2 col-sm-2" style="padding:0px 0px 0px 20px;">
					        		<input name="Search" type="submit" class="btn btn-primary" value="Search" />
								        
					        	</div>
							</div>
			        	</div>
			       </form> <!--end of form-->
        </div>
        <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          	<div class="box">
            <div class="box-header">
              <h3 class="box-title">List of (<?= $numRows; ?>)</h3>
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
									<th>Charges Name</th>
									<th>Charges Account</th>
									<th>Type</th>
									<th>Creation Date</th>
									<th>Modified Date</th>

									<th>Status</th>
									<th>Action</th>
									</tr>
									</thead>
									<tbody>  
									<?php 
				 				
								 if($total > 0){$counter = 1;
										
										$i=1;		 
									while($row = $db->fetch_object()){ ?>
									<tr>

									<td><?php echo $i++;?></td>
									<td><?php echo $row->name;?></td>
									<?php 
									$charges_account = $row->charges_account;
									if($charges_account==1){
										$chargesVal="Sales";
									} elseif($charges_account==2){
										$chargesVal="Purchase";
									}elseif($charges_account==3){
										$chargesVal="Income";
									}elseif($charges_account==4){
										$chargesVal="Expense";
									}elseif($charges_account==5){
										$chargesVal="Taxes";
									}elseif($charges_account==6){
										$chargesVal="Discount";
									}elseif($charges_account==7){
										$chargesVal="Others";
									}elseif($charges_account==7){
										$chargesVal="Others";
									}elseif($charges_account==8){
										$chargesVal="Bank";
									}
									
									
									
									
									
									?>
									<td><?php echo $chargesVal;?></td> 
									<?php 
									$transaction_type = $row->transaction_type;
									if($transaction_type==1){
										$transVal="Local";
									} elseif($transaction_type==2){
										$transVal="Interstate";
									}else{
										$transVal="Not Applicable";

									}
									?>
									<td><?php echo $transVal;?></td>  
									<td><?php echo $row->date_created;?></td>
									<td><?php echo $row->last_modified;?></td>

									<td><?php echo $row->status=='1'?'<span onclick="location.href=\'manageCharges.php?inactiveId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCharges.php?activeId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>		

									
									<td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCharges.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);"/></td>
									</tr>
								<?php }  ?>
								<tr>	 
					              <td align="right" colspan="4"><?php  echo $pagging->getLinks();?> </td>
                               </tr>   
								
								
						       	<?php	} else {?>
				
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
</div>

<?php include_once("includes/footer.php")?> 

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
  		      		window.location.href='manageCharges.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_product_type="+id, true);
  		  xhttp.send();
  	}


  </script> 




