<?php
/*$client_outstanding = 0;
$all_valid_invo_total = 0;
$past_overdue = 0;
$all_paid_amount = 0;
$not_paid = 0;
$fully_paid = 0;
$draft = 0;
$partially_paid = 0;
$overdue = 0;

$all_invo_res = $this->db->query("SELECT
COALESCE(SUM(tbl_items.total_cost), 0) AS all_invoices_cost,
COALESCE(SUM(tbl_invoices.discount_total), 0) AS all_invoices_discount,
COALESCE(SUM(tbl_items.item_tax_total), 0) AS all_invoices_tax,
COALESCE(SUM(tbl_invoices.adjustment), 0) AS all_invoices_adjustment
FROM tbl_invoices
LEFT JOIN tbl_items ON tbl_invoices.invoices_id = tbl_items.invoices_id
WHERE tbl_invoices.status != 'cancelled' AND tbl_invoices.status != 'draft'")->row();

if(!empty($all_invo_res) && $all_invo_res->all_invoices_cost > 0) {
$all_valid_invo_total = $all_invo_res->all_invoices_cost - $all_invo_res->all_invoices_discount + $all_invo_res->all_invoices_tax + $all_invo_res->all_invoices_adjustment;
}

$all_paid_amount_res = $this->db->query("SELECT COALESCE(SUM(tbl_payments.amount), 0) as all_paid_amount
FROM tbl_invoices
LEFT JOIN tbl_payments ON tbl_invoices.invoices_id = tbl_payments.invoices_id
WHERE tbl_invoices.status != 'cancelled' AND tbl_invoices.status != 'draft'")->row()->all_paid_amount;
if(!empty($all_paid_amount_res)) { $all_paid_amount =    $all_paid_amount_res;}


$client_outstanding = $all_valid_invo_total - $all_paid_amount;


$due_date_expired_invo_res = $this->db->query("SELECT
COALESCE(SUM(tbl_items.total_cost), 0) AS all_invoices_cost,
COALESCE(SUM(tbl_invoices.discount_total), 0) AS all_invoices_discount,
COALESCE(SUM(tbl_items.item_tax_total), 0) AS all_invoices_tax,
COALESCE(SUM(tbl_invoices.adjustment), 0) AS all_invoices_adjustment,
COALESCE(SUM(tbl_payments.amount), 0) AS unpaid_invoices_payment
FROM tbl_invoices
LEFT JOIN tbl_items ON tbl_invoices.invoices_id = tbl_items.invoices_id
LEFT JOIN tbl_payments ON tbl_invoices.invoices_id = tbl_payments.invoices_id
WHERE tbl_invoices.status != 'cancelled' AND tbl_invoices.status != 'draft' AND due_date < CURDATE() AND status != 'paid'")->row();

if(!empty($due_date_expired_invo_res)) {
    $past_overdue = $due_date_expired_invo_res->all_invoices_cost - $due_date_expired_invo_res->all_invoices_discount
        + $due_date_expired_invo_res->all_invoices_tax + $due_date_expired_invo_res->all_invoices_adjustment
        - $due_date_expired_invo_res->unpaid_invoices_payment;
}*/

