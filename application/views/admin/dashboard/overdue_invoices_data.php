<?php

if (!empty($all_invoices_info)) {
    $data = Array();
    foreach ($all_invoices_info as $v_invoices) {
        $sub_array = Array();
        $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
        if (strtotime($v_invoices->due_date) < strtotime(date('Y-m-d')) && $payment_status != lang('fully_paid')) {
            if ($payment_status == lang('fully_paid')) {
                $invoice_status = lang('fully_paid');
                $label = "success";
            } elseif ($v_invoices->emailed == 'Yes') {
                $invoice_status = lang('sent');
                $label = "info";
            } else {
                $invoice_status = lang('draft');
                $label = "default";
            }

            $reference_no = '<a class="text-info"
                       href="' . base_url() . 'admin/invoice/manage_invoice/invoice_details/' . $v_invoices->invoices_id . '">' . $v_invoices->reference_no . '</a>';

            $due_date = strftime(config_item('date_format'), strtotime($v_invoices->due_date));
            $due_date = '<span class="label label-danger ">' . lang('overdue') . '</span>';

            $client_name = client_name($v_invoices->client_id);
            $due_amount = display_money($this->invoice_model->calculate_to('invoice_due', $v_invoices->invoices_id), client_currency($v_invoices->client_id));
            $status = '<span class="label label-' . $label . '">' . $invoice_status . '</span>';
            if ($v_invoices->recurring == 'Yes') {
                $status .= '<span data-toggle="tooltip" data-placement="top" title="' . lang('recurring') . '" class="label label-primary">
                                        <i class="fa fa-retweet"></i></span>';
            }
        }

        $sub_array[] = $reference_no;
        $sub_array[] = $due_date;
        $sub_array[] = $client_name;
        $sub_array[] = $due_amount;
        $sub_array[] = $status;
        $data[] = $sub_array;
    }

    render_table($data, $whereClauses);
}

exit;
?>