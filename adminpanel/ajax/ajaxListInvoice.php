<?php  include_once("../../config/auto_loader.php");

$Sql = "SELECT * FROM `invoice_request` where   `id` = '".addslashes($_REQUEST['id_invoice_request'])."' AND status='0'  ";	
$row = mysqli_fetch_object(mysqli_query($connNew,$Sql));
$row->json_data;
$Records=array();
$Records['Invoice']	=json_decode($row->json_data,true);	
$listarray=array();
//$arrayList ='<tbody>';
				
				foreach($Records as $DataList){
					foreach($DataList as $Invoice){
						$InvoiceStatusInvoiceNo='';$InvoiceStatusCompany='' ;$InvoiceStatusHotel='';
					  $SqlInvoicw = "SELECT * FROM `invoice` where   `invoice_no` = '".addslashes($Invoice['Invoice No'])."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ";	
						$rowInvoice = mysqli_num_rows(mysqli_query($connNew,$SqlInvoicw));
						 
						 if($rowInvoice=='0'){
							
							 }else{
								 $InvoiceText ='Invoice No Already Exist';
							 $InvoiceStatusInvoiceNo='1';//
							 $InvoiceStatusSet='1';
							 $color='red';
								  //$InvoiceText ='Success';
							 	  //$InvoiceStatus='0';//
								  //$color='green';
								 }
				$SQLCompany	= "
				select *  from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !='' AND name LIKE '{$Invoice['Source']}%'  and city='".$Invoice['Source City']."'
				";
				
				
				$queryCompany=mysqli_query($connNew, $SQLCompany);
				$rowCompany = mysqli_num_rows($queryCompany);
				if($rowCompany==0){
				$InvoiceText ='Invalid Company Name';
				$InvoiceStatusCompany='1';//
				$InvoiceStatusSet='1';
				$color='red';
				}else{ $InvoiceStatusCompany=='' ;}
	
				$SQLHotel	= "
				select *  from ".TBL_HOTELS." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !='' AND name LIKE '{$Invoice['Hotel Name']}%'  and city='".$Invoice['Hotel City']."'
				";
				
				
				$queryHotel=mysqli_query($connNew, $SQLHotel);
				$rowHotel = mysqli_num_rows($queryHotel);
				if($rowHotel==0){
				$InvoiceText ='Invalid Hotel Name';
				$InvoiceStatusHotel='1';//
				$InvoiceStatusSet='1';
				$color='red';
				}
				$InvoiceText1='';
				if($InvoiceStatusInvoiceNo=='1' || $InvoiceStatusCompany=='1' || $InvoiceStatusHotel=='1'){
					$InvoiceStatusSet='1';
							if($InvoiceStatusInvoiceNo=='1'){
								$InvoiceText1 .=' <br>Invoice No Already Exist';						
							}else{$InvoiceText1=='';}
							if($InvoiceStatusCompany=='1'){
								$InvoiceText1 .=' <br> Invalid Company Name';
							}else{$InvoiceText1=='';}
							if($InvoiceStatusHotel=='1'){
								$InvoiceText1 .=' <br> Invalid Hotel Name';
							}else{$InvoiceText1=='';}
					}else{
						$InvoiceText1='';
						
						}
				
				$InvoiceTextCheckNull='';			
	if($Invoice['S:NO']==''){
		$InvoiceTextCheckNull .=' <br> S.No is Null';
		}
	if($Invoice['Amount']==''){
		$InvoiceTextCheckNull .=' <br> Amount is Null';
		}
	if($Invoice['Source']==''){
		$InvoiceTextCheckNull .=' <br> Company Name is Null';
		}
	if($Invoice['Advance']==''){
		//$InvoiceTextCheckNull .=' <br>Advance is Null';
		}
	if($Invoice['Balance']==''){
		$InvoiceTextCheckNull .=' <br> Balance is Null';
		}
	if($Invoice['Checkin']==''){
		$InvoiceTextCheckNull .=' <br> Checkin Name is Null';
		}
	if($Invoice['Checkout']==''){
		$InvoiceTextCheckNull .=' <br> Checkout is Null';
		}
	if($Invoice['Due Date']==''){
		$InvoiceTextCheckNull .=' <br> Due Date is Null';
		}
	if($Invoice['Guest Name']==''){
		$InvoiceTextCheckNull .=' <br> Guest Name is Null';
		}
		
		
		
	if($Invoice['Hotel Name']==''){
		$InvoiceTextCheckNull .=' <br> Hotel Name is Null';
		}
	if($Invoice['Accounts No']==''){
		$InvoiceTextCheckNull .=' <br> Accounts No is Null';
		}
	if($Invoice['Invoice Date']==''){
		$InvoiceTextCheckNull .=' <br> Invoice Date is Null';
		}
		
		if($Invoice['Accounts Head']==''){
		$InvoiceTextCheckNull .=' <br> Accounts Head is Null';
		}
		if($Invoice['Sales Manager']==''){
		$InvoiceTextCheckNull .=' <br> Sales Manager is Null';
		}
		if($Invoice['Accounts  Email Id']==''){
		$InvoiceTextCheckNull .=' <br> Accounts  Email Id is Null';
		}
				

						$list .='<tr>';						
							$list .='<td>'.$Invoice['S:NO'].'</td>'; 
							$list .='<td>'.$Invoice['Invoice No'].'</td>'; 
							$list .='<td>'.$Invoice['Amount'].'</td>'; 
							$list .='<td>'.$Invoice['Source'].'-'.$Invoice['Source City'].'</td>'; 
							$list .='<td>'.$Invoice['Advance'].'</td>'; 
							$list .='<td>'.$Invoice['Balance'].'</td>'; 
							$list .='<td>'.$Invoice['Checkin'].'</td>'; 
							$list .='<td>'.$Invoice['Checkout'].'</td>'; 
							$list .='<td>'.$Invoice['Due Date'].'</td>'; 
							$list .='<td>'.$Invoice['Guest Name'].'</td>'; 
							$list .='<td>'.$Invoice['Hotel Name'].' - '.$Invoice['Hotel City'].'</td>'; 
							 
							$list .='<td>'.$Invoice['Accounts No'].'</td>'; 
							$list .='<td>'.$Invoice['Invoice Date'].'</td>';  
							$list .='<td>'.$Invoice['Accounts Head'].'</td>';  
							$list .='<td>'.$Invoice['Sales Manager'].'</td>';
							$list .='<td>'.$Invoice['Accounts  Email Id'].'</td>'; 
							 
							$list .='<td style="color:'.$color.';">'.$InvoiceText1.' '.$InvoiceTextCheckNull.'</td>';
							$list .='</tr>';
					}
		
				}
			   
			                 
				
                $listarray['content'].='<div class="box-body table-responsive">
              <table id="" class="table table-bordered table-striped">
                <thead>
                  <tr>
					<th>S:NO</th>
					<th>Invoice No</th> 
					<th>Amount</th>
					<th>Source</th>
					<th>Advance</th>
					<th>Balance</th>
					<th>Checkin</th>
					<th>Checkout</th>
					<th>Due Date</th>
					<th>Guest Name</th>
					<th>Hotel Name</th>
					
					<th>Accounts No</th>
					<th>Invoice Date</th> 
					<th>Accounts Head</th> 
					<th>Sales Manager</th>
					<th>Accounts  Email Id</th>
					
					<th style="width:220px !important; float:left;height:57px;">Validate</th>
					</tr>
                </thead>
                
                <tbody>
                '.$list.'
				  </tbody>
               
                
              </table>
            </div>'; 
			
			
			
			$listarray['status']=$InvoiceStatusSet;
			echo json_encode($listarray);
?>