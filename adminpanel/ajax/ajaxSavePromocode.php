<?php include_once("../../config/auto_loader.php");

	


	  



	 if(!empty($_REQUEST['eId'])){

		

		

		$promo_code_edit	= $_POST['promo_code_edit'];

		$date_valid_from	 = $_POST['date_valid_from'];

		 $date_valid_to 		 = $_POST['date_valid_to'];

		

		 

		  $editSql = "   	UPDATE `".TBL_PROMO_CODE_DETAILS."` SET 

													`vaoucher_value` = '".addslashes($_POST['vaoucher_value'])."',
													`food_value` = '".addslashes($_POST['food_value'])."',	
													`status` = '".addslashes($_POST['status'])."',	
													`employee_name` = '".addslashes($_POST['employee_name'])."',
													`emp_title` = '".addslashes($_POST['emp_title'])."',
													`emp_id` = '".addslashes($_POST['emp_id'])."',	
													`serial_no` = '".addslashes($_POST['serial_no'])."',										

													`date_valid_from` = '".addslashes(date('Y-m-d',strtotime($date_valid_from)))."',	

													`date_valid_to` = '".addslashes(date('Y-m-d',strtotime($date_valid_to)))."'													

													,`last_modified` = '".currenDateTime()."'

													,`last_modified_by` = '".$_SESSION['userId']."'

												WHERE `id` =  ".addslashes(encryptor('decrypt',$_REQUEST['eId']))."	";

																
			executeSql($editSql);

			//echo $editSql;

			

		echo '<p class="help-block"> Evoucher has been updated Sucessfully.</p><script>window.setTimeout(function() { window.location.href = "manageEvoucher.php?action=edit&page=1";}, 2000); </script>';

	 }else{

		

		$no_of_coupons = $_POST['no_of_coupons'];

		$length = $_POST['length'];

		$prefix = $_POST['prefix'];

		$suffix = $_POST['suffix'];

		$numbers = $_POST['numbers'];

		$letters = $_POST['letters'];

		$symbols = $_POST['symbols'];

		$random_register = $_POST['random_register'] == 'false' ? false : true;

		$mask = $_POST['mask'] == '' ? false : $_POST['mask'];

		

		

		$date_valid_from	 = $_POST['date_valid_from'];

		$date_valid_to 		 = $_POST['date_valid_to'];

		

						$addpromocode = "  INSERT INTO `".TBL_PROMO_CODE."` SET 

													`id_shop` = '".addslashes($_SESSION['shop'])."',	

													`company_id` = '".addslashes($_POST['id_company'])."',	

													`scheme_type` = '".addslashes($_POST['scheme_type'])."',	

													`vaoucher_value`= '".addslashes($_POST['vaoucher_value'])."',
													`food_value`= '".addslashes($_POST['food_value'])."',

													`no_of_coupons`= '".addslashes($_POST['no_of_coupons'])."',	

													`date_valid_from` = '".addslashes(date('Y-m-d',strtotime($date_valid_from)))."',	

													`date_valid_to` = '".addslashes(date('Y-m-d',strtotime($date_valid_to)))."',	

													`date_created` = '".currenDateTime()."'

													,`last_modified` = '".currenDateTime()."'

													,`last_modified_by` = '".$_SESSION['userId']."'

													,`status` = '".addslashes($_POST['status'])."'";

								executeSql($addpromocode);

								$promocodeAssignId = $db->insert_id();

								

								

		

    function generate_coupons($maxNumberOfCoupons = 1, $length,$numbers) {

        $coupons = array();

		

		

        for ($i = 0; $i < $maxNumberOfCoupons; $i++) {

       



            $temp = generate($options,$numbers,$length);

            $coupons[] = $temp;

        }

        return $coupons;

    }

	function generate($options = array(), $numbers2,$lengthss) {

 

        $length         = (isset($lengthss) ? filter_var($lengthss, FILTER_VALIDATE_INT) : 5 );

        $prefix         = (isset($options['prefix']) ? self::cleanString(filter_var($options['prefix'], FILTER_SANITIZE_STRING)) : '' );

        $suffix         = (isset($options['suffix']) ? self::cleanString(filter_var($options['suffix'], FILTER_SANITIZE_STRING)) : '' );

        $useLetters     = (isset($options['letters']) ? filter_var($options['letters'], FILTER_VALIDATE_BOOLEAN) : true );

		

	    $useNumbers     = (isset($numbers2) ? filter_var($numbers2, FILTER_VALIDATE_INT) : 0 );

		

        $useSymbols     = (isset($options['symbols']) ? filter_var($options['symbols'], FILTER_VALIDATE_BOOLEAN) : false );

        $useMixedCase   = (isset($options['mixed_case']) ? filter_var($options['mixed_case'], FILTER_VALIDATE_BOOLEAN) : false );

        $mask           = (isset($options['mask']) ? filter_var($options['mask'], FILTER_SANITIZE_STRING) : false );



        $uppercase    = array('Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M');

        $lowercase    = array('q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm');

        $numbers      = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);

        $symbols      = array('`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '\\', '|', '/', '[', ']', '{', '}', '"', "'", ';', ':', '<', '>', ',', '.', '?');



        $characters   = array();

        $coupon = '';



        if ($useLetters) {

            if ($useMixedCase) {

                $characters = array_merge($characters, $lowercase, $uppercase);

            } else {

                $characters = array_merge($characters, $uppercase);

            }

        }



        if ($useNumbers) {

			

            $characters = array_merge($characters, $numbers);

        }



        if ($useSymbols) {

            $characters = array_merge($characters, $symbols);

        }



        if ($mask) {

            for ($i = 0; $i < strlen($mask); $i++) {

                if ($mask[$i] === 'X') {

                    $coupon .= $characters[mt_rand(0, count($characters) - 1)];

                } else {

                    $coupon .= $mask[$i];

                }

            }

        } else {

            for ($i = 0; $i < $length; $i++) {

                $coupon .= $characters[mt_rand(0, count($characters) - 1)];

            }

        }



        return $prefix . $coupon . $suffix;

    }

    

    

		$coupons = generate_coupons($no_of_coupons,$length, $numbers, $prefix, $suffix,  $letters, $symbols, $random_register, $mask);

		

		$coupons2 = generate_coupons($no_of_coupons, $length, $prefix, $suffix, $numbers2, $letters, $symbols, $random_register, $mask);

		//$coupons2 = coupon::generate_coupons($no_of_coupons, $length, $prefix, $suffix, $numbers2, $letters, $symbols, $random_register, $mask);

		$promo_code_order_id=1;

		$coupons2_count=0;

		foreach ($coupons as $key => $value) {

			//echo "<pre>";print_r($coupons2[$coupons2_count]);			echo "</pre>";

			

			//die;

				$addpromocodedetails = "  INSERT INTO `".TBL_PROMO_CODE_DETAILS."` SET 

													`promo_code_id` = '".addslashes($promocodeAssignId)."',	

													`id_shop` = '".addslashes($_SESSION['shop'])."',	

													`promo_code_order_id` = '".addslashes($promo_code_order_id)."',

													`promo_code` = '".addslashes($value)."',

													`pass_code` = '".addslashes($coupons2[$coupons2_count])."',

													`vaoucher_value` = '".addslashes($_POST['vaoucher_value'])."',
													`food_value` = '".addslashes($_POST['food_value'])."',

													`company_id` = '".addslashes($_POST['id_company'])."',

													`date_valid_from` = '".addslashes(date('Y-m-d',strtotime($date_valid_from)))."',	

													`date_valid_to` = '".addslashes(date('Y-m-d',strtotime($date_valid_to)))."',

													`promo_code_status` = '1',

													`url` = '".addslashes($_POST['url'])."',	

													`date_created` = '".currenDateTime()."'

													,`last_modified` = '".currenDateTime()."'

													,`last_modified_by` = '".$_SESSION['userId']."'

													,`status` = '1'";

								executeSql($addpromocodedetails);

								$addpromocodedetails = $db->insert_id();

			

			//echo "  ".$value."\n ";

			$promo_code_order_id++;

			$coupons2_count++;

		}



		echo '<p class="help-block">'.$no_of_coupons.' coupons has been Created Sucessfully.</p><script>window.setTimeout(function() { window.location.href = "manageEvoucher.php?action=edit&page=1";}, 2000); </script>';

		

	 }

	

  

    

   



    





?>

