<!-- <div class="row" style="background-color:#f0f6ff">

    <div class="col-lg-12">
        <div class="ibox float-e-margins card shadow-sm bg-white">

            <div class="ibox-title bg-primary text-white">
                <h5 class="mb-0"><?php echo $title; ?></h5>
            </div>

            <div class="ibox-content">

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <a href="<?php echo base_url(); ?>Material/add"
                           class="btn btn-success">
                            <i class="fa fa-plus"></i> Add
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tbl"
                           class="table table-striped table-bordered table-hover dataTables-example">

                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Material Code</th>
                                <th>Material Description</th>
                                <th>SAP Plant</th>
                                <th>Material Grade</th>
                                <th>GSM</th>
                                <th class="text-center">View</th>
                                <th class="text-center">Edit</th>
                            </tr>
                        </thead>

                        <tbody id="tbody">

                        <?php
                        $ctr = 1;
                        if ($material != false)
                        {
                            foreach ($material as $k => $v)
                            {
                        ?>
                            <tr>
                                <td><?php echo $ctr; ?></td>
                                <td><?php echo $material[$k]["FINISH_MATERIAL_CODE"]; ?></td>
                                <td><?php echo $material[$k]["DESCRIPTION"]; ?></td>
                                <td><?php echo $material[$k]["SAP_PLANT"]; ?></td>
                                <td><?php echo $material[$k]["GRADE"]; ?></td>
                                <td><?php echo $material[$k]["GSM"]; ?></td>

                                <td class="text-center">
                                    <a href="<?php echo base_url();?>Material/view/<?php echo $material[$k]["ID"]; ?>"
                                       class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a href="<?php echo base_url();?>Material/edit/<?php echo $material[$k]["ID"]; ?>"
                                       class="btn btn-sm btn-warning text-white">
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

</script> -->


<div class="card mt-4">

    <div class="row" style="">
        <div class="col-12">

                <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#FFE0B5;">
                    <h5 class="mb-0"><?php echo $title; ?> Details</h5>

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

