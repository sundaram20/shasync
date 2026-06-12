<?php include_once("includes/autoloader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$hotelId = $_POST['hotelId'];
$room_id = $_POST['room_id'];
$reservation_date = explode(' to ',$_POST['reservation_date']);


$checkinDate = $reservation_date['0'];
$checkoutDate = $reservation_date['1'];
if($room_id == 0){
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");
}else{
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."' and ahr.room_id='".addslashes($room_id)."'");
}

$availableData = '<table class="table table-hover">
					<tr>
					  <th>Room Type</th>';
					  $availableData .= '<th>'.date('d M, Y', strtotime($checkinDate)).'</th>';
					for($i =0; $i < 6; $i++){
						$checkinDate = date('d M, Y', strtotime('+1 day', strtotime($checkinDate)));
						$availableData .= '<th>'.$checkinDate.'</th>';
					}				  
$availableData .= '</tr>';


if(mysql_num_rows($resRoom) >0 ){
/*while (strtotime($startdate) <= strtotime($enddate)) {			
			for($i=0;$i<$bookingRoomNo;$i++){			
				if($bookingAdultNo == '1'){
					$priceValue += $single_price;			
				}elseif($bookingAdultNo == '2'){
				    $priceValue += $double_price;	
				}elseif($bookingAdultNo == '3'){
				    $priceValue += $single_price+$double_price;	
				}
				if($bookingChildNo == '1'){
					$priceValue += $single_price;
				}elseif($bookingChildNo == '2'){					
				    $priceValue += $single_price*2;	
				}					
			}
			$startdate = date ("Y-m-d", strtotime("+1 day", strtotime($startdate)));
}*/
while($rowRoom = $db->fetch_object2($resRoom)){

$availableData .= '<tr>
                  <td>'.$rowRoom->name.'</td>';
						for($i =0; $i < 7; $i++){						
							$availableData .= '<td>'.$rowRoom->inventory.' AVL</td>';							
							}
							$inventory += $rowRoom->inventory;
$availableData .= '</tr>';
}			

				 
$availableData .= '<tr>
                  <td>Total Rooms Available</td>';
				  
if($inventory != 0){
$availableClass = 'label-success';
}else {
$availableClass = 'label-danger';
}				  
for($i =0; $i < 7; $i++){		
$availableData .= '<td><span class="label '.$availableClass.'">'.$inventory .' AVL</span></td>';
}				  
								  
$availableData .= '</tr>  
              </table>';
}else {
$availableData .= '<tr align="center">
                  <td colspan="8" >No Data Available. Please try different Search.</td>
                </tr>';

}
			  
echo $availableData;
?>