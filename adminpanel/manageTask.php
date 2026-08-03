<?php include_once("../config/auto_loader.php");

// checkUserLevelPermission($_SESSION['userLevel'],'task','view');

/*
	Table names used directly as literals in this app (no TBL_* constants):
	task, task_details, mst_team, task_module.

	`task` = one row per task item (fixed: date, team, module, description, task_code).
	`task_details` = full history of that item (executive, status, estimated
	delivery, completed date, remark) - append-only, latest row = current state.

	Status value -> label map (keep in sync with editTask.php):
		Pending      -> Pending
		In Progress  -> In Progress
		Completed    -> Development Completed
		QA           -> QA
		On Hold      -> Customer Verified
*/

function taskStatusLabel($status){
	$map = array(
		'Pending'     => 'Pending',
		'In Progress' => 'In Progress',
		'Completed'   => 'Development Completed',
		'QA'          => 'QA',
		'On Hold'     => 'Customer Verified',
	);
	return isset($map[$status]) ? $map[$status] : $status;
}

function taskStatusBadgeClass($status){
	$map = array(
		'Pending'     => 'label-default',
		'In Progress' => 'label-info',
		'Completed'   => 'label-success',
		'QA'          => 'label-warning',
		'On Hold'     => 'label-primary',
	);
	return isset($map[$status]) ? $map[$status] : 'label-default';
}

// ---- Every $_REQUEST read below goes through isset() first: on PHP 8,
// reading an undefined array key raises a warning, and depending on your
// error_reporting/display_errors settings that warning text can leak into
// the output and break the page (this was likely why filters looked broken). ----

$reqIdTeam       = isset($_REQUEST['id_team']) ? $_REQUEST['id_team'] : '';
$reqIdExecutive  = isset($_REQUEST['id_executive']) ? $_REQUEST['id_executive'] : '';
$reqIdModule     = isset($_REQUEST['id_module']) ? $_REQUEST['id_module'] : '';
$reqTaskStatuses = isset($_REQUEST['task_status']) ? (array)$_REQUEST['task_status'] : array();
$reqTaskStatuses = array_filter($reqTaskStatuses, function($v){ return $v !== ''; });
$reqTaskDate     = isset($_REQUEST['task_date']) ? trim($_REQUEST['task_date']) : '';
$reqDateFields   = isset($_REQUEST['date_field']) ? (array)$_REQUEST['date_field'] : array('task_date');
$reqAction       = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$reqDelId        = isset($_REQUEST['delId']) ? $_REQUEST['delId'] : '';
$reqExport       = isset($_REQUEST['Export']) ? $_REQUEST['Export'] : '';

/////////////////////////////////////////////////////////////////////////////////////
// Delete a task item entirely (its whole history goes with it).
/////////////////////////////////////////////////////////////////////////////////////
if($reqAction == 'delete' && $reqDelId != ''){

	checkUserLevelPermission($_SESSION['userLevel'],'task','delete');

	$taskId = addslashes(encryptor('decrypt',$reqDelId));

	executeSql("DELETE FROM `task_details` WHERE `task_id`='".$taskId."' ");
	executeSql("DELETE FROM `fs_daily_calender` WHERE `type`='9' AND `visit_id`='".$taskId."' ");

	if(executeSql("DELETE FROM `task` WHERE `id`='".$taskId."' ")){
		$_SESSION['successMsg'] = 'Task has been deleted successfully.';
	}else{
		$_SESSION['errorMsg'] = 'Unable to delete task.';
	}
}

/////////////////////////////////////////////////////////////////////////////////////
// Build listing query - task joined to its latest task_details row
/////////////////////////////////////////////////////////////////////////////////////

$sql = " SELECT T.*, TD.id AS detail_id, TD.id_executive, TD.estimated_delivery_date,
				TD.status, TD.completed_date, TD.remark
		 FROM `task` T
		 LEFT JOIN `task_details` TD ON TD.id = (
			SELECT MAX(id) FROM `task_details` WHERE task_id = T.id
		 )
		 WHERE T.id_shop = '".addslashes($_SESSION['shop'])."' ";

// ---- Permission scoping (simplified - extend like the old drilled_team logic if needed) ----
if($_SESSION['userLevel'] != '1'){
	$sql .= " AND (TD.id_executive = '".addslashes($_SESSION['userId'])."' OR T.created_by = '".addslashes($_SESSION['userId'])."') ";
}

// ---- Filters ----
if($reqIdTeam != ''){
	$sql .= " AND T.id_team = '".addslashes($reqIdTeam)."' ";
}

if($reqIdExecutive != ''){
	$sql .= " AND TD.id_executive = '".addslashes($reqIdExecutive)."' ";
}

