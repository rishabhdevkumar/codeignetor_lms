<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h5><?php echo $title; ?></h5>
            </div>

            <div class="ibox-content">

                <div class="row">
                    <div class="col-sm-3">
                        <a href="<?php echo base_url(); ?>FinishStock/add" class="btn btn-outline-secondary btn-sm">
                           <strong>Add Finish Stock</strong>
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Finish Material Code</th>
                                <th>SAP Plant</th>
                                <th>Stock Qty</th>
                                <th>Balance Qty</th>
                                <th>View</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        $ctr = 1;
                        if ($finishstock!=false) {
                            foreach ($finishstock as $k=>$v) {
                        ?>
                            <tr>
                                <td><?= $ctr++; ?></td>
                                <td><?php echo($finishstock[$k]['FINISH_MATERIAL_CODE']); ?></td>
                                <td><?php echo($finishstock[$k]['SAP_PLANT']); ?></td>
                                <td><?php echo($finishstock[$k]['STOCK_QTY']); ?></td>
                                <td><?php echo($finishstock[$k]['BALANCE_QTY']); ?></td>
                                <td><a href="<?php echo base_url();?>FinishStock/view/<?php echo $finishstock[$k]["PP_ID"];?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-eye"></i></a></td>

                                <td><a href="<?php echo base_url();?>FinishStock/edit/<?php echo $finishstock[$k]["PP_ID"];?>" class="btn btn-outline-success btn-sm">
                                        <i class="fa fa-pencil"></i></a></td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="7" class="text-center text-danger">
                                    No records found
                                </td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