?>


    <div class="col-sm-12 bg-white p0 report_menu" style="<?= $margin ?>">
        <div class="col-md-4">
            <div class="row row-table pv-lg">
                <div class="col-xs-6">
                    <p class="m0 lead"><?= ($tolal_goal) ?></p>
                    <p class="m0">
                        <small><?= lang('achievement') ?></small>
                    </p>
                </div>
                <div class="col-xs-6 ">
                    <p class="m0 lead"><?= ($total_terget) ?></p>
                    <p class="m0">
                        <small><?= lang('last_weeks') . ' ' . lang('created') ?></small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row row-table ">
                <div class="col-xs-6">
                    <p class="m0 lead"><?= ($complete_achivement) ?></p>
                    <p class="m0">
                        <small><?= lang('completed') . ' ' . lang('achievements') ?></small>
                    </p>
                </div>
                <div class="col-xs-6 pt">
                    <div data-sparkline="" data-bar-color="#23b7e5" data-height="60" data-bar-width="8"
                         data-bar-spacing="6" data-chart-range-min="0" values="<?php
                    if (!empty($invoice_result)) {
                        foreach ($invoice_result as $v_invoice_result) {
                            echo $v_invoice_result . ',';
                        }
                    }
                    ?>">
                    </div>
                    <p class="m0">
                        <small>
                            <?php
                            if (!empty($invoice_result)) {
                                foreach ($invoice_result as $date => $v_invoice_result) {
                                    echo date('d', strtotime($date)) . ' ';
                                }
                            }
                            ?>
                        </small>
                    </p>

                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="row row-table ">
                <div class="col-xs-6">
                    <p class="m0 lead">
                        <?php
                        if ($tolal_goal < $complete_achivement) {
                            $pending_goal = 0;
                        } else {
                            $pending_goal = $tolal_goal - $complete_achivement;
                        } ?>
                        <?= $pending_goal ?></p>
                    <p class="m0">
                        <small><?= lang('pending') . ' ' . lang('achievements') ?></small>
                    </p>
                </div>
                <?php
                if (!empty($tolal_goal)) {
                    if ($tolal_goal <= $complete_achivement) {
                        $total_progress = 100;
                    } else {
                        $progress = ($complete_achivement / $tolal_goal) * 100;
                        $total_progress = round($progress);
                    }
                } else {
                    $total_progress = 0;
                }
                ?>
                <div class="col-xs-6 text-center pt">
                    <div class="inline ">
                        <div class="easypiechart text-success"
                             data-percent="<?= $total_progress ?>"
                             data-line-width="5" data-track-Color="#f0f0f0"
                             data-bar-color="#<?php
                             if ($total_progress == 100) {
                                 echo '8ec165';
                             } elseif ($total_progress >= 40 && $total_progress <= 50) {
                                 echo '5d9cec';
                             } elseif ($total_progress >= 51 && $total_progress <= 99) {
                                 echo '7266ba';
                             } else {
                                 echo 'fb6b5b';
                             }
                             ?>" data-rotate="270" data-scale-Color="false"
                             data-size="50"
                             data-animate="2000">
