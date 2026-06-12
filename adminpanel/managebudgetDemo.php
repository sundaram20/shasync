<?php include_once("../config/auto_loader.php");

include_once("includes/reportFunctionsAgentAchieved.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_BUDGET_MASTER,'view');



$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

$sql = "SELECT id,name FROM `fs_budget_year` WHERE id_shop = ".$_SESSION['shop']." "; 



$res = mysqli_query($conn,$sql);

/////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['method']=='Download'){





		reportExecutiveBudget($_SESSION['shop'], $_REQUEST['id_user'] , $_REQUEST['seasonId'],$_REQUEST['type']);

	

	}

if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){



    checkUserLevelPermission($_SESSION['userLevel'],TBL_BUDGET_MASTER,'delete');

	$delSql = "DELETE FROM `".TBL_BUDGET_MASTER."` WHERE `seasonId` = '".addslashes($_REQUEST['seasonId'])."' and `type` = '".addslashes($_REQUEST['type'])."' and `id_user` = '".addslashes($_REQUEST['id_user'])."'  ";



	



	



	 $sqlDelUsers = selectRow(TBL_BUDGET_MASTER," WHERE `seasonId` = '".addslashes($_REQUEST['seasonId'])."' and `type` = '".addslashes($_REQUEST['type'])."' and `id_user` = '".addslashes($_REQUEST['id_user'])."' ");



	if(executeSql($delSql)){		



	$err = 0;



		$_SESSION['successMsg'] = 'One Hotel Wise Monthly Budget has been deleted sucessfully';//.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$sqlDelUsers['hotel_id']."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$sqlDelUsers['room_id']."'").' has been deleted sucessfully.';



	}else{



$err = 1;



		$_SESSION['errorMsg'] = 'Unable to delete Hotel Wise Monthly Budget ';//.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$sqlDelUsers['hotel_id']."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$sqlDelUsers['room_id']."'");



	}



	



}



$type=1;

$BudgetType	=" AND type='".$type."' ";

$sql = " SELECT  * FROM `".TBL_BUDGET_MASTER."`  WHERE `".TBL_BUDGET_MASTER."`.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType ";



if($_REQUEST['searchFormSubmit'] =='1'){



if($_REQUEST['user_id'] != ''){



	$sql .= " AND `".TBL_BUDGET_MASTER."`.`id_user` = '".addslashes($_REQUEST['user_id'])."'";



}



if($_REQUEST['seasonId'] != ''){



	$sql .= " AND `".TBL_BUDGET_MASTER."`.`seasonId` = '".addslashes($_REQUEST['seasonId'])."'";



}



if($_REQUEST['hotelId'] != ''){



	$sql .= " AND `".TBL_BUDGET_MASTER."`.`id_hotel` = '".addslashes($_REQUEST['hotelId'])."'";



}



}



	//$sql .= "group by `".TBL_RATE_ASSIGN_DETAILS."`.rate_id order by id desc";



$sql =	$sql." group by id_user,seasonId,type";







//echo $sql;



$db->query($sql);



$numRows= $db->num_rows();



$pagging = new pagingClass($sql,$setpage);



$db->query($pagging->getQuery());



$total = $db->num_rows();

?>



<?php include_once("includes/header.php")?>







<?php include_once("includes/left.php")?>

 



 <!--########## Budget Import Modal Start jump#######-->  

   

   <!-- Modal -->

     <div class="modal fade" id="budgetModal" role="dialog" >

       <div class="modal-dialog">

       

         <!-- Modal content-->

         <div class="modal-content" style="width: 300px; margin: 0px auto;">

           <div class="modal-header">

             <button type="button" class="close" data-dismiss="modal">&times;</button>

             <h4 class="modal-title">Import Budget</h4>

           </div>

           <div class="modal-body">

             <form name="import" method="post" enctype="multipart/form-data" id="import">

               <div>

                 <label for="season">Season : <span style="color: red;">*</span></label>

                 <select class="form-control" name="seasonImp" id="seasonImp">

                   <option value="0">Select Season</option>

                   <?php

                     while ($sea = mysqli_fetch_object($res)) {

                       echo "<option value=".$sea->id.">".$sea->name."</option>";

                     }

                   ?>

                 </select>

               </div>

               <div >

                 <label for="file">Choose File : <span style="color: red;">*</span></label>

                 <input type="file" name="excelImport" class="form-control" id="excelImport">

               </div><br>

               <div >

                 <input type="submit" value="Import" name="submit" class="btn btn-primary" id="importBgt"><span style="color:red;margin-left:50px; ">*</span> = Required Field

               </div>



            </form>

           </div>

         </div>

         

       </div>

     </div>

     

   

