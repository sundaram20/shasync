<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'view');

if($_SESSION['userLevel'] !=1){
  $perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
  $resPer = mysqli_query($connNew,$perSql);

  if($resPer){
    	$perData	=	mysqli_fetch_object($resPer);
      if($perData->calendar_user_list_approved == 0){
  	   $UserRestriction	=" AND id='".$_SESSION['userId']."'";	
      }
  }
}

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

      <h1>
        Mappings
        <small>Manage Mapping</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Mapping</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">
		 <!--########## Company Import jump#######-->  
		   
		   <!-- Modal -->
		     <div class="modal fade" id="importComapnyModal" role="dialog" >
		       <div class="modal-dialog">
		       
		         <!-- Modal content-->
		         <div class="modal-content" style="width: 300px; margin: 0px auto;">
		           <div class="modal-header">
		             <button type="button" class="close" data-dismiss="modal">&times;</button>
		             <h4 class="modal-title">Import Company</h4><br>
		             <span id="returnTxt" style="color: Green;"></span>
		           </div>
		           <div class="modal-body">
		             <form name="companyimport" method="post" enctype="multipart/form-data" id="companyimport">
		               <div >
		                 <label for="file">Choose File : <span style="color: red;">*</span></label>
		                 <input type="file" name="companyImport" class="form-control" id="companyImport">
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
		  <!--<div class="btn-group  pull-right">
                  <a type="button" class="btn btn-success" href="editCompany.php">Add Company</a>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                 
                  <ul class="dropdown-menu" role="menu">
                  	<li><a title="Import to excel file" href="#" data-toggle="modal" data-target="#importComapnyModal" ><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>
                    <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export Creation Based</a></li>
                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_COMPANY;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export Creation Based</a></li>
                  <li><a title="Export to excel file" href="exportCompanyTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export Profile Based </a></li>
                   
                  </ul>
                </div>  -->        
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <!--<div class="col-md-6">
              <div class="form-group">
                <label>Company Name</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>
              
            </div>-->


          <div class="form-group col-md-4 ">
                                <label>Database Field</label>
                                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                          
          
                         <select class="form-control select2" name="assign_user_id" id="assign_user_id">
		                      <option value="">Invoice No</option>
		                      <option value="">Invoice Date</option>
		                      <option value="">Amount</option>
		                      <option value="">Due Date</option>
		                      <option value="">Hotel Name</option>
		                      <option value="">Source</option>
		                      <option value="">Booker First Name</option>
		                      <option value="">Booker Last Name</option>
		                      <option value="">Email</option>                 
		                      <option value="">Phone</option>
		                      <option value="">Guest Name</option>
		                      <option value="">Checkin</option>
		                      <option value="">Checkout</option>
            
                       </select >
                                   
                                                              
             </div>

          <div class="form-group col-md-4 ">
                                <label>Excel Field</label>
                                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                          
          
                         <select class="form-control select2" name="assign_user_id" id="assign_user_id">
				                    <option value="">A</option>
				                    <option value="">B</option>
				                    <option value="">C</option>
				                    <option value="">D</option>
				                    <option value="">E</option>
				                    <option value="">F</option>
				                    <option value="">G</option>
				                    <option value="">H</option>
				                    <option value="">I</option>                 
				                    <option value="">J</option>
				                    <option value="">K</option>
				                    <option value="">L</option>
				                    <option value="">M</option>
            
                     </select >
                                   
                                                              
             </div>
          <div class="form-group col-md-4 ">
          	<br>
               <input name="Search" type="submit" class="btn btn-primary" value="Add" style="margin-top:4px;" />
            <!--Area Executive-->
		  </div>
          <!-- /.row -->
        </div>
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
      
        <!--<a  href="companyExport.php?Download=Generate&id_area=<?php echo $_REQUEST['id_area']; ?>&search_name=<?php echo $_REQUEST['search_name']; ?>&status=<?php echo $_REQUEST['status']; ?>&id_default_group=<?php echo $_REQUEST['id_default_group']; ?>&id_email=<?php echo $_REQUEST['id_email']; ?>&id_phone=<?php echo $_REQUEST['id_phone']; ?>" class="btn btn-primary" />Generate</a>-->
        </div>
		</form>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="5%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
                  <th>Database Field</th>
                  <th>Excel Field</th>
				  <th>Modify</th>
				 
				 
                </tr>
                </thead>
                <tbody>
				
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> 
				 1 . &nbsp;</td>
				  <td style="width:22%;">
                         <select class="form-control">
		                      <option value="">Invoice No</option>
		                      <option value="">Invoice Date</option>
		                      <option value="">Amount</option>
		                      <option value="">Due Date</option>
		                      <option value="">Hotel Name</option>
		                      <option value="">Source</option>
		                      <option value="">Booker First Name</option>
		                      <option value="">Booker Last Name</option>
		                      <option value="">Email</option>                 
		                      <option value="">Phone</option>
		                      <option value="">Guest Name</option>
		                      <option value="">Checkin</option>
		                      <option value="">Checkout</option>
            
                         </select >
                   </td>

                   <td style="width:22%;">
                   	
                         <select class="form-control">
				                    <option value="">A</option>
				                    <option value="">B</option>
				                    <option value="">C</option>
				                    <option value="">D</option>
				                    <option value="">E</option>
				                    <option value="">F</option>
				                    <option value="">G</option>
				                    <option value="">H</option>
				                    <option value="">I</option>                 
				                    <option value="">J</option>
				                    <option value="">K</option>
				                    <option value="">L</option>
				                    <option value="">M</option>
            
                     </select >
                   </td>

                   <td>
                   	   <input name="update" type="submit" class="btn" value="Update" style="background:#34a58b;color:#fff;" />
                   </td>


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


  //COMPANY AUTO COMPLETE START==================================================================
	comCheck = () =>{
		window.location.href='https://www.roomstatushub.in/sync/adminpanel/index.php';
	}
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
 
  	function deleteMe(id,name){
  		//var companyName='<?php echo $_REQUEST['search_name'];?>';
  		var companyName=$("#search_name").val();
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    if (this.readyState == 4 && this.status == 200) {
  		    	console.log(this.responseText);
  		      if(this.responseText == 1){
  		      	alert("Transaction Found In the Table");
  		      }
  		      else{
  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){
  		      		window.location.href='manageCompany.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>&search_name='+companyName+'&searchFormSubmit=1&Search=Search';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_company="+id, true);
  		  xhttp.send();
  	}
  </script> 

<!--jump-->
<script type="text/javascript">
	//jump
	$("document").ready(function(){
		$("#importCompany").click(function(){
        $("#companyimport").submit(function(e){
          e.preventDefault();	
          var fileName = $("#companyImport").val();
          console.log(fileName);
          if(fileName == ""){
          	$("#returnTxt").css("color","red");
          	$("#returnTxt").html(" !! Kindly Select a file !!");
          }  
          else{
            $.ajax({
            type        : 'POST',
            contentType : false,
            processData : false, 
            url         : 'ajax/ajaxCompanyImport.php', 
            data        : new FormData(this),
            success     : function(data){
              $("#returnTxt").html(data);
              /*$("#credithidden").val(data[1]);*/
              //alert(data);
            } 
           })
          }
        });
      });
	});
</script>