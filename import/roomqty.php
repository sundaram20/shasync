<?php 
include_once("../config/fron_autoload.php"); 

	$query1 = "SELECT * FROM fs_orders" ;

	$result1 = executeSql($query1,$link);
	 $query1count = mysql_num_rows($result1);
	while($query1data = mysql_fetch_array($result1)){
	  $order_id	=	$query1data['id_order'];
	
	
	$SQL2 =	"SELECT id_order,room_quantity as room_quantity  FROM  fs_order_detail WHERE hotel_id='126' AND id_order='".$order_id."' group by id_order";	
	
	$result2 = executeSql($SQL2,$link);
	$query2count = mysql_num_rows($result2);
	while($query2data = mysql_fetch_array($result2)){
	
	$order_id	=	$query2data['id_order'];
	
	
	$room_quantity	=	$query2data['room_quantity'];
	
	
	echo "<br>".$update =	"update `fs_orders` SET  `total_products` =  '".$room_quantity."' WHERE  `id_order` ='".$order_id."'".";";

	}
}

echo "sucess";

?>