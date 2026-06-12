<?php
require('api-config.php');

if($conn){
	
	$json = file_get_contents('php://input');
	$data = json_decode($json);

	$sqlApp = "SELECT `database` FROM app_shops WHERE shop_code='".$data->auth->code."' ";
	$resApp = mysqli_query($conn, $sqlApp);
	
	if(mysqli_num_rows($resApp) > 0){
		$database = mysqli_fetch_object($resApp)->database;

		$conn  = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$database);
		if($conn){
			restCalls();
		}
		else{
			$res = array('type' =>'error','msg'=>'Unable To Connect Corporate.');
			echo json_encode($res);
		}
	}
	else{
		$res = array('type' =>'error','msg'=>'Please Check The Corporate Code.');
		echo json_encode($res);
	}
}
else{
	$res = array('type' =>'error','msg'=>'Unable To Connect Application.');
	echo json_encode($res);
}

function listData($id_user, $id_shop){
	global $conn;
	
	$response['assignUsers']=$response['travelModes']= $response['designations'] = $response['hotels'] = $response['companies'] = array();
	
	$hotelList = array();
	$companyList = array();
	$designationList= array();
	$travelModeList = array();
	$assignUser = array();

	/*************** Hotel List **************/
	$hotSql = "SELECT id,CONCAT(name,'-',city) AS name FROM fs_hotels WHERE id_shop='".$id_shop."' AND status=1 Order By name";
	$hotRes = mysqli_query($conn,$hotSql);

	while($hotRow = mysqli_fetch_object($hotRes)){
		$hotList['id']=$hotRow->id;
		$hotList['name']=$hotRow->name;
		array_push($response['hotels'], $hotList);	
	}

	/*************** Company List **************/
	$comSql = "SELECT id_company,CONCAT(name,'-',city) AS name FROM fs_company WHERE id_shop='".$id_shop."' AND status=1 AND name !='' Order By name";
	$comRes = mysqli_query($conn,$comSql);

	while($comRow = mysqli_fetch_object($comRes)){
		$comList['id']=$comRow->id_company;
		$comList['name']=$comRow->name;
		array_push($response['companies'], $comList);	
	}

	/*************** Designation List **************/
	$desSql = "SELECT id,name FROM fs_designation_master WHERE id_shop='".$id_shop."' AND status=1  Order By name";
	$desRes = mysqli_query($conn,$desSql);

	
	while($desRow = mysqli_fetch_object($desRes)){
		$desList['id']=$desRow->id;
		$desList['name']=$desRow->name;
		array_push($response['designations'], $desList);	
	}

	/*************** Travel Mode List **************/
	$traSql = "SELECT id,name FROM mst_travel_modes WHERE id_shop='".$id_shop."' AND status=1  Order By name";
	$traRes = mysqli_query($conn,$traSql);

	while($traRow = mysqli_fetch_object($traRes)){
		$traList['id']=$traRow->id;
		$traList['name']=$traRow->name;
		array_push($response['travelModes'], $traList);	
	}

	/*************** Assign Users List **************/
	$userSql = "SELECT A.id,CONCAT(A.name,'-',B.name) AS name FROM fs_users AS A LEFT JOIN mst_team AS B ON B.id=A.ids_team WHERE A.id_shop='".$id_shop."' AND user_level!=1 AND A.status=1 AND user_type!=2  ORDER BY  name";
	
	$userRes = mysqli_query($conn,$userSql);

	while($userRow = mysqli_fetch_object($userRes)){
		$userList['id']=$userRow->id;
		$userList['name']=$userRow->name;
		array_push($response['assignUsers'], $userList);	
	}

	echo json_encode($response);
}

