
		            	<table id="myTable1" class="table table-striped table-responsive table-bordered dataTable no-footer order-list1 max-h">
				             <thead>
				                <tr class="th-bg">
				                 
				                    <th >Item Code</th>
				                    <th >Item Description</th> 
				                    <th  >Qty</th> 
				                    <th >Unit</th> 
				                    <th>Rate</th>  
				                    <th>Per</th>  
				                    <th >%Dis</th> 
				                    <th >Amount</th> 
				                
				                     <th>Purchase Accounts</th>  
				                       <th>Tax</th>  
				                           <th>Remarks</th>    
				                </tr>
				               
				      
				            </thead >
				            	  <tbody >
				            	
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select
				            	// $sql2 = "SELECT * FROM  `".TBL_INV_PO_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $sql2 = "SELECT * FROM   enquiry_item_details WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND id='".$row->id_enquiry_item_details."' ";
								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
							 		 $array['id_inv_indent'.''.$i] = $rowsID->id_inv_indent; 
							 		 $array['id_inv_indent_details'.''.$i] = $rowsID->id_inv_indent_details;
							 		 $array['id_inv_items'.''.$i] = $rowsID->id_inv_items; 
							 		 $array['transaction_unit'.''.$i] = $rowsID->transaction_unit; 
							 		 $array['qty'.''.$i] = $rowsID->qty; 
							 		 $array['conver_rate_per_unit'.''.$i] = $rowsID->conver_rate_per_unit;
							 		 $array['id_mst_charges_purchase_interstate'.''.$i] = $rowsID->id_mst_charges_purchase_interstate;
							 		 $array['id_mst_charges_purchase_local'.''.$i] = $rowsID->id_mst_charges_purchase_local;
							 		 $array['rate_per_main_unit'.''.$i] = $rowsID->rate_per_main_unit;
							 		 $array['discount_percent'.''.$i] = $rowsID->discount_percent;
							 		 $array['discount_amount'.''.$i] = $rowsID->discount_amount;
							 		 $array['item_amount_before_discount'.''.$i] = $rowsID->item_amount_before_discount; 
							 		 $array['item_amount'.''.$i] = $rowsID->item_amount; 
							 		 $array['id_mst_charges_sgst'.''.$i] = $rowsID->id_mst_charges_sgst;
							 		 $array['item_sgst_percent'.''.$i] = $rowsID->item_sgst_percent;
							 		 $array['item_sgst_amount'.''.$i] = $rowsID->item_sgst_amount;
							 		 $array['id_mst_charges_cgst'.''.$i] = $rowsID->id_mst_charges_cgst;
							 		 $array['item_cgst_percent'.''.$i] = $rowsID->item_cgst_percent;
							 		 $array['item_cgst_amount'.''.$i] = $rowsID->item_cgst_amount;
							 		 $array['id_mst_charges_igst'.''.$i] = $rowsID->id_mst_charges_igst;
							 		 $array['item_igst_percent'.''.$i] = $rowsID->item_igst_percent;
							 		 $array['item_igst_amount'.''.$i] = $rowsID->item_igst_amount;
							 		 $array['item_remarks'.''.$i] = $rowsID->item_remarks;
							 		 $array['main_unit'.''.$i] = $rowsID->main_unit;
							 		 $array['alt_unit'.''.$i] = $rowsID->alt_unit;
							 		 $array['per_unit'.''.$i] = $rowsID->per_unit; 
							 		 $array['alt_qty'.''.$i] = $rowsID->alt_qty; 
							 		 $array['rate_per_alt_unit'.''.$i] = $rowsID->rate_per_alt_unit; 
									 
							//$array['id_indentt'.''.$i] = selectColumn('inv_indent_details','id_inv_indent','where id="'.$array['id_inv_indent_details'.''.$i].'" ');
							//$array['final'.''.$i] = selectColumn('inv_indent','doc_no','where id="'.$array['id_indentt'.''.$i].'" ');
							 		 
							 		 $i++;
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0){
								 	$k='';
								 }else{
								 	$k = $j;
								 } 
				            	?>
				            	<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;"  >
				                  <label for="name">Update Id</label>
				                  <input type="text" class="form-control" id="update_id<?php echo $k;?>" name="update_id<?php echo $k;?>" value="<?php echo $array['id'.''.$j];?>"> 

				                  <label for="name">Update Count</label>
				                  <input type="text" class="form-control" id="update_count" name="update_count" value="<?php echo $k;?>"> 
				                </div>
				                <?php 
								
								$edit_ledger_id = selectColumn('mst_party','ledger'," WHERE `id` = '".$row->id_mst_party_supplier."'");
								if($row->id == ''){ $ledger_id = ''; ?>
					                <style type="text/css">
					                /*	#locals{
					                		display: none;
					                	}
					                	#interstates{
					                		display: none;
					                	}
					                	#localss{
					                		display: none;
					                	}
					                	#interstatess{
					                		display: none;
					                	} */
					                </style>
					                <?php } elseif($edit_ledger_id == 1) {
					                 $ledger_id = 1; ?>
					                	<style type="text/css">
					                	 <?php echo $k;?>{
					                		display: block;
					                	}
					                	#interstates<?php echo $k;?>{
					                		display: none;
					                	}
					                	#localss<?php echo $k;?>{
					                		display: block;
					                	}
					                	#interstatess<?php echo $k;?>{
					                		display: none;
					                	} 
					                	</style>
					                <?php } elseif($edit_ledger_id == 2) {$ledger_id = 2; ?>
					                	<style type="text/css"> 
					                	/*#locals<?php echo $k;?>{
					                		display: none;
					                	}
					                	#interstates<?php echo $k;?>{
					                		display: block;
					                	}
					                	#localss<?php echo $k;?>{
					                		display: none;
					                	}
					                	#interstatess<?php echo $k;?>{
					                		display: block;
					                	}*/
					                	tbody{
					                		border:1px solid red;
					                	}
					                	</style>
					                <?php } ?>
					                <input id="ledger_id" name="ledger_id" value="<?php if($_POST) echo $ledger_id;else echo stripslashes($ledger_id); ?>" type="hidden">
					              
				                <tr id="edittrdelete<?php echo $k;?>">
					                <input hidden id="select<?php echo $k;?>" name="select<?php echo $k;?>">
					              <!-- <td style="width:10%"> 

					                 <select data-parsley-required data-parsley-errors-container="#outletError3" class="form-control select2" name="id_inv_indent<?php echo $k;?>" id="id_inv_indent<?php echo $k;?>" onchange="popupshow(this.id);" style="width:100%">
										<?php $categoryDropDown = '<option value=""> Select Indent No -1</option>'.'<option value="na">NA</option>';

											if($_REQUEST['eId']==''){
						                   		$condChk = 'AND inv_indent_details.bal_qty > 0';
						                   	}
						                   	else{
						                   		$condChk = '';
						                   	}	

											$sql = "SELECT inv_indent.doc_date, inv_indent.mdoc_no,  inv_indent.doc_no, 
						                   	inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id, inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, 
						                   	inv_items.item_code, inv_items.name, 
						                   	mst_attributes.field_value 
						                   	FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id ".$condChk."  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and  inv_indent.doc_type = '2'   group by inv_indent.doc_no,inv_indent.id_doc_type_configuration ";	

						                   	/*if($_REQUEST['eId']==''){
						                   		$condChk = 'AND B.bal_qty!=0';
						                   	}
						                   	else{
						                   		$condChk = '';
						                   	}	

						                   	$sql="SELECT DISTINCT B.id_inv_indent,A.doc_no,A.doc_date FROM ".TBL_INV_INDENT." A CROSS JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent WHERE  A.id_shop='".$_SESSION['shop']."' ".$condChk."  AND B.doc_type=2 ";*/	

												$db->query($sql); 
							                    while($row1 = $db->fetch_object()){	
												
											if($row->id !=''){
												if($_REQUEST['id_inv_indent'] == $row1->id){
													$selected = 'selected="selected"';
												}elseif($array['final'.''.$j] == $row1->doc_no){
													$selected = 'selected="selected"';													
												}else{
													$selected = '';
												}
											}else{
												if($_REQUEST['id_inv_indent'] == $row1->id){
													$selected = 'selected="selected"';
												}elseif($array['id_inv_indent'.''.$j] == $row1->doc_no){
													$selected = 'selected="selected"';													
												}else{
													$selected = '';
												}
											}
												$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'-'.$row1->id_inv_indent.'">'.ucfirst($row1->doc_no.' | '.date('d-m-Y' , strtotime(addslashes($row1->doc_date)))).'</option>';
												}
												if($row->id !=''){
													if($array['final'.''.$j] == '')	 {
														$categoryDropDown .= '<option selected="selected" value="na">NA</option>';
													}
												}
											 	echo $categoryDropDown .= '</select><span id="outletError3"></span>';  
										?> 
					                </td> -->  
					               <input type="text"  autocomplete="off" name="id_inv_indent_details<?php echo $k;?>" id="id_inv_indent_details<?php echo $k;?>" placeholder="ID"  class="form-control"  value="<?php if($_POST) echo $_POST['id_inv_indent_details'];else echo stripslashes($array['id_inv_indent_details'.''.$j]); ?>" readonly  style="display:none;" />
									
					              
				                	<td style="width:7%"> 
				                		<input type="text"  autocomplete="off" name="id_inv_items<?php echo $k;?>" id="id_inv_items<?php echo $k;?>" placeholder="Item ID"  class="form-control"  value="<?php if($_POST) echo $_POST['id_inv_items'];else echo stripslashes($array['id_inv_items'.''.$j]); ?>" style="display:none;" /> 

				                		<?php 
				                		//Name Get
				                			$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND status=1 AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                			//Item Description Get
				                			$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
											
				                			//$item_description1  =  selectColumn(TBL_INV_ITEMS,'id_mst_charges_purchase_local'," WHERE `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                		?>
				                <!--	<div id="hideshow_item_code">
					                 	<input type="text"  autocomplete="off" name="item_code<?php echo $k;?>" id="item_code<?php echo $k;?>" placeholder="Item Code"  class="form-control"  value="<?php echo $item_code; ?>" readonly />
					                </div>-->
					                <div id="hideshow_item_codes" >
					                	<select class="form-control select2" name="id_inv_items_po<?php echo $k;?>" id="id_inv_items_po<?php echo $k;?>" onchange="itemget(this.id)" style="width:100%">
										<?php $categoryDropDown = '<option value="">Select Item Code</option>';
										
										

											$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both') AND id_shop = ".$_SESSION['shop'] ." ";
												$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
												
													while($sqlRow = mysqli_fetch_object($QuerySQL1)){
												        $list = $sqlRow->id;
														$string .= $list.',';
													}	
											$item_list = rtrim($string,',');										
											 						 
							                   	$sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.status=1 and inv_items.id_mst_attributes_item_type IN ($item_list) and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
							                  
							                   	 $db->query($sql); 
							                    while($row1 = $db->fetch_object()){	

							                    	if($_REQUEST['id_inv_items'] == $row1->id){
														$selected = 'selected="selected"';
													}elseif($array['id_inv_items'.''.$j] == $row1->id){
														$selected = 'selected="selected"';
														$item_description =  $row1->name;
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->item_code.' | '.$row1->name).'</option>';
												} 
											  
											 	echo $categoryDropDown .= '</select>'; 
										?>
					                  </div>
									
					                </td> 
				                    <td style="width:18%;">
				                        <input type="text"  autocomplete="off" name="item_description<?php echo $k;?>" id="item_description<?php echo $k;?>" placeholder="Item Description"  class="form-control"   value="<?php echo $item_description; ?>" readonly />
				                    </td> 
				                    <?php 
				                    	$transaction_unit = $array['transaction_unit'.''.$j];
				                    	$main_unit = $array['main_unit'.''.$j];
				                    	$alt_unit = $array['alt_unit'.''.$j];
				                    	$per_unit = $array['per_unit'.''.$j];
				                    	if($transaction_unit == $main_unit){
				                    		$qty = $array['qty'.''.$j]; 
				                    	}else{
				                    		$qty = $array['alt_qty'.''.$j]; 
				                    	}
				                    	if($per_unit == $main_unit){ 
				                    		$rate_per_main_unit = $array['rate_per_main_unit'.''.$j];
				                    	}else{ 
				                    		$rate_per_main_unit = $array['rate_per_alt_unit'.''.$j];
				                    	}
										//echo $rate_per_main_unit;
				                    ?>
									
									
									
				                    <td  style="width:6%"> 
				                        <input data-parsley-required type="text"  autocomplete="off"  name="qty<?php echo $k;?>" id="qty<?php echo $k;?>" placeholder="Qty" onkeyup="amount_calc(this.id);" onclick="amount_calc(this.id);"  class="form-control discou" value="<?php if($qty=='') echo '0';else echo $qty; ?>" />
				                    </td>
				                    <td style="width:6%;">  
				                        <select class="form-control select2" id="transaction_unit<?php echo $k;?>" name="transaction_unit<?php echo $k;?>" onchange="amount_calc(this.id);" style="width:100%"> 
				                        <?php if($row->id != ''){?> <option value="<?php echo $array['transaction_unit'.''.$j];?>" selected="selected"><?php echo $array['transaction_unit'.''.$j];?></option> <option value="<?php echo $array['main_unit'.''.$j];?>" ><?php echo $array['main_unit'.''.$j];?></option><option value="<?php echo $array['alt_unit'.''.$j];?>" ><?php echo $array['alt_unit'.''.$j];?></option><?php } ?>
					                  	 </select>
					                  	 <!-- Main Unit -->
					                  	 <input type="text"  autocomplete="off" name="main_unit<?php echo $k;?>" id="main_unit<?php echo $k;?>" placeholder="Main Unit"  class="form-control"   value="<?php if($_POST) echo $_POST['main_unit'];else echo stripslashes($array['main_unit'.''.$j]); ?>"  style="display:none;"/>
					                  	 <!-- Alt Unit -->
					                  	 <input type="text"  autocomplete="off" name="alt_unit<?php echo $k;?>" id="alt_unit<?php echo $k;?>" placeholder="Alt Unit"  class="form-control"   value="<?php if($_POST) echo $_POST['alt_unit'];else echo stripslashes($array['alt_unit'.''.$j]); ?>"  style="display:none;"/>
					                  	 <!-- Conversion Rate Per Unit -->
					                  	 <input  type="text"  autocomplete="off" name="conver_rate_per_unit<?php echo $k;?>" id="conver_rate_per_unit<?php echo $k;?>" placeholder="conver_rate_per_unit"  class="form-control discountvalue"   value="<?php if($_POST) echo $_POST['conver_rate_per_unit'];else echo stripslashes($array['conver_rate_per_unit'.''.$j]); ?>"  style="display:none;"/>
				                    </td>
				                     
				                    <td style="width:5%"> 
					                 	 <input type="text"  autocomplete="off"  name="rate_per_main_unit<?php echo $k;?>" id="rate_per_main_unit<?php echo $k;?>" placeholder="Rate"  class="form-control discountvalue" value="<?php if($rate_per_main_unit == ''){ echo '0';  }else {  echo $rate_per_main_unit; } ?>" onkeyup="amount_calc(this.id)" required />

					                 	 <input type="text"  autocomplete="off"  name="item_amount_before_discount<?php echo $k;?>" id="item_amount_before_discount<?php echo $k;?>" placeholder="Rate"  class="form-control discountvalue" value="<?php if($_POST) echo $_POST['item_amount_before_discount'];else echo stripslashes($array['item_amount_before_discount'.''.$j]); ?>" style="display:none;" />
					                </td>
					                <td style="width:5%">  
				                        <select class="form-control select2" id="per_unit<?php echo $k;?>" name="per_unit<?php echo $k;?>" onchange="amount_calc(this.id);" style="width:100%"> 
				                        <?php if($row->id != ''){?> <option value="<?php echo $array['per_unit'.''.$j];?>" selected="selected"><?php echo $array['per_unit'.''.$j];?></option><option value="<?php echo $array['main_unit'.''.$j];?>" ><?php echo $array['main_unit'.''.$j];?></option><option value="<?php echo $array['alt_unit'.''.$j];?>" ><?php echo $array['alt_unit'.''.$j];?></option> <?php } ?>
					                  	 </select>
				                    </td>
					                <td style="width:4%;">
				                         <input type="text"  autocomplete="off"  name="discount_percent<?php echo $k;?>" id="discount_percent<?php echo $k;?>" placeholder="%Discount"  class="form-control discountvalue" value="<?php if($array['discount_percent'.''.$j]=='') echo '0';else echo stripslashes($array['discount_percent'.''.$j]); ?>" onkeyup="amount_calc(this.id);"  onclick="amount_calc(this.id);" />
				                    </td>
					                <td style="width:6%;"> 
					                 	 <input type="text"  data-parsley-required data-parsley-errors-container="#item_amount<?php echo $k;?>Error3" autocomplete="off"  name="item_amount<?php echo $k;?>" id="item_amount<?php echo $k;?>" placeholder="Amount"  class="form-control discountvalue" value="<?php if($array['item_amount'.''.$j]=='') echo '';else echo stripslashes($array['item_amount'.''.$j]); ?>" readonly/>
					                </td>					                
					                
				                  	<div id="taxconfig" id="taxconfig" style="display: none;">
				                    	<!-- SGST -->
				                    	<input type="text"  autocomplete="off"  name="id_mst_charges_sgst<?php echo $k;?>" id="id_mst_charges_sgst<?php echo $k;?>" placeholder="SGST"  class="form-control" value="<?php if($_POST) echo $_POST['id_mst_charges_sgst'];else echo stripslashes($array['id_mst_charges_sgst'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_sgst_percent<?php echo $k;?>" id="item_sgst_percent<?php echo $k;?>" placeholder="SGST"  class="form-control" value="<?php if($_POST) echo $_POST['item_sgst_percent'];else echo stripslashes($array['item_sgst_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_sgst_amount<?php echo $k;?>" id="item_sgst_amount<?php echo $k;?>" placeholder="SGST Amount"  class="form-control" value="<?php if($_POST) echo $_POST['item_sgst_amount'];else echo stripslashes($array['item_sgst_amount'.''.$j]); ?>" />
										
										<!-- CGST -->
										<input type="text"  autocomplete="off"  name="id_mst_charges_cgst<?php echo $k;?>" id="id_mst_charges_cgst<?php echo $k;?>" placeholder="CGST"  class="form-control" value="<?php if($_POST) echo $_POST['id_mst_charges_cgst'];else echo stripslashes($array['id_mst_charges_cgst'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_cgst_percent<?php echo $k;?>" id="item_cgst_percent<?php echo $k;?>" placeholder="CGST"  class="form-control" value="<?php if($_POST) echo $_POST['item_cgst_percent'];else echo stripslashes($array['item_cgst_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_cgst_amount<?php echo $k;?>" id="item_cgst_amount<?php echo $k;?>" placeholder="CGST Amount"  class="form-control" value="<?php if($_POST) echo $_POST['item_cgst_amount'];else echo stripslashes($array['item_cgst_amount'.''.$j]); ?>" />
										<!-- IGST -->
										<input type="text"  autocomplete="off"  name="id_mst_charges_igst<?php echo $k;?>" id="id_mst_charges_igst<?php echo $k;?>" placeholder="IGST"  class="form-control" value="<?php if($_POST) echo $_POST['id_mst_charges_igst'];else echo stripslashes($array['id_mst_charges_igst'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_igst_percent<?php echo $k;?>" id="item_igst_percent<?php echo $k;?>" placeholder="IGST"  class="form-control" value="<?php if($_POST) echo $_POST['item_igst_percent'];else echo stripslashes($array['item_igst_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_igst_amount<?php echo $k;?>" id="item_igst_amount<?php echo $k;?>" placeholder="IGST Amount"  class="form-control" value="<?php if($_POST) echo $_POST['item_igst_amount'];else echo stripslashes($array['item_igst_amount'.''.$j]); ?>" />
									</div>		                				                    				                  	

				                    
				                    <?php if($k>=1){ ?>
				                    
 
					                <?php if($row->id != ''){?>
                                     <td style="display:none;">
					                   	<input type="text"  autocomplete="off"  name="dbid<?php echo $k;?>" id="dbid<?php echo $k;?>" class="form-control" value="<?php if($_POST) echo $_POST['dbid'];else echo stripslashes($array['id'.''.$j]); ?>" style="display: none;"/>
					                </td>
                                     <?php } ?>
				                    
				                	<?php } 
				                	 if($row->id ==''){
				                	 	$counts = 0;
				                	 }else{
				                	 	$counts = $k;
				                	 }
				                	 ?>
				           
				                        
								
									
				                		
				                    <td style="width:6%;"> 
					                					                    
					                   <div id="locals<?php echo $k;?>" name="locals<?php echo $k;?>" >

					                  	<select onchange="po_locals();" class="form-control select2" name="id_mst_charges_purchase_local<?php echo $k;?>" id="id_mst_charges_purchase_local<?php echo $k;?>" style="width:100%;">

										 <?php $categoryDropDown = '<option value="">Select Tax Register</option>';
										  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '2'  ",' ORDER BY `name`');
										  if($db->num_rows2($resCat)){
										  	while($resultCat = $db->fetch_object2($resCat)){
												//$_REQUEST['id_mst_charges_purchase_local'] = '6' ;
												//id_mst_charges_purchase_local1
												
												if($_REQUEST['id_mst_charges_purchase_local'] == $resultCat->id){
													$selected = 'selected="selected"';
												}elseif($array['id_mst_charges_purchase_local'.''.$j] == $resultCat->id){
													$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
											}
										  }
										 	echo $categoryDropDown .= '</select>';
										  ?>
											<?php echo $err_item_chargestax;?>
											<?php if($row->id !=''){
												$sgst = 'SGST: '.''.$array['item_sgst_amount'.''.$j];
												$cgst = 'CGST: '.''.$array['item_cgst_amount'.''.$j];
												$igst = 'IGST: '.''.$array['item_igst_amount'.''.$j];
											}else{
												$sgst='';
												$cgst = '';
												$igst = '';
											}
											?>
											
										</div>

					                  	<!-- <div id="interstates<?php echo $k;?>" name="interstates<?php echo $k;?>">
					                  	 	<select  onchange="po_interstate(this.id)" class="form-control select2" name="id_mst_charges_purchase_interstate<?php echo $k;?>" id="id_mst_charges_purchase_interstate<?php echo $k;?>"  style="width:100%;" >
											<?php $categoryDropDown = '<option value="">Select Tax Register</option>';
											  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1' and charges_account = '2' and transaction_type = '2' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_mst_charges_purchase_interstate'] == $resultCat->id){
														$selected = 'selected="selected"';
													}elseif( $array['id_mst_charges_purchase_interstate'.''.$j] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
											<?php echo $err_item_chargestax;?>
											
					                  	</div>-->
				                  	</td>
				                  	<td width="8%">
				                  	  <!--<input type="text"  placeholder="Tax here"  class="form-control" value="" />-->
				                  		<div id="localss<?php echo $k;?>" name="localss<?php echo $k;?>" >
					                  		<div style="color:red;font-size:11px;" id="s_amount<?php echo $k;?>">
												<?php echo $sgst;?>
											</div>
											<div style="color:red;font-size:11px;" id="c_amount<?php echo $k;?>">
												<?php echo $cgst;?>
											</div>

											<div style="color:red;font-size:11px;" id="vat_amount<?php echo $k;?>">
												<?php echo $vat;?>
											</div>
												
											<div style="color:red;font-size:11px;" id="cess_amount<?php echo $k;?>">
												<?php echo $cess;?>
											</div>
												
											
											<div style="color:red;font-size:11px;" id="surcharge_amount<?php echo $k;?>">
												<?php echo $surcharge;?>
											</div>
										</div>
										
										 <div id="interstatess<?php echo $k;?>" name="interstatess<?php echo $k;?>">
											<div style="color:red;font-size:11px;" id="i_amount<?php echo $k;?>">
													<?php echo $igst;?>
											</div>
										</div>
									</td>

				                 <td style="width:15%;"> 
				                       <input type="text"  autocomplete="off"  name="item_remarks<?php echo $k;?>" id="item_remarks<?php echo $k;?>" placeholder="Remarks"  class="form-control" value="<?php if($_POST) echo $_POST['item_remarks'];else echo stripslashes($array['item_remarks'.''.$j]); ?>" />
				                    </td>
									
									
									
									
				                  	
				                 
				                  	<td style="width:1%;"><?php if($k>=1){ ?>
                                    <a class="btn n-btn  abtn ibtnDel1 " style="cursor:pointer;" title="Delete" id="ibtn<?php echo $k;?>"  name="ibtn<?php echo $k;?>"><i class="fa fa-trash-o"></i></a>
                                    
                  <?php /*?><img src="images/delete.gif"  class="ibtnDel1" style="cursor:pointer;" title="Delete" id="ibtn<?php echo $k;?>"  name="ibtn<?php echo $k;?>"/><?php */?>
				  
				  <?php } ?></td>
				                	
				                	 <!--<td class="form-group col-xs-12 col-sm-2"><a class="deleteRows" ></a></td>-->
				                </tr>

				            	<?php } ?>
				            	<input type="text" name="counter1" id="counter1" value="<?php echo 
				                    $counts; ?>" hidden=""> 

				               
				            </tbody>
				            </div>

				            <tfoot>
				                <tr> 
				                        <td colspan="12" style="text-align: left;">
				                          
											  <a  type="button" class="btn n-btn btn-block" style="font-size:14px;font-weight:700" id="addrow1" value="Add Row" ><span style="font-size:14px;font-weight:700"><i class="fa fa-plus"></i> Add Row</span> </a>
				                        </td> 
				                </tr>
				                <tr>
				                </tr>
				            </tfoot> 
				        </table>