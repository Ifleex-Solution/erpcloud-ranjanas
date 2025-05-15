<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <h4><?php echo $title ?> </h4>
                </div>
            </div>

            <div class="panel-body" style="background-color:#B2BEB5">
                <?php echo form_open('add_shifts/' . $shift->id, 'class="" id="addcalender_events"') ?>


                <div class="form-group row">
                    <label for="shift" class="col-sm-2 text-right col-form-label">Start Time <i class="text-danger"> * </i>:</label>
                    <div class="col-sm-4">
                    <input type="time" id="stime" value="<?php
                                                                date_default_timezone_set('Asia/Colombo');

                                                                if ($shift->id) {
                                                                    echo $shift->stime;
                                                                } else {
                                                                    echo date('H:i');
                                                                }
                                                                ?>" name="stime" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="shift" class="col-sm-2 text-right col-form-label">End Time <i class="text-danger"> * </i>:</label>
                    <div class="col-sm-4">


                        <input type="time" id="etime" value="<?php
                                                                date_default_timezone_set('Asia/Colombo');

                                                                if ($shift->id) {
                                                                    echo $shift->etime;
                                                                } else {
                                                                    echo date('H:i');
                                                                }
                                                                ?>" name="etime" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="status" class="col-sm-2 text-right col-form-label">Status<i class="text-danger">*</i>:</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="status" name="status" required tabindex="-1" aria-hidden="true">
                            <option value="">Select One</option>
                            <option value="1" <?php echo ($shift->status == 1) ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo ($shift->status == 0) ? 'selected' : ''; ?>>Inactive</option>
                        </select>

                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-sm-6 text-right">


                        <button type="submit" class="btn btn-success ">
                            <?php echo (empty($shift->id) ? display('save') : display('update')) ?></button>



                    </div>
                </div>


                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

    });
</script>