<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <h4><?php echo display('calenderevent_list') ?></h4>
                </div>
            </div>
            <div class="panel-body" style="background-color:#B2BEB5">
                <div class="table-responsive">
                    <table id="" class="table table-bordered datatable">
                        <thead>
                            <tr>
                                <th class="text-center"><?php echo display('sl') ?></th>
                                <th class="text-center"><?php echo display('name') ?></th>
                                <th class="text-center"><?php echo display('date') ?></th>
                                <th class="text-center"><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($calenderevent_list) {
                            ?>

                                <?php
                                $sl = 1;
                                foreach ($calenderevent_list as $designations) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $sl; ?></td>
                                        <td class="text-center"><?php echo html_escape($designations['name']); ?></td>
                                        <td class="text-center"><?php echo html_escape($designations['date']); ?></td>
                                        <td>
                                            <center>
                                                <?php echo form_open() ?>
                                                <?php if ($this->permission1->method('calenderevent_list', 'update')->access()) { ?>
                                                    <a href="<?php echo base_url() . 'addcalender_events/' . $designations['id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                <?php } ?>
                                                <?php if ($this->permission1->method('calenderevent_list', 'delete')->access()) { ?>
                                                    <a href="<?php echo base_url('hrm/hrm/bdtask_deletecalenderevent/' . $designations["id"]) ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo display('are_you_sure') ?>')" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                <?php } ?>

                                                <?php echo form_close() ?>
                                            </center>
                                        </td>
                                    </tr>

                            <?php
                                    $sl++;
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