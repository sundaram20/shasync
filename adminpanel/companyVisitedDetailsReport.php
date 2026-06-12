<?php
	include_once("../config/auto_loader.php");
	include_once("includes/reportFunctions.php");
	
	checkUserLevelPermission($_SESSION['userLevel'],'companyVisitedDetailsReport','view');

	if(@isset($_REQUEST['Download'])){

		$date = explode(' to ', $_REQUEST['report_date']);
		$from = date('Y-m-d',strtotime($date[0]));
		$to = date('Y-m-d',strtotime($date[1]));
		
		companyVisitedDetails($_SESSION['shop'], $_REQUEST['search_name'] , $_SESSION['teamMembers'], $from, $to,$_REQUEST['usernameid']);
	
	}	

?>

<?php include_once("includes/header.php")?>

<?php include_once("includes/left.php")?>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Company Visited Detail Report

        <small></small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Sales Report/Company Visited Detail Report</li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">		

	

      <div class="row">

        <div class="col-xs-12">		     

          <!-- /.box -->

          <div class="box">

            <div class="box-header">

              <h3 class="box-title">Company Visited Detail Report</h3>

            </div>

                    <form name="searchForm" action="" method="get">

                      <input type="hidden" value="1" name="searchFormSubmit" />

                      <div class="box-body">

                        <div class="row">

                          <div class="form-group col-sm-4">



                              <label for="reservation_date">From - To </label>



                              <div class="input-group">



                                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>



                                <input type="text" class="form-control pull-right dateRangethirtydays" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  automcomplete="off">

                         </div>     



                              </div>



                        <div class="form-group col-sm-4">

                          <label for="id_company">Company Name - City </label>

                  <select class="form-control select2 itemName" name="search_name" id="search_name"   >

                  </select>

              </div>  

                                                   

                          <div class="col-md-4">

                            <div class="form-group">

                            <label>Sales Executive</label>

                            

                           <?php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid" >

                        <option value="">Select Executive</option>';

                          //$resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND `sales_status_active` = '1'  ".$teamMembers."  $UserRestriction AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`'); //COMMENTED ON 13-02-2021
                          $resUserLevel = selectSql(TBL_USERS," WHERE  `sales_status_active` = '1'  ".$teamMembers."  $UserRestriction AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

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
                          <div class="col-md-4">

                                           
<label><span class="text-danger">*</span><b> leave blank for all</b></label>
</div>

                      </div>
                      
     
                      <!-- /.box-body -->

                      <div class="box-footer">

                       <!-- <input name="Search" type="submit" class="btn btn-primary" value="Search" />-->

                        <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />

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
<script>
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
 </script>