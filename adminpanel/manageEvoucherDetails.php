<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE,'view');



//---------------------------------------------------------------------------------------------------------

if($_REQUEST['action'] == 'change'){

	if($_REQUEST['activeId'] != ''){

		

		checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE_DETAILS,'activate');

		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));

		$statusSql = "	UPDATE `".TBL_PROMO_CODE_DETAILS."`

						SET `status` = '1'

						,`last_modified` = '".currenDateTime()."'

						,`last_modified_by` = '".$_SESSION['userId']."'

						WHERE `id` = '".addslashes($statusId)."'";

	}elseif($_REQUEST['inactiveId'] != ''){

				

		checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE_DETAILS,'deactivate');

		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));

		$statusSql = "	UPDATE `".TBL_PROMO_CODE_DETAILS."` 

						SET `status` = '0' 

						,`last_modified` = '".currenDateTime()."'

						,`last_modified_by` = '".$_SESSION['userId']."'

						WHERE `id` = '".addslashes($statusId)."'";

	}

	if(executeSql($statusSql)){

		$err = 0;

		$_SESSION['successMsg'] = 'status has been changed sucessfully.';

	}else{

		$err = 1;

		$_SESSION['errorMsg'] = ' status has not been changed sucessfully.';

	}

}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){

	checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE_DETAILS,'delete');

	echo $delSql = "DELETE FROM `".TBL_PROMO_CODE_DETAILS."` WHERE `id` = '".$_REQUEST['delId']."'";

	die;

	$sqlDelUserLevel = selectRow(TBL_PROMO_CODE_DETAILS," WHERE `id` = '".$_REQUEST['delId']."'");

	if(executeSql($delSql)){		

		$err = 0;

		

		$_SESSION['successMsg'] = 'One Evoucher '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';

	}else{

		$err = 1;

		$_SESSION['errorMsg'] = 'Unable to delete Evoucher '.$sqlDelUserLevel["name"];

	}

}

if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){

	checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE_DETAILS,'activate');	

	$activateIds = implode(',',$_REQUEST['ids']);	

	$statusSql = "	UPDATE `".TBL_PROMO_CODE_DETAILS."`

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

	checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE_DETAILS,'deactivate');	

	$deactivateIds = implode(',',$_REQUEST['ids']);	

	$statusSql = "	UPDATE `".TBL_PROMO_CODE_DETAILS."`

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

	checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE_DETAILS,'delete');	

	$deleteIds = implode(',',$_REQUEST['ids']);	

	$delSql = "DELETE FROM `".TBL_PROMO_CODE_DETAILS."` WHERE `id` IN (".addslashes($deleteIds).")";

	$delSqlImage = selectSql(TBL_PROMO_CODE_DETAILS,"where `id` in (".addslashes($deleteIds).") ",'');	

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

$promo_code_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));

// ----------cate---------

$sql = " SELECT * FROM `".TBL_PROMO_CODE_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ";

if($promo_code_id!=''){

	

	$sql .= " AND `promo_code_id` = '".addslashes($promo_code_id)."' ";

	}





if($_REQUEST['promo_code'] != ''){

	$sql .= " AND `promo_code` LIKE '%".addslashes($_REQUEST['promo_code'])."%'";

}

if($_REQUEST['status'] != ''){

	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."'";

}





if($_REQUEST['order'] != ''){

	$sql .= " ORDER BY `date_created` DESC";

}else{

	$sql .= " ORDER BY `date_created` DESC";

}

//echo $sql;

if($promo_code_id != '' || $_REQUEST['promo_code'] != '' || $_REQUEST['status'] != ''){

$db->query($sql);

$numRows= $db->num_rows();

$pagging = new pagingClass($sql,$setpage);

$db->query($pagging->getQuery());

$total = $db->num_rows();



}

?>

<?php include_once("includes/header.php")?>

