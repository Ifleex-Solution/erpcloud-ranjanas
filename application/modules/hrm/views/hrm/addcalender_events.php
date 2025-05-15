<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <h4><?php echo $title ?> </h4>
                </div>
            </div>

            <div class="panel-body" style="background-color:#B2BEB5">
                <?php echo form_open('addcalender_events/' . $calenderevent->id, 'class="" id="addcalender_events"') ?>


                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label"><?php echo display('date') ?> <i class="text-danger"> * </i>:</label>
                    <div class="col-sm-4">


                    <input type="text" id="date" value="<?php
                                         date_default_timezone_set('Asia/Colombo');

                                         if($calenderevent->id){
                                              echo $calenderevent->date;
                                         }else{
                                            echo  date('Y-m-d');
                                         }
                                 ?>" name="date" class="form-control datepicker">                    </div>
                </div>

                <div class="form-group row">
                    <label for="details" class="col-sm-2 text-right col-form-label"><?php echo display('name') ?> <i class="text-danger">* </i>:</label>
                    <div class="col-sm-4">


                        <input type="text" name="name" class="form-control" id="name" placeholder="<?php echo display('name') ?>" value="<?php echo $calenderevent->name?>" >
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-sm-6 text-right">


                        <button type="submit" class="btn btn-success ">
                            <?php echo (empty($calenderevent->id) ? display('save') : display('update')) ?></button>



                    </div>
                </div>


                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>