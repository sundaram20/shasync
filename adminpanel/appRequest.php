<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],SAL_API_REQUESTS,'view');

// ----------cate---------


if($_REQUEST['usernameid'] != ''){
    $report_date=date('Y-m-d',strtotime($_REQUEST['report_date']));
    //$report_date="2020-02-20 15:34:59";
     
  $sql = " SELECT * FROM `".SAL_API_REQUESTS."`  ";
   
$sql .= " WHERE `data` LIKE '%".addslashes(strtolower($_REQUEST['usernameid']))."%' AND 

`send_at` LIKE '%".$report_date."%' Order by id desc";

echo $sql;

	$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
}
//echo $sql;

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       App Request
        <small> App Request Master</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">App Request Master</li>
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
		 
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Executive</label>        
                  <?php 
				  
				  
				  $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">
                           <option value="">Select Executive</option>';

                  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' ".$teamMembers." ".$UserRestriction." AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

                  if($db->num_rows2($resUserLevel)){
                              while($resultUserLevel = $db->fetch_object2($resUserLevel)){

                                          if($_REQUEST['usernameid'] == $resultUserLevel->username){

                                            $selected = 'selected="selected"';

                                          }else{

                                            $selected = '';

                                          }

                                          $categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->username.'">'.ucfirst($resultUserLevel->name).'</option>';

                                        }

                                        }

                                        echo $categoryDropDown .= '</select>';

                                        ?>

                                                              

                                                              </div>

                              </div>
			
            <!-- /.col -->  
            <div class="col-md-3">
              <div class="form-group">
  
                 <label for="start_date">Date</label>
                    <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="report_date" name="report_date" value="<?php echo date('d-m-Y');?>"  data-parsley-required>

            </div>
        </div>
                <!-- /.col --> 
		
          </div>
          <!-- /.row -->
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
              <h3 class="box-title">App Request List</h3>
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
                  <th>App Request Title</th>
                  
                </tr>
                </thead>
                <tbody>
				<?php 
				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
                  <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                  <td><?=$row->data;?></td>
                  </tr>
               <?php }?> 
			   
				<tr>	 
					  <td align="right" colspan="4"><?php  echo $pagging->getLinks();?> </td>
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