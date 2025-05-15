<style>
    .panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .button-group {
        display: flex;
        gap: 10px;
        /* Optional: Adds space between buttons */
    }
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading d-flex justify-content-between align-items-center" style="background-color:#B2BEB5">
                <h4>Timesheet Report</h4>
            </div>

            <div class="panel-body" style="background-color:#B2BEB5">
                <div class="row">
                    <div class="panel-body">
                        <?php echo form_open('timesheet_report/get_timesheet') ?>
                        <div class="form-group row">
                            <label for="date" class="col-sm-3 col-form-label"><?php echo display('date') ?>
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input name="date" class="datepicker form-control" value="<?php echo date("Y-m-d") ?>"
                                    type="text" id="date" required>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success w-md m-b-5">Generate Report</button>
                        </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>