<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"> 
                <h5><?php echo $title; ?> <small></small></h5>  
            </div>

            <div class="ibox-content">
                
                <div class="row">	
                    <div class="col-sm-3"> 
                        <a href="<?php echo base_url(); ?>CustomerQuota/add" class="btn btn-outline-info btn-sm"><strong>Add customer quota</strong></a>	
                    </div>
                </div> 

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
                                <th>Delete</th>
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
                                    <a href="<?php echo base_url(); ?>CustomerQuota/view/<?php echo $customerquota[$k]["PP_ID"]; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo base_url(); ?>CustomerQuota/edit/<?php echo $customerquota[$k]["PP_ID"]; ?>" class="btn btn-outline-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo base_url(); ?>CustomerQuota/delete/<?php echo $customerquota[$k]["PP_ID"]; ?>" 
                                        class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">
                                        <i class="fa fa-trash"></i>
                                    </a></td>
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
