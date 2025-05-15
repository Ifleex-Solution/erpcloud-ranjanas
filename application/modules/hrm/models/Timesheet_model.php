<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Timesheet_model extends CI_Model
{
    public function getTimesheetRows($format_date)
    {
        try {
            $raw_data = $this->getRawAttendanceData($format_date);

            $processed_data = [];
            foreach ($raw_data as $attendance) {
                $employee_id = $attendance['employee_id'];
                $date = $attendance['date'];
                $b_hours = floor($attendance['total_break_seconds'] / 3600);
                $b_minutes = floor(($attendance['total_break_seconds'] % 3600) / 60);
                $break_hours = str_pad($b_hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($b_minutes, 2, '0', STR_PAD_LEFT);


                $sign_in = $attendance['sign_in'] ? date("h:i A", strtotime($attendance['sign_in'])) : null;
                $sign_out = $attendance['sign_out'] ? date("h:i A", strtotime($attendance['sign_out'])) : null;

                if ($attendance['sign_in'] && $attendance['sign_out']) {
                    $working_hours = strtotime($attendance['sign_out']) - strtotime($attendance['sign_in']);
                    $w_hours = floor($working_hours / 3600);
                    $w_minutes = floor(($working_hours % 3600) / 60);
                    $working_hours = str_pad($w_hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($w_minutes, 2, '0', STR_PAD_LEFT);


                    $stay_time = $this->calculateStayHours($attendance['sign_in'], $attendance['sign_out'], $attendance['total_break_seconds']);
                } else {
                    $working_hours = "0.00";
                    $stay_time = "0.00";
                }

                $processed_data[] = [
                    'employee_id' => $employee_id,
                    'employee_name' => $attendance['employee_name'],
                    'date' => $date,
                    'check_in' => $sign_in,
                    'check_out' => $sign_out,
                    'break_in_out' => $attendance['break_in_out'],
                    'working_hours' => $working_hours,
                    'break_hours' => $break_hours,
                    'stay_time' => $stay_time,
                ];
            }
            return $processed_data;
        } catch (Exception $e) {
            echo "Error in fetching attendance data - " . $e->getMessage();
        }
    }


    public function getRawAttendanceData($format_date)
    {
        try {
            $query = $this->db
                ->select("
                p.first_name AS employee_id,
                p.employee_zktec_id,
                p.last_name AS employee_name,
                a.date,
                DATE_FORMAT(a.sign_in, '%h:%i %p') AS sign_in,  -- Time in AM/PM
                DATE_FORMAT(a.sign_out, '%h:%i %p') AS sign_out,  -- Time in AM/PM
                GROUP_CONCAT(
                    CONCAT(
                        IFNULL(DATE_FORMAT(b.break_in, '%h:%i %p'), 'NA'), 
                        ' - ', 
                        IFNULL(DATE_FORMAT(b.break_out, '%h:%i %p'), 'NA')
                    ) 
                    ORDER BY b.break_in ASC
                    SEPARATOR '<br>'
                ) AS break_in_out,  -- Time in AM/PM
                SUM(
                    IF(
                        b.break_in IS NOT NULL AND b.break_out IS NOT NULL,
                        TIME_TO_SEC(TIMEDIFF(b.break_out, b.break_in)),
                        0
                    )
                ) AS total_break_seconds
            ")
                ->from("attendance a")
                ->join("employee_history p", "a.employee_zktec_id = p.employee_zktec_id", "left")
                ->join("break_employee b", "b.employee_zktec_id = a.employee_zktec_id AND b.date = a.date", "left")
                ->where("a.date", $format_date)
                // ->where("a.sign_in IS NOT NULL")
                // ->where("a.sign_out IS NOT NULL")
                ->group_by(["a.employee_zktec_id", "a.date"])
                ->order_by("p.first_name", "asc")
                ->order_by("a.sign_in", "asc")
                ->get();

            return $query->result_array();
        } catch (Exception $e) {
            echo "Error in fetching attendance data - " . $e->getMessage();
            return [];
        }
    }
    // Calculate working hours
    private function calculateStayHours($sign_in, $sign_out, $break_duration)
    {
        $stay_duration = strtotime($sign_out) - strtotime($sign_in);
        $stay_duration =  $stay_duration -$break_duration;
        $s_hours = floor($stay_duration / 3600);
        $s_minutes = floor(($stay_duration % 3600) / 60);
        $working_hours = str_pad($s_hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($s_minutes, 2, '0', STR_PAD_LEFT);



        return $working_hours;
    }

    // Calculate break hours
    private function calculateBreakHours($break_times)
    {
        $total_break_seconds = 0;

        foreach ($break_times as $break) {
            if (!empty($break['break_in']) && !empty($break['break_out'])) {
                $total_break_seconds += strtotime($break['break_out']) - strtotime($break['break_in']);
            }
        }

        return number_format($total_break_seconds / 3600, 2); // Convert to hours
    }

    // Format break-in and break-out as "break_in - break_out" with line breaks
    private function formatBreakTimes($break_times)
    {
        $formatted = [];
        foreach ($break_times as $break) {
            if (!empty($break['break_in']) && !empty($break['break_out'])) {
                $formatted[] = date("h:i A", strtotime($break['break_in'])) . " - " . date("h:i A", strtotime($break['break_out']));
            }
        }
        return implode("<br>", $formatted);
    }
}
