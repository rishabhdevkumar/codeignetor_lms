<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h4><?php echo $title; ?></h4>
        </div>
    </div>

    <form method="post" action="<?= base_url('FinishStock/insertData'); ?>" enctype="multipart/form-data">

        <div class="row">

            <div class="col-md-6">
                <label>Finish Material Code</label>
                <input type="text" class="form-control" name="finish_material_code" id="finish_material_code" required>
            </div>

            <div class="col-md-6">
                <label>SAP Plant</label>
                <input type="text" class="form-control" name="sap_plant" id="sap_plant" required>
            </div>

        </div>

        <br>

        <div class="row">

            <div class="col-md-6">
                <label>Stock Quantity</label>
                <input type="number" class="form-control" name="stock_qty" id="stock_qty" required>
            </div>

            <div class="col-md-6">
                <label>Balance Quantity</label>
                <input type="number" class="form-control" name="balance_qty" id="balance_qty" required>
            </div>

        </div>

        <br>

        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-success">
                    Save
                </button>
                <a href="<?= base_url('FinishStock'); ?>" class="btn btn-secondary">
                    Back
                </a>
            </div>
        </div>

    </form>

</div>
