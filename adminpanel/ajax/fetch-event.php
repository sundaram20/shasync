<?php include_once("../../config/auto_loader.php");


   $selected_hotel_id =$_REQUEST['selected_hotel_id'];
  
if($selected_hotel_id !=''){
	$Query		= " AND id_hotel = '".addslashes($selected_hotel_id)."'";
	}
$eventArray='';
$e='';
$json='';
unset($eventArray);
unset($e);
unset($json);
    $json = array();
    $sqlQuery = "SELECT * FROM fs_event_calender ORDER BY id";

    $result = executeSql($sqlQuery);
    $eventArray = array();
    while ($row = mysqli_fetch_assoc($result)) {
        //array_push($eventArray, $row);
				  $sqlQuery2 = "SELECT * FROM fs_event_calender_detail where event_id='". $row['id']."' $Query";
				$result2 = executeSql($sqlQuery2);
				if(num_rows($result2) > 0){
				
					$e['id'] = $row['id'];
					$e['start'] = $row['start'];
					$e['title'] = $row['name'];
					$e['end'] = $row['end'];
					$e['backgroundColor'] = $row['event_color'];
					$e['borderColor'] = $row['event_color'];
					array_push($eventArray, $e);
			}
    }
    //mysqli_free_result($result);

   $_REQUEST['selected_hotel_id']='';
   unset($_REQUEST['selected_hotel_id']);	
    echo json_encode($eventArray);
?>