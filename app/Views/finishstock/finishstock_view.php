<div class="card mt-4">
<div class="row">
    <div class="col-12">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#FFE0B5;">
                <h5><?php echo $title; ?> Details</h5>
                <a href="<?php echo base_url(); ?>FinishStock/add" class="btn btn-outline-success btn-sm">
                    <strong>Add Finish Stock</strong>
                </a>
            </div>

                <div class="card-body">
                <div class="table-responsive">
                    <table id="tbl" class="table table-striped table-bordered table-hover dataTables-example">
                        <thead class="text-center">
                            <tr>
                                <th style="background-color:#efd6bb; color:#000;">Sl.No</th>
                                <th style="background-color:#efd6bb; color:#000">Finish Material Code</th>
                                <th style="background-color:#efd6bb; color:#000">SAP Plant</th>
                                <th style="background-color:#efd6bb; color:#000">Stock Qty</th>
                                <th style="background-color:#efd6bb; color:#000">Balance Qty</th>
                                <th style="background-color:#efd6bb; color:#000">View</th>
                                <th style="background-color:#efd6bb; color:#000">Edit</th>
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
</div>

<script>
$(document).ready(function()
{
	
    $('.dataTables-example').
	DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend: 'copy', title: 'Material', exportOptions: {columns: [ 0, 1,2,3,4] }},
                    {extend: 'csv', title: 'Material', exportOptions: {columns: [  0, 1,2,3,4] }},
                    {extend: 'excel', title: 'Material', exportOptions: {columns: [  0, 1,2,3,4] }},
                    {extend: 'pdf', title: 'Material', exportOptions: {columns: [  0, 1,2,3,4] }},							 									
                    {
						 extend: 'print',
						 title: 'Material',
                         exportOptions: 
						 {
						 columns: [0, 1,2,3,4]
						 },
                         					 
						 customize: function (win)
						 {
								$(win.document.body).addClass('white-bg');
								$(win.document.body).css('font-size', '10px');

								$(win.document.body).find('table')
										.addClass('compact')
										.css('font-size', 'inherit');							
								
						}
                    }
                ]

            });
			
	
	
});
function del(id)
{
	var x=confirm("Do you want to delete this record ???");
	if(x==true)
	{
		return true;
	}
	else
	{
		return false;
	}
}

</script>