<?php include_once("includes/left.php")?>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->

     <!--########## Evoucher Import Modal Start jump #######-->  
       
       <!-- Modal -->
         <div class="modal fade" id="evoucherModal" role="dialog" >
           <div class="modal-dialog">
           
             <!-- Modal content-->
             <div class="modal-content" style="width: 300px; margin: 0px auto;">
               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Import Evoucher</h4>
               </div>
               <div class="modal-body">
                 <form name="import" method="post" enctype="multipart/form-data" id="import">
                  	<div >
                     <label for="file">Choose File : <span style="color: red;" id="warnTxt">*</span></label>
                     <input type="file" name="excelImport" class="form-control" id="excelEvoucherImport">
                     <input type="hidden" name="promocode_import" value="<?php echo $promo_code_id?>">
                   </div><br>
                   <div >
                     <input type="submit" value="Import" name="submit" class="btn btn-primary" id="importEvo"><span style="color:red;margin-left:50px; ">*</span> = Required Field
                   </div>

                </form>
               </div>
             </div>
             
           </div>
         </div>
         
       
    <!--########## Evoucher Import Modal End#######-->  

    <section class="content-header">

      <h1>

        Evoucher Manager

        <small>Manage Evouchers Code</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Manage Evouchers</li>

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

		  
          <div class="btn-group  pull-right"><a type="button" class="btn btn-success" href="editEvoucher.php" >Add Evocher</a>

            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>

            <ul class="dropdown-menu" role="menu">

              	<li><a title="Import Evoucher" href="" data-toggle="modal" data-target="#evoucherModal"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>
				<?php/*?	            
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_BUDGET_MASTER;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li>*/?>

            </ul>

          </div>



		  <!--<div class="btn-group  pull-right">
			  <a type="button" class="btn btn-success" href="editEvoucher.php" >Add Evoucher</a>
	      </div>
	      <div class="btn-group  pull-right " >
			  <a type="button" class="btn btn-success" data-toggle="modal" data-target="#evoucherModal" style="margin-right: 10px !important;" >Import Evoucher</a>
	      </div>-->
	      
	      
	    </div>
	    
	    	
	    
        <!-- /.box-header -->

		<form name="searchForm" action="" method="get">

            <input type="hidden" value="1" name="searchFormSubmit" />

        <div class="box-body">

          <div class="row">

            <div class="col-md-6">

              <div class="form-group">

                <label>Evouchers  Code</label>				

				<input type="text" name="promo_code" id="promo_code" value="<?php echo trim($_REQUEST['promo_code']);?>" class="form-control" />

              </div>

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

              <h3 class="box-title">Evoucher List</h3>

            </div>

			<form name="listingForm" action="" method="post">

               <input type="hidden" value="" name="act" />

			     <div id="listingDiv"></div>

            <!-- /.box-header -->

            <div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                <tr>

                  <!--<th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' /> Check All&nbsp;</th>-->
                  <th>S.No.</th>
                  <th>Emp_id</th>
                  <th>Employee Name</th>

                  <th>Company Name</th>

				  <th>Voucher Value</th>
				  <th>Food Value</th>

                  <th>Evouchers Code </th>

                   <th>Date Valid From & To</th>

                   <th>Evouchers Code Status</th>

                   

                  <th>Status</th>

				  <th>Action</th>

                </tr>

                </thead>

                <tbody>

				<?php 				 				

				if($total > 0){$counter = 1;

				  while($row = $db->fetch_object()){

					  

					  $rateletter_url  =	'promocode.php';

				?>

                <tr>

                  <!--<td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>-->

                  <td><?php echo $row->serial_no;   ?></td>
                  <td><?php echo $row->emp_id;   ?></td>
                  <td><?php echo $row->emp_title." ".$row->employee_name;   ?></td>
                  <td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->company_id."'");  ?></td>

				  <td><?php echo $row->vaoucher_value;   ?></td>
				  <td><?php echo $row->food_value;   ?></td>

                   <td><?php echo $row->promo_code;   ?></td>

                    <td ><?php echo dateformat_date($row->date_valid_from);  echo ' To '; echo dateformat_date($row->date_valid_to);?></td>

                    

                    <td style="text-align:center; color:#204d74; font-weight:bold;" align="center">

					<?php if($row->promo_code_status == '1'){

						echo "Not Issued";

						}if($row->promo_code_status == '2'){

						echo "Re-Issued";

						} if($row->promo_code_status == '3'){

						echo "Issued";

						}

						if($row->promo_code_status == '4'){

						echo "Used,";

						} ?></td>

                    

                    

                    

                    

                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageEvoucherDetails.php?id='.encryptor('encrypt',$promo_code_id).'&inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageEvoucherDetails.php?id='.encryptor('encrypt',$promo_code_id).'&activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 

				 

                  <td>

                  

                   

                    &nbsp;&nbsp; <a href="editEvoucher.php?eId=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a>















                  <!--<img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editEvoucher.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);"/>--></td>

                </tr>

               <?php }?> 

			   <!-- <tr>

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



   



<?php include_once("includes/footer.php")?>  

<script type="text/javascript">
	//jump
    $("document").ready(function(){
      	$("#importEvo").click(function(){
        	$("#import").submit(function(e){
         		e.preventDefault();
          		var fileName = $("#excelEvoucherImport").val();
          	    console.log(fileName);
          	    if(fileName != "" ){
	            	$.ajax({
	            		type        : 'POST',
	            		contentType : false,
	            		processData : false, 
	            		url         : 'ajax/ajaxEvoucherImport.php', 
	            		data        : new FormData(this),
	            		success     : function(data){
	              			chk = alert(data);
	              			if(chk = true)
	              				location.reload();

	            		}
	            	})
	            }
	            else{
	            	$("#warnTxt").html("* Kindly select a file.");
	            }	
	        });
        });
    });
</script>