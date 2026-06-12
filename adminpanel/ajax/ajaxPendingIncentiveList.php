<?php
	include_once("../../config/auto_loader.php");
 
	if($_SESSION['teamMembers'] !=""){

		if($_SESSION['teamMemberLevel']==2){
			$sqlLevel = "SELECT id_user_level_1 FROM ".TBL_TEAM." WHERE id_user_level_2='".$_SESSION['userId']."' ";
			$resRet = mysqli_query($connNew,$sqlLevel);

			$arrRet = array();

			while($rowRet=mysqli_fetch_object($resRet)){
				array_push($arrRet, $rowRet->id_user_level_1);
			}

			$teamMembers = "AND  FIND_IN_SET(A.id_user, '".str_replace(','.$_SESSION['userId'], '', implode(',',$arrRet))."')";
		}
		else{
		     //$teamMembers = "AND  FIND_IN_SET(A.id_user,'".str_replace(','.$_SESSION['userId'], '', $_SESSION['teamMembers'])."')";
			/*$idsTeamSQL = "SELECT id FROM ".TBL_TEAM." WHERE id_user_level_1='".$_SESSION['userId']."' OR id_user_level_2='".$_SESSION['userId']."' "  ;

			 $idsTeamRes = mysqli_query($connNew, $idsTeamSQL);
			 $idsTeam = array();
			 while($idsTeamRow = mysqli_fetch_object($idsTeamRes)){
			 	array_push($idsTeam, $idsTeamRow->id);
			 }
             $allUserIds = array();
			 //$teamMemberSQL = "SELECT id FROM ".TBL_USERS." WHERE FIND_IN_SET(ids_team, '".implode(',', $idsTeam)."') "; 
            foreach($idsTeam as $idTeamValue){
				$teamMemberSQL = "SELECT id FROM ".TBL_USERS." WHERE FIND_IN_SET('".$idTeamValue."',ids_team) ";
			    $teamMemberRes = mysqli_query($connNew, $teamMemberSQL);
    			 while($rowTeamMembers = mysqli_fetch_object($teamMemberRes)){
    			 	array_push($allUserIds, $rowTeamMembers->id);
    			 }
            }
			 $teamMembers = "AND  FIND_IN_SET(A.id_user, '".implode(',',$allUserIds)."') ";*/
			 
			 
			 
			 
			// echo $teamMembers = "AND  FIND_IN_SET(A.id_user,'".str_replace(','.$_SESSION['userId'], '', $_SESSION['teamMembers'])."')";
			 //OR id_user_level_2='".$_SESSION['userId']."'
			  $idsTeamSQL = "SELECT id FROM ".TBL_TEAM." WHERE id_user_level_1='".$_SESSION['userId']."'  "  ;

			 $idsTeamRes = mysqli_query($connNew, $idsTeamSQL);
			 $idsTeam = array();
			 while($idsTeamRow = mysqli_fetch_object($idsTeamRes)){
			 	array_push($idsTeam, $idsTeamRow->id);
			 }

			$allUserIds = array();
			foreach($idsTeam as $idTeamValue){
				$teamMemberSQL = "SELECT id FROM ".TBL_USERS." WHERE FIND_IN_SET('".$idTeamValue."',ids_team) ";
				 $teamMemberRes = mysqli_query($connNew, $teamMemberSQL);
	
				 
	
				 while($rowTeamMembers = mysqli_fetch_object($teamMemberRes)){
					array_push($allUserIds, $rowTeamMembers->id);
				 }
			}
			
			//================================================
			  $idsTeamSQL_2 = "SELECT id_user_level_1 FROM ".TBL_TEAM." WHERE  id_user_level_2='".$_SESSION['userId']."' "  ;

			 $idsTeamRes_2 = mysqli_query($connNew, $idsTeamSQL_2);
			  $numRows_2	=mysqli_num_rows($idsTeamRes_2);
			 if($numRows_2>0){
				
			 $idsTeam_2 = array();
			 while($idsTeamRow_2 = mysqli_fetch_object($idsTeamRes_2)){
			 	array_push($idsTeam_2, $idsTeamRow_2->id_user_level_1);
			 }
				$allUserIds = array_merge($allUserIds, $idsTeam_2);
			
			}
			
			//================================================
			
			
			
			  $teamMembers = "AND  FIND_IN_SET(A.id_user, '".implode(',',$allUserIds)."') ";
			
		
		}
	 
	}
	else{
	  $teamMembers ="";
	}

	$countToGet = 1 ;
	if($_REQUEST['flag']==1){
		if($_REQUEST['pendingshowlist']=='30days'){
		$SqlConn	=	'AND A.date_created >= (CURDATE() - INTERVAL 1 MONTH )';
		}else if($_REQUEST['pendingshowlist']=='showall'){
			$SqlConn	=	'';
			}else{
				$SqlConn	=	'AND A.date_created >= (CURDATE() - INTERVAL 1 MONTH )';
				}
		
	/*	$sql = "SELECT DISTINCT 1 AS 'id',A.id_user AS id_user,A.dated,'Sales Report' AS 'DOC_TYPE',A.conveyance_approved,supervisor_remarks FROM ".TBL_DAILYVISIT." A  
			LEFT JOIN ".TBL_USERS." B ON A.id_user=B.id 
			WHERE A.id_shop='".$_SESSION['shop']."' AND (A.conveyance_approved=0 || A.conveyance_approved=2)  ".$teamMembers." AND (Total+entertainment+lunch) !=0  $SqlConn


			UNION ALL 

			SELECT  A.id AS id,A.id_user AS id_user,A.dated,'other' AS 'DOC_TYPE',A.conveyance_approved,supervisor_remarks FROM ".TBL_OTHER." A  
			LEFT JOIN ".TBL_USERS." B ON A.id_user=B.id 
			WHERE A.id_shop='".$_SESSION['shop']."' AND (A.conveyance_approved=0 || A.conveyance_approved=2)  ".$teamMembers." AND (Total+entertainment+lunch) !=0 $SqlConn
  			ORDER BY DOC_TYPE,dated DESC";*/
  				
			  /* $sql = "SELECT B.*,A.id_enquiry as id_enquiry,A.hotel_id FROM `".TBL_INCENTIVE."` A  
			RIGHT JOIN ".TBL_INCENTIVE_DETAILS." B ON A.id=B.id_incentive 
			WHERE A.id_shop='".$_SESSION['shop']."'  AND B.id_forward_for_approval='".$_SESSION['userId']."' 
			ORDER BY B.dated DESC

			";*/
			$sql11 = "SELECT A.* FROM `".TBL_INCENTIVE."` AS  A  
			
			WHERE A.id_shop='".$_SESSION['shop']."'  AND A.id_currently_with='".$_SESSION['userId']."' and (current_status='0' OR current_status='1')
			ORDER BY A.id DESC

			";
		
		$IncentiveSQL	=" SELECT max(id) as MaxId FROM fs_incentive_details group by fs_incentive_details.id_incentive";
		$resIncentive = mysqli_query($connNew,$IncentiveSQL);
		if($resIncentive){
			$numRows = mysqli_num_rows($resIncentive); $rowIncentiveMaxId=array();
			while($rowIncentive	=mysqli_fetch_object($resIncentive)){
				
				$rowIncentiveMaxId[]=$rowIncentive->MaxId;
				}
			$rowIncentiveMaxId=implode(',',$rowIncentiveMaxId);
		}
	$sql = "SELECT * 

FROM `fs_incentive` 
LEFT JOIN fs_incentive_details ON `fs_incentive`.id = fs_incentive_details.id_incentive 
WHERE `fs_incentive`.follow_up_close_summary !='' AND `fs_incentive`.`id_shop` = '6' AND (fs_incentive.`current_status` = '0' || fs_incentive.`current_status` = '1')  AND fs_incentive_details.id_forward_for_approval='".$_SESSION['userId']."' AND (`fs_incentive_details`.id IN (".$rowIncentiveMaxId.") ) " ;
		//echo $sql;die;
			//AND B.status='0'
		$res = mysqli_query($connNew,$sql);
		$notiCount = mysqli_num_rows($res);
		if($notiCount>0){
			$i=0;
			
			$content = '<table id="tableDataBody" class="table " style="text-align:left;width: 100%;margin:0px auto; color:black;">
                    <!--<tr ><td colspan="3" style="background-color:#3C8DBC;padding: 10px 10px !important">Approval Pending List</td></tr>-->
                    			<tr style="background-color:#e2e2e2;">
                    			<td  style="padding:10px;font-weight:bold;">Claim Date</td> 
								<td  style="padding:10px;font-weight:bold;">Lead Created Date</td>                   			
                    			<td  style="padding:10px;font-weight:bold;">Lead Handled By</td>
								<td style="padding:10px;font-weight:bold;">Lead For Hotel</td>
								
								<td  style="padding:10px;font-weight:bold;">Action</td>
								</tr>';
                  $inc=1;
			while($row = mysqli_fetch_object($res)){
				
				

				$userName = "SELECT name FROM ".TBL_USERS." WHERE id='".$row->id_user."' AND id_shop='".$_SESSION['shop']."'  ";

				$resName = mysqli_query($connNew,$userName);

				$select1='';
				
				$select2='';
				
				$selectZero='';
				
				/*if($row->conveyance_approved==1){
					$select1 = "selected='selected'";
				}
				else if($row->conveyance_approved==2){
					$select2 = "selected='selected'";
				}
				else{
					$selectZero = "";
				}*/


				$dataName = mysqli_fetch_object($resName);
				$hotelName	= selectColumn(TBL_HOTELS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$row->hotel_id."'");
				$hotelCityName	= selectColumn(TBL_HOTELS,'city'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$row->hotel_id."'");
				$enqueryDated	= selectColumn(TBL_DAILY_ENQUERY,'dated'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$row->id_enquiry."'");
				$content .='<tr style="border 1px solid;">
								<td style="padding:10px;">'.date('d-M-Y',strtotime($row->date_created)).'<br>'.date('l',strtotime($row->date_created)).'</td>
								<td style="padding:10px;" >'.date('d-M-Y',strtotime($enqueryDated)).'</td>
								<td style="padding:10px;">'.ucwords($dataName->name).'</td>
								<td style="padding:10px;">'.$hotelName.'- '.$hotelCityName.'
									
								</td>
									
								</td>
								
								<td style="padding:10px;" class="approved" >

									

									<a target="new" href="editEnquiry.php?action=edit&eId='.encryptor('encrypt',$row->id_enquiry).'" title="Edit"><button class="btn btn-info" style="margin-left:10px;">Open</button></a>
								</td>
							</tr>';

				$countToGet++;
				
			}
			$content .="</table>";
			$resultArr = array($content,$notiCount);
			echo json_encode($resultArr);
			
		}

	}
	else{
		echo json_encode('');
	}

	mysqli_close($connNew);
?>