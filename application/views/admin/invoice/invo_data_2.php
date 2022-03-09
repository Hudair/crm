<?php
$no_of_inv = $this->admin_model->get_permission('tbl_invoices', null, 'count(tbl_invoices.invoices_id) as num_of_inv')[0]->num_of_inv;

$not_paid = $this->admin_model->get_permission('tbl_invoices', array('tbl_invoices.status = ' => 'unpaid'), 'count(tbl_invoices.invoices_id) as num_of_inv')[0]->num_of_inv;

$fully_paid = $this->admin_model->get_permission('tbl_invoices', array('tbl_invoices.status = ' => 'paid'), 'count(tbl_invoices.invoices_id) as num_of_inv')[0]->num_of_inv;

$draft = $this->admin_model->get_permission('tbl_invoices', array('tbl_invoices.status = ' => 'Draft'), 'count(tbl_invoices.invoices_id) as num_of_inv')[0]->num_of_inv;

$partially_paid = $this->admin_model->get_permission('tbl_invoices', array('tbl_invoices.status = ' => 'partially_paid'), 'count(tbl_invoices.invoices_id) as num_of_inv')[0]->num_of_inv;

$overdue = $this->admin_model->get_permission('tbl_invoices', array('tbl_invoices.due_date < ' => strtotime(date("Y-m-d")), 'tbl_invoices.status != ' => 'Paid'), 'count(tbl_invoices.invoices_id) as num_of_inv')[0]->num_of_inv;


$mdate = date('Y-m-d');
$last_7_days = date('Y-m-d', strtotime('today - 7 days'));
$all_goal_tracking = $this->invoice_model->get_permission('tbl_goal_tracking', array('goal_type_id' => 5));

$all_goal = 0;
$bank_goal = 0;
$complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->invoice_model->get_progress($v_goal_track, true);
        $all_goal += $v_goal_track->achievement;
        $complete_achivement += $goal_achieve['achievement'];
    }
}
// 30 days before

for ($iDay = 7; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('date_saved >=' => $date . " 00:00:00", 'date_saved <=' => $date . " 23:59:59");
    $invoice_result[$date] = count($this->db->where($where)->get('tbl_invoices')->result());
}

$terget_achievement = $this->db->where(array('goal_type_id' => 5, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate))->get('tbl_goal_tracking')->result();

$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}
$tolal_goal = $all_goal + $bank_goal;
$currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

//echo '<pre>'; print_r($h_s); exit();
//if ($this->session->userdata('user_type') == 1 && trim($h_s) == 'block') {
if ($this->session->userdata('user_type') == 1) {
    $h_s = config_item('invoice_state');
    ?>
    <div id="state_report" style="display: <?php //echo $h_s; ?>">
        <div class="col-sm-12 bg-white p0 report_menu" style="margin-bottom:15px">
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

        $currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
        ?>
        <div class="row">
            <div class="col-lg-3">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                        <h3 class="mt0 mb0" id="client_outstanding"></h3>
                        <p class="text-warning m0"><?= lang('total') . ' ' . lang('outstanding') . ' ' . lang('invoice') ?></p>
                    </div>
                </div>
            </div>
            <!-- END widget-->

            <div class="col-lg-3">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                        <h3 class="mt0 mb0 " id="total_invo_amount"></h3>
                        <p class="text-primary m0"><?= lang('total') . ' ' . lang('invoice_amount') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-3">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                        <h3 class="mt0 mb0" id="past_overdue"></h3>
                        <p class="text-danger m0"><?= lang('past') . ' ' . lang('overdue') . ' ' . lang('invoice') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-3">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                        <h3 class="mt0 mb0 " id="paid_invo_amount"></h3>
                        <p class="text-success m0"><?= lang('paid') . ' ' . lang('invoice') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
        </div>


        <?php if (!empty($no_of_inv)) { ?>
            <div class="row">

                <div class="col-lg-5ths pl-lg">
                    <!-- START widget-->
                    <div class="panel widget">
                        <div class="pl-sm pr-sm pb-sm">
                            <strong><a style="font-size: 15px" class="filter_by_type"
                                       search-type="<?= lang('not_paid') ?>" id="not_paid"
                                       href="#"><?= lang('unpaid') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $not_paid ?>
                                    / <?= $no_of_inv ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-danger " data-toggle="tooltip"
                                     data-original-title="<?= ($not_paid / $no_of_inv) * 100 ?>%"
                                     style="width: <?= ($not_paid / $no_of_inv) * 100 ?>%"></div>
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
                                    / <?= $no_of_inv ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-success " data-toggle="tooltip"
                                     data-original-title="<?= ($fully_paid / $no_of_inv) * 100 ?>%"
                                     style="width: <?= ($fully_paid / $no_of_inv) * 100 ?>%"></div>
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
                                    / <?= $no_of_inv ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                                     data-original-title="<?= ($partially_paid / $no_of_inv) * 100 ?>%"
                                     style="width: <?= ($partially_paid / $no_of_inv) * 100 ?>%"></div>
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
                                    / <?= $no_of_inv ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-warning " data-toggle="tooltip"
                                     data-original-title="<?= ($overdue / $no_of_inv) * 100 ?>%"
                                     style="width: <?= ($overdue / $no_of_inv) * 100 ?>%"></div>
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
                                    / <?= $no_of_inv ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-aqua " data-toggle="tooltip"
                                     data-original-title="<?= ($draft / $no_of_inv) * 100 ?>%"
                                     style="width: <?= ($draft / $no_of_inv) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END widget-->
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<script type="text/javascript">
    $('.filter_by_type').on('click', function () {
        $('.filter_by_type').removeClass('active');
        $('#showed_result').html($(this).attr('search-type'));
        $(this).addClass('active');
        var filter_by = $(this).attr('id');
        if (filter_by) {
            filter_by = filter_by;
        } else {
            filter_by = '';
        }
        table_url(base_url + "admin/invoice/invoiceList/" + filter_by);
    });
</script>