<?php
include_once("../../config/auto_loader.php");

$result = array();

$sql = "SELECT longitude,latitude,location,type,TIME_FORMAT(created_at, '%h : %i %p') AS `time` FROM sales_executive_locations WHERE id_shop='".$_SESSION['shop']."' AND id_user='".$_REQUEST['usernameid']."' AND DATE(created_at)='".date('Y-m-d',strtotime($_REQUEST['report_date']))."' group by longitude  ORDER BY created_at DESC ";

$res = mysqli_query($connNew, $sql);

while($row = mysqli_fetch_object($res)){
	array_push($result,$row) ;
}

echo '[{"longitude":"80.21","latitude":"13.08","location":"10\/15, Block AD, AD Block, Anna Nagar, Chennai, Tamil Nadu 600040, India","type":"3","time":"01 : 18 PM"},{"longitude":"80.161153","latitude":"13.056570","location":"Palanisamy Road, 72\/14, Sakthi Nagar Third Main Road, Sivaraj Pet, Tirupattur, Tamil Nadu 635601, India","type":"3","time":"10 : 11 AM"},{"longitude":"80.240857","latitude":"13.068810","location":"Palanisamy Road, 72\/14, Sakthi Nagar Third Main Road, Sivaraj Pet, Tirupattur, Tamil Nadu 635601, India","type":"3","time":"10 : 11 AM"},{"longitude":"80.195629","latitude":"13.057255","location":"Palanisamy Road, 72\/14, Sakthi Nagar Third Main Road, Sivaraj Pet, Tirupattur, Tamil Nadu 635601, India","type":"3","time":"10 : 11 AM"}]
';//json_encode($result);