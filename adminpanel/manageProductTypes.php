<?php include_once("../config/auto_loader.php");

//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){

		//checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'active');
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_ATTRIBUTES."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
			if(executeSql($statusSql)){
				$err = 0;
				$_SESSION['successMsg'] = 'status has been changed successfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'status has not been changed.';
			}				
		
	}elseif($_REQUEST['inactiveId'] != ''){

		//checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'inactive');
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_ATTRIBUTES."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
			
			if(executeSql($statusSql)){
				$err = 0;
				$_SESSION['successMsg'] = 'status has been changed sucessfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'status has not been changed.';
			}		
						
	}
	
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){

	//echo "hello"; exit;
	
	 //checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'delete');
		$delSql = "DELETE FROM `".TBL_ATTRIBUTES."` WHERE `id` = '".$_REQUEST['delId']."'";
	
		if(executeSql($delSql)){		
			$err = 0;
			$_SESSION['successMsg'] = 'One Product Type  has been deleted sucessfully.';
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Unable to delete Product Type ';
		}
		
}

// ----------cate---------
$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'  and `table_name` = 'items_type'  ";

if($_REQUEST['search_name'] != ''){
	$sql .= " AND `id`= '".addslashes($_REQUEST['search_name'])."'";
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

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<?php// include_once("../ajax/ajaxCheckTransactions.php");?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
       <h1>
          Product Type
        <small>Manage Product Type</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Product Type</li>
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
					<a type="button" class="btn btn-success" href="editProductTypes.php" >Add Product Type</a>							
			  </div>     
           </div>	
             <form name="searchForm" id="phpForm" action="" method="get">
		        		<input type="hidden" value="1" name="searchFormSubmit" />
		        		<div class="box-body">
							<div class="row">
								
		              			<div class="col-md-6 col-sm-6">
					            	<div class="form-group">
					            		<label>Product Type Name</label>
		                     
					                      <?php $categoryDropDown = '<select class="form-control select2" name="search_name" style="width:100%">
					                        <option value="">Select Charges name</option>';
					                        $resUserLevel = selectSql(TBL_ATTRIBUTES," WHERE `table_name`='items_type' and `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `field_value`');
					                        if($db->num_rows2($resUserLevel)){
					                          while($resultUserLevel = $db->fetch_object2($resUserLevel)){
					                            if($_REQUEST['search_name'] == $resultUserLevel->id){
					                              $selected = 'selected="selected"';
					                            }else{
					                              $selected = '';
					                            }
					                            $categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->field_value).'</option>';
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
              <h3 class="box-title">Product Type List</h3>
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
                  <th>Products Type Name</th>
                  <th>Products Category</th>
				  <th>Description</th>
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
                  <td><?php echo $row->field_category;?></td>
                  <td><?php echo $row->field_description;?></td>  

                  
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageProductTypes.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageProductTypes.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editProductTypes.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);"/></td>
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
  		      		window.location.href='manageProductTypes.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_product_type="+id, true);
  		  xhttp.send();
  	}
  </script> 

<?php include_once("includes/footer.php")?>  