<span class="small "><?= $total_progress ?>
    %</span>
                            <span class="easypie-text"><strong><?= lang('done') ?></strong></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php
    //$client_outstanding = $this->invoice_model->all_outstanding();
    $currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
    ?>
    <div class="row">
        <div class="col-lg-3">
            <!-- START widget-->
            <div class="panel widget">
                <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                    <h3 class="mt0 mb0" id="client_outstanding"><?php
                        if ($client_outstanding > 0) {
                        echo display_money($client_outstanding, $currency->symbol);
                        } else {
                        echo display_money(0, $currency->symbol);
                        }
                        ?></h3>
                    <p class="text-warning m0"><?= lang('total') . ' ' . lang('outstanding') . ' ' . lang('invoice') ?></p>
                </div>
            </div>
        </div>
        <!-- END widget-->
        <?php


        //echo $all_invoices ; exit;
        //
        //if (!empty($all_invoices)) {
        //
        //    //echo 'salam'; exit;
        //
        //$all_invoices = array_reverse($all_invoices);
        //foreach ($all_invoices as $v_invoice) {
        //$payment_status = $this->invoice_model->get_payment_status($v_invoice->invoices_id);
        //if (strtotime($v_invoice->due_date) < strtotime(date('Y-m-d')) && $payment_status != lang('fully_paid')) {
        //$past_overdue += $this->invoice_model->calculate_to('invoice_due', $v_invoice->invoices_id);
        //}
        //$all_paid_amount += $this->invoice_model->calculate_to('paid_amount', $v_invoice->invoices_id);
        //
        //if ($this->invoice_model->get_payment_status($v_invoice->invoices_id) == lang('not_paid')) {
        //$not_paid += count($v_invoice->invoices_id);
        //}
        //if ($this->invoice_model->get_payment_status($v_invoice->invoices_id) == lang('fully_paid')) {
        //$fully_paid += count($v_invoice->invoices_id);
        //}
        //if ($this->invoice_model->get_payment_status($v_invoice->invoices_id) == lang('draft')) {
        //$draft += count($v_invoice->invoices_id);
        //}
        //if ($this->invoice_model->get_payment_status($v_invoice->invoices_id) == lang('partially_paid')) {
        //$partially_paid += count($v_invoice->invoices_id);
        //}
        //if (strtotime($v_invoice->due_date) < strtotime(date('Y-m-d')) && $payment_status != lang('fully_paid')) {
        //$overdue += count($v_invoice->invoices_id);
        //}
        //}
        //}

        ?>
        <div class="col-lg-3">
            <!-- START widget-->
            <div class="panel widget">
                <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                    <h3 class="mt0 mb0 " id="total_invo_amount"><?php   echo display_money($all_valid_invo_total, $currency->symbol); // echo display_money($all_paid_amount + $client_outstanding, $currency->symbol) ?></h3>
                    <p class="text-primary m0"><?= lang('total') . ' ' . lang('invoice_amount') ?></p>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-3">
            <!-- START widget-->
            <div class="panel widget">
                <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                    <h3 class="mt0 mb0"><?= display_money($past_overdue, $currency->symbol) ?></h3>
                    <p class="text-danger m0"><?= lang('past') . ' ' . lang('overdue') . ' ' . lang('invoice') ?></p>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-3">
            <!-- START widget-->
            <div class="panel widget">
                <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                    <h3 class="mt0 mb0 " id="paid_invo_amount"><?php echo  display_money($all_paid_amount, $currency->symbol) ?></h3>
                    <p class="text-success m0"><?= lang('paid') . ' ' . lang('invoice') ?></p>
                </div>
            </div>
            <!-- END widget-->
        </div>
    </div>
    <?php if (!empty($all_invoices)) { ?>
        <div class="row">

            <div class="col-lg-5ths pl-lg">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="pl-sm pr-sm pb-sm">
                        <strong><a style="font-size: 15px" class="filter_by_type"
                                   search-type="<?= lang('not_paid') ?>" id="not_paid"
                                   href="#"><?= lang('unpaid') ?></a>
                            <small class="pull-right " style="padding-top: 2px"> <?= $not_paid ?>
                                / <?= count($all_invoices) ?></small>
                        </strong>
                        <div class="progress progress-striped progress-xs mb-sm">
                            <div class="progress-bar progress-bar-danger " data-toggle="tooltip"
                                 data-original-title="<?= ($not_paid / count($all_invoices)) * 100 ?>%"
                                 style="width: <?= ($not_paid / count($all_invoices)) * 100 ?>%"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>

            <div class="col-lg-5ths">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="pl-sm pr-sm pb-sm">
                        <strong><a style="font-size: 15px" class="filter_by_type"
                                   search-type="<?= lang('paid') ?>"
                                   id="paid"
                                   href="#"><?= lang('paid') ?></a>
                            <small class="pull-right " style="padding-top: 2px"> <?= $fully_paid ?>
                                / <?= count($all_invoices) ?></small>
                        </strong>
                        <div class="progress progress-striped progress-xs mb-sm">
                            <div class="progress-bar progress-bar-success " data-toggle="tooltip"
                                 data-original-title="<?= ($fully_paid / count($all_invoices)) * 100 ?>%"
                                 style="width: <?= ($fully_paid / count($all_invoices)) * 100 ?>%"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-5ths">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="pl-sm pr-sm pb-sm">
                        <strong><a style="font-size: 15px" class="filter_by_type"
                                   search-type="<?= lang('partially_paid') ?>" id="partially_paid"
                                   href="#"><?= lang('partially_paid') ?></a>
                            <small class="pull-right " style="padding-top: 2px"> <?= $partially_paid ?>
                                / <?= count($all_invoices) ?></small>
                        </strong>
                        <div class="progress progress-striped progress-xs mb-sm">
                            <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                                 data-original-title="<?= ($partially_paid / count($all_invoices)) * 100 ?>%"
                                 style="width: <?= ($partially_paid / count($all_invoices)) * 100 ?>%"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-5ths">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="pl-sm pr-sm pb-sm">
                        <strong><a style="font-size: 15px" class="filter_by_type"
                                   search-type="<?= lang('overdue') ?>" id="overdue"
                                   href="#"><?= lang('overdue') ?></a>
                            <small class="pull-right " style="padding-top: 2px"> <?= $overdue ?>
                                / <?= count($all_invoices) ?></small>
                        </strong>
                        <div class="progress progress-striped progress-xs mb-sm">
                            <div class="progress-bar progress-bar-warning " data-toggle="tooltip"
                                 data-original-title="<?= ($overdue / count($all_invoices)) * 100 ?>%"
                                 style="width: <?= ($overdue / count($all_invoices)) * 100 ?>%"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-5ths pr-lg">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="pl-sm pr-sm pb-sm">
                        <strong><a style="font-size: 15px" class="filter_by_type"
                                   search-type="<?= lang('draft') ?>"
                                   id="draft"
                                   href="#"><?= lang('draft') ?></a>
                            <small class="pull-right " style="padding-top: 2px"> <?= $draft ?>
                                / <?= count($all_invoices) ?></small>
                        </strong>
                        <div class="progress progress-striped progress-xs mb-sm">
                            <div class="progress-bar progress-bar-aqua " data-toggle="tooltip"
                                 data-original-title="<?= ($draft / count($all_invoices)) * 100 ?>%"
                                 style="width: <?= ($draft / count($all_invoices)) * 100 ?>%"></div>
                        </div>
                    </div>
                </div>
                <!-- END widget-->
            </div>
        </div>
    <?php } ?>
