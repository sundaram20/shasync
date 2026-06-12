<?php 

include_once("../config/auto_loader.php");

include_once("includes/reportFunctions_new.php");

//error_reporting(E_ALL);

checkUserLevelPermission($_SESSION['userLevel'],'fs_contract_report','view');

$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");

$rowShop = $db->fetch_object2($resShop);

$logo	=	$rowShop->image;

	// ----------cate---------

	$cond = "  where `".TBL_RATE."`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.status='1' ";



   $sql = " SELECT `".TBL_RATE."`.* FROM `".TBL_RATE."`  ".$cond;







if($_REQUEST['Download']=='Download'){

	

  hotelWiseContractReportNEW(NULL,$_SESSION['shop'],$_REQUEST['id'],$_REQUEST['session'],$db,$objPHPExcel);

}



if($_POST['Search'] == 'Search'){

if($_REQUEST['hotelId']	!=''){

		$hotel_ids 		= implode(',',$_REQUEST['hotelId']);

		$hotel_ids_Query	= encryptor('encrypt',$hotel_ids);

}

	//$sql;



	$db->query($sql);



	$numRows= $db->num_rows();



	//$pagging = new pagingClass($sql,$setpage);



	//$db->query($pagging->getQuery());



	//echo $total = $db->num_rows();







}



?>

