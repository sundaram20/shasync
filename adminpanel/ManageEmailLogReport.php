<?php include_once("../config/auto_loader.php");
define('TBL_EMAILLOG','email_log');
checkUserLevelPermission($_SESSION['userLevel'],TBL_EMAILLOG,'view');

//$hotelId='1';
/////////////////////////////////////////////////////////////////////////////////////
/*echo "Sorry for the inconvenience <br> Please wait we are working on DSR ...";
echo "<pre>";
print_r($_SESSION);
echo "<br>";
print_r($_REQUEST);
echo "</pre>";
exit;*/
if($_SESSION['userLevel']!=1){
$perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
$resPer = mysqli_query($connNew,$perSql);
if($resPer){
  	$perData	=	mysqli_fetch_object($resPer);
    if($perData->calendar_user_list_approved == 0){
	   $UserRestriction	=" AND id='".$_SESSION['userId']."'";	
    }
}
}
if($_SESSION['teamMembers'] !=""){
  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";
}
else{
  $teamMembers ="";
}

if($_REQUEST['state'] != ''){
	$sql = "SELECT  `".TBL_EMAILLOG."`.* FROM `".TBL_EMAILLOG."` LEFT JOIN ".TBL_USERS." ON `".TBL_EMAILLOG."`.id_user=".TBL_USERS.".id   WHERE `".TBL_EMAILLOG."`.`id_shop` = '".addslashes($_SESSION['shop'])."'  ";
}					  
else{
	$sql = " SELECT  `".TBL_EMAILLOG."`.*  FROM `".TBL_EMAILLOG."`  WHERE 1=1 ";
}					  
if($_REQUEST['searchFormSubmit'] =='1'){
if($_REQUEST['report_date'] != ''){
	$_REQUEST['report_date'];
	$report_date= explode(" to ",$_REQUEST['report_date']);
	$checkin = $report_date['0'];
	$checkout = $report_date['1'];
	 $sql .= " AND `".TBL_EMAILLOG."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
	// echo $sql;
	//$sql .= " AND `".TBL_EMAILLOG."`.`dated` = '".stripslashes(date('Y-m-d',strtotime($_REQUEST['report_date'])))."' ";
}
if($_REQUEST['usernameid'] != ''){
	$sql .= " AND `".TBL_EMAILLOG."`.`id_user` = '".addslashes($_REQUEST['usernameid'])."'";
	
		
}



/*if($_REQUEST['hotelId'] != ''){
	$sql .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` = '".addslashes($_REQUEST['hotelId'])."'";
}*/
	
}
 if($_SESSION['userLevel']==1){
				 	
	$sql .= "";
	}else if($_REQUEST['usernameid']==""){
	$sql .=  "  AND `".TBL_EMAILLOG."`.`id_user` = '".addslashes($_SESSION['userId'])."'";
	}
		if($_REQUEST['reportDate'] == '' && $_REQUEST['location'] ==''){
		$sql .= " order by dated asc";
		}
		$datewise_array = array();
		 $checkinDate = date('Y-m-d',strtotime($checkin));
		  $checkoutDate = date('Y-m-d',strtotime($checkout));
		while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	
				$datewise_array[] = $checkinDate;
				$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
			}
	//echo $sql;		
?>

