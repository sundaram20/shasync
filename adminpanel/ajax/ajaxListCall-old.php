<?php  include_once("../../config/auto_loader.php");

$Sql = "SELECT * FROM `call_request` where   `id` = '".addslashes($_REQUEST['id_call_request'])."' AND status='0'  ";	
$row = mysqli_fetch_object(mysqli_query($connNew,$Sql));
$row->json_data;
$Records=array();
$Records['Call']	=json_decode($row->json_data,true);	
$listarray=array();
//$arrayList ='<tbody>';
				
				foreach($Records as $DataList){
					foreach($DataList as $Call){
						//$CallStatusCallNo='';$CallStatusCompany='' ;$CallStatusHotel='';
					/* $SqlCall = "SELECT * FROM `call` where   `name` = '".addslashes($Call['Name'])."' AND  `mobile` = '".addslashes($Call['Mobile'])."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ";	
					 $rowCall = mysqli_num_rows(mysqli_query($connNew,$SqlCall));
						 
					 if($rowCall=='0'){
						
						 }else{
							 $CallText ='Call Details Already Exist';
						// $InvoiceStatusInvoiceNo='1';//
						// $InvoiceStatusSet='1';
						  $color='red';
							  
							 }
						 */

						 $color = 'red';
					
						$CallStatusSet='0';
				$CallTextCheckNull='';			
	if($Call['S:NO']==''){
		$CallTextCheckNull .=' <br> S.No is Null';
		}
	if($Call['Mobile']==''){
		$CallTextCheckNull .=' <br> Mobile No is Null';
		}
	if($Call['Name']==''){
		$CallTextCheckNull .=' <br> Name is Null';
		}

		
				

						$list .='<tr>';						
							$list .='<td>'.$Call['S:NO'].'</td>'; 
							$list .='<td>'.$Call['Mobile'].'</td>'; 
							$list .='<td>'.$Call['Name'].'</td>'; 
							$list .='<td style="color:'.$color.';">'.$CallText1.' '.$CallTextCheckNull.'</td>';

							 
						$list .='</tr>';
					}
		
				}
			   
			                 
				
                $listarray['content'].='<div class="box-body table-responsive">
              <table id="" class="table table-bordered table-striped">
                <thead>
                  <tr>
					<th>S:NO</th>
					<th>Mobile</th> 
					<th>Name</th>
	
					
					<th style="width:220px !important; float:left;height:57px;">Validate</th>
					</tr>
                </thead>
                
                <tbody>
                '.$list.'
				  </tbody>
               
                
              </table>
            </div>'; 
			
			
			
			$listarray['status']=$CallStatusSet;
			echo json_encode($listarray);
?>