<?php
defined('BASEPATH') or exit('No direct script access allowed');
#------------------------------------    
# Author: Bdtask Ltd
# Author link: https://www.bdtask.com/
# Dynamic style php file
# Developed by :Isahaq
#------------------------------------    

class Hrm extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'hrm_model',
            'country_model'
        ));
        if (! $this->session->userdata('isLogIn'))
            redirect('login');
    }


    public function bdtask_designation_list()
    {
        $data['title']            = display('manage_designation');
        $data['designation_list'] = $this->hrm_model->designation_list();
        $data['module']           = "hrm";
        $data['page']             = "hrm/designation_list";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_designation_form($id = null)
    {
        $data['title'] = display('add_designation');
        #-------------------------------#
        $this->form_validation->set_rules('designation', display('designation'), 'required|max_length[200]');
        $this->form_validation->set_rules('details', display('details'), 'max_length[250]');
        #-------------------------------#
        $data['designation'] = (object)$postData = [
            'id'             => $id,
            'designation'    => $this->input->post('designation', true),
            'details'        => $this->input->post('details', true),
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->hrm_model->create_designation($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect("designation_list");
            } else {
                if ($this->hrm_model->update_designation($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect("designation_list");
            }
        } else {
            if (!empty($id)) {
                $data['title']       = display('designation_update_form');
                $data['designation'] = $this->hrm_model->single_designation_data($id);
            }
            $data['module']      = "hrm";
            $data['page']        = "hrm/designation_form";
            echo Modules::run('template/layout', $data);
        }
    }

    public function bdtask_calenderevent_list()
    {
        $data['title']            = display('calenderevent_list');
        $data['calenderevent_list'] = $this->hrm_model->calenderevent_list();
        $data['module']           = "hrm";
        $data['page']             = "hrm/calenderevent_list";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_calenderevent_form($id = null)
    {
        $data['title'] = display('addcalender_events');
        #-------------------------------#
        $this->form_validation->set_rules('date', display('date'), 'required');
        $this->form_validation->set_rules('name', display('name'), 'required');
        #-------------------------------#
        $data['designation'] = (object)$postData = [
            'id'          => $id,
            'date'        => $this->input->post('date', true),
            'name'        => $this->input->post('name', true),
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->hrm_model->create_calenderevent($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect("calenderevent_list");
            } else {
                if ($this->hrm_model->update_calenderevent($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect("calenderevent_list");
            }
        } else {
            if (!empty($id)) {
                $data['title']       = "Update Calender Event";
                $data['calenderevent'] = $this->hrm_model->single_calenderevent_data($id);
            }
            $data['module']      = "hrm";
            $data['page']        = "hrm/addcalender_events";
            echo Modules::run('template/layout', $data);
        }
    }



    public function bdtask_deletecalenderevent($id = null)
    {
        if ($this->hrm_model->delete_calenderevent($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("calenderevent_list");
    }


    public function bdtask_shift_form($id = null)
    {
        $data['title'] = display('add_shifts');
        #-------------------------------#
        $this->form_validation->set_rules('stime', 'Start Time', 'required');
        $this->form_validation->set_rules('etime', 'End Time', 'required');

        $this->form_validation->set_rules('status', display('status'), 'required');

        $stime = $this->input->post('stime');
        $etime = $this->input->post('etime');

        // Convert time to comparable format (24-hour format)
        $start_time = strtotime($stime);
        $end_time = strtotime($etime);
        #-------------------------------#
        $data['designation'] = (object)$postData = [
            'id'             => $id,
            'stime'    => $this->input->post('stime'),
            'etime'        => $this->input->post('etime'),
            'status'        => $this->input->post('status', true),

        ];



        #-------------------------------#
        if ($this->form_validation->run() === true) {

            if ($start_time >= $end_time) {
                $this->session->set_flashdata('exception', 'End Time must be greater than Start Time.');
                redirect("add_shifts");
            }

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->hrm_model->create_shift($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect("shift_list");
            } else {
                if ($this->hrm_model->update_shift($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect("shift_list");
            }
        } else {
            if (!empty($id)) {
                $data['title']       = "Update Shift";
                $data['shift'] = $this->hrm_model->single_shift_data($id);
            }
            $data['module']      = "hrm";
            $data['page']        = "hrm/add_shifts";
            echo Modules::run('template/layout', $data);
        }
    }


    public function bdtask_shift_list()
    {
        $data['title']            = display('shift_list');
        $data['shift_list'] = $this->hrm_model->shift_list();
        $data['module']           = "hrm";
        $data['page']             = "hrm/shift_list";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_manage_ot()
    {
        $data['title']            = display('manage_ot');
        // $data['shift_list'] = $this->hrm_model->manage_ots();
        $data['module']           = "hrm";
        $data['page']             = "hrm/manage_ot";
        $data['employees']       = $this->hrm_model->employee_dropdown();
        $data['shifts']       = $this->hrm_model->shift_dropdown();

        echo modules::run('template/layout', $data);
    }

    public function manage_ot()
    {
        $data = $this->hrm_model->manage_ots($this->input->post('year', TRUE), $this->input->post('month', TRUE));
        echo json_encode($data);
    }

    
    public function otreport_data()
    {
        $data1 = $this->hrm_model->manage_ots($this->input->post('year', TRUE), $this->input->post('month', TRUE));
        $data2 = $this->hrm_model-> getattendendance($this->input->post('sdate', TRUE), $this->input->post('edate', TRUE));
        $data3 = $this->hrm_model-> att_employees($this->input->post('year', TRUE), $this->input->post('month', TRUE));

        
        $datapreparation = [
            'employees'      => $data3,
            'ot_data'       => $data1,
            'attendance_data'=> $data2
        ];
        
        echo json_encode($datapreparation);    
    }

    public function bdtask_deleteshift($id = null)
    {
        if ($this->hrm_model->delete_shift($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("shift_list");
    }

    public function save_shift()
    {
        $employees = $this->input->post('employees', TRUE);
        $dates = $this->input->post('dates', TRUE);

        foreach ($employees as $employee) {
            foreach ($dates as $date) {

                $this->db->where('date', $date)
                    ->where('employee', $employee)
                    ->delete("shift_assignment");

                $data1 = array(
                    'employee' =>  $employee,
                    'date'     => $date,
                    'year'     => $this->input->post('year', TRUE),
                    'month'    => $this->input->post('month', TRUE),
                    'shift'    => $this->input->post('shift', TRUE),
                    'ot'       => $this->input->post('ot', TRUE)


                );
                $this->db->insert('shift_assignment', $data1);
            }
        }

        echo json_encode("done");
    }

    public function update_shift()
    {
        $employees = $this->input->post('employees', TRUE);
        $this->db->where('date', $this->input->post('date', TRUE))
            ->where('shift', $this->input->post('oldshift', TRUE))
            ->where('ot', $this->input->post('oldot', TRUE))
            ->delete("shift_assignment");

        foreach ($employees as $employee) {
            $this->db->where('date', $this->input->post('date', TRUE))
                ->where('employee', $employee)
                ->delete("shift_assignment");
            $data1 = array(
                'employee' =>  $employee,
                'date'     => $this->input->post('date', TRUE),
                'year'     => $this->input->post('year', TRUE),
                'month'    => $this->input->post('month', TRUE),
                'shift'    => $this->input->post('shift', TRUE),
                'ot'       => $this->input->post('ot', TRUE),
            );
            $this->db->insert('shift_assignment', $data1);
        }

        echo json_encode("done");
    }

    public function delete_shift()
    {
        $this->db->where('date', $this->input->post('date', TRUE))
            ->where('shift', $this->input->post('shift', TRUE))
            ->where('ot', $this->input->post('ot', TRUE))
            ->delete("shift_assignment");

        echo json_encode("done");
    }



    public function bdtask_new_ot($id = null)
    {
        $data['title'] = display('new_ot');
        #-------------------------------#
        $this->form_validation->set_rules('employee', "Employee is Required", 'required');
        $this->form_validation->set_rules('shift', "Shift Required", 'required');
        $this->form_validation->set_rules('sdate', "Start Date Required", 'required');
        $this->form_validation->set_rules('edate', "End Date Requied", 'required');
        #-------------------------------#
        $data['designation'] = (object)$postData = [
            'id'             => $id,
            'employee'    => $this->input->post('employee', true),
            'shift'        => $this->input->post('shift', true),
            'sdate'    => $this->input->post('sdate', true),
            'edate'        => $this->input->post('edate', true)
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {
            if ($this->hrm_model->create_shiftassignment($postData)) {
                #set success message
                $this->session->set_flashdata('message', display('save_successfully'));
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            redirect("shift_assignment");
        } else {
            if (!empty($id)) {
                $data['title']       = "Update Calender Event";
                $data['calenderevent'] = $this->hrm_model->single_calenderevent_data($id);
            }
            $data['employees']       = $this->hrm_model->employee_dropdown();
            $data['shifts']       = $this->hrm_model->shift_dropdown();


            $data['module']      = "hrm";
            $data['page']        = "hrm/shift_assignment";
            echo Modules::run('template/layout', $data);
        }
    }




    public function bdtask_deletedesignation($id = null)
    {
        if ($this->hrm_model->delete_designation($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("designation_list");
    }


    /*employee part start*/
    public function bdtask_employee_form($id = null)
    {
        $data['title'] = display('add_employee');
        $this->form_validation->set_rules('first_name', display('first_name'), 'required|max_length[100]');
        // $this->form_validation->set_rules('last_name',display('last_name'),'required|max_length[100]');
        // $this->form_validation->set_rules('designation',display('designation'),'required|max_length[100]');
        // $this->form_validation->set_rules('phone',display('phone'),'max_length[20]');
        // $this->form_validation->set_rules('hrate',display('salary'),'max_length[20]');
        $this->load->library('fileupload');
        $img = $this->fileupload->do_upload(
            './my-assets/image/employee/',
            'image'

        );
        $old_image = $this->input->post('old_image');

        $data['employee'] = (object)$postData = [
            'id'            => $this->input->post('id', true),
            'first_name'    => $this->input->post('first_name', true),
            'last_name'     => $this->input->post('last_name', true),
            'designation'   =>  $this->input->post('designation', true),
            'employee_zktec_id' => $this->input->post('employee_zktec_id', true),
            'phone'         => $this->input->post('phone', true),
            'image'         => (!empty($img) ? $img : $old_image),
            // 'rate_type'     => $this->input->post('rate_type',true),
            // 'email'         => $this->input->post('email',true),
            // 'hrate'         => $this->input->post('hrate',true),
            'address_line_1' => $this->input->post('address_line_1', true),
            'status' => $this->input->post('status', true),

            'address_line_2' => $this->input->post('address_line_2', true),
            'blood_group'   => $this->input->post('blood_group', true),
            'basicsalary'       => $this->input->post('basicsalary',true),
            'city'          => $this->input->post('city', true),
            // 'zip'           => $this->input->post('zip',true),
        ];
        #-------------------------------#
        if ($this->form_validation->run()) {

            if (empty($id)) {
                if ($this->hrm_model->create_employee($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                    redirect("employee_list");
                } else {
                    $this->session->set_flashdata('error_message',  display('please_try_again'));
                    redirect("employee_form");
                }
            } else {
                if ($this->hrm_model->update_employee($postData)) {
                    $old_head    =  $this->input->post('id', true) . '-' . $this->input->post('old_first_name', true) . '' . $this->input->post('old_last_name', true);
                    $up_headname = $id . '-' . $data['first_name'] . '' . $data['last_name'];
                    $updatedby   = $this->session->userdata('id');
                    $updateddate = date('Y-m-d H:i:s');
                    $coa_inf = array(
                        'HeadName'  => $up_headname,
                        'UpdateBy'  => $updatedby,
                        'UpdateDate' => $updateddate,
                    );
                    $this->db->where('HeadName', $old_head);
                    $this->db->update('acc_coa', $coa_inf);
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                    redirect("employee_form/" . $id);
                }
                redirect("employee_list/");
            }
        } else {
            if (!empty($id)) {
                $data['employee']    = $this->hrm_model->single_employee_data($id);
                $data['title']       = display('edit_employee');
            }
            $data['country_list'] = $this->country_model->country();
            $data['desig']       = $this->hrm_model->designation_dropdown();
            $data['module']      = "hrm";
            $data['page']        = "hrm/employee_form";
            echo Modules::run('template/layout', $data);
        }
    }



    public function bdtask_employee_list()
    {
        $data['title']        = display('manage_employee');
        $data['employee_list'] = $this->hrm_model->employee_list();
        $data['module']       = "hrm";
        $data['page']         = "hrm/employee_list";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_employee_profile($id)
    {

        $data['title']      = display('employee_profile');
        $data['row']        = $this->hrm_model->employee_details($id);
        $data['module']     = "hrm";
        $data['page']       = "hrm/resumepdf";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_delete_employee($id = null)
    {
        if ($this->hrm_model->delete_employee($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("employee_list");
    }
}
