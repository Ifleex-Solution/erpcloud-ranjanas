<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">

                <div class="panel-title">
                    <!-- Back Button -->
                    <h4>Employee Wise Break Report </h4>

                </div>
            </div>
            <div class="panel-body" style="background-color:#B2BEB5">
                <div class="row" id="printableArea">


                    <?php if (empty($query)) { ?>
                        <p class="text-center">No information found for the specified date range</p>
                    <?php } else { ?>
                        <table class="datatable table table-bordered">
                            <caption>
                                <center>
                                    <?php echo display('from') . ' -'  . $start_date . '&nbsp;&nbsp;&nbsp;' . display('to') . ' -' . $end_date ?>
                                    &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                                    <?php echo display('name'); ?>:
                                    <?php
                                    echo html_escape($ab[0]['first_name']) . " " . html_escape($ab[0]['last_name']);
                                    ?>
                                </center>
                            </caption>
                            <thead>
                                <th><?php echo display('sl'); ?></th>
                                <th><?php echo display('date'); ?></th>
                                <th><?php echo display('break_in'); ?></th>
                                <th><?php echo display('break_out'); ?></th>
                                <th>Break Time (h) </th>
                            </thead>
                            <tbody>

                                <?php
                                $x = 1;
                                foreach ($query as $qr) { ?>
                                    <tr>
                                        <td><?php echo $x++; ?></td>
                                        <td><?php echo html_escape($qr->date); ?></td>
                                        <td><?php
                                            if (!empty($qr->break_in)) {
                                                echo html_escape(date("h:i A", strtotime($qr->break_in)));
                                            } else {
                                                echo '';
                                            }
                                            ?></td>
                                        <td><?php
                                            if (!empty($qr->break_out)) {
                                                echo html_escape(date("h:i A", strtotime($qr->break_out)));
                                            } else {
                                                echo '';
                                            }
                                            ?></td>

                                        <td>
                                            <?php
                                            if (!empty($qr->break_out)) {
                                                $break_hour = strtotime($qr->break_out) - strtotime($qr->break_in);
                                                $b_hours = floor($break_hour / 3600);
                                                $b_minutes = floor(($break_hour % 3600) / 60);
                                                $b_secounds = $break_hour % 60;

                                                $break_hour = str_pad($b_hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($b_minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($b_secounds, 2, '0', STR_PAD_LEFT);
                                                list($hours, $minutes, $seconds) = explode(":", $break_hour);
                                                $total_seconds += ($hours * 3600) + ($minutes * 60) + $seconds;
                                            } else {
                                                $break_hour = null;
                                                $total_seconds += 0;
                                            }
                                            ?>

                                            <?php echo html_escape($break_hour); ?>
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
                    <?php } ?>

                    <br>
                </div>
            </div>
        </div>
    </div>
</div>