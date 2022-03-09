<?php
$mdate = date('Y-m-d');
$last_7_days = date('Y-m-d', strtotime('today - 7 days'));
$all_goal_tracking = $this->bugs_model->get_permission('tbl_goal_tracking');

$all_goal = 0;
$bank_goal = 0;
$complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->bugs_model->get_progress($v_goal_track, true);

        if ($v_goal_track->goal_type_id == 9) {

            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->bugs_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->bugs_model->send_goal_mail('goal_not_achieve', $v_goal_track);
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
    $where = array('update_time >=' => $date . " 00:00:00", 'update_time <=' => $date . " 23:59:59", 'bug_status' => 'resolved');

    $invoice_result[$date] = count($this->db->where($where)->get('tbl_bug')->result());
}

$terget_achievement = $this->db->where(array('goal_type_id' => 9, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate))->get('tbl_goal_tracking')->result();

$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}
$tolal_goal = $all_goal + $bank_goal;
$curency = $this->bugs_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:15px';
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
    $unconfirmed = 0;
    $confirmed = 0;
    $in_progress = 0;
    $resolved = 0;
    $verified = 0;
    $all_bugs_info = $this->bugs_model->get_permission('tbl_bug');
    if (!empty($all_bugs_info)):foreach ($all_bugs_info as $v_bugs):
        if ($v_bugs->bug_status == 'unconfirmed') {
            $unconfirmed += count($v_bugs->bug_id);
        }
        if ($v_bugs->bug_status == 'confirmed') {
            $confirmed += count($v_bugs->bug_id);
        }
        if ($v_bugs->bug_status == 'in_progress') {
            $in_progress += count($v_bugs->bug_id);
        }
        if ($v_bugs->bug_status == 'resolved') {
            $resolved += count($v_bugs->bug_id);
        }
        if ($v_bugs->bug_status == 'verified') {
            $verified += count($v_bugs->bug_id);
        }
    endforeach;
    endif;
    if (!empty($all_bugs_info)) {
        $unconfirmed_width = ($unconfirmed / count($all_bugs_info)) * 100;
        $confirmed_width = ($confirmed / count($all_bugs_info)) * 100;
        $in_progress_width = ($in_progress / count($all_bugs_info)) * 100;
        $resolved_width = ($resolved / count($all_bugs_info)) * 100;
        $verified_width = ($verified / count($all_bugs_info)) * 100;
    } else {
        $unconfirmed_width = 0;
        $confirmed_width = 0;
        $in_progress_width = 0;
        $resolved_width = 0;
        $verified_width = 0;
    }
    ?>
    <div class="row">
        <div class="col-lg-5ths pl-lg">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="b_status" search-type="<?= ('unconfirmed') ?>"
                               id="unconfirmed"
                               href="#"><?= lang('unconfirmed') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $unconfirmed ?>
                            / <?= count($all_bugs_info) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                             data-original-title="<?= $unconfirmed_width ?>%"
                             style="width: <?= $unconfirmed_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-5ths">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="b_status" search-type="<?= ('confirmed') ?>"
                               id="confirmed"
                               href="#"><?= lang('confirmed') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $confirmed ?>
                            / <?= count($all_bugs_info) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-info " data-toggle="tooltip"
                             data-original-title="<?= $confirmed_width ?>%"
                             style="width: <?= $confirmed_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-5ths">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="b_status" search-type="<?= ('in_progress') ?>"
                               id="in_progress"
                               href="#"><?= lang('in_progress') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $in_progress ?>
                            / <?= count($all_bugs_info) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-warning " data-toggle="tooltip"
                             data-original-title="<?= $in_progress_width ?>%"
                             style="width: <?= $in_progress_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>

        <div class="col-lg-5ths">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="b_status" search-type="<?= ('resolved') ?>"
                               id="resolved"
                               href="#"><?= lang('resolved') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $resolved ?>
                            / <?= count($all_bugs_info) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-danger " data-toggle="tooltip"
                             data-original-title="<?= $resolved_width ?>%"
                             style="width: <?= $resolved_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-5ths pr-lg">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="b_status" search-type="<?= ('verified') ?>" id="verified"
                               href="#"><?= lang('verified') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $verified ?>
                            / <?= count($all_bugs_info) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-success " data-toggle="tooltip"
                             data-original-title="<?= $verified_width ?>%"
                             style="width: <?= $verified_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
    </div>
<?php } ?>
<script type="text/javascript">
    $('.b_status').on('click', function () {
        var result = $(this).attr('id');
        table_url(base_url + "admin/bugs/bugsList/" + result);
    });
</script>
