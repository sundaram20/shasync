<?php include_once("../config/auto_loader.php");
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'view');
?>

<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<?php
	$appConnect = mysqli_connect($DB_HOST,$DB_USERNAME_APP, $DB_PASSWORD_APP, $DB_NAME_APP);

///
	echo '=================='.TBL_REPORT;
	echo $sql="SELECT * FROM ".TBL_REPORT." WHERE id_shop='".$_SESSION['shop']."' GROUP BY table_name ";

	$res = mysqli_query($connNew,$sql);

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
       
      </h3>
      <?php //echo breadCrumbs(); ?>
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
	          <h3 class="box-title">Report Config List</h3>
	          <a href="editReportConfig.php" class="btn btn-success pull-right">Add Reprt Config</a>
			</div>
		            <div class="box-body table-responsive">
		              <table id="example2" class="table table-bordered table-striped">
		                <thead>
		                <tr>
		                  <!--<th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />S.no&nbsp;</th>-->
						  <th>S.no</th>
						  <th>Table Name</th>
						  <th>Action</th>
		                </tr>
		                </thead>
		                <tbody>
						<?php 				 				
						if(mysqli_num_rows($res) > 0){$counter = 0;
						
						  while($row = mysqli_fetch_object($res)){ 
						  
						 ?>
		                <tr>
		                  <!--<td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>-->
						  <td><?php echo $counter++ ;?></td>
						  <td><?= $row->table_name ?></td>
						  
						  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editReportConfig.php?tname=<?=encryptor('encrypt',$row->table_name)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;</td>
		                </tr>
		               <?php }?> 
					  
						<tr>	 
							  <td align="right" colspan="3"><?php  echo $pagging->getLinks();?> </td>
		                 </tr>                
						<?php }else {?>
						
						 <tr>
		                      <td height="200" align="center" colspan="3">---- No Record Found ---- </td>
		                 </tr>                 
						<?php }?>
		                </tbody>                
		              </table>			  
		            </div>
    </div>
    </section>
    </div>

<?php include_once("includes/footer.php")?>        