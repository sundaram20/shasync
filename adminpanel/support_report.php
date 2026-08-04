<?php
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_CUSTOMER, 'view');

/*=====================================================================================
  SUPPORT DASHBOARD
  Source of truth: `support` (s) — one row per ticket, same table/fields the
  ManagerDailyPickup.php listing page uses, so totals reconcile with it.

  Two view modes, switched via ?view_mode=as_on|period :
   - "As On"       (default) — one static table per report with FIVE rolling
                    windows side by side (last 30/60/90/180/365 days from today),
                    computed with a single conditional-aggregation query.
   - "Period Wise" — a single custom date range (from/to), one "Tickets" column
                    per report, same query shape the dashboard used previously.

  Company / Executive / Product filters apply in both modes.
=====================================================================================*/

$shopId = $_SESSION['shop'];

// Safe request-value helper — avoids "Undefined array key" warnings printing
// raw as text at the top of the page.
function req($key, $default = '') {
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
}

$viewMode = req('view_mode', 'as_on');
if (!in_array($viewMode, array('as_on', 'period'), true)) {
    $viewMode = 'as_on';
}

// ---------------------------------------------------------------------------
// Build shared WHERE clause — company/executive/product filters, common to
// both modes. Date logic is added separately per mode below.
// ---------------------------------------------------------------------------
$where = " WHERE s.id_shop = '" . addslashes($shopId) . "' ";

if (req('companyId') !== '') {
    $where .= " AND s.id_company = '" . addslashes(req('companyId')) . "' ";
}

if (req('id_executive') !== '') {
    $where .= " AND s.id_mst_user_created_by = '" . addslashes(req('id_executive')) . "' ";
}

if (req('id_products') !== '') {
    $where .= " AND s.id_product = '" . addslashes(req('id_products')) . "' ";
}

// ---------------------------------------------------------------------------
// Helper: build a URL preserving current filters but overriding given params
// ---------------------------------------------------------------------------
function buildUrl($overrides) {
    $params = array_merge($_GET, $overrides);
    return '?' . http_build_query($params);
}

