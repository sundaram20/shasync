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

			$teamMembers = "AND  FIND_IN_SET(A.id_user,'".str_replace(','.$_SESSION['userId'], '', implode(',',$arrRet))."')";
		}
		else{
			 $teamMembers = "AND  FIND_IN_SET(A.id_user,'".str_replace(','.$_SESSION['userId'], '', $_SESSION['teamMembers'])."')";
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
		
		$sql = "SELECT DISTINCT 1 AS 'id',A.id_user AS id_user,A.dated,'Sales Report' AS 'DOC_TYPE',A.conveyance_approved,supervisor_remarks FROM ".TBL_DAILYVISIT." A  
			LEFT JOIN ".TBL_USERS." B ON A.id_user=B.id 
			WHERE A.id_shop='".$_SESSION['shop']."' AND (A.conveyance_approved=0 || A.conveyance_approved=2)  ".$teamMembers." AND (Total+entertainment+lunch) !=0  $SqlConn


			UNION ALL 

			SELECT  A.id AS id,A.id_user AS id_user,A.dated,'other' AS 'DOC_TYPE',A.conveyance_approved,supervisor_remarks FROM ".TBL_OTHER." A  
			LEFT JOIN ".TBL_USERS." B ON A.id_user=B.id 
			WHERE A.id_shop='".$_SESSION['shop']."' AND (A.conveyance_approved=0 || A.conveyance_approved=2)  ".$teamMembers." AND (Total+entertainment+lunch) !=0 $SqlConn
  			ORDER BY DOC_TYPE,dated DESC";
  				
		
		$res = mysqli_query($connNew,$sql);
		$notiCount = mysqli_num_rows($res);
		if($res){
			$i=0;
			
			$content = '<table id="tableDataBody" class="table " style="text-align:left;width: 100%;margin:0px auto; color:black;">
                    <!--<tr ><td colspan="3" style="background-color:#3C8DBC;padding: 10px 10px !important">Approval Pending List</td></tr>-->
                    			<tr style="background-color:#e2e2e2;">
                    			<td  style="padding:10px;font-weight:bold;">Date</td>
                    			<td  style="padding:10px;font-weight:bold;">Source</td>
                    			<td  style="padding:10px;font-weight:bold;">Sales Executive</td>
								<td width="50%" style="padding:10px;font-weight:bold;">Remarks</td>
								<td  style="padding:10px;font-weight:bold;">Action</td>
								</tr>';
                  $inc=1;
			while($row = mysqli_fetch_object($res)){
				
				

				$userName = "SELECT name FROM ".TBL_USERS." WHERE id='".$row->id_user."' AND id_shop='".$_SESSION['shop']."'  ";

				$resName = mysqli_query($connNew,$userName);

				if($row->conveyance_approved==1){
					$select1 = "selected='selected'";
				}
				else if($row->conveyance_approved==2){
					$select2 = "selected='selected'";
				}
				else{
					$select0="selected='selected'";
				}


				$dataName = mysqli_fetch_object($resName);
				
				$content .='<tr style="border 1px solid;">
								<td style="padding:10px;">'.date('d-M-Y',strtotime($row->dated)).'<br>'.date('l',strtotime($row->dated)).'</td>
								<td style="padding:10px;">'.ucfirst($row->DOC_TYPE).'</td>
								<td style="padding:10px;">'.ucwords($dataName->name).'</td>
								<td>
									<input name="supRemark'.$countToGet.'"
										id="supRemark'.$countToGet.'" 
										 value="'.$row->supervisor_remarks.'"
										class="form-control" type="text" placeholder="Type Remarks here..." />
										
									<lable>Approval Status</lable>
									<select class="form-control" name="approval'.$countToGet.'" id="approval'.$countToGet.'">
										<option '.$select0.' selected="selected" value="0">Select Action</option>
										<option '.$select1.' value="1">Approved</option>
										<option '.$select2.' value="2" >Not Approved</option>
									</select>
								</td>
								<td style="padding:10px;" class="approved" >

									<button id="'.($row->DOC_TYPE=="other"?encryptor('encrypt',$row->id):$row->id_user).'" onClick="updatePendingApproval(this.id,\''.$row->dated.'\','.$countToGet.',\''.$row->DOC_TYPE.'\','.$row->id_user.')" class="btn btn-success btn-small">Update</button>

									<button class="btn btn-info" id="'.($row->DOC_TYPE=="other"?encryptor('encrypt',$row->id):$row->id_user).'" style="margin-left:10px;" onClick="openDsr(this.id,\''.$row->dated.'\','.$countToGet.',\''.$row->DOC_TYPE.'\')">Open</button>
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