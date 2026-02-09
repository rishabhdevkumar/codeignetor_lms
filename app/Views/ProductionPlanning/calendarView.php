<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Production Planning Records</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f3f3f3;
            font-family: "72", "Segoe UI", Arial, sans-serif;
        }

        h3 {
            font-weight: 600;
            color: #32363a;
        }

        .sap-card {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        /* Filters */
        /* Filter container */
        .filters-bar {
            background: #ffffff;
            border: 1px solid #d9d9d9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        /* Filter labels */
        .filter-label {
            font-size: 12px;
            font-weight: 600;
            color: #5f6368;
            margin-bottom: 4px;
            display: block;
        }

        /* Inputs */
        .filters-bar .form-control,
        .filters-bar .form-select {
            font-size: 13px;
            height: 36px;
            border-radius: 4px;
        }

        /* Focus state (SAP blue) */
        .filters-bar .form-control:focus,
        .filters-bar .form-select:focus {
            border-color: #0a6ed1;
            box-shadow: 0 0 0 0.1rem rgba(10, 110, 209, 0.25);
        }


        /* Table */
        table.dataTable thead th {
            background: linear-gradient(#e9edf2, #dde3e9);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        table.dataTable tbody td {
            font-size: 13px;
            color: #32363a;
        }

        table.dataTable tbody tr:hover {
            background-color: #eef4fb !important;
        }

        /* Pagination */
        .page-item.active .page-link {
            background-color: #0a6ed1;
            border-color: #0a6ed1;
        }

        .page-link {
            font-size: 13px;
            color: #0a6ed1;
        }

        /* Buttons */
        .dt-buttons .btn {
            font-size: 13px;
            margin-right: 5px;
        }

        .dataTables_info {
            font-size: 12px;
            color: #6a6d70;
        }
    </style>
</head>

<body>

    <div class="container-fluid mt-4">
        <div class="sap-card">

            <h6 class="mb-4">Production Planning: Calendar View</h6>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label for="filterVersion" class="filter-label">Version</label>
                        <input type="text" id="filterVersion" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label for="filterMachine" class="filter-label">Machine</label>
                        <select id="filterMachine" class="form-select">
                            <option value="">All Machines</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="filterMotherRoll" class="filter-label">Mother Roll</label>
                        <input type="text" id="filterMotherRoll" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label for="filterGrade" class="filter-label">Grade</label>
                        <select id="filterGrade" class="form-select">
                            <option value="">All Grades</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="filterGsm" class="filter-label">GSM</label>
                        <input type="number" id="filterGsm" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label for="fromDate" class="filter-label">From Start Date</label>
                        <input type="date" id="fromDate" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label for="toDate" class="filter-label">To Start Date</label>
                        <input type="date" id="toDate" class="form-control">
                    </div>

                </div>
            </div>



            <!-- Table -->
            <div class="table-responsive" style="max-height: 550px;">
                <table id="productionTable"
                    class="table table-bordered table-hover table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>Calendar Id</th>
                            <th>Version</th>
                            <th>Machine</th>
                            <th>Mother Roll</th>
                            <th>Grade</th>
                            <th>GSM</th>
                            <th>Qty (MT)</th>
                            <th>Start date</th>
                            <th>End Date</th>
                            <th>Utilised</th>
                            <th>Balance</th>
                            <th>KC1</th>
                            <th>KC2</th>
                            <th>NKC</th>
                            <th>KC1 Util</th>
                            <th>KC2 Util</th>
                            <th>NKC Util</th>
                            <th>KC1 Bal</th>
                            <th>KC2 Bal</th>
                            <th>NKC Bal</th>
                            <th>Uploaded By</th>
                            <th>Uploaded Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $row): ?>
                            <tr>
                                <td><?= $row['PP_ID'] ?></td>
                                <td><?= $row['VERSION'] ?></td>
                                <td><?= $row['MACHINE_TPM_ID'] ?></td>
                                <td><?= $row['SAP_MR_FG_CODE'] ?></td>
                                <td><?= $row['GRADE'] ?></td>
                                <td><?= $row['GSM'] ?></td>
                                <td><?= $row['QTY_MT'] ?></td>
                                <td><?= $row['FROM_DATE_TIME'] ?></td>
                                <td><?= $row['TO_DATE_TIME'] ?></td>
                                <td><?= $row['UTILISED_QTY'] ?></td>
                                <td><?= $row['BALANCE_QTY'] ?></td>
                                <td><?= $row['KC1_QTY_MT'] ?></td>
                                <td><?= $row['KC2_QTY_MT'] ?></td>
                                <td><?= $row['NKC_QTY_MT'] ?></td>
                                <td><?= $row['KC1_UTILISED_QTY_MT'] ?></td>
                                <td><?= $row['KC2_UTILISED_QTY_MT'] ?></td>
                                <td><?= $row['NKC_UTILISED_QTY_MT'] ?></td>
                                <td><?= $row['KC1_BALANCE_QTY_MT'] ?></td>
                                <td><?= $row['KC2_BALANCE_QTY_MT'] ?></td>
                                <td><?= $row['NKC_BALANCE_QTY_MT'] ?></td>
                                <td><?= $row['UPLOADED_BY'] ?></td>
                                <td><?= $row['UPLOADED_DATE'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {

            // Date range filter
            $.fn.dataTable.ext.search.push(function (settings, data) {

                let filterFrom = $('#fromDate').val();
                let filterTo = $('#toDate').val();

                // Row date range
                let rowFrom = new Date(data[6]);
                let rowTo = new Date(data[7]);

                // If no filter applied, show all
                if (!filterFrom && !filterTo) {
                    return true;
                }

                // Convert filter dates
                let fromDate = filterFrom ? new Date(filterFrom) : null;
                let toDate = filterTo ? new Date(filterTo) : null;

                // Case 1: Only FROM date selected
                if (fromDate && !toDate) {
                    return rowTo >= fromDate;
                }

                // Case 2: Only TO date selected
                if (!fromDate && toDate) {
                    return rowFrom <= toDate;
                }

                // Case 3: Both FROM and TO selected (OVERLAP LOGIC)
                return rowFrom <= toDate && rowTo >= fromDate;
            });


            let table = $('#productionTable').DataTable({
                pageLength: 25,
                order: [[6, 'desc']],
                responsive: true,

                dom:
                    "<'row mb-2'<'col-md-6'B><'col-md-6 text-end'l>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row mt-2'<'col-md-5'i><'col-md-7 text-end'p>>",

                buttons: [
                    { extend: 'excel' },
                    { extend: 'csv' },
                    { extend: 'print' }
                ]
            });

            // Populate dropdown filters
            function populateFilter(colIndex, selector) {
                let column = table.column(colIndex);
                let select = $(selector);

                column.data().unique().sort().each(function (d) {
                    if (d) select.append(`<option value="${d}">${d}</option>`);
                });

                select.on('change', function () {
                    column.search(this.value).draw();
                });
            }

            populateFilter(2, '#filterMachine');
            populateFilter(4, '#filterGrade');

            $('#filterVersion').on('keyup change', function () {
                table.column(1).search(this.value).draw();
            });

            $('#filterMotherRoll').on('keyup change', function () {
                table.column(1).search(this.value).draw();
            });

            $('#filterGsm').on('keyup change', function () {
                table.column(5).search(this.value).draw();
            });

            $('#fromDate, #toDate').on('change', function () {
                table.draw();
            });

        });
    </script>

</body>

</html>