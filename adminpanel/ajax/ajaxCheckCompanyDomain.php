<?php include_once("../../config/auto_loader.php");
	

	if(isset($_REQUEST['id_domain'])){

		$sql = "SELECT * FROM `fs_company` WHERE id_default_group = ".$_REQUEST['id_domain']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_hotel'])){

		$sql = "SELECT * FROM `fs_orders` WHERE id_hotel = ".$_REQUEST['id_hotel']." ";

		$res = executeSql($sql);

		$num = num_rows($res);



		$sql1 = "SELECT * FROM `fs_assign_hotel_room` WHERE hotel_id = ".$_REQUEST['id_hotel']." ";

		$res1 = executeSql($sql1);

		$num1 = num_rows($res1);



		if($num > 0 || $num1 > 0){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_hotel_cat'])){

		$sql = "SELECT * FROM `fs_hotels` WHERE hotel_category = ".$_REQUEST['id_hotel_cat']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0 ){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_room_type'])){

		$sql = "SELECT * FROM `fs_room_mapping` WHERE room_id = ".$_REQUEST['id_room_type']." ";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0 ){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_gen_ser'])){

		$sql = "SELECT * FROM `fs_hotels` WHERE hotel_services = ".$_REQUEST['id_gen_ser']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0 ){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}





	if(isset($_REQUEST['id_company'])){

			$sql = "SELECT * FROM ".TBL_ORDERS." WHERE id_company = ".$_REQUEST['id_company']." AND id_shop = ".$_SESSION['shop']."";
			
			$sql2 = "SELECT * FROM ".TBL_CUSTOMER." WHERE id_company = ".$_REQUEST['id_company']." ";

			$sql3 = "SELECT * FROM ".TBL_RATE." WHERE company_id = ".$_REQUEST['id_company']." ";

			$sql4 = "SELECT * FROM ".TBL_VISIT." WHERE id_company = ".$_REQUEST['id_company']." ";

			$res = executeSql($sql);
			$res2 = executeSql($sql2);
			$res3 = executeSql($sql3);
			$res4 = executeSql($sql4);

			$num = num_rows($res);
			$num2 = num_rows($res2);
			$num3 = num_rows($res3);
			$num4 = num_rows($res4);
			$chk = $num+$num2+$num3+$num4;
			if($chk > 0  ){

				echo json_encode(1);

			}
			else{

				echo json_encode(0);

			}
			exit;
	}



	if(isset($_REQUEST['id_customer'])){

			$sql = "SELECT * FROM ".TBL_ORDERS." WHERE id_customer = ".$_REQUEST['id_customer']." AND id_shop = ".$_SESSION['shop']."";
			$sql2 = "SELECT * FROM ".TBL_VISIT." WHERE id_contacts = ".$_REQUEST['id_customer']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);
			$res2 = executeSql($sql2);
			$num = num_rows($res);
			$num2 = num_rows($res2);
			$chk=$num+$num2;
			if($chk > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_cmp_grp'])){

		$sql = "SELECT * FROM `fs_company` WHERE id_default_group = ".$_REQUEST['id_cmp_grp']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_cmp_area'])){

		$sql = "SELECT * FROM `fs_company` WHERE area = ".$_REQUEST['id_cmp_area']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_guest'])){

			$sql = "SELECT * FROM `fs_customer_live` WHERE id_customer = ".$_REQUEST['id_guest']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate'])){

			$sql = "SELECT * FROM `fs_orders` WHERE id_rate = ".$_REQUEST['id_rate']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_letter'])){

			$sql = "SELECT * FROM ".TBL_ORDERS." WHERE id_rate = ".$_REQUEST['id_rate_letter']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode($sql);

			}

			else{

				echo json_encode($sql);

			}

	}



	if(isset($_REQUEST['id_rate_level'])){

			$sql = "SELECT * FROM `fs_rate` WHERE rate_level_id = ".$_REQUEST['id_rate_level']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_plan'])){

			$sql = "SELECT * FROM `fs_rate_details` WHERE rate_plan_id = ".$_REQUEST['id_rate_plan']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_sea'])){

			$sql = "SELECT * FROM `fs_rate` WHERE seasonId = ".$_REQUEST['id_rate_sea']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_mkt'])){

			$sql = "SELECT * FROM `fs_rate` WHERE market = ".$_REQUEST['id_rate_mkt']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_pnt'])){

			$sql = "SELECT * FROM `fs_rate` WHERE rate_points = ".$_REQUEST['id_rate_pnt']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_gen_term'])){

			$sql = "SELECT * FROM `fs_rate` WHERE generalterms = ".$_REQUEST['id_gen_term']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_bgt_yr'])){

			$sql = "SELECT * FROM `fs_budget_master` WHERE seasonId = ".$_REQUEST['id_bgt_yr']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_segment'])){

			$sql = "SELECT * FROM `fs_orders` WHERE segment_id = ".$_REQUEST['id_segment']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_series'])){

			$sql = "SELECT * FROM `fs_orders` WHERE series_id = ".$_REQUEST['id_series']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_operator'])){

			$sql = "SELECT * FROM `fs_orders` WHERE operator_id = ".$_REQUEST['id_operator']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}


	if(isset($_REQUEST['id_main_group'])){

			$sql = "SELECT * FROM ".TBL." WHERE id = ".$_REQUEST['id_main_group']." AND `id_shop` = '".addslashes($_SESSION['shop'])."'  and `table_name` = 'item_group_main' ";

			$res = executeSql($sql);

			$num = num_rows($res);

		

				echo json_encode(2);

			

	}


	if(isset($_REQUEST['id_cancel'])){

			$sql = "SELECT * FROM `fs_orders` WHERE cancellation_reason_id = ".$_REQUEST['id_cancel']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_amd'])){

			$sql = "SELECT * FROM `fs_orders` WHERE amendment_remarks_id = ".$_REQUEST['id_amd']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_shop'])){

			$sql = "SELECT * FROM `fs_orders` WHERE  id_shop = ".$_REQUEST['id_shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_user_lvl'])){

			$sql = "SELECT * FROM `fs_users` WHERE user_level = ".$_REQUEST['id_user_lvl']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}

	

	if(isset($_REQUEST['Evoucher_id'])){

			$sql = "SELECT * FROM ".TBL_PROMO_CODE_DETAILS." WHERE promo_code_id = ".$_REQUEST['Evoucher_id']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_user'])){

			$sql = "SELECT * FROM `fs_areas_assign` WHERE user_id = ".$_REQUEST['id_user']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);



			$sql1 = "SELECT * FROM `fs_orders` WHERE last_modified_by = ".$_REQUEST['id_user']." AND id_shop = ".$_SESSION['shop']."";

			$res1 = executeSql($sql1);

			$num1 = num_rows($res1);



			if($num > 0 AND $num1 > 0){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



?>