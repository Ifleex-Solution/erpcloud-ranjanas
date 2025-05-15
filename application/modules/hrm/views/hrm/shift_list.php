
<div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading" style="background-color:#B2BEB5">
                        <div class="panel-title">
                          <h4><?php echo display('shift_list')?></h4>
                        </div>
                    </div>
                    <div class="panel-body" style="background-color:#B2BEB5">
                        <div class="table-responsive">
                            <table id="" class="table table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('sl') ?></th>
                                        <th class="text-center">Start Time</th>
                                        <th class="text-center">End Time</th>
                                        <th class="text-center">Status</th>

                                        <th class="text-center"><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($shift_list) {
                                        ?>
                                       
                                        <?php
                                        $sl =1;
                                         foreach($shift_list as $designations){?>
                                        <tr>
                                            <td class="text-center"><?php echo $sl;?></td>
                                            <td class="text-center">
                                            <?php
                                            if (!empty($designations['stime'])) {
                                                echo html_escape(date("h:i A", strtotime($designations['stime'])));
                                            } else {
                                                echo '';
                                            }
                                            ?>
                                            </td>
                                            <td class="text-center">
                                            <?php
                                            if (!empty($designations['etime'])) {
                                                echo html_escape(date("h:i A", strtotime($designations['etime'])));
                                            } else {
                                                echo '';
                                            }
                                            ?>
                                            </td>
                                            <td class="text-center">
                                            <?php
                                            if ($designations['status']==1) {
                                                echo "Active";
                                            } else {
                                                echo 'Inactive';
                                            }
                                            ?>
                                            </td>
                                            <td>
                                    <center>
                                        <?php echo form_open() ?>
         <?php if($this->permission1->method('shift_list','update')->access()){ ?>                              
                                        <a href="<?php echo base_url() . 'add_shifts/'.$designations['id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <?php } ?>
      <?php if($this->permission1->method('shift_list','delete')->access()){ ?>                                
                                        <a href="<?php echo base_url('hrm/hrm/bdtask_deleteshift/'.$designations["id"]) ?>" class="btn btn-danger btn-sm"  onclick="return confirm('<?php echo display('are_you_sure') ?>')" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    <?php }?>
                                   
                                            <?php echo form_close() ?>
                                    </center>
                                    </td>
                                    </tr>
                                   
                                    <?php
                                    $sl++;
                                }}
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 