<?php include_once("includes/header.php")?>

  <?php include_once("includes/left.php")?>

  <div class="content-wrapper"> 

    

    <!-- Content Header (Page header) -->

    

    <section class="content-header">

      <h1> Hotel Wise Contract Report <small>Manage Report</small> </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Manage Report</li>

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

          <h3 class="box-title">Manage Reports &nbsp;</small> </h3>

        </div>

        

        <!-- /.box-header -->

        

        <form name="searchForm" id="searchForm" action="" method="post" data-parsley-validate autocomplete="off" >

          <input type="hidden" value="1" name="searchFormSubmit" />

          <div class="box-body">

            <div class="row">

              <div class="col-md-4">

                <div class="form-group">

                  <label>Hotel</label>

                  <?php 

                    if($_SESSION['HotelPerHotel'] != ""){

                      $hotel_access = $_SESSION['HotelPerHotel'];

                    }else{

                      $hotel_access ="";

                    }

                    

                  $hotelDropDown = '<select class="form-control select2" name="hotelId[]" id="hotelId"   data-parsley-required data-parsley-errors-container="#hotelIdError">



											    <option value="">Select Hotel</option>';



											  $resCat = selectSql(TBL_HOTELS,"where id_shop='".addslashes($_SESSION['shop'])."' ".$hotel_access."  ",' ORDER BY `city`');



											  if($db->num_rows2($resCat)){



											  	while($resultCat = $db->fetch_object2($resCat)){



													if(isset($_REQUEST['hotelId']))



													if(in_array($resultCat->id,$_REQUEST['hotelId'])){



														$selected = 'selected="selected"';



													}else{



														$selected = '';



													}



													$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' - '.strtoupper($resultCat->city).'</option>';



												}



											  }



											 	echo $hotelDropDown .= '</select>';



											  ?>

                  

                </div>

                

                <!-- /.form-group --> <span id="hotelIdError"><?php echo $err_hotelId;?></span> </div>

              

              <!-- /.col --> <?php echo $_REQUEST['seasonId'];?>

              

              

              <div class="col-md-4">

                <div class="form-group">

                  <label>Season</label>

                  <?php 



				 $guestDropDown = '<select class="form-control" name="session[]" id="session" multiple="multiple" data-parsley-required data-parsley-errors-container="#sessionError">



											    <option value="0" >Select Seasion</option>';



											  $resCat = selectSql(TBL_RATE_SEASON," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');



											  if($db->num_rows2($resCat)){



											  	while($resultCat = $db->fetch_object2($resCat)){

																if(isset($_REQUEST['session']))

													if(in_array($resultCat->id,$_REQUEST['session'])){

														

														$selected = 'selected="selected"';



													}else{



														$selected = '';



													}



													$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';



												}



											  }



											 	echo $guestDropDown .= '</select>';



											  ?>

               <span id="sessionError"><?php echo $err_session;?></span> </div>

              </div>

             <!-- <div class="col-md-4">

                <div class="form-group">

                  <label>Company Name - City</label>

                  <?php 



                  $companyDropDown = '<select class="form-control select2" name="company_id" id="company_id" >



											    <option value="">Select Source</option>';



											  $resCat = selectSql(TBL_COMPANY," where id_shop='".addslashes($_SESSION['shop'])."' AND FIND_IN_SET(area,'".$_SESSION['teamMemberAreas']."') and name !='' ",' ORDER BY `name`');



											  if($db->num_rows2($resCat)){



											  	while($resultCat = $db->fetch_object2($resCat)){



													if($_REQUEST['company_id'] == $resultCat->id_company){



														$selected = 'selected="selected"';



													}else{



														$selected = '';



													}



													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).' - '.strtoupper($resultCat->city).'</option>';



												}



											  }



											 	echo $companyDropDown .= '</select>';



											  ?>

                </div>

                

               

                

              </div>-->

              

              <!-- /.row --> 

              

            </div>

          </div>

          

          <!-- /.box-body -->

          

          <div class="box-footer">

            <!--<input name="Search" type="submit" class="btn btn-primary" value="Search" style="float:left" />-->

             <!--<input name="Download" type="submit" class="btn btn-primary" value="Download"  />-->

             

            <a href="javascript://" class="btn btn-success" title="Download"  style="float:left; margin-left:15px;"  onClick="ContractExcel();">Generate</a>

           <!--<a href="javascript://" title="Download" style="float:left; margin-left:5px;" onClick="ContractPdf();"><img src="../uploaded_files/icons/pdf.png" class="img-responsive" alt="logo" title="logo"   style="width:16.5%;"   /></a>--> 

            <!--

            <a type="submit" href="pdf-template/generateHotelwiseContractExcel.php?id=<?=$hotel_ids_Query?>&session=<?=$_REQUEST['session']?>&company_id=<?=$_REQUEST['company_id']?>" title="Download" target="_blank" style="float:left; margin-left:15px;"><img src="../uploaded_files/icons/download.jpg" class="img-responsive" alt="logo" title="logo"    style="width:60%;"  /></a> 

            

            <a href="pdf-template/generateHotelwiseContractPdf.php?id=<?=$hotel_ids_Query?>&session=<?=$_REQUEST['session']?>&company_id=<?=$_REQUEST['company_id']?>" title="Download" target="_blank" ><img src="../uploaded_files/icons/pdf.png" class="img-responsive" alt="pdf" title="pdf"  style="width:3.5%;" /></a>--> </div>

        </form>

      </div>

      <div class="row">

        <div class="col-xs-12"> 

          

          <!-- /.box -->

          

          <div class="box">

            <div class="box-header">

              <h3 class="box-title"></h3>

            </div>

            <form name="listingForm" action="" method="post">

              <input type="hidden" value="" name="act" />

              <div id="listingDiv"></div>

              

              <!-- /.box-header -->

              

              <div class="box-body table-responsive">

                <table id="example2" class="table table-bordered table-striped">

                 <!-- <thead>

                    <tr> 

                                           

                      <th>Agent Name</th>

                      <th>Document Date</th>

                      <th>Market</th>

                      <th>Rate Level</th>

                      <th>Room Category</th>

                      <th>Plan</th>

                      <th>Single</th>

                      <th>Double</th>

                      <th>Extra Bed</th>

                      <th>B/F</th>

                      <th>Lunch</th>

                      <th>Dinner</th>

                    </tr>

                  </thead>

                  <tbody>-->

                    <?php 		





