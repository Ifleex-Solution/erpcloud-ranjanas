<?php
defined('BASEPATH') or exit('No direct script access allowed');
#------------------------------------    
# Author: Bdtask Ltd
# Author link: https://www.bdtask.com/
# Dynamic style php file
# Developed by :Isahaq
#------------------------------------    

require_once(__DIR__ . '/TCPDF-main/tcpdf.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class EmployeeReport extends TCPDF
{
    // Override the Header() method to remove the line
    public $Total;

    public function Header() {}

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        // $this->SetY(-15);
        // $this->SetFont('', 'B', 12);
        // $this->Cell(50, 10, "Grand Total:", 'TB', 0, 'L');
        // $this->Cell(100, 10, "", 'TB', 0, 'L');
        // $this->Cell(30, 10, number_format($this->Total, 2), 'TB', 1, 'R');
    }
}

class CounterSalesReport1 extends TCPDF
{
    // Override the Header() method to remove the line

    public function Header() {}

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        // $this->SetY(-15);
        // $this->SetFont('', 'B', 12);
        // $this->Cell(50, 10, "Grand Total:", 'TB', 0, 'L');
        // $this->Cell(100, 10, "", 'TB', 0, 'L');
        // $this->Cell(30, 10, number_format($this->Total, 2), 'TB', 1, 'R');
    }
}

class CounterSalesReport2 extends TCPDF
{
    // Override the Header() method to remove the line
    public $Total;

    public function Header() {}

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        // $this->SetY(-15);
        // $this->SetFont('', 'B', 12);
        // $this->Cell(50, 10, "Grand Total:", 'TB', 0, 'L');
        // $this->Cell(100, 10, "", 'TB', 0, 'L');
        // $this->Cell(30, 10, number_format($this->Total, 2), 'TB', 1, 'R');
    }
}

class InvoiceSummaryReport extends TCPDF
{
    // Override the Header() method to remove the line
    public $pageTotal = 0;

    public function Header()
    {
        $this->pageTotal = 0;
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetFont('', 'B', 12);
        $this->Cell(50, 10, "Page Total:", 'TB', 0, 'L');
        $this->Cell(100, 10, "", 'TB', 0, 'L');
        $this->Cell(30, 10, number_format($this->pageTotal, 2), 'TB', 1, 'R');
    }

    public function updatePageTotal($amount)
    {
        $this->pageTotal += $amount;
    }
}


