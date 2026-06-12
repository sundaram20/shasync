<?php
include_once("../../config/auto_loader.php");

$callId = intval($_GET['id'] ?? 0);
$list_id = intval($_GET['list']??0);
$currentFormat = $_GET['format'];

if ($callId <= 0) {
    echo '<div>Error: Invalid call ID</div>';
    exit;
}

$sql = "SELECT extra_data FROM `call_details` WHERE call_id = '$callId' ORDER BY id desc LIMIT 1";
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

$callDetailsSql = "SELECT cd.*, u1.name AS source_name, u2.name AS forwarded_to_name
                  FROM call_details cd
                  LEFT JOIN fs_users u1 ON cd.id_user = u1.id
                  LEFT JOIN fs_users u2 ON cd.assign_user_id = u2.id
                  WHERE cd.call_id = '$callId' AND cd.id_list_name = '$list_id' AND cd.format_type='$currentFormat'";
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
  ],
	'mau'=>[
		'serial'=>'Serial Number',
		'Product'=>'Product',
		'release'=>'Release',
		'expiry_date'=>'TSS Expiry Date',
		'company_name'=>'Org Name',
		'contact_person'=>'Contact Person',
		'mobile'=>'Mobile',
		'email'=>'Email ID',
		
	],
	'salesync'=>[
		'bill_no'=>'Bill No',
		'serial'=>'Serial Number',
		'Product'=>'Product',
		'contact_person'=> 'Contact Person',
		'email'=>'Email ID',
		'mobile'=>'Mobile',
		'Designation' => 'Designation',
		'company_name'=> 'Company Name',
		'lead_source'=>'Lead Source'
		
	],
	'webinar'=>[
		'email'=>'Email ID',
		'contact_person'=> 'Contact Person',
		'company_name'=> 'Company Name',
		'mobile'=>'Mobile',
		'phone'=>'Phone',
		'serial'=>'Serial Number'	
	],
	'aws'=>[
		'serial'=>'Serial Number',
		'company_name'=> 'Account Name',
		'instance_type'=> 'Instance Type',
		'cloud'=>'Cloud',
		'expiry_date'=>'Expiry Date',
		'created_date'=>'Created Date',
		'email'=>'Email',
		'mobile'=>'Mobile No.',
		'partner'=>'Partner'
	],
	'amc'=>[
		'serial'=>'Serial Number',
		'Product'=>'Product',
		'release'=>'Release',
		'expiry_date'=>'TSS Expiry Date',
		'company_name'=>'Org Name',
		'contact_person'=>'Contact Person',
		'mobile'=>'Mobile',
		'email'=>'Email ID',
		
	],
	'cocloud'=>[
		'sub_id'=>'Sub ID',
		'customer_name'=>'Customer Name',
		'mobile'=>'Mobile',
		'email'=>'Email ID',
		'customer_last_login' => 'Customer Last Login',
		'start_date' => 'Start Date',
		'last_renew_date' => 'Last Renew Date',
		'end_date'=>'End Date',
		'plan_name'=>'Plan Name',
		'plan_unit_price'=>'Plan Unit Price',
		'no_of_users'=>'No of Users',
		'sales_person_name'=>'Sales Person Name',
		'relationship_manager'=>'Relationship Manager',
		'duration'=>'Duration',
		'amount'=>'Amount',
		'status'=>'Status'
		
	],
];

$currentFormat = $_GET['format'] ?? 'tss';
$fieldsToShowInDrawer = $formatFields[$currentFormat] ?? [];

function formatExtraValue($key, $value) {
    if (in_array($key, ['mobile', 'email']) && is_array($value)) {
        return end($value);
    }
    return is_array($value) ? implode(', ', $value) : $value;
}
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
    <div class="button-container d-flex justify-content-end mb-3">
        <button class="addnum-btn btn btn-success" onclick="addPhonePop(<?php echo $callId; ?>)">
            <i class="fa fa-plus"></i> Phone/Email
        </button>
    </div>

    <?php $i=0; ?>
    <?php foreach ($fieldsToShowInDrawer as $field => $label): ?>
        <?php if($i % 3 == 0) echo '<div class="row mb-3">'; ?>

        <div class="col-md-4 position-relative">
            <label class="form-label"><?= htmlspecialchars($label) ?></label>
            <?php
                $rawValue = $extraData[$field] ?? '';
                $formatted = formatExtraValue($field, $rawValue);
                $isMulti = in_array($field, ['mobile', 'email','contact_person']) && is_array($rawValue);
            ?>
            <input type="text" 
                   class="form-control has-dropdown" 
                   id="drawer_<?= htmlspecialchars($field) ?>" 
                   value="<?= htmlspecialchars($formatted) ?>" 
                   readonly
                   <?= $isMulti ? 'data-values=\'' . json_encode($rawValue) . '\'' : '' ?> >
            <?php if ($isMulti): ?>
                <div class="dropdown-list"></div>
            <?php endif; ?>
        </div>

        <?php $i++; if ($i % 3 == 0) echo '</div>'; ?>
    <?php endforeach; ?>
    <?php if ($i % 3 != 0) echo '</div>'; ?>

    <div class="mb-3">
        <?php
            $lastCallDetail = end($callDetailsRows);
            $disabled = ($lastCallDetail['call_status'] == 0) ? 'disabled' : '';
            reset($callDetailsRows);
        ?>
        <label class="form-label">Assigned Call To</label><br>
        <button <?= $disabled ?> class="btn btn-success btn-sm"
    onclick="addEqyFollowUp1(<?= $callId ?>, <?= $list_id ?>, '<?= addslashes($currentFormat) ?>')"
    type="button" id="enqFollowUp">Assign Call</button>
    </div>

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
                    <td><?= !empty($callDetailsRow['followup_date']) && $callDetailsRow['followup_date'] !== '0000-00-00'
                            ? date('d-M-Y', strtotime($callDetailsRow['followup_date']))
                            : '' ?>
                    </td>
                    <td>
                        <?php if ($rowIndex > 1): ?>
                            <a id="dlt_<?= $id ?>" onclick="deleteCall(<?= $id?>,<?= $callId ?>,<?=$list_id?>)">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
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