<!--########## Budget Import Modal End#######-->  



<div class="content-wrapper">



  <!-- Content Header (Page header) -->





  <section class="content-header">



    <h1> Hotel Wise Monthly Budget <small>Hotel Wise Monthly Budget Master</small> </h1>



    <ol class="breadcrumb">



      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>



      <li class="active">Hotel Wise Monthly Budget</li>



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



          <div class="btn-group  pull-right"><a type="button" class="btn btn-success" href="editbudget.php" >Add Budget</a>



            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>



            <ul class="dropdown-menu" role="menu">



              	<li><a title="Import Budget" href="" data-toggle="modal" data-target="#budgetModal"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>

            

								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_BUDGET_MASTER;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li>



            </ul>



          </div>



        </div>



        <!-- /.box-header -->



        <form name="searchForm" action="" method="get">



          <input type="hidden" value="1" name="searchFormSubmit" />



          <div class="box-body">



            <div class="row">



              



                  



              <div class="col-md-6">



                <div class="form-group">



                  <label>User Name</label><?php echo $_SESSION['hotel_access']; debugData($_SESSION);?>



                  <?php $levelDropDown = '<select class="form-control select2" name="user_id">



											    <option value="">Select user</option>';



											 if(empty($_SESSION['hotel_access'])){



													$resCat = selectSql(TBL_USERS," where status='1' AND `sales_status_active` = '1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');		



												  }else{

echo TBL_USERS."   where status='1' AND `sales_status_active` = '1' and find_in_set(id,'".$_SESSION['hotel_access']."') and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`';


												  $resCat = selectSql(TBL_USERS," where status='1' AND `sales_status_active` = '1' and find_in_set(id,'".$_SESSION['hotel_access']."') and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');												}



												  if($db->num_rows2($resCat)){



													while($resultCat = $db->fetch_object2($resCat)){



														if($resultCat->id == $row->hotelId){



															$selected = 'selected="selected"';



														}else if($_REQUEST['user_id']== $resultCat->id){



															$selected = 'selected="selected"';



														}else{



															$selected = '';



														}	



														$levelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';



													}



												  }



													echo $levelDropDown .= '</select>';



												  ?>



                </div>



              </div>



              <!-- /.col -->



              <div class="form-group col-sm-6">



                <label for="seasonId">Season<font color="#FF0000">*</font></label>



                <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" >



											  <option value="">Select Season</option>';



											  $resCat = selectSql(TBL_BUDGET_YEAR," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');



											  if($db->num_rows2($resCat)){



											  	while($resultCat = $db->fetch_object2($resCat)){



													if($resultCat->id == $_REQUEST['seasonId']){



														$selected = 'selected="selected"';



													}else{



														$selected = '';



													}	



													$seasonDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';



												}



											  }



											 	echo $seasonDropDown .= '</select>';



											  ?>



              </div>



              <!-- /.row -->



            </div>



          </div>



          <!-- /.box-body -->



          <div class="box-footer">



            <input name="Search" type="submit" class="btn btn-primary" value="Search" />



          </div>



        </form>

        <!-- /.box -->

        <div class="box">



          <div class="box-header">



            <h3 class="box-title">Hotel Wise Monthly Budget List</h3>



          </div>



          <form name="listingForm" action="" method="post">



            <input type="hidden" value="" name="act" />



            <div id="listingDiv"></div>



            <!-- /.box-header -->



            <div class="box-body table-responsive">



              <table id="example2" class="table table-bordered table-striped">



                <thead>



                  <tr>



                    <th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />



                      Check All&nbsp;</th>

                    <th>User Name</th>

                   

                    <th>Season</th>

                    <th>Action</th>

                  </tr>



                </thead>



                <tbody>



                  <?php 



				  



							 				



				if($total > 0){$counter = 1;



				



				



				  while($row = $db->fetch_object()){



					



					  ?>



                  <div data-role="header">



                  <tr>



                    <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/>



                      <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>



                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_user."'");    ?></td>



                   

                   <td><?php echo selectColumn(TBL_BUDGET_YEAR,'name'," WHERE `id` = '".$row->seasonId."'");   ?></td>



                   



                    <td>



 <?php







		$rateletter_url  =	selectColumn(TBL_SHOP,'rateletter_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");



		



		if($rateletter_url!=''){



		



		$rateletter_url  =	selectColumn(TBL_SHOP,'rateletter_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");



		}else{



		$rateletter_url  =	'excel.php';



		}







?>



					  <!-- &nbsp;&nbsp; <a href="pdf-template/<?PHP echo $rateletter_url ?>?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>-->



                     <!-- &nbsp;&nbsp; <a href="ex.php?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>-->



                    &nbsp;&nbsp; <a href="editbudget.php?id_user=<?php echo encryptor('encrypt',$row->id_user); ?>&seasonId=<?=encryptor('encrypt',$row->seasonId);?>&id=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a>



                    &nbsp;&nbsp; <a href="javascript:void(0)" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='managebudget.php?delId=<?=encryptor('encrypt',$row->id)?>&seasonId=<?php echo $row->seasonId; ?>&type=<?php echo $row->type;?>&id_user=<?php echo $row->id_user; ?>&action=delete&page=<?=$_REQUEST['page']?>';}" title="Delete"><i class="fa fa-remove" ></i></a> 

                    

                     &nbsp;&nbsp;<a href="managebudget.php?method=Download&id_user=<?php echo encryptor('encrypt',$row->id_user); ?>&seasonId=<?=encryptor('encrypt',$row->seasonId);?>>&type=<?php echo $row->type;?>" title="Download" ><i class="fa fa-file-excel-o"></i></a>



                    </td>



                  </tr>



                



                 



                 



                 



                  <?php 



				  



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



  <div id="bookedby" class="well">



  <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post"  >



    <div class="form-group">



      <label for="first_name">First Name </label>



      <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required data-parsley-type="alphanum">



    </div>



    <div class="form-group">



      <label for="last_name">Last Name</label>



      <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required>



    </div>



    <div class="form-group">



      <label for="email" >Email Id</label>



      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off">



    </div>



    <div class="form-group">



      <label for="mobile" >Mobile No.</label>



      <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off">



    </div>



    <input  type="button" class="btn btn-default" onClick="saveRateContactPopupform();" value="Save">



    <button class="bookedby_close btn btn-default">Close</button>



  </form>



</div>



  <div id="duplicate" class="well" style="display:none;">



    



  </div>

  



  

  

<?php include_once("includes/footer.php")?>

<!--jump-->

<script type="text/javascript">

   $("document").ready(function(){

      $("#importBgt").click(function(){

        $("#import").submit(function(e){

          e.preventDefault();

          var sea = $("#seasonImp").val();

          if(sea == 0 || sea==""){

            alert("Kindly Select a Season !");

          }

          else{

            var fileName = $("#excelImport").val();

            console.log(fileName);

            $.ajax({

            type        : 'POST',

            contentType : false,

            processData : false, 

            url         : 'ajax/ajaxBudgetImport.php', 

            data        : new FormData(this),

            success     : function(data){

              alert(data);

            } 

           })

          }

        });

      });

   });

</script>

<script>

function duplicate(id){

	var Id = id;

	$('#dupId').val(Id);

}

</script>