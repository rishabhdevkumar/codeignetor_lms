<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row">

    <form method="post" action="<?= base_url('FinishStock/insertData'); ?>" enctype="multipart/form-data">
        <div class="col-sm-3" style="float:left;margin-top:20px"></div>
        <div class="col-sm-6" style="float:left;margin-top:20px;">
            <div class="bg-white shadow-sm">
                <div style="background-color:#efd6bb; color:#000" class="ibox-title">
                    <h5>
                        <?php echo $title; ?>
                    </h5>
                </div>
                <div class="ibox-content">
                    <div class="form-horizontal">
                        <div class="form-group">
                        <div class="row">

                            <div class="col-sm-6 col-xs-12">
                                <label class="text-secondary">Finish Material Code<em>*</em></label>
                                <input type="text" class="form-control" name="finish_material_code"
                                    id="finish_material_code" required>
                            </div>

                            <div class="col-sm-6 col-xs-12">
                                <label class="text-secondary">SAP Plant<em>*</em></label>
                                <input type="text" class="form-control" name="sap_plant" id="sap_plant" required>
                            </div>

                        </div>
</div>

                        <div class="form-group">
                        <div class="row">

                            <div class="col-sm-6 col-xs-12">
                                <label class="text-secondary">Stock Quantity<em>*</em></label>
                                <input type="number" class="form-control" name="stock_qty" id="stock_qty" required>
                            </div>

                            <div class="col-sm-6 col-xs-12">
                                <label class="text-secondary">Balance Quantity<em>*</em></label>
                                <input type="number" class="form-control" name="balance_qty" id="balance_qty" required>
                            </div>

                        </div>
</div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-success btn-sm">
                                    Save
                                </button>
                                <a href="<?= base_url('FinishStock'); ?>" class="btn btn-secondary btn-sm">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>