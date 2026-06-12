<?php
	include_once("../../config/auto_loader.php");
	
 //$_POST['table_name']='mst_guest';
	$sqlQuery = "DESCRIBE " . $_POST['table_name']; 
	$table_name = $_POST['table_name'];
	$resShowTable = mysqli_query($connNew,$sqlQuery);


	$return = '<table  class="table table-bordered  text-center text-white">
					<thead>
						<tr  style="background-color:#3C8DBC;color:#fff;">
							<th colspan="9" ><h4>' . strtoupper($_POST['table_name']) . '</h4></th>
						</tr>
						<tr  style="background-color:#3C8DBC;color:#fff;">
							<th rowspan="2" style="width:5%;vertical-align: middle;">S.No.</th>
							<th rowspan="2"  style="width:10%;vertical-align: middle;" >Name</th>
							<th rowspan="2"  style="width:10%;vertical-align: middle;" >Label</th>
							
							<th colspan="7" style="width:75%;">Report Config</th>
						</tr>
						
						<tr style="background-color:#252525;color:#fff;">
							<th style="width:10%;">Display Order
							</th>
							<th style="width:7%;">Enabled Order
							</th>
							<th style="width:7%;">Display
							</th>
							<th style="width:7%;">Default Select
							</th>
						</tr>
					</thead><tbody>';	
	$sno = 0;
	$listtable = array();
	while($rowShowTable = mysqli_fetch_object($resShowTable)){


  		$resCat = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".$_POST['table_name']."' AND id_shop = ". $_SESSION['shop']." AND field_name = '".$rowShowTable->Field."' Order By display_order DESC");

		 if(mysqli_num_rows($resCat)){

		 	$resultCat = mysqli_fetch_object($resCat);
		 	$fieldId = $resultCat->id;
		 	$field_label = $resultCat->field_label;
		 	$display_order = $resultCat->display_order;
		 	//$enabled_order = $resultCat->enable_order_by;
		 	$display = $resultCat->display;
		 	if($display==1){
		 		$displaychecked = "checked";
		 	}else{
		 		$displaychecked = "";
		 	}
		 	if($resultCat->default_select==1){
		 		$defaultchecked = "checked";
		 	}else{
		 		$defaultchecked = "";
		 	}		
		 	if($resultCat->enable_order_by==1){
		 		$enabled_order = "checked";
		 	}else{
		 		$enabled_order = "";
		 	}	
		 	
		 }else{
		 	$field_label = "";
		 	$fieldId = "";
		 	$displaychecked = "";
		 	$defaultchecked = "";
		 	$enabled_order = "";
		 	$display_order = "";
		 }	

		 

		 $listtable[$rowShowTable->Field]['table_field'] = $rowShowTable->Field;
		 $listtable[$rowShowTable->Field]['fieldId'] = $fieldId;
		 $listtable[$rowShowTable->Field]['field_label'] = $field_label;
		 $listtable[$rowShowTable->Field]['display_order'] = $display_order;
		 $listtable[$rowShowTable->Field]['enabled_order'] = $enabled_order;
		 $listtable[$rowShowTable->Field]['display'] = $displaychecked;
		 $listtable[$rowShowTable->Field]['default_select'] = $defaultchecked;

		 


		/*$return .='<tr>
							<th style="width:5%;">'.($sno+1).'</th>
							<th style="width:25%;">'.$rowShowTable->Field.'</th>
							<input type="hidden" name="listtable['.$rowShowTable->Field.']['.table_field.']" value="'. $rowShowTable->Field . '" id="table_field" />
							<input type="hidden" class="form-control" name="listtable['.$rowShowTable->Field.']['.fieldId.']" value="'.$fieldId.'"/>
							<th style="width:25%;"><input type="text" class="form-control" name="listtable['.$rowShowTable->Field.']['.field_label.']" value="'.$field_label.'"/></th>
							<th style="width:6%;"><input type="number" class="form-control" name="listtable['.$rowShowTable->Field.']['.display_order.']"  value="'.$display_order.'" onchange="'.$OnKeyUpOne.'" id="display_order" /></th>
							<th style="width:13%;" ><input  type="checkbox"  value="'.$sno.'" name="listtable['.$rowShowTable->Field.']['.enabled_order.']" '.$enabled_order.' /></th>
							<th style="width:13%;" ><input  type="checkbox" value="'.$sno.'" name="listtable['.$rowShowTable->Field.']['.display.']" '.$displaychecked.'/></th>
							
							<th style="width:13%;" ><input  type="checkbox"  value="'.$sno.'" name="listtable['.$rowShowTable->Field.']['.default_select.']" '.$defaultchecked.' /></th>
							
						</tr>' ;
		$sno++;	*/

	}	