if($_REQUEST['hotelId'] != ''){		



		$hotel_ids = implode(',',$_REQUEST['hotelId']);		



		if($hotel_ids!=''){

			$cond2 .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` in (".$hotel_ids.")";

		}else{	

			if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 

			 $cond2 .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` IN  (".addslashes($_SESSION['HotelUserPermission']).")";

			}

		}

}else{

	if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 

			 $cond2 .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` IN  (".addslashes($_SESSION['HotelUserPermission']).")";

			}



	}

				



				if($_REQUEST['Search']=='Search'){

					

					$counter = 1;



				  while($row = $db->fetch_object()){



					  



					  ?>

                    <?php









$resHotel_rooms = executeSql("SELECT `".TBL_RATE_DETAILS."`.*,`".TBL_HOTELS."`.*, `".TBL_RATE_DETAILS."`.id  as detail_id FROM `".TBL_RATE_DETAILS."` join `".TBL_HOTELS."` on fs_hotels.id=fs_rate_details.hotel_id and rate_id='".addslashes($row->id)."'".$cond2." group by `".TBL_RATE_DETAILS."`.hotel_id");



$NumOfHotelValue	=	$db->num_rows2($resHotel_rooms);



while($rowonlyHote 		= $db->fetch_object2($resHotel_rooms)){



	//print_r($rowonlyHote);



	



	$rate_detail_id[]	=	$rowonlyHote->detail_id;



	



}











$resCat_rooms = executeSql("SELECT `".TBL_RATE_DETAILS."`.*,`".TBL_HOTELS."`.*, `".TBL_RATE_DETAILS."`.id  as detail_id  FROM `".TBL_RATE_DETAILS."` join `".TBL_HOTELS."` on fs_hotels.id=fs_rate_details.hotel_id and rate_id='".addslashes($row->id)."'".$cond2." order by `".TBL_RATE_DETAILS."`.hotel_id");



//echo "<pre>";print_r($row);echo "</pre>";



















//print_r($rate_detail_id);















$NumValue	=	$db->num_rows2($resCat_rooms);



$K=0;



while($rowInclusion = $db->fetch_object2($resCat_rooms)){











//echo "<pre>";print_r($rowInclusion);echo "</pre>";



if($K==0){



	



    $Date	=	dateformat_date($row->date_created);



	



	}else{



		



	$Date	=	'';



	$Company	='';



		}



		$Company1	=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->company_id."'");



	



	if($Company1!=''){



		$Company=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->company_id."'");



		}if($Company1==''){



			$Company= "Template Rate"; 



		}



	?>

                    <?php 







if (in_array($rowInclusion->detail_id, $rate_detail_id)){



	?>

                    <tr>

                      <td colspan="13" style="background-color:#CCC;"><?=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$rowInclusion->hotel_id."'");?></td>

                    </tr>

                    <?php 



	



	}



					  ?>

                    <tr>

                      <input type="hidden" name="newrateId[]" id="newrateId[]" value="<?php echo $row->id; ?>">

                      <td><?=$Company;?></td>

                      <td><?=$Date;?></td>

                      

                      <!--<td><?=selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$row->rate_level_id."'");?></td>-->

                      

                      <td><?=selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$row->market."'");?></td>

                      <td><?=$row->rate_name;?></td>

                      <td><?=selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowInclusion->room_id."'");?></td>

                      <td><?=selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = '".$rowInclusion->rate_plan_id."'");?></td>

                      <td><?=$rowInclusion->single_pax_price;?></td>

                      <td><?=$rowInclusion->double_pax_price;?></td>

                      <td><?=$rowInclusion->extra_bed_price;?></td>

                      <td><?=$rowInclusion->breakfast_price;?></td>

                      <td><?=$rowInclusion->lunch_price;?></td>

                      <td><?=$rowInclusion->dinner_price;?></td>

                    </tr>

                    <?php $K++; 



			   



			   } $K++;



			   ?>

                    <?php  }?>

                    <tr>

                      <td align="left" colspan="8">&nbsp;&nbsp;&nbsp;&nbsp; </td>

                    </tr>

                    <tr>

                      <td align="right" colspan="5"><?php  //echo $pagging->getLinks();?></td>

                    </tr>

                    <?php }else {?>

                    <tr>

                      <td height="200" align="center" colspan="8"> </td>

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

      

      <!-- /.row --> 

      

    </section>

    

    <!-- /.content --> 

    

  </div>

  <script>

  function ContractPdf(hotelId){

	  	var hotelId = $("#hotelId").val();

		var session = $("#session").val();

		var company_id = $("#company_id").val();

		

		

   var form1=$("#searchForm");	

  // var form2=$("#addRoomForm");

 

   

	if(form1.parsley().validate()){

		window.open('pdf-template/generateHotelwiseContractPdf.php?id='+hotelId+'&session='+session+'&company_id='+company_id, '_blank');

	}





}

  function ContractExcel(hotelId){

	  	var hotelId = $("#hotelId").val();

		var session = $("#session").val();

		var company_id = $("#company_id").val();

		

		

   var form1=$("#searchForm");	

  // var form2=$("#addRoomForm");

 

   

	if(form1.parsley().validate()){

		window.open('manageHotelwiseContractReport.php?id='+hotelId+'&session='+session+'&Download=Download', '_blank');



	}





}

</script>

  <?php include_once("includes/footer.php")?>

