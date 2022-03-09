<?php
if ($this->input->post('goal_month', TRUE)) { // if input year
    $data['goal_month'] = $this->input->post('goal_month', TRUE);
} else { // else current year
    $data['goal_month'] = date('Y-m'); // get current year
}
$goal_report = $this->admin_model->get_goal_report($data['goal_month']);
//echo '<pre>'; print_r($goal_report); exit();
/*
$end_date = date('Y-m-d');

$start_date = date('Y-m-d', strtotime("$end_date - 1 year"));

//echo $start_date; exit;


$goal_report_2 = $this->admin_model->get_goal_report_by_month_2($start_date, $end_date);

echo '<pre>'; print_r($goal_report_2); exit();*/

?>


<?php
$mdate = date('Y-m-d');
$last_7_days = date('Y-m-d', strtotime('today - 7 days'));
$all_goal_tracking = $this->estimates_model->get_permission('tbl_goal_tracking');

$all_goal = 0;
$bank_goal = 0;
$complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->estimates_model->get_progress($v_goal_track, true);
        if ($v_goal_track->goal_type_id == 6) {
            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->estimates_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->estimates_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                        }
                    }
                }
            }


            $all_goal += $v_goal_track->achievement;
            $complete_achivement += $goal_achieve['achievement'];
        }
    }
}
// 30 days before

for ($iDay = 7; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('date_saved >=' => $date . " 00:00:00", 'date_saved <=' => $date . " 23:59:59");
    $invoice_result[$date] = count($this->db->where($where)->get('tbl_estimates')->result());
}

$terget_achievement = $this->db->where(array('goal_type_id' => 6, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate))->get('tbl_goal_tracking')->result();

$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}
$tolal_goal = $all_goal + $bank_goal;
$curency = $this->estimates_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

//$tolal_goal = $tolal_goal;  //Target Achievement

//$total_terget            //Last Weeks Created

//Completed Achievemen

//Pending Achievement

