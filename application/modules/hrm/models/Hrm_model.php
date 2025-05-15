<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hrm_model extends CI_Model
{

    public function create_designation($data = [])
    {
        return $this->db->insert('designation', $data);
    }

    public function update_designation($data = [])
    {
        return $this->db->where('id', $data['id'])
            ->update('designation', $data);
    }


    public function single_designation_data($id)
    {
        return $this->db->select('*')
            ->from('designation')
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function delete_designation($id)
    {
        $this->db->where('id', $id)
            ->delete("designation");
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }


    public function designation_list()
    {
        return $this->db->select('*')
            ->from('designation')
            ->get()
            ->result_array();
    }

    /*employee part*/
    public function single_employee_data($id)
    {
        return $this->db->select('*')
            ->from('employee_history')
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function create_employee($data = [])
    {
        $this->db->insert('employee_history', $data);

        $id = $this->db->insert_id();
        $coa = $this->headcode();

        if ($coa->HeadCode != NULL) {
            $headcode = $coa->HeadCode + 1;
        } else {
            $headcode = "2116000001";
        }


        $sub_acc = [
            'subTypeId'   => 2,
            'name'        => $data['first_name'] . " " . $data['last_name'],
            'referenceNo' =>  $id,
            'status'      => 1,
            'created_date' => date("Y-m-d"),

        ];
        $this->db->insert('acc_subcode', $sub_acc);

        $c_acc = $id . '-' . $data['first_name'] . '' . $data['last_name'];
        $createby = $this->session->userdata('id');
        $createdate = date('Y-m-d H:i:s');
        $employee_coa = [
            'HeadCode'         => $headcode,
            'HeadName'         => $c_acc,
            'PHeadName'        => 'Employee Ledger',
            'HeadLevel'        => '4',
            'IsActive'         => '1',
            'IsTransaction'    => '1',
            'IsGL'             => '0',
            'HeadType'         => 'L',
            'IsBudget'         => '0',
            'IsDepreciation'   => '0',
            'DepreciationRate' => '0',
            'CreateBy'         => $createby,
            'CreateDate'       => $createdate,
        ];
        $this->db->insert('acc_coa', $employee_coa);
        return true;
    }

    public function headcode()
    {
        $query = $this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '2116000%'");
        return $query->row();
    }

    public function designation_dropdown()
    {
        $this->db->select('*');
        $this->db->from('designation');
        $query = $this->db->get();
        $data = $query->result();
        $list[''] = display('select_option');
        if (!empty($data)) {
            foreach ($data as  $value) {
                $list[$value->id] = $value->designation;
            }
        }
        return $list;
    }

    public function employee_dropdown()
    {
        $this->db->select('*');
        $this->db->from('employee_history');
        $this->db->where('status', 1);


        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function shift_dropdown()
    {
        $this->db->select('*');
        $this->db->from('shift');
        $this->db->where('status', 1);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


    public function update_employee($data = [])
    {
        return $this->db->where('id', $data['id'])
            ->update('employee_history', $data);
    }

    // Employee list
    public function employee_list()
    {
        $this->db->select("a.*,b.designation,IF(a.status = 1, 'Active', 'Inactive') as status_label");
        $this->db->from('employee_history a');
        $this->db->join('designation b', 'a.designation = b.id', 'left');
        $this->db->order_by('a.id', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function employee_details($id)
    {
        $this->db->select('a.*,b.designation');
        $this->db->from('employee_history a');
        $this->db->join('designation b', 'a.designation = b.id');
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function delete_employee($id)
    {
        $this->db->where('id', $id)
            ->delete("employee_history");
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }


    //Calender Events
    public function create_calenderevent($data = [])
    {
        return $this->db->insert('calender_events', $data);
    }

    public function update_calenderevent($data = [])
    {
        return $this->db->where('id', $data['id'])
            ->update('calender_events', $data);
    }


    public function single_calenderevent_data($id)
    {
        return $this->db->select('*')
            ->from('calender_events')
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function delete_calenderevent($id)
    {
        $this->db->where('id', $id)
            ->delete("calender_events");
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }


    public function calenderevent_list()
    {
        return $this->db->select('*')
            ->from('calender_events')
            ->get()
            ->result_array();
    }





    //Calender Events
    public function create_shift($data = [])
    {
        return $this->db->insert('shift', $data);
    }

    public function update_shift($data = [])
    {
        return $this->db->where('id', $data['id'])
            ->update('shift', $data);
    }


    public function single_shift_data($id)
    {
        return $this->db->select('*')
            ->from('shift')
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function delete_shift($id)
    {
        $this->db->where('id', $id)
            ->delete("shift");
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }


    public function shift_list()
    {
        return $this->db->select('*')
            ->from('shift')
            ->where_not_in('id', [1])
            ->get()
            ->result_array();
    }





    public function create_shiftassignment($data = [])
    {
        return $this->db->insert('shift_assignment', $data);
    }


    public function manage_ots($year, $month)
    {
        $sql = "SELECT sa.date,eh.first_name,eh.last_name,s.stime,s.etime,sa.ot,sa.shift,sa.employee
        FROM `shift_assignment` sa
        INNER JOIN employee_history eh on eh.id=sa.employee
         INNER JOIN shift s on s.id=sa.shift
         where sa.month='" . $month . "' and sa.year='" . $year . "'
         ORDER by sa.date,sa.shift,sa.ot";
        $query = $this->db->query($sql);
        $data  = $query->result_array();
        return $data;
    }

    public function att_employees($year, $month)
    {
        $this->db->select('eh.first_name, eh.last_name, eh.employee_zktec_id, eh.id,eh.basicsalary');
        $this->db->from('shift_assignment a');
        $this->db->join('employee_history eh', 'eh.id = a.employee');
        $this->db->where('a.month =', $month);
        $this->db->where('a.year =', $year);
        $this->db->group_by('eh.id');
        $this->db->order_by('eh.first_name');
        $query = $this->db->get();

        return $query->result_array();
    }


    public function getattendendance($sdate, $edate)
    {
        $this->db->select('employee_zktec_id, date, sign_in, sign_out, staytime');
        $this->db->from('attendance');
        $this->db->where('date >=', $sdate);
        $this->db->where('date <=', $edate);
        $query = $this->db->get();
        return $query->result_array();
    }
}
