<div class="row">
	<div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                        <h4><?php echo $title ?> </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTableExample3" class="table table-bordered table-striped table-hover">
                                <thead>
                        <tr>
                            <th><?php echo display('sl')?></th>
                            <th class="text-center">Counter Code</th>

                            <th class="text-center">Counter Name</th>
                            <th class="text-center"><?php echo display('status') ?></th>
                            <th class="text-center"><?php echo display('action') ?></th>
                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sl = 1;
                                    if ($countercode_list) {
                                    	foreach($countercode_list as $countercodes){
                                        ?>
                                    
                                        <tr>
                                   <td><?php echo $sl++;?></td>
                                   <td class="text-center"><?php echo $countercodes->counter_code?></td>

                                    <td class="text-center"><?php echo $countercodes->countercode_name?></td>
                                  <td class="text-center"><?php if($countercodes->status ==1){echo display('active');}else{
                                  	echo display('inactive');
                                  }?></td>
                                    <td>
                                    <center>
                                        
                                        <?php if($this->permission1->method('manage_countercode','update')->access()){ ?>
                                        <a href="<?php echo base_url().'countercode_form/'.$countercodes->countercode_id; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <?php }?>
                                      <?php if($this->permission1->method('manage_countercode','delete')->access()){ ?>
                                        <a href="<?php echo base_url().'product/product/bdtask_deletecountercode/'.$countercodes->countercode_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure To Want To Delete ?')" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    <?php }?>
                                            
                                    </center>
                                    </td>
                                    </tr>
                                   
                                    <?php
                                }
                                }else{?>

                                	<tr>
                                		<td colspan="5" class="text-center"><?php echo display('no_record_found')?></td>
                                	</tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
</div>