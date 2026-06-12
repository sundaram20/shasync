<?php
include_once("../../config/auto_loader.php");

$daily_pickup_id = intval($_GET['id'] ?? 0);


if ($daily_pickup_id <= 0) {
    echo '<div>Error: Invalid Pickup ID '.$dailyPickupId.' </div>';
    exit;
}

//$sql = "SELECT extra_data FROM `call_details` WHERE call_id = '$callId' ORDER BY id desc LIMIT 1";

$sql = "SELECT dp.id_company, dp.id_contacts, dpd.serial_number, dpd.id_product
FROM daily_pickup AS dp
LEFT JOIN daily_pickup_details AS dpd 
  ON dp.id = dpd.id_daily_pickup
WHERE dp.id = '$daily_pickup_id'";

$res = mysqli_query($connNew, $sql);
if (!$res) {
    echo '<div>Error: Query failed - ' . htmlspecialchars(mysqli_error($connNew)) . '</div>';
    exit;
}

$details=mysqli_fetch_assoc($res);


if (!$details) {
    echo '<div>Error: No data found for this Pickup</div>';
    exit;
}

$supportDetailSql = "SELECT id_user, date_created, assign_user_id, internal_remark, support_remark, support_status, followup_date FROM support_details WHERE id_daily_pickup = '$daily_pickup_id'";

$supportDetailsRes = mysqli_query($connNew, $supportDetailSql);
if (!$supportDetailsRes) {
    echo '<div>Error: Support details query failed - ' . htmlspecialchars(mysqli_error($connNew)) . '</div>';
    exit;
}

$supportDetailsRows = [];
while ($row = mysqli_fetch_assoc($supportDetailsRes)) {
    $supportDetailsRows[] = $row;
}
$hasSupportDetails = !empty($supportDetailsRows);

?>

<!-- Drawer content starts -->
<button class="close-btn" onclick="closeDrawer()">X</button>

<style>
.button-container {
    display: flex;
    justify-content: flex-end;
    width: 100%;
}
.dropdown-list {
    position: absolute;
    background: #fff;
    border: 1px solid #ccc;
    z-index: 10;
    width: 100%;
    max-height: 150px;
    overflow-y: auto;
    display: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.dropdown-list div {
    padding: 5px 10px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}
</style>

<div class="content" style="margin-top: 50px;">
	<form id='support_form'>
	<div class=row>
    
	<div class="col-md-4">
		<div class="form-group">
			<input type="hidden" name="support_id" value="<?=$daily_pickup_id?>" >
			<label for="createDate">Support Date</label>
			<input type="text" class="form-control pickerdate_addreport" id="createDate" name="createDate" value="<?php echo date('Y-m-d'); ?>" readonly>

		</div>
	</div>
	
	<!-- Supporting By -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="support_by">Supporting By</label>
						<input type="hidden" name="id_user" value="<?=$_SESSION['userId']?>">
                      <input readonly type="text" name="support_by" id="support_by" value="<?php echo selectColumn('fs_users','name','WHERE id = "'.$_SESSION['userId'].'"') ?>" class="form-control" />
                    </div>
                  </div>

                  <!-- Company Name -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="company_name">Company Name</label>
						<?php
$companyId = $details['id_company'];
$companyName = selectColumn('fs_company', 'name', 'WHERE id_company = "'.$companyId.'"');
?>
						<input type="hidden" name="company_id" value="<?=$companyId?>">
                      <input readonly type="text" name="company_name" id="company_name" value="<?php echo htmlspecialchars($companyName); ?>" class="form-control" />
                    </div>
                  </div>

                  <!-- Person Met -->
                  <!--<div class="col-md-6">
                    <div class="form-group">
                      <label for="id_contacts">Contact Person</label>
                      <div class="input-group" id="showbookedby">
                        <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError" data-parsley-required onChange="ContactEditEnable();">
                          <option value="">Select Person Met</option>
							<?php
							$companyId = $details['id_company'];
echo $con_sql = "SELECT id_customer, first_name, last_name, email, mobile FROM fs_customer WHERE id_company = '$companyId'";
							$res = mysqli_query($connNew, $con_sql);
							
							while($row= mysqli_fetch_object($res)){
							echo '<option value="'.$row->id_customer.'">'.$row->first_name.' '.$row->last_name.' | '.$row->mobile.' | '.$row->email.'</option>';
							}
							
							?>
                        </select>
                        <div id="EditContactName" class="input-group-addon bookedby_open"><i class="fa fa-pencil"></i></div>
                        <div id="addCon" class="input-group-addon bookedby_open"><i class="fa fa-plus"></i></div>
                      </div>
                      <span id="contactError"></span>
                    </div>
                  </div>-->
		
		<!-- Company Name -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="company_name">Contact Person</label>
						<?php
$contactId = $details['id_contacts'];
$con_sql = "SELECT id_customer, first_name, last_name, email, mobile 
            FROM fs_customer 
            WHERE id_customer = '$contactId' 
            LIMIT 1";
$res = mysqli_query($connNew, $con_sql);
$row = mysqli_fetch_object($res);
?>
<input type="hidden" name="id_contacts" value="<?php echo $contactId; ?>" />
<input readonly type="text" name="contact_person_display" value="<?php echo htmlspecialchars($row->first_name . ' ' . $row->last_name . ' | ' . $row->mobile); ?>" class="form-control" />
                    </div>
                  </div>
					
					<div class="col-md-4">
                    <div class="form-group">
                      <label for="company_name">Serial No</label>
                      <input readonly type="text" name="serial_number" id="serial_number" value="<?php echo htmlspecialchars($details['serial_number']); ?>" class="form-control" />
                    </div>
                  </div>
					
					<div class="col-md-4">
                    <div class="form-group">
                      <label for="company_name">Product</label>
						<?php
						$item_name =  selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$details['id_product']."'");
						?>
						<input readonly type="hidden" name="product_id" id="company_name" value="<?=$details['id_product']?>" class="form-control" />
                      <input readonly type="text" name="product_name" id="company_name" value="<?=$item_name?>" class="form-control" />
                    </div>
                  </div>
	
