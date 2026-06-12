<?php
include_once("../../config/auto_loader.php");

$start         = $_REQUEST['start'];
$end           = $_REQUEST['end'];
$hotelId       = $_REQUEST['hotelId'];
$Admin_user_id = $_REQUEST['Admin_user_id'];

$events = array();

$shop = addslashes($_SESSION['shop']);
$uid  = addslashes($Admin_user_id);

// FIX 1: UserLevel fetched ONCE before all loops (was inside the loop before)
$UserLevel = selectColumn(
    TBL_USERS,
    'user_level',
    " WHERE `status`='1' AND `id_shop`='$shop' AND `id`='$uid'"
);

// FIX 2: Max-id for open enquiries built ONCE before all loops.
// Old code scanned all 18829 rows in PHP with one selectColumn() per row = 18829+ queries.
// New code: one SQL query, scoped to this shop + open leads only.
$resMaxId = executeSql("
    SELECT MAX(id) AS MaxId
    FROM `fs_enquiry_details`
    WHERE `id_shop`='$shop' AND `lead_status`='1'
    GROUP BY `enquiry_id`
");
$maxIds = array();
while ($rowMax = $db->fetch_assoc2($resMaxId)) {
    $maxIds[] = (int)$rowMax['MaxId'];
}
$rowImpMaxId = !empty($maxIds) ? implode(',', $maxIds) : '0';

$resState2 = executeSql("SELECT * FROM `" . TBL_DAILY_LEAD_TYPE . "` ORDER BY id ASC");

if (num_rows($resState2) > 0) {
    while ($row22 = $db->fetch_assoc2($resState2)) {

        $Table           = $row22['table_name'];
        $type            = $row22['id'];
        $DatedLabel      = 'dated';
        $UseType         = '';
        $FileName        = 'addreport.php';
        $IncentiveActive = '';

        switch ((int)$type) {
            case 1: $UseType = "AND type!=2 AND enquiry_details=1"; break;
            case 2: $UseType = "AND type=2"; break;
            case 3: $DatedLabel = 'followup_date'; $UseType = "AND type=3"; break;
            case 4: $UseType = "AND type=4 AND doc_id=0"; $FileName = 'editEnquiry.php'; break;
            case 5: $UseType = "AND type=5 AND doc_id=0"; $FileName = 'editQuote.php'; break;
            case 7: $UseType = "AND type='7' AND status='1'"; $FileName = 'calls.php'; $IncentiveActive = '1'; break;
            case 8: $UseType = "AND type='8' AND status='1'"; $FileName = 'ManagerDailyPickupItemWise.php'; break;
        }

        // Per-user filter — plain version for fs_daily_calender (no joins, no ambiguity)
        $UserAssignId = '';
        // f.-prefixed version for UNION queries where multiple joined tables share the column name
        $UserAssignIdJoin = '';
        if ($UserLevel == '1') {
            if (in_array((string)$type, array('2','3','7'))) {
                $UserAssignId     = " AND `id_user`='$uid'";
                $UserAssignIdJoin = " AND f.`id_user`='$uid'";
            } elseif (in_array((string)$type, array('1','8'))) {
                $UserAssignId     = " AND `assign_user_id`='$uid'";
                $UserAssignIdJoin = " AND f.`assign_user_id`='$uid'";
            }
        } else {
            if ($type == 3) {
                $UserAssignId     = " AND `id_user`='$uid'";
                $UserAssignIdJoin = " AND f.`id_user`='$uid'";
            } else {
                $UserAssignId     = " AND `assign_user_id`='$uid'";
                $UserAssignIdJoin = " AND f.`assign_user_id`='$uid'";
            }
        }

        // One grouped row per date in the requested range
        $resState = executeSql(
            "SELECT * FROM `fs_daily_calender`
              WHERE status='1' AND `id_shop`='$shop'
                AND $DatedLabel BETWEEN '" . addslashes($start) . "' AND '" . addslashes($end) . "'
                $UseType $UserAssignId
              GROUP BY $DatedLabel"
        );

        if (num_rows($resState) < 1) continue;

        while ($row = $db->fetch_assoc2($resState)) {

            $dated = addslashes($row[$DatedLabel]);

            switch ((int)$type) {

                // ── Type 1: leads from 4 source tables ───────────────────────────
                // FIX 3: contact person, mobile, lead source all joined in — zero per-row queries
                case 1:
                    $resSql123 = executeSql("
                        (SELECT f.id, f.type, f.dated, f.lead_status, f.visit_id, f.hotel_id,
                                f.assign_user_id, f.visit_id AS enquiry_ref,
                                'fs_follow_up_details' AS TableName,
                                h.name AS hotel_name, h.city AS hotel_city,
                                c.name AS company_name,
                                u.name AS assigned_user_name,
                                CONCAT(cu.first_name,' ',cu.last_name) AS contact_person_name,
                                cu.mobile AS contact_mobile,
                                ls.name AS lead_source_name
                         FROM `fs_follow_up_details` f
                         LEFT JOIN `" . TBL_HOTELS . "` h    ON h.id = f.hotel_id
                         LEFT JOIN `fs_visit` v               ON v.id = f.visit_id
                         LEFT JOIN `" . TBL_COMPANY . "` c   ON c.id_company = v.id_company AND c.id_shop='$shop'
                         LEFT JOIN `" . TBL_USERS . "` u     ON u.id = f.assign_user_id AND u.id_shop='$shop'
                         LEFT JOIN `fs_enquiry_details` ed   ON ed.enquiry_id = f.visit_id AND ed.id_shop='$shop'
                         LEFT JOIN `fs_customer` cu          ON cu.id_customer = ed.id_contact AND cu.id_shop='$shop'
                         LEFT JOIN `fs_enquiry` enq          ON enq.id = f.visit_id
                         LEFT JOIN `mst_lead_source` ls      ON ls.id = enq.id_mst_lead_source AND ls.id_shop='$shop'
                         WHERE f.status='1' AND f.id_shop='$shop'
                           AND f.lead_status=1 AND f.dated='$dated' $UserAssignIdJoin)

                        UNION ALL

                        (SELECT f.id, f.type, f.dated, f.lead_status, f.visit_id, f.hotel_id,
                                f.assign_user_id, f.visit_id AS enquiry_ref,
                                'fs_feedback_details' AS TableName,
                                h.name AS hotel_name, h.city AS hotel_city,
                                c.name AS company_name,
                                u.name AS assigned_user_name,
                                CONCAT(cu.first_name,' ',cu.last_name) AS contact_person_name,
                                cu.mobile AS contact_mobile,
                                ls.name AS lead_source_name
                         FROM `fs_feedback_details` f
                         LEFT JOIN `" . TBL_HOTELS . "` h    ON h.id = f.hotel_id
                         LEFT JOIN `fs_visit` v               ON v.id = f.visit_id
                         LEFT JOIN `" . TBL_COMPANY . "` c   ON c.id_company = v.id_company AND c.id_shop='$shop'
                         LEFT JOIN `" . TBL_USERS . "` u     ON u.id = f.assign_user_id AND u.id_shop='$shop'
                         LEFT JOIN `fs_enquiry_details` ed   ON ed.enquiry_id = f.visit_id AND ed.id_shop='$shop'
                         LEFT JOIN `fs_customer` cu          ON cu.id_customer = ed.id_contact AND cu.id_shop='$shop'
                         LEFT JOIN `fs_enquiry` enq          ON enq.id = f.visit_id
                         LEFT JOIN `mst_lead_source` ls      ON ls.id = enq.id_mst_lead_source AND ls.id_shop='$shop'
                         WHERE f.status='1' AND f.id_shop='$shop'
                           AND f.lead_status=1 AND f.dated='$dated' $UserAssignIdJoin)

                        UNION ALL

                        (SELECT f.id, f.type, f.dated, f.lead_status, f.id_quote AS visit_id, f.hotel_id,
                                f.assign_user_id, f.id_quote AS enquiry_ref,
                                'sales_quote_followup' AS TableName,
                                h.name AS hotel_name, h.city AS hotel_city,
                                c.name AS company_name,
                                u.name AS assigned_user_name,
                                CONCAT(cu.first_name,' ',cu.last_name) AS contact_person_name,
                                cu.mobile AS contact_mobile,
                                ls.name AS lead_source_name
                         FROM `sales_quote_followup` f
                         LEFT JOIN `" . TBL_HOTELS . "` h    ON h.id = f.hotel_id
                         LEFT JOIN `" . TBL_COMPANY . "` c   ON c.id_company = f.id_company AND c.id_shop='$shop'
                         LEFT JOIN `" . TBL_USERS . "` u     ON u.id = f.assign_user_id AND u.id_shop='$shop'
                         LEFT JOIN `fs_customer` cu          ON cu.id_customer = f.id_contact AND cu.id_shop='$shop'
                         LEFT JOIN `fs_enquiry` enq          ON enq.id = f.id_quote
                         LEFT JOIN `mst_lead_source` ls      ON ls.id = enq.id_mst_lead_source AND ls.id_shop='$shop'
                         WHERE f.status='1' AND f.id_shop='$shop'
                           AND f.lead_status=1 AND f.dated='$dated' $UserAssignIdJoin)

                        UNION ALL

                        (SELECT f.id, f.type, f.dated, f.lead_status, f.enquiry_id AS visit_id, f.hotel_id,
                                f.assign_user_id, f.enquiry_id AS enquiry_ref,
                                'fs_enquiry_details' AS TableName,
                                h.name AS hotel_name, h.city AS hotel_city,
                                c.name AS company_name,
                                u.name AS assigned_user_name,
                                CONCAT(cu.first_name,' ',cu.last_name) AS contact_person_name,
                                cu.mobile AS contact_mobile,
                                ls.name AS lead_source_name
                         FROM `fs_enquiry_details` f
                         LEFT JOIN `" . TBL_HOTELS . "` h    ON h.id = f.hotel_id
                         LEFT JOIN `" . TBL_COMPANY . "` c   ON c.id_company = f.id_company AND c.id_shop='$shop'
                         LEFT JOIN `" . TBL_USERS . "` u     ON u.id = f.assign_user_id AND u.id_shop='$shop'
                         LEFT JOIN `fs_customer` cu          ON cu.id_customer = f.id_contact AND cu.id_shop='$shop'
                         LEFT JOIN `fs_enquiry` enq          ON enq.id = f.enquiry_id
                         LEFT JOIN `mst_lead_source` ls      ON ls.id = enq.id_mst_lead_source AND ls.id_shop='$shop'
                         WHERE f.status='1' AND f.id_shop='$shop'
                           AND f.lead_status=1 AND f.dated='$dated' $UserAssignIdJoin
                           AND f.id IN ($rowImpMaxId))
                        LIMIT 200
                    ");
                    $VisiteID = 'visit_id';
                    break;

                // ── Type 2: visits / reports ──────────────────────────────────────
                case 2:
                    $resSql123 = executeSql("
                        SELECT f.*, c.name AS company_name,
                               CONCAT(cu.first_name,' ',cu.last_name) AS contact_person_name
                        FROM `$Table` f
                        LEFT JOIN `" . TBL_COMPANY . "` c   ON c.id_company = f.id_company AND c.id_shop='$shop'
                        LEFT JOIN `" . TBL_CUSTOMER . "` cu ON cu.id_customer = f.id_contacts
                        WHERE f.status='1' AND f.id_shop='$shop'
                          AND f.dated='$dated' AND f.id_user='$uid'
                        LIMIT 200
                    ");
                    $VisiteID = 'id';
                    break;

                // ── Type 3: follow-ups ────────────────────────────────────────────
                case 3:
                    $resSql123 = executeSql("
                        SELECT f.*, h.name AS hotel_name, h.city AS hotel_city,
                               c.name AS company_name
                        FROM `$Table` f
                        LEFT JOIN `" . TBL_HOTELS . "` h  ON h.id = f.hotel_id
                        LEFT JOIN `fs_visit` v             ON v.id = f.visit_id
                        LEFT JOIN `" . TBL_COMPANY . "` c ON c.id_company = v.id_company AND c.id_shop='$shop'
                        WHERE f.status='1' AND f.id_shop='$shop'
                          AND f.created_date='$dated' AND f.lead_status=1 $UserAssignIdJoin
                        LIMIT 200
                    ");
                    $VisiteID = 'visit_id';
                    break;

                // ── Type 4: enquiries direct ──────────────────────────────────────
                case 4:
                    $resSql123 = executeSql("
                        SELECT f.*, h.name AS hotel_name, c.name AS company_name
                        FROM `$Table` f
                        LEFT JOIN `" . TBL_HOTELS . "` h  ON h.id = f.hotel_id
                        LEFT JOIN `" . TBL_COMPANY . "` c ON c.id_company = f.id_company AND c.id_shop='$shop'
                        WHERE f.status='1' AND f.id_shop='$shop'
                          AND f.dated='$dated' $UserAssignIdJoin
                        LIMIT 200
                    ");
                    $VisiteID = 'id';
                    break;

                // ── Type 5: quotes ────────────────────────────────────────────────
                case 5:
                    $resSql123 = executeSql("
                        SELECT f.*, h.name AS hotel_name, c.name AS company_name
                        FROM `$Table` f
                        LEFT JOIN `" . TBL_HOTELS . "` h  ON h.id = f.hotel_id
                        LEFT JOIN `" . TBL_COMPANY . "` c ON c.id_company = f.id_company AND c.id_shop='$shop'
                        WHERE f.status='1' AND f.id_shop='$shop'
                          AND f.dated='$dated' AND f.id_assign_user='$uid'
                        LIMIT 200
                    ");
                    $VisiteID = 'id';
                    break;

                // ── Type 7: calls ─────────────────────────────────────────────────
                case 7:
                    $IncentiveActive = '1';
                    $resSql123 = executeSql("
                        SELECT cd.*,
                               cd2.call_remark, cd2.format_type, cd2.id_list_name,
                               JSON_UNQUOTE(JSON_EXTRACT(cd2.extra_data, '$.serial')) AS serial_number,
                               cl.name AS call_name,
                               ln.name AS list_name
                        FROM `$Table` cd
                        LEFT JOIN `call_details` cd2
                               ON cd2.call_id = cd.call_id AND cd2.call_status != '0'
                              AND cd2.id = (SELECT MAX(id) FROM call_details
                                            WHERE call_id = cd.call_id AND call_status != '0')
                        LEFT JOIN `call` cl           ON cl.id = cd.call_id
                        LEFT JOIN `call_list_name` ln ON ln.id = cd2.id_list_name
                        WHERE cd.call_status != '0'
                          AND cd.followup_date = '$dated'
                          AND cd.assign_user_id = '$uid'
                        LIMIT 200
                    ");
                    $VisiteID = 'call_id';
                    break;

                // ── Type 8: daily pickup / support ────────────────────────────────
                case 8:
                    // Get visit_ids for this date from fs_daily_calender first
                    $visit_ids_sql    = "SELECT visit_id FROM `fs_daily_calender`
                                         WHERE dated='" . addslashes($row[$DatedLabel]) . "'
                                           AND type='8' AND visit_id > 0";
                    $visit_ids_result = executeSql($visit_ids_sql);
                    $visit_ids_array  = array();
                    while ($visit_row = mysqli_fetch_assoc($visit_ids_result)) {
                        $visit_ids_array[] = intval($visit_row['visit_id']);
                    }
                    $visit_ids_list = !empty($visit_ids_array) ? implode(',', $visit_ids_array) : '0';

                    $resSql123 = executeSql("
                        SELECT sd.*, dp.bill_no,
                               c.name AS company_name,
                               CONCAT(cu.first_name,' ',cu.last_name) AS contact_person_name
                        FROM `$Table` sd
                        LEFT JOIN `daily_pickup` dp         ON dp.id = sd.id_daily_pickup
                        LEFT JOIN `" . TBL_COMPANY . "` c   ON c.id_company = sd.id_company
                        LEFT JOIN `" . TBL_CUSTOMER . "` cu ON cu.id_customer = dp.id_contacts
                        WHERE sd.id_daily_pickup > 0
                          AND sd.id_daily_pickup IN ($visit_ids_list)
                          AND sd.followup_date = '" . addslashes($row[$DatedLabel]) . "'
                          AND sd.assign_user_id = '$uid'
                          AND sd.id_shop = '$shop'
                          AND sd.id IN (
                              SELECT MAX(id) FROM `$Table`
                              WHERE id_daily_pickup > 0
                                AND id_daily_pickup IN ($visit_ids_list)
                                AND followup_date = '" . addslashes($row[$DatedLabel]) . "'
                                AND assign_user_id = '$uid'
                                AND id_shop = '$shop'
                              GROUP BY id_daily_pickup
                          )
                        LIMIT 200
                    ");
                    $IncentiveActive = '';
                    $VisiteID = 'id';
                    break;

                default:
                    continue 2;
            }

            if (num_rows($resSql123) < 1) continue;

            // ── Build HTML popup table ────────────────────────────────────────────
            $k  = '<div class="box-body table-responsive" style="height:200px;overflow:scroll;">';
            $k .= '<table id="example2" class="table table-bordered table-striped"><thead><tr>';

            switch ((int)$type) {
                case 1: $k .= '<th>Source</th><th>Hotel Name</th><th>Company</th><th>Assign To</th><th>Contact Person</th><th>Lead Source</th>'; break;
                case 2: $k .= '<th>Company Name</th><th>Person Met</th>'; break;
                case 3: $k .= '<th>Hotel Name</th><th>Company</th>'; break;
                case 4: $k .= '<th>Enquiry</th><th>Hotel Name</th>'; break;
                case 5: $k .= '<th>Company Name</th><th>Hotel Name</th>'; break;
                case 7: $k .= '<th>Source</th><th>Call Type</th><th>Serial</th><th>Customer Name</th><th>Remark</th>'; break;
                case 8: $k .= '<th>Bill No</th><th>Company</th><th>Contact</th><th>Last Remark</th><th>Status</th>'; break;
            }

            $k .= '</tr></thead><tbody>';

            while ($row123 = $db->fetch_assoc2($resSql123)) {

                $rowFileName = $FileName;
                if ($type == 1) {
                    if ($row123['type'] == 4)     $rowFileName = 'editEnquiry.php';
                    elseif ($row123['type'] == 5) $rowFileName = 'editQuote.php';
                    else                          $rowFileName = 'addreport.php';
                }

                $encId = encryptor('encrypt', $row123[$VisiteID]);
                $k .= '<tr>';

                // Type 1: lead type source link
                if ($type == 1) {
                    $leadTypeName = selectColumn(TBL_DAILY_LEAD_TYPE, 'name', " WHERE `id`='" . $row123['type'] . "'");
                    $k .= '<td><a href="' . $rowFileName . '?eId=' . $encId . '&action=edit&page=1">' . $leadTypeName . '</a></td>';
                }

                // Type 7: calls view link
                if ($type == 7) {
                    $k .= '<td><a href="calls.php?format_req=' . $row123['format_type'] . '&call_type=' . $row123['id_list_name'] . '&view_id=' . $row123['call_id'] . '">View</a></td>';
                }

                // Type 8: all from JOIN
                if ($type == 8) {
                    $k .= '<td><a href="ManagerDailyPickupItemwise.php?search_name=' . urlencode($row123['bill_no']) . '&searchFormSubmit=1">' . $row123['bill_no'] . '</a></td>';
                    $k .= '<td>' . $row123['company_name']        . '</td>';
                    $k .= '<td>' . $row123['contact_person_name'] . '</td>';
                    $k .= '<td>' . $row123['support_remark']      . '</td>';
                    $k .= '<td>' . ($row123['support_status'] == 0 ? 'Close' : 'Open') . '</td>';
                }

                // Hotel (types 1, 3) — from JOIN
                if ($type == 1 || $type == 3) {
                    $k .= '<td>' . $row123['hotel_name'] . ' - ' . $row123['hotel_city'] . '</td>';
                }

                // Call columns (type 7) — from JOIN
                if ($type == 7) {
                    $k .= '<td>' . $row123['list_name']     . '</td>';
                    $k .= '<td>' . $row123['serial_number'] . '</td>';
                    $k .= '<td>' . $row123['call_name']     . '</td>';
                    $k .= '<td>' . $row123['call_remark']   . '</td>';
                }

                // Company link cell (types 1-5) — from JOIN
                if ($type != 7 && $type != 8) {
                    $withOutComTxt = '';
                    if ($type == 4) $withOutComTxt = 'Enquiry';
                    if ($type == 5) $withOutComTxt = 'Direct Guest';

                    $comTxt = !empty($row123['company_name']) ? $row123['company_name'] : $withOutComTxt;

                    $k .= '<td><a href="' . $rowFileName . '?eId=' . $encId . '&action=edit&page=1">' . $comTxt . '</a>
                           <input type="hidden" name="followup_id"     value="' . $row123['id']       . '">
                           <input type="hidden" name="daily_Visit_id"  value="' . $row123['visit_id'] . '">
                           <input type="hidden" name="hotel_id"        value="' . $row123['hotel_id'] . '">
                           <input type="hidden" name="followup_status" value="">
                           </td>';
                }

                // Hotel (types 4, 5) — from JOIN
                if ($type == 4 || $type == 5) {
                    $k .= '<td>' . $row123['hotel_name'] . '</td>';
                }

                // Person met (type 2) — from JOIN
                if ($type == 2) {
                    $k .= '<td>' . $row123['company_name']        . '</td>';
                    $k .= '<td>' . $row123['contact_person_name'] . '</td>';
                }

                // Assign-to + contact + lead source (type 1) — ALL from JOIN, zero extra queries
                if ($type == 1) {
                    $k .= '<td>' . $row123['assigned_user_name'] . '</td>';

                    $contactName   = trim($row123['contact_person_name']);
                    $customerDetail = $contactName
                        ? $contactName . ' (' . $row123['contact_mobile'] . ')'
                        : 'N/A';
                    $k .= '<td>' . $customerDetail . '</td>';
                    $k .= '<td>' . $row123['lead_source_name'] . '</td>';
                }

                $k .= '</tr>';
            }

            $k .= '</tbody></table></div>';

            $e = array();
            $e['id']          = $row22['id'];
            $e['title']       = $row22['name'] . ' | ' . num_rows($resSql123);
            $e['description'] = $k;
            $e['room_id']     = $row22['id'];
            $e['start']       = $row[$DatedLabel];
            $e['end']         = $row[$DatedLabel];
            $e['allDay']      = true;

            if (!empty($row22['color'])) {
                $e['backgroundColor'] = $row22['color'];
                $e['borderColor']     = $row22['color'];
            } else {
                $e['backgroundColor'] = '#00a65a';
                $e['borderColor']     = '#00a65a';
            }

            array_push($events, $e);
        }
    }
}

echo json_encode($events);
?>