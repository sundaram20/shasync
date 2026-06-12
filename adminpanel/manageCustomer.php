<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'view');

/////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['eId'] == ''){ header("location:editCompany.php"); }

/////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['action'] == 'change'){

	if($_REQUEST['activeId'] != ''){

		checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'activate');

		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));

		$statusSql = "	UPDATE `".TBL_CUSTOMER."`

						SET `status` = '1'

						,`last_modified` = '".currenDateTime()."'

						,`last_modified_by` = '".$_SESSION['userId']."'

						WHERE `id_customer` = '".addslashes($statusId)."'";

	}elseif($_REQUEST['inactiveId'] != ''){

		checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'deactivate');

		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));

		$statusSql = "	UPDATE `".TBL_CUSTOMER."` 

						SET `status` = '0' 

						,`last_modified` = '".currenDateTime()."'

						,`last_modified_by` = '".$_SESSION['userId']."'

						WHERE `id_customer` = '".addslashes($statusId)."'";

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

	$delSql = "DELETE FROM `".TBL_CUSTOMER."` WHERE `id_customer` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'";

	$sqlDelUsers = selectRow(TBL_CUSTOMER," WHERE `id_customer` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'");

	if(executeSql($delSql)){		

		$err = 0;		

		$_SESSION['successMsg'] = 'One Contact '.selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'").' has been deleted sucessfully.';

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

$sql = " SELECT * FROM `".TBL_CUSTOMER."` WHERE type='2' ";

if($_REQUEST['eId'] != ''){

	$sql .= " AND `id_company` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";

}



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

        Company Manager

        <small>Manage Contacts</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Manage Contacts</li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">	

      <div class="row">

        <div class="col-xs-12">	

		 <div class="nav-tabs-custom">

			<ul class="nav nav-tabs">

			   <li ><a href="editCompany.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Overview</a></li> 

			  <li class="active"><a href="manageCustomer.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Contacts</a></li>   

			     

            </ul> 

            <div class="box-header with-border">

              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Contacts : <a><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?></a></h3>

			  

			   <a href="editCustomer.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-plus fa-x1"></i> Add New Contacts</a>

            

			</div>   

			

			 <div class="form-group has-error" align="center">

		<?php if($_SESSION['errorMsg']){?>

		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

		<?php unset($_SESSION['successMsg']);}?>

		</div>  

          <!-- /.box -->

          <div class="box">

            <div class="box-header">

              <h3 class="box-title">Contacts List</h3>

            </div>

			<form name="listingForm" action="" method="post">

               <input type="hidden" value="" name="act" />

			     <div id="listingDiv"></div>

            <!-- /.box-header -->

            <div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                <tr>

                  <th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' /> Check All&nbsp;</th>

				  <th>Contact Name</th>

                  <th>Status</th>

				  <th>Action</th>

                </tr>

                </thead>

                <tbody>

				<?php 				 				

				if($total > 0){$counter = 1;

				  while($row = $db->fetch_object()){?>

                <tr>

                  <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_customer;?>"/> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>

				  <td><?php echo $row->first_name.' '.$row->last_name;   ?></td>

                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageCustomer.php?inactiveId='.encryptor('encrypt',$row->id_customer).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCustomer.php?activeId='.encryptor('encrypt',$row->id_customer).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 

				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCustomer.php?eId=<?=$_GET['eId']?>&id=<?=encryptor('encrypt',$row->id_customer)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<!--<img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageCustomer.php?delId=<?=encryptor('encrypt',$row->id_customer)?>&eId=<?=$_GET['eId']?>&action=delete&page=<?=$_REQUEST['page']?>';}"/>--></td>

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

<?php include_once("includes/footer.php")?>  