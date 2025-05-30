<?php
defined('BASEPATH') or exit('No direct script access allowed');
#------------------------------------    
# Author: Bdtask Ltd
# Author link: https://www.bdtask.com/
# Dynamic style php file
# Developed by :Isahaq
#------------------------------------    

class Product extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'product_model',
            'supplier/supplier_model'
        ));
        $this->load->library('ciqrcode');
        if (!$this->session->userdata('isLogIn'))
            redirect('login');
    }

    // category part
    function bdtask_category_list()
    {
        $data['title']      = "Manage Group";
        $data['module']     = "product";
        $data['page']       = "category_list";
        $data["category_list"] = $this->product_model->category_list();
        echo modules::run('template/layout', $data);
    }


    public function bdtask_category_form($id = null)
    {
        $data['title'] = display('add_category');
        #-------------------------------#
        $this->form_validation->set_rules('category_name', display('category_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['category'] = (object)$postData = [
            'category_id'      => $id,
            'category_code'    => $this->input->post('category_code', true),
            'category_name'    => $this->input->post('category_name', true),
            'status'           => $this->input->post('status', true),
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_category($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('category_form'));
                    exit;
                } else {
                    redirect("category_list");
                }
            } else {
                if ($this->product_model->update_category($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('category_form'));
                    exit;
                } else {
                    redirect("category_list");
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = display('edit_category');
                $data['category'] = $this->product_model->single_category_data($id);
            }
            $data['module']   = "product";
            $data['page']     = "category_form";
            echo Modules::run('template/layout', $data);
        }
    }

    public function bdtask_deletecategory($id = null)
    {
        if ($this->product_model->delete_category($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("category_list");
    }


    // brandcode part
    function bdtask_brandcode_list()
    {
        $data['title']      = "Manage Brandcode";
        $data['module']     = "product";
        $data['page']       = "brandcode_list";
        $data["brandcode_list"] = $this->product_model->brandcode_list();
        echo modules::run('template/layout', $data);
    }

    public function bdtask_brandcode_form($id = null)
    {
        $data['title'] = 'Add Brandcode';
        #-------------------------------#
        $this->form_validation->set_rules('brandcode_name', display('brandcode_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['brandcode'] = (object)$postData = [
            'brandcode_id'      => $id,
            'brand_code'    => $this->input->post('brand_code', true),
            'brandcode_name'    => $this->input->post('brandcode_name', true),
            'status'           => $this->input->post('status', true),
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_brandcode($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('brandcode_form'));
                    exit;
                } else {
                    redirect("brandcode_list");
                }
            } else {
                if ($this->product_model->update_brandcode($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('brandcode_form'));
                    exit;
                } else {
                    redirect("brandcode_list");
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = "Edit Brandcode";
                $data['brandcode'] = $this->product_model->single_brandcode_data($id);
            }
            $data['module']   = "product";
            $data['page']     = "brandcode_form";
            echo Modules::run('template/layout', $data);
        }
    }




    public function bdtask_deletebrandcode($id = null)
    {
        if ($this->product_model->delete_brandcode($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("brandcode_list");
    }


    // countercode part
    function bdtask_countercode_list()
    {
        $data['title']      = "Manage Countercode";
        $data['module']     = "product";
        $data['page']       = "countercode_list";
        $data["countercode_list"] = $this->product_model->countercode_list();
        echo modules::run('template/layout', $data);
    }

    public function bdtask_countercode_form($id = null)
    {
        $data['title'] = 'Add Countercode';
        #-------------------------------#
        $this->form_validation->set_rules('countercode_name', display('countercode_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['countercode'] = (object)$postData = [
            'countercode_id'      => $id,
            'counter_code'    => $this->input->post('counter_code', true),
            'countercode_name'    => $this->input->post('countercode_name', true),
            'status'           => $this->input->post('status', true),
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_countercode($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('countercode_form'));
                    exit;
                } else {
                    redirect("countercode_list");
                }
            } else {
                if ($this->product_model->update_countercode($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('countercode_form'));
                    exit;
                } else {
                    redirect("countercode_list");
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = "Edit Countercode";
                $data['countercode'] = $this->product_model->single_countercode_data($id);
            }
            $data['module']   = "product";
            $data['page']     = "countercode_form";
            echo Modules::run('template/layout', $data);
        }
    }




    public function bdtask_deletecountercode($id = null)
    {
        if ($this->product_model->delete_countercode($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("countercode_list");
    }



    // floorwisecounter part
    function bdtask_floorwisecounter_list()
    {
        $data['title']      = "Manage Floorwise";
        $data['module']     = "product";
        $data['page']       = "floorwisecounter_list";
        $data["floorwisecounter_list"] = $this->product_model->floorwisecounter_list();
        echo modules::run('template/layout', $data);
    }

    public function bdtask_floorwisecounter_form($id = null)
    {
        $data['title'] = 'Add Floorwise Counter';
        #-------------------------------#
        $this->form_validation->set_rules('floorwise_name', display('floorwise_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['floorwisecounter'] = (object)$postData = [
            'floorwisecounter_id'      => $id,
            'floorwise_name'    => $this->input->post('floorwise_name', true),
            'status'           => $this->input->post('status', true),
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_floorwisecounter($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('floorwisecounter_form'));
                    exit;
                } else {
                    redirect("floorwisecounter_list");
                }
            } else {
                if ($this->product_model->update_floorwisecounter($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('floorwisecounter_form'));
                    exit;
                } else {
                    redirect("floorwisecounter_list");
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = "Edit Floorwise Counter";
                $data['floorwisecounter'] = $this->product_model->single_floorwisecounter_data($id);
            }
            $data['module']   = "product";
            $data['page']     = "floorwisecounter_form";
            echo Modules::run('template/layout', $data);
        }
    }




    public function bdtask_deletefloorwisecounter($id = null)
    {
        if ($this->product_model->delete_floorwisecounter($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("floorwisecounter_list");
    }



    // unit part
    function bdtask_unit_list()
    {
        $data['title']      = display('manage_unit');
        $data['module']     = "product";
        $data['page']       = "unit_list";
        $data["unit_list"] = $this->product_model->unit_list();
        echo modules::run('template/layout', $data);
    }


    public function bdtask_unit_form($id = null)
    {
        $data['title'] = display('add_unit');
        #-------------------------------#
        $this->form_validation->set_rules('unit_name', display('unit_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['unit'] = (object)$postData = [
            'unit_id'      => $id,
            'unit_name'    => $this->input->post('unit_name', true),
            'status'       => $this->input->post('status', true),
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_unit($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('unit_form'));
                    exit;
                } else {
                    redirect("unit_list");
                }
            } else {
                if ($this->product_model->update_unit($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if (isset($_POST['add-another'])) {
                    redirect(base_url('unit_form'));
                    exit;
                } else {
                    redirect("unit_list");
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = display('edit_unit');
                $data['unit'] = $this->product_model->single_unit_data($id);
            }
            $data['module']   = "product";
            $data['page']     = "unit_form";
            echo Modules::run('template/layout', $data);
        }
    }



    public function bdtask_deleteunit($id = null)
    {
        if ($this->product_model->delete_unit($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect("unit_list");
    }

    // product part
    public function bdtask_product_form($id = null)
    {
        $data['title'] = display('add_product');
        $data['product_open']   = null;
        #-------------------------------#
        $this->form_validation->set_rules('product_name', display('product_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('model', display('model'), 'max_length[200]');
        $this->form_validation->set_rules('category_id', display('category'), 'required|max_length[20]');
        $this->form_validation->set_rules('price', display('price'), 'max_length[12]');

        $product_id = null;
        $sup_price = $this->input->post('supplier_price', TRUE);
        // $s_id      = $this->input->post('supplier_id',TRUE);
        $product_model = $this->input->post('model', TRUE);
        // $taxfield = $this->db->select('tax_name,default_value')
        //     ->from('tax_settings')
        //     ->get()
        //     ->result_array();

        #-------------------------------#
        $data['product'] = (object)$postData = [
            'product_id'     =>  str_pad($this->input->post('product_id', TRUE), 6, '0', STR_PAD_LEFT),
            'product_name'   => $this->input->post('product_name', TRUE),
            'category_id'    => $this->input->post('category_id', TRUE),
            'unit'           => $this->input->post('unit', TRUE),
            'tax'            => 0,
            'serial_no'      => $this->input->post('serial_no', TRUE),
            'price'          => $this->input->post('price', TRUE),
            'product_model'  => $this->input->post('model', TRUE),
            'product_details' => $this->input->post('description', TRUE),
            'product_vat'    => $this->input->post('product_vat', TRUE),
            'status'         => 1,
            'countercode_id'      => $this->input->post('countercode_id', TRUE),
            'brandcode_id'      => $this->input->post('brandcode_id', TRUE)

        ];

        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_product($postData)) {
                    $product = array(
                        'productId'    =>  str_pad($this->input->post('product_id', TRUE), 6, '0', STR_PAD_LEFT),
                    );
                    $this->db->update('productsequence', $product);
                    // for ($i = 0, $n = count($s_id); $i < $n; $i++) {

                    //    // $supp_id = $s_id[$i];


                    // }
                    #set success message
                    // $supp_prd = array(
                    //     'product_id'     => $product_id,
                    //     'supplier_id'    => 1,
                    //     'supplier_price' => $sup_price,
                    //     'products_model' => $product_model,
                    // );

                    // $this->db->insert('supplier_product', $supp_prd);


                    // $data1 = array(
                    //     'product_id'         => $product_id,
                    //     'quantity'           => $this->input->post('opening_stock', TRUE),
                    //     'batch_id'           => $this->input->post('opening_batch', TRUE),
                    //     'status'             => 1
                    // );
                    // $this->db->insert('product_purchase_details', $data1);

                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect("product_list");
            } else {

                if ($this->product_model->update_product($postData, $id)) {
                    // $this->db->where('product_id', $id)
                    //          ->delete("supplier_product");
                    // for ($i = 0, $n = count($s_id); $i < $n; $i++) {

                    //     //$supp_id = $s_id[$i];

                    // }


                    // $supp_prd = array(
                    //     'product_id'     => $id,
                    //     'supplier_id'    => 1,
                    //     'supplier_price' => $sup_price,
                    //     'products_model' => $product_model,
                    // );

                    // $this->db->insert('supplier_product', $supp_prd);

                    // $supp_prd = array(
                    //     'product_id'     =>$id,
                    //     'supplier_id'    => 1,
                    //     'supplier_price' => $sup_price,
                    //     'products_model' => $product_model,
                    // );

                    //  $this->db->where('product_id', $id)
                    // ->update('supplier_product', $supp_prd);

                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect("product_list");
            }
        } else {
            if (!empty($id)) {
                $data['title']         = display('edit_product');
                $data['product']       = $this->product_model->single_product_data($id);
            } else {
                $sql3 = "SELECT productId FROM productsequence";
                $query3 = $this->db->query($sql3);
                $result3 = $query3->row();
                $data['productId'] =  $result3->productId + 1;
            }
            $data['supplier']      = $this->product_model->supplier_list();
            // $data['vattaxinfo']    = $this->product_model->vat_tax_setting();
            $data['id']            =  $id;
            $data['category_list'] = $this->product_model->active_category();
            $data['brandcode_list'] = $this->product_model->active_brandcode();
            $data['countercode_list'] = $this->product_model->active_countercode();
            $data['floorwisecounter_list'] = $this->product_model->active_floorwisecounter();



            $data['unit_list']     = $this->product_model->active_unit();
            $data['supplier_pr']   = $this->product_model->supplier_product_list($id);
            $data['product_open']   = $this->product_model->product_opening($id);
            // $data['taxfield']      = $taxfield;
            $data['module']        = "product";
            $data['page']          = "product_form";
            echo Modules::run('template/layout', $data);
        }
    }




    public function bdtask_product_list()
    {
        $data['title']         = display('manage_product');
        $data['total_product'] = $this->db->count_all("product_information");
        $data['module']        = "product";
        $data['page']          = "product_list";
        echo modules::run('template/layout', $data);
    }

    public function CheckProductList()
    {
        $postData = $this->input->post();
        $data = $this->product_model->getProductList($postData);
        echo json_encode($data);
    }

    public function single_product_data($id)
    {
        return $this->db->select('*')
            ->from('product_information')
            ->where('product_id', $id)
            ->get()
            ->row();

        echo json_encode($data);
    }

    public function CheckProductListForLabelPrint()
    {
        $postData = $this->input->post();
        $data = $this->product_model->getProductListForLabelPrint($postData, $this->input->post('category'), $this->input->post('brand'));
        echo json_encode($data);
    }


    public function bdtask_deleteproduct($id = null)
    {
        $check_calculation = $this->product_model->check_product($id);
        if ($check_calculation > 0) {
            $this->session->set_flashdata('exception', 'You can not Delete this product because it already engaged in calculation');
            redirect("product_list");
        }
        if ($this->product_model->delete_product($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("product_list");
    }

    public function bdtask_csv_product()
    {
        $data['title']         = display('add_product_csv');
        $data['module']        = "product";
        $data['page']          = "add_product_csv";
        echo modules::run('template/layout', $data);
    }


    function uploadCsv()
    {
        $filename = $_FILES['upload_csv_file']['name'];
        $basenameAndExtension = explode('.', $filename);
        $ext = end($basenameAndExtension);
        if ($ext == 'csv') {
            $count = 0;
            $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");

            if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {
                $count = 0;
                while ($csv_line = fgetcsv($fp, 1024)) {
                    //keep this if condition if you want to remove the first row

                    for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                        //$product_id = $this->generator(10);


                        // $logFilePath = 'logfile.log';
                        // $fileHandle = fopen($logFilePath, 'a');
                        // fwrite($fileHandle, $i . "\nBatch No : " . $j . $csv_line[1]);
                        // fclose($fileHandle);


                        //counter code insert
                        // $insert_csv = array();
                        // $insert_csv['counter_code'] = (!empty($csv_line[0]) ? str_pad($csv_line[0], 6, '0', STR_PAD_LEFT) : null);
                        // $insert_csv['countercode_name'] = (!empty($csv_line[1]) ? $csv_line[1] : null);
                        // $insert_csv['department'] = (!empty($csv_line[5]) ? $csv_line[5] : null);
                        // $insert_csv['saleType'] = (!empty($csv_line[6]) ? $csv_line[6] : null);
                        // $insert_csv['count'] = (!empty($count) ? $count  : null);

                        //brand code insert
                        // $insert_csv = array();
                        // $insert_csv['brand_code'] = (!empty($csv_line[0]) ? str_pad($csv_line[0], 6, '0', STR_PAD_LEFT) : null);
                        // $insert_csv['brandcode_name'] = (!empty($csv_line[1]) ? $csv_line[1] : null);

                        //Group code insert
                        // $insert_csv = array();
                        // $insert_csv['category_code'] = (!empty($csv_line[0]) ? str_pad($csv_line[0], 6, '0', STR_PAD_LEFT) : null);
                        // $insert_csv['category_name'] = (!empty($csv_line[1]) ? $csv_line[1] : null);

                        //Product insert
                        // $sql = "SELECT category_id FROM product_category WHERE category_code = ?";
                        // $categoryName = (!empty($csv_line[2]) ? str_pad($csv_line[2], 6, '0', STR_PAD_LEFT) : null);
                        // $query = $this->db->query($sql, array($categoryName));
                        // $result = $query->row();

                        // $sql2 = "SELECT brandcode_id FROM brandcode WHERE brand_code = ?";
                        // $brandcodename = (!empty($csv_line[3]) ? str_pad($csv_line[3], 6, '0', STR_PAD_LEFT) : null);
                        // $query2 = $this->db->query($sql2, array($brandcodename));
                        // $result2 = $query2->row();

                        // $sql3 = "SELECT countercode_id FROM countercode WHERE counter_code = ?";
                        // $countercodename = (!empty($csv_line[6]) ? str_pad($csv_line[6], 6, '0', STR_PAD_LEFT) : null);
                        // $query3 = $this->db->query($sql3, array($countercodename));
                        // $result3 = $query3->row();


                        // $insert_csv = array();
                        // $insert_csv['product_id'] = (!empty($csv_line[0]) ? str_pad($csv_line[0], 6, '0', STR_PAD_LEFT) : null);
                        // $insert_csv['product_name'] = (!empty($csv_line[1]) ? $csv_line[1] : null);
                        // $insert_csv['category_id'] = ($result ? $result->category_id : null);
                        // $insert_csv['brandcode_id'] = ($result2 ? $result2->brandcode_id : null);
                        // $insert_csv['countercode_id'] = ($result3 ? $result3->countercode_id : null);

                        // if ($csv_line[5] == "Nos")
                        //     $insert_csv['unit'] = "qty";
                        // else
                        //     $insert_csv['unit'] = "m";

                        $sql3 = "SELECT productId FROM productsequence";
                        $query3 = $this->db->query($sql3);
                        $result3 = $query3->row();
                        $prod_id =  $result3->productId + 1;

                        $sql = "SELECT category_id FROM product_category WHERE category_code = ?";
                        $categoryName = (!empty($csv_line[1]) ? str_pad($csv_line[1], 6, '0', STR_PAD_LEFT) : null);
                        $query = $this->db->query($sql, array($categoryName));
                        $result = $query->row();

                        $sql2 = "SELECT brandcode_id FROM brandcode WHERE brand_code = ?";
                        $brandcodename = (!empty($csv_line[2]) ? str_pad($csv_line[2], 6, '0', STR_PAD_LEFT) : null);
                        $query2 = $this->db->query($sql2, array($brandcodename));
                        $result2 = $query2->row();

                        $sql3 = "SELECT countercode_id FROM countercode WHERE counter_code = ?";
                        $countercodename = (!empty($csv_line[3]) ? str_pad($csv_line[3], 6, '0', STR_PAD_LEFT) : null);
                        $query3 = $this->db->query($sql3, array($countercodename));
                        $result3 = $query3->row();


                        $insert_csv = array();
                        $insert_csv['product_id'] = (!empty($prod_id) ? str_pad($prod_id, 6, '0', STR_PAD_LEFT) : null);
                        $insert_csv['product_name'] = (!empty($csv_line[0]) ? $csv_line[0] : null);
                        $insert_csv['category_id'] = ($result ? $result->category_id : null);
                        $insert_csv['brandcode_id'] = ($result2 ? $result2->brandcode_id : null);
                        $insert_csv['countercode_id'] = ($result3 ? $result3->countercode_id : null);
                        $insert_csv['unit'] = $csv_line[4];
                        $insert_csv['price'] = $csv_line[5];



                        //Product update

                        // $supp_prd = array(
                        //     'price'    => $csv_line[3],
                        // );
                        // $product_id = str_pad($csv_line[0], 6, '0', STR_PAD_LEFT);
                        // $this->db->where('product_id', $product_id);
                        // $this->db->update('product_information', $supp_prd);

                        //Employee insert
                        // $insert_csv = array();
                        // $insert_csv['first_name'] = (!empty($csv_line[0]) ? str_pad($csv_line[0], 3, '0', STR_PAD_LEFT) : null);
                        // $insert_csv['last_name'] = (!empty($csv_line[1]) ? $csv_line[1] : null);
                    }
                    $count++;

                    $supp_prd = array(
                        'product_id'    => $insert_csv['product_id'],
                        'product_name' => $insert_csv['product_name'],
                        'category_id' => $insert_csv['category_id'],
                        'brandcode_id' => $insert_csv['brandcode_id'],
                        'countercode_id' => $insert_csv['countercode_id'],
                        'unit' => $insert_csv['unit'],
                        'price' => $insert_csv['price'],
                        'status' => 1
                    );

                    $this->db->insert('product_information', $supp_prd);



                    //counter code insert
                    // if ($count != null) {
                    //     $supp_prd = array(
                    //         'counter_code'    =>  $insert_csv['counter_code'],
                    //         'countercode_name' => $insert_csv['countercode_name'],
                    //         'department' => $insert_csv['department'],
                    //         'saleType' => $insert_csv['saleType'],
                    //         'status' => 1
                    //     );
                    //     $this->db->insert('countercode', $supp_prd);
                    // }

                    //brandcode insert
                    // if ($count != null) {
                    //     $supp_prd = array(
                    //         'brand_code'    =>  $insert_csv['brand_code'],
                    //         'brandcode_name' => $insert_csv['brandcode_name'],
                    //         'status' => 1
                    //     );
                    //     $this->db->insert('brandcode', $supp_prd);
                    // }

                    //groupcode insert
                    // if ($count != null) {
                    //     $supp_prd = array(
                    //         'category_code'    =>  $insert_csv['category_code'],
                    //         'category_name' => $insert_csv['category_name'],
                    //         'status' => 1
                    //     );
                    //     $this->db->insert('product_category', $supp_prd);
                    // }

                    //product insert
                    // if ($count != null) {
                    //     $supp_prd = array(
                    //         'product_id'    => $insert_csv['product_id'],
                    //         'product_name' => $insert_csv['product_name'],
                    //         'category_id' => $insert_csv['category_id'],
                    //         'brandcode_id' => $insert_csv['brandcode_id'],
                    //         'countercode_id' => $insert_csv['countercode_id'],
                    //         'unit' => $insert_csv['unit'],
                    //         'price' => $insert_csv['price'],
                    //         'status' => 1
                    //     );

                    //     $this->db->insert('product_information', $supp_prd);
                    // }

                    //employee insert
                    // if ($insert_csv['first_name'] != null) {
                    //     $supp_prd = array(
                    //         'first_name'    =>  $insert_csv['first_name'],
                    //         'last_name' => $insert_csv['last_name']
                    //     );
                    //     $this->db->insert('employee_history', $supp_prd);
                    // }


                    $count++;
                }
            }

            $this->session->set_flashdata(array('message' => display('successfully_added')));
            redirect(base_url('product_list'));
        } else {
            $this->session->set_flashdata(array('error_message' => 'Please Import Only Csv File'));
            redirect(base_url('bulk_products'));
        }
    }




    public function qrgenerator($product_id)
    {
        $config['cacheable'] = true; //boolean, the default is true
        $config['cachedir'] = ''; //string, the default is application/cache/
        $config['errorlog'] = ''; //string, the default is application/logs/
        $config['quality'] = true; //boolean, the default is true
        $config['size'] = '1024'; //interger, the default is 1024
        $config['black'] = array(224, 255, 255); // array, default is 
        $config['white'] = array(70, 130, 180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);
        $params['data'] = $product_id;
        $params['level'] = 'H';
        $params['size'] = 10;
        $image_name = $product_id . '.png';
        $params['savename'] = FCPATH . 'my-assets/image/qr/' . $image_name;
        $this->ciqrcode->generate($params);
        $product_info = $this->product_model->bdtask_barcode_productdata($product_id);
        $data = array(
            'title'           => display('qr_code'),
            'product_name'    => $product_info[0]['product_name'],
            'product_model'   => $product_info[0]['product_model'],
            'price'           => $product_info[0]['price'],
            'product_details' => $product_info[0]['product_details'],
            'qr_image'        => $image_name,
        );
        $data['module']        = "product";
        $data['page']          = "barcode_print_page";
        echo modules::run('template/layout', $data);
    }


    // bar code part
    public function barcode_print()
    {
        $data = array(
            'title'           => display('labelprint'),
        );
        $data['module']        = "product";
        $data['page']          = "barcode_print_page";
        echo modules::run('template/layout', $data);
    }


    public function label_print()
    {
        $data = array(
            'title'           => display('labelprint'),
        );
        $data['category_list'] = $this->product_model->active_category();
        $data['brandcode_list'] = $this->product_model->active_brandcode();

        $data['module']        = "product";
        $data['page']          = "label_print";
        echo modules::run('template/layout', $data);
    }

    public function printlabel()
    {
        $_SESSION['barcodelabels'] =   $this->input->post('labels');
        $_SESSION['cqty'] =   $this->input->post('cqty');

        echo json_encode($_SESSION['barcodelabels']);
    }


    public function bdtask_product_details($product_id = null)
    {
        $details_info = $this->product_model->bdtask_barcode_productdata($product_id);
        $purchaseData = $this->product_model->product_purchase_info($product_id);
        $totalPurchase = 0;
        $totalPrcsAmnt = 0;

        if (!empty($purchaseData)) {
            foreach ($purchaseData as $k => $v) {
                $purchaseData[$k]['final_date'] = $purchaseData[$k]['purchase_date'];
                $totalPrcsAmnt = ($totalPrcsAmnt + $purchaseData[$k]['total_amount']);
                $totalPurchase = ($totalPurchase + $purchaseData[$k]['quantity']);
            }
        }

        $salesData = $this->product_model->invoice_data($product_id);

        $totalSales = 0;
        $totaSalesAmt = 0;
        if (!empty($salesData)) {
            foreach ($salesData as $k => $v) {
                $salesData[$k]['final_date'] = $salesData[$k]['date'];
                $totalSales = ($totalSales + $salesData[$k]['quantity']);
                $totaSalesAmt = ($totaSalesAmt + $salesData[$k]['total_amount']);
            }
        }

        $stock = ($totalPurchase - $totalSales);
        $data = array(
            'title'               => display('product_details'),
            'product_name'        => $details_info[0]['product_name'],
            'product_model'       => $details_info[0]['product_model'],
            'price'               => $details_info[0]['price'],
            'purchaseTotalAmount' => number_format($totalPrcsAmnt, 2, '.', ','),
            'salesTotalAmount'    => number_format($totaSalesAmt, 2, '.', ','),
            'img'                 => $details_info[0]['image'],
            'total_purchase'      => $totalPurchase,
            'total_sales'         => $totalSales,
            'purchaseData'        => $purchaseData,
            'salesData'           => $salesData,
            'stock'               => $stock,
        );

        $data['module']        = "product";
        $data['page']          = "product_details";
        echo modules::run('template/layout', $data);
    }


    public function generator($lenth)
    {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 9);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
}