if ($viewMode === 'as_on') {

    // =========================================================================
    // AS ON MODE — five rolling windows, one query per report
    // =========================================================================
    $periods = array(30, 60, 90, 180, 365);

    function periodSelectExpr($periods) {
        $parts = array();
        foreach ($periods as $days) {
            $parts[] = "SUM(CASE WHEN DATE(s.date_created) >= DATE_SUB(CURDATE(), INTERVAL {$days} DAY) THEN 1 ELSE 0 END) as d{$days}";
        }
        return implode(",\n               ", $parts);
    }

    $sqlTotal = "SELECT " . periodSelectExpr($periods) . " FROM support s " . $where;
    $db->query($sqlTotal);
    $totalRow = $db->fetch_object();

    $sqlCompany = "SELECT s.id_company, c.name as company_name,
                   " . periodSelectExpr($periods) . "
                   FROM support s
                   LEFT JOIN " . TBL_COMPANY . " c ON c.id_company = s.id_company
                   " . $where . "
                   GROUP BY s.id_company
                   HAVING d365 > 0
                   ORDER BY d365 DESC";
    $db->query($sqlCompany);
    $companyReport = array();
    while ($row = $db->fetch_object()) { $companyReport[] = $row; }
    $activeCompanies = count($companyReport);

    $sqlExecutive = "SELECT s.id_mst_user_created_by, u.name as exec_name,
                     " . periodSelectExpr($periods) . "
                     FROM support s
                     LEFT JOIN " . TBL_USERS . " u ON u.id = s.id_mst_user_created_by
                     " . $where . "
                     GROUP BY s.id_mst_user_created_by
                     HAVING d365 > 0
                     ORDER BY d365 DESC";
    $db->query($sqlExecutive);
    $executiveReport = array();
    while ($row = $db->fetch_object()) { $executiveReport[] = $row; }
    $activeExecutives = count($executiveReport);

    $sqlProduct = "SELECT s.id_product, h.name as product_name,
                   " . periodSelectExpr($periods) . "
                   FROM support s
                   LEFT JOIN " . TBL_HOTELS . " h ON h.id = s.id_product
                   " . $where . "
                   GROUP BY s.id_product
                   HAVING d365 > 0
                   ORDER BY d365 DESC
                   LIMIT 10";
    $db->query($sqlProduct);
    $productReport = array();
    while ($row = $db->fetch_object()) { $productReport[] = $row; }

} else {

    // =========================================================================
    // PERIOD WISE MODE — single custom date range, one "Tickets" column
    // =========================================================================
    function parseDMY($str) {
        $str = trim($str);
        $dt  = DateTime::createFromFormat('d-m-Y', $str);
        if ($dt === false) {
            $dt = DateTime::createFromFormat('d/m/Y', $str);
        }
        return $dt !== false ? $dt->format('Y-m-d') : null;
    }

    function splitDateRange($str) {
        $str = trim($str);
        foreach (array(' to ', ' - ', ' – ') as $sep) {
            if (strpos($str, $sep) !== false) {
                return explode($sep, $str, 2);
            }
        }
        return array($str);
    }

    $fromDate = '';
    $toDate   = '';
    if (req('date_created') !== '') {
        $dateParts  = splitDateRange(req('date_created'));
        $parsedFrom = parseDMY($dateParts[0]);
        $parsedTo   = isset($dateParts[1]) ? parseDMY($dateParts[1]) : $parsedFrom;

        $fromDate = $parsedFrom ? $parsedFrom : date('Y-m-01');
        $toDate   = $parsedTo   ? $parsedTo   : date('Y-m-d');
    } else {
        // Default: current month
        $fromDate = date('Y-m-01');
        $toDate   = date('Y-m-d');
    }
    $periodWhere = $where . " AND DATE(s.date_created) >= '" . $fromDate . "' AND DATE(s.date_created) <= '" . $toDate . "' ";

    $sqlTotal = "SELECT COUNT(*) as total_tickets FROM support s " . $periodWhere;
    $db->query($sqlTotal);
    $totalRow     = $db->fetch_object();
    $totalTickets = $totalRow ? (int) $totalRow->total_tickets : 0;

    $sqlCompany = "SELECT s.id_company, c.name as company_name, COUNT(*) as tickets
                   FROM support s
                   LEFT JOIN " . TBL_COMPANY . " c ON c.id_company = s.id_company
                   " . $periodWhere . "
                   GROUP BY s.id_company
                   ORDER BY tickets DESC";
    $db->query($sqlCompany);
    $companyReport = array();
    while ($row = $db->fetch_object()) { $companyReport[] = $row; }
    $activeCompanies = count($companyReport);

    $sqlExecutive = "SELECT s.id_mst_user_created_by, u.name as exec_name, COUNT(*) as tickets
                     FROM support s
                     LEFT JOIN " . TBL_USERS . " u ON u.id = s.id_mst_user_created_by
                     " . $periodWhere . "
                     GROUP BY s.id_mst_user_created_by
                     ORDER BY tickets DESC";
    $db->query($sqlExecutive);
    $executiveReport = array();
    while ($row = $db->fetch_object()) { $executiveReport[] = $row; }
    $activeExecutives = count($executiveReport);

    $sqlProduct = "SELECT s.id_product, h.name as product_name, COUNT(*) as tickets
                   FROM support s
                   LEFT JOIN " . TBL_HOTELS . " h ON h.id = s.id_product
                   " . $periodWhere . "
                   GROUP BY s.id_product
                   ORDER BY tickets DESC
                   LIMIT 10";
    $db->query($sqlProduct);
    $productReport = array();
    while ($row = $db->fetch_object()) { $productReport[] = $row; }
}

?>
<?php include_once("includes/header.php") ?>
<?php include_once("includes/left.php") ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-dt@1.13.6/css/jquery.dataTables.min.css">
<!-- NOTE: datatables.net JS (jQuery plugin) and all $(...) code are loaded further
     below, AFTER includes/footer.php — that's where jQuery itself gets loaded in
     this template. Anything using $ before that point silently fails. -->