<?php
if($_REQUEST['Download'] == 'Download'){
	
	
	$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
	$rowShop = $db->fetch_object2($resShop);
	
	
//echo $sql;
/*exit;*/
	
	$db->query($sql);
	 $numRows= $db->num_rows();
	//$pagging = new pagingClass($sql,$setpage);
	//$db->query($pagging->getQuery());
	$total = $db->num_rows();
	
	

		
	
$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
 $rowShop = $db->fetch_object2($resShop);
 
$availableData .= '<style>
.table-bordered {
    border: 1px solid #000;
}
.table {
    margin-bottom: 20px;
    max-width: 80%;
    width:100%;
}
table {
    background-color: transparent;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000;
    font-size: 0.85em;
    padding: 2px !important;
}</style>';
 /*$availableData .= '<table class="table">
						<tr>
						  <td width="100%">
							<address>
						   <img src="../../uploaded_files/shop/'.$rowShop->image.'" class="img-responsive" alt="logo" title="logo" />
							</address>
						 	</td>
						 
						 </td>
						<tr>
					</table>'; */
	 
 
 
		
				
					
				
					
	
		
		$availableData .= '<div style="page-break-inside: avoid;">
       ';
$availableData .= '<table class="table" border="1" >
						<tr style="color:white;">
                                       <th colspan="4" style="vertical-align: middle;font-size:15px;">Email Log Report '.date('d-m-Y',strtotime($checkin)).' To '.date('d-m-Y',strtotime($checkout)).' </th>     
                                     </tr>
							<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px">
						   <th width="4%" style="color:#000;"><b>S:No</b></th>
						   <th width="8%" style="color:#000;"><b>Date</b></th>
						   <th width="20%" style="color:#000;text-align:center;"><b>Name</b></th>
						   
						   
						   <th width="28%" style="color:#000;text-align:center;" ><b>Type</b></th>
						   
						   
						   
						   
						   
						   
						   						
						</tr>';	
		   								
						
	/*================================CONVEYANCE START==================================================================================*/
		$counter=1;
		while($row = $db->fetch_object()){
			
			if($row->table_name= 'fs_visit'){
				$Type	=	'Sales Visit';
				}
								 
					  $availableData .= '<tr>
						                       
                    <td>'.$counter++.'</td>';
                     $availableData .= '<td>'.date('d-m-Y',strtotime($row->dated)).'</td>';
                    $availableData .='<td style="text-align:center;">'.selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_user."'").'</td>';
                   
					
					$availableData .= '<td style=" text-align:center;">'.$Type.'</td>';
					//$availableData .= '<td style=" text-align:center;">'.$row->date_created.'</td>
					
					
					
					$availableData .= '</tr>'; 
					
				  
}
	
						
				$availableData .= '</table></div>';
       
	   
$availableData .= '</div>';	
							
				
				
						
			  	 		
				
 
 
/*================================CONVEYANCE START==================================================================================*/
/*echo $availableData;
exit;*/
//die;
$dompdf = new DOMPDF();
$dompdf->set_paper('letter', 'landscape');
$dompdf->load_html($availableData);
$dompdf->render();
if($_REQUEST['location']=='set'){
	$Filename='EMAIL_'.str_replace(' ','',selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ')).'_'.date('d-m-Y',strtotime($checkin));
	$gen = $dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
	file_put_contents('mailattach/'.$Filename.'.pdf', $gen);die;
}
else if($_REQUEST['location']=='open'){
	//$dompdf->output();
	//$dompdf->stream();
		
	$dompdf->stream('EmailLog'.date('d-m-Y').'.pdf', array("Attachment" => false));die;
}
else{
	$dompdf->output();
	//$dompdf->stream();
	$dompdf->stream('EmailLog'.date('d-m-Y').'.pdf', array("Attachment" => true));die;
}
/*$dompdf->load_html($availableData);
//$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->output();
$dompdf->stream();
    //file_put_contents('Brochure.pdf', array("Attachment" => false	));
//$dompdf->stream('test.pdf', array("Attachment" => false	));*/


	}
 
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
	<?php
	
	 	$text='Email Log Report';
	?>
    <h1><?php echo $text; ?><small></small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><?php echo $text; ?></li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom">
        <div class="form-group has-error" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block success"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block dander"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
        <div class="box-header with-border">
          <h3 class="box-title">Search <small>Total Records: (
            <?=$numRows;?>
            ) &nbsp;</small> </h3>
           
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
                <label for="reservation_date">From - To </label>
                <div class="input-group">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  automcomplete="off">
                </div>
                <!-- /.input group --> 
                <span id="reservation_dateError"></span> </div>
              <!--<div class="form-group col-sm-6">
                <label for="seasonId">Date <font color="#FF0000">*</font></label>
                <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>
              </div>-->
              <!--<div class="col-md-6">
                <div class="form-group">
                  <label>Company - City</label>
                  <?php $companyDropDown = '<select class="form-control select2" name="companyId" '.$disabledCompany.'>
										    <option value="">Select Company</option>';
											  $resCat = selectSql(TBL_COMPANY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name !=''   and FIND_IN_SET(area,'".$_SESSION['teamMemberAreas']."') ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['companyId'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
												}
											  }
											 	echo $companyDropDown .= '</select>';
											  ?>
                </div>
              </div>-->
              <?php 
			  if($_SESSION['userLevel']==1){
				 	
				  $ConditonUserLevel = "";
				  }else{
					  $ConditonUserLevel= "  `".TBL_USERS."`.`id` = '".addslashes($_SESSION['userId'])."' AND ";
					  }
			  ?>
              <div class="col-md-6">
                <div class="form-group">
                <label>Sales Executive</label>
                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
               <?php $categoryDropDown = '<select class="form-control select2" required name="usernameid" id="usernameid">
