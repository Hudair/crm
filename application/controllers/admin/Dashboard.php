<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admistrator
 *
 * @author pc mart ltd
 */
class Dashboard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('invoice_model');
        $this->load->model('estimates_model');
    }


    public function rendering($param, $filter_by = null)
    {
        $today = date('Y-m-d h:i:s');
        //if($_POST) {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            if ($param == 'overdue_projects') {
                $this->datatables->table = 'tbl_project';
                $this->load->model('projects_model');
                $mata['all_project'] = $this->projects_model->overdue_projects();
                $this->load->view('admin/dashboard/overdue_projects_data', $mata);
            }

            if ($param == 'overdue_tasks') {
                $this->datatables->table = 'tbl_task';
                $this->load->model('tasks_model');
                $mata['task_all_info'] = $this->admin_model->get_permission('tbl_task', array('tbl_task.task_status !=' => 'completed', 'tbl_task.due_date <' => $today));
                $this->load->view('admin/dashboard/overdue_tasks_data', $mata);
            }

            if ($param == 'overdue_invoices') {
                $this->datatables->table = 'tbl_invoices';
                $this->load->model('invoice_model');
                $mata['whereClauses'] = array('tbl_invoices.status !=' => 'Paid', 'tbl_invoices.due_date <' => $today);
                $mata['all_invoices_info'] = $this->admin_model->get_permission('tbl_invoices', $mata['whereClauses']);
                $this->load->view('admin/dashboard/overdue_invoices_data', $mata);
            }

            if ($param == 'expired_estimate') {
                $this->datatables->table = 'tbl_estimates';
                $mata['whereClauses'] = array('tbl_estimates.due_date <' => $today, 'tbl_estimates.status' => 'Pending');
                //$mata['whereClauses'] = array();
                $mata['all_estimates_info'] = $this->admin_model->get_permission('tbl_estimates', $mata['whereClauses']);
                $this->load->view('admin/dashboard/expired_estimate_data', $mata);
            }

            if ($param == 'unconfirmed_bugs') {
                $this->datatables->table = 'tbl_bug';
                $mata['whereClauses'] = array('tbl_bug.bug_status' => 'unconfirmed');
                //$mata['whereClauses'] = array();
                $mata['all_bugs_info'] = $this->admin_model->get_permission('tbl_bug', $mata['whereClauses']);
                $this->load->view('admin/dashboard/unconfirmed_bugs_data', $mata);
            }

            if ($param == 'overdue_opportunities') {
                $this->datatables->table = 'tbl_opportunities';
                $mata['whereClauses'] = array('tbl_opportunities.close_date <' => $today, 'tbl_opportunities.probability <' => 100);
                $mata['all_opportunity'] = $this->admin_model->get_permission('tbl_opportunities', $mata['whereClauses']);
                $this->load->view('admin/dashboard/overdue_opportunities_data', $mata);
            }

        } else {
            redirect('admin/dashboard');
        }
        // }
    }

    public function different_overdue_total()
    {

        if ($this->input->is_ajax_request()) {
            $today = date('Y-m-d h:i:s');

            $this->load->model('projects_model');
            $overdue_projects_total = $this->projects_model->overdue_projects(true);
            if (empty($overdue_projects_total)) {
                $overdue_projects_total = 0;
            }

            $overdue_tasks_total = $this->admin_model->get_permission('tbl_task', array('tbl_task.task_status !=' => 'completed', 'tbl_task.due_date <' => $today), null, true);
            if (empty($overdue_tasks_total)) {
                $overdue_tasks_total = 0;
            }

            $overdue_invoices_total = $this->admin_model->get_permission('tbl_invoices', array('tbl_invoices.status !=' => 'Paid', 'tbl_invoices.due_date <' => $today), null, true);
            if (empty($overdue_invoices_total)) {
                $overdue_invoices_total = 0;
            }

            $expired_estimate_total = $this->admin_model->get_permission('tbl_estimates', array('tbl_estimates.due_date <' => $today, 'tbl_estimates.status' => 'Pending'), null, true);
            if (empty($expired_estimate_total)) {
                $expired_estimate_total = 0;
            }

            $unconfirmed_bugs_total = $this->admin_model->get_permission('tbl_bug', array('bug_status' => 'unconfirmed'), null, true);
            if (empty($unconfirmed_bugs_total)) {
                $unconfirmed_bugs_total = 0;
            }

            $overdue_opportunities_total = $this->admin_model->get_permission('tbl_opportunities', array('tbl_opportunities.close_date <' => $today, 'tbl_opportunities.probability <' => 100), null, true);
            if (empty($overdue_opportunities_total)) {
                $overdue_opportunities_total = 0;
            }

            $res = array();
            $res['overdue_projects_total'] = '(' . $overdue_projects_total . ')';
            $res['overdue_tasks_total'] = '(' . $overdue_tasks_total . ')';
            $res['overdue_invoices_total'] = '(' . $overdue_invoices_total . ')';
            $res['expired_estimate_total'] = '(' . $expired_estimate_total . ')';
            $res['unconfirmed_bugs_total'] = '(' . $unconfirmed_bugs_total . ')';
            $res['overdue_opportunities_total'] = '(' . $overdue_opportunities_total . ')';
            echo json_encode($res);
            exit();
        } else {
            redirect('admin/dashboard');
        }
    }


    public function different_completed_total()
    {

        if ($this->input->is_ajax_request()) {
            $completed_projects = $this->admin_model->get_permission('tbl_project', array('tbl_project.project_status' => 'completed'), null, true);
            if (empty($completed_projects)) {
                $completed_projects = 0;
            }

            $completed_tasks = $this->admin_model->get_permission('tbl_task', array('tbl_task.task_status' => 'completed'), null, true);
            if (empty($completed_tasks)) {
                $completed_tasks = 0;
            }

            $total_estimate_amount = $this->db->query("SELECT  tbl_estimates.estimates_id, sum(tbl_estimate_items.total_cost) as cost
        FROM tbl_estimates
        LEFT JOIN tbl_estimate_items ON tbl_estimates.estimates_id = tbl_estimate_items.estimates_id
        WHERE tbl_estimates.status NOT IN ('draft', 'cancelled')")->row()->cost;

            if (empty($total_estimate_amount)) {
                $total_estimate_amount = 0;
            }


            $total_resolved_bugs = $this->admin_model->get_permission('tbl_bug', array('tbl_bug.bug_status' => 'resolved'), null, true);
            if (empty($total_resolved_bugs)) {
                $total_resolved_bugs = 0;
            }

            $total_won_opportunities = $this->admin_model->get_permission('tbl_opportunities', array('tbl_opportunities.stages' => 'won'), null, true);
            if (empty($total_won_opportunities)) {
                $total_won_opportunities = 0;
            }


            $res = array();
            $res['completed_projects'] = $completed_projects;
            $res['completed_tasks'] = $completed_tasks;
            $res['total_estimate_amount'] = $total_estimate_amount;
            $res['total_resolved_bugs'] = $total_resolved_bugs;
            $res['total_won_opportunities'] = $total_won_opportunities;
            echo json_encode($res);
            exit();
        } else {
            redirect('admin/dashboard');
        }
    }


    public function income_expenses()
    {
        //total expense count
        if ($this->input->is_ajax_request()) {
            $this->admin_model->_table_name = "tbl_transactions"; //table name
            $this->admin_model->_order_by = "transactions_id"; // order by
            $total_income = $this->db->query("SELECT  COALESCE(sum(amount), 0) AS amount FROM tbl_transactions where type = 'income'")->row()->amount;
            $today_income = $this->db->query("SELECT  COALESCE(SUM(amount), 0) AS amount FROM tbl_transactions WHERE type = 'income' AND  date = CURDATE()")->row()->amount;
            $total_expense = $this->db->query("SELECT  COALESCE(sum(amount), 0) AS amount FROM tbl_transactions where type = 'expense'")->row()->amount;
            $today_expense = $this->db->query("SELECT  COALESCE(sum(amount), 0) AS amount FROM tbl_transactions where type = 'expense' AND date = CURDATE()")->row()->amount;

            $res = array();
            $res['total_income'] = display_money($total_income, default_currency());
            $res['today_income'] = display_money($today_income, default_currency());
            $res['total_expense'] = display_money($total_expense, default_currency());
            $res['today_expense'] = display_money($today_expense, default_currency());
            echo json_encode($res);
            exit();
        } else {
            redirect('admin/dashboard');
        }

    }


    public function data_rendering()
    {
        if ($this->input->is_ajax_request()) {
            $tasks_in_progress = $this->admin_model->get_permission('tbl_task', array('tbl_task.task_status' => 'in_progress'), 'COUNT(1)', true);
            if (empty($tasks_in_progress)) {
                $tasks_in_progress = 0;
            }

            $tickets_open = $this->admin_model->get_permission('tbl_tickets', array('tbl_tickets.status' => 'open'), 'COUNT(1)', true);
            if (empty($tickets_open)) {
                $tickets_open = 0;
            }

            $bugs_in_progress = $this->admin_model->get_permission('tbl_bug', array('tbl_bug.bug_status' => 'in_progress'), 'COUNT(1)', true);
            if (empty($bugs_in_progress)) {
                $bugs_in_progress = 0;
            }

            $project_in_progress = $this->admin_model->get_permission('tbl_project', array('tbl_project.project_status' => 'in_progress'), 'COUNT(1)', true);
            if (empty($project_in_progress)) {
                $project_in_progress = 0;
            }


            $res = array();
            $res['tasks_in_progress'] = $tasks_in_progress;
            $res['tickets_open'] = $tickets_open;
            $res['bugs_in_progress'] = $bugs_in_progress;
            $res['project_in_progress'] = $project_in_progress;

            echo json_encode($res);
            exit();
        } else {
            redirect('admin/dashboard');
        }

    }


    public function index($action = NULL)
    {
        $data['title'] = config_item('company_name');
        $data['page'] = lang('dashboard');

        $data['role'] = $this->session->userdata('user_type');

        $data['year'] = date('Y'); // get current year
        if (!empty($action) && $action == 'Income') {
            $data['Income'] = $this->input->post('Income', TRUE);
        } else {
            $data['Income'] = date('Y'); // get current year
        }
        if ($this->input->post('year', TRUE)) { // if input year 
            $data['year'] = $this->input->post('year', TRUE);
        }
        $data['month'] = date('Y-m'); // get current year
        if ($this->input->post('month', TRUE)) { // if input year 
            $data['month'] = $this->input->post('month', TRUE);
        }

        if ($this->input->post('finance_overview', TRUE)) { // if input year
            $data['finance_year'] = $this->input->post('finance_overview', TRUE);
        } else { // else current year
            $data['finance_year'] = date('Y'); // get current year
        }
        $data['subview'] = $this->load->view('admin/main_content', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function finance_overview()
    {

        if ($this->input->is_ajax_request()) {
            if ($this->input->post('finance_overview', TRUE)) { // if input year
                $data['finance_year'] = $this->input->post('finance_overview', TRUE);
            } else { // else current year
                $data['finance_year'] = date('Y'); // get current year
            }
            // get all income/expense list by year and month
            $data['finance_overview_by_year'] = $this->finance_overview_by_year($data['finance_year']);
            $data['total_annual'] = $this->finance_overview_by_year($data['finance_year'], true);
            $pathonor_jonno['finance_overview_div'] = $this->load->view("admin/dashboard/finance_overview", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }


    public function my_projects()
    {
        if ($this->input->is_ajax_request()) {
            $select = "tbl_project.project_id,tbl_project.project_name, tbl_project.progress, tbl_project.end_date";
            $data['my_projects'] = $this->admin_model->get_permission('tbl_project', array('tbl_project.project_status !=' => 'completed', 'tbl_project.progress <' => 100), $select);
            $pathonor_jonno['my_projects_div'] = $this->load->view("admin/dashboard/my_projects_data", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }

    public function goal_report()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            // goal tracking
            if ($this->input->post('goal_month', TRUE)) { // if input year
                $data['goal_month'] = $this->input->post('goal_month', TRUE);
            } else { // else current year
                $data['goal_month'] = date('Y-m'); // get current year
            }
            $pathonor_jonno['goal_report_div'] = $this->load->view("admin/partials/goal_report", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }


    public function to_do_list()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $pathonor_jonno['to_do_list_div'] = $this->load->view("admin/dashboard/to_do_list_data", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }

    public function my_task()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $pathonor_jonno['my_task_div'] = $this->load->view("admin/partials/my_task", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }

    public function announcements()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $pathonor_jonno['announcements_div'] = $this->load->view("admin/dashboard/announcements", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }

    public function payments_report()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            if ($this->input->post('yearly', TRUE)) {
                $data['yearly'] = $this->input->post('yearly', TRUE);
            } else {
                $data['yearly'] = date('Y'); // get current year
            }
            $pathonor_jonno['payments_report_div'] = $this->load->view("admin/partials/payments_report_old", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }

    public function income_vs_expense()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            if ($this->input->post('month', TRUE)) { // if input year
                $data['month'] = $this->input->post('month', TRUE);
            } else { // else current year
                $data['month'] = date('Y-m'); // get current year
            }
            $data['income_expense'] = $this->get_income_expense($data['month']);
            $pathonor_jonno['income_vs_expense_div'] = $this->load->view("admin/dashboard/income_expense_old", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }


    public function income_report()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $data['year'] = date('Y'); // get current year
            if (!empty($this->input->post('Income', TRUE))) {
                $data['Income'] = $this->input->post('Income', TRUE);
                $data['year'] = $this->input->post('Income', TRUE);
            } else {
                $data['Income'] = date('Y'); // get current year
            }
            $data['all_income'] = $this->get_transactions_list($data['Income'], 'Income');
            $pathonor_jonno['income_report_div'] = $this->load->view("admin/dashboard/income_report_old", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }


    public function expense_report()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            if (!empty($this->input->post('year', TRUE))) {
                $data['year'] = $this->input->post('year', TRUE);
            } else {
                $data['year'] = date('Y'); // get current year
            }
            $data['all_income'] = $data['all_expense'] = $this->get_transactions_list($data['year'], 'Expense');
            $pathonor_jonno['expense_report_div'] = $this->load->view("admin/dashboard/expense_report_old", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }

    public function recently_paid_invoices()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $data['recently_paid'] = $this->db->query("SELECT tbl_payments.invoices_id,  tbl_payments.trans_id, tbl_payments.currency, tbl_payments.amount,
            COALESCE(tbl_invoices.reference_no, '') AS reference_no,
            COALESCE(tbl_invoices.client_id, 0) AS client_id,
            COALESCE(tbl_payment_methods.method_name, '-') method_name,
            CASE WHEN tbl_payments.payment_method = '1' THEN 'success' WHEN tbl_payments.payment_method = '2' THEN 'danger'  ELSE 'dark' END AS label
            FROM tbl_payments
            LEFT JOIN tbl_invoices ON tbl_payments.invoices_id = tbl_invoices.invoices_id
            LEFT JOIN tbl_payment_methods ON tbl_payments.payment_method = tbl_payment_methods.payment_methods_id
            ORDER BY created_date DESC LIMIT 5")->result();
            $pathonor_jonno['recently_paid_invoices_div'] = $this->load->view("admin/dashboard/recently_paid_invoices", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }

    public function recent_activities()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $user_id = $this->session->userdata('user_id');
            $whereSql = "WHERE tbl_activities.user = '$user_id'";
            if ($this->session->userdata('user_type') == '1') {
                $whereSql = NULL;
            }

            $data['activities'] = $this->db->query("SELECT tbl_activities.activity_date, tbl_activities.activity,tbl_activities.value1, tbl_activities.value2, tbl_account_details.avatar, tbl_account_details.fullname
        FROM tbl_activities 
        LEFT  JOIN tbl_account_details ON tbl_activities.user = tbl_account_details.user_id
        $whereSql
        ORDER BY tbl_activities.activity_date DESC LIMIT 10")->result();

            $pathonor_jonno['recent_activities_div'] = $this->load->view("admin/dashboard/recent_activities", $data, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }


    public function pinned_menu_items()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $pathonor_jonno = array();
            $tata = array();
            $pathonor_jonno['nav_pinned_cont'] = $this->load->view("admin/components/nav_pinned", $data, true);
            $pathonor_jonno['nav_pinned_cont_2'] = $this->load->view("admin/components/nav_pinned_2", $tata, true);
            echo json_encode($pathonor_jonno);
            exit;
        } else {
            redirect('admin/dashboard');
        }
    }


    public function finance_overview_by_year($year, $annual = null)
    {
        if (!empty($annual)) {
            $start_date = (new \DateTimeImmutable('January 1 ' . $year))->format('Y-m-d') . PHP_EOL;
            $end_date = (new \DateTimeImmutable('December 31 ' . $year))->format('Y-m-d') . PHP_EOL;

            $f_total_income = $this->db->query("SELECT COALESCE(sum(amount), 0) as amount FROM tbl_transactions WHERE date >= '$start_date' AND date <= '$end_date' AND  type='income'")->row()->amount;
            $f_total_expense = $this->db->query("SELECT COALESCE(sum(amount), 0) as amount FROM tbl_transactions WHERE date >= '$start_date' AND date <= '$end_date' AND  type='expense'")->row()->amount;

            $tatal_annual = array(
                'total_income' => $f_total_income,
                'total_expense' => $f_total_expense,
                'total_profit' => $f_total_income - $f_total_expense,
            );
            return $tatal_annual;
        } else {
            $this->load->model('report_model');
            $finance_overview_list = array();
            for ($i = 1; $i <= 12; $i++) { // query for months
                $date = new DateTime($year . '-' . $i . '-01');
                $start_date = $date->modify('first day of this month')->format('Y-m-d');

                $end_date = $date->modify('last day of this month')->format('Y-m-d');

                //$finance_overview_list[$i] = $this->report_model->get_report_by_date($start_date, $end_date); // get all report by start_date and end_date
                $finance_overview_list[$i] = $this->get_income_expense($year . '-' . $i, $year); // get all report by start_date and end_date
                //echo $this->db->last_query(); //exit();
            }

            //echo '<pre>'; print_r($finance_overview_list); exit();

            return $finance_overview_list; // return the result
        }
    }

    public function get_income_expense($month, $year = null)
    {
        // this function is to create get monthy recap report
        //m = date('m', strtotime($month));
        //2020-12

        $m = date('m', strtotime($month));
        if ($year) {
            $date = new DateTime($year . '-' . $m . '-01');
        } else {
            $year = date('Y', strtotime($month));
            $date = new DateTime($year . '-' . $m . '-01');
        }
        $start_date = $date->modify('first day of this month')->format('Y-m-d');
        $end_date = $date->modify('last day of this month')->format('Y-m-d');
        //$get_expense_list = $this->admin_model->get_transactions_list_by_month($start_date, $end_date); // get all report by start date and in date
        //echo  $this->db->last_query(); exit;

        $get_expense = array();
        $get_expense['total_income'] = $this->db->query("SELECT COALESCE(sum(amount), 0) as amount FROM tbl_transactions WHERE date >= '$start_date' AND date <= '$end_date' AND  type='income'")->row()->amount;
        $get_expense['total_expense'] = $this->db->query("SELECT COALESCE(sum(amount), 0) as amount FROM tbl_transactions WHERE date >= '$start_date' AND date <= '$end_date' AND  type='expense'")->row()->amount;

        // return $get_expense_list; // return the result
        return $get_expense; // return the result
    }


    function invoice_totals_per_currency()
    {
        $invoices_info = $this->db->where(array('inv_deleted' => 'No', 'status !=' => 'draft'))->limit(2220)->get('tbl_invoices')->result();

        $paid = $due = array();
        $currency = 'USD';
        $symbol = array();
//        foreach ($invoices_info as $v_invoices) {
//            if (!isset($paid[$v_invoices->currency])) {
//                $paid[$v_invoices->currency] = 0;
//            }
//            if (!isset($due[$v_invoices->currency])) {
//                $due[$v_invoices->currency] = 0;
//            }
//            $paid[$v_invoices->currency] += $this->invoice_model->get_invoice_paid_amount($v_invoices->invoices_id);
//            $due[$v_invoices->currency] += $this->invoice_model->get_invoice_due_amount($v_invoices->invoices_id);
//            $currency = $this->admin_model->check_by(array('code' => $v_invoices->currency), 'tbl_currencies');
//            $symbol[$v_invoices->currency] = $currency->symbol;
//        }

        return array("paid" => $paid, "due" => $due, "symbol" => $symbol);

    }


    public function get_transactions_list($year, $type)
    {// this function is to create get monthy recap report
        for ($i = 1; $i <= 12; $i++) { // query for months
            $date = new DateTime($year . '-' . $i . '-01');
            $start_date = $date->modify('first day of this month')->format('Y-m-d');
            $end_date = $date->modify('last day of this month')->format('Y-m-d');

            //$get_expense_list[$i] = $this->admin_model->get_transactions_list_by_date($type, $start_date, $end_date); // get all report by start date and in date
            $get_expense_list[$i] = $this->db->query("SELECT COALESCE(sum(amount), 0) as amount FROM tbl_transactions WHERE date >= '$start_date' AND date <= '$end_date' AND  type='$type'")->row()->amount; // get all report by start date and in date
        }
        return $get_expense_list; // return the result
    }

    public function set_language($lang)
    {
        $check_languages = get_row('tbl_languages', array('active' => 1, 'name' => $lang));
        if (!empty($check_languages)) {
            $this->session->set_userdata('lang', $lang);
        } else {
            set_message('error', lang('nothing_to_display'));
        }
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/dashboard');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function set_clocking($id = NULL, $user_id = null, $row = null, $redirect = null)
    {
        if (!empty(attendance_access())) {
            if ($id == 0) {
                $id = null;
            }
            if ($row == 0) {
                $row = null;
            }
            // sate into attendance table
            if (!empty($user_id)) {
                $adata['user_id'] = $user_id;
            } else {
                $adata['user_id'] = $this->session->userdata('user_id');
            }
            if (!empty($row)) {
                $clocktime = 1;
            } elseif (!empty($id)) {
                $clocktime = 2;
            } else {
                $clocktime = 1;
            }

            $date = date('Y-m-d');
            $time = date('H:i:s');
            //        $already_clocking = $this->admin_model->check_by(array('user_id' => $adata['user_id'], 'clocking_status' => 1), 'tbl_attendance');
            if ($clocktime == 1) {
                $adata['date_in'] = $date;
                $adata['date_out'] = $date;
            } else {
                $adata['date_out'] = $date;
            }
            if (!empty($adata['date_in'])) {
                // check existing date is here or not
                $check_date = $this->admin_model->check_by(array('user_id' => $adata['user_id'], 'date_in' => $adata['date_in']), 'tbl_attendance');
            }
            if (!empty($check_date)) { // if exis do not save date and return id
                $this->admin_model->_table_name = "tbl_attendance"; // table name
                $this->admin_model->_primary_key = "attendance_id"; // $id
                if ($check_date->attendance_status != '1') {
                    $udata['attendance_status'] = 1;
                    $this->admin_model->save($udata, $check_date->attendance_id);
                }
                if ($check_date->clocking_status == 0) {
                    $udata['date_out'] = $date;
                    $udata['clocking_status'] = 1;
                    $this->admin_model->save($udata, $check_date->attendance_id);
                }
                $data['attendance_id'] = $check_date->attendance_id;
            } else { // else save data into tbl attendance
                // get attendance id by clock id into tbl clock
                // if attendance id exist that means he/she clock in
                // return the id
                // and update the day out time
                $check_existing_data = $this->admin_model->check_by(array('clock_id' => $id), 'tbl_clock');
                $this->admin_model->_table_name = "tbl_attendance"; // table name
                $this->admin_model->_primary_key = "attendance_id"; // $id
                if (!empty($check_existing_data)) {
                    $adata['clocking_status'] = 0;
                    $this->admin_model->save($adata, $check_existing_data->attendance_id);
                } else {
                    $adata['attendance_status'] = 1;
                    $adata['clocking_status'] = 1;
                    //save data into attendance table
                    $data['attendance_id'] = $this->admin_model->save($adata);
                }
            }
            // save data into clock table
            if ($clocktime == 1) {
                $data['clockin_time'] = $time;
                send_clock_email('clock_in_email');
            } else {
                $data['clockout_time'] = $time;
                $data['comments'] = $this->input->post('comments', TRUE);
                send_clock_email('clock_out_email');
            }
            $data['ip_address'] = $this->input->ip_address();
            //save data in database
            $this->admin_model->_table_name = "tbl_clock"; // table name
            $this->admin_model->_primary_key = "clock_id"; // $id
            if (!empty($id)) {
                $data['clocking_status'] = 0;
                $this->admin_model->save($data, $id);
            } else {
                $data['clocking_status'] = 1;
                $id = $this->admin_model->save($data);
                if (!empty($check_date)) {
                    if ($check_date->clocking_status == 1) {
                        $data['clockout_time'] = $time;
                        $data['clocking_status'] = 0;
                        $this->admin_model->save($data, $id);
                    }
                }
            }
        } else {
            set_message('error', 'please contact with admin to clock in');
        }
        if (empty($redirect)) {
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/dashboard');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            return true;
        }
    }

    public function update_clock()
    {
        $clock_in = $this->input->post('clock_in', true);
        $clock_out = $this->input->post('clock_out', true);
        if (!empty($clock_in)) {
            foreach ($clock_in as $user_id) {
                $this->set_clocking(0, $user_id, true, true);
            }
        }
        if (!empty($clock_out)) {
            foreach ($clock_out as $clock_out_id) {
                $clock_id = $this->input->post($clock_out_id, true);
                $this->set_clocking($clock_id, $clock_out_id, 0, true);
            }
        }
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/dashboard');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public
    function mark_attendance()
    {
        $this->load->model('global_model');
        $this->load->model('attendance_model');

        $data['title'] = lang('mark_attendance');
        $date = $this->input->post('attend_date', TRUE);

        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $data['date'] = $date;

        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));
        $day = date('d', strtotime($date));

        $data['users'] = get_staff_details();

        $holidays = $this->global_model->get_holidays(); //tbl working Days Holiday

        if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
        } else {
            $yymm = $year . '-' . $month;
        }

        $public_holiday = $this->global_model->get_public_holidays($yymm);

        //tbl a_calendar Days Holiday
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                $p_hday = $this->global_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
        }
        foreach ($data['users'] as $sl => $v_employee) {
            if ($v_employee->user_id != $this->session->userdata('user_id')) {
                $x = 1;
                if ($day >= 1 && $day <= 9) {
                    $sdate = $yymm . '-' . '0' . $day;
                } else {
                    $sdate = $yymm . '-' . $day;
                }
                $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $day)));

                // get leave info

                if (!empty($holidays)) {
                    foreach ($holidays as $v_holiday) {
                        if ($v_holiday->day == $day_name) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($p_hday)) {
                    foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($flag)) {
                    $data['attendace_info'][$sl] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                } else {
                    $data['attendace_info'][$sl] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                }
                $flag = '';
            }
        }
        $data['subview'] = $this->load->view('admin/settings/mark_attendance', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function change_report($status, $type = null)
    {
        if (!empty($type)) {
            if ($status == 'show') {
                $input_data[$type . '_state'] = 'block';
            } else {
                $input_data[$type . '_state'] = 'none';
            }
        } else {
            if ($status == 'show') {
                $input_data['invoice_state'] = 'block';
            } else {
                $input_data['invoice_state'] = 'none';
            }
        }
        foreach ($input_data as $key => $value) {
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/dashboard');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function new_todo($id = null)
    {
        $data['title'] = lang('new') . ' ' . lang('to_do');
        if (!empty($id)) {
            $data['todo_info'] = $this->db->where('todo_id', $id)->get('tbl_todo')->row();;
        }
        $data['modal_subview'] = $this->load->view('admin/settings/new_todo', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function save_todo($id = null)
    {
        $data = $this->admin_model->array_from_post(array('user_id', 'title', 'status', 'due_date'));
        if (!empty($data['user_id']) && $data['user_id'] != $this->session->userdata('user_id')) {
            $data['assigned'] = $this->session->userdata('user_id');
        } else {
            $data['assigned'] = 0;
        }
        if (empty($data['user_id'])) {
            $data['user_id'] = $this->session->userdata('user_id');
        }
        if (empty($id)) {
            $data['order'] = 1;
        }
        $this->admin_model->_table_name = "tbl_todo"; // table name
        $this->admin_model->_primary_key = "todo_id"; // $id
        $this->admin_model->save($data, $id);
        $type = "success";
        $message = lang('todo_information_updated');
        set_message($type, $message);
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/dashboard');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function delete_todo($todo_id = '')
    {
        $this->db->where('todo_id', $todo_id);
        $this->db->delete('tbl_todo');
        $type = "success";
        $message = lang('todo_information_deleted');
        set_message($type, $message);
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/dashboard');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function completed_todo($todo_id = '')
    {
        $data['status'] = $this->input->post('status', true);
        $this->db->where('todo_id', $todo_id);
        $this->db->update('tbl_todo', $data);
        $type = "success";
        $message = lang('todo_status_change');
        echo json_encode(array("status" => $type, "message" => $message));
        exit();
    }

    public function change_todo_status($id = null, $status = null)
    {

        $_status = $this->input->post('status', true);
        if (!empty($_status)) {
            $todo_id = $this->input->post('todo_id', true);
            foreach ($todo_id as $key => $id) {
                $data['status'] = $_status;
                $data['order'] = $key + 1;
                //save data into table.
                $this->admin_model->_table_name = "tbl_todo"; // table name
                $this->admin_model->_primary_key = "todo_id"; // $id
                $this->admin_model->save($data, $id);
            }
            $post = true;

        } else {
            $data['status'] = $status;
            $todo_id = $id;

            $this->admin_model->_table_name = "tbl_todo"; // table name
            $this->admin_model->_primary_key = "todo_id"; // $id
            $this->admin_model->save($data, $todo_id);

        }
        if (!empty($post)) {
            $type = "success";
            $message = lang('todo_status_change');
            echo json_encode(array("status" => $type, "message" => $message));
            exit();
        } else {
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/dashboard');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }

    }

    public function all_todo($id = null)
    {
        $data['title'] = lang('all') . ' ' . lang('to_do') . ' ' . lang('list');
        $user_id = $this->input->post('user_id', true);
        if ($id == 'kanban') {
            $k_session['todo_kanban'] = $id;
            $this->session->set_userdata($k_session);
        } elseif ($id == 'list') {
            $data['active'] = 1;
            $this->session->unset_userdata('todo_kanban');
        }
        if (!empty($user_id)) {
            $data['user_id'] = $user_id;
            if ($user_id != $this->session->userdata('user_id')) {
                $data['where'] = array('assigned' => $this->session->userdata('user_id'));
            } else {
                $data['where'] = null;
            }

        } else {
            $data['user_id'] = $this->session->userdata('user_id');
            $data['where'] = null;
        }

        $data['subview'] = $this->load->view('admin/settings/all_todo', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

}
