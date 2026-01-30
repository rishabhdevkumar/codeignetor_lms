<!DOCTYPE html>
<html>

<head>
    <title>Production Planning – Reorder Approval</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        /* body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f7f7f7;
            padding: 20px;
            font-size: 13px;
            color: #32363a;
        } */

        h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        h4 {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .sap-card {
            background: #ffffff;
            border: 1px solid #d9d9d9;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .compare-wrapper {
            display: flex;
            gap: 15px;
        }

        .compare-table {
            width: 50%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d9d9d9;
            font-size: 12.5px;
        }

        thead {
            background: #f2f2f2;
        }

        th,
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e5e5;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f5faff;
        }

        .changed {
            background: #fff3cd;
            font-weight: 600;
        }

        .approval-bar {
            text-align: right;
            margin-top: 15px;
        }
        
         button {
            background: #0a6ed1;
            border: none;
            color: #fff;
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 2px;
            cursor: pointer;
        }

        button:hover {
            background: #085caf;
        }

        .approveBtn {
            background: #107e3e;
            color: #fff;
            margin-right: 8px;
        }

        .rejectBtn {
            background: #bb0000;
            color: #fff;
        }

        .no-data {
            text-align: center;
            color: #777;
            padding: 12px;
        }
    </style>
</head>

<body>


    <div class="container-fluid mt-4">
        <!-- ================= FILTER ================= -->
        <div class="sap-card">
            <form id="filterForm">
                <label>
                    Machine <span style="color:red">*</span>:
                    <select id="machineFilter" required>
                        <option value="">-- Select Machine --</option>
                        <option value="1">PM01</option>
                        <option value="2">PM02</option>
                        <option value="3">PM03</option>
                        <option value="4">PM04</option>
                    </select>
                </label>

                <button type="submit">Search</button>
            </form>
        </div>

        <!-- ================= SIDE BY SIDE ================= -->
        <div class="sap-card">
            <h3>Production Planning vs Reorder Planning</h3>

            <div class="compare-wrapper">

                <!-- ===== ORIGINAL ===== -->
                <div class="compare-table">
                    <h4>Original Production Planning</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>PP ID</th>
                                <th>Machine</th>
                                <th>FG Code</th>
                                <th>Qty</th>
                                <th>From</th>
                                <th>To</th>
                            </tr>
                        </thead>
                        <tbody id="originalBody">
                            <?php foreach ($originalPlans as $pp): ?>
                                <tr data-machine="<?= esc($pp['MACHINE']) ?>">
                                    <td><?= esc($pp['PP_ID']) ?></td>
                                    <td><?= esc($pp['MACHINE']) ?></td>
                                    <td><?= esc($pp['SAP_MR_FG_CODE']) ?></td>
                                    <td><?= esc($pp['QTY_MT']) ?></td>
                                    <td><?= esc($pp['FROM_DATE_TIME']) ?></td>
                                    <td><?= esc($pp['TO_DATE_TIME']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr id="noOriginal" class="no-data" style="display:none;">
                                <td colspan="6">No production planning data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ===== REORDER ===== -->
                <div class="compare-table">
                    <h4>Reorder Planning (Pending Approval)</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Planning calendar ID</th>
                                <th>Machine</th>
                                <th>FG Code</th>
                                <th>Qty</th>
                                <th>From</th>
                                <th>To</th>
                            </tr>
                        </thead>
                        <tbody id="reorderBody">
                            <?php foreach ($pendingReorders as $rp): ?>
                                <?php
                                $orig = array_filter(
                                    $originalPlans,
                                    fn($o) => $o['PP_ID'] == $rp['PLANNING_CAL_ID']
                                );
                                $orig = reset($orig);
                                ?>
                                <tr data-id="<?= esc($rp['PLANNING_CAL_ID']) ?>" data-approval-id="<?= esc($rp['PP_ID']) ?>" data-machine="<?= esc($rp['MACHINE']) ?>">
                                    <td><?= esc($rp['PLANNING_CAL_ID']) ?></td>
                                    <td><?= esc($rp['MACHINE']) ?></td>
                                    <td><?= esc($rp['SAP_MR_FG_CODE']) ?></td>
                                    <td class="<?= ($orig && $orig['QTY_MT'] != $rp['QTY_MT']) ? 'changed' : '' ?>">
                                        <?= esc($rp['QTY_MT']) ?>
                                    </td>
                                    <td class="<?= ($orig && $orig['FROM_DATE_TIME'] != $rp['FROM_DATE_TIME']) ? 'changed' : '' ?>">
                                        <?= esc($rp['FROM_DATE_TIME']) ?>
                                    </td>
                                    <td class="<?= ($orig && $orig['TO_DATE_TIME'] != $rp['TO_DATE_TIME']) ? 'changed' : '' ?>">
                                        <?= esc($rp['TO_DATE_TIME']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr id="noReorder" class="no-data" style="display:none;">
                                <td colspan="6">No pending reorder data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- ===== APPROVAL ===== -->
            <div class="approval-bar">
                <button class="approveBtn" id="approveAll">Approve</button>
                <button class="rejectBtn" id="rejectAll">Reject</button>
            </div>
        </div>
    </div>

    <!-- ================= JS ================= -->
    <script>
        $(function() {

            function applyFilter(machine) {
                let oCount = 0,
                    rCount = 0;

                $("#originalBody tr").not("#noOriginal").each(function() {
                    const show = $(this).data("machine").toString() === machine;
                    $(this).toggle(show);
                    if (show) oCount++;
                });

                $("#reorderBody tr").not("#noReorder").each(function() {
                    const show = $(this).data("machine").toString() === machine;
                    $(this).toggle(show);
                    if (show) rCount++;
                });

                $("#noOriginal").toggle(oCount === 0);
                $("#noReorder").toggle(rCount === 0);
            }

            /* ---------- DEFAULT MACHINE (FIRST) ---------- */
            const firstMachine = $("#machineFilter option:eq(1)").val();
            if (firstMachine) {
                $("#machineFilter").val(firstMachine);
                applyFilter(firstMachine);
            }

            /* ---------- FILTER SUBMIT ---------- */
            $("#filterForm").on("submit", function(e) {
                e.preventDefault();
                applyFilter($("#machineFilter").val());
            });

            /* ---------- APPROVE ---------- */
            $("#approveAll").on("click", function() {

                const planning_cal_ids = [];
                const new_row_ids = [];

                $("#reorderBody tr:visible").each(function() {

                    const planningCalId = Number($(this).data("id"));
                    const approvalId = Number($(this).data("approval-id"));

                    if (planningCalId > 0) {
                        planning_cal_ids.push(planningCalId);
                    } else {
                        new_row_ids.push(approvalId);
                    }
                });

                if (planning_cal_ids.length === 0 && new_row_ids.length === 0) {
                    alert("No pending reorder for selected machine");
                    return;
                }

                if (!confirm("Approve all visible reorder changes?")) return;

                $.post("<?= base_url('production-planning/approvePendingApproval') ?>", {
                    planning_cal_ids: planning_cal_ids,
                    new_row_ids: new_row_ids,
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                }, function(res) {
                    alert(res.message);
                    location.reload();
                }, 'json');
            });


            /* ---------- REJECT ---------- */
            $("#rejectAll").on("click", function() {
                const machine = $("#machineFilter").val();

                if (!confirm("Reject all pending records for selected machine?")) return;

                $.post("<?= base_url('production-planning/rejectPendingApproval') ?>", {
                    machine: machine,
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                }, function() {
                    alert("Rejected successfully");
                    location.reload();
                });
            });

        });
    </script>

</body>

</html>