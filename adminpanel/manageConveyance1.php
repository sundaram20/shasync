<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILYVISIT,'view');

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
				  

$sql = " SELECT  *  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";





if($_REQUEST['searchFormSubmit'] =='1'){





if($_REQUEST['usernameid'] != ''){

	$sql .= " AND `".TBL_DAILYVISIT."`.`id_user` = '".addslashes($_REQUEST['usernameid'])."'";

}



if($_REQUEST['companyId'] != ''){

	$sql .= " AND `".TBL_DAILYVISIT."`.`id_company` = '".addslashes($_REQUEST['companyId'])."'";

}



if($_REQUEST['hotelId'] != ''){

	$sql .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` = '".addslashes($_REQUEST['hotelId'])."'";

}



}

$sql .=  "  ORDER BY `".TBL_DAILYVISIT."`.`id_user`,dated";

?>



<?php

//echo $sql;

	if($_REQUEST['Search'] == 'Search'){





	$db->query($sql);

	$numRows= $db->num_rows();

	$pagging = new pagingClass($sql,$setpage);

	$db->query($pagging->getQuery());

	$total = $db->num_rows();







 }?>



<?php

if($_REQUEST['Download'] == 'Download'){
	error_reporting(1);

	$content="
			<style>
				.headings{
					padding:2px 2px 2px 2px;
					border:1px solid black;
					font-size:12px;
				}
				.topHead{
					font-weight:bold;
				}
				.bottomHead{
					border:1px solid black;
					font-size:13px;
					text-align:center;
					font-weight:bold;

				}
			</style>
			";

	if($_REQUEST['usernameid']=="" || empty($_REQUEST['usernameid'])){
		$_SESSION['errorMsg']="Select Executive";
		header("Location:manageConveyance.php");
	}

	if($_REQUEST['report_date'] != ''){
		//list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
$report_date= explode(" to ",$_REQUEST['report_date']);
	$checkin = $report_date['0'];
	$checkout = $report_date['1'];
		$query .= " AND `".TBL_DAILYVISIT."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
	}
	$logo = selectColumn('fs_shop','image'," WHERE  id=".$_SESSION['shop']." ");
	$signature = "../uploaded_files/shop/".$logo."";
	
	$content.="	<div width='100%' style='margin-top:-60px;' ><img style='margin-left:40%;' src='".$signature."'/></div>
				";

	$content.="	<table width='100%'>
					<tr>
						<td colspan='3'><b>Executive Name : </b></td>
						<td colspan='2'><b>".ucwords(selectColumn(TBL_USERS,'name','WHERE id="'.$_REQUEST['usernameid'].'" '))."</b></td>
						<td colspan='8'></td>
					</tr>	
					<tr>
						<td colspan='3'><b>Area : ".ucwords(selectColumn(TBL_AREAS,'name','WHERE user_id="'.$_REQUEST['usernameid'].'" '))."</b></td>
						<td colspan='2'><b>".ucwords(selectColumn(TBL_AREAS,'description','WHERE user_id="'.$_REQUEST['usernameid'].'" '))."</b></td>
						<td colspan='8'></td>
					</tr>
					<tr style='background-color:#254061;'>
						<td colspan='13'style='text-align:center;color:#fff;'>".strtoupper('Conveyance Report  from '.$_REQUEST['report_date'])."</td>
					</tr>
					<tr style='background-color:#c2c2c2;'>
						<td class='headings topHead' width='5%'  style='text-align:center;'>S.No.</td>
						<td class='headings topHead' width='10%'  style='text-align:center;'>Date</td>
						<td class='headings topHead' width='15%' style='text-align:center;' >Details</td>
						<td class='headings topHead' width='15%'  style='text-align:center;'>Mode Of Travel</td>
						<td class='headings topHead' width='15%' style='text-align:center;' >Company Visited</td>
						<td class='headings topHead' width='5%' style='text-align:center;' >KmsRun</td>
						<td class='headings topHead' width='5%'  style='text-align:center;'>Rate/Km</td>
						<td class='headings topHead' width='10%'  style='text-align:center;'>Sub Total</td>
						<td class='headings topHead' width='5%'  style='text-align:center;'>Parking</td>
						
						<td class='headings topHead' width='5%'  style='text-align:center;'>Total</td>
						<td class='headings topHead' width='5%'  style='text-align:center;'>Entertainment</td>
						<td class='headings topHead' width='10%'  style='text-align:center;'>Grand Total</td>
						<td class='headings topHead' width='25%'  style='text-align:center;'>Approval Status</td>
					</tr>
				";




	$query = "SELECT * FROM ".TBL_DAILYVISIT." WHERE id_user=".$_REQUEST['usernameid']." AND id_shop=".$_SESSION['shop']." AND StatFrom !='' ";

	$resQue = mysqli_query($connNew,$query);
	$numRows= mysqli_num_rows($resQue);


	if($numRows>0){
		$head_cntr_column='A';
		$head_hotel_row=11;
				
		
		$datePrint='';
		$printRow=12;
		$finalTotal=0;
		$finalGrand=0;
		$approvalText='';
		while($rowData=mysqli_fetch_object($resQue)){

						
			$printCol='A';

			if($datePrint==''){
				$datePrint=$rowData->dated;
				$sno=1;
				$sno2='123';
				$datePrint2='123';
				$bgColor='style="background-color:#e2e2e2"';
					
			}
			else if($datePrint==$rowData->dated){
				//just to skip printing
				$sno2='';
				$datePrint2='';
				$bgColor='';
			}
			else{
				$datePrint=$rowData->dated;
				$sno++;
				$sno2='123';
				$datePrint2='123';
				$bgColor='style="background-color:#e2e2e2"';
			}

			if($rowData->conveyance_approved==1)
				$approvalText='Approved';
			else if($rowData->conveyance_approved==2)
				$approvalText='Not Approved';
			else 
				$approvalText='<b>Pending</b>';

			$content.="		<tr ".$bgColor.">
							<td class='headings' width='5%'  style='text-align:center;'>".($sno2==''?$sno2:$sno)."</td>
							<td class='headings' width='10%'  style='text-align:center;'>".($datePrint2==''?$datePrint2:date('d-M-Y',strtotime($datePrint)))."</td>
							<td class='headings' width='10%' style='text-align:left;' >".$rowData->StatFrom.($rowData->StatTo !=""?"-".$rowData->StatTo:"")."s</td>
							<td class='headings' width='15%'  style='text-align:center;'>".selectColumn(TBL_TRAVEL_MODES,'name','WHERE id="'.$rowData->id_travel_mode.'" ')."</td>
							<td class='headings' width='15%' style='text-align:left;' >".selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$rowData->id_company.'" ')."</td>
							<td class='headings' width='5%' style='text-align:center;' >".$rowData->KmsRun."</td>
							<td class='headings' width='5%'  style='text-align:center;'>".$rowData->RateKm."</td>
							<td class='headings' width='10%'  style='text-align:center;'>".$rowData->KmsRun*$rowData->RateKm."</td>
							<td class='headings' width='5%'  style='text-align:center;'>".$rowData->Parking."</td>
							
							<td class='headings' width='5%'  style='text-align:center;'>".$rowData->Total."</td>
							<td class='headings' width='5%'  style='text-align:center;'>".$rowData->entertainment."</td>
							<td class='headings' width='10%'  style='text-align:center;'>".($rowData->Total+$rowData->entertainment)."</td>
							<td class='headings' width='25%'  style='text-align:center;'>".$approvalText."</td>
							
						</tr>";
						$finalTotal+=$rowData->Total;
						$finalGrand+=($rowData->Total+$rowData->entertainment);

		}
		$content.="<tr>
					<td colspan='5'></td>
					<td style='color:blue;' colspan='4' class='bottomHead'>Conveyance Total</td>
					<td style='color:blue;' class='bottomHead'>".$finalTotal."</td>
					<td style='color:green;' class='bottomHead'>Grand Total</td>
					<td style='color:green;' class='bottomHead'>".$finalGrand."</td>
					<td></td>
					</tr>";



		$content.="</table>";
		/*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$printRow, 'Grand Total');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$printRow, '=SUM(I12:I'.($printRow-1).')');
		$objPHPExcel->getActiveSheet()->getStyle('E'.$printRow.':I'.$printRow)->applyFromArray($totalArray);
		$objPHPExcel->getActiveSheet()->mergeCells('E'.$printRow.':H'.$printRow);
		$objPHPExcel->getActiveSheet()->getStyle('A10:J'.$printRow)->applyFromArray($styleThinBlackBorderOutline);*/

	}


	$dompdf = new DOMPDF();
	//$dompdf->set_option("isPhpEnabled", true);
	$dompdf->set_paper('landscape', 'landscape');
	$dompdf->load_html($content);
	$dompdf->render();


	$font = Font_Metrics::get_font("helvetica", "bold");
	$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));

	$dompdf->output();
	//$dompdf->stream();
	$dompdf->stream('ConveyanceReport_'.date('d-M-Y H:i:s').'.pdf', array("Attachment" => true));
	exit;
}



 

