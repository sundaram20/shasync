<?php include_once("../../config/auto_loader.php");

////////////////////////////////////////////////////////////////////////////////////////




$remove = $_REQUEST['remove'];

$uniqueCode = $_REQUEST['uniqueCode'];

$FollowupRemove = $_REQUEST['FollowupRemove'];



 if(($remove == 'removeOne') && ($uniqueCode!='')  ){



	unset($_SESSION['followup_hotel_id'][$uniqueCode]);

	unset($_SESSION['followup_description'][$uniqueCode]);

	unset($_SESSION['followup_date'][$uniqueCode]);

	unset($_SESSION['followupstatus'][$uniqueCode]);

	unset($_SESSION['feedback_Explode_Description'][$uniqueCode]);



	

	$DateVisitList	='

<div class="box">

          

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

        	

			<div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                  <tr>

                                   

                    <th>Added On</th>

					

                     <th>Follow Up Summary</th>

                    <th>Follow Up Date</th>

					<th>Status</th>

                    <th>Action</th>

					

                    

                  </tr>

                </thead>

                <tbody>';

	foreach($_SESSION['followup_hotel_id'] as $Followuphotel => $k){


	$DateVisitList	.='<tr>';

			   $DateVisitList	.='<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['followup_hotel_id'][$Followuphotel]."'").'</td>';
			   

			   $DateVisitList	.='<td>'.$_SESSION['followup_description'][$Followuphotel].'</td>';

	$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['followup_date'][$Followuphotel])).'</td>';

	$DateVisitList	.='<td>'.$_SESSION['followupstatus'][$Followuphotel].'</td>';

		  $DateVisitList	.='<td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$Followuphotel.'" onclick="ajaxFollowupRemove($(this).attr(\'id\'));");">

				  <i class="fa fa-trash-o fa-lg"></i> </a></td>';

		

		$DateVisitList	.='</tr>';

	

   

			   }

			   

	

	echo $DateVisitList;

			

			

	

}





?>