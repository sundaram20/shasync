<?php include_once("../../config/auto_loader.php");

 $po = $_POST["po"]; 

 	//PO Locals
 
	if($po == 'locals'){

 		$id_mst_charges_purchase_local = $_POST["id_mst_charges_purchase_local"]; 

		$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_charges_purchase_local."' ";
		$db->query($sql2);  
		$numRows= $db->num_rows();
			
			while($row2 = $db->fetch_object()){  
					$sgst = $row2->id_mst_charges_sgst;       
					$cgst = $row2->id_mst_charges_cgst; 

					$res['id_mst_charges_sgst']  = $sgst;
					$res['id_mst_charges_cgst']  = $cgst;


			}
			//SGST GET HERE
			$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$sgst."' ";
			$db->query($sql2);  
			$numRows= $db->num_rows();
			if($numRows >= 1){
				while($row2 = $db->fetch_object()){  
						$res['sgst'] = $row2->percentage;
						
				}
			}
			//CGST GET HERE
			$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$cgst."' ";
			$db->query($sql2);  
			$numRows= $db->num_rows();
			if($numRows >= 1){
				while($row2 = $db->fetch_object()){  
					$res['cgst'] = $row2->percentage;
					
				}
			}
		echo json_encode($res);
	 	empty($res);

	}
	//PO Interstate
	if($po == 'interstate'){
		
 		$id_mst_charges_purchase_interstate = $_POST["id_mst_charges_purchase_local"]; 

		 $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_charges_purchase_interstate."' ";
		$db->query($sql2);  
		$numRows= $db->num_rows();
			
			while($row2 = $db->fetch_object()){  
					$igst = $row2->id_mst_charges_igst;
					$res['id_mst_charges_igst']  = $igst;         
			}
			//SGST GET HERE
			$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$igst."' ";
			$db->query($sql2);  
			$numRows= $db->num_rows();
			if($numRows >= 1){
				while($row2 = $db->fetch_object()){  
						$res['igst'] = $row2->percentage;
						
				}
			} 
		echo json_encode($res);
	 	empty($res);

	}
?>