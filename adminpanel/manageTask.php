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

/////////////////////////////////////////////////////////////////////////////////////
// Delete a task item entirely (its whole history goes with it).
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){

	checkUserLevelPermission($_SESSION['userLevel'],'task','delete');

	$taskId = addslashes(encryptor('decrypt',$_REQUEST['delId']));

	executeSql("DELETE FROM `task_details` WHERE `task_id`='".$taskId."' ");

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
		 INNER JOIN (
			SELECT `task_id`, MAX(`id`) AS max_id FROM `task_details` GROUP BY `task_id`
		 ) L ON L.task_id = T.id
		 INNER JOIN `task_details` TD ON TD.id = L.max_id
		 WHERE T.id_shop = '".addslashes($_SESSION['shop'])."' ";

// ---- Permission scoping (simplified - extend like the old drilled_team logic if needed) ----
if($_SESSION['userLevel'] != '1'){
	$sql .= " AND (TD.id_executive = '".addslashes($_SESSION['userId'])."' OR T.created_by = '".addslashes($_SESSION['userId'])."') ";
}

// ---- Filters ----
if($_REQUEST['id_team'] != ''){
	$sql .= " AND T.id_team = '".addslashes($_REQUEST['id_team'])."' ";
}

if($_REQUEST['id_executive'] != ''){
	$sql .= " AND TD.id_executive = '".addslashes($_REQUEST['id_executive'])."' ";
}

if($_REQUEST['id_module'] != ''){
	$sql .= " AND T.id_module = '".addslashes($_REQUEST['id_module'])."' ";
}

if($_REQUEST['task_status'] != ''){
	$sql .= " AND TD.status = '".addslashes($_REQUEST['task_status'])."' ";
}

if($_REQUEST['task_date'] != ''){

	$task_date_range = explode(" to ",$_REQUEST['task_date']);
	$fromDate = $task_date_range[0];
	$toDate   = isset($task_date_range[1]) ? $task_date_range[1] : $task_date_range[0];

	$sql .= " AND DATE(T.dated) >= '".date('Y-m-d',strtotime($fromDate))."' AND DATE(T.dated) <= '".date('Y-m-d',strtotime($toDate))."' ";
}

$sql .= " ORDER BY T.id DESC ";

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
            <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);
            }elseif($_SESSION['successMsg']){?>
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
                              $selected = ($_REQUEST['id_team'] == $rowTeam->id) ? 'selected="selected"' : '';
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
                      if($_REQUEST['id_team'] != ''){
                          $execWhere .= " AND FIND_IN_SET('".addslashes($_REQUEST['id_team'])."', ids_team) ";
                      }
                      $resExec = selectSql(TBL_USERS,$execWhere,' ORDER BY `name`');
                      if($db->num_rows2($resExec)){
                          while($rowExec = $db->fetch_object2($resExec)){
                              $selected = ($_REQUEST['id_executive'] == $rowExec->id) ? 'selected="selected"' : '';
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
                      if($_REQUEST['id_team'] != ''){
                          $modWhere .= " AND `id_team`='".addslashes($_REQUEST['id_team'])."' ";
                      }
                      $resMod = selectSql('task_module',$modWhere,' ORDER BY `name`');
                      if($db->num_rows2($resMod)){
                          while($rowMod = $db->fetch_object2($resMod)){
                              $selected = ($_REQUEST['id_module'] == $rowMod->id) ? 'selected="selected"' : '';
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
                    <label>Status</label>
                    <?php
                      $statuses = array(
                          'Pending'     => 'Pending',
                          'In Progress' => 'In Progress',
                          'Completed'   => 'Development Completed',
                          'QA'          => 'QA',
                          'On Hold'     => 'Customer Verified',
                      );
                      $statusDropDown = '<select class="form-control select2" name="task_status"><option value="">All Status</option>';
                      foreach($statuses as $val => $label){
                          $selected = ($_REQUEST['task_status'] == $val) ? 'selected="selected"' : '';
                          $statusDropDown .= '<option '.$selected.' value="'.$val.'">'.$label.'</option>';
                      }
                      $statusDropDown .= '</select>';
                      echo $statusDropDown;
                    ?>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="task_date">Task Date : From - To</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                      <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select date range" id="task_date" name="task_date" value="<?php if($_REQUEST['task_date']) echo $_REQUEST['task_date'];?>">
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <input name="Search" type="submit" class="btn btn-primary" value="Search" style="float:left;" /> &nbsp;&nbsp;&nbsp;
              <a title="Clear" class="pull-left btn btn-success" href="manageTask.php" style="margin-left:10px;color:#fff;font-weight:bold;">&nbsp;Clear</a>
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
                    <td align="right" colspan="10"><?php echo $pagging->getLinks(); ?></td>
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