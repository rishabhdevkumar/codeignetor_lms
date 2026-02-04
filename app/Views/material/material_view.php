
<div class="card mt-4">

    <div class="row">
        <div class="col-12">

                <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#FFE0B5;">
                    <h5><?php echo $title; ?> Details</h5>

                    <a href="<?php echo base_url(); ?>Material/add"
                       class="btn-success btn-outline-warning text-dark btn-sm">
                        <i class="fa fa-plus p-1"></i>Add New
                    </a>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="tbl"
                               class="table table-striped table-bordered table-hover dataTables-example">

                            <thead class="text-center">
                                <tr>
                                    <th style="background-color:#efd6bb; color:#000">Sl.No</th>
                                    <th style="background-color:#efd6bb; color:#000">Material Code</th>
                                    <th style="background-color:#efd6bb; color:#000">Material Description</th>
                                    <th style="background-color:#efd6bb; color:#000">SAP Plant</th>
                                    <th style="background-color:#efd6bb; color:#000">Material Grade</th>
                                    <th style="background-color:#efd6bb; color:#000">GSM</th>
                                    <th style="background-color:#efd6bb; color:#000">View</th>
                                    <th style="background-color:#efd6bb; color:#000">Edit</th>
                                </tr>
                            </thead>

                            <tbody id="tbody">
                                <?php
                                $ctr = 1;
                                if ($material != false) {
                                    foreach ($material as $k => $v) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $ctr; ?></td>
                                        <td class="text-center"><?php echo $v["FINISH_MATERIAL_CODE"]; ?></td>
                                        <td class="text-center"><?php echo $v["DESCRIPTION"]; ?></td>
                                        <td class="text-center"><?php echo $v["SAP_PLANT"]; ?></td>
                                        <td class="text-center"><?php echo $v["GRADE"]; ?></td>
                                        <td class="text-center"><?php echo $v["GSM"]; ?></td>

                                        <td class="text-center">
                                            <a href="<?php echo base_url(); ?>Material/view/<?php echo $v["ID"]; ?>"
                                               class="btn btn-sm btn-info text-white">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>

                                        <td class="text-center">
                                            <a href="<?php echo base_url(); ?>Material/edit/<?php echo $v["ID"]; ?>"
                                               class="btn btn-sm btn-warning text-dark">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                        $ctr++;
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
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

