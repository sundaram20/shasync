<?php 
include_once("../../config/auto_loader.php");


$name = isset($_POST['name']) ? $_POST['name'] : "";
$start = isset($_POST['start']) ? $_POST['start'] : "";
$end = isset($_POST['end']) ? $_POST['end'] : "";
$currColorselected = isset($_POST['currColorselected']) ? $_POST['currColorselected'] : "";

if($_POST['DuphotelId']=='null'  || $_POST['DuphotelId']==''){
$resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													

													$ListHotelID[] = $resultCat->id;

												}

											  }
											  
}else{
		$ListHotelID	=explode(',',$_POST['DuphotelId']);
	}
	
$id_shop=$_SESSION['shop'];
$start = date("Y-m-d", strtotime($start));
$end = date("Y-m-d", strtotime($end));

  $sqlInsert = "INSERT INTO fs_event_calender (name,start,end,event_color,id_shop,date_created,last_modified,created_by,last_modified_by) VALUES ('".$name."','".$start."','".$end ."','".$currColorselected."','".$id_shop."','".currenDateTime()."','".currenDateTime()."','".$_SESSION['userId']."','".$_SESSION['userId']."')";

 $result = executeSql($sqlInsert);
 $LastInserId = $db->insert_id();
 
foreach($ListHotelID as $id_hotel){

 
  $sqlInsert1 = "INSERT INTO fs_event_calender_detail (event_id,id_hotel) VALUES ('".$LastInserId."','".$id_hotel."')";

 $result2 = executeSql($sqlInsert1);
 
}
 
 
 
	
	if ($result==1) {
     $json = array();
    $sqlQuery = "SELECT * FROM fr_event_calender WHERE  id=".$LastInserId;

    $result = executeSql($sqlQuery);
    $eventArray = array();
    while ($row = mysqli_fetch_assoc($result)) {
        //array_push($eventArray, $row);
		
			$e['id'] = $row['id'];
			$e['start'] = $row['start'];
			$e['title'] = $row['name'];
			$e['end'] = $row['end'];
			$e['backgroundColor'] = $row['event_color'];
			
			array_push($eventArray, $e);
    }
    mysqli_free_result($result);

    
    echo json_encode($eventArray);
}else{
	
	}

?>