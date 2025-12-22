
<div class="row">
<div class="col-md-12">
<?php
// if($this->session->flashdata('message'))
// {

//   echo $this->session->flashdata('message');

// }
?>
</div>
</div>
<div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"> 
                         <h5><?php echo $title; ?> <small> </small></h5> 					
                      
                    </div>
					
                    <div class="ibox-content">
                        							
						<div class="row">	
						    <div class="col-sm-3"> 
							<a href="<?php echo base_url(); ?>Material/add" class="btn btn-info">Add</a>	
							</div>
						</div> 
						<div class="table-responsive">						   
							<table id="tbl" class="table table-striped table-bordered table-hover dataTables-example" >
								<thead>
								<tr>								   
									<th>Sl.</th>
									<th>Material Code</th>	
									<th>Material Description</th>
									<th>SAP Plant</th>
									<th>Material Grade</th>
									<th>GSM</th>
									<th>View</th>
									<th>Edit</th>
									<!--<th>Delete</th>-->
								</tr>
								</thead>
								<tbody id="tbody">
								
								<?php
								    $ctr=1;
                                    if($material!=false)
									{										
										foreach($material as $k=>$v)
										{
										?>								
											<tr class="gradeX">																					
												<td><?php echo $ctr;?></td>
												<td><?php echo $material[$k]["FINISH_MATERIAL_CODE"];?></td>
												<td><?php echo $material[$k]["DESCRIPTION"];?></td>
												<td><?php echo $material[$k]["SAP_PLANT"];?></td>
												<td><?php echo $material[$k]["GRADE"];?></td>
												<td><?php echo $material[$k]["GSM"];?></td>
												<td><a href="<?php echo base_url();?>Material/view/<?php echo $material[$k]["ID"];?>" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
												<td><a href="<?php echo base_url();?>Material/edit/<?php echo $material[$k]["ID"];?>" class="btn btn-warning"><i class="fa fa-pencil"></i></a></td>
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