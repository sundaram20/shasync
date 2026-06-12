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
			// INSERTING REQUEST
			$sqlReq = "INSERT INTO sales_api_requests SET data='".$json."',type=1,send_at='".date('Y-m-d H:i:s')."' ";

			mysqli_query($conn, $sqlReq);

			// USER VERIFICATION
			$userSql = "SELECT id,id_shop,geo_location_interval,dsr_num_days  FROM fs_users WHERE username='".$data->auth->username."' AND password='".base64_encode($data->auth->password)."' AND status=1 ";
			
			$resUser = mysqli_query($conn,$userSql);

			if(mysqli_num_rows($resUser) > 0 ){
				$objUser = mysqli_fetch_object($resUser);
				$type = '';

				if(strtoupper(trim($data->req_type->type))=='LOGIN')
					$type=1;
				else if(strtoupper(trim($data->req_type->type))=='LOGOUT')
					$type=2;
				else
					$type=3;
				
				$locSql = "INSERT INTO sales_executive_locations SET id_user='".$objUser->id."',id_shop='".$objUser->id_shop."',longitude='".$data->position->longitude."',latitude='".$data->position->latitude."',location='".htmlentities($data->position->location)."',type=".$type.",created_at='".date('Y-m-d H:i:s')."' ";

				if(mysqli_query($conn, $locSql)){
					$arrData = array();
					$arrData['location_interval'] =$objUser->geo_location_interval;
					$arrData['back_date_allowed'] =$objUser->dsr_num_days;


					$res = array('type' =>'success','msg'=>'location updated successfully.', 'data'=>$arrData);
					
					$rep = "INSERT INTO sales_api_requests SET data='".json_encode($res)."',type=2,send_at='".date('Y-m-d H:i:s')."' ";
					
					mysqli_query($conn, $rep);

					echo json_encode($res);
				}
				else{
					$res = array('type' =>'error','msg'=>'Failed To update location.');
					echo json_encode($res);
				}
			}
			else{
				$res = array('type' =>'error','msg'=>'User Authentication Failed.');
				echo json_encode($res);
			}
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

}else{
	$res = array('type' =>'error','msg'=>'Unable To Connect Application.');
	echo json_encode($res);
}

mysqli_close($conn);