function restCalls(){
	global $conn;
	global $json;
	global $data;
	
	if(strtoupper($data->req_type->type)=='LIST'){
		$type=3;
	}
	else if(strtoupper($data->req_type->type)=="GET"){
		$type=8;
	} 
	else if(strtoupper($data->req_type->type)=="VISIT"){
		$type=5;	
	}
	else if(strtoupper($data->req_type->type)=="LEAD"){
		$type=7;
	} 
	else if(strtoupper($data->req_type->type)=="PUT"){
		$type=10;
	}  
	else{
		$res = array('type' =>'error','msg'=>'Invalid Request');
		echo json_encode($res);
		exit;	
	}  

	// INSERTING REQUEST
	$sqlReq = "INSERT INTO sales_api_requests SET data='".$json."',type='".$type."',send_at='".date('Y-m-d H:i:s')."' ";

	mysqli_query($conn, $sqlReq);

	// USER VERIFICATION
	$userSql = "SELECT id,id_shop FROM fs_users WHERE username='".$data->auth->username."' AND password='".base64_encode($data->auth->password)."' AND status=1 ";
	
	$resUser = mysqli_query($conn,$userSql);

	if(mysqli_num_rows($resUser) > 0 ){
		$objUser = mysqli_fetch_object($resUser);
		
		switch($type){
			case '3' : 
				listData($objUser->id, $objUser->id_shop);
				break;
			case '5' : 
				postVisit($data->data, $objUser);
				break;	
			case '7' : 
				postLead($data->data, $objUser);
				break;
			case '8' :
				getContacts($data->data->id_company);
				break;
			case '10' :
				putContacts($data->data, $objUser);
				break;		
			default:
				$res = array('type' =>'error','msg'=>'Invalid Request');
				echo json_encode($res);
		}
	}
	else{
		$res = array('type' =>'error','msg'=>'User Authentication Failed.');
		echo json_encode($res);
	}

}



function getContacts($id_company){
	global $conn;
	
	$comSql = "SELECT id_customer, CONCAT(title,' ',first_name,' ',last_name) AS name, email, mobile FROM fs_customer WHERE id_company='".$id_company."' AND status=1 ORDER BY first_name ";

	$comRes = mysqli_query($conn, $comSql);
	$response['data'] = array();

	while($comRow = mysqli_fetch_object($comRes)){
		$cust['id'] = $comRow->id_customer;
		$cust['name'] = $comRow->name;
		$cust['email'] = $comRow->email;
		$cust['mobile'] = $comRow->mobile;

		array_push($response['data'], $cust);
	}

	echo json_encode($response);
}

function putContacts($data, $objUser){
	global $conn;
	$response['data'] = array();

	$sql = "INSERT INTO fs_customer SET id_customer='".$data->id_customer."', title='".$data->title."',first_name='".$data->first_name."',last_name='".$data->last_name."',designation='".$data->id_designation."',email='".$data->email."', mobile='".$data->mobile."', type=2, id_company='".$data->id_company."',last_modified_by='".$objUser->id."',id_shop='".$objUser->id_shop."',status=1,date_created='".date('Y-m-d')."'  ";
	
	if(mysqli_query($conn, $sql)){

		$sqlCon = "SELECT id_customer, CONCAT(title,' ',first_name,' ',last_name) AS name, email, mobile FROM fs_customer WHERE id_customer='".mysqli_insert_id($conn)."' ";
		$resCon = mysqli_query($conn, $sqlCon);

		$comRow = mysqli_fetch_object($resCon);

		$cust['id'] = $comRow->id_customer;
		$cust['name'] = $comRow->name;
		$cust['email'] = $comRow->email;
		$cust['mobile'] = $comRow->mobile;
		
		$response['type'] = 'success';
		$response['msg'] = 'Contact Created Successfully.';

		$response['data'] =  $cust ;
		echo json_encode($response);

	}
	else{
		$res = array('type' =>'error','msg'=>'Failed To Insert Data.');
		echo json_encode($res);
	}


}

