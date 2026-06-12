<?php  include_once("../../config/auto_loader.php");
//print_r($_SESSION);
//echo date('Y-m-d',strtotime($_REQUEST['reservation_date']));
//echo "shafeer";

$SelectedDate	= date('Y-m-d',strtotime($_REQUEST['reservation_date']));

$DateVisitList	='<div class="box">
          <div class="box-header">
            <h3 class="box-title">Visit List</h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
          
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                                      
                    <th>Company Name</th>
                     <th>Person Met</th>
                    <th>Business Potential</th>
                    <th>Discussion Summary</th>
                    <th>Sales Executive</th>
					<th>Action</th>
                  </tr>
                </thead>
                <tbody>';
               
							 				
				 $sql = " SELECT  *  FROM `fs_daily_visit`  WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'  AND `dated` = '".date('Y-m-d',strtotime($SelectedDate))."'  ";

				$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();

if($total > 0){$counter = 1;

				$Expand = 1;
				  while($row = $db->fetch_object()){
				//print_r($row);
				 $y	=encryptor('encrypt',$row->id);					 
					  $Expand;	  
                  $resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($row->id_contacts)."'",''); 
		  			$resultContact = $db->fetch_object2($resContact);
                    $NAme	=	$resultContact->first_name.' '.$resultContact->last_name;
					
				   $DateVisitList	.='<tr>';
				  // $DateVisitList	.='<td>'.(($_REQUEST['page']-1)*$setpage)+$counter++.'</td>';
				   $DateVisitList	.='<td>'.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'").'</td>';
				   $DateVisitList	.='<td>'.$NAme.'</td>';
				   $DateVisitList	.='<td>'.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'").'</td>';
                   $DateVisitList	.='<td>'.$row->business_potential.'</td>';
                   $DateVisitList	.='<td>'.$row->discussion_summary.'</td>';
				   $DateVisitList	.='<td style="padding-bottom:10px;">&nbsp;&nbsp;<a href="addreport.php?eId='.$y.'&action=edit&page='.$_REQUEST['page'].'" ><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit "  /></a>
                   </a> </td>';
				  }}
                $DateVisitList	.=' </tr></tbody>
              </table>
            </div>
          </form>';
         
      echo  $DateVisitList	.='</div>';
        
?>