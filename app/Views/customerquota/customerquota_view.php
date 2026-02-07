<div class="card mt-4">
<div class="row">
    <div class="col-12">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#FFE0B5;"> 
                <h5><?php echo $title; ?> Details</h5> 
                 <a href="<?php echo base_url(); ?>CustomerQuota/add" class="btn-success btn-outline-warning text-dark btn-sm">
                    <i class="fa fa-plus p-1"></i><strong>Add customer quota</strong></a>	 
            </div>

             <div class="card-body">


                <div class="table-responsive">						   
                    <table id="tbl" class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>								   
                                <th>Sl.</th>
                                <th>Grade</th>
                                <th>Customer Type</th>
                                <th>Quota Percentage</th>
                                <th>View</th>
                                <th>Edit</th>
                            </tr>
                        </thead>

                        <tbody id="tbody">
                        <?php
                            $ctr = 1;
                            if ($customerquota != false) {
                                foreach ($customerquota as $k => $v) {
                        ?>
                            <tr class="gradeX">
                                <td><?php echo $ctr; ?></td>
                                <td><?php echo $customerquota[$k]["GRADE"]; ?></td>
                                <td><?php echo $customerquota[$k]["CUSTOMER_TYPE"]; ?></td>
                                <td><?php echo $customerquota[$k]["QUOTA_PERCENTAGE"]; ?></td>
                                <td>
                                    <a href="<?php echo base_url(); ?>CustomerQuota/view/<?php echo $customerquota[$k]["PP_ID"]; ?>" class="btn btn-primary btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo base_url(); ?>CustomerQuota/edit/<?php echo $customerquota[$k]["PP_ID"]; ?>" class="btn btn-warning btn-sm">
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