function postVisit($data, $objUser){
	global $conn;
	
	
	/******* Marking The Company **********/
	$areaSql = "SELECT id FROM fs_areas_assign WHERE user_id='".$objUser->id."' AND status=1";

	$areaRes = mysqli_query($conn, $areaSql);
	$areas = array();
	while($areaRow = mysqli_fetch_object($areaRes)){
		array_push($areas, $areaRow->id);
	}

	$chkSQl = 'SELECT id_company FROM  fs_company WHERE id_company="'.$data->id_company.'" AND FIND_IN_SET(area,"'.implode(',', $areas).'") ';

	$resChk = mysqli_query($conn,$chkSQl);
		
	if(mysqli_num_rows($resChk)==0){
		$markedCompany = 1;
	}
	else{
		$markedCompany = 0;
	}
	/********* Marking Company End ********/

	$sql = "INSERT INTO fs_visit set id_shop='".$objUser->id_shop."',id_company='".$data->id_company."',id_contacts='".$data->id_customer."',id_user='".$objUser->id."',dated='".date('Y-m-d',strtotime($data->visit_date))."',discussion_summary='".$data->summary."',StatFrom='".$data->area_covered."',KmsRun='".$data->kms_run."',RateKm='".$data->rate_per_km."',Total='".$data->total."',Parking='".$data->parking."',lunch='".$data->lunch."',entertainment='".$data->entertainment."',status=1,date_created='".date('Y-m-d H:i:s')."',last_modified='".date('Y-m-d H:i:s')."',id_travel_mode='".$data->id_travel_mode."',company_marked='".$markedCompany."' ";


	if($data->id_company==0 || $data->id_company==''){
		$res = array('type' =>'error','msg'=>'Company Can\'t be blank.');
		echo json_encode($res);
	}
	else if($data->id_customer==0 || $data->id_customer==''){
		$res = array('type' =>'error','msg'=>'Customer Can\'t be blank.');
		echo json_encode($res);
	}
	else{
		if(mysqli_query($conn, $sql)){

			$id_visit = mysqli_insert_id($conn);

			$sqlCal = "INSERT INTO fs_daily_calender SET 
					  `visit_id`='".$id_visit."',						  
					  `id_shop` = '".$objUser->id_shop."',
					  `type` = '2',				 
					  `id_user` = '".$objUser->id."',
					  `assign_user_id`='".$objUser->id."',	
					  `dated`='".date('Y-m-d',strtotime($data->visit_date))."',			  
					  `status` = '1'"; 

			if(mysqli_query($conn, $sqlCal)){		  
				$res = array('type' =>'success','msg'=>'Visit Created Successfully.');
				echo json_encode($res);
			}
			else{
				$res = array('type' =>'error','msg'=>'Failed To Created Visit.');
				echo json_encode($res);
			}	
		}
		else{
			$res = array('type' =>'error','msg'=>'Failed To Created Visit.');
			echo json_encode($res);
		}
	}
}

