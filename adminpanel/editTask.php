<?php
include_once("../config/auto_loader.php");

// checkUserLevelPermission($_SESSION['userLevel'],'task','view');

/*
	Table names used directly as literals in this app (no TBL_* constants):
	task, task_details, mst_team, task_module.

	`task` = one row per task item (fixed at creation: date, team, module,
	description, task_code - never edited again).
	`task_details` = full history of that item (executive, status,
	estimated delivery, completed date, remark) - append-only; the row
	with the highest id is the current state.

	Add mode (no eId): the familiar bulk form - pick Date/Team/Executive
	once, add several Module/Description rows, each becomes its own
	independent task + initial task_details entry.

	Edit mode (eId present): a single task item. Module/Description/Date/
	Team are shown read-only. The Update form appends a new task_details
	row (can reassign the executive, change status, extend the delivery
	date, add a remark) - every save is kept as history below it.
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

$isEdit = false;
$taskRow = null;
$currentDetail = null;
$historyEntries = array();

if(!empty($_REQUEST['eId']) && $_REQUEST['action'] == 'edit'){

	$taskId = addslashes(encryptor('decrypt',$_REQUEST['eId']));

	$resTask = selectSql('task'," WHERE `id`='".$taskId."' AND `id_shop`='".addslashes($_SESSION['shop'])."' ",'');

	if($db->num_rows2($resTask)){
		$taskRow = $db->fetch_object2($resTask);
		$isEdit = true;
	}

	if($isEdit){

		$allDetailRows = array();
		$resDetails = selectSql('task_details'," WHERE `task_id`='".$taskId."' ",' ORDER BY `id` ASC');
		if($db->num_rows2($resDetails)){
			while($rowDetail = $db->fetch_object2($resDetails)){
				$allDetailRows[] = $rowDetail;
			}
		}

		if(empty($allDetailRows)){
			// shouldn't normally happen (every task gets an initial task_details row on creation)
			$_SESSION['errorMsg'] = 'This task has no details on record.';
			header('Location: manageTask.php');
			exit;
		}

		$currentDetail = end($allDetailRows);

		// only admin, the currently assigned executive, or the item's creator may edit
		if($_SESSION['userLevel'] != '1' && $currentDetail->id_executive != $_SESSION['userId'] && $taskRow->created_by != $_SESSION['userId']){
			$_SESSION['errorMsg'] = 'You do not have permission to edit this task.';
			header('Location: manageTask.php');
			exit;
		}

		// ---- Build the history feed (diff each entry against the one before it) ----
		$prev = null;
		foreach($allDetailRows as $d){

			$lines = array();
			$execName = ucfirst(selectColumn(TBL_USERS,'name'," WHERE `id`='".$d->id_executive."' "));

			if($prev === null){
				$deliveryTxt = ($d->estimated_delivery_date != '' && $d->estimated_delivery_date != '0000-00-00') ? date('d M Y',strtotime($d->estimated_delivery_date)) : 'not set';
				$lines[] = 'Task created, assigned to '.$execName.', status '.taskStatusLabel($d->status).', estimated delivery '.$deliveryTxt;
			} else {

				if($d->id_executive != $prev->id_executive){
					$prevExecName = ucfirst(selectColumn(TBL_USERS,'name'," WHERE `id`='".$prev->id_executive."' "));
					$lines[] = 'Reassigned from '.$prevExecName.' to '.$execName;
				}

				if($d->status != $prev->status){
					$lines[] = 'Status changed from '.taskStatusLabel($prev->status).' to '.taskStatusLabel($d->status);
				}

				if($d->estimated_delivery_date != $prev->estimated_delivery_date){
					$oldD = ($prev->estimated_delivery_date != '' && $prev->estimated_delivery_date != '0000-00-00') ? date('d M Y',strtotime($prev->estimated_delivery_date)) : '-';
					$newD = ($d->estimated_delivery_date != '' && $d->estimated_delivery_date != '0000-00-00') ? date('d M Y',strtotime($d->estimated_delivery_date)) : '-';
					$lines[] = 'Estimated delivery changed from '.$oldD.' to '.$newD;
				}
			}

			if(!empty($d->remark)){
				$lines[] = $d->remark;
			}

			if(empty($lines)){
				$lines[] = 'Updated';
			}

			$historyEntries[] = array(
				'lines' => $lines,
				'user'  => ucfirst(selectColumn(TBL_USERS,'name'," WHERE `id`='".$d->created_by."' ")),
				'when'  => date('d M Y, h:i A',strtotime($d->created_date)),
			);

			$prev = $d;
		}

		$historyEntries = array_reverse($historyEntries);
	}
}

// ---- Executives filtered to this task's team (edit mode) or all-blank-until-team-chosen (add mode) ----
$execOptionsHtml = '<option value="">Select Team First</option>';
$addModeTeamId = $isEdit ? '' : ''; // add mode always starts blank; JS fills this via team-change AJAX

if($isEdit){
	$execOptionsHtml = '<option value="">Select Executive</option>';
	$resExec = selectSql(TBL_USERS," WHERE `status`='1' AND `id_shop`='".addslashes($_SESSION['shop'])."' AND (user_type=1 OR user_type=0) AND FIND_IN_SET('".addslashes($taskRow->id_team)."', ids_team) ",' ORDER BY `name`');
	if($db->num_rows2($resExec)){
		while($rowExec = $db->fetch_object2($resExec)){
			$sel = ($rowExec->id == $currentDetail->id_executive) ? 'selected="selected"' : '';
			$execOptionsHtml .= '<option '.$sel.' value="'.$rowExec->id.'">'.ucfirst($rowExec->name).'</option>';
		}
	}
}

include_once("includes/header.php");
include_once("includes/left.php");
?>
<style>
.parsley-required { float:left; }
#taskDetailsTable td { vertical-align: middle; }
#taskDetailsTable .task-id-placeholder { color:#888; font-style: italic; }
#taskDetailsTable .completed-date-cell { display: none; }
.history-list { list-style:none; margin:0; padding:0; }
.history-list li { border-bottom:1px solid #eee; padding:10px 0; }
.history-list .history-when { float:right; font-size:11px; color:#888; }
</style>

<div class="content-wrapper">

  <section class="content-header">
    <h1> Task <small><?php echo $isEdit ? 'Edit Task' : 'Add / Assign Task'; ?></small></h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Task</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">

        <?php if($isEdit){ ?>

        <!-- ===================================== EDIT MODE: single item ===================================== -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Task <?php echo $taskRow->task_code; ?></h3>
          </div>
          <div class="box-body">
            <div class="form-group has-error" style="margin-bottom:15px;">
              <?php if($_SESSION['errorMsg']){?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']); ?></p>
                <?php unset($_SESSION['errorMsg']); ?>
              <?php } elseif($_SESSION['successMsg']){ ?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']); ?></p>
                <?php unset($_SESSION['successMsg']); ?>
              <?php } ?>
            </div>

            <div class="row">
              <div class="col-md-3"><strong>Date:</strong> <?php echo date('d M Y',strtotime($taskRow->dated)); ?></div>
              <div class="col-md-3"><strong>Team:</strong> <?php echo ucfirst(selectColumn('mst_team','name'," WHERE `id`='".$taskRow->id_team."' ")); ?></div>
              <div class="col-md-6"><strong>Module:</strong> <?php echo ucfirst(selectColumn('task_module','name'," WHERE `id`='".$taskRow->id_module."' ")); ?></div>
            </div>
            <div class="row" style="margin-top:10px;">
              <div class="col-md-12">
                <strong>Description:</strong><br>
                <?php echo nl2br(htmlspecialchars($taskRow->description)); ?>
              </div>
            </div>
          </div>
        </div>

        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Update</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="form-group col-md-3">
                <label>Executive</label>
                <select class="form-control select2" id="update_executive">
                  <?php echo $execOptionsHtml; ?>
                </select>
              </div>
              <div class="form-group col-md-3">
                <label>Status</label>
                <select class="form-control" id="update_status">
                  <option value="Pending" <?php echo ($currentDetail->status=='Pending')?'selected="selected"':''; ?>>Pending</option>
                  <option value="In Progress" <?php echo ($currentDetail->status=='In Progress')?'selected="selected"':''; ?>>In Progress</option>
                  <option value="Completed" <?php echo ($currentDetail->status=='Completed')?'selected="selected"':''; ?>>Development Completed</option>
                  <option value="QA" <?php echo ($currentDetail->status=='QA')?'selected="selected"':''; ?>>QA</option>
                  <option value="On Hold" <?php echo ($currentDetail->status=='On Hold')?'selected="selected"':''; ?>>Customer Verified</option>
                </select>
              </div>
              <div class="form-group col-md-3">
                <label>Estimated Delivery</label>
                <?php $currentDeliveryFmt = ($currentDetail->estimated_delivery_date != '' && $currentDetail->estimated_delivery_date != '0000-00-00') ? date('d-m-Y',strtotime($currentDetail->estimated_delivery_date)) : ''; ?>
                <input type="text" class="form-control datepickertest" id="update_estimated_delivery" value="<?php echo $currentDeliveryFmt; ?>" placeholder="dd-mm-yyyy">
              </div>
              <div class="form-group col-md-3" id="update_completed_date_group" style="<?php echo ($currentDetail->status=='Completed') ? '' : 'display:none;'; ?>">
                <label>Completed Date</label>
                <?php $currentCompletedFmt = ($currentDetail->completed_date != '' && $currentDetail->completed_date != '0000-00-00') ? date('d-m-Y',strtotime($currentDetail->completed_date)) : ''; ?>
                <input type="text" class="form-control datepickertest" id="update_completed_date" value="<?php echo $currentCompletedFmt; ?>" placeholder="dd-mm-yyyy">
              </div>
            </div>
            <div class="form-group">
              <label>Remark (optional)</label>
              <textarea class="form-control" id="update_remark_text" rows="2" placeholder="What's new on this task?"></textarea>
            </div>
            <button type="button" class="btn btn-primary" onclick="SaveTaskUpdate();">Save Update</button>
            &nbsp;
            <a href="manageTask.php" class="btn btn-default">Back to List</a>
            &nbsp; <span id="ajaxMsg" style="display:none;color:red;">Please Wait...</span>
          </div>
        </div>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">History</h3>
          </div>
          <div class="box-body">
            <?php if(!empty($historyEntries)){ ?>
            <ul class="history-list">
              <?php foreach($historyEntries as $h){ ?>
              <li>
                <strong><?php echo $h['user']; ?></strong>
                <span class="history-when"><?php echo $h['when']; ?></span>
                <div style="clear:both;margin-top:4px;">
                  <?php echo implode('<br>', array_map('htmlspecialchars', $h['lines'])); ?>
                </div>
              </li>
              <?php } ?>
            </ul>
            <?php } else { ?>
            <p class="text-muted">No history yet.</p>
            <?php } ?>
          </div>
        </div>

        <?php } else { ?>

        <!-- ===================================== ADD MODE: bulk creation form ===================================== -->
        <div class="box box-primary">

          <div class="box-header with-border">
            <h3 class="box-title">Add Task</h3>
          </div>

          <form name="taskForm" id="taskForm" method="post" role="form" data-parsley-validate autocomplete="off">

            <div class="form-group has-error" style="margin:10px 15px 0;">
              <?php if($_SESSION['errorMsg']){?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']); ?></p>
                <?php unset($_SESSION['errorMsg']); ?>
              <?php } elseif($_SESSION['successMsg']){ ?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']); ?></p>
                <?php unset($_SESSION['successMsg']); ?>
              <?php } ?>
            </div>

            <div class="box-body">

              <!-- ===================== Common fields ===================== -->
              <div class="row">

                <div class="form-group col-md-3">
                  <label for="task_date">Date <font color="#FF0000">*</font></label>
                  <input type="text" class="form-control datepickertest" id="task_date" name="task_date"
                         value="<?php echo date('d-m-Y'); ?>" data-parsley-required readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="id_team">Team Group <font color="#FF0000">*</font></label>
                  <select class="form-control select2" name="id_team" id="id_team" data-parsley-required>
                    <option value="">Select Team</option>
                    <?php
                      $resTeam = selectSql('mst_team'," WHERE `status`='1' AND `id_shop`='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                      if($db->num_rows2($resTeam)){
                          while($rowTeam = $db->fetch_object2($resTeam)){
                              echo '<option value="'.$rowTeam->id.'">'.ucfirst($rowTeam->name).'</option>';
                          }
                      }
                    ?>
                  </select>
                </div>

                <div class="form-group col-md-4">
                  <label for="id_executive">Assign To (Executive) <font color="#FF0000">*</font></label>
                  <select class="form-control select2" name="id_executive" id="id_executive" data-parsley-required>
                    <option value="">Select Team First</option>
                  </select>
                </div>

              </div>
              <!-- =================== /Common fields ======================= -->

              <hr>

              <!-- =================== Task detail rows ====================== -->
              <div class="table-responsive">
                <table class="table table-bordered" id="taskDetailsTable">
                  <thead>
                    <tr style="background-color:#f4f4f4;">
                      <th style="width:8%;">Task ID</th>
                      <th style="width:16%;">Module</th>
                      <th style="width:26%;">Task Description</th>
                      <th style="width:13%;">Estimated Delivery</th>
                      <th style="width:13%;">Status</th>
                      <th style="width:13%;">Completed Date</th>
                      <th style="width:5%;"></th>
                    </tr>
                  </thead>
                  <tbody id="taskDetailsBody">
                    <tr class="task-row">
                      <td><span class="task-id-placeholder">Auto</span></td>
                      <td>
                        <select class="form-control module-select" name="module[]" data-parsley-required>
                          <option value="">Select Team First</option>
                        </select>
                      </td>
                      <td>
                        <textarea class="form-control" name="description[]" rows="1" placeholder="Task description" data-parsley-required></textarea>
                      </td>
                      <td>
                        <input type="text" class="form-control datepickertest" name="estimated_delivery[]" placeholder="dd-mm-yyyy" data-parsley-required>
                      </td>
                      <td>
                        <select class="form-control status-select" name="task_status[]">
                          <option value="Pending">Pending</option>
                          <option value="In Progress">In Progress</option>
                          <option value="Completed">Development Completed</option>
                          <option value="QA">QA</option>
                          <option value="On Hold">Customer Verified</option>
                        </select>
                      </td>
                      <td class="completed-date-cell">
                        <input type="text" class="form-control datepickertest completed-date-input" name="completed_date[]" placeholder="dd-mm-yyyy">
                      </td>
                      <td>
                        <button type="button" class="btn btn-danger btn-sm removeRowBtn" title="Remove row"><i class="fa fa-remove"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <button type="button" id="addRowBtn" class="btn btn-success btn-sm">
                  <i class="fa fa-plus"></i>&nbsp;Add Task
                </button>
              </div>
              <!-- =================== /Task detail rows ===================== -->

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <input type="button" value="Save" class="btn btn-primary" onclick="SaveTask();">
              &nbsp;&nbsp;
              <input type="button" value="Cancel" class="btn btn-default" onclick="location.replace('manageTask.php');">
              &nbsp; <span id="ajaxMsg" style="display:none;color:red;">Please Wait...</span>
            </div>

          </form>

        </div>

        <?php } ?>

      </div>
    </div>
  </section>

</div>

<?php include_once("includes/footer.php"); ?>

<?php if($isEdit){ ?>
<script>
$(function(){
    $(".datepickertest").datepicker({ dateFormat: 'dd-mm-yy' });

    $( document ).ajaxStart(function(){ $("#ajaxMsg").show(); });
    $( document ).ajaxStop(function(){ $("#ajaxMsg").hide(); });

    $("#update_status").on('change', function(){
        if($(this).val() == 'Completed'){
            $("#update_completed_date_group").show();
        } else {
            $("#update_completed_date_group").hide();
        }
    });
});

function SaveTaskUpdate(){
    var executive = $('#update_executive').val();
    var status = $('#update_status').val();
    var estDelivery = $('#update_estimated_delivery').val();
    var completedDate = $('#update_completed_date').val();
    var remark = $('#update_remark_text').val().trim();

    if(executive == ''){
        alert('Please select an Executive.');
        return false;
    }
    if(estDelivery == ''){
        alert('Please choose an Estimated Delivery date.');
        return false;
    }
    if(status == 'Completed' && completedDate == ''){
        alert('Please enter the Completed Date.');
        return false;
    }

    $.ajax({
        type: "POST",
        url: 'ajax/ajaxUpdateTaskDetail.php',
        data: {
            task_id: '<?php echo $_REQUEST['eId']; ?>',
            id_executive: executive,
            status: status,
            estimated_delivery: estDelivery,
            completed_date: completedDate,
            remark: remark
        },
        success: function(result){
            if(result == '4'){
                alert('You do not have permission to update this task.');
                return false;
            }
            if(result == '3'){
                alert('Task not found.');
                return false;
            }
            if(result == '7'){
                alert('Please check the values and try again.');
                return false;
            }
            location.reload();
        }
    });
}
</script>
<?php } else { ?>
<script>
var currentModuleOptions = '<option value="">Select Team First</option>';

$(function(){
    $(".datepickertest").datepicker({ dateFormat: 'dd-mm-yy', minDate: 0 });

    $( document ).ajaxStart(function(){ $("#ajaxMsg").show(); });
    $( document ).ajaxStop(function(){ $("#ajaxMsg").hide(); });

    // ---- Team changed -> reload Executive list + Modules for ALL rows ----
    $("#id_team").on('change', function(){
        var idTeam = $(this).val();

        if(idTeam == ''){
            currentModuleOptions = '<option value="">Select Team First</option>';
            $(".module-select").html(currentModuleOptions);
            $("#id_executive").html('<option value="">Select Team First</option>').trigger('change.select2');
            return;
        }

        $.ajax({
            type: "POST",
            url: 'ajax/ajaxGetTeamExecutives.php',
            data: { id_team: idTeam },
            success: function(result){
                $("#id_executive").html(result).trigger('change.select2');
            }
        });

        $.ajax({
            type: "POST",
            url: 'ajax/ajaxGetTaskModules.php',
            data: { id_team: idTeam },
            success: function(result){
                currentModuleOptions = result;
                $(".module-select").html(currentModuleOptions).trigger('change.select2');
            }
        });
    });

    // ---- Status changed -> show/hide Completed Date for that row ----
    $("#taskDetailsBody").on('change', '.status-select', function(){
        var $row = $(this).closest('tr');
        var $dateCell = $row.find('.completed-date-cell');

        if($(this).val() == 'Completed'){
            $dateCell.show();
        } else {
            $dateCell.hide();
            $dateCell.find('.completed-date-input').val('');
        }
    });

    // ---- Add a new task row by cloning the first one ----
    $("#addRowBtn").on('click', function(){
        var $newRow = $("#taskDetailsBody .task-row:first").clone();

        $newRow.find('input[type=text]').val('');
        $newRow.find('textarea').val('');
        $newRow.find('select').prop('selectedIndex', 0);
        $newRow.find('.task-id-placeholder').text('Auto');
        $newRow.find('.module-select').html(currentModuleOptions);
        $newRow.find('.completed-date-cell').hide();

        $("#taskDetailsBody").append($newRow);
        $newRow.find('.datepickertest').datepicker({ dateFormat: 'dd-mm-yy', minDate: 0 });
    });

    // ---- Remove a row (keep at least one) ----
    $("#taskDetailsBody").on('click', '.removeRowBtn', function(){
        if($("#taskDetailsBody .task-row").length > 1){
            $(this).closest('tr').remove();
        } else {
            alert('At least one task row is required.');
        }
    });
});

function SaveTask(){
    var form = $("#taskForm");

    if(form.parsley().validate()){

        var missingCompletedDate = false;
        $("#taskDetailsBody .task-row").each(function(){
            var status = $(this).find('.status-select').val();
            var completedDate = $(this).find('.completed-date-input').val();
            if(status == 'Completed' && completedDate == ''){
                missingCompletedDate = true;
            }
        });

        if(missingCompletedDate){
            alert('Please enter the Completed Date for tasks marked as "Development Completed".');
            return false;
        }

        $.ajax({
            type: "POST",
            url: 'ajax/ajaxUpdateTask.php',
            data: form.serialize(),
            success: function(result){
                if(result == 7){
                    alert('Please fill all required fields for each task row.');
                    return false;
                }
                alert('Task(s) saved successfully.');
                window.location.href = 'manageTask.php';
            }
        });
        return false;
    }
}
</script>
<?php } ?>