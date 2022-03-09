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
class Quotations extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('quotations_model');
    }

    public function index($action = NULL, $id = NULL)
    {
        $data['title'] = lang('quotations');
        $data['page'] = lang('quotations');
        $data['sub_active'] = lang('quotations');
        if ($action == 'delete_quotations') {
            $this->quotations_model->_table_name = 'tbl_quotations';
            $this->quotations_model->_primary_key = 'quotations_id';
            $this->quotations_model->delete($id);
            $type = 'success';
            $message = lang('delete_quotation');
            set_message($type, $message);
            redirect('admin/quotations/');
        } else {

            $sub_view = 'all_quotations';
            //$sub_view = 'all_quotations';
            $data['active'] = 1;
        }
        $this->quotations_model->_table_name = 'tbl_quotations';
        $this->quotations_model->_order_by = 'quotations_id';
        $data['all_quatations'] = $this->quotations_model->get();

        $data['subview'] = $this->load->view('admin/quotations/' . $sub_view, $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function quotationsList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_quotations';
            $this->datatables->column_order = array('quotations_date', 'quotations_amount');
            $this->datatables->column_search = array('quotations_date', 'quotations_amount');
            $this->datatables->order = array('quotations_id' => 'desc');

            // get all invoice
            $fetch_data = make_datatables();

            $data = array();

            foreach ($fetch_data as $_key => $v_quatations) {

                $action = null;
                $user_info = $this->quotations_model->check_by(array('user_id' => $v_quatations->user_id), 'tbl_users');
                if (!empty($user_info)) {
                    if ($user_info->role_id == 1) {
                        $user = '(' . lang('admin') . ')';
                    } elseif ($user_info->role_id == 3) {
                        $user = '(' . lang('staff') . ')';
                    } else {
                        $user = '(' . lang('client') . ')';
                    }
                } else {
                    $user = ' ';
                }

                $sub_array = array();

                $sub_array[] = '<a class="text-info" href="' . base_url() . 'admin/quotations/quotations_details/' . $v_quatations->quotations_id . '">' . $v_quatations->quotations_form_title . '</a>';

                $sub_array[] = $v_quatations->name;
                $sub_array[] = strftime(config_item('date_format'), strtotime($v_quatations->quotations_date));
                $amount = null;
                if (!empty($v_quatations->quotations_amount)) {
                    $amount = display_money($v_quatations->quotations_amount, client_currency($v_quatations->client_id));
                }
                $sub_array[] = $amount;
                if ($v_quatations->quotations_status == 'completed') {
                    $quotations_status = '<span class="label label-success">' . lang('completed') . '</span>';
                } else {
                    $quotations_status = '<span class="label label-danger">' . lang('pending') . '</span>';
                };
                $sub_array[] = $quotations_status;

                $sub_array[] = (!empty($user_info->username) ? $user_info->username : '-') . ' ' . $user;

                $action .= btn_view('admin/quotations/quotations_details/' . $v_quatations->quotations_id) . ' ';
                $action .= ajax_anchor(base_url("admin/quotations/index/delete_quotations/$v_quatations->quotations_id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';

                $sub_array[] = $action;
                $data[] = $sub_array;
            }

            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function quotations_form($action = NULL, $id = NULL)
    {
        $data['title'] = lang('quotations');
        $data['page'] = lang('quotations');
        $data['quotationforms_info'] = $this->quotations_model->check_by(array('quotationforms_id' => $id), 'tbl_quotationforms');
        if ($action == 'edit_quotations_form') {
            $data['sub_active'] = lang('quotations_form');
            $form_data = json_decode($data['quotationforms_info']->quotationforms_code, true);

            $data['formbuilder_data'] = $form_data['fields'];

            $data['quotationforms_code'] = json_encode($form_data['fields']);
            $sub_view = 'quotations_form_details';
            $data['active'] = 2;
        } elseif ($action == 'delete_quotations_form') {
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'quotations',
                'module_field_id' => $id,
                'activity' => ('activity_delete_quotations_form'),
                'icon' => 'fa-coffee',
                'value1' => $data['quotationforms_info']->quotationforms_title,
            );
            // Update into tbl_project
            $this->quotations_model->_table_name = "tbl_activities"; //table name
            $this->quotations_model->_primary_key = "activities_id";
            $this->quotations_model->save($activities);

            $this->quotations_model->_table_name = 'tbl_quotationforms';
            $this->quotations_model->_primary_key = 'quotationforms_id';
            $this->quotations_model->delete($id);
            $type = 'success';
            $message = lang('delete_quotation_form');
            set_message($type, $message);
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/quotations_form');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $data['sub_active'] = lang('quotations_form');
            $sub_view = 'quotations_form';
            //$sub_view = 'all_quotations';
            $data['active'] = 1;
        }
        $this->quotations_model->_table_name = 'tbl_quotationforms';
        $this->quotations_model->_order_by = 'quotationforms_id';
        $data['all_quatations'] = $this->quotations_model->get();

        $data['subview'] = $this->load->view('admin/quotations/' . $sub_view, $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function quotationsformList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_quotationforms';
            $this->datatables->column_order = array('quotationforms_title', 'quotationforms_date_created');
            $this->datatables->column_search = array('quotationforms_title', 'quotationforms_date_created');
            $this->datatables->order = array('quotationforms_id' => 'desc');

            // get all invoice
            $fetch_data = make_datatables();

            $data = array();

            foreach ($fetch_data as $_key => $v_quatations) {

                $action = null;
                $sub_array = array();

                $sub_array[] = '<a class="text-info" href="' . base_url() . 'admin/quotations/quotations_form_details/' . $v_quatations->quotationforms_id . '">' . $v_quatations->quotationforms_title . '</a>';

                $sub_array[] = fullname($v_quatations->quotations_created_by_id);
                $sub_array[] = strftime(config_item('date_format'), strtotime($v_quatations->quotationforms_date_created));
                if ($v_quatations->quotationforms_status == 'enabled') {
                    $quotationforms_status = '<span class="label label-success"> ' . lang('enabled') . '</span>';
                } else {
                    $quotationforms_status = '<span class="label label-danger">' . lang('disabled') . '</span>';
                };
                $sub_array[] = $quotationforms_status;

                $action .= btn_view('admin/quotations/quotations_form/edit_quotations_form/' . $v_quatations->quotationforms_id) . ' ';
                $action .= ajax_anchor(base_url("admin/quotations/quotations_form/delete_quotations_form/$v_quatations->quotationforms_id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';

                $sub_array[] = $action;
                $data[] = $sub_array;
            }

            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function quotations_details($quotations_id)
    {
        $data['title'] = 'View  Quatations Form';
        $data['page'] = lang('quotations');
        $data['sub_active'] = lang('quotations');
        $data['quotations_info'] = $this->quotations_model->check_by(array('quotations_id' => $quotations_id), 'tbl_quotations');
        $this->quotations_model->_table_name = 'tbl_quotation_details';
        $this->quotations_model->_order_by = 'quotations_id';
        $data['quotation_details'] = $this->quotations_model->get_by(array('quotations_id' => $quotations_id), FALSE);
        $data['subview'] = $this->load->view('admin/quotations/quotation_details', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function quotations_details_pdf($quotations_id)
    {
        $data['title'] = 'View  Quatations Form';
        $data['page'] = lang('quotations');
        $data['sub_active'] = lang('quotations');
        $data['quotations_info'] = $this->quotations_model->check_by(array('quotations_id' => $quotations_id), 'tbl_quotations');
        $this->quotations_model->_table_name = 'tbl_quotation_details';
        $this->quotations_model->_order_by = 'quotations_id';
        $data['quotation_details'] = $this->quotations_model->get_by(array('quotations_id' => $quotations_id), FALSE);

        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/quotations/quotations_details_pdf', $data, TRUE);
        pdf_create($viewfile, slug_it($data['quotations_info']->quotations_form_title));
    }

    public function set_price($quotations_id)
    {
        $data['quotations_id'] = $quotations_id;
        $data['quotations_info'] = $this->quotations_model->check_by(array('quotations_id' => $quotations_id), 'tbl_quotations');
        $data['subview'] = $this->load->view('admin/quotations/set_price', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function set_price_quotations($id)
    {
        $data = $this->quotations_model->array_from_post(array('quotations_amount', 'notes'));
        $qtation_info = $this->quotations_model->check_by(array('quotations_id' => $id), 'tbl_quotations');
        $client_info = $this->quotations_model->check_by(array('client_id' => $qtation_info->client_id), 'tbl_client');

        $send_mail = $this->input->post('send_email', TRUE);
        if ($send_mail == 'on') {
            $email_template = email_templates(array('email_group' => 'quotations_form'), $qtation_info->client_id);

            $message = $email_template->template_body;
            $subject = $email_template->subject;
            $client_name = str_replace("{CLIENT}", $client_info->name, $message);

            $Date = str_replace("{DATE}", date('Y-m-d'), $client_name);
            $Currency = str_replace("{CURRENCY}", client_currency($qtation_info->client_id), $Date);
            $Amount = str_replace("{AMOUNT}", $this->input->post('quotations_amount', true), $Currency);
            $Notes = str_replace("{NOTES}", $this->input->post('notes', true), $Amount);
            $link = str_replace("{QUOTATION LINK}", base_url() . 'client/quotations/quotations_details/' . $id, $Notes);
            $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);

            $sdata['message'] = $message;
            $message = $this->load->view('email_template', $sdata, TRUE);


            $address = $client_info->email;
            $params['recipient'] = $address;
            $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
            $params['message'] = $message;
            $params['resourceed_file'] = '';
            $this->quotations_model->send_email($params);
        }
        if (!empty($client_info->primary_contact)) {
            $notifyUser = array($client_info->primary_contact);
        } else {
            $user_info = $this->quotations_model->check_by(array('company' => $client_info->client_id), 'tbl_account_details');
            if (!empty($user_info)) {
                $notifyUser = array($user_info->user_id);
            }
        }
        if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
                if ($v_user != $this->session->userdata('user_id')) {
                    add_notification(array(
                        'to_user_id' => $v_user,
                        'icon' => 'paste',
                        'description' => 'not_set_quotations_price',
                        'link' => 'client/quotations/quotations_details/' . $id,
                        'value' => $qtation_info->quotations_form_title . ' ' . lang('amount') . ' ' . display_money($this->input->post('quotations_amount', true), client_currency($qtation_info->client_id)),
                    ));
                }
            }
            show_notification($notifyUser);
        }

        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'quotations',
            'module_field_id' => $id,
            'activity' => ('activity_set_quotations_price'),
            'icon' => 'fa-coffee',
            'value1' => $qtation_info->quotations_form_title,
            'value2' => $client_info->name . '(' . display_money($this->input->post('quotations_amount', true), client_currency($qtation_info->client_id)) . ')',
        );
        // Update into tbl_project
        $this->quotations_model->_table_name = "tbl_activities"; //table name
        $this->quotations_model->_primary_key = "activities_id";
        $this->quotations_model->save($activities);

        $data['reviewer_id'] = $this->session->userdata('user_id');
        $data['reviewed_date'] = date('Y-m-d H:i:s');
        $data['quotations_status'] = 'completed';

        $this->quotations_model->_table_name = 'tbl_quotations';
        $this->quotations_model->_primary_key = 'quotations_id';
        $this->quotations_model->save($data, $id);
        $type = 'success';
        $message = lang('save_quotation_form');
        set_message($type, $message);
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/quotations_form');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function quotations_form_details($id)
    {
        $data['title'] = 'View  Quatations Form';
        $data['page'] = lang('quotations');
        $data['sub_active'] = lang('quotations_form');
        $data['quotationforms_info'] = $this->quotations_model->check_by(array('quotationforms_id' => $id), 'tbl_quotationforms');

        $form_data = json_decode($data['quotationforms_info']->quotationforms_code, true);

        $data['formbuilder_data'] = $form_data['fields'];

        $data['quotationforms_code'] = json_encode($form_data['fields']);

        $data['subview'] = $this->load->view('admin/quotations/view_quotations_form', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function quotations_form_details_pdf($id)
    {
        $data['title'] = 'View  Quatations Form';
        $data['quotationforms_info'] = $this->quotations_model->check_by(array('quotationforms_id' => $id), 'tbl_quotationforms');
        $form_data = json_decode($data['quotationforms_info']->quotationforms_code, true);
        $data['formbuilder_data'] = $form_data['fields'];
        $data['quotationforms_code'] = json_encode($form_data['fields']);
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/quotations/quotations_form_details_pdf', $data, TRUE);
        pdf_create($viewfile, slug_it($data['quotationforms_info']->quotationforms_title));
    }


    public function add_form($id = NULL)
    {
        $data['quotationforms_title'] = $this->input->post('quotationforms_title', TRUE);
        $data['quotationforms_code'] = $this->input->post('quotationforms_code', TRUE);
        if (!empty($id)) {
            $data['quotationforms_status'] = $this->input->post('quotationforms_status', TRUE);
        }
        $data['quotations_created_by_id'] = $this->session->userdata('user_id');

        $this->quotations_model->_table_name = 'tbl_quotationforms';
        $this->quotations_model->_primary_key = 'quotationforms_id';
        $this->quotations_model->save($data, $id);
        if (!empty($id)) {
            $action = ('activity_update_quotation_form');
        } else {
            $action = ('activity_save_quotation_form');
        }
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'quotations',
            'module_field_id' => $id,
            'activity' => $action,
            'icon' => 'fa-coffee',
            'value1' => $data['quotationforms_title'],
        );
        // Update into tbl_project
        $this->quotations_model->_table_name = "tbl_activities"; //table name
        $this->quotations_model->_primary_key = "activities_id";
        $this->quotations_model->save($activities);

        $type = 'success';
        $message = lang('save_quotation_form');
        set_message($type, $message);
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/quotations_form');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }


    public
    function convert_to($type, $id)
    {

        $data['title'] = lang('convert') . ' ' . lang($type);
        $edited = can_action('22', 'edited');
        $can_edit = $this->quotations_model->can_action('tbl_quotations', 'edit', array('quotations_id' => $id));
        if (!empty($can_edit) && !empty($edited)) {
            // get all client
            $this->quotations_model->_table_name = 'tbl_client';
            $this->quotations_model->_order_by = 'client_id';
            $data['all_client'] = $this->quotations_model->get();
            // get permission user
            $data['permission_user'] = $this->quotations_model->all_permission_user('22');
            $data['quotations_info'] = $this->quotations_model->check_by(array('quotations_id' => $id), 'tbl_quotations');
            $data['modal_subview'] = $this->load->view('admin/quotations/convert_to_' . $type, $data, FALSE);
            $this->load->view('admin/_layout_modal_large', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/quotations');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function converted_to_invoice($quotations_id)
    {
        $data = $this->quotations_model->array_from_post(array('reference_no', 'client_id', 'project_id', 'discount_type', 'discount_percent', 'user_id', 'adjustment', 'discount_total', 'show_quantity_as'));

        $all_payment = get_result('tbl_online_payment');
        foreach ($all_payment as $payment) {
            $allow_gateway = 'allow_' . slug_it(strtolower($payment->gateway_name));
            $gateway_status = slug_it(strtolower($payment->gateway_name)) . '_status';
            if (config_item($gateway_status) == 'active') {
                $data[$allow_gateway] = ($this->input->post($allow_gateway) == 'Yes') ? 'Yes' : 'No';
            }
        }
        $data['client_visible'] = ($this->input->post('client_visible') == 'Yes') ? 'Yes' : 'No';
        $data['invoice_date'] = date('Y-m-d', strtotime($this->input->post('invoice_date', TRUE)));
        if (empty($data['invoice_date'])) {
            $data['invoice_date'] = date('Y-m-d');
        }
        if (empty($data['discount_total'])) {
            $data['discount_total'] = 0;
        }
        $data['invoice_year'] = date('Y', strtotime($this->input->post('invoice_date', TRUE)));
        $data['invoice_month'] = date('Y-m', strtotime($this->input->post('invoice_date', TRUE)));
        $data['due_date'] = date('Y-m-d', strtotime($this->input->post('due_date', TRUE)));
        $data['notes'] = $this->input->post('notes', TRUE);
        $tax['tax_name'] = $this->input->post('total_tax_name', TRUE);
        $tax['total_tax'] = $this->input->post('total_tax', TRUE);
        $data['total_tax'] = json_encode($tax);
        $i_tax = 0;
        if (!empty($tax['total_tax'])) {
            foreach ($tax['total_tax'] as $v_tax) {
                $i_tax += $v_tax;
            }
        }
        $data['tax'] = $i_tax;
        $save_as_draft = $this->input->post('save_as_draft', TRUE);
        if (!empty($save_as_draft)) {
            $data['status'] = 'draft';
        }
        $currency = $this->quotations_model->client_currency_symbol($data['client_id']);
        if (!empty($currency->code)) {
            $curren = $currency->code;
        } else {
            $curren = config_item('default_currency');
        }
        $data['currency'] = $curren;

        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->quotations_model->array_from_post(array('assigned_to'));
                if (!empty($assigned_to['assigned_to'])) {
                    foreach ($assigned_to['assigned_to'] as $assign_user) {
                        $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                    }
                }
            }
            if (!empty($assigned)) {
                if ($assigned != 'all') {
                    $assigned = json_encode($assigned);
                }
            } else {
                $assigned = 'all';
            }
            $data['permission'] = $assigned;
        } else {
            set_message('error', lang('assigned_to') . ' Field is required');
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/quotations');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }

        // get all client
        $this->quotations_model->_table_name = 'tbl_invoices';
        $this->quotations_model->_primary_key = 'invoices_id';
        $invoice_id = $this->quotations_model->save($data);

        $recuring_frequency = $this->input->post('recuring_frequency', TRUE);
        if (!empty($recuring_frequency) && $recuring_frequency != 'none') {
            $recur_data = $this->quotations_model->array_from_post(array('recur_start_date', 'recur_end_date'));
            $recur_data['recuring_frequency'] = $recuring_frequency;
            $this->get_recuring_frequency($invoice_id, $recur_data); // set recurring
        }

        // save items
        $qty_calculation = config_item('qty_calculation_from_items');
        // save items
        $invoices_to_merge = $this->input->post('invoices_to_merge', TRUE);
        $cancel_merged_invoices = $this->input->post('cancel_merged_invoices', TRUE);
        if (!empty($invoices_to_merge)) {
            foreach ($invoices_to_merge as $inv_id) {
                if (empty($cancel_merged_invoices)) {
                    if (!empty($qty_calculation) && $qty_calculation == 'Yes') {
                        $all_items_info = $this->db->where('invoices_id', $inv_id)->get('tbl_items')->result();
                        if (!empty($all_items_info)) {
                            foreach ($all_items_info as $v_items) {
                                $this->return_items($v_items->items_id);
                            }
                        }
                    }
                    $this->db->where('invoices_id', $inv_id);
                    $this->db->delete('tbl_invoices');

                    $this->db->where('invoices_id', $inv_id);
                    $this->db->delete('tbl_items');
                } else {
                    $mdata = array('status' => 'Cancelled');
                    $this->quotations_model->_table_name = 'tbl_invoices';
                    $this->quotations_model->_primary_key = 'invoices_id';
                    $this->quotations_model->save($mdata, $inv_id);
                }
            }
        }

        $removed_items = $this->input->post('removed_items', TRUE);
        if (!empty($removed_items)) {
            foreach ($removed_items as $r_id) {
                if ($r_id != 'undefined') {
                    if (!empty($qty_calculation) && $qty_calculation == 'Yes') {
                        $this->return_items($r_id);
                    }

                    $this->db->where('items_id', $r_id);
                    $this->db->delete('tbl_items');
                }
            }
        }

        $itemsid = $this->input->post('items_id', TRUE);
        $items_data = $this->input->post('items', true);

        if (!empty($items_data)) {
            $index = 0;
            foreach ($items_data as $items) {
                $items['invoices_id'] = $invoice_id;
                $tax = 0;
                if (!empty($items['taxname'])) {
                    foreach ($items['taxname'] as $tax_name) {
                        $tax_rate = explode("|", $tax_name);
                        $tax += $tax_rate[1];
                    }
                    $items['item_tax_name'] = $items['taxname'];
                    unset($items['taxname']);
                    $items['item_tax_name'] = json_encode($items['item_tax_name']);
                }
                if (empty($items['saved_items_id'])) {
                    $items['saved_items_id'] = 0;
                }
                if (!empty($qty_calculation) && $qty_calculation == 'Yes') {
                    if (!empty($items['saved_items_id']) && $items['saved_items_id'] != 'undefined') {
                        $this->quotations_model->reduce_items($items['saved_items_id'], $items['quantity']);
                    }
                }
                $price = $items['quantity'] * $items['unit_cost'];
                $items['item_tax_total'] = ($price / 100 * $tax);
                $items['total_cost'] = $price;
                // get all client
                $this->quotations_model->_table_name = 'tbl_items';
                $this->quotations_model->_primary_key = 'items_id';
                $this->quotations_model->save($items);
                $index++;
            }
        }
        if (!empty($invoice_id)) {
            $p_data = array('quotations_status' => 'completed', 'is_convert' => 'Yes', 'convert_module' => 'invoice', 'convert_module_id' => $invoice_id);

            $this->quotations_model->_table_name = 'tbl_quotations';
            $this->quotations_model->_primary_key = 'quotations_id';
            $this->quotations_model->save($p_data, $quotations_id);
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'quotations',
            'module_field_id' => $invoice_id,
            'activity' => 'convert_to_invoice_from_quotations',
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/quotations/quotations_details/' . $quotations_id,
            'value1' => $data['reference_no']
        );
        $this->quotations_model->_table_name = 'tbl_activities';
        $this->quotations_model->_primary_key = 'activities_id';
        $this->quotations_model->save($activity);

        // send notification to client
        if (!empty($data['client_id'])) {
            $client_info = $this->quotations_model->check_by(array('client_id' => $data['client_id']), 'tbl_client');
            if (!empty($client_info->primary_contact)) {
                $notifyUser = array($client_info->primary_contact);
            } else {
                $user_info = $this->quotations_model->check_by(array('company' => $data['client_id']), 'tbl_account_details');
                if (!empty($user_info)) {
                    $notifyUser = array($user_info->user_id);
                }
            }
        }
        if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
                if ($v_user != $this->session->userdata('user_id')) {
                    add_notification(array(
                        'to_user_id' => $v_user,
                        'icon' => 'shopping-cart',
                        'description' => 'quotations_convert_to_invoice',
                        'link' => 'client/invoice/manage_invoice/invoice_details/' . $invoice_id,
                        'value' => $data['reference_no'],
                    ));
                }
            }
            show_notification($notifyUser);
        }
        // messages for user
        $type = "success";
        $message = lang('convert_to_invoice') . ' ' . lang('successfully');
        set_message($type, $message);
        redirect('admin/quotations/quotations_details/' . $quotations_id);
    }

    function return_items($items_id)
    {
        $items_info = $this->db->where('items_id', $items_id)->get('tbl_items')->row();
        if (!empty($items_info->saved_items_id)) {
            $this->quotations_model->return_items($items_info->saved_items_id, $items_info->quantity);
        }
        return true;
    }

    function check_existing_qty($items_id, $qty)
    {
        $items_info = $this->db->where('items_id', $items_id)->get('tbl_items')->row();
        if (!empty($items_info)) {
            if ($items_info->quantity != $qty) {
                if ($qty > $items_info->quantity) {
                    $reduce_qty = $qty - $items_info->quantity;
                    if (!empty($items_info->saved_items_id)) {
                        $this->quotations_model->reduce_items($items_info->saved_items_id, $reduce_qty);
                    }
                }
                if ($qty < $items_info->quantity) {
                    $return_qty = $items_info->quantity - $qty;
                    if (!empty($items_info->saved_items_id)) {
                        $this->quotations_model->return_items($items_info->saved_items_id, $return_qty);
                    }
                }
            }
        }
        return true;
    }

    function get_recuring_frequency($invoices_id, $recur_data)
    {
        $recur_days = $this->get_calculate_recurring_days($recur_data['recuring_frequency']);
        $due_date = $this->quotations_model->get_table_field('tbl_invoices', array('invoices_id' => $invoices_id), 'due_date');

        $next_date = date("Y-m-d", strtotime($due_date . "+ " . $recur_days . " days"));

        if ($recur_data['recur_end_date'] == '') {
            $recur_end_date = '0000-00-00';
        } else {
            $recur_end_date = date('Y-m-d', strtotime($recur_data['recur_end_date']));
        }
        $update_invoice = array(
            'recurring' => 'Yes',
            'recuring_frequency' => $recur_days,
            'recur_frequency' => $recur_data['recuring_frequency'],
            'recur_start_date' => date('Y-m-d', strtotime($recur_data['recur_start_date'])),
            'recur_end_date' => $recur_end_date,
            'recur_next_date' => $next_date
        );
        $this->quotations_model->_table_name = 'tbl_invoices';
        $this->quotations_model->_primary_key = 'invoices_id';
        $this->quotations_model->save($update_invoice, $invoices_id);
        return TRUE;
    }

    function get_calculate_recurring_days($recuring_frequency)
    {
        switch ($recuring_frequency) {
            case '7D':
                return 7;
                break;
            case '1M':
                return 31;
                break;
            case '3M':
                return 90;
                break;
            case '6M':
                return 182;
                break;
            case '1Y':
                return 365;
                break;
        }
    }

    public function converted_to_estimate($quotations_id)
    {
        $data = $this->quotations_model->array_from_post(array('reference_no', 'client_id', 'project_id', 'discount_type', 'discount_percent', 'user_id', 'adjustment', 'discount_total', 'show_quantity_as'));

        $data['client_visible'] = ($this->input->post('client_visible') == 'Yes') ? 'Yes' : 'No';
        $data['estimate_date'] = date('Y-m-d', strtotime($this->input->post('estimate_date', TRUE)));
        if (empty($data['estimate_date'])) {
            $data['estimate_date'] = date('Y-m-d');
        }
        if (empty($data['discount_total'])) {
            $data['discount_total'] = 0;
        }
        $data['estimate_year'] = date('Y', strtotime($this->input->post('estimate_date', TRUE)));
        $data['estimate_month'] = date('Y-m', strtotime($this->input->post('estimate_date', TRUE)));
        $data['due_date'] = date('Y-m-d', strtotime($this->input->post('due_date', TRUE)));
        $data['notes'] = $this->input->post('notes', TRUE);
        $tax['tax_name'] = $this->input->post('total_tax_name', TRUE);
        $tax['total_tax'] = $this->input->post('total_tax', TRUE);
        $data['total_tax'] = json_encode($tax);
        $i_tax = 0;
        if (!empty($tax['total_tax'])) {
            foreach ($tax['total_tax'] as $v_tax) {
                $i_tax += $v_tax;
            }
        }
        $data['tax'] = $i_tax;
        $save_as_draft = $this->input->post('status', TRUE);
        if (!empty($save_as_draft)) {
            $data['status'] = $save_as_draft;
        } else {
            $data['status'] = 'pending';
        }
        $currency = $this->quotations_model->client_currency_symbol($data['client_id']);
        if (!empty($currency->code)) {
            $curren = $currency->code;
        } else {
            $curren = config_item('default_currency');
        }
        $data['currency'] = $curren;

        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->quotations_model->array_from_post(array('assigned_to'));
                if (!empty($assigned_to['assigned_to'])) {
                    foreach ($assigned_to['assigned_to'] as $assign_user) {
                        $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                    }
                }
            }
            if (!empty($assigned)) {
                if ($assigned != 'all') {
                    $assigned = json_encode($assigned);
                }
            } else {
                $assigned = 'all';
            }
            $data['permission'] = $assigned;
        } else {
            set_message('error', lang('assigned_to') . ' Field is required');
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/quotations');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        // get all client
        $this->quotations_model->_table_name = 'tbl_estimates';
        $this->quotations_model->_primary_key = 'estimates_id';
        if (!empty($id)) {
            $estimates_id = $id;
            $this->quotations_model->save($data, $id);
        } else {
            $estimates_id = $this->quotations_model->save($data);
        }
        // save items
        $invoices_to_merge = $this->input->post('invoices_to_merge', TRUE);
        $cancel_merged_invoices = $this->input->post('cancel_merged_estimate', TRUE);
        if (!empty($invoices_to_merge)) {
            foreach ($invoices_to_merge as $inv_id) {
                if (empty($cancel_merged_invoices)) {
                    $this->db->where('estimates_id', $inv_id);
                    $this->db->delete('tbl_estimates');

                    $this->db->where('estimate_items_id', $inv_id);
                    $this->db->delete('tbl_estimate_items');
                } else {
                    $mdata = array('status' => 'cancelled');
                    $this->quotations_model->_table_name = 'tbl_estimates';
                    $this->quotations_model->_primary_key = 'estimates_id';
                    $this->quotations_model->save($mdata, $inv_id);
                }
            }
        }

        $removed_items = $this->input->post('removed_items', TRUE);
        if (!empty($removed_items)) {
            foreach ($removed_items as $r_id) {
                if ($r_id != 'undefined') {
                    $this->db->where('estimate_items_id', $r_id);
                    $this->db->delete('tbl_estimate_items');
                }
            }
        }

        $itemsid = $this->input->post('estimate_items_id', TRUE);
        $items_data = $this->input->post('items', true);

        if (!empty($items_data)) {
            $index = 0;
            foreach ($items_data as $items) {
                $items['estimates_id'] = $estimates_id;
                if (!empty($items['taxname'])) {
                    $tax = 0;
                    foreach ($items['taxname'] as $tax_name) {
                        $tax_rate = explode("|", $tax_name);
                        $tax += $tax_rate[1];
                    }
                    $price = $items['quantity'] * $items['unit_cost'];
                    $items['item_tax_total'] = ($price / 100 * $tax);
                    $items['total_cost'] = $price;

                    $items['item_tax_name'] = $items['taxname'];
                    unset($items['taxname']);
                    $items['item_tax_name'] = json_encode($items['item_tax_name']);
                }
                // get all client
                $this->quotations_model->_table_name = 'tbl_estimate_items';
                $this->quotations_model->_primary_key = 'estimate_items_id';
                if (!empty($itemsid[$index])) {
                    $items_id = $itemsid[$index];
                    $this->quotations_model->save($items, $items_id);
                } else {
                    $items_id = $this->quotations_model->save($items);
                }
                $index++;
            }
        }
        $p_data = array('quotations_status' => 'completed', 'is_convert' => 'Yes', 'convert_module' => 'estimate', 'convert_module_id' => $estimates_id);
        $this->quotations_model->_table_name = 'tbl_quotations';
        $this->quotations_model->_primary_key = 'quotations_id';
        $this->quotations_model->save($p_data, $quotations_id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'quotations',
            'module_field_id' => $estimates_id,
            'activity' => 'convert_to_estimate_from_quotations',
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/quotations/quotations_details/' . $quotations_id,
            'value1' => $data['reference_no']
        );
        $this->quotations_model->_table_name = 'tbl_activities';
        $this->quotations_model->_primary_key = 'activities_id';
        $this->quotations_model->save($activity);

        // send notification to client
        if (!empty($data['client_id'])) {
            $client_info = $this->quotations_model->check_by(array('client_id' => $data['client_id']), 'tbl_client');
            if (!empty($client_info->primary_contact)) {
                $notifyUser = array($client_info->primary_contact);
            } else {
                $user_info = $this->quotations_model->check_by(array('company' => $data['client_id']), 'tbl_account_details');
                if (!empty($user_info)) {
                    $notifyUser = array($user_info->user_id);
                }
            }
        }
        if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
                if ($v_user != $this->session->userdata('user_id')) {
                    add_notification(array(
                        'to_user_id' => $v_user,
                        'icon' => 'shopping-cart',
                        'description' => 'quotations_convert_to_estimate',
                        'link' => 'client/estimates/index/estimates_details/' . $estimates_id,
                        'value' => $data['reference_no'],
                    ));
                }
            }
            show_notification($notifyUser);
        }
        // messages for user
        $type = "success";
        $message = lang('convert_to_estimate') . ' ' . lang('successfully');
        set_message($type, $message);
        redirect('admin/quotations/quotations_details/' . $quotations_id);
    }
}