function postLead($data, $objUser){
	global $conn;

	$sql ="INSERT INTO fs_enquiry SET 							
							`id_shop` = '".$objUser->id_shop."',
							`id_company` = '".$data->id_company."',	
							`hotel_id` = '".$data->id_hotel."',	
							`id_contact`='".$data->id_customer."',
							 `id_user` = '".$objUser->id."',	
							`status` = '1',
							`assign_user_id`='".$data->id_assign_user."',
							`type` = '4',
							`details` = '".$data->summary."',
							`created_date`='".date('Y-m-d')."',	
							`dated`='".date('Y-m-d',strtotime($data->lead_date))."',
							`follow_up_summary`='".$data->followup_summary."',
							`follow_up_date`='".date('Y-m-d',strtotime($data->followup_date))."',
							`created_by`='".$objUser->id."',
							`date_created`='".date('Y-m-d H:i:s')."',
							`modified_by`='".$objUser->id."',
							`date_modified`='".date('Y-m-d H:i:s')."',
							`lead_status` = '1'
							";

	if($data->id_company==0 || $data->id_company==''){
		$res = array('type' =>'error','msg'=>'Company Can\'t be blank.');
		echo json_encode($res);
	}
	else if($data->id_customer==0 || $data->id_customer==''){
		$res = array('type' =>'error','msg'=>'Customer Can\'t be blank.');
		echo json_encode($res);
	}
	else if($data->id_customer==0 || $data->id_hotel==''){
		$res = array('type' =>'error','msg'=>'Hotel Can\'t be blank.');
		echo json_encode($res);
	}
	else if($data->id_customer==0 || $data->id_hotel==''){
		$res = array('type' =>'error','msg'=>'Hotel Can\'t be blank.');
		echo json_encode($res);
	}
	else{
		if(mysqli_query($conn, $sql)){

			$id_enquiry = mysqli_insert_id($conn);

			$sqlCal = "INSERT INTO fs_daily_calender SET 
				 		 		`type`='4',
								`id_shop` = '".$objUser->id_shop."',
								`id_user`='".$objUser->id."',
								`doc_id` ='0',
								`visit_id` ='".$id_enquiry."',
								`dated`='".addslashes(date('Y-m-d',strtotime($data->lead_date)))."',
								`status` = '1'"; 

			if(mysqli_query($conn, $sqlCal)){	

				 	$insertfollowup = "INSERT INTO fs_enquiry_details SET 
				 		`id_company` = '".$data->id_company."',
						`enquiry_id`='".$id_enquiry."',
						`id_shop` = '".$objUser->id_shop."',
						`hotel_id`='".$data->id_hotel."',	
						`id_contact`='".$data->id_customer."',
						`id_user`='".$objUser->id."',
						`created_date`='".date('Y-m-d')."',
						`details` = '".$data->followup_summary."',
						`assign_user_id` = '".$data->id_assign_user."',
						`created_by`='".$objUser->id."',
						`modified_by`='".$objUser->id."',
						`type` = '4',
						`dated`  = '".date('Y-m-d',strtotime($data->followup_date))."',
						`lead_status` = '1'";

					if(mysqli_query($conn, $insertfollowup)){
						$id_enquiry_detail = mysqli_insert_id($conn);

						$insertCalendar = "INSERT INTO fs_daily_calender SET 
				 		`enquiry_details`='1',
				 		`type`='4',
						`id_shop` = '".$objUser->id_shop."',
						`id_user`='".$objUser->id."',
						`assign_user_id` = '".$data->id_assign_user."',
						`doc_id` ='".$id_enquiry_detail."',
						`visit_id` ='".$id_enquiry."',
						`dated`='".date('Y-m-d',strtotime($data->followup_date))."',
						`status` = '1'";


						if(mysqli_query($conn, $insertCalendar)){

							/***************** SENDING MAIL *******************************/
							$infoTable="<table style='margin-bottom:20px;' border='1' cellspacing='0'>
							               <tr style='text-align:center;'><td colspan='2'><b>LEAD</b></td></tr> 
							                <tr ><td><b>Date of Origin of Lead</b></td><td>".$data->lead_date."</td></tr>
											<tr ><td><b>Hotel Name</b></td><td>".selectField('fs_hotels','name','WHERE id="'.$data->id_hotel.'" ').'-'.selectField('fs_hotels','city','WHERE id="'.$data->id_hotel.'" ')."</td></tr>
											<tr ><td><b>Company Name</b></td><td>".selectField('fs_company','name','WHERE id_company="'.$data->id_company.'" ')."</td></tr>
											<tr ><td><b>Person Name</b></td><td>".selectField('fs_customer','CONCAT(title," ",first_name," ",last_name)','WHERE id_customer="'.$data->id_customer.'" ')."</td></tr>
											<tr ><td><b>Contact Number</b></td><td>".selectField('fs_customer','mobile','WHERE id_customer="'.$data->id_customer.'" ')."</td></tr>
											<tr ><td><b>Email Id</b></td><td>".selectField('fs_customer','email','WHERE id_customer="'.$data->id_customer.'" ')."</td></tr>
											<tr ><td><b>Lead Details</b></td><td>".$data->summary."</td></tr>
											<tr ><td><b>Last Remarks</b></td><td>".$data->followup_summary."</td></tr>
											<tr ><td><b>Follow Up Date</b></td><td>".$data->followup->date."</td></tr>";
							$infoTable .="<tr ><td><b>Status</b></td><td>Open</td></tr>
										</table>";

							$mailSummary	=	"Kindly updated the status of the above lead in Sales Sync software to keep me posted. To update, <b>Go on the Follow up date in your dashboard, Click on Follow Up and thereafter click Open button in status & update. </b>";
							
							$id_designation = selectField('fs_users','designation','WHERE id="'.$objUser->id.'" ');

							$designation = selectField('fs_designation_master','name','WHERE id="'.$id_designation.'" ');

							$signature.="<table>
							              <tr>
							                  <td class='forTd' ><b>".ucwords(selectField('fs_users','name','WHERE id="'.$objUser->id.'" '))."</b></td>
							                  
							              </tr>
							              <tr>
							                <td class='forTd' ><b>".ucwords($designation)."</b><br></td>
							              </tr>
							          </table>";

							  
							


							$address1 =selectField('fs_users','address','WHERE id="'.$objUser->id.'" ');	
							$address2 =selectField('fs_users','address2','WHERE id="'.$objUser->id.'" ');
							$city =selectField('fs_users','city','WHERE id="'.$objUser->id.'" '); 
							$zip =selectField('fs_users','zip','WHERE id="'.$objUser->id.'" '); 
							$mobileCc =selectField('fs_users','mobile','WHERE id="'.$objUser->id.'" '); 
							$phone =selectField('fs_users','phone','WHERE id="'.$objUser->id.'" '); 
							$ccEmail =selectField('fs_users','email','WHERE id="'.$objUser->id.'" ');
							$ccName =selectField('fs_users','name','WHERE id="'.$objUser->id.'" ');

							$id_team = selectField('fs_users','ids_team','WHERE id="'.$objUser->id.'" ');
							$id_user_level_1 = selectField('mst_team','id_user_level_1','WHERE id="'.explode(',',$id_team )[0].'" ');

							$ccHeadEmailID = selectField('fs_users','email','WHERE id="'.$id_user_level_1.'" ');


							$signature .= "<table style='width : 100%;'>
							        <tr><td></td></tr>
							        </tr>
							            <td ><span style='color:green;font-weight:bold;'>".selectField('fs_users','company','WHERE id="'.$objUser->id.'" ')."</span>
										<span style='font-family:Georgia !imporant;font-size:9pt !imporant;'><br>".trim(selectField('fs_shop','formal_name','WHERE id="'.$objUser->id_shop.'" '))."<br/>
							                ".($address1!=''?$address1.', ':'').($address2!=''?$address2.' ':'').trim($city).'-'.$zip."<br>
							                M: ".$mobileCc." | T: ".$phone." | Email : ".$ccEmail."</span>               
							            </td>
							        </tr>
							      </table>";	


								$mailContent = "Dear ".selectField('fs_users','name','WHERE id="'.$data->id_assign_user.'" ').",<br/><br/>";
							
								$mailContent .=$infoTable."<br/><br/>";
								
								$mailContent .=$mailSummary; 
								
								$mailContent .="<br/><br/>Thanks & Regards<br/>".$signature;
								
								
								$headers=array();

								$headers[] = 'MIME-Version: 1.0';
								$headers[] = 'Content-type: text/html; charset=iso-8859-1';
							    
								$headers[] = 'From: RoomStatusHUB <support@roomstatushub.com>';
								$headers[] = 'Reply-To: '.$ccName.' <'.$ccEmail.'>';
						    
								$headers[] = 'Cc: <'.$ccEmail.'><'.$ccHeadEmailID.'>';     
								
								
								$to = selectField('fs_users','email','WHERE id="'.$data->id_assign_user.'" ');

								$mail = mail($to, 'Sales Sync - Lead', $mailContent, implode("\r\n", $headers));

								if($mail){
									$res = array('type' =>'success','msg'=>'Lead AND Mail Created Successfully.');
									echo json_encode($res);
								}
								else{
									$res = array('type' =>'error','msg'=>'Lead Created Successfully But Failed To Send Mail.');
									echo json_encode($res);
								}
							/***************** MAIL END *************************************/
							
						}
						else{
							$res = array('type' =>'error','msg'=>'Failed To Update Lead Followup Calender.');
							echo json_encode($res);
						}
					}	
					else{
						$res = array('type' =>'error','msg'=>'Failed To Update Lead Followup.');
						echo json_encode($res);
					}
			
			}
			else{
				$res = array('type' =>'error','msg'=>'Failed To Update Lead Calender.');
				echo json_encode($res);
			}	
		}
		else{
			$res = array('type' =>'error','msg'=>'Failed To Create Lead.');
			echo json_encode($res);
		}
	}
}

function selectField($table,$field, $cond){
	global $conn;

	$sql = "SELECT ".$field." FROM ".$table." ".$cond." ";

	$res = mysqli_query($conn, $sql);

	if(mysqli_num_rows($res)>0){
		return mysqli_fetch_object($res)->$field;
	}
	else{
		return "";	
	}

}


mysqli_close($conn);
?>