class Report extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'report_model'
        ));
        if (! $this->session->userdata('isLogIn'))
            redirect('login');
    }

    /*product stock part*/
    function bdtask_stock_report()
    {
        $data['title']      = display('stock_report');
        $data['totalnumber'] = $this->report_model->totalnumberof_product();
        $data['module']     = "report";
        $data['page']       = "stock_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_checkStocklist()
    {
        // GET data
        $postData = $this->input->post();
        $data = $this->report_model->bdtask_getStock($postData);
        echo json_encode($data);
    }


    public function bdtask_cash_closing()
    {
        $data['title']      = "Reports | Daily Closing";
        $data['info']       = $this->report_model->accounts_closing_data();
        $data['pay_methods'] = $this->report_model->payment_methods();
        $data['module']     = "report";
        $data['page']       = "closing_form";
        echo modules::run('template/layout', $data);
    }

    public function add_daily_closing()
    {

        $closedata = $this->db->select('*')->from('daily_closing')->where('date', date('Y-m-d'))->get()->num_rows();
        if ($closedata > 0) {
            $this->session->set_flashdata(array('exception' => 'Already Closed Today'));
            redirect(base_url('closing_form'));
        }
        $todays_date = date("Y-m-d");
        $data = array(
            'last_day_closing'  =>  str_replace(',', '', $this->input->post('last_day_closing', TRUE)),
            'cash_in'           =>  str_replace(',', '', $this->input->post('cash_in', TRUE)),
            'cash_out'          =>  str_replace(',', '', $this->input->post('cash_out', TRUE)),
            'date'              =>  $todays_date,
            'amount'            =>  str_replace(',', '', $this->input->post('cash_in_hand', TRUE)),
            'status'            =>      1
        );
        $invoice_id = $this->report_model->daily_closing_entry($data);


        $this->session->set_flashdata(array('message' => display('successfully_added')));
        redirect(base_url('closing_report'));
    }


    public function bdtask_closing_report()
    {
        $daily_closing_data = $this->report_model->get_closing_report();
        $i = 0;

        if (!empty($daily_closing_data)) {
            foreach ($daily_closing_data as $k => $v) {
                $daily_closing_data[$k]['final_date'] = $this->occational->dateConvert(date("Y-m-d", strtotime($daily_closing_data[$k]['datetime'])));
            }
        }
        $data = array(
            'title'              => display('closing_report'),
            'daily_closing_data' => $daily_closing_data,
        );
        $data['module']   = "report";
        $data['page']     = "closing_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_closing_report_search()
    {
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $daily_closing_data = $this->report_model->get_date_wise_closing_report($from_date, $to_date);

        $i = 0;
        if (!empty($daily_closing_data)) {
            foreach ($daily_closing_data as $k => $v) {
                $daily_closing_data[$k]['final_date'] = $this->occational->dateConvert(date("Y-m-d", strtotime($daily_closing_data[$k]['datetime'])));
            }
            foreach ($daily_closing_data as $k => $v) {
                $i++;
                $daily_closing_data[$k]['sl'] = $i;
            }
        }

        $data = array(
            'title'              => display('closing_report'),
            'daily_closing_data' => $daily_closing_data,
            'from_date'          => $from_date,
            'to_date'            => $to_date,

        );

        $data['module']   = "report";
        $data['page']     = "closing_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_todays_report()
    {
        $sales_report = $this->report_model->todays_sales_report();
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $this->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }

        $purchase_report = $this->report_model->todays_purchase_report();
        $purchase_amount = 0;
        if (!empty($purchase_report)) {
            $i = 0;
            foreach ($purchase_report as $k => $v) {
                $i++;
                $purchase_report[$k]['sl'] = $i;
                $purchase_report[$k]['prchse_date'] = $this->occational->dateConvert($purchase_report[$k]['purchase_date']);
                $purchase_amount = $purchase_amount + $purchase_report[$k]['grand_total_amount'];
            }
        }

        $data = array(
            'title'           => display('todays_report'),
            'sales_report'    => $sales_report,
            'sales_amount'    => number_format($sales_amount, 2, '.', ','),
            'purchase_amount' => number_format($purchase_amount, 2, '.', ','),
            'purchase_report' => $purchase_report,
            'date'            => $today = date('Y-m-d'),
        );

        $data['module']   = "report";
        $data['page']     = "todays_report";
        echo modules::run('template/layout', $data);
    }

    public function generate_invoicesummary()
    {
        $page = 1;
        $data = isset($_SESSION['invoice_summary']) ? $_SESSION['invoice_summary'] : [];
        $pdf = new InvoiceSummaryReport('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Invoice Summary Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
        $col_width_id = 30;
        $col_width_name = 30;
        $row_height = 7.8;

        $this->addHeaders($page, $pdf, $col_width_id, $col_width_name, $row_height);
        $y = $pdf->GetY();
        $total = 0;
        for ($i = 0; $i < count($data); $i++) {
            $section = intval(($i % 90) / 30);
            $section_offset_x = 15 + $section * ($col_width_id + $col_width_name);
            $item = $data[$i];

            $row_in_section = $i % 30;
            $current_y = $y + ($row_in_section * $row_height);
            if ($i > 0 && $i % 90 == 0) {
                $pdf->updatePageTotal($total);

                $total = 0;

                $page = $page + 1;

                $pdf->AddPage();

                $this->addHeaders($page, $pdf, $col_width_id, $col_width_name, $row_height); // Add headers again
                $y = $pdf->GetY();

                $current_y = $y;
            }
            $pdf->SetY(100);

            $pdf->MultiCell($col_width_id, $row_height, str_pad($item['invoice_id'], 9, '0', STR_PAD_LEFT), "L", 'L', 0, 0, $section_offset_x, $current_y);
            $formatted_amount = number_format($item['total_amount'], 2, '.', ',');
            $total = $total + $item['total_amount'];
            $pdf->MultiCell($col_width_name, $row_height, $formatted_amount, "R", 'R', 0, 0, $section_offset_x + $col_width_id, $current_y);
            if (($i + 1) % 30 == 0) {
                $pdf->Ln();
            }
        }
        $pdf->updatePageTotal($total);
        $pdf->Output('invoice_summary.pdf', 'I');
    }

    private function addHeaders($page, $pdf, $col_width_id, $col_width_name, $row_height)
    {
        $pdf->SetFont('', 'B', 50);
        $pdf->Cell(40, 10, $page, 0, 0, 'L', 0, '', 1);
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->Cell(100, 20, "INVOICE SUMMARY REPORT", 0, 0, 'L', 0, '', 1);
        $pdf->Ln(20);


        if (isset($_SESSION['is_istype']) && $_SESSION['is_istype'] === "false") {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, $row_height, "FROM DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, $row_height,  $_SESSION['isfrom_date'], 0, 0, 'L', 0, '', 1);
            $pdf->Cell(23, $row_height, "TO DATE:", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, $row_height, $_SESSION['isto_date'], 0, 0, 'L', 0, '', 1);
            $pdf->Ln(10);
        } else {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, $row_height, "DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, $row_height,  $_SESSION['isfrom_date'], 0, 0, 'L', 0, '', 1);
            $pdf->Ln(10);
        }


        $pdf->Cell($col_width_id, $row_height, 'Invoice No', 'LTB', 0, 'L', 0, '', 1);
        $pdf->Cell($col_width_name, $row_height, 'Amount', 'RTB', 0, 'R', 0, '', 1);
        $pdf->Cell($col_width_id, $row_height, 'Invoice No', 'LTB', 0, 'L', 0, '', 1);
        $pdf->Cell($col_width_name, $row_height, 'Amount', 'RTB', 0, 'R', 0, '', 1);
        $pdf->Cell($col_width_id, $row_height, 'Invoice No', 'LTB', 0, 'L', 0, '', 1);
        $pdf->Cell($col_width_name, $row_height, 'Amount', 'RTB', 1, 'R', 0, '', 1);
        $pdf->SetFont('helvetica', '', 10);
    }

    public function generate_employeesales()
    {

        $data = $_SESSION['employee_sales'];
        $pdf = new EmployeeReport('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Employee Sales Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);


        $page = 1;
        $pdf->SetFont('', 'B', 40);
        $pdf->Cell(40, 10, $page, 0, 0, 'L', 0, '', 1);
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->Cell(100, 20, "EMPLOYEE SALES REPORT", 0, 0, 'L', 0, '', 1);
        $pdf->Ln(20);

        if (isset($_SESSION['emp_istype']) && $_SESSION['emp_istype'] === "false") {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, 10, "FROM DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, 10,  $_SESSION['empfrom_date'], 0, 0, 'L', 0, '', 1);
            $pdf->Cell(23, 10, "TO DATE:", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, 10, $_SESSION['empto_date'], 0, 0, 'L', 0, '', 1);
        } else {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, 10, "DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, 10,  $_SESSION['empfrom_date'], 0, 0, 'L', 0, '', 1);
        }
        $pdf->Ln(10);
        $pdf->Cell(50, 10, 'Emp No', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(100, 10, 'Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(30, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        // $prevDate = null;


        $maxY = 275;
        $lineHeight = 10;
        $total = 0;
        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {

                $pdf->AddPage();
                $page = $page + 1;

                $pdf->SetFont('', 'B', 50);
                $pdf->Cell(70, 5, $page, 0, 0, 'L', 0, '', 1);
                $pdf->SetFont('helvetica', 'B', 20);
                $pdf->Cell(100, 5, "EMPLOYEE SALES REPORT", 0, 0, 'L', 0, '', 1);
                $pdf->Ln(30);
                if (isset($_SESSION['emp_istype']) && $_SESSION['emp_istype'] === "false") {
                    $pdf->SetFont('', 'B', 10);
                    $pdf->Cell(23, 10, "FROM DATE: ", 0, 0, 'R', 0, '', 1);
                    $pdf->Cell(30, 10,  $_SESSION['empfrom_date'], 0, 0, 'L', 0, '', 1);
                    $pdf->Cell(23, 10, "TO DATE:", 0, 0, 'R', 0, '', 1);
                    $pdf->Cell(30, 10, $_SESSION['empto_date'], 0, 0, 'L', 0, '', 1);
                } else {
                    $pdf->SetFont('', 'B', 10);
                    $pdf->Cell(23, 10, "DATE: ", 0, 0, 'R', 0, '', 1);
                    $pdf->Cell(30, 10,  $_SESSION['empfrom_date'], 0, 0, 'L', 0, '', 1);
                }
                $pdf->Ln(10);
                $pdf->Cell(50, 10, 'Emp No', 0, 0, 'L', 0, '', 1);
                $pdf->Cell(100, 10, 'Name', 0, 0, 'L', 0, '', 1);
                $pdf->Cell(30, 10, 'Amount', 0, 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $pdf->SetFont('', '', 10);
            $pdf->Cell(50, 5, $row['first_name'], 0, 0, 'L');
            $pdf->Cell(100, 5,  $row['last_name'], 0, 0, 'L');
            $total = $total + $row['total_price_sum'];

            $pdf->Cell(30, 7, number_format($row['total_price_sum'], 2), 0, 1, 'R');
        }

        $pdf->Ln(5);

        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Grand Total:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "", 'TB', 0, 'L');
        $pdf->Cell(30, 10, number_format($total, 2), 'TB', 1, 'R');

        $pdf->Total = $total;
        // Output PDF
        $pdf->Output('employee_sales_report.pdf', 'I');
    }

    public function employeewisereport()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $employeeid = $this->input->post('employeeid');

        if ($empid == "All") {
            $sql = "SELECT id, first_name, last_name, MAX(date) AS date, 
               SUM(total_price_sum) AS total_price_sum
        FROM (
            SELECT emp.id, emp.first_name, emp.last_name, 
                   SUM(id.total_price) AS total_price_sum, 
                   i.date 
            FROM invoice_details id 
            LEFT JOIN employee_history emp ON emp.id = id.employeeId 
            LEFT JOIN invoice i ON i.id = id.invoice_id 
            WHERE i.date BETWEEN '$from_date' AND '$to_date'
            GROUP BY emp.id, emp.first_name, emp.last_name, i.date

            UNION ALL

            SELECT emp.id, emp.first_name, emp.last_name, 
                   SUM(id.total_price) AS total_price_sum, 
                   i.date 
            FROM emp_details id 
            LEFT JOIN employee_history emp ON emp.id = id.employeeId 
            LEFT JOIN emp i ON i.id = id.invoice_id 
            WHERE i.date BETWEEN '$from_date' AND '$to_date'
            GROUP BY emp.id, emp.first_name, emp.last_name, i.date
        ) AS subquery ";

            if (!empty($employeeid)) {
                $sql .= "where subquery.id = '$employeeid'";
            }
            $sql .=  "GROUP BY id, first_name, last_name;";
        } else {

            $sql = "SELECT emp.first_name, emp.last_name, SUM(id.total_price) as total_price_sum ";

            if ($empid == "B") {
                $sql .= "FROM emp_details id "
                    . "LEFT JOIN employee_history emp ON emp.id = id.employeeId "
                    . "LEFT JOIN emp i ON i.id = id.invoice_id ";
            } else {
                $sql .= "FROM invoice_details id "
                    . "LEFT JOIN employee_history emp ON emp.id = id.employeeId "
                    . "LEFT JOIN invoice i ON i.id = id.invoice_id ";
            }


            $conditions = array();

            if (!empty($from_date) && !empty($to_date)) {
                $conditions[] = "i.date BETWEEN '$from_date' AND '$to_date'";
            } elseif (!empty($from_date)) {
                $conditions[] = "i.date >= '$from_date'";
            } elseif (!empty($to_date)) {
                $conditions[] = "i.date <= '$to_date'";
            }
            if (!empty($employeeid)) {
                $conditions[] = "emp.id = '$employeeid'";
            }
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            $sql .= "GROUP BY  emp.id ";
        }


        $query = $this->db->query($sql);
        $data  = $query->result_array();
        $_SESSION['employee_sales'] =  $data;
        $_SESSION['emp_istype'] =   $this->input->post('istype');
        $_SESSION['empfrom_date'] = $from_date;
        $_SESSION['empto_date'] =  $to_date;


        echo json_encode($_SESSION['employee_sales']);
    }

    public function countersales_report2()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');

        if ($empid == "All") {
            $sql = "SELECT countercode_id, counter_code, countercode_name, MAX(date) AS date, 
               SUM(total_price_sum) AS total_price_sum
        FROM (
            SELECT cc.countercode_id, cc.counter_code, cc.countercode_name, SUM(id.total_price) as total_price_sum, 
                   i.date 
            FROM invoice_details id 
            LEFT JOIN product_information pi ON pi.product_id = id.product_id 
            LEFT JOIN countercode cc ON cc.countercode_id = pi.countercode_id  
            LEFT JOIN invoice i ON i.id = id.invoice_id 
            WHERE i.date BETWEEN '$from_date' AND '$to_date'
            GROUP BY cc.countercode_id,  cc.counter_code, cc.countercode_name, i.date

            UNION ALL

            SELECT cc.countercode_id,  cc.counter_code, cc.countercode_name, 
                   SUM(id.total_price) AS total_price_sum, 
                   i.date 
            FROM emp_details id 
            LEFT JOIN product_information pi ON pi.product_id = id.product_id 
            LEFT JOIN countercode cc ON cc.countercode_id = pi.countercode_id 
            LEFT JOIN emp i ON i.id = id.invoice_id 
            WHERE i.date BETWEEN '$from_date' AND '$to_date'
            GROUP BY cc.countercode_id,  cc.counter_code, cc.countercode_name, i.date
        ) AS subquery ";


            $sql .=  "GROUP BY countercode_id, counter_code, countercode_name;";
        } else {

            $sql = "SELECT cc.counter_code, cc.countercode_name, SUM(id.total_price) as total_price_sum ";

            if ($empid == "B") {
                $sql .= "FROM emp_details id "
                    . "LEFT JOIN product_information pi ON pi.product_id = id.product_id "
                    . "LEFT JOIN countercode cc ON cc.countercode_id = pi.countercode_id "
                    . "LEFT JOIN emp i ON i.id = id.invoice_id ";
            } else {
                $sql .= "FROM invoice_details id "
                    . "LEFT JOIN product_information pi ON pi.product_id = id.product_id "
                    . "LEFT JOIN countercode cc ON cc.countercode_id = pi.countercode_id "
                    . "LEFT JOIN invoice i ON i.id = id.invoice_id ";
            }


            $conditions = array();

            if (!empty($from_date) && !empty($to_date)) {
                $conditions[] = "i.date BETWEEN '$from_date' AND '$to_date'";
            } elseif (!empty($from_date)) {
                $conditions[] = "i.date >= '$from_date'";
            } elseif (!empty($to_date)) {
                $conditions[] = "i.date <= '$to_date'";
            }
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            $sql .= "GROUP BY  cc.counter_code ";
        }


        $query = $this->db->query($sql);
        $data  = $query->result_array();
        $_SESSION['counter_sales2'] =  $data;
        $_SESSION['counter_istype'] =   $this->input->post('istype');
        $_SESSION['counterfrom_date'] = $from_date;
        $_SESSION['counterto_date'] =  $to_date;


        echo json_encode($_SESSION['counter_sales2']);
    }


    public function generate_otreport()
    {
        $data = json_decode($_POST['otlist'], true);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Lalitha Staffs OT Chart As At Month Of ' . $data[0]['month'] . " " . $data[0]['year']);
        $startCol = 1;
        $endCol = $data[0]['totaldays'] + 8;
        $row = 1;
        $startLetter = Coordinate::stringFromColumnIndex($startCol);
        $endLetter = Coordinate::stringFromColumnIndex($endCol);
        $sheet->mergeCells("{$startLetter}{$row}:{$endLetter}{$row}");





        // === HEADER ROWS ===
        $headers = ['No', 'Name'];
        $columnWidths = [5, 17];

        for ($i = 1; $i <= $data[0]['totaldays']; $i++) {
            $headers[] = $i;
            $columnWidths[] = 4;
        }

        $headers = array_merge($headers, ['N.OT', 'D.OT', 'B.SALARY', 'N.OT Total', 'D.OT Total', 'NET OT']);
        $columnWidths = array_merge($columnWidths, [5, 5, 11, 11, 11, 11]);

        $sheet->fromArray($headers, NULL, 'A2');

        foreach ($headers as $index => $header) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->getColumnDimension($colLetter)->setWidth($columnWidths[$index]);
        }

        // === SAMPLE DATA ===
        $startRow = 3;
        $cal1 = 0;
        $cal11 = '';
        $cal2 = 0;
        $net = 0;

        foreach ($data as $row) {
            $sheet->setCellValue('A' . $startRow, $row['first_name']);
            $sheet->setCellValue('B' . $startRow, $row['last_name']);
            $col = 'C';

            $NOT = 0;
            $DOT = 0;


            foreach ($row['otdays'] as  $row1) {
                $timeAsDouble = (float) ($row1['hours'] . '.' . $row1['minutes']);

                $sheet->setCellValue($col . $startRow, $timeAsDouble);
                if ($row1['ot'] == '1') {
                    $NOT = $NOT + $timeAsDouble;
                }
                if ($row1['ot'] == '2') {
                    $DOT = $DOT + $timeAsDouble;
                }
                $col++;
            }
            $sheet->setCellValue($col . $startRow, $NOT);
            $col++;
            $sheet->setCellValue($col . $startRow, $DOT);
            $col++;
            if ($row['basicsalary']) {
                $sheet->setCellValue($col . $startRow, number_format($row['basicsalary'], 2, '.', ','));
            } else {
                $sheet->setCellValue($col . $startRow,  0.0);
            }
            $col++;

            if ($row['basicsalary']) {
                $NOTB =  ($row['basicsalary']) / 240 * 1.5 * $NOT;
                $sheet->setCellValue($col . $startRow, number_format($NOTB, 2, '.', ','));
                $cal1 = $cal1 + $NOTB;
            } else {
                $sheet->setCellValue($col . $startRow, 0.0);
            }
            $cal11= $col;
            $col++;


            if ($row['basicsalary']) {
                $DOTB =  ($row['basicsalary']) / 240 * 2 * $DOT;
                $cal2 = $cal2 + $DOTB;

                $sheet->setCellValue($col . $startRow, number_format($DOTB, 2, '.', ','));
            } else {
                $sheet->setCellValue($col . $startRow,  0.0);
            }
            $col++;

            if ($row['basicsalary']) {
                $nettotal = $NOTB+ $DOTB;
                $net = $net + $nettotal;

                $sheet->setCellValue($col . $startRow, number_format($nettotal, 2, '.', ','));
            } else {
                $sheet->setCellValue($col . $startRow,  0.0);
            }
            $col++;

            $startRow++;
        }

        $sheet->setCellValue('A'.$startRow, '');
        $startCol = 1;
        $endCol = $data[0]['totaldays'] + 5;
        $row =  $startRow;
        $startLetter = Coordinate::stringFromColumnIndex($startCol);
        $endLetter = Coordinate::stringFromColumnIndex($endCol);
        $sheet->mergeCells("{$startLetter}{$row}:{$endLetter}{$row}");
        $sheet->setCellValue($cal11. $startRow, number_format($cal1, 2, '.', ','));
        $cal11++;
        $sheet->setCellValue($cal11. $startRow, number_format($cal2, 2, '.', ','));
        $cal11++;
        $sheet->setCellValue($cal11. $startRow, number_format($net, 2, '.', ','));


        $highestRow = $sheet->getHighestRow();
        $highestColumn = $data[0]['totaldays'] + 8;

        $j = 0;

        for ($row = 1; $row <= $highestRow; $row++) {


            if ($row >= 3) {
                $checkotrow = $data[$j]['otdays'];
                $j++;
            }
            $i = 0;

            for ($col = 1; $col <=  $highestColumn; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $value = $cell->getValue();

                $style = $sheet->getStyleByColumnAndRow($col, $row);
                $style->getFont()->setSize(10.2)->setBold(true);

                if ($col == 2) {
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::HORIZONTAL_LEFT);
                } else  if ($col == $data[0]['totaldays'] + 5) {
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::HORIZONTAL_RIGHT);
                } else  if ($col == $data[0]['totaldays'] + 6) {
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::HORIZONTAL_RIGHT);
                } else  if ($col == $data[0]['totaldays'] + 7) {
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::HORIZONTAL_RIGHT);
                } else  if ($col == $data[0]['totaldays'] + 8) {
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::HORIZONTAL_RIGHT);
                } else {
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                }
                $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);


                if ($col >= 3 && $row >= 3 && $col < $data[0]['totaldays'] + 3) {
                    $checkotcolumn =  $checkotrow[$i]['ot'];
                    if ($checkotcolumn == '2') {
                        $style->getFont()->getColor()->setARGB(Color::COLOR_RED);
                    }
                    if ($checkotcolumn == '1') {
                        $style->getFont()->getColor()->setARGB(Color::COLOR_BLUE);
                    }
                    $i = $i + 1;
                }

                if ($col == $data[0]['totaldays'] + 3 && $row >= 3) {
                    $style->getFont()->getColor()->setARGB(Color::COLOR_BLUE);
                }

                if ($col == $data[0]['totaldays'] + 4 && $row >= 3) {
                    $style->getFont()->getColor()->setARGB(Color::COLOR_RED);
                }

                if ($col == $data[0]['totaldays'] + 6 && $row >= 3) {
                    $style->getFont()->getColor()->setARGB(Color::COLOR_BLUE);
                }

                if ($col == $data[0]['totaldays'] + 7 && $row >= 3) {
                    $style->getFont()->getColor()->setARGB(Color::COLOR_RED);
                }

                if ($row == 1) {
                    $style->getFont()->setSize(18)->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                }

                // if ($value == 6.0 || $value == 4.5) {
                // }
            }
        }

        // === OUTPUT DOWNLOAD ===
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="OT_Reportcheck.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function generate_countersales2()
    {

        $data = $_SESSION['counter_sales2'];
        $pdf = new CounterSalesReport2('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Counter Sales Summary');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);


        $page = 1;
        $pdf->SetFont('', 'B', 40);
        $pdf->Cell(40, 10, $page, 0, 0, 'L', 0, '', 1);
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->Cell(100, 20, "COUNTER SALES SUMMARY", 0, 0, 'L', 0, '', 1);
        $pdf->Ln(20);

        if (isset($_SESSION['counter_istype']) && $_SESSION['counter_istype'] === "false") {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, 10, "FROM DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, 10,  $_SESSION['counterfrom_date'], 0, 0, 'L', 0, '', 1);
            $pdf->Cell(23, 10, "TO DATE:", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, 10, $_SESSION['counterto_date'], 0, 0, 'L', 0, '', 1);
        } else {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, 10, "DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, 10,  $_SESSION['counterfrom_date'], 0, 0, 'L', 0, '', 1);
        }
        $pdf->Ln(10);
        $pdf->Cell(50, 10, 'Counter Code', 'B', 0, 'L', 0, '', 1);
        $pdf->Cell(100, 10, 'Counter Name', 'B', 0, 'L', 0, '', 1);
        $pdf->Cell(30, 10, 'Amount', 'B', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        // $prevDate = null;


        $maxY = 275;
        $lineHeight = 10;
        $total = 0;
        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {

                $pdf->AddPage();
                $page = $page + 1;

                $pdf->SetFont('', 'B', 50);
                $pdf->Cell(60, 5, $page, 0, 0, 'L', 0, '', 1);
                $pdf->SetFont('helvetica', 'B', 20);
                $pdf->Cell(100, 5, "COUNTER SALES SUMMARY", 0, 0, 'L', 0, '', 1);
                $pdf->Ln(30);
                if (isset($_SESSION['counter_istype']) && $_SESSION['counter_istype'] === "false") {
                    $pdf->SetFont('', 'B', 10);
                    $pdf->Cell(23, 10, "FROM DATE: ", 0, 0, 'R', 0, '', 1);
                    $pdf->Cell(30, 10,  $_SESSION['counterfrom_date'], 0, 0, 'L', 0, '', 1);
                    $pdf->Cell(23, 10, "TO DATE:", 0, 0, 'R', 0, '', 1);
                    $pdf->Cell(30, 10, $_SESSION['counterto_date'], 0, 0, 'L', 0, '', 1);
                } else {
                    $pdf->SetFont('', 'B', 10);
                    $pdf->Cell(23, 10, "DATE: ", 0, 0, 'R', 0, '', 1);
                    $pdf->Cell(30, 10,  $_SESSION['counterfrom_date'], 0, 0, 'L', 0, '', 1);
                }
                $pdf->Ln(10);
                $pdf->Cell(50, 10, 'Counter Code', 'B', 0, 'L', 0, '', 1);
                $pdf->Cell(100, 10, 'Counter Name', 'B', 0, 'L', 0, '', 1);
                $pdf->Cell(30, 10, 'Amount', 'B', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $pdf->SetFont('', '', 10);
            $pdf->Cell(50, 5, $row['counter_code'], 0, 0, 'L');
            $pdf->Cell(100, 5,  $row['countercode_name'], 0, 0, 'L');
            $total = $total + $row['total_price_sum'];

            $pdf->Cell(30, 7, number_format($row['total_price_sum'], 2), 0, 1, 'R');
        }
        $pdf->Ln(5);

        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Grand Total:", 'T', 0, 'L');
        $pdf->Cell(100, 10, "", 'T', 0, 'L');
        $pdf->Cell(30, 10, number_format($total, 2), 'T', 1, 'R');

        // Output PDF
        $pdf->Output('counter_sales_summary.pdf', 'I');
    }





    //    ============ its for todays_customer_receipt =============
    public function bdtask_todays_customer_received()
    {
        date_default_timezone_set('Asia/Colombo');

        $today = date('Y-m-d');
        $all_customer = $this->db->select('*')->from('customer_information')->get()->result();
        $todays_customer_receipt = $this->report_model->todays_customer_receipt($today);
        $data = array(
            'title'                   => "Received From Customer",
            'all_customer'            => $all_customer,
            'todays_customer_receipt' => $todays_customer_receipt,
            'today'                   => $today,
            'customer_id'             => '',
        );
        $data['module']   = "report";
        $data['page']     = "todays_customer_receipt";
        echo modules::run('template/layout', $data);
    }


    //    ============ its for todays_customer_receipt =============
    public function bdtask_customerwise_received()
    {
        date_default_timezone_set('Asia/Colombo');

        $customer_id = $this->input->post('customer_id', TRUE);
        $from_date   = $this->input->post('from_date', TRUE);
        $today       = date('Y-m-d');
        $all_customer = $this->db->select('*')->from('customer_information')->get()->result();
        $filter_customer_wise_receipt = $this->report_model->filter_customer_wise_receipt($customer_id, $from_date);
        $data = array(
            'title'                   => "Received From Customer",
            'all_customer'            => $all_customer,
            'todays_customer_receipt' => $filter_customer_wise_receipt,
            'today'                   => $from_date,
            'customer_info'           => $this->report_model->customerinfo_rpt($customer_id),
            'customer_id'            => $customer_id,
        );

        $data['module']   = "report";
        $data['page']     = "todays_customer_receipt";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_todays_sales_report()
    {
        $sales_report = $this->report_model->todays_sales_report();
        $sales_amount = 0;
        $data = array(
            'title'        => display('sales_report'),
            'sales_amount' => number_format($sales_amount, 2, '.', ','),
        );
        $data['module']   = "report";
        $data['page']     = "sales_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_cashbalance()
    {
        $sales_report = $this->report_model->todays_sales_report();
        $sales_amount = 0;
        $data = array(
            'title'        => "Cash Balace Report",
            'sales_amount' => number_format($sales_amount, 2, '.', ','),
        );
        $data['module']   = "report";
        $data['page']     = "cash_balance_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_otreport()
    {
        //$sales_report = $this->report_model->todays_sales_report();
        $sales_amount = 0;
        $data = array(
            'title'        => "OT Report",
            // 'sales_amount' => number_format($sales_amount, 2, '.', ','),
        );
        $data['module']   = "report";
        $data['page']     = "ot_report";
        echo modules::run('template/layout', $data);
    }

    public function cashbalance_report()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');



        $_SESSION['cb_istype'] =   $this->input->post('istype');
        $_SESSION['cb_empid'] =   $empid;

        if ($empid == null) {
            $_SESSION['cb_empid'] =  "A";
        }
        $_SESSION['cbfrom_date'] = $from_date;
        $_SESSION['cbto_date'] =  $to_date;


        echo json_encode($_SESSION['cash_balance']);
    }



    public function generate_cashbalance()
    {

        require_once(__DIR__ . '/TCPDF-main/tcpdf.php');

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Cash Balance Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);


        $page = 1;
        $pdf->SetFont('', 'B', 50);
        $pdf->Cell(30, 10, $page, 0, 0, 'L', 0, '', 1);
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->Cell(100, 20, "CASH BALANCE REPORT", 0, 0, 'L', 0, '', 1);
        $pdf->Ln(30);

        if (isset($_SESSION['cb_istype']) && $_SESSION['cb_istype'] === "false") {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, $row_height, "FROM DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, $row_height,  $_SESSION['cbfrom_date'], 0, 0, 'L', 0, '', 1);
            $pdf->Cell(23, $row_height, "TO DATE:", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, $row_height, $_SESSION['cbto_date'], 0, 0, 'L', 0, '', 1);
        } else {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, $row_height, "DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, $row_height,  $_SESSION['cbfrom_date'], 0, 0, 'L', 0, '', 1);
        }
        $pdf->Ln(10);

        // if ($_SESSION['cb_empid'] == "All") {

        //     $data1     = $this->report_model->net_earning($_SESSION['cbfrom_date'], $_SESSION['cbto_date'], "A", 1);
        //     $data2     = $this->report_model->net_deduction($_SESSION['cbfrom_date'], $_SESSION['cbto_date'], "A", 1);
        //     $data3     = $this->report_model->net_earning($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'],  "B", 1);
        //     $data4     = $this->report_model->net_deduction($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'], "B", 1);
        //     $report_data = array_merge($data1, $data2, $data3, $data4);
        // } else {
        //     $data1     = $this->report_model->net_earning($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'], $_SESSION['cb_empid'], 1);
        //     $data2     = $this->report_model->net_deduction($_SESSION['cbfrom_date'], $_SESSION['cbto_date'], $_SESSION['cb_empid'], 1);
        //     $report_data = array_merge($data1, $data2);
        // }




        $users = $this->getUsers();
        $total1 = 0;
        $data3 = 0;
        $data4 = 0;
        $data5 = 0;
        $data6 = 0;
        $i = 0;
        $netearn = 0;

        if ($_SESSION['cb_empid'] == "All") {


            $data5     = $this->report_model->net_earning($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'],  "A", 4);
            $data6    = $this->report_model->net_deduction($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'], "A", 4);

            $data3     = $this->report_model->net_earning($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'],  "B", 8);
            $data4   = $this->report_model->net_deduction($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'], "B", 8);

            $total1 = $total1 + (float)$data3[0]['totalamount'] + (float)$data4[0]['totalamount'] + (float)$data5[0]['totalamount'] + (float)$data6[0]['totalamount'];
            $netearn = $netearn + (float)$data5[0]['totalamount'] + (float)$data3[0]['totalamount'];
            $netdeduct = $netdeduct + (float)$data6[0]['totalamount'] + (float)$data4[0]['totalamount'];

            //if($data3 !=0&& $data4!=0&& $data5!=0&& $data6!=0){
            if ($i == 0) {
                $pdf->Cell(50, 10,  "Main Cashier", 0, 0, 'L', 0, '', 1);
                $pdf->Ln(10);

                $pdf->Cell(50, 10, 'Net Earning', 1, 0, 'L', 0, '', 1);
                $pdf->Cell(50, 10, number_format($netearn, 2), 1, 0, 'R', 0, '', 1);
                $pdf->Ln(10);

                $pdf->Cell(50, 10, 'Net Deduction', 1, 0, 'L', 0, '', 1);
                $pdf->Cell(50, 10, number_format($netdeduct, 2), 1, 0, 'R', 0, '', 1);
                $pdf->Ln(10);

                $pdf->Cell(50, 10,  "Total", 1, 0, 'L');
                $pdf->Cell(50, 10,   number_format($total1, 2), 1, 0, 'R');
                $pdf->Ln(10);
                $i = $i + 1;
            }

            //}



        }

        foreach ($users as $user) {


            if ($_SESSION['cb_empid'] != "All") {

                if ($user['roleId'] == 1 && $_SESSION['cb_empid'] == "A") {
                    $pdf->Cell(50, 10,  $user['fullname'], 0, 0, 'L', 0, '', 1);
                    $pdf->Ln(10);
                    $data1     = $this->report_model->net_earning($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'],  "A", $user['id']);
                    $data2     = $this->report_model->net_deduction($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'], "A", $user['id']);
                    $pdf->Cell(50, 10, 'Net Earning', 1, 0, 'L', 0, '', 1);
                    $pdf->Cell(50, 10, number_format($data1[0]['totalamount'], 2), 1, 0, 'R', 0, '', 1);
                    $pdf->Ln(10);

                    $pdf->Cell(50, 10, 'Net Deduction', 1, 0, 'L', 0, '', 1);
                    $pdf->Cell(50, 10, number_format($data2[0]['totalamount'], 2), 1, 0, 'R', 0, '', 1);
                    $pdf->Ln(10);

                    $total = (float)$data1[0]['totalamount'] + (float)$data2[0]['totalamount'];
                    $pdf->Cell(50, 10,  "Total", 1, 0, 'L');
                    $pdf->Cell(50, 10,   number_format($total, 2), 1, 0, 'R');
                    $pdf->Ln(10);
                } else if ($user['roleId'] == 4 && $_SESSION['cb_empid'] == "B") {
                    $pdf->Cell(50, 10,  $user['fullname'], 0, 0, 'L', 0, '', 1);
                    $pdf->Ln(10);
                    $data1     = $this->report_model->net_earning($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'],  "B", $user['id']);
                    $data2     = $this->report_model->net_deduction($_SESSION['cbfrom_date'],  $_SESSION['cbto_date'], "B", $user['id']);
                    $pdf->Cell(50, 10, 'Net Earning', 1, 0, 'L', 0, '', 1);
                    $pdf->Cell(50, 10, number_format($data1[0]['totalamount'], 2), 1, 0, 'R', 0, '', 1);
                    $pdf->Ln(10);

                    $pdf->Cell(50, 10, 'Net Deduction', 1, 0, 'L', 0, '', 1);
                    $pdf->Cell(50, 10, number_format($data2[0]['totalamount'], 2), 1, 0, 'R', 0, '', 1);
                    $pdf->Ln(10);

                    $total = (float)$data1[0]['totalamount'] + (float)$data2[0]['totalamount'];
                    $pdf->Cell(50, 10,  "Total", 1, 0, 'L');
                    $pdf->Cell(50, 10,   number_format($total, 2), 1, 0, 'R');
                    $pdf->Ln(10);
                }
            }
        }

        $pdf->Output('cash_balance.pdf', 'I');
    }

    public function getUsers()
    {
        $query = $this->db->select(' a.id, CONCAT_WS(" ", a.first_name, a.last_name) AS fullname,d.type,d.id as roleId')
            ->from('users a')
            ->join('user_login b', 'b.user_id = a.user_id')
            ->join('sec_userrole c', 'c.user_id = a.user_id COLLATE utf8_general_ci', 'left', false)
            ->join('sec_role d', 'd.id = c.roleid', 'left')
            ->where_in('d.id', [4, 1])
            ->where('b.status', 1)
            ->get();

        $results = $query->result_array();
        return $results;
    }

    public function bdtask_datewise_sales_report()
    {
        $from_date = $this->input->get('from_date');
        $to_date  = $this->input->get('to_date');
        $sales_report = $this->report_model->retrieve_dateWise_SalesReports($from_date, $to_date);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $this->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $data = array(
            'title'        => display('sales_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
        );
        $data['module']   = "report";
        $data['page']     = "sales_report";
        echo modules::run('template/layout', $data);
    }

    public function sales_report()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');


        if ($empid == "All") {
            $data1     = $this->report_model->retrieve_dateWise_SalesReports($from_date, $to_date, "A");
            $data2     = $this->report_model->retrieve_dateWise_SalesReports($from_date, $to_date, "B");
            if ($data1 === null && $data2 === null) {
                $report_data = null;
            } elseif ($data1 === null) {
                $report_data = $data2;
            } elseif ($data2 === null) {
                $report_data = $data1;
            } else {
                $report_data = array_merge($data1, $data2);
            }
        } else {
            $report_data = $this->report_model->retrieve_dateWise_SalesReports($from_date, $to_date, $empid);
        }



        $_SESSION['invoice_summary'] =  $report_data;
        $_SESSION['is_istype'] =   $this->input->post('istype');
        $_SESSION['isfrom_date'] = $from_date;
        $_SESSION['isto_date'] =  $to_date;


        echo json_encode($_SESSION['invoice_summary']);
    }

    public function bdtask_userwise_sales_report()
    {

        $star_date      = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $end_date        = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $user_id = (!empty($this->input->get('user_id')) ? $this->input->get('user_id') : '');
        $empid     = (!empty($this->input->get('empid')) ? $this->input->get('empid') : '');


        $sales_report = null;

        if ($empid == "all") {
            $data1     = $this->report_model->user_sales_report($star_date, $end_date, $user_id, "god");
            $data2     = $this->report_model->user_sales_report($star_date, $end_date, $user_id, "123");

            if (empty($data1)) {
                $sales_report = $data1;
            } else if (empty($data2)) {
                $sales_report = $data2;
            } else {
                $sales_report = array_merge($data1, $data2);
            }
        } else {
            $sales_report = $this->report_model->user_sales_report($star_date, $end_date, $user_id, $empid);
        }

        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;

                $sales_amount += $sales_report[$k]['amount'];
            }
        }
        $user_list = $this->report_model->userList();
        $data = array(
            'title'         => display('user_wise_sales_report'),
            'sales_amount'  => number_format($sales_amount, 2, '.', ','),
            'sales_report'  => $sales_report,
            'from'          => $this->occational->dateConvert($star_date),
            'to'            => $this->occational->dateConvert($end_date),
            'user_list'     => $user_list,
            'user_id'       => $user_id,
        );
        $data['module']   = "report";
        $data['page']     = "user_wise_sales_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_invoice_wise_due_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));

        $data = array(
            'title'        => display('due_report'),
            'from_date'    => $from_date,
            'to_date'      => $to_date,

        );

        $data['module']   = "report";
        $data['page']     = "due_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_shippingcost_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $sales_report = $this->report_model->retrieve_dateWise_Shippingcost($from_date, $to_date);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $this->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $data = array(
            'title'        => display('shipping_cost_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
        );
        $data['module']   = "report";
        $data['page']     = "shippincost_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_purchase_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));

        $data['title']   = display('purchase_report');
        $data['from']   = $from_date;
        $data['to']   = $to_date;
        $data['module']   = "report";
        $data['page']     = "purchase_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_purchase_report_category_wise()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date   = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $category  = (!empty($this->input->get('category')) ? $this->input->get('category') : '');
        $category_list = $this->report_model->category_list_product();
        $purchase_report_category_wise = $this->report_model->purchase_report_category_wise($from_date, $to_date, $category);
        $data = array(
            'title'         => display('category_wise_purchase_report'),
            'category_list' => $category_list,
            'from'          => $from_date,
            'to'            => $to_date,
            'category_id'   => $category,
            'purchase_report_category_wise' => $purchase_report_category_wise,
        );
        $data['module']   = "report";
        $data['page']     = "purchase_report_category_wise";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_sale_report_employeewise()
    {
        $from_date      = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date        = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $employee_id     = (!empty($this->input->get('employee_id')) ? $this->input->get('employee_id') : '');
        $empid     = (!empty($this->input->get('empid')) ? $this->input->get('empid') : '');


        $employee_report = [];
        if ($empid == "all") {
            $data1     = $this->report_model->retrieve_employee_sales_report($from_date, $to_date, $employee_id, "god");;
            $data2 = $this->report_model->retrieve_employee_sales_report($from_date, $to_date, $employee_id, "123");;
            if (empty($data1) && empty($data2)) {
            } else if (empty($data1)) {
                $employee_report = $data1;
            } else if (empty($data2)) {
                $employee_report = $data2;
            } else {
                $report = array_merge($data1, $data2);

                foreach ($report as $k => $v) {
                    $existing_ids = array_column($employee_report, 'employee_id');
                    if (!in_array($v['employee_id'], $existing_ids)) {

                        $employee_report[] = $v;
                    } else {
                        foreach ($employee_report as &$report_entry) {
                            if ($report_entry['employee_id'] == $v['employee_id']) {
                                $report_entry['total_sale'] += $v['total_sale'];
                                $report_entry['total_amount'] += $v['total_amount'];
                            }
                        }
                    }
                }
            }
        } else {
            $employee_report = $this->report_model->retrieve_employee_sales_report($from_date, $to_date, $employee_id, $empid);
        }
        $employee_list = $this->report_model->employee_list();
        if (!empty($employee_report)) {
            $i = 0;
            foreach ($employee_report as $k => $v) {
                $i++;
                $employee_report[$k]['sl'] = $i;
            }
        }
        $sub_total = 0;
        if (!empty($employee_report)) {
            foreach ($employee_report as $k => $v) {
                //  $product_report[$k]['sales_date'] = $this->occational->dateConvert($product_report[$k]['date']);
                $sub_total = $sub_total + $employee_report[$k]['total_amount'];

                $employee_report[$k]['total_amount'] = number_format($employee_report[$k]['total_amount'], 2, '.', ',');
            }
        }
        $data = array(

            'title'          => display('sales_report_employee_wise'),
            'sub_total'      => number_format($sub_total, 2, '.', ','),
            'employee_report' => $employee_report,
            'employee_list'   => $employee_list,
            'employee_id'     => $employee_id,
            'from'           => $from_date,
            'to'             => $to_date,
        );
        $data['module']   = "report";
        $data['page']     = "employee_wise_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_sale_report_productwise()
    {
        $from_date      = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date        = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $product_id     = (!empty($this->input->get('product_id')) ? $this->input->get('product_id') : '');
        $empid     = (!empty($this->input->get('empid')) ? $this->input->get('empid') : '');

        $product_report = null;

        if ($empid == "all") {
            $data1     = $this->report_model->retrieve_product_sales_report($from_date, $to_date, $product_id, "god");;
            $data2 = $this->report_model->retrieve_product_sales_report($from_date, $to_date, $product_id, "123");;


            if (empty($data1)) {
                $product_report = $data1;
            } else if (empty($data2)) {
                $product_report = $data2;
            } else {
                $product_report = array_merge($data1, $data2);
            }
        } else {
            $product_report = $this->report_model->retrieve_product_sales_report($from_date, $to_date, $product_id, $empid);
        }
        $product_list = $this->report_model->product_list();
        if (!empty($product_report)) {
            $i = 0;
            foreach ($product_report as $k => $v) {
                $i++;
                $product_report[$k]['sl'] = $i;
            }
        }
        $sub_total = 0;
        if (!empty($product_report)) {
            foreach ($product_report as $k => $v) {
                $product_report[$k]['sales_date'] = $this->occational->dateConvert($product_report[$k]['date']);
                $sub_total = $sub_total + $product_report[$k]['total_amount'];
            }
        }
        $data = array(
            'title'          => display('sales_report_product_wise'),
            'sub_total'      => number_format($sub_total, 2, '.', ','),
            'product_report' => $product_report,
            'product_list'   => $product_list,
            'product_id'     => $product_id,
            'from'           => $from_date,
            'to'             => $to_date,
        );
        $data['module']   = "report";
        $data['page']     = "product_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_categorywise_sales_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $category = (!empty($this->input->get('category')) ? $this->input->get('category') : '');
        $category_list = $this->report_model->category_list_product();

        // $sales_report_category_wise = $this->report_model->sales_report_category_wise($from_date, $to_date, $category);


        $empid     = (!empty($this->input->get('empid')) ? $this->input->get('empid') : '');

        $sales_report_category_wise = null;

        if ($empid == "all") {
            $data1     = $this->report_model->sales_report_category_wise($from_date, $to_date, $category, "god", "B");
            $data2 = $this->report_model->sales_report_category_wise($from_date, $to_date, $category, "123", "A");
            if (empty($data1) && empty($data2)) {
            } else
            if (empty($data1)) {
                $sales_report_category_wise = $data1;
            } else if (empty($data2)) {
                $sales_report_category_wise = $data2;
            } else {
                $sales_report_category_wise = array_merge($data1, $data2);
            }
        } else {
            $sales_report_category_wise = $this->report_model->sales_report_category_wise($from_date, $to_date, $category, $empid, "");
        }

        $data = array(
            'title'                      => display('sales_report_category_wise'),
            'category_list'              => $category_list,
            'sales_report_category_wise' => $sales_report_category_wise,
            'from'                       => $from_date,
            'to'                         => $to_date,
            'category_id'                => $category,
        );
        $data['module']   = "report";
        $data['page']     = "sales_report_category_wise";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_sales_return()
    {
        $from_date = $this->input->post('from_date', TRUE);
        $to_date   = $this->input->post('to_date', TRUE);
        $start     = (!empty($from_date) ? $from_date : date('Y-m-d'));
        $end       = (!empty($to_date) ? $to_date : date('Y-m-d'));
        $return_list = $this->report_model->sales_return_list($start, $end);
        if (!empty($return_list)) {
            foreach ($return_list as $k => $v) {
                $return_list[$k]['final_date'] = $this->occational->dateConvert($return_list[$k]['date_return']);
            }
        }

        $data = array(
            'title'      => display('invoice_return'),
            'return_list' => $return_list,
            'from_date'  => $start,
            'to_date'    => $end,
        );

        $data['module']   = "report";
        $data['page']     = "sales_return";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_supplier_return()
    {
        $from_date = $this->input->post('from_date', TRUE);
        $to_date   = $this->input->post('to_date', TRUE);
        $start     = (!empty($from_date) ? $from_date : date('Y-m-d'));
        $end       = (!empty($to_date) ? $to_date : date('Y-m-d'));
        $return_list = $this->report_model->supplier_return($start, $end);
        if (!empty($return_list)) {
            foreach ($return_list as $k => $v) {
                $return_list[$k]['final_date'] = $this->occational->dateConvert($return_list[$k]['date_return']);
            }
        }

        $data = array(
            'title'       => display('supplier_return'),
            'return_list' => $return_list,
            'start_date'  => $start,
            'end_date'    => $end,
        );

        $data['module']   = "report";
        $data['page']     = "supplier_return";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_tax_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $sales_report = $this->report_model->retrieve_dateWise_tax($from_date, $to_date);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {

                $sales_report[$k]['sl']         = $i;
                $sales_report[$k]['sales_date'] = $this->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
                $i++;
            }
        }
        $data = array(
            'title'        => display('tax_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
        );

        $data['module']   = "report";
        $data['page']     = "tax_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_profit_report()
    {
        $start_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $end_date   = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $total_profit_report = $this->report_model->total_profit_report($start_date, $end_date);
        $profit_ammount   = 0;
        $SubTotalSupAmnt  = 0;
        $SubTotalSaleAmnt = 0;
        if (!empty($total_profit_report)) {
            $i = 0;
            foreach ($total_profit_report as $k => $v) {
                $total_profit_report[$k]['sl'] = $i;
                $total_profit_report[$k]['prchse_date'] = $this->occational->dateConvert($total_profit_report[$k]['date']);
                $profit_ammount = $profit_ammount + $total_profit_report[$k]['total_profit'];
                $SubTotalSupAmnt = $SubTotalSupAmnt + $total_profit_report[$k]['total_supplier_rate'];
                $SubTotalSaleAmnt = $SubTotalSaleAmnt + $total_profit_report[$k]['total_sale'];
            }
        }

        $data = array(
            'title'               => display('profit_report'),
            'profit_ammount'      => number_format($profit_ammount, 2, '.', ','),
            'total_profit_report' => $total_profit_report,
            'from'                => $start_date,
            'to'                  => $end_date,
            'SubTotalSupAmnt'     => number_format($SubTotalSupAmnt, 2, '.', ','),
            'SubTotalSaleAmnt'    => number_format($SubTotalSaleAmnt, 2, '.', ','),
        );
        $data['module']   = "report";
        $data['page']     = "profit_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_add_closing()
    {

        $this->form_validation->set_rules('opening_bal', display('opening_balance'), 'max_length[100]|required');
        if ($this->form_validation->run()) {
            $createby    = $this->session->userdata('id');
            $check_exist = $this->db->select('')->from('closing_records')->where('user_id', $createby)->where('DATE(datetime)', date('Y-m-d'))->where('head_code', $this->input->post('head_code', true))->get()->num_rows();
            if ($check_exist > 0) {
                $data['status'] = 0;
                $data['message'] = 'Already Closed Today';
                echo json_encode($data);
                exit;
            }
            $createdate = date('Y-m-d H:i:s');
            $postData = array(
                'head_code'       => $this->input->post('head_code', true),
                'opening_balance' => $this->input->post('opening_bal', true),
                'amount_in'       => $this->input->post('total_received', true),
                'amount_out'      => $this->input->post('total_paid', true),
                'closign_balance' => $this->input->post('closing', true),
                'user_id'         => $createby,
                'status'          => 1
            );
            if ($this->report_model->create_opening($postData)) {
                $data['status'] = 1;
                $data['message'] = display('successfully_saved');
            } else {
                $data['status'] = 0;
                $data['message'] = display('please_try_again');
            }
        } else {
            $data['status'] = 0;
            $data['message'] = validation_errors();
        }
        echo json_encode($data);
        exit;
    }

    public function countersales_report()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');


        if ($empid == "All") {

            $report_data     = $this->report_model->getCountersalesSummaryAll($from_date, $to_date);
        } else {
            $report_data     = $this->report_model->getcountersalessummary($from_date, $to_date, $empid);
        }



        $_SESSION['coutersales'] =  $report_data;
        $_SESSION['cw_istype'] =   $this->input->post('istype');
        $_SESSION['cw_empid'] =   $empid;

        $_SESSION['cwfrom_date'] = $from_date;
        $_SESSION['cwto_date'] =  $to_date;


        echo json_encode($_SESSION['coutersales']);
    }

    public function generate_countersalesreport()
    {

        $data = $_SESSION['coutersales'];
        require_once(__DIR__ . '/TCPDF-main/tcpdf.php');

        $pdf = new CounterSalesReport1('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Couter Sales Summary');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);


        $page = 1;
        $pdf->SetFont('', 'B', 12);

        $pdf->Cell(155, $row_height, "", 0, 0, 'R', 0, '', 1);
        $pdf->Cell(30, $row_height, date("Y-m-d"), 0, 0, 'L', 0, '', 1);


        $pdf->SetFont('', 'B', 50);
        $pdf->Ln(2);

        $pdf->Cell(40, 10, "", 0, 0, 'L', 0, '', 1);
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->Cell(100, 20, "COUNTER SALES SUMMARY", 0, 0, 'L', 0, '', 1);
        $pdf->Ln(20);

        if (isset($_SESSION['cw_istype']) && $_SESSION['cw_istype'] === "false") {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, $row_height, "FROM DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, $row_height,  $_SESSION['cwfrom_date'], 0, 0, 'L', 0, '', 1);
            $pdf->Cell(23, $row_height, "TO DATE:", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, $row_height, $_SESSION['cwto_date'], 0, 0, 'L', 0, '', 1);
            $pdf->Cell(22, $row_height, "", 0, 0, 'L', 0, '', 1);
            // $pdf->Cell(23, $row_height, "PRINT DATE:", 0, 0, 'R', 0, '', 1);
            // $pdf->Cell(30, $row_height, date("Y-m-d"), 0, 0, 'L', 0, '', 1);
        } else {
            $pdf->SetFont('', 'B', 10);
            $pdf->Cell(23, $row_height, "DATE: ", 0, 0, 'R', 0, '', 1);
            $pdf->Cell(30, $row_height,  $_SESSION['cwfrom_date'], 0, 0, 'L', 0, '', 1);
            $pdf->Cell(75, $row_height, "", 0, 0, 'L', 0, '', 1);
            // $pdf->Cell(23, $row_height, "PRINT DATE:", 0, 0, 'R', 0, '', 1);
            // $pdf->Cell(30, $row_height, date("Y-m-d"), 0, 0, 'L', 0, '', 1);
        }
        $pdf->Ln(5);

        $maxY = 275;
        $lineHeight = 10;
        $total = 0;
        $prevDate1 = $row['department'];
        $rowtotal = 0;
        $retail = 0;
        $saree = 0;

        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {

                $pdf->AddPage();
                $page = $page + 1;

                $pdf->SetFont('', 'B', 50);
                $pdf->Cell(70, 10, $page, 0, 0, 'L', 0, '', 1);
                $pdf->SetFont('helvetica', 'B', 20);
                $pdf->Cell(100, 20, "Counter Summary Report", 0, 0, 'L', 0, '', 1);
                $pdf->Ln(30);
                if (isset($_SESSION['emp_istype']) && $_SESSION['emp_istype'] === "false") {
                    $pdf->SetFont('', 'B', 10);
                    $pdf->Cell(23, $row_height, "FROM DATE: ", 0, 0, 'R', 0, '', 1);
                    $pdf->Cell(30, $row_height,  $_SESSION['cwfrom_date'], 0, 0, 'L', 0, '', 1);
                    $pdf->Cell(23, $row_height, "TO DATE:", 0, 0, 'R', 0, '', 1);
                    $pdf->Cell(30, $row_height, $_SESSION['cwto_date'], 0, 0, 'L', 0, '', 1);
                } else {
                    $pdf->SetFont('', 'B', 10);
                    $pdf->Cell(23, $row_height, "DATE: ", 0, 0, 'R', 0, '', 1);
                    $pdf->Cell(30, $row_height,  $_SESSION['cwfrom_date'], 0, 0, 'L', 0, '', 1);
                }
                $pdf->Ln(10);
                $pdf->Cell(50, 10, 'Emp No', 0, 0, 'L', 0, '', 1);
                $pdf->Cell(70, 10, 'Name', 0, 0, 'L', 0, '', 1);
                $pdf->Cell(30, 10, 'Amount', 0, 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            if ($prevDate !== $row['department']) {
                // if ($prevDate !== null) {
                //     $pdf->Ln(5);
                // }
                if ($rowtotal != 0) {
                    $pdf->SetFont('', 'B', 14);
                    $pdf->Cell(95, 10, $prevDate1, 1, 0, 'L');
                    $pdf->Cell(85, 10,  number_format($rowtotal, 2), 1, 0, 'R');
                }


                $prevDate = $row['department'];
                $rowtotal = 0;
                $pdf->Ln(10);
            }
            $pdf->SetFont('', '', 12);
            $pdf->Cell(95, 10, $row['countercode_name'], 1, 0, 'L');
            $pdf->Cell(85, 10,  number_format($row['total_price'], 2), 1, 0, 'R');
            $prevDate1 = $row['department'];
            $rowtotal = (float) $rowtotal + (float)$row['total_price'];
            $pdf->Ln(10);

            if ($row['saleType'] == "Retail") {
                $retail = $retail + (float)$row['total_price'];
            } else {
                $saree = $saree + (float)$row['total_price'];
            }
            // $pdf->Cell(30, 10, number_format($row['total_price_sum'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 14);
        $pdf->Cell(95, 10, $prevDate1, 1, 0, 'L');
        $pdf->Cell(85, 10,  number_format($rowtotal, 2), 1, 0, 'R');
        $pdf->Ln(10);

        $pdf->Cell(95, 10, "Retail", 1, 0, 'L');
        $pdf->Cell(85, 10,  number_format($retail, 2), 1, 0, 'R');

        $pdf->Ln(10);

        $pdf->Cell(95, 10, "Saree", 1, 0, 'L');
        $pdf->Cell(85, 10,  number_format($saree, 2), 1, 0, 'R');

        $pdf->Ln(10);

        $pdf->Cell(95, 10, "Grand Total:", 1, 0, 'L');
        $pdf->Cell(85, 10,  number_format($saree + $retail, 2), 1, 0, 'R');



        // Output PDF
        $pdf->Output('counter_sales_summary.pdf', 'I');
    }


    public function CheckReportList()
    {
        // echo "bb";
        // exit;
        $postData = $this->input->post();
        $data = $this->report_model->getReportList($postData);
        // dd($data);
        // exit;
        echo json_encode($data);
    }
    public function getSalesReportList()
    {
        // echo "bb";
        // exit;
        $postData = $this->input->post();
        $empid   = $this->input->post('empid', TRUE);
        if ($empid == "all") {
            $data1     = $this->report_model->getAllSalesReportList($postData, "god", 1);
            $data2 = $this->report_model->getAllSalesReportList($postData, "123",  $data1['sl']);
            $mergedData = array_merge($data1['aaData'], $data2['aaData']);
            $totalRecordwithFilter = $data1['iTotalRecords'] + $data2['iTotalRecords'];
            $totalRecords = $data1['iTotalDisplayRecords'] + $data2['iTotalDisplayRecords'];
            $draw         = $postData['draw'];

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
                "aaData" =>  $mergedData
            );
            echo json_encode($response);
        } else {

            $data = $this->report_model->getSalesReportList($postData, $empid, 1);
            // dd($data);
            // exit;
            echo json_encode($data);
        }
    }
    public function get_retrieve_dateWise_DueReports()
    {
        // echo "bb";
        // exit;
        $postData = $this->input->post();
        $data = $this->report_model->get_retrieve_dateWise_DueReports($postData);
        // dd($data);
        // exit;
        echo json_encode($data);
    }
}
