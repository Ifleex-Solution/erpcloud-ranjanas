<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <h4><?php echo $title ?> </h4>
                </div>
            </div>
            <div class="panel-body" style="background-color:#B2BEB5">

                <div class="row">
                    <div class="col-sm-12 col-md-12">


                        <?php echo  form_open('hrm/attendance/bdtask_create_atten') ?>
                        <div class="form-group row">
                            <label for="employee_id" class="col-sm-3 col-form-label"><?php echo display('employee_name') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <?php if ($this->session->userdata('isAdmin') == 1) { ?>
                                    <?php echo form_dropdown('employee_id', $dropdownatn, null, 'class="form-control" id="employee_id" required') ?>
                                <?php } else { ?>
                                    <input type="text" name="employee_name" class="form-control" value="<?php echo $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'); ?>" readonly>
                                    <input type="hidden" name="employee_id" id="employee_id" class="form-control" value="<?php echo $this->session->userdata('employee_id'); ?>">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="date" class="col-sm-3 col-form-label"><?php echo display('date') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9 picker-container">

                                <input type="text" id="date" value="<?php
                                                                    date_default_timezone_set('Asia/Colombo');

                                                                    echo  date('Y-m-d'); ?>" name="date" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="time" class="col-sm-3 col-form-label"><?php echo display('sign_in') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9 picker-container">

                                <input type="text" id="timepicker-12-hr" name="intime" class="form-control timepicker" required="">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="time" class="col-sm-3 col-form-label"><?php echo display('sign_out') ?> </label>
                            <div class="col-sm-9 picker-container">

                                <input type="text" id="timepicker-12-hr" name="outtime" class="form-control timepicker" >
                            </div>
                        </div>

                        <div class="form-group text-right">

                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                        </div>
                        <?php echo form_close() ?>


                    </div>
                </div>

            </div>
        </div>
    </div>

</div>