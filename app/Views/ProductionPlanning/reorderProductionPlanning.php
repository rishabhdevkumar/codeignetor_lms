<!DOCTYPE html>
<html>

<head>
    <title>Production Planning – Reorder</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 20px;
            font-size: 13px;
            color: #32363a;
        }

        h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .sap-card {
            background: #ffffff;
            border: 1px solid #d9d9d9;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
        }

        /* Filter */
        #filterForm label {
            margin-right: 12px;
            font-weight: 500;
        }

        #filterForm input,
        #filterForm select {
            padding: 5px 6px;
            border: 1px solid #bfbfbf;
            border-radius: 2px;
            font-size: 13px;
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

        #addBtn {
            background: #107e3e;
        }

        #addBtn:hover {
            background: #0b5d2e;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border: 1px solid #d9d9d9;
            font-size: 12.5px;
        }

        thead {
            background: #f2f2f2;
        }

        th {
            padding: 6px 8px;
            border-bottom: 1px solid #d9d9d9;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #eeeeee;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f5faff;
        }

        tbody tr {
            cursor: move;
        }

        .ui-sortable-helper {
            background: #e5f0fa !important;
            border: 1px dashed #0a6ed1;
        }

        .machine-row {
            transition: background-color 0.2s ease;
        }

        /* Modal */
        .ui-dialog-titlebar {
            background: #f2f2f2;
            border: none;
            font-size: 14px;
        }

        #addModal label {
            font-weight: 600;
        }

        #addModal input,
        #addModal select {
            width: 100%;
            padding: 6px;
            border: 1px solid #bfbfbf;
            border-radius: 2px;
        }

        .table-scroll-x {
            width: 100%;
            overflow-x: auto;
            /* horizontal scroll */
            overflow-y: hidden;
        }
    </style>
</head>

