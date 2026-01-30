<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Indent Allotment notAllotted</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- SAP Fiori style -->
    <style>
        body {
            background-color: #f3f3f3;
            font-family: "72", "72 Bold", "Segoe UI", Arial, sans-serif;
        }

        h3 {
            font-weight: 600;
            color: #32363a;
            /* Dark gray Fiori heading */
            margin-bottom: 25px;
        }

        .sap-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin-bottom: 30px;
        }

        /* Table Styles */
        .table thead th {
            background-color: #e1e5ea;
            /* Light gray Fiori header */
            color: #32363a;
            font-size: 13px;
            text-transform: uppercase;
            font-weight: 600;
            border-bottom: 2px solid #cfd4d9;
        }

        .table tbody td {
            font-size: 13px;
            vertical-align: middle;
            color: #32363a;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f4f8;
            /* Light blue-gray Fiori hover */
        }

        /* Buttons */
        .btn-sap {
            background-color: #0a6ed1;
            /* SAP Fiori blue primary */
            color: #fff;
            border-radius: 4px;
            padding: 5px 15px;
            font-size: 13px;
        }

        .btn-sap:hover {
            background-color: #0858a5;
        }

        .btn-outline-sap {
            border: 1px solid #0a6ed1;
            color: #0a6ed1;
            padding: 5px 15px;
            font-size: 13px;
        }

        .btn-outline-sap:hover {
            background-color: #0a6ed1;
            color: white;
        }

        /* Modal Header */
        .modal-header {
            background-color: #0a6ed1;
            color: #fff;
            font-weight: 600;
        }

        .modal-title {
            font-size: 16px;
        }

        .modal-body label {
            font-weight: 500;
        }

        /* Table scroll */
        .table-responsive {
            max-height: 550px;
            overflow-y: auto;
        }

        /* Action buttons spacing */
        .action-btns .btn {
            margin-right: 5px;
            margin-bottom: 2px;
        }
    </style>
</head>

<body>

    <div class="mt-4" style="padding: 1rem;">

        <div class="sap-card">

            <h3 class="text-center">Indent Allotment notAllotted</h3>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle text-center">
                    <thead>
                        <tr>
                            <th>Indent No.</th>
                            <th>Indent Line Item</th>
                             <th>Material</th> 
                             <th>Quantity</th>
                            <!-- <th>From DATE/TIME</th>
                            <th>To DATE/TIME</th>
                            <th>Finishing DATE/TIME</th>
                            <th>Doorstep Del. DATE/TIME</th> -->
                             <th>Ship To Customer</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($notAllotted) && is_array($notAllotted)): ?>
                            <?php foreach ($notAllotted as $row): ?>
                                <tr>
                                    <td><?= esc($row['IN_NO']) ?></td>
                                    <td><?= esc($row['LINE_ITEM']) ?></td>
                                    <td><?= esc($row['MATERIAL']) ?></td>
                                    <td><?= esc($row['QTY']) ?></td>
                                    <td><?= esc($row['SHIPCUSTOMER']) ?></td>
                                    <td class="text-danger">
                                        <?= esc($row['STATUS']) ?>
                                    </td>
                                   
                                  


                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="18" class="text-center">No notAllotted Found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>