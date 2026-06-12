<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILY_ENQUERY,'view');



/////////////////////////////////////////////////////////////////////////////////////



//debugData($_REQUEST);



$sql = " SELECT * FROM `call` WHERE  `id_shop` = '".addslashes($_SESSION['shop'])."' ";



    
if($_REQUEST['name'] != ''){
	$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['name'])."%'";
}


    
if($_REQUEST['mobile'] != ''){
	$sql .= " AND `mobile` LIKE '%".addslashes($_REQUEST['mobile'])."%'";
}




if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `date_created` DESC";
}else{
	$sql .= " ORDER BY `date_created` DESC";
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

    <h4 style="margin:0;"> Call Manager<small> Call  Master</small> </h4>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Calls</li>

    </ol>

  </section>

  <!-- Main content -->

  <section class="content" style="padding-top:0;">

  <div class="row">

    <div class="col-xs-12">

      <div class="nav-tabs-custom">

      		 <!--########## Company Import jump#######-->  
		   
		   <!-- Modal -->
		     <div class="modal fade" id="importCallModal" role="dialog" >
		       <div class="modal-dialog">
		       
		         <!-- Modal content-->
		         <div class="modal-content" style="width: 300px; margin: 0px auto;">
		           <div class="modal-header">
		             <button type="button" class="close" data-dismiss="modal">&times;</button>
		             <h4 class="modal-title">Import Call</h4><br>
		             <span id="returnTxt" style="color: Green;"></span>
		           </div>
		           <div class="modal-body">
		             <form name="CallImport" method="post" enctype="multipart/form-data" id="CallImport">
		               <div >
		                 <label for="file">Choose File : <span style="color: red;">*</span></label>
		                 <input type="file" name="CallImport" class="form-control" id="CallImport">
		               </div><br>
		               <div >
		                 <input type="submit" value="uplaod" name="submit" class="btn btn-primary" id="importCompany"><span style="color:red;margin-left:50px; ">*</span> = Required 
		                 Field<br>
		               </div>

		            </form>
		           </div>
		         </div>
		         
		       </div>
		     </div>
		     
		   
		<!--########## Import Company  Modal End#######-->  

        <div class="form-group has-error" align="center">

          <?php if($_SESSION['errorMsg']){?>

          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

          <?php unset($_SESSION['successMsg']);}?>

        </div>

	      <div class="box-header with-border">
	          <?php /*?><h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3><?php */?>
	          	  <div class="btn-group   pull-right" >
	                  <a type="button"  onclick="filter();" class="btn btn-info" >Filter</a>
	               
	             
	           </div>&nbsp;&nbsp;   

			  <div class="btn-group  pull-right" style="margin-right:3px;">
	                  <a type="button" class="btn btn-success" href="editCall.php">Add Call</a>
	                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
	                    <span class="caret"></span>
	                    <span class="sr-only">Toggle Dropdown</span>
	                  </button>
	                 
	                  <ul class="dropdown-menu" role="menu">
	                  	<?php /*?><li><a title="Import to excel file" href="#" data-toggle="modal" data-target="#importCallModal" ><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>
	                    <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export Creation Based</a></li>
	                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_COMPANY;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export Creation Based</a></li>
	                  <li><a title="Export to excel file" href="exportCallTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export Profile Based </a></li><?php */?>
	                 <li><a title="Import to excel file" href="UploadCall.php"  ><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>  
	                  </ul>
	           </div>          
	        </div>
	        <!-- /.box-header -->

       <form name="searchForm" action="" method="get" id="filter">
          <input type="hidden" value="1" name="searchFormSubmit" />
          <div class="box-body">
            <div class="row">          
	            <div class="col-md-4">
	              <div class="form-group">
	                <label>Name</label>
	                 <input type="text" name="name" id="name"  class="form-control">                                  
	              </div>                                                                         
	           </div>
	            <div class="col-md-4">
	              <div class="form-group">
	                <label>Mobile</label>
	                 <input type="text" name="mobile" id="mobile"  class="form-control">                                  
	              </div>                                                                         
	           </div>   <!--col ends-->	       
          </div> <!--End Row-->

        </div> <!--Box body ends-->
           <div class="box-footer">

            <input name="Search" type="submit" class="btn btn-primary" value="Search" style="float:left;"  /> &nbsp;&nbsp;&nbsp;

            

         <?php /*?>   <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" style="margin-left:10px;float:left;" />

        <?php */?>

          </div>
      </form> 

		       <!--end form ends-->       

        <div class="box">

          <!--<div class="box-header text-center">

            <h3 class="box-title">Call List</h3>

          </div>-->

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

            <!-- /.box-header -->

            <div class="box-body table-responsive">

              <table id="" class="table table-bordered table-striped">

                <thead>

                  <tr>

					<!--<th width="10%">S.No.&nbsp;</th>-->
			
					<th>Name</th>
					
					<th>Mobile </th>
					<th>Type Of Call </th>

                    <th>Status </th>
					<th>Action</th>
  

                  </tr>

                </thead>
 			<tbody>
				<?php 
				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){
					  if($row->Call_status=='2'){
						$LeadText	='Parcially Received';  
						$leadStatus='2';//parsley Paid
						}elseif($row->lead_status=='1'){
							$LeadText	='';  
							$leadStatus='1';//Pending Paid
			  				}else{
								$LeadText	='Received';  
								//Received;
								}
					  
					  
					  
					//  print_r($row);
					  ?>
                <tr>
                <td><?=$row->name;?></td>
                <td><?=$row->mobile;?></td>
                <td><select >
                       <option value="1">Booking</option>
                       <option onclick="this.options[this.selectedIndex].value && (window.location=this.options[this.selectedIndex].value);" value="https://www.roomstatushub.in/sync/adminpanel/manageEnquiry.php"><a href="hi.php">Lead</a></option>
                    </select>
                </td>

           
                <td><button class="btn  btn-danger btn-sm" style="padding:0 3px;"  type="button" >Closed</button></td>
                <td><img src="images/view_edit.gif" style="cursor:pointer;" title="Assign To " onClick="window.location.href='editCall.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" /></td>
                </tr>
                 <?php $Expand++;

				  

				  	}?>

                  <tr>

                    <td align="right" colspan="12"><?php  echo $pagging->getLinks();?>

                    </td>

                  </tr>

                  <?php }else {?>

                  <tr>

                    <td height="200" align="center" colspan="12">---- No Record Found ---- </td>

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


</div>

  
  <?php include_once("includes/footer.php")?>


<script>
//COMPANY AUTO COMPLETE START==================================================================
	comCheck = () =>{
		window.location.href='https://www.roomstatushub.in/sync/adminpanel/index.php';
	}
    
	  //COMPANY AUTO COMPLETE END==================================================================
	  

 </script>

 <script>
 	var x = document.getElementById("filter");
 	           x.style.display ="none";

 	function filter(){
 		
 		if(x.style.display ==="none"){
           x.style.display ="block";
 		}else{
 		  x.style.display ="none";

 		}
 	}
 </script>