<body>

    <!-- FILTER CARD -->
    <div class="sap-card">
        <div class="row">
            <div class="col-md-10">
                <form id="filterForm">
                    <label>
                        From Date Time:
                        <input type="datetime-local" id="fromDate">
                    </label>

                    <label>
                        To Date Time:
                        <input type="datetime-local" id="toDate">
                    </label>

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

            <div class="col-md-2" style="text-align:right;">
                <button id="addBtn" type="button">+ Add</button>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="sap-card table-scroll-x">
        <table style="horizontal-scroll:y">
            <thead>
                <tr>
                    <th>☰</th>
                    <!-- <th>PP ID</th> -->
                    <th>Planning Cal ID</th>
                    <th>Version</th>
                    <th>Machine</th>
                    <th>SAP MR FG Code</th>
                    <th>Qty</th>
                    <th>From Date Time</th>
                    <th>To Date Time</th>
                    <th>Utilised Qty</th>
                    <th>Balance Qty</th>
                    <th>KC1 Qty</th>
                    <th>KC2 Qty</th>
                    <th>NKC Qty</th>
                    <th>KC1 Utilised</th>
                    <th>KC2 Utilised</th>
                    <th>NKC Utilised</th>
                    <th>KC1 Balance</th>
                    <th>KC2 Balance</th>
                    <th>NKC Balance</th>
                    <th>Calendar Type</th>
                </tr>
            </thead>

            <tbody id="sortable">
                <?php foreach ($items as $item): ?>
                    <tr data-id="<?= $item['PP_ID']; ?>" data-machine="<?= $item['MACHINE']; ?>" class="machine-row">
                        <td>☰</td>
                        <td><?= $item['PP_ID'] ?></td>
                        <!-- <td><?= $item['PLANNING_CAL_ID'] ?></td> -->
                        <td><?= $item['VERSION'] ?></td>
                        <td><?= $item['MACHINE'] ?></td>
                        <td><?= $item['SAP_MR_FG_CODE'] ?></td>
                        <td><?= $item['QTY_MT'] ?></td>
                        <td><?= $item['FROM_DATE_TIME'] ?></td>
                        <td><?= $item['TO_DATE_TIME'] ?></td>
                        <td><?= $item['UTILISED_QTY'] ?></td>
                        <td><?= $item['BALANCE_QTY'] ?></td>
                        <td><?= $item['KC1_QTY_MT'] ?></td>
                        <td><?= $item['KC2_QTY_MT'] ?></td>
                        <td><?= $item['NKC_QTY_MT'] ?></td>
                        <td><?= $item['KC1_UTILISED_QTY_MT'] ?></td>
                        <td><?= $item['KC2_UTILISED_QTY_MT'] ?></td>
                        <td><?= $item['NKC_UTILISED_QTY_MT'] ?></td>
                        <td><?= $item['KC1_BALANCE_QTY_MT'] ?></td>
                        <td><?= $item['KC2_BALANCE_QTY_MT'] ?></td>
                        <td><?= $item['NKC_BALANCE_QTY_MT'] ?></td>
                        <td><?= $item['CALENDAR_TYPE'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr id="noDataRow" style="display:none;">
                    <td colspan="21" style="text-align:center; padding:15px; color:#666;">
                        No records found for the selected filters
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="sap-card" style="text-align:right;">
            <button id="saveOrderBtn" type="button">
                Save Order
            </button>
        </div>
    </div>

    <!-- ADD MODAL -->
    <div id="addModal" title="Add Production Planning" style="display:none;">
        <form id="addForm">
            <div>
                <label>Machine *</label>
                <select name="machine" required>
                    <option value="">-- Select --</option>
                    <option value="PM01">PM01</option>
                    <option value="PM02">PM02</option>
                </select>
            </div>

            <div style="margin-top:10px;">
                <label>SAP Mother Roll Code *</label>
                <input type="text" name="sap_mother_roll_code" required>
            </div>

            <div style="margin-top:10px;">
                <label>Qty (MT) *</label>
                <input type="number" name="qty_mt" step="0.01" required>
            </div>

            <div style="margin-top:15px; text-align:right;">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>


    <script>
        $(function () {

            // Open modal
            $("#addBtn").on("click", function () {
                $("#addModal").dialog({
                    modal: true,
                    width: 400,
                    close: function () {
                        $("#addForm")[0].reset();
                    }
                });
            });

            // Submit form
            $("#addForm").on("submit", function (e) {
                e.preventDefault();

                $.ajax({
                    url: "<?= base_url('production-planning/store'); ?>",
                    type: "POST",
                    data: $(this).serialize() + '&<?= csrf_token() ?>=<?= csrf_hash() ?>',
                    success: function (res) {
                        alert("Record added successfully");
                        location.reload();
                    },
                    error: function () {
                        alert("Something went wrong");
                    }
                });
            });

        });
    </script>

    <script>
        $(function () {

            let draggedMachine = null;
            let pendingOrder = []; // store reordered data

            $("#sortable").sortable({
                axis: "y",
                helper: "clone",

                start: function (event, ui) {
                    draggedMachine = ui.item.data("machine");
                },

                sort: function (event, ui) {
                    let $currentRow = ui.placeholder.prev().length
                        ? ui.placeholder.prev()
                        : ui.placeholder.next();

                    if ($currentRow.length) {
                        let targetMachine = $currentRow.data("machine");

                        if (targetMachine !== draggedMachine) {
                            $("#sortable").sortable("cancel");
                        }
                    }
                },

                update: function () {
                    pendingOrder = [];

                    $("#sortable tr:visible").not("#noDataRow").each(function () {
                        pendingOrder.push({
                            id: $(this).data("id"),
                            machine: $(this).data("machine")
                        });
                    });

                    console.log("Order updated locally", pendingOrder);
                }
            });

            $("#saveOrderBtn").on("click", function () {

                if (!pendingOrder.length) {
                    alert("No changes to save");
                    return;
                }

                $.ajax({
                    url: "<?= base_url('production-planning/updateProductionPlanningOrder'); ?>",
                    type: "POST",
                    data: {
                        order: pendingOrder,
                        <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                    },
                    success: function () {
                        alert("Order saved successfully");
                    },
                    error: function () {
                        alert("Failed to save order");
                    }
                });
            });

        });
    </script>


    <script>
        $(function () {

            const colors = [
                '#f9f9ff',
                '#fff9f9',
                '#f9fff9',
                '#fffde7',
                '#e3f2fd',
                '#fce4ec'
            ];

            let machineColorMap = {};
            let colorIndex = 0;

            $(".machine-row").each(function () {
                let machine = $(this).data("machine");

                if (!machineColorMap[machine]) {
                    machineColorMap[machine] = colors[colorIndex % colors.length];
                    colorIndex++;
                }

                $(this).css("background-color", machineColorMap[machine]);
            });

        });
    </script>

    <script>
        $(function () {

            $("#filterForm").on("submit", function (e) {
                e.preventDefault();

                let fromDate = $("#fromDate").val();
                let toDate = $("#toDate").val();
                let machine = $("#machineFilter").val();

                if (!machine) {
                    alert("Please select a machine");
                    return;
                }

                let visibleCount = 0;

                $("#sortable tr").not("#noDataRow").each(function () {

                    let rowMachine = $(this).data("machine");

                    if (!rowMachine) {
                        $(this).hide();
                        return true;
                    }

                    rowMachine = rowMachine.toString();

                    let rowFromText = $(this).find("td:eq(7)").text().trim();
                    let rowToText = $(this).find("td:eq(8)").text().trim();

                    let showRow = true;

                    if (rowMachine !== machine) {
                        showRow = false;
                    }

                    if (fromDate) {
                        let rowFrom = new Date(rowFromText.replace(' ', 'T'));
                        let from = new Date(fromDate);
                        if (rowFrom < from) showRow = false;
                    }

                    if (toDate) {
                        let rowTo = new Date(rowToText.replace(' ', 'T'));
                        let to = new Date(toDate);
                        if (rowTo > to) showRow = false;
                    }

                    $(this).toggle(showRow);

                    if (showRow) visibleCount++;
                });


                $("#noDataRow").toggle(visibleCount === 0);
            });

        });
    </script>


    <script>
        $(function () {

            const $machineSelect = $("#machineFilter");
            const firstMachineValue = $machineSelect.find("option[value!='']").first().val();

            if (firstMachineValue) {
                $machineSelect.val(firstMachineValue);
            }

            $("#filterForm").trigger("submit");

        });
    </script>

    <script>
        $(function () {

            function getNowForDateTimeLocal() {
                const now = new Date();
                now.setSeconds(0, 0);
                return now.toISOString().slice(0, 16);
            }

            const now = getNowForDateTimeLocal();

            // Disable past date/time
            $("#fromDate").attr("min", now);
            $("#toDate").attr("min", now);

            // ensure To Date is always >= From Date
            $("#fromDate").on("change", function () {
                const fromVal = $(this).val();
                if (fromVal) {
                    $("#toDate").attr("min", fromVal);
                }
            });

        });
    </script>

</body>

</html>