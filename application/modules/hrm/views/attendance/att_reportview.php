<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <!-- Back Button -->
                    <h4>Employee Wise Report </h4>

                </div>
            </div>

            <div class="panel-body" style="background-color:#B2BEB5">
                <div class="row" id="printableArea">

                    <!-- Attendance Table -->
                    <table class="datatable table  table-bordered" width="100%">
                        <caption>
                            <center>
                                <?php echo display('from') . ' -'  . $start_date . '&nbsp;&nbsp;&nbsp;' . display('to') . ' -' . $end_date ?>
                                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                                <?php echo display('name'); ?>:
                                <?php
                                echo html_escape($firstname) . " " . html_escape($lastname);
                                ?>
                            </center>
                        </caption>
                        <thead>
                            <tr>
                                <th><?php echo display('sl'); ?></th>
                                <th><?php echo display('date'); ?></th>
                                <th><?php echo display('checkin'); ?></th>
                                <th><?php echo display('checkout'); ?></th>
                                <th>Stay Time (h) </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $x = 1;
                            foreach ($query as $qr) { ?>
                                <tr>
                                    <td><?php echo $x++; ?></td>
                                    <td><?php echo html_escape($qr->date); ?></td>
                                    <td><?php
                                            if (!empty($qr->sign_in)) {
                                                echo html_escape(date("h:i A", strtotime($qr->sign_in)));
                                            } else {
                                                echo '';
                                            }
                                            ?></td>
                                        <td><?php
                                            if (!empty($qr->sign_out)) {
                                                echo html_escape(date("h:i A", strtotime($qr->sign_out)));
                                            } else {
                                                echo '';
                                            }
                                            ?></td>
                                     <td>
                                            <?php
                                            if (!empty($qr->sign_out)) {
                                                $sign_hour = strtotime($qr->sign_out) - strtotime($qr->sign_in);
                                                $s_hours = floor($sign_hour / 3600);
                                                $s_minutes = floor(($sign_hour % 3600) / 60);
                                                $s_secounds = $sign_hour % 60;

                                                $sign_hour = str_pad($s_hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($s_minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($s_secounds, 2, '0', STR_PAD_LEFT);
                                                list($hours, $minutes, $seconds) = explode(":", $sign_hour);
                                                $total_seconds += ($hours * 3600) + ($minutes * 60) + $seconds;
                                            } else {
                                                $sign_hour =null;
                                                $total_seconds += 0;
                                            }
                                            ?>

                                            <?php echo html_escape($sign_hour); ?>
                                        </td>
                                       
                                </tr>
                               
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <td colspan="4" class="text-right"><strong>Total Stay Time:</strong></td>
                            <td><strong>
                                    <?php
                                    // Convert the total seconds back into hh:mm:ss format
                                    $total_hours = floor($total_seconds / 3600);
                                    $total_minutes = floor(($total_seconds % 3600) / 60);
                                    $total_seconds_left = $total_seconds % 60;

                                    echo sprintf("%02d:%02d:%02d", $total_hours, $total_minutes, $total_seconds_left);
                                    ?>
                                </strong></td>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>