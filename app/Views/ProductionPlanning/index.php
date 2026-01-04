<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Production Planning Records</title>

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

            <h3 class="text-center">Production Planning Records</h3>

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

            <!-- Action Buttons -->
            <div class="mb-3 d-flex justify-content-end gap-2">
                <button class="btn btn-outline-sap btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    Upload XLSX
                </button>

                <a href="<?= base_url('planning-production/create') ?>" class="btn btn-sap btn-sm">
                    + Add New Record
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle text-center">
                    <thead>
                        <tr>
                            <th>VERSION</th>
                            <th>MACHINE</th>
                            <th>SAP MOTHER ROLL CODE</th>
                            <th>QTY (MT)</th>
                            <th>FROM DATE/TIME</th>
                            <th>TO DATE/TIME</th>
                            <th>UTILISED QTY</th>
                            <th>BALANCE QTY</th>
                            <th>KC1 QTY</th>
                            <th>KC2 QTY</th>
                            <th>KC1 UTIL</th>
                            <th>KC2 UTIL</th>
                            <th>KC1 BAL</th>
                            <th>KC2 BAL</th>
                            <th>UPLOADED BY</th>
                            <th>UPLOADED DATE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($records) && is_array($records)): ?>
                            <?php foreach ($records as $row): ?>
                                <tr>
                                    <td><?= $row['VERSION'] ?></td>
                                    <td><?= $row['MACHINE_TPM_ID'] ?></td>
                                    <td><?= $row['SAP_MR_FG_CODE'] ?></td>
                                    <td><?= $row['QTY_MT'] ?></td>
                                    <td><?= $row['FROM_DATE_TIME'] ?></td>
                                    <td><?= $row['TO_DATE_TIME'] ?></td>
                                    <td><?= $row['UTILISED_QTY'] ?></td>
                                    <td><?= $row['BALANCE_QTY'] ?></td>
                                    <td><?= $row['KC1_QTY_MT'] ?></td>
                                    <td><?= $row['KC2_QTY_MT'] ?></td>
                                    <td><?= $row['KC1_UTILISED_QTY_MT'] ?></td>
                                    <td><?= $row['KC2_UTILISED_QTY_MT'] ?></td>
                                    <td><?= $row['KC1_BALANCE_QTY_MT'] ?></td>
                                    <td><?= $row['KC2_BALANCE_QTY_MT'] ?></td>
                                    <td><?= $row['UPLOADED_BY'] ?></td>
                                    <td><?= $row['UPLOADED_DATE'] ?></td>
                                    <td class="action-btns text-center">
                                        <a href="<?= base_url('planning-production/edit/' . $row['PP_ID']) ?>"
                                            class="btn btn-outline-sap btn-sm" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <a href="<?= base_url('planning-production/delete/' . $row['PP_ID']) ?>"
                                            onclick="return confirm('Are you sure you want to delete this record?');"
                                            class="btn btn-outline-danger btn-sm" title="Delete">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="18" class="text-center">No Records Found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Upload XLSX Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Upload Excel File</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="<?= base_url('production-planning/uploadXlsx') ?>" method="post"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <label class="form-label">Choose XLSX File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sap">Upload</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>