?>

<?php include_once("includes/header.php")?>



<?php include_once("includes/left.php")?>

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> Conveyance Manager<small>Conveyance Report</small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Conveyance Report</li>

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

            <div class="form-group col-sm-4">



                <label for="reservation_date">From - To </label>



                <div class="input-group">



                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>



                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  autocomplete="off">


                </div>



                <!-- /.input group --> 



                <span id="reservation_dateError"></span> </div>

              <!--<div class="form-group col-sm-6">

                <label for="seasonId">Date <font color="#FF0000">*</font></label>

                <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>

              </div>-->

              <div class="col-md-4">

                <div class="form-group">

                  <label>Company</label>

                  <?php $companyDropDown = '<select class="form-control select2" name="companyId" '.$disabledCompany.'>

											    <option value="">Select Company</option>';

											  $resCat = selectSql(TBL_COMPANY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name !='' ",' ORDER BY `name`');

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

              </div>

              <?php 

			  if($_SESSION['userLevel']==1){

				 	

				  $ConditonUserLevel = "";

				  }else{

					  $ConditonUserLevel= "  `".TBL_USERS."`.`id` = '".addslashes($_SESSION['userId'])."' AND ";

					  }

			  ?>

              <div class="col-md-4">

                <div class="form-group">

                <label>Sales Executive</label>

                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->

               <?php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">

											  								<option value="">All</option>';

											  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  ".$ConditonUserLevel." `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

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

              

              <!-- /.row -->

            </div>

          </div>

          <!-- /.box-body -->

          <div class="box-footer">

            <!--<input name="Search" type="submit" class="btn btn-primary" value="Search" />-->

            <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />

            </div>

            

        </form>

      

        <div class="box">

          <!--<div class="box-header">

            <h3 class="box-title">Conveyance List</h3>

          </div>-->

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

           

            

             

               



                        <div class="box-body table-responsive">

                          <table id="example2" class="table table-bordered table-striped text-center">

                            <thead>

                            <!--<tr>

                              <th >S.No.&nbsp;</th>

            				  <th>Date</th>

            				  <th>Executive</th>

            				  <th>From</th>

                              <th>To</th>

                              <th>Company Visited</th>

                              <th>Kms Run</th>

                              <th>Rate/Km</th>

                              <th>Parking</th>

                              <th>Sub Total</th>

                              <th>Entertainment</th>

                              <th>Total</th>

                              <th>Status</th>
                            </tr>-->
                            </thead>

                            <tbody>

            				<?php 				 				

            				if($total > 0){$counter = 1;

            				  while($row = $db->fetch_object()){?>

                            <tr>

                              <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>



                              <td><?=date('d-M-Y',strtotime($row->dated));?></td>

                              <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->id_user." and id_shop=".$_SESSION['shop']." ");  

                               ?>

                               <td><?=$row->StatFrom;?></td>



                               <td><?=$row->StatTo;?></td>

                               <td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` =".$row->id_company." and id_shop=".$_SESSION['shop']." ");  

                               ?>

                               	

                               </td>



                               <td><?=$row->KmsRun;?></td>

                               <td><?=$row->RateKm;?></td>

                               <td><?=$row->Parking;?></td>

                               <td><?=$row->Total;?></td>

                               <td><?=$row->entertainment;?></td>

                               <td><?=$row->Total+$row->entertainment;?></td>



                               <?php

                               if($row->conveyance_approved==1)

                               		$approval ='Approved';

                               else

                               		$approval ='Not Approved';

                               ?>



                               <td><?=$approval;?></td>

            				  

                            </tr>

                           <?php }?> 

            			   

            				<tr>	 

            					  <td align="right" colspan="13"><?php  echo $pagging->getLinks();?> </td>

                             </tr>               

            				<?php }else {?>

            				

            				 <!--<tr>

                                  <td height="200" align="center" colspan="13">---- No Record Found ---- </td>

                             </tr>   -->              

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

