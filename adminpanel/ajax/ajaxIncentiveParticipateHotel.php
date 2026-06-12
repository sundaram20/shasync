<?php include_once("../../config/auto_loader.php");

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);


if($_REQUEST['hotel_id']!=''){	
  $resCat_rooms2 ="select  * from fs_incentive_participate_hotel WHERE  hotel_id='".addslashes($_REQUEST['hotel_id'])."'";
		$resCat_rooms1=mysqli_query($conn,$resCat_rooms2);	
		$rowHotelResult = mysqli_fetch_object($resCat_rooms1);
		if($rowHotelResult->hotel_id==''){
			$hotel_id	= $_REQUEST['hotel_id'];
			$hotel_percentage	= '';
			$executive_percentage	= '';
			$editId	= '';
			$Button='Add';
			}else{
				$editId	= $rowHotelResult->id;
				$hotel_id	= $rowHotelResult->hotel_id;
				$hotel_percentage	= $rowHotelResult->hotel_percentage;
				$executive_percentage	= $rowHotelResult->executive_percentage;				
				$Button='Edit';
				}
	
	

	
	$AddCompanylist .= '<table  class="table table-bordered table-striped">
	
	<tr><td>Hotel Percentage</br>
		  <input type="hidden" name="editId" value="'.$editId.'">
		  <input type="text" class="form-control" id="hotel_percentage" name="hotel_percentage" value="'.$hotel_percentage.'"  data-parsley-required automcomplete="off" data-parsley-type="number"  >
		  </td>';
	$AddCompanylist .= '<td>Executive Percentage</br>

  <input type="text" class="form-control" id="executive_percentage" name="executive_percentage" value="'.$executive_percentage.'" data-parsley-required automcomplete="off" data-parsley-type="number"  >
  </td>';
	  
echo $AddCompanylist .='<td></br>
<input name="Save" type="hidden" value="'.$Button.'" id="Save">
 <input type="button" value="'.$Button.'" name="Save" class="btn btn-primary" onclick="submitIncentiveParticipateHotel();"  >
</td></tr></table>';
}else{
	echo '';
	
	
	}
?>

