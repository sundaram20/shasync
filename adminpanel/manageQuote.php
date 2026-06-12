<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_SALES_QUOTE,'view');
//$hotelId='1';
/////////////////////////////////////////////////////////////////////////////////////


$sql = " SELECT  DISTINCT A.*,B.lead_status AS F_STATUS,B.assign_user_id AS assign_to_id,B.quote_close_summary FROM `".TBL_SALES_QUOTE."` A
         LEFT JOIN  `".TBL_SALES_QUOTE_FOLLOWUP."` B
         ON A.id=B.id_quote
         WHERE A.`id_shop` = '".addslashes($_SESSION['shop'])."' 
         AND (A.id_user='".$_SESSION['userId']."' OR B.assign_user_id='".$_SESSION['userId']."') ";



	$sql .= "order by id desc";



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
    <h1> Quotation Manager<small>Quotation Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Quotation</li>
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
            <a title="Add Quotation" class="pull-right btn btn-success" href="editQuote.php" style="color:#fff;font-weight:bold;">&nbsp;ADD Quote</a>
          <div class="btn-group  pull-right"><!--<a type="button" class="btn btn-success" href="editRateLetters.php" >Add Rate</a>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>-->
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
               

                    <label for="reservation_date">&nbsp;Date : From - To </label>

                    <div class="input-group">

                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>

                      <input type="text" class="form-control pull-right dateRangeEdit" id="reservation_date" placeholder="Enter Checkin date" name="reservation_date" id="reservation_date" data-parsley-required value="<?php if(isset($_REQUEST['reservation_date'])) echo $_REQUEST['reservation_date'];?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">

					   

                    </div>

                    <!-- /.input group -->

                    <span id="reservation_dateError"></span>
              </div>
              <!--<div class="col-md-6">
                <div class="form-group">
                  <label>Company</label>
                  <?php $companyDropDown = '<select class="form-control select2" name="companyId" '.$disabledCompany.'>
											    <option value="">Select Company</option>';
											  $resCat = selectSql(TBL_COMPANY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['companyId'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $companyDropDown .= '</select>';
											  ?>
                </div>
              </div>-->
              
              <!-- /.col -->
              
              <!-- /.row -->
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <input name="Search" type="submit" class="btn btn-primary" value="Search" />
          </div>
        </form>
        
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Quotation List</h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="10%">
                      S.No.&nbsp;</th>
                    <th>Company Name</th>
                    <th>Hotel</th>
                    <th>Special Offer</th>
                    <th>Assigned By</th>
                    <th>Date</th>
                    <th>Assigned To</th>
                    <th>Close Remarks</th>
                    <th>Follow up status</th> 
                    <th>status</th> 
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
                    <td>
                      <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                    
                    <td><?php echo ($row->id_company!=0?selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'"):'Direct Guest');  ?></td>
                    <td><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'");  ?></td>
                    
                    <?php
					$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($row->id_contacts)."'",''); 
		  $resultContact = $db->fetch_object2($resContact);
                    $NAme	=	$resultContact->first_name.' '.$resultContact->last_name;
					?>
                    
                    
                    <td><?php echo $row->details;   ?></td>
                    <td><?php echo selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_user.'" ');?></td>
                    <td><?php echo date('d M Y',strtotime($row->dated));   ?></td>
                    <td><?php echo selectColumn(TBL_USERS,'name','WHERE id="'.$row->assign_to_id.'" ');?></td>
                    <td><?php echo $row->quote_close_summary;   ?></td>
                     <?php
                        if($row->F_STATUS==1){
                          $lead_status='Open';
                        } 
                        else if($row->F_STATUS==''){
                          $lead_status='';
                        }
                        else{
                         $lead_status='Close';
                        }
                        

                        if($row->status==1){
                          $status='Active';
                        } 
                        else if($row->lead_status==0){
                         $status='Inactive';
                        }
                     ?> 

                    <td ><?php echo $lead_status;   ?></td>
                    <td ><?php echo $status;   ?></td>
                    
                    
                   
                    <td>
                
                    

					  &nbsp;&nbsp; <!--<a href="#" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>-->
                     <!-- &nbsp;&nbsp; <a href="ex.php?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>-->
                    &nbsp;&nbsp; <a href="editQuote.php?action=edit&eId=<?=encryptor('encrypt',$row->id)?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a>
                    &nbsp;&nbsp; <a href="mail-template/sendQuoteMail.php?eId=<?=encryptor('encrypt',$row->id)?>" title="Edit"><i class="fa fa-paper-plane" ></i></a>
                    &nbsp;&nbsp; <!--<a href="#"  title="Delete"><i class="fa fa-remove" ></i></a> </td>-->
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
