<?php include_once("../config/auto_loader.php");

include_once("includes/reportFunctionsAgentAchieved.php");



checkUserLevelPermission($_SESSION['userLevel'],TBL_AGENT_ACHIEVED,'view');



$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);



$unitUser = selectColumn(TBL_USERS,'user_type','WHERE id="'.$_SESSION['userId'].'" '); 



if($unitUser==2){

  $budgetAcheivedTable = TBL_UNIT_AGENT_ACHIEVED ;

  $condHotGroup =",".$budgetAcheivedTable.".id_hotel"; 

}

else{

  $budgetAcheivedTable = TBL_AGENT_ACHIEVED ;

}



if($_SESSION['userLevel'] !=1){

  $perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";

  $resPer = mysqli_query($conn,$perSql);



  if($resPer){

      $perData  = mysqli_fetch_object($resPer);

      if($perData->calendar_user_list_approved == 0){

       $UserRestriction =" AND id='".$_SESSION['userId']."'"; 

      }

  }

}



if($_SESSION['teamMembers'] !=""){

  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";

}

else{

  $teamMembers ="";

}

$sql = "SELECT id,name FROM `fs_budget_year` WHERE id_shop = ".$_SESSION['shop']." "; 



$res = mysqli_query($conn,$sql);



/////////////////////////////////////////////////////////////////////////////////////



if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){





	$delSql = "DELETE FROM `".$budgetAcheivedTable."` WHERE `seasonId` = '".addslashes($_REQUEST['seasonId'])."' and `id_hotel` = '".addslashes($_REQUEST['id_hotel'])."' and `id_user` = '".addslashes($_REQUEST['id_user'])."'  ";



	



	



	 $sqlDelUsers = selectRow($budgetAcheivedTable," WHERE `seasonId` = '".addslashes($_REQUEST['seasonId'])."' and `id_hotel` = '".addslashes($_REQUEST['id_hotel'])."' and `id_user` = '".addslashes($_REQUEST['id_user'])."' ");



	if(executeSql($delSql)){		



	$err = 0;



		$_SESSION['successMsg'] = 'One Hotel Room assigned '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$sqlDelUsers['hotel_id']."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$sqlDelUsers['room_id']."'").' has been deleted sucessfully.';



	}else{



$err = 1;



		$_SESSION['errorMsg'] = 'Unable to delete hotel Room assign '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$sqlDelUsers['hotel_id']."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$sqlDelUsers['room_id']."'");



	}



	



}





$sql = " SELECT  * FROM `".$budgetAcheivedTable."`  WHERE `".$budgetAcheivedTable."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";



if($_REQUEST['searchFormSubmit'] =='1'){



if($_REQUEST['user_id'] != ''){



	$sql .= " AND `".$budgetAcheivedTable."`.`id_user` = '".addslashes($_REQUEST['user_id'])."'";



}

elseif($_SESSION['teamMemberLevel']==1){



  $sql .= " AND `".$budgetAcheivedTable."`.`id_user` in (".$_SESSION['teamMembers'].")";

}

else{

  $sql .= " AND `".$budgetAcheivedTable."`.`id_user` in (".$_SESSION['userId'].")";

}



if($_REQUEST['seasonId'] != ''){



	$sql .= " AND `".$budgetAcheivedTable."`.`seasonId` = '".addslashes($_REQUEST['seasonId'])."'";



}



if($_REQUEST['hotelId'] != ''){



	$sql .= " AND `".$budgetAcheivedTable."`.`id_hotel` = '".addslashes($_REQUEST['hotelId'])."'";



}



}

else{

  if(!isset($_SESSION['teamMemberLevel'])){

    $sql .= " AND `".$budgetAcheivedTable."`.`id_user` = '".$_SESSION['userId']."'";

  }

  else{

    $sql .= " AND `".$budgetAcheivedTable."`.`id_user` IN (".$_SESSION['teamMembers'].")";

  }

  

}

$sql .= "group by `".$budgetAcheivedTable."`.seasonId ".$condHotGroup." order by id desc";



//debugData($_SESSION);







//echo $sql;

/*

exit;*/



$db->query($sql);



$numRows= $db->num_rows();



$pagging = new pagingClass($sql,$setpage);



$db->query($pagging->getQuery());



$total = $db->num_rows();



if($_REQUEST['method']=='Download'){





	

		

		reportAgentAchieved($_SESSION['shop'], $_REQUEST['id_user'] , $_REQUEST['seasonId'],$_REQUEST['id_hotel']);

	

	}

?>



<?php include_once("includes/header.php")?>







<?php include_once("includes/left.php")?>

 



 <!--########## Budget Import Modal Start jump#######-->  

   

   <!-- Modal -->

    <!-- <div class="modal fade" id="budgetModal" role="dialog" >

       <div class="modal-dialog">

       

          Modal content

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

     </div>-->

     

   

<!--########## Budget Import Modal End#######-->  