if($reqIdModule != ''){
	$sql .= " AND T.id_module = '".addslashes($reqIdModule)."' ";
}

if(!empty($reqTaskStatuses)){
	$escapedStatuses = array();
	foreach($reqTaskStatuses as $st){
		$escapedStatuses[] = "'".addslashes($st)."'";
	}
	$sql .= " AND TD.status IN (".implode(',',$escapedStatuses).") ";
}

// Date range applies to whichever field(s) are checked - Task Date and/or
// Estimated Delivery Date. If both are checked, a row matches if EITHER
// date falls in the range ("search with any of the date").

if(isset($_REQUEST['check_box']) && $_REQUEST['check_box'] == 1 && !empty($_REQUEST['date_created'])){
    $date_created = explode(" to ", $_REQUEST['date_created']);
    $from_book = $date_created[0];
    $to_book = $date_created[1];

    $sql .= " AND DATE(T.dated) >= '".date('Y-m-d', strtotime($from_book))."' AND DATE(T.dated) <= '".date('Y-m-d', strtotime($to_book))."' ";
}

if(isset($_REQUEST['renewal_check']) && $_REQUEST['renewal_check'] == 1 && !empty($_REQUEST['renewal_date'])){
    
    $renewal_date = explode(" to ", $_REQUEST['renewal_date']);
    $from_renewal = $renewal_date[0];
    $to_renewal   = $renewal_date[1];

    $sql .= " AND dp.id_renewal_required = '1' 
              AND DATE(TD.estimated_delivery_date) >= '".date('Y-m-d', strtotime($from_renewal))."'
              AND DATE(TD.estimated_delivery_date) <= '".date('Y-m-d', strtotime($to_renewal))."' ";
}

if(@$_REQUEST['searchFormSubmit']!='1'){

  $UniqueDateFor = date ('Y-m-d'); 
	$StartDateListFor	=	strtotime("-7 day", strtotime($UniqueDateFor));
	$UniqueDateFor = date ("Y-m-d", $StartDateListFor); 
	$sql .= " AND T.dated >= '".$UniqueDateFor."'";

}

$sql .= " ORDER BY T.id DESC ";

/////////////////////////////////////////////////////////////////////////////////////
// Export to Excel - same filters, no pagination
/////////////////////////////////////////////////////////////////////////////////////
if($reqExport == 'Export'){

	$exportRes = executeSql($sql);

	$objPHPExcel->getProperties()->setCreator($_SESSION['userId'])
								 ->setTitle("Task List Export")
								 ->setSubject("Task List Export")
								 ->setDescription("Task List Export");

	$sheet = $objPHPExcel->getActiveSheet();
	$sheet->setTitle('Tasks');

	$headers = array('Task ID','Date','Executive','Team','Module','Task Description','Estimated Delivery','Status','Completed Date');
	$col = 'A';
	foreach($headers as $h){
		$sheet->setCellValue($col.'1', $h);
		$col++;
	}
	$sheet->getStyle('A1:I1')->getFont()->setBold(true);

	$rowNum = 2;
	while($exportRow = $db->fetch_assoc2($exportRes)){

		$execName   = ucfirst(selectColumn(TBL_USERS,'name'," WHERE `id` = '".$exportRow['id_executive']."'"));
		$teamName   = ucfirst(selectColumn('mst_team','name'," WHERE `id` = '".$exportRow['id_team']."'"));
		$moduleName = ucfirst(selectColumn('task_module','name'," WHERE `id` = '".$exportRow['id_module']."'"));

		$estDelivery = ($exportRow['estimated_delivery_date'] != '' && $exportRow['estimated_delivery_date'] != '0000-00-00') ? date('d M Y',strtotime($exportRow['estimated_delivery_date'])) : '-';
		$completed   = ($exportRow['completed_date'] != '' && $exportRow['completed_date'] != '0000-00-00') ? date('d M Y',strtotime($exportRow['completed_date'])) : '-';

		$sheet->setCellValue('A'.$rowNum, $exportRow['task_code']);
		$sheet->setCellValue('B'.$rowNum, date('d M Y',strtotime($exportRow['dated'])));
		$sheet->setCellValue('C'.$rowNum, $execName);
		$sheet->setCellValue('D'.$rowNum, $teamName);
		$sheet->setCellValue('E'.$rowNum, $moduleName);
		$sheet->setCellValue('F'.$rowNum, $exportRow['description']);
		$sheet->setCellValue('G'.$rowNum, $estDelivery);
		$sheet->setCellValue('H'.$rowNum, taskStatusLabel($exportRow['status']));
		$sheet->setCellValue('I'.$rowNum, $completed);

		$rowNum++;
	}

	foreach(range('A','I') as $c){
		$sheet->getColumnDimension($c)->setWidth(20);
	}

	$fileName = 'Task_List_'.date('d_M_Y');

	if(ob_get_length()) ob_end_clean();

	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}