<!--<div class="col-md-12">
  <div class="form-group">
    <label for="remarks">Remarks / Notes</label>
    <textarea class="form-control" name="remarks" id="remarks" rows="4" placeholder="Enter Support remarks here..."></textarea>
  </div>
</div>-->
		
	</div>
	</form>
	
	<button class='btn btn-success btn-sm' onclick='addSupportFollowup(<?php echo $daily_pickup_id?>)'>Assign Support</button>
	
	<div class="table-responsive" style="display: <?= $hasSupportDetails ? 'block' : 'none' ?>;">
		<div class="m-2"><h3>Support Records</h3></div>
		<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Support Date</th>
                    <th>Supported by</th>
                    <th>Forwarded To</th>
                    <th>Internal Remark</th>
                    <th>Support Remark</th>
                    <th>Follow Up Date</th>
					<th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowIndex = 0;
                foreach ($supportDetailsRows as $supportDetailsRow):
                    $rowIndex++;
                    $id = $supportDetailsRow['id'];
                ?>
                <tr>
                    <td><?= htmlspecialchars(date('d-M-Y', strtotime($supportDetailsRow['date_created'])) ?? '0000-00-00') ?></td>
                    <td><?php echo selectColumn('fs_users','name', 'WHERE id = "'.$supportDetailsRow['id_user'].'" '); ?></td>
                    <td><?php echo selectColumn('fs_users','name', 'WHERE id = "'.$supportDetailsRow['assign_user_id'].'" '); ?></td>
                    <td><?= htmlspecialchars($supportDetailsRow['internal_remark'] ?? '') ?></td>
                    <td><?= htmlspecialchars($supportDetailsRow['support_remark'] ?? '') ?></td>
                    <td><?= !empty($supportDetailsRow['followup_date']) && $supportDetailsRow['followup_date'] !== '0000-00-00'
                            ? date('d-M-Y', strtotime($supportDetailsRow['followup_date']))
                            : '' ?>
                    </td>
					<td style="color:<?php echo ($supportDetailsRow['support_status']==0) ? 'red':'';  ?>"><?php echo ($supportDetailsRow['support_status'] == 1) ? 'Open' : 'Close' ?></td>
                    <td>
                        <?php if ($rowIndex > 1): ?>
                            <!--<a id="dlt_<?= $id ?>" onclick="deleteCall(<?= $id?>,<?= $callId ?>,<?=$list_id?>)">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>-->
						
						
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
	</div>
    
</div>

<script>
document.querySelectorAll('.has-dropdown').forEach(input => {
    const dropdown = input.nextElementSibling;

    input.addEventListener('focus', () => {
        const data = input.getAttribute('data-values');
        if (!data || !dropdown) return;

        try {
            const list = JSON.parse(data);
            if (!Array.isArray(list)) return;
            dropdown.innerHTML = list.map(item => `<div>${item}</div>`).join('');
            dropdown.style.display = 'block';
        } catch (e) {}
    });

    input.addEventListener('blur', () => {
        setTimeout(() => {
            if (dropdown) dropdown.style.display = 'none';
        }, 200); // Let user view for a bit
    });
});
</script>
<!-- Drawer content ends -->
