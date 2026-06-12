<?php include_once("../../config/auto_loader.php");

 $id_inv_items = $_POST["id_inv_items"]; 

$sql = "SELECT * FROM `".TBL_INV_ITEMS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_inv_items."' and `status` = '".'1'."' ";

//print_r($sql);

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$name= $row->name; 
	    	$conversion_qty= $row->conversion_qty; 
	    	$id_mst_attributes_unit_main= $row->id_mst_attributes_unit_main; 
	    	$id_mst_attributes_unit_alt= $row->id_mst_attributes_unit_alt; 
	    	$id_mst_attributes_store= $row->id_mst_attributes_store; 
	    	$id_mst_charges_purchase_local= $row->id_mst_charges_purchase_local; 
	    }

//Main Unit Get Here

$sql = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_attributes_unit_main."' and `status` = '".'1'."' ";

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$main_unit= $row->field_value;  
	    }  

//Alt Unit Get Here

$sql = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_attributes_unit_alt."' and `status` = '".'1'."' ";

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$alt_unit= $row->field_value;  
	    } 

//Store Get Here

$sql = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_attributes_store."' and `status` = '".'1'."' ";

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$store= $row->field_value;  
	    } 

$res['name'] = $name;
$res['main_unit'] = $main_unit;
$res['alt_unit'] = $alt_unit;
$res['conversion_qty'] = $conversion_qty;
$res['id_mst_attributes_store'] = $id_mst_attributes_store;
$res['store'] = $store;
$res['id_mst_charges_purchase_local'] = $id_mst_charges_purchase_local;
 
 
echo json_encode($res);
 empty($res);
?>