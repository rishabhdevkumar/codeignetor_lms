<div class="row">
<div class="col-md-12">
<?php if (session()->getFlashdata('message')) : ?>
    <?= session()->getFlashdata('message'); ?>
<?php endif; ?>
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
							<a href="<?php echo base_url(); ?>User/add" class="btn btn-info">Add</a>	
							</div>
						</div> 
						<div class="table-responsive">						   
							<table id="tbl" class="table table-striped table-bordered table-hover dataTables-example" >
								<thead>
								<tr>								   
									<th>Sl.</th>
									<th>Name</th>	
									<th>User Name</th>	
									<!--<th>Password</th>-->
									<th>Status</th>
									<th>View</th>
									<th>Edit</th>
									<!--<th>Delete</th>-->
								</tr>
								</thead>
								<tbody id="tbody">
								
								<?php
								    $ctr=1;
                                    if($user!=false)
									{										
										foreach($user as $k=>$v)
										{
										?>								
											<tr class="gradeX">																					
												<td><?php echo $ctr;?></td>
												<td><?php echo $user[$k]["name"];?></td>
												<td><?php echo $user[$k]["user_name"];?></td>
												<!--<td><?php echo decrypt($user[$k]["password"],$this->config->item('enc_dec_key'));?></td>-->
												<td><?php if($user[$k]["status"]==1) echo "Active"; else echo "Deactive";?></td>
												<td><a href="<?php echo base_url();?>User/view/<?php echo $user[$k]["id"];?>" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
												<td><a href="<?php echo base_url();?>User/edit/<?php echo $user[$k]["id"];?>" class="btn btn-warning"><i class="fa fa-pencil"></i></a></td>
												<!--<td><a href="<?php echo base_url();?>User/del/<?php echo $user[$k]["id"];?>" onclick="return del(<?php echo $user[$k]["id"]; ?>)" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>-->
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
                    {extend: 'copy', title: 'Apis', exportOptions: {columns: [ 0, 1,2,3] }},
                    {extend: 'csv', title: 'Apis', exportOptions: {columns: [  0, 1,2,3] }},
                    {extend: 'excel', title: 'Apis', exportOptions: {columns: [   0, 1,2,3] }},
                    {extend: 'pdf', title: 'Apis', exportOptions: {columns: [   0, 1,2,3] }},							 									
                    {
						 extend: 'print',
						 title: 'Users',
                         exportOptions: 
						 {
						 columns: [  0, 1,2,3]
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


</script>