<div class="content-wrapper">



  <!-- Content Header (Page header) -->





  <section class="content-header">



    <h1>Budget Manager <small>Budget Achieved</small> </h1>



    <ol class="breadcrumb">



      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>



      <li class="active">Budget Achieved</li>



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



          <div class="btn-group  pull-right"><a type="button" class="btn btn-success" href="editAgentAchieved.php" >Add Achieved</a>



            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>



            <ul class="dropdown-menu" role="menu">



              	<li><a title="Import Budget" href="" data-toggle="modal" data-target="#budgetModal"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>

            

								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo $budgetAcheivedTable;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li>



            </ul>



          </div>



        </div>



        <!-- /.box-header -->



        <form name="searchForm" action="" method="get">



          <input type="hidden" value="1" name="searchFormSubmit" />



          <div class="box-body">



            <div class="row">



             <!-- <div class="form-group col-sm-6">



                <label for="seasonId">Hotel <font color="#FF0000">*</font></label>



                <?php $hotelDropDown = '<select class="form-control select2" name="hotelId" id="hotelId" '.$disabledHotel.'>



														  <option value="">Select Hotel</option>';



														if(empty($_SESSION['hotel_access'])){



															$resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');		



														  }else{



														  $resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and find_in_set(id,'".$_SESSION['hotel_access']."') ",' ORDER BY `name`');												}



														  if($db->num_rows2($resCat)){



															while($resultCat = $db->fetch_object2($resCat)){



																if($resultCat->id == $row->hotelId){



																	$selected = 'selected="selected"';



																}else if($_REQUEST['hotelId']== $resultCat->id){



																	$selected = 'selected="selected"';



																}else{



																	$selected = '';



																}	



																$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';



															}



														  }



															echo $hotelDropDown .= '</select>';



														  ?>



              </div>-->



                  



              <div class="col-md-6">



                <div class="form-group">



                  <label>User Name</label>



                  <?php $levelDropDown = '<select class="form-control select2" name="user_id">



											    <option value="">Select user</option>';



											 if(empty($_SESSION['hotel_access'])){



													$resCat = selectSql(TBL_USERS," where status='1' AND `sales_status_active` = '1' and id_shop='".addslashes($_SESSION['shop'])."' ".$UserRestriction." ".$teamMembers." ",' ORDER BY `name`');		



												  }else{



												  $resCat = selectSql(TBL_USERS," where status='1' AND `sales_status_active` = '1' and id_shop='".addslashes($_SESSION['shop'])."' ".$UserRestriction." ".$teamMembers." ",' ORDER BY `name`');												}



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



            <h3 class="box-title">Achieved List</h3>



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

                    <th>User Name</th>

                    <?php

                      if($unitUser==2){

                        echo "<th>Hotel</th>";

                      }

                    ?>

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



                    <td>



                      <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>



                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_user."'");    ?></td>



                    <?php

                      if($unitUser==2){

                        echo "<td>".selectColumn(TBL_HOTELS,'CONCAT(name,", ",city)','WHERE id="'.$row->id_hotel.'"')."</td>";

                      }

                    ?>

                   <td><?php echo selectColumn(TBL_BUDGET_YEAR,'name'," WHERE `id` = '".$row->seasonId."'");   ?></td>



                   



                    <td>



 

					  <!-- &nbsp;&nbsp; <a href="pdf-template/<?PHP echo $rateletter_url ?>?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>-->



                     <!-- &nbsp;&nbsp; <a href="ex.php?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>-->



                    &nbsp;&nbsp; <a href="editAgentAchieved.php?hotelId=<?php echo encryptor('encrypt',$row->id_user); ?>&id=<?=encryptor('encrypt',$row->seasonId);?>&id_hotel=<?=encryptor('encrypt',$row->id_hotel);?>&action=edit&page=<?=$_REQUEST['page']?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a>



                    &nbsp;&nbsp; 

                                        <a href="manageAgentAchieved.php?method=Download&id_user=<?php echo encryptor('encrypt',$row->id_user); ?>&seasonId=<?=encryptor('encrypt',$row->seasonId);?>&id_hotel=<?php echo $row->id_hotel;?>" title="Download" ><i class="fa fa-file-excel-o"></i></a>

                                        

                                        <!--<a href="javascript:void(0)" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageAgentAchieved.php?delId=<?=encryptor('encrypt',$row->id)?>&seasonId=<?php echo $row->seasonId; ?>&id_hotel=<?php echo $row->id_hotel;?>&id_user=<?php echo $row->id_user; ?>&action=delete&page=<?=$_REQUEST['page']?>';}" title="Delete"><i class="fa fa-remove" ></i></a> --></td>



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

  $(function () {

    $('#example1').DataTable()

    $('#example2').DataTable({

      'paging'      : true,

      'lengthChange': false,

      'searching'   : false,

      'ordering'    : true,

      'info'        : true,

      'autoWidth'   : false

    })

  })

</script>

<script>

function duplicate(id){

	var Id = id;

	$('#dupId').val(Id);

}

</script>