<style>
  /* =========================================================================
     Support Dashboard — minimal design system, namespaced under .support-dash
     so nothing here leaks into other AdminLTE pages that reuse .box / .table.
  ========================================================================= */
  .support-dash {
    --sd-border:  #e9ebee;
    --sd-muted:   #8a919a;
    --sd-text:    #2b2e33;
    --sd-primary: #3c6ef0;
    --sd-radius:  10px;
    --sd-shadow:  0 1px 3px rgba(20,20,30,.05), 0 1px 2px rgba(20,20,30,.04);
    color: var(--sd-text);
  }

  .support-dash .content-header h1 { font-weight: 600; font-size: 22px; }
  .support-dash .content-header h1 small { font-weight: 400; color: var(--sd-muted); }

  /* ---- Cards ---- */
  .support-dash .box {
    background: #fff;
    border: 1px solid var(--sd-border);
    border-radius: var(--sd-radius);
    box-shadow: var(--sd-shadow);
    margin-bottom: 20px;
  }
  .support-dash .box-header { padding: 16px 20px 12px; border-bottom: 1px solid var(--sd-border); background: transparent; }
  .support-dash .box-header.with-border { border-bottom: 1px solid var(--sd-border); }
  .support-dash .box-title { font-weight: 600; font-size: 15px; color: var(--sd-text); margin: 0; }
  .support-dash .box-subtitle { display: block; font-size: 12px; color: var(--sd-muted); margin-top: 3px; font-weight: 400; }
  .support-dash .box-body { padding: 18px 20px 20px; }
  .support-dash .box-footer { background: transparent; border-top: 1px solid var(--sd-border); padding: 14px 20px; }

  /* ---- Filter form ---- */
  .support-dash label { font-size: 12.5px; font-weight: 600; color: #55595f; margin-bottom: 5px; }
  .support-dash .form-control { border: 1px solid var(--sd-border); border-radius: 6px; box-shadow: none; font-size: 13.5px; }
  .support-dash .form-control:focus { border-color: var(--sd-primary); box-shadow: 0 0 0 3px rgba(60,110,240,.12); }
  .support-dash .btn-primary {
    background: var(--sd-primary); border-color: var(--sd-primary); border-radius: 6px;
    padding: 7px 20px; font-size: 13.5px; font-weight: 600; box-shadow: none;
  }
  .support-dash .btn-primary:hover { background: #2f5bd6; border-color: #2f5bd6; }

  /* ---- Mode toggle (As On / Period Wise) ---- */
  .support-dash .mode-toggle { display: inline-flex; background: #f0f2f4; border-radius: 8px; padding: 3px; margin-bottom: 18px; }
  .support-dash .mode-toggle a {
    padding: 7px 18px; border-radius: 6px; font-size: 13px; font-weight: 600;
    color: #6b7178; text-decoration: none; transition: background .15s ease, color .15s ease;
  }
  .support-dash .mode-toggle a:hover { color: var(--sd-text); text-decoration: none; }
  .support-dash .mode-toggle a.active { background: #fff; color: var(--sd-primary); box-shadow: 0 1px 2px rgba(20,20,30,.08); }

  /* ---- Clean multi-period summary strip (As On mode) ---- */
  .support-dash .summary-strip { display: flex; flex-wrap: wrap; gap: 22px; padding: 4px 2px 18px; }
  .support-dash .summary-stat { font-size: 13px; color: var(--sd-muted); }
  .support-dash .summary-stat b { display: block; font-size: 19px; color: var(--sd-text); font-weight: 700; line-height: 1.3; }

  /* ---- Clean single summary line (Period Wise mode) ---- */
  .support-dash .summary-line { font-size: 13.5px; color: var(--sd-muted); padding: 2px 2px 18px; }
  .support-dash .summary-line b { color: var(--sd-text); font-weight: 600; }
  .support-dash .summary-line .divider { margin: 0 8px; color: #d7dade; }

  /* ---- Tables: quiet, minimal, no heavy grid ---- */
  .support-dash table.table { border: none; }
  .support-dash table.table thead th {
    border: none !important; border-bottom: 1px solid var(--sd-border) !important;
    background: transparent; text-transform: uppercase; font-size: 11px; letter-spacing: .04em;
    color: var(--sd-muted); font-weight: 600; padding: 8px 10px; text-align: right;
  }
  .support-dash table.table thead th:first-child,
  .support-dash table.table thead th:nth-child(2) { text-align: left; }
  .support-dash table.table tbody td {
    border: none !important; border-bottom: 1px solid var(--sd-border) !important;
    padding: 10px 10px; font-size: 13.5px; vertical-align: middle; text-align: right;
  }
  .support-dash table.table tbody td:first-child,
  .support-dash table.table tbody td:nth-child(2) { text-align: left; }
  .support-dash table.table tbody tr:last-child td { border-bottom: none !important; }
  .support-dash table.table tbody tr:hover td { background: #fafbfc; }
  .support-dash table.table tbody td:first-child { color: var(--sd-muted); }
  .support-dash table.table tbody td.col-365 { font-weight: 600; color: var(--sd-text); }

  /* DataTables chrome (search box / pagination) restyled to match */
  .support-dash .dataTables_wrapper { padding-top: 2px; }
  .support-dash .dataTables_filter { margin-bottom: 12px; }
  .support-dash .dataTables_filter input {
    border: 1px solid var(--sd-border); border-radius: 6px; padding: 6px 10px;
    font-size: 13px; margin-left: 6px; box-shadow: none;
  }
  .support-dash .dataTables_filter input:focus { border-color: var(--sd-primary); outline: none; }
  .support-dash .dataTables_info { font-size: 12px; color: var(--sd-muted); padding-top: 10px; }
  .support-dash .dataTables_paginate { padding-top: 6px; }
  .support-dash .dataTables_paginate .paginate_button {
    border: none !important; background: transparent !important; padding: 5px 10px !important;
    margin: 0 !important; border-radius: 6px; font-size: 12.5px; color: var(--sd-text) !important;
  }
  .support-dash .dataTables_paginate .paginate_button.current { background: var(--sd-primary) !important; color: #fff !important; }
  .support-dash .dataTables_paginate .paginate_button:hover { background: #eef1f4 !important; color: var(--sd-text) !important; }
  .support-dash .dataTables_paginate .paginate_button.disabled { opacity: .4; }
</style>

<div class="content-wrapper support-dash">

  <section class="content-header">
    <h1>Support Dashboard <small>Company / Executive Reports</small></h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="ManagerDailyPickup.php">Support Manager</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <section class="content">

    <!-- ============================= FILTER FORM ============================= -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Filters</h3>
      </div>
      <form name="dashboardFilterForm" action="" method="get">
        <input type="hidden" name="view_mode" value="<?php echo htmlspecialchars($viewMode); ?>">
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Company</label>
                <?php
                $companyDropDown = '<select class="form-control select2" name="companyId">
                                        <option value="">All Companies</option>';
                $resCat = selectSql(TBL_COMPANY, " where status='1' and id_shop='" . addslashes($shopId) . "' ", ' ORDER BY name');
                if ($db->num_rows2($resCat)) {
                    while ($resultCat = $db->fetch_object2($resCat)) {
                        $selected = (req('companyId') == $resultCat->id_company) ? 'selected="selected"' : '';
                        $companyDropDown .= '<option ' . $selected . ' value="' . $resultCat->id_company . '">' . ucfirst($resultCat->name) . '</option>';
                    }
                }
                echo $companyDropDown .= '</select>';
                ?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Executive</label>
                <?php
                $executiveDropDown = '<select class="form-control select2" name="id_executive">
                                          <option value="">All Executives</option>';
                $resCat = selectSql(TBL_USERS, " where id_shop='" . addslashes($shopId) . "' AND status=1 ", ' ORDER BY name');
                if ($db->num_rows2($resCat)) {
                    while ($resultCat = $db->fetch_object2($resCat)) {
                        $selected = (req('id_executive') == $resultCat->id) ? 'selected="selected"' : '';
                        $execLabel = trim($resultCat->name . ' ' . (isset($resultCat->last_name) ? $resultCat->last_name : ''));
                        $executiveDropDown .= '<option ' . $selected . ' value="' . $resultCat->id . '">' . ucfirst($execLabel) . '</option>';
                    }
                }
                echo $executiveDropDown .= '</select>';
                ?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Product</label>
                <?php
                $productDropDown = '<select class="form-control select2" name="id_products">
                                        <option value="">All Products</option>';
                $resCatt = selectSql(TBL_HOTELS, " where id_shop='" . addslashes($shopId) . "' AND status=1 ");
                if ($db->num_rows2($resCatt)) {
                    while ($resultCatt = $db->fetch_object2($resCatt)) {
                        $selected = (req('id_products') == $resultCatt->id) ? 'selected="selected"' : '';
                        $productDropDown .= '<option ' . $selected . ' value="' . $resultCatt->id . '">' . ucfirst($resultCatt->name) . '</option>';
                    }
                }
                echo $productDropDown .= '</select>';
                ?>
              </div>
            </div>

            <?php if ($viewMode === 'period'): ?>
            <div class="col-md-4">
              <div class="form-group">
                <label>Date Range</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control dateRangeReport" placeholder="Defaults to current month"
                         id="date_created" name="date_created"
                         value="<?php echo req('date_created') !== '' ? req('date_created') : (date('d-m-Y', strtotime($fromDate)) . ' to ' . date('d-m-Y', strtotime($toDate))); ?>">
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="box-footer">
          <input name="Search" type="submit" class="btn btn-primary" value="Apply Filters" />
        </div>
      </form>
    </div>

    <!-- ============================= MODE TOGGLE ============================= -->
    <div class="mode-toggle">
      <a class="<?php echo $viewMode === 'as_on' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars(buildUrl(array('view_mode' => 'as_on'))); ?>">As On</a>
      <a class="<?php echo $viewMode === 'period' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars(buildUrl(array('view_mode' => 'period'))); ?>">Period Wise</a>
    </div>

    <?php if ($viewMode === 'as_on'): ?>
      <!-- ============================= SUMMARY STRIP (AS ON) ============================= -->
      <div class="summary-strip">
        <?php foreach ($periods as $days): $field = 'd' . $days; ?>
          <div class="summary-stat">Last <?php echo $days; ?> Days<b><?php echo (int) $totalRow->$field; ?></b></div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <!-- ============================= SUMMARY LINE (PERIOD WISE) ============================= -->
      <div class="summary-line">
        Total Tickets: <b><?php echo $totalTickets; ?></b>
        <span class="divider">&middot;</span>
        Period: <b><?php echo date('d M Y', strtotime($fromDate)); ?></b> to <b><?php echo date('d M Y', strtotime($toDate)); ?></b>
      </div>
    <?php endif; ?>

    <!-- ============================= COMPANY-WISE + EXECUTIVE-WISE ============================= -->
    <div class="row">
      <div class="col-md-6">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Company-wise Support Report</h3>
            <span class="box-subtitle"><?php echo $activeCompanies; ?> companies<?php echo $viewMode === 'as_on' ? ' with activity in the last year' : ' in this period'; ?></span>
          </div>
          <div class="box-body">
            <table id="companyTable" class="table" style="width:100%;">
              <thead>
                <tr>
                  <?php if ($viewMode === 'as_on'): ?>
                    <th style="width:34px;">#</th>
                    <th>Company</th>
                    <?php foreach ($periods as $days): ?><th><?php echo $days; ?>D</th><?php endforeach; ?>
                  <?php else: ?>
                    <th style="width:34px;">#</th><th>Company</th><th style="width:90px;">Tickets</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php $rank = 1; foreach ($companyReport as $c): ?>
                <tr>
                  <?php if ($viewMode === 'as_on'): ?>
                    <td><?php echo $rank++; ?></td>
                    <td><?php echo $c->company_name ? htmlspecialchars($c->company_name) : '<em>Unknown</em>'; ?></td>
                    <?php foreach ($periods as $days): $field = 'd' . $days; ?>
                      <td class="<?php echo $days == 365 ? 'col-365' : ''; ?>"><?php echo (int) $c->$field; ?></td>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <td><?php echo $rank++; ?></td>
                    <td><?php echo $c->company_name ? htmlspecialchars($c->company_name) : '<em>Unknown</em>'; ?></td>
                    <td><?php echo $c->tickets; ?></td>
                  <?php endif; ?>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Executive-wise Support Report</h3>
            <span class="box-subtitle"><?php echo $activeExecutives; ?> executives<?php echo $viewMode === 'as_on' ? ' with activity in the last year' : ' active in this period'; ?></span>
          </div>
          <div class="box-body">
            <table id="executiveTable" class="table" style="width:100%;">
              <thead>
                <tr>
                  <?php if ($viewMode === 'as_on'): ?>
                    <th>Executive</th>
                    <?php foreach ($periods as $days): ?><th><?php echo $days; ?>D</th><?php endforeach; ?>
                  <?php else: ?>
                    <th>Executive</th><th style="width:90px;">Tickets</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($executiveReport as $e): ?>
                <tr>
                  <?php if ($viewMode === 'as_on'): ?>
                    <td><?php echo htmlspecialchars(trim($e->exec_name)) ?: '<em>Unassigned</em>'; ?></td>
                    <?php foreach ($periods as $days): $field = 'd' . $days; ?>
                      <td class="<?php echo $days == 365 ? 'col-365' : ''; ?>"><?php echo (int) $e->$field; ?></td>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <td><?php echo htmlspecialchars(trim($e->exec_name)) ?: '<em>Unassigned</em>'; ?></td>
                    <td><?php echo $e->tickets; ?></td>
                  <?php endif; ?>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================= TOP ITEMS ============================= -->
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border"><h3 class="box-title">Top 10 Items Generating Support Requests</h3></div>
          <div class="box-body">
            <table class="table">
              <thead>
                <tr>
                  <?php if ($viewMode === 'as_on'): ?>
                    <th>Item / Product</th>
                    <?php foreach ($periods as $days): ?><th><?php echo $days; ?>D</th><?php endforeach; ?>
                  <?php else: ?>
                    <th style="width:34px;">#</th><th>Item / Product</th><th style="width:90px;">Tickets</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($productReport)): $i = 1; foreach ($productReport as $p): ?>
                <tr>
                  <?php if ($viewMode === 'as_on'): ?>
                    <td><?php echo $p->product_name ? htmlspecialchars($p->product_name) : '<em>Direct / Not linked</em>'; ?></td>
                    <?php foreach ($periods as $days): $field = 'd' . $days; ?>
                      <td class="<?php echo $days == 365 ? 'col-365' : ''; ?>"><?php echo (int) $p->$field; ?></td>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $p->product_name ? htmlspecialchars($p->product_name) : '<em>Direct / Not linked</em>'; ?></td>
                    <td><?php echo $p->tickets; ?></td>
                  <?php endif; ?>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="<?php echo $viewMode === 'as_on' ? count($periods) + 1 : 3; ?>" align="center">No records found</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </section>
</div>

<?php include_once("includes/footer.php") ?>

<!-- ==========================================================================
     Everything below needs jQuery, which this template loads inside footer.php.
========================================================================== -->
<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(function () {
  var companyOrderCol   = <?php echo $viewMode === 'as_on' ? 6 : 2; ?>;
  var executiveOrderCol = <?php echo $viewMode === 'as_on' ? 5 : 1; ?>;

  $('#companyTable').DataTable({ pageLength: 8, lengthChange: false, order: [[companyOrderCol, 'desc']] });
  $('#executiveTable').DataTable({ pageLength: 8, lengthChange: false, order: [[executiveOrderCol, 'desc']] });

  $('.select2').select2();

  <?php if ($viewMode === 'period'): ?>
  $('.dateRangeReport').daterangepicker({
    locale: { format: 'DD-MM-YYYY', separator: ' to ' },
    autoUpdateInput: true
  });
  <?php endif; ?>
});
</script>