//echo "<pre>";
//array_multisort($listtable);
//usort($listtable, function($a, $b) {
   //return $a['display_order'] - $b['display_order'];
    //return $b['order'] <=> $a['order'];
   //if($a['display_order']==$b['display_order']) return 0;
  //  return $a['display_order'] < $b['display_order']?1:-1;

//}); 

/*uasort($listtable, function($a) {
     return (is_null($a['display_order']) OR $a['display_order'] == "") ? 1 : -1;


}); */

/*usort($arr, function($a, $b){
    if($a['display_order'] == "") return 1;
    if($b['display_order'] == "") return -1;
    return $a['display_order'] - $b['display_order'];
});*/

/*function date_compare($element1, $element2) { 
    $datetime1 = $element1['display_order']; 
    $datetime2 = $element2['display_order']; 
    return $datetime1 - $datetime2; 
} */ 
function sortByPosition($a, $b) {
    if ($a['display_order'] == $b['display_order']) return 0;
    if ($a['display_order'] == 0) return 1;
    if ($b['display_order'] == 0) return -1;
    return $a['display_order'] > $b['display_order'] ? 1 : -1;
}  
// Sort the array  
usort($listtable, 'sortByPosition'); 

//$descending_array = array_reverse(usort($listtable, 'display_order'));
//$listtable = array_reverse($listtable);


	// print_r($listtable);

	foreach ($listtable as $index ) {
		$table_field = $index['table_field'];
		$fieldId= $index['fieldId'];
		$field_label= $index['field_label'];
		$display_order= $index['display_order'];
		$enabled_order= $index['enabled_order'];
		$display= $index['display'];
		$default_select= $index['default_select'];
		
		//$fieldId = $resultCat->id;
		 	//$field_label = $resultCat->field_label;
		 //	$display_order = $resultCat->display_order;
		 	//$enabled_order = $resultCat->enable_order_by;
		 	//$display = $resultCat->display;
		 	if($display=='checked'){
		 		$displaychecked = "checked";
		 	}else{
		 		$displaychecked = "";
		 	}
		 	if($default_select=='checked'){
		 		$defaultchecked = "checked";
		 	}else{
		 		$defaultchecked = "";
		 	}		
		 	if($enabled_order=='checked'){
		 		$enabled_order = "checked";
		 	}else{
		 		$enabled_order = "";
		 	}	
		 	
		 /*}else{
		 	$field_label = "";
		 	$fieldId = "";
		 	$displaychecked = "";
		 	$defaultchecked = "";
		 	$enabled_order = "";
		 	$display_order = "";
		 }*/
		 $OnKeyUpOne ="changeDisplay(this.value,'".$table_field."','".$fieldId."')";	
			$return .='<tr>
							<th style="width:5%;">'.($sno+1).'</th>
							<th style="width:25%;">'.$table_field.'</th>
							<input type="hidden" name="listtable['.$table_field.']['.table_field.']" value="'.$table_field. '" id="table_field" />
							<input type="hidden" class="form-control" name="listtable['.$table_field.']['.fieldId.']" value="'.$fieldId.'"/>
							<th style="width:25%;"><input type="text" class="form-control" name="listtable['.$table_field.']['.field_label.']" value="'.$field_label.'"/></th>
							<th style="width:6%;"><input type="number" class="form-control" name="listtable['.$table_field.']['.display_order.']"  value="'.$display_order.'" onchange="'.$OnKeyUpOne.'" id="display_order" /></th>
							<th style="width:13%;" ><input  type="checkbox"  value="'.$sno.'" name="listtable['.$table_field.']['.enabled_order.']" '.$enabled_order.' /></th>
							<th style="width:13%;" ><input  type="checkbox" value="'.$sno.'" name="listtable['.$table_field.']['.display.']" '.$displaychecked.'/></th>
							
							<th style="width:13%;" ><input  type="checkbox"  value="'.$sno.'" name="listtable['.$table_field.']['.default_select.']" '.$defaultchecked.' /></th>
							
						</tr>' ;
		$sno++;
		
		}	

	$return .= '</tbody></table>';  

	echo $return ;
	mysqli_close($connNew);
	mysqli_close($appConnect);
?>
