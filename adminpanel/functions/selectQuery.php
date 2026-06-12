<?php 
ob_start();
//----------------------------------------------------------------------------------
function local_SelectQuery_Mst_Company($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
	
				$ConnMstSql=array();

					if(@in_array('address', $field_name)){
						$namearra= array_search("address",$field_name,true);
						$ConnMstSql[$namearra] = "a.address AS '".$field_label[$namearra]."'";
					}

					if(@in_array('id_mst_portfolio_account', $field_name)){
						$namearra= array_search("id_mst_portfolio_account",$field_name,true);
						$ConnMstSql[$namearra] = "e.name AS '".$field_label[$namearra]."'";
					}


					if(@in_array('id_mst_attributes_company_group', $field_name)){
						$namearra= array_search("id_mst_attributes_company_group",$field_name,true);
						$ConnMstSql[$namearra] = "at.field_value AS '".$field_label[$namearra]."'";
					}

					if(@in_array('id_mst_state', $field_name)){						
						$namearra= array_search("id_mst_state",$field_name,true);
						$ConnMstSql[$namearra]= "CASE WHEN a.id_mst_state = 10000 THEN a.other_state ELSE f.name END AS '".$field_label[$namearra]."'";
					}

					if(@in_array('city', $field_name)){
						$namearra=array_search("city",$field_name,true);
						$ConnMstSql[$namearra]= "a.city AS '".$field_label[$namearra]."'";
					}
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";
					}
				
					if(@in_array('id_mst_country_lang', $field_name)){						
						$namearra= array_search("id_mst_country_lang",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.id_mst_country_lang = 10000 THEN a.other_country ELSE d.name END AS '".$field_label[$namearra]."'";
					}

					if(@in_array('postcode', $field_name)){
						$namearra= array_search("postcode",$field_name,true);
						$ConnMstSql[$namearra] = "a.postcode AS '".$field_label[$namearra]."'";

					}

					if(@in_array('primary_contact_type', $field_name)){
						$namearra= array_search("primary_contact_type",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.primary_contact_type = 1 THEN a.primary_mobile WHEN a.primary_contact_type = 2 THEN a.primary_landline END AS '".$field_label[$namearra]."'";
					}

					if(@in_array('email', $field_name)){
						$namearra= array_search("email",$field_name,true);
						$ConnMstSql[$namearra] = "a.email AS '".$field_label[$namearra]."'";
					}

					if(@in_array('company_credibility', $field_name)){
						$namearra= array_search("company_credibility",$field_name,true);						
						$ConnMstSql[$namearra] = "CASE WHEN a.company_credibility = 1 THEN 'Allowed' WHEN a.company_credibility = 2 THEN 'Not Allowed' END  As `Company Credibility` ";
					}
					
					ksort($ConnMstSql);
					
					$ConnMstSql	=	implode(",",$ConnMstSql);
					
					
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}
					if($_REQUEST['id_mst_portfolio_account'] != ''){
						$SearchConnSQL .= " AND a.`id_mst_portfolio_account` = '".addslashes($_REQUEST['id_mst_portfolio_account'])."%'";
					}
					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					if($_REQUEST['id_mst_attributes_company_group'] != ''){
						$SearchConnSQL .= " AND a.`id_mst_attributes_company_group` = '".addslashes($_REQUEST['id_mst_attributes_company_group'])."%'";
					}
					if($_REQUEST['email'] != ''){
						$SearchConnSQL .= " AND a.`email` LIKE '%".addslashes($_REQUEST['email'])."%' ";
					}
					if($_REQUEST['primary_contact_type'] != ''){
						$SearchConnSQL .= " AND a.`primary_mobile` LIKE '%".addslashes($_REQUEST['primary_contact_type'])."%' ";
						$SearchConnSQL .= " OR a.`primary_landline` LIKE '%".addslashes($_REQUEST['primary_contact_type'])."%' ";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status 
					
					FROM mst_company a 
					
					LEFT JOIN mst_country_lang d ON a.id_mst_country_lang = d.id_country 
					LEFT JOIN mst_state f ON a.id_mst_state = f.id_state 
					LEFT JOIN mst_portfolio_account e ON a.id_mst_portfolio_account = e.id 
					LEFT JOIN mst_attributes at ON a.id_mst_attributes_company_group = at.id 
					LEFT JOIN mst_users g ON e.id_mst_users_primary = g.id 
					LEFT JOIN mst_users h ON a.last_modified = h.id 
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' AND a.name!='' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
				    return $query;
					
	
		}


		function local_SelectQuery_Mst_Guest($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

					if(@in_array('address', $field_name)){
						$namearra= array_search("address",$field_name,true);
						$ConnMstSql[$namearra] = "a.address AS '".$field_label[$namearra]."'";
					}

					if(@in_array('id_mst_state', $field_name)){						
						$namearra= array_search("id_mst_state",$field_name,true);
						$ConnMstSql[$namearra]= "CASE WHEN a.id_mst_state = 10000 THEN a.other_state ELSE d.name END AS '".$field_label[$namearra]."'";
					}

					if(in_array('city', $field_name)){
						$namearra=array_search("city",$field_name,true);
						$ConnMstSql[$namearra]= "a.city AS '".$field_label[$namearra]."'";
					}
					if(in_array('guest_reg_no', $field_name)){
						$namearra=array_search("guest_reg_no",$field_name,true);
						$ConnMstSql[$namearra]= "a.guest_reg_no AS '".$field_label[$namearra]."'";
					}
					if(in_array('first_name', $field_name)){						
						$namearra= array_search("first_name",$field_name,true);
						$ConnMstSql[$namearra]= "a.first_name AS '".$field_label[$namearra]."'";

					}
					if(in_array('last_name', $field_name)){						
						$namearra= array_search("last_name",$field_name,true);
						$ConnMstSql[$namearra]= "a.last_name AS '".$field_label[$namearra]."'";

					}
				
					if(@in_array('id_mst_country_lang', $field_name)){						
						$namearra= array_search("id_mst_country_lang",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.id_mst_country_lang = 10000 THEN a.other_country ELSE c.name END AS '".$field_label[$namearra]."'";
					}

					if(@in_array('postcode', $field_name)){
						$namearra= array_search("postcode",$field_name,true);
						$ConnMstSql[$namearra] = "a.postcode AS '".$field_label[$namearra]."'";

					}

					if(@in_array('primary_contact_type', $field_name)){
						$namearra= array_search("primary_contact_type",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.primary_contact_type = 1 THEN a.primary_mobile WHEN a.primary_contact_type = 2 THEN a.primary_landline END AS '".$field_label[$namearra]."'";
					}

					if(@in_array('email', $field_name)){
						$namearra= array_search("email",$field_name,true);
						$ConnMstSql[$namearra] = "a.email AS '".$field_label[$namearra]."'";
					}
									
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}
					
					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status 
					
					FROM `mst_guest` a 
					
					LEFT JOIN `mst_country_lang` c ON a.id_mst_country_lang = c.id_country
					LEFT JOIN `mst_state` d ON a.id_mst_state = d.id_state
					LEFT JOIN  `mst_users` e ON a.id_mst_user_modified_by = e.id
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				    //echo $query;
					
	
		}

		function local_SelectQuery_Mst_Hotels($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
	

				$ConnMstSql=array();

					if(@in_array('address', $field_name)){
						$namearra= array_search("address",$field_name,true);

						$ConnMstSql[$namearra] = "a.address AS '".$field_label[$namearra]."'";
					}

					if(@in_array('id_mst_state', $field_name)){						
						$namearra= array_search("id_mst_state",$field_name,true);
						$ConnMstSql[$namearra]= "CASE WHEN a.id_mst_state = 10000 THEN a.other_state ELSE d.name END AS '".$field_label[$namearra]."'";
					}

					if(@in_array('city', $field_name)){
						$namearra=array_search("city",$field_name,true);
						$ConnMstSql[$namearra]= "a.city AS '".$field_label[$namearra]."'";
					}
					
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

					if(@in_array('hotel_code', $field_name)){
						$namearra=array_search("hotel_code",$field_name,true);
						$ConnMstSql[$namearra]= "a.hotel_code AS '".$field_label[$namearra]."'";
					}

					if(@in_array('id_mst_hotel_category', $field_name)){						
						$namearra= array_search("id_mst_hotel_category",$field_name,true);
						$ConnMstSql[$namearra]= "hc.name AS '".$field_label[$namearra]."'";

					}
					
					if(@in_array('id_mst_country_lang', $field_name)){						
						$namearra= array_search("id_mst_country_lang",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.id_mst_country_lang = 10000 THEN a.other_country ELSE c.name END '".$field_label[$namearra]."'";
					}

					if(@in_array('pincode', $field_name)){
						$namearra= array_search("pincode",$field_name,true);
						$ConnMstSql[$namearra] = "a.pincode AS '".$field_label[$namearra]."'";

					}

					if(@in_array('primary_contact_type', $field_name)){
						$namearra= array_search("primary_contact_type",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.primary_contact_type = 1 THEN a.primary_mobile WHEN a.primary_contact_type = 2 THEN a.primary_landline END AS '".$field_label[$namearra]."'";
					}

					if(@in_array('email', $field_name)){
						$namearra= array_search("email",$field_name,true);
						$ConnMstSql[$namearra] = "a.email AS '".$field_label[$namearra]."'";
					}
									
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}

					if($_REQUEST['id_mst_hotel_category'] != ''){
						$SearchConnSQL .= " AND a.`id_mst_hotel_category` = '".addslashes($_REQUEST['id_mst_hotel_category'])."%'";
					}


					if($_REQUEST['city'] != ''){
						$SearchConnSQL .= " AND a.`city` LIKE '%".addslashes($_REQUEST['city'])."%' ";
					}
					
					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_hotels` a 
					
					LEFT JOIN `mst_hotel_category` hc ON a.id_mst_hotel_category = hc.id
					LEFT JOIN `mst_country_lang` c ON a.id_mst_country_lang = c.id_country
					LEFT JOIN `mst_state` d ON a.id_mst_state = d.id_state
					LEFT JOIN  `mst_users` e ON a.id_mst_user_modified_by = e.id
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return  $query;

				   //echo $query;
					
	
		}


		function local_SelectQuery_Mst_Users($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

					if(@in_array('address', $field_name)){
						$namearra= array_search("address",$field_name,true);

						$ConnMstSql[$namearra] = "a.address AS '".$field_label[$namearra]."'";
					}

					if(@in_array('city', $field_name)){
						$namearra=array_search("city",$field_name,true);
						$ConnMstSql[$namearra]= "a.city AS '".$field_label[$namearra]."'";
					}
					
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

					if(@in_array('user_name', $field_name)){						
						$namearra= array_search("user_name",$field_name,true);
						$ConnMstSql[$namearra]= "a.user_name AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_user_levels', $field_name)){						
						$namearra= array_search("id_mst_user_levels",$field_name,true);
						$ConnMstSql[$namearra]= "ul.name AS '".$field_label[$namearra]."'";
					}
					
					if(@in_array('email', $field_name)){
						$namearra= array_search("email",$field_name,true);
						$ConnMstSql[$namearra] = "a.email AS '".$field_label[$namearra]."'";
					}

					if(@in_array('primary_contact_type', $field_name)){
						$namearra= array_search("primary_contact_type",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.primary_contact_type = 1 THEN a.primary_mobile WHEN a.primary_contact_type = 2 THEN a.primary_landline END AS '".$field_label[$namearra]."'";
					}
									
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['id_mst_user_levels'] != ''){
						$SearchConnSQL .= " AND a.`id_mst_user_levels` = ".$_REQUEST['id_mst_user_levels'];
					}

					if($_REQUEST['name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['name'])."'";
					}
					
					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_users` a 
					
					LEFT JOIN `mst_user_levels` ul ON a.id_mst_user_levels = ul.id
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
					
	
		}

		function local_SelectQuery_Mst_Team($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_team` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
					
	
		}

		function local_SelectQuery_Mst_UserLevels($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_user_levels` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY id DESC ";
								
				   return $query;

				   //echo "hello";
					
	
		}

		function local_SelectQuery_Mst_Hotel_Category($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_hotel_category` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
					
	
		}

		function local_SelectQuery_Mst_Room_Type($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_room_types` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
					
	
		}

		function local_SelectQuery_Mst_General_Services($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_hotel_general_services` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
					
	
		}
		
		function local_SelectQuery_Mst_Room_Blocks($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_hotel_room_block` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
					
	
		}

		function local_SelectQuery_Mst_Dining_Services($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_hotel_dining_services` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
					
	
		}

		function local_SelectQuery_Mst_Outdoor_Activities($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_hotel_outdoor_services` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
		}

		function local_SelectQuery_Mst_Conference_Services($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_hotel_conference_services` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
		}

		function local_SelectQuery_Mst_Room_Amenities($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_room_amenities` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
		}

		function local_SelectQuery_Company_Groups($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('field_value', $field_name)){						
						$namearra= array_search("field_value",$field_name,true);
						$ConnMstSql[$namearra]= "a.field_value AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` = '".addslashes($_REQUEST['search_name'])."'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_attributes` a 
					
					
					WHERE table_name ='company_group' AND a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
		}

		function local_SelectQuery_Company_Customer($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
	
				$ConnMstSql=array();

					if(@in_array('first_name', $field_name)){						
						$namearra= array_search("first_name",$field_name,true);
						$ConnMstSql[$namearra]= "a.first_name AS '".$field_label[$namearra]."'";
					}

					if(@in_array('city', $field_name)){
						$namearra=array_search("city",$field_name,true);
						$ConnMstSql[$namearra]= "a.city AS '".$field_label[$namearra]."'";
					}

					if(@in_array('id_mst_state', $field_name)){						
						$namearra= array_search("id_mst_state",$field_name,true);
						$ConnMstSql[$namearra]= "CASE WHEN a.id_mst_state = 10000 THEN a.other_state ELSE f.name END AS '".$field_label[$namearra]."'";
					}

				
					if(@in_array('id_mst_company', $field_name)){						
						$namearra= array_search("id_mst_company",$field_name,true);
						$ConnMstSql[$namearra] = "c.name AS'".$field_label[$namearra]."'";
					}

					if(@in_array('email', $field_name)){
						$namearra= array_search("email",$field_name,true);
						$ConnMstSql[$namearra] = "a.email AS '".$field_label[$namearra]."'";
					}

					if(@in_array('primary_contact_type', $field_name)){
						$namearra= array_search("primary_contact_type",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.primary_contact_type = 1 THEN a.primary_mobile WHEN a.primary_contact_type = 2 THEN a.primary_landline END AS '".$field_label[$namearra]."'";
					}

					
					ksort($ConnMstSql);
					
					$ConnMstSql	=	implode(",",$ConnMstSql);
					
					
					//=======================================================SEARCH

					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}

					if($_REQUEST['company_name'] != ''){
						$SearchConnSQL .= " AND c.`id` =".$_REQUEST['company_name'];
					}
					
					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}

					//=======================================================SEARCH
					
					$query = "SELECT a.id, a.id_mst_company,  ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status 
					
					FROM mst_company_contacts a 
					
					LEFT JOIN mst_country_lang d ON a.id_mst_country_lang = d.id_country 
					LEFT JOIN mst_state f ON a.id_mst_state = f.id_state 
					LEFT JOIN mst_company c ON a.id_mst_company = c.id 
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' AND a.first_name!='' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";

				    return $query;	
	
		}


		function local_SelectQuery_Mst_Company_Areas($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

										
					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

						
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}



					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_company_area` a 
					
					
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
		}


		function local_SelectQuery_Mst_Portfolio_Account($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

					if(@in_array('status_active_date', $field_name)){						
						$namearra= array_search("status_active_date",$field_name,true);
						$ConnMstSql[$namearra]= " DATE_FORMAT(a.status_active_date,'%d-%m-%Y') AS '".$field_label[$namearra]."'";

					}

					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_users_primary', $field_name)){						
						$namearra= array_search("id_mst_users_primary",$field_name,true);
						$ConnMstSql[$namearra]= "u.user_name AS '".$field_label[$namearra]."'";

					}

					if(@in_array('ids_mst_users_secondary', $field_name)){						
						$namearra= array_search("ids_mst_users_secondary",$field_name,true);
						$ConnMstSql[$namearra]= "us.name AS '".$field_label[$namearra]."'";

					}
									
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['id_mst_users_primary'] != ''){
						$SearchConnSQL .= " AND a.`id_mst_users_primary` = ".$_REQUEST['id_mst_users_primary'];
					}

					if($_REQUEST['ids_mst_users_secondary'] != ''){
						$SearchConnSQL .= " AND a.`id_mst_users_primary` = ".$_REQUEST['ids_mst_users_secondary'];
					}

					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_portfolio_account` a 
					
					LEFT JOIN `mst_users` u ON a.id_mst_users_primary = u.id
					LEFT JOIN `mst_users` us ON a.ids_mst_users_secondary = us.id
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
		}


			
				
	
	

		function local_SelectQuery_Mst_Items($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = '', $item_list){
				
				//echo '======='.$item_list;
				
				$ConnMstSql=array();

					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_attributes_item_type', $field_name)){						
						$namearra= array_search("id_mst_attributes_item_type",$field_name,true);
						$ConnMstSql[$namearra]= "at.field_value AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_attributes_unit_main', $field_name)){						
						$namearra= array_search("id_mst_attributes_unit_main",$field_name,true);
						$ConnMstSql[$namearra]= "am.field_value AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_attributes_group_main', $field_name)){						
						$namearra= array_search("id_mst_attributes_group_main",$field_name,true);
						$ConnMstSql[$namearra]= "agm.field_value AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_attributes_group_sub', $field_name)){						
						$namearra= array_search("id_mst_attributes_group_sub",$field_name,true);
						$ConnMstSql[$namearra]= "ags.field_value AS '".$field_label[$namearra]."'";

					}
									
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH

					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH

		$SearchConnSQL .= " AND a.`id_mst_attributes_item_type` IN(".$item_list.")";
	
		
		 $query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `inv_items` a 
					
					LEFT JOIN `mst_attributes` at ON a.id_mst_attributes_item_type = at.id
					LEFT JOIN `mst_attributes` am ON a.id_mst_attributes_unit_main = am.id
					LEFT JOIN `mst_attributes` agm ON a.id_mst_attributes_group_main = agm.id
					LEFT JOIN `mst_attributes` ags ON a.id_mst_attributes_group_sub = ags.id
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL  
					ORDER BY a.".$EnableOrderBy." ASC ";
						//echo $query ; 		
				   return $query;  
				
		}	
	
			
		
		function local_SelectQuery_Mst_Items1($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_attributes_item_type', $field_name)){						
						$namearra= array_search("id_mst_attributes_item_type",$field_name,true);
						$ConnMstSql[$namearra]= "at.field_value AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_attributes_unit_main', $field_name)){						
						$namearra= array_search("id_mst_attributes_unit_main",$field_name,true);
						$ConnMstSql[$namearra]= "am.field_value AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_attributes_group_main', $field_name)){						
						$namearra= array_search("id_mst_attributes_group_main",$field_name,true);
						$ConnMstSql[$namearra]= "agm.field_value AS '".$field_label[$namearra]."'";

					}

					if(@in_array('id_mst_attributes_group_sub', $field_name)){						
						$namearra= array_search("id_mst_attributes_group_sub",$field_name,true);
						$ConnMstSql[$namearra]= "ags.field_value AS '".$field_label[$namearra]."'";

					}
									
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH

					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `inv_items` a 
					
					LEFT JOIN `mst_attributes` at ON a.id_mst_attributes_item_type = at.id
					LEFT JOIN `mst_attributes` am ON a.id_mst_attributes_unit_main = am.id
					LEFT JOIN `mst_attributes` agm ON a.id_mst_attributes_group_main = agm.id
					LEFT JOIN `mst_attributes` ags ON a.id_mst_attributes_group_sub = ags.id
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL AND a.id_mst_attributes_item_type = 177
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
					
	
		}
		
		

		function local_SelectQuery_Charges_Master($tableName = '', $field_name = '' , $field_label = '', $EnableOrderBy = ''){
				
				$ConnMstSql=array();

					if(@in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS '".$field_label[$namearra]."'";

					}

					if(@in_array('charges_account', $field_name)){						
						$namearra= array_search("charges_account",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.charges_account = 1 THEN 'SALES' WHEN a.charges_account = 2 THEN 'PURCHASE' WHEN a.charges_account = 3 THEN 'INCOME' WHEN a.charges_account = 4 THEN 'EXPENSE' WHEN a.charges_account = 5 THEN 'TAXES' WHEN a.charges_account = 6 THEN 'DISCOUNT' ELSE 'OTHERS' END AS '".$field_label[$namearra]."'";

					}

					if(@in_array('tax_applicable', $field_name)){						
						$namearra= array_search("tax_applicable",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.tax_applicable = 1 THEN 'GST' WHEN a.tax_applicable = 2 THEN 'VAT' ELSE 'Not Applicable' END AS '".$field_label[$namearra]."'";
					}

					if(@in_array('tax_type', $field_name)){						
						$namearra= array_search("tax_type",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.tax_type = 1 THEN 'SGST' WHEN a.tax_type = 2 THEN 'CGST' WHEN a.tax_type = 3 THEN 'IGST' WHEN a.tax_type = 4 THEN 'VAT' WHEN a.tax_type = 5 THEN 'CESS'  ELSE 'Not Applicable' END AS '".$field_label[$namearra]."'";
					}

					if(@in_array('transaction_type', $field_name)){						
						$namearra= array_search("transaction_type",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.transaction_type = 1 THEN 'Local' WHEN a.transaction_type = 2 THEN 'Interstate' ELSE 'Not Applicable' END AS '".$field_label[$namearra]."'";
					}


									
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH

					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}

					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT a.id, ";
					$query .=	$ConnMstSql;
					$query .= ", DATE_FORMAT(a.date_created,'%d-%m-%Y %h:%m:%s') AS `Creation Date`, DATE_FORMAT(a.last_modified,'%d-%m-%Y %h:%m :%s') AS `Modified Date`, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS Status
					
					FROM `mst_charges` a 
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				   return $query;

				   //echo "hello";
					
	
		}
		



?>
