<?php
include_once("../../config/auto_loader.php");

$callId = intval($_GET['id'] ?? 0);

if ($callId <= 0) {
    echo '<div>Error: Invalid call ID</div>';
    exit;
}

$sql = "SELECT extra_data FROM `call` WHERE id = '$callId' LIMIT 1";
$res = mysqli_query($connNew, $sql);
if (!$res) {
    echo '<div>Error: Query failed - ' . htmlspecialchars(mysqli_error($connNew)) . '</div>';
    exit;
}
$row = mysqli_fetch_assoc($res);
if (!$row) {
    echo '<div>Error: No data found for this call</div>';
    exit;
}

$extraData = json_decode($row['extra_data'], true) ?? [];


/*/////////////////////////////////////////////////////////////////////*/

//$callDetailsSql = "SELECT * 
     //             FROM call_details WHERE call_id = '$callId'";
//$callDetailsRes = mysqli_query($connNew, $callDetailsSql);
//$callDetailsRows = [];
//while ($row = mysqli_fetch_assoc($callDetailsRes)) {
 //   $callDetailsRows[] = $row;
//}
//$hasCallDetails = !empty($callDetailsRows);


// Fetch call_details with user names
$callDetailsSql = "SELECT cd.*, u1.name AS source_name, u2.name AS forwarded_to_name
                  FROM call_details cd
                  LEFT JOIN fs_users u1 ON cd.id_user = u1.id
                  LEFT JOIN fs_users u2 ON cd.assign_user_id = u2.id
                  WHERE cd.call_id = '$callId'";


$callDetailsRes = mysqli_query($connNew, $callDetailsSql);

if (!$callDetailsRes) {
    echo '<div>Error: Call details query failed - ' . htmlspecialchars(mysqli_error($connNew)) . '</div>';
    exit;
}
$callDetailsRows = [];

while ($row = mysqli_fetch_assoc($callDetailsRes)) {
    $callDetailsRows[] = $row;
}
$hasCallDetails = !empty($callDetailsRows);

////////////////////////////////////////////////////////////
// You can define this if not already available
$formatFields = [
  'tss' => [
    'serial' => 'Serial',
    'expiry_date' => 'Expiry Date',
    'flavour' => 'Flavour',
    'release' => 'Release',
	  'email'=> 'Email ID',
	  'mobile' => 'Mobile',
    'account_id' => 'Account ID',
    'admin_id' => 'Admin ID',
    'city' => 'City',
    'state' => 'State',
    'pin_code' => 'Pin Code',
    'contact_person' => 'Contact Person',
    'landline' => 'Landline'
  ]
  // Add more if needed
];

$currentFormat = $_GET['format'] ?? 'tss'; // default
$fieldsToShowInDrawer = $formatFields[$currentFormat] ?? [];

?>

<!-- Drawer content starts -->
<button class="close-btn" onclick="closeDrawer()">X</button>

<style>
    .button-container {
        display: flex;
        justify-content: flex-end;
        width: 100%;
    }
</style>

<div class="content" style="margin-top: 50px;">
    <div class="button-container d-flex justify-content-end mb-3">
        <button class="addnum-btn btn btn-success" onclick="addPhonePop(<?php echo $callId; ?>)">
            <i class="fa fa-plus"></i> Phone/Email
        </button>
    </div>
	
	<?php $i=0; ?>
  <?php foreach ($fieldsToShowInDrawer as $field => $label): ?>
    <?php if($i % 3 == 0) echo '<div class="row mb-3">'; ?>
	
      <div class="col-md-4">
        <label class="form-label"><?= htmlspecialchars($label) ?></label>
        <input type="text" class="form-control" id="drawer_<?= htmlspecialchars($field) ?>"
          value="<?= htmlspecialchars($extraData[$field] ?? '') ?>" readonly>
      </div>
	<?php $i++; 
	if ($i % 3 == 0) echo '</div>';
	?>
  <?php endforeach; ?>
	
	<?php if ($i % 3 != 0) echo '</div>'; ?>


 <div class="mb-3">
	 <?php
	 	$lastCallDetail = end($callDetailsRows);
		$disabled = ($lastCallDetail['call_status'] == 0) ? 'disabled' : '';
		// Reset pointer (optional)
		reset($callDetailsRows);
	 
	 ?>
        <label class="form-label">Assigned Call To</label><br>
        <button <?= $disabled ?> class="btn btn-success btn-sm" onclick="addEqyFollowUp1(<?php echo $callId; ?>);" type="button" id="enqFollowUp">Assign Call</button>
    </div>
	
	  <!-- Call Details Table -->
  <div class="table-responsive" style="display: <?= $hasCallDetails ? 'block' : 'none' ?>;">
	  <div class="m-2"><h3>Records</h3></div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Source</th>
          <th>Generation Date</th>
          <th>Forwarded To</th>
          <th>Internal Remark</th>
          <th>Call Remark</th>
          <th>Follow Up Date</th>
          <!--<th>Status</th>-->
			<th>Action</th>
        </tr>
      </thead>
      <tbody>  
        <?php
			$rowIndex = 0;
			foreach ($callDetailsRows as $callDetailsRow): 
		  	$rowIndex++;
				$id = $callDetailsRow['id'];
		  ?>
		  		
          <tr>
            <td><?= htmlspecialchars($callDetailsRow['source_name'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($callDetailsRow['created_at'] ?? '') ?></td>
                        <td><?= htmlspecialchars($callDetailsRow['forwarded_to_name'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($callDetailsRow['internal_remark'] ?? '') ?></td>
                        <td><?= htmlspecialchars($callDetailsRow['call_remark'] ?? '') ?></td>
			  			<td>
							<?= !empty($callDetailsRow['followup_date']) && $callDetailsRow['followup_date'] !== '0000-00-00' 
      ? date('d-M-Y', strtotime($callDetailsRow['followup_date'])) 
      : '' ?>
			  			</td>
                        
			  <!--<td class="<?= $callDetailsRow['call_status'] == 1 ? 'text-success' : 'text-danger' ?>">
  					<?= isset($callDetailsRow['call_status']) ? ($callDetailsRow['call_status'] == 1 ? 'Open' : 'Close') : '' ?>
			 </td>-->
			  <td>
			  <?php if ($rowIndex > 1): ?>
			  <a id="dlt_<?= $id ?>" onclick="deleteCall(<?= $id ?>)">
              <i class="fa fa-trash" aria-hidden="true"></i>
            </a>
          <?php endif; ?>
			  
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
		
	
	 
</div>
<!-- Drawer content ends -->