if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:15px';
    $h_s = config_item('estimate_state');
    ?>
    <div id="state_report" style="display: <?= $h_s ?>">
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
                            <?= ($pending_goal) ?></p>
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
        ?>
        <div class="row">
            <!-- END widget-->
            <?php
            $currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
            $expired = 0;
            $draft = 0;
            $total_draft = 0;
            $total_sent = 0;
            $total_declined = 0;
            $total_accepted = 0;
            $total_expired = 0;
            $sent = 0;
            $declined = 0;
            $accepted = 0;
            $pending = 0;
            $cancelled = 0;
            $all_estimates = $this->estimates_model->get_permission('tbl_estimates');
            if (!empty($all_estimates)) {
                $all_estimates = array_reverse($all_estimates);
                foreach ($all_estimates as $v_invoice) {
                    if (strtotime($v_invoice->due_date) < strtotime(date('Y-m-d')) && $v_invoice->status == ('pending') || strtotime($v_invoice->due_date) < strtotime(date('Y-m-d')) && $v_invoice->status == ('draft')) {
                        $total_expired += $this->estimates_model->estimate_calculation('total', $v_invoice->estimates_id);
                        $expired += count($v_invoice->estimates_id);;
                    }
                    if ($v_invoice->status == ('draft')) {
                        $draft += count($v_invoice->estimates_id);
                        $total_draft += $this->estimates_model->estimate_calculation('total', $v_invoice->estimates_id);
                    }
                    if ($v_invoice->status == ('sent')) {
                        $sent += count($v_invoice->estimates_id);
                        $total_sent += $this->estimates_model->estimate_calculation('total', $v_invoice->estimates_id);
                    }
                    if ($v_invoice->status == ('declined')) {
                        $declined += count($v_invoice->estimates_id);
                        $total_declined += $this->estimates_model->estimate_calculation('total', $v_invoice->estimates_id);
                    }
                    if ($v_invoice->status == ('accepted')) {
                        $accepted += count($v_invoice->estimates_id);
                        $total_accepted += $this->estimates_model->estimate_calculation('total', $v_invoice->estimates_id);
                    }
                    if ($v_invoice->status == ('pending')) {
                        $pending += count($v_invoice->estimates_id);
                    }
                    if ($v_invoice->status == ('cancelled')) {
                        $cancelled += count($v_invoice->estimates_id);
                    }
                }
            }
            ?>

            <div class="col-lg-5ths">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                        <h3 class="mt0 mb0 "><?= display_money($total_draft, $currency->symbol) ?></h3>
                        <p class="m0"><?= lang('draft') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-5ths">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                        <h3 class="mt0 mb0"><?= display_money($total_sent, $currency->symbol) ?></h3>
                        <p class="text-primary m0"><?= lang('sent') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-5ths">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                        <h3 class="mt0 mb0 "><?= display_money($total_expired, $currency->symbol) ?></h3>
                        <p class="text-danger m0"><?= lang('expired') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-5ths">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                        <h3 class="mt0 mb0 "><?= display_money($total_declined, $currency->symbol) ?></h3>
                        <p class="text-warning m0"><?= lang('declined') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
            <div class="col-lg-5ths">
                <!-- START widget-->
                <div class="panel widget">
                    <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                        <h3 class="mt0 mb0 "><?= display_money($total_accepted, $currency->symbol) ?></h3>
                        <p class="text-success m0"><?= lang('accepted') ?></p>
                    </div>
                </div>
                <!-- END widget-->
            </div>
        </div>
        <?php if (!empty($all_estimates)) { ?>
            <div class="row">
                <div class="col-lg-5ths">
                    <!-- START widget-->
                    <div class="panel widget">
                        <div class="pl-sm pr-sm pb-sm">
                            <strong><a class="text-purple filter_by_type" style="font-size: 15px"
                                       search-type="<?= lang('draft') ?>" id="draft"
                                       href="#"><?= lang('draft') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $draft ?>
                                    / <?= count($all_estimates) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                                     data-original-title="<?= ($draft / count($all_estimates)) * 100 ?>%"
                                     style="width: <?= ($draft / count($all_estimates)) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END widget-->
                </div>
                <div class="col-lg-5ths pr-lg">
                    <!-- START widget-->
                    <div class="panel widget">
                        <div class="pl-sm pr-sm pb-sm">
                            <strong><a class="text-primary filter_by_type" style="font-size: 15px"
                                       search-type="<?= lang('sent') ?>" id="sent"
                                       href="#"><?= lang('sent') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $sent ?>
                                    / <?= count($all_estimates) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-aqua " data-toggle="tooltip"
                                     data-original-title="<?= ($sent / count($all_estimates)) * 100 ?>%"
                                     style="width: <?= ($sent / count($all_estimates)) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END widget-->
                </div>
                <div class="col-lg-5ths">
                    <!-- START widget-->
                    <div class="panel widget">
                        <div class="pl-sm pr-sm pb-sm">
                            <strong><a class="text-danger filter_by_type" style="font-size: 15px"
                                       search-type="<?= lang('expired') ?>" id="expired"
                                       href="#"><?= lang('expired') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $expired ?>
                                    / <?= count($all_estimates) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                                     data-original-title="<?= ($expired / count($all_estimates)) * 100 ?>%"
                                     style="width: <?= ($expired / count($all_estimates)) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END widget-->
                </div>
                <div class="col-lg-5ths">
                    <!-- START widget-->
                    <div class="panel widget">
                        <div class="pl-sm pr-sm pb-sm">
                            <strong><a class="text-warning filter_by_type" style="font-size: 15px"
                                       search-type="<?= lang('declined') ?>" id="declined"
                                       href="#"><?= lang('declined') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $declined ?>
                                    / <?= count($all_estimates) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                                     data-original-title="<?= ($declined / count($all_estimates)) * 100 ?>%"
                                     style="width: <?= ($declined / count($all_estimates)) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END widget-->
                </div>
                <div class="col-lg-5ths">
                    <!-- START widget-->
                    <div class="panel widget">
                        <div class="pl-sm pr-sm pb-sm">
                            <strong><a class="text-success filter_by_type" style="font-size: 15px"
                                       search-type="<?= lang('accepted') ?>" id="accepted"
                                       href="#"><?= lang('accepted') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $accepted ?>
                                    / <?= count($all_estimates) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-warning " data-toggle="tooltip"
                                     data-original-title="<?= ($accepted / count($all_estimates)) * 100 ?>%"
                                     style="width: <?= ($accepted / count($all_estimates)) * 100 ?>%"></div>
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
        table_url(base_url + "admin/estimates/estimatesList/" + filter_by);
    });
</script>
