<?php 
	include_once("../../config/auto_loader.php");
	
	$sqlNoti="SELECT * FROM ".TBL_NOTIFICATION." WHERE id_user_assigned_to='".$_SESSION['userId']."' AND read_status='0' AND id_shop='".$_SESSION['shop']."' ORDER BY dated DESC";
	
	$res = mysqli_query($connNew,$sqlNoti);

	if($res){
		$rowCount = mysqli_num_rows($res);
		if($rowCount>0){

			

			$table = "<table class='table' style='text-align:left;width: 100%;margin:0px auto; color:black;'>
						<tr style='background-color:#e2e2e2;'>
							<td>Date</td>
							<td>Source</td>
							<td>From</td>
							<td>Message</td>
							<td>Read Status</td>
						</tr>";
			while($row = mysqli_fetch_object($res)){

				if($row->source=='Sales Report'){
					$addDsr ="&nbsp;&nbsp;<button class='btn btn-info' id='readStatus".$row->id."' 
					onClick=\"
					    window.open('ManagervisitReport.php?searchFormSubmit=1&Download=Download&location=open&report_date=".$row->dated." to ".$row->dated."&usernameid=".$row->id_user_assigned_to."');\">Open</button>";	
				}
				else{
					$addDsr="";
				}

				$table .="<tr>
							<td>".date('d-M-Y',strtotime($row->dated))."</td>
							<td>".$row->source."</td>
							<td>".ucwords(selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_user_assigned_by.'" '))."</td>
							<td>".ucfirst($row->message)."</td>
							<td><button class='btn btn-success' id='readStatus".$row->id."' onClick='updateRead(this.id);'>Mark as read</button>".$addDsr."</td>
							</tr>";
			}

			$table.="</table>";
			$dataArray = array($table,$rowCount);
			echo json_encode($dataArray);

		}
		else{
			echo "";
		}
	}
	else{
		echo "";
	}

?>	