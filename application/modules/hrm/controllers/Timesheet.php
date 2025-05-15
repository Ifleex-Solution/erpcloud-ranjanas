<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Timesheet extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'attendance_model',
            'timesheet_model'
        ));
        if (!$this->session->userdata('isLogIn'))
            redirect('login');

    }
    public function timesheet_report()
    {
        $data['title'] = "Timesheet Report";
        $data['module'] = "hrm";
        $data['page'] = "attendance/timesheet_report";
        echo modules::run('template/layout', $data);
    }
    public function get_timesheet()
    {
        $data['title'] = "Timesheet Report";
        $format_date = $this->input->post('date', true);
        $data['date'] = $format_date;
        $data['records'] = $this->timesheet_model->getTimesheetRows($format_date);
        $data['module'] = "hrm";
        $data['page'] = "attendance/get_timesheet_report";
        echo modules::run('template/layout', $data);
    }
}