<option value="">Select Sales Executive</option>
						';
						/*  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1'  AND `sales_status_active` = '1' ".$teamMembers."  $UserRestriction AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                            Commented ON 13-02-2021*/
                            
                            $resUserLevel = selectSql(TBL_USERS," WHERE  `sales_status_active` = '1' ".$teamMembers."  $UserRestriction AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_REQUEST['usernameid'] == $resultUserLevel->id){
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
              <!-- /.col -->
              <!--<div class="form-group col-sm-6">
                  <label for="remarks">State</label>
                  <?php $marketDropDown = '<select class="form-control  select2 input-sm" name="state" id="state" >
												  <option value="">Select State</option>';
												 
												  $resCat = selectSql(TBL_STATE," where status='1' AND id_country='110' ",' ORDER BY `name`');										
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id_state ==$_REQUEST['state']){
															$selected = 'selected="selected"';
														}else if($_REQUEST['state']== $resultCat->id_state){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$marketDropDown .= '<option '.$selected.' value="'.$resultCat->id_state.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
													echo $marketDropDown .= '</select>';
												  ?>
					 
                </div>
              
             
            </div>-->
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
           <!-- <input name="Search" type="submit" class="btn btn-primary" value="Search" />-->
            <?php if($_REQUEST['link'] !="set"){ ?>
            <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />
            </div>
            <?php } ?>
            
        </form>
      
        <div class="box"  style="display:none;">
          <div class="box-header">
            <h3 class="box-title">Email Log List</h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
            <!-- /.box-header -->
            
             
               
       <?php
 if($_REQUEST['Search'] == 'Search'){
$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
	$datawisearrayFinal = array();			
	
			
		

 
?>       
<?php //print_r($datawisearrayFinal); ?>
                    
                    
            
            
            
            
            
            <div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                <tr>

                  <th> SNo</th>

				  <th>Date</th>
 <th>Name</th>
				  <th>Type</th>
					
				 

                </tr>

                </thead>

                <tbody>

		

<?php 				 				

				if($total > 0){$counter = 1;

				  while($row = $db->fetch_object()){
					if($row->table_name= 'fs_visit'){
				$Type	=	'Sales Visit';
				}  
					  ?>
					 <tr>
						                       
                    <td><?php echo $counter++;?></td>
                     <td><?php echo date('d-m-Y',strtotime($row->dated));?></td>
                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_user."'");?></td>
                   
					
					<td><?php echo $Type;?></td>

                </tr>

               <?php }?> 

			    <!--<tr>

                     <td align="left" colspan="8">

					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 

					  </td>

				</tr>-->

				<tr>	 

					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>

                 </tr>            

				<?php }else {?>

				

				 <tr>

                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>

                 </tr>            

				<?php } } ?>

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
  <script type="text/javascript">
  	function deleteMe(id,dated){
  		var type=confirm("Do you want to delete this visit ! ");
  		var report_date = '<?php echo $checkin.'+to+'.$checkout;?>';
  		var user = '<?php echo $_REQUEST['usernameid'];?>';
  		console.log(report_date);
  		if(type==true){
  			window.location.href='ManagervisitReport.php?searchFormSubmit=1&report_date='+report_date+'&dated='+dated+'&Search=Search&eId='+id+'&usernameid='+user+'&action=delete&page=<?=$_REQUEST['page']?>';
  		}
  		else{
  			return;
  		}
  	}
  </script>