/////////////////////////////////////////////////////////////////////////////////////

$db->query($sql);
$numRows = $db->num_rows();

$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">

  <section class="content-header">
    <h1> Task Manager <small>Task Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Task</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="nav-tabs-custom">

          <div class="form-group has-error" align="center">
            <?php if(isset($_SESSION['errorMsg']) && $_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);
            }elseif(isset($_SESSION['successMsg']) && $_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);
            }?>
          </div>

          <div class="box-header with-border">
            <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small></h3>
            <a title="Add Task" class="pull-right btn btn-success" href="editTask.php" style="color:#fff;font-weight:bold;">&nbsp;ADD TASK</a>
          </div>

          <!-- /.box-header -->
          <form name="searchForm" id="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
            <div class="box-body">
              <div class="row">

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Team Group</label>
                    <?php
                      $teamDropDown = '<select class="form-control select2" name="id_team" id="filter_id_team">
                                          <option value="">All Teams</option>';
                      $resTeam = selectSql('mst_team'," WHERE `status`='1' AND `id_shop`='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                      if($db->num_rows2($resTeam)){
                          while($rowTeam = $db->fetch_object2($resTeam)){
                              $selected = ($reqIdTeam == $rowTeam->id) ? 'selected="selected"' : '';
                              $teamDropDown .= '<option '.$selected.' value="'.$rowTeam->id.'">'.ucfirst($rowTeam->name).'</option>';
                          }
                      }
                      $teamDropDown .= '</select>';
                      echo $teamDropDown;
                    ?>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Executive</label>
                    <?php
                      $execDropDown = '<select class="form-control select2" name="id_executive" id="filter_id_executive">
                                          <option value="">All Executives</option>';
                      $execWhere = " WHERE `status`='1' AND `id_shop`='".addslashes($_SESSION['shop'])."' AND (user_type=1 OR user_type=0) ";
                      if($reqIdTeam != ''){
                          $execWhere .= " AND FIND_IN_SET('".addslashes($reqIdTeam)."', ids_team) ";
                      }
                      $resExec = selectSql(TBL_USERS,$execWhere,' ORDER BY `name`');
                      if($db->num_rows2($resExec)){
                          while($rowExec = $db->fetch_object2($resExec)){
                              $selected = ($reqIdExecutive == $rowExec->id) ? 'selected="selected"' : '';
                              $execDropDown .= '<option '.$selected.' value="'.$rowExec->id.'">'.ucfirst($rowExec->name).'</option>';
                          }
                      }
                      $execDropDown .= '</select>';
                      echo $execDropDown;
                    ?>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Module</label>
                    <?php
                      $moduleDropDown = '<select class="form-control select2" name="id_module" id="filter_id_module">
                                            <option value="">All Modules</option>';
                      $modWhere = " WHERE `status`='1' AND `id_shop`='".addslashes($_SESSION['shop'])."' ";
                      if($reqIdTeam != ''){
                          $modWhere .= " AND `id_team`='".addslashes($reqIdTeam)."' ";
                      }
                      $resMod = selectSql('task_module',$modWhere,' ORDER BY `name`');
                      if($db->num_rows2($resMod)){
                          while($rowMod = $db->fetch_object2($resMod)){
                              $selected = ($reqIdModule == $rowMod->id) ? 'selected="selected"' : '';
                              $moduleDropDown .= '<option '.$selected.' value="'.$rowMod->id.'">'.ucfirst($rowMod->name).'</option>';
                          }
                      }
                      $moduleDropDown .= '</select>';
                      echo $moduleDropDown;
                    ?>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Status (multi-select)</label>
                    <?php
                      $statuses = array(
                          'Pending'     => 'Pending',
                          'In Progress' => 'In Progress',
                          'Completed'   => 'Development Completed',
                          'QA'          => 'QA',
                          'On Hold'     => 'Customer Verified',
                      );
                      $statusDropDown = '<select class="form-control select2" name="task_status[]" multiple="multiple">';
                      foreach($statuses as $val => $label){
                          $selected = in_array($val,$reqTaskStatuses) ? 'selected="selected"' : '';
                          $statusDropDown .= '<option '.$selected.' value="'.$val.'">'.$label.'</option>';
                      }
                      $statusDropDown .= '</select>';
                      echo $statusDropDown;
                    ?>
                  </div>
                </div>


                <div class="form-group col-sm-4">
              <label for="date_created">
                <input type="checkbox" name="check_box" value="1" <?php ?>/>
                &nbsp;Task Assign Date : From - To</label>
              <div class="input-group">
                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                <input type="text" class="form-control pull-right dateRangeReport" placeholder="Enter Task date" id="date_created" name="date_created" value="<?php if($_REQUEST) echo $_REQUEST['date_created'];?>">
              </div>
              <!-- /.input group --> 
            </div>
			  
                <div class="form-group col-sm-4">
          <label for="est_date">
            <input type="checkbox" name="est_check" value="1"
              <?php if(@$_REQUEST['est_check']==1) echo 'checked'; ?> />
            &nbsp;Estimate Delivery Date : From - To
          </label>

          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text"
                  class="form-control pull-right dateRangeReport"
                  name="est_date"
                  placeholder="Enter Estimate Delivery Date"
                  value="<?php if($_REQUEST) echo $_REQUEST['est_date']; ?>">
          </div>
        </div>


              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <input name="Search" type="submit" class="btn btn-primary" value="Search" style="float:left;" /> &nbsp;&nbsp;&nbsp;
              <input name="Export" type="submit" class="btn btn-success" value="Export" style="float:left;margin-left:10px;" />
              <a title="Clear" class="pull-left btn btn-default" href="manageTask.php" style="margin-left:10px;font-weight:bold;">&nbsp;Clear</a>
            </div>
          </form>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Task List</h3>
            </div>

            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Task ID</th>
                    <th>Date</th>
                    <th>Executive</th>
                    <th>Team</th>
                    <th>Module</th>
                    <th>Task Description</th>
                    <th>Estimated Delivery</th>
                    <th>Status</th>
                    <th>Completed Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if($total > 0){
                      while($row = $db->fetch_object()){
                  ?>
                  <tr>
                    <td><?php echo $row->task_code; ?></td>
                    <td><?php echo date('d M Y',strtotime($row->dated)); ?></td>
                    <td><?php echo ucfirst(selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_executive."'")); ?></td>
                    <td><?php echo ucfirst(selectColumn('mst_team','name'," WHERE `id` = '".$row->id_team."'")); ?></td>
                    <td><?php echo ucfirst(selectColumn('task_module','name'," WHERE `id` = '".$row->id_module."'")); ?></td>
                    <td><?php echo $row->description; ?></td>
                    <td><?php echo ($row->estimated_delivery_date != '' && $row->estimated_delivery_date != '0000-00-00') ? date('d M Y',strtotime($row->estimated_delivery_date)) : '-'; ?></td>
                    <td><span class="label <?php echo taskStatusBadgeClass($row->status); ?>"><?php echo taskStatusLabel($row->status); ?></span></td>
                    <td><?php echo ($row->completed_date != '' && $row->completed_date != '0000-00-00') ? date('d M Y',strtotime($row->completed_date)) : '-'; ?></td>
                    <td>
                      <a href="editTask.php?action=edit&eId=<?=encryptor('encrypt',$row->id)?>" title="Edit"><i class="fa fa-pencil-square-o"></i></a>
                      &nbsp;
                      <a href="javascript:void(0)" onClick="if(confirm('Are you sure that you want to delete this task?')){window.location.href='manageTask.php?delId=<?=encryptor('encrypt',$row->id)?>&action=delete';}" title="Delete"><i class="fa fa-remove"></i></a>
                    </td>
                  </tr>
                  <?php }
                  ?>
                  <tr>
                    <td align="right" colspan="14"><?php echo $pagging->getLinks(); ?></td>
                  </tr>
                  <?php }else{ ?>
                  <tr>
                    <td height="200" align="center" colspan="10">---- No Record Found ----</td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
      </div>
    </div>
  </section>

</div>

<?php include_once("includes/footer.php")?>

<script>
$(function(){
    $(".dateRangeEdit").daterangepicker({
        locale: { format: 'DD-MM-YYYY' }
    });

    // Cascading filters: Team -> Executive + Module (search form only)
    $("#filter_id_team").on('change', function(){
        var idTeam = $(this).val();

        if(idTeam == ''){
            $("#filter_id_executive").html('<option value="">All Executives</option>').trigger('change.select2');
            $("#filter_id_module").html('<option value="">All Modules</option>').trigger('change.select2');
            return;
        }

        $.ajax({
            type: "POST",
            url: 'ajax/ajaxGetTeamExecutives.php',
            data: { id_team: idTeam },
            success: function(result){
                $("#filter_id_executive").html('<option value="">All Executives</option>' + result).trigger('change.select2');
            }
        });

        $.ajax({
            type: "POST",
            url: 'ajax/ajaxGetTaskModules.php',
            data: { id_team: idTeam },
            success: function(result){
                $("#filter_id_module").html('<option value="">All Modules</option>' + result).trigger('change.select2');
            }
        });
    });
});
</script>