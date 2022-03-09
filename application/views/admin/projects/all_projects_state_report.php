<?php
$all_customer_group = $this->db->where('type', 'projects')->order_by('customer_group_id', 'DESC')->get('tbl_customer_group')->result();
$mdate = date('Y-m-d');
$last_7_days = date('Y-m-d', strtotime('today - 7 days'));
$all_goal_tracking = $this->items_model->get_permission('tbl_goal_tracking');
$all_goal = 0;
$bank_goal = 0;
$complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->items_model->get_progress($v_goal_track, true);

        if ($v_goal_track->goal_type_id == 12) {

            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->items_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->items_model->send_goal_mail('goal_not_achieve', $v_goal_track);
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
    $where = array('created_time >=' => $date . " 00:00:00", 'created_time <=' => $date . " 23:59:59", 'project_status' => 'completed');
    $invoice_result[$date] = count($this->db->where($where)->get('tbl_project')->result());
}

$terget_achievement = $this->db->where(array('goal_type_id' => 12, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate))->get('tbl_goal_tracking')->result();

$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}
$tolal_goal = $all_goal + $bank_goal;
$curency = $this->items_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
if (!empty(admin())) {
    $margin = 'margin-bottom:15px';
    ?>
    <div class="col-sm-12 bg-white p0 report_menu" style="<?= $margin ?>">
        <div class="col-md-4">
            <div class="row row-table pv-lg">
                <div class="col-xs-6">
                    <p class="m0 lead"><?= ($tolal_goal) ?></p>
                    <p class="m0">
                        <small data-toggle="tooltip" data-placement="top"
                               title="<?= lang('achievement') ?>"><?= lang('achievement') ?></small>
                    </p>
                </div>
                <div class="col-xs-6">
                    <p class="m0 lead" data-toggle="tooltip" data-placement="top"
                       title="<?= lang('completed') . ' ' . lang('achievements') ?>"><?= ($complete_achivement) ?></p>
                    <p class="m0" data-toggle="tooltip" data-placement="top"
                       title="<?= lang('completed') . ' ' . lang('achievements') ?>">
                        <small><?= lang('completed') ?></small>
                    </p>
                </div>


            </div>
        </div>
        <div class="col-md-4">
            <div class="row row-table ">
                <div class="col-xs-6">
                    <p class="m0 lead" data-toggle="tooltip" data-placement="top"
                       title="<?= lang('pending') . ' ' . lang('achievements') ?>">
                        <?php
                        if ($tolal_goal < $complete_achivement) {
                            $pending_goal = 0;
                        } else {
                            $pending_goal = $tolal_goal - $complete_achivement;
                        } ?>
                        <?= $pending_goal ?></p>
                    <p class="m0" data-toggle="tooltip" data-placement="top"
                       title="<?= lang('pending') . ' ' . lang('achievements') ?>">
                        <small><?= lang('pending') ?></small>
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
                <div class="col-xs-6 text-center pt" data-toggle="tooltip" data-placement="top"
                     title="<?= lang('done') . ' ' . lang('percentage') ?>">
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
                            <span class="easypie-text"><strong> <?= lang('done') ?></strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row row-table ">
                <div class="col-xs-6 ">
                    <p class="m0 lead"><?= ($total_terget) ?></p>
                    <p class="m0">
                        <small><?= lang('last_weeks') . ' ' . lang('created') ?></small>
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
    </div>
    <?php
    $complete = 0;
    $cancel = 0;
    $in_progress = 0;
    $started = 0;
    $on_hold = 0;
    $overdue = 0;
    $all_project = $this->items_model->get_permission('tbl_project');

    if (!empty($all_project)):foreach ($all_project as $v_project):
        $aprogress = $this->items_model->get_project_progress($v_project->project_id);
        if ($v_project->project_status == 'completed') {
            $complete += count($v_project->project_id);
        }
        if ($v_project->project_status == 'cancel') {
            $cancel += count($v_project->project_id);
        }
        if ($v_project->project_status == 'in_progress') {
            $in_progress += count($v_project->project_id);
        }
        if ($v_project->project_status == 'on_hold') {
            $on_hold += count($v_project->project_id);
        }
        if ($v_project->project_status == 'started') {
            $started += count($v_project->project_id);
        }
        if (strtotime(date('Y-m-d')) > strtotime($v_project->end_date) && $aprogress < 100) {
            $overdue += count($v_project->project_id);

        }
    endforeach;
    endif;
    if (!empty($all_project)) {
        $overdue_width = ($overdue / count($all_project)) * 100;
        $started_width = ($started / count($all_project)) * 100;
        $on_hold_width = ($on_hold / count($all_project)) * 100;
        $in_progress_width = ($in_progress / count($all_project)) * 100;
        $cancel_width = ($cancel / count($all_project)) * 100;
        $complete_width = ($complete / count($all_project)) * 100;
    } else {
        $overdue_width = 0;
        $started_width = 0;
        $on_hold_width = 0;
        $in_progress_width = 0;
        $cancel_width = 0;
        $complete_width = 0;
    }
    ?>
    <div class="row">
        <div class="col-lg-2 pl-lg">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="p_status" search-type="<?= ('overdue') ?>" id="overdue"
                               href="#"><?= lang('overdue') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $overdue ?>
                            / <?= count($all_project) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-danger " data-toggle="tooltip"
                             data-original-title="<?= $overdue_width ?>%"
                             style="width: <?= $overdue_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>

        <div class="col-lg-2">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="p_status" search-type="<?= ('started') ?>" id="started"
                               href="#"><?= lang('started') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $started ?>
                            / <?= count($all_project) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                             data-original-title="<?= $overdue_width ?>%"
                             style="width: <?= $overdue_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-2">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="p_status" search-type="<?= ('on_hold') ?>" id="on_hold"
                               href="#"><?= lang('on_hold') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $on_hold ?>
                            / <?= count($all_project) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-warning " data-toggle="tooltip"
                             data-original-title="<?= $on_hold_width ?>%"
                             style="width: <?= $on_hold_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-2">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="p_status" search-type="<?= ('in_progress') ?>"
                               id="in_progress"
                               href="#"><?= lang('in_progress') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $in_progress ?>
                            / <?= count($all_project) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-info " data-toggle="tooltip"
                             data-original-title="<?= $in_progress_width ?>%"
                             style="width: <?= $in_progress_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-2">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="p_status" search-type="<?= ('cancel') ?>" id="cancel"
                               href="#"><?= lang('cancel') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $cancel ?>
                            / <?= count($all_project) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-danger " data-toggle="tooltip"
                             data-original-title="<?= $cancel_width ?>%"
                             style="width: <?= $cancel_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-2 pr-lg">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a style="font-size: 15px" class="p_status" search-type="<?= ('complete') ?>" id="completed"
                               href="#"><?= lang('complete') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $complete ?>
                            / <?= count($all_project) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-success " data-toggle="tooltip"
                             data-original-title="<?= $complete_width ?>%"
                             style="width: <?= $complete_width ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
    </div>
<?php } ?>
<script type="text/javascript">
    $('.p_status').on('click', function () {
        var result = $(this).attr('id');
        table_url(base_url + "admin/projects/projectList/" + result);
    });
</script>
