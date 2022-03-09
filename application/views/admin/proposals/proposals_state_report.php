<?php

if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:15px';
    $h_s = config_item('proposal_state');
    ?>
    <div id="state_report" style="display: <?= $h_s ?>">

        <?php
        $currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
        ?>
        <div class="row">
            <!-- END widget-->
            <?php
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
            $all_proposals = $this->proposal_model->get_permission('tbl_proposals');
            if (!empty($all_proposals)) {
                $all_proposals = array_reverse($all_proposals);
                foreach ($all_proposals as $v_invoice) {
                    if (strtotime($v_invoice->due_date) < strtotime(date('Y-m-d')) && $v_invoice->status == ('pending') || strtotime($v_invoice->due_date) < strtotime(date('Y-m-d')) && $v_invoice->status == ('draft')) {
                        $total_expired += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
                        $expired += count($v_invoice->proposals_id);;
                    }
                    if ($v_invoice->status == ('draft')) {
                        $draft += count($v_invoice->proposals_id);
                        $total_draft += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
                    }
                    if ($v_invoice->status == ('sent')) {
                        $sent += count($v_invoice->proposals_id);
                        $total_sent += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
                    }
                    if ($v_invoice->status == ('declined')) {
                        $declined += count($v_invoice->proposals_id);
                        $total_declined += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
                    }
                    if ($v_invoice->status == ('accepted')) {
                        $accepted += count($v_invoice->proposals_id);
                        $total_accepted += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
                    }
                    if ($v_invoice->status == ('pending')) {
                        $pending += count($v_invoice->proposals_id);
                    }
                    if ($v_invoice->status == ('cancelled')) {
                        $cancelled += count($v_invoice->proposals_id);
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
        <?php if (!empty($all_proposals)) { ?>
            <div class="row">
                <div class="col-lg-5ths">
                    <!-- START widget-->
                    <div class="panel widget">
                        <div class="pl-sm pr-sm pb-sm">
                            <strong><a class="text-purple filter_by_type" style="font-size: 15px"
                                       search-type="<?= lang('draft') ?>" id="draft"
                                       href="#"><?= lang('draft') ?></a>
                                <small class="pull-right " style="padding-top: 2px"> <?= $draft ?>
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                                     data-original-title="<?= ($draft / count($all_proposals)) * 100 ?>%"
                                     style="width: <?= ($draft / count($all_proposals)) * 100 ?>%"></div>
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
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-aqua " data-toggle="tooltip"
                                     data-original-title="<?= ($sent / count($all_proposals)) * 100 ?>%"
                                     style="width: <?= ($sent / count($all_proposals)) * 100 ?>%"></div>
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
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                                     data-original-title="<?= ($expired / count($all_proposals)) * 100 ?>%"
                                     style="width: <?= ($expired / count($all_proposals)) * 100 ?>%"></div>
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
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                                     data-original-title="<?= ($declined / count($all_proposals)) * 100 ?>%"
                                     style="width: <?= ($declined / count($all_proposals)) * 100 ?>%"></div>
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
                                    / <?= count($all_proposals) ?></small>
                            </strong>
                            <div class="progress progress-striped progress-xs mb-sm">
                                <div class="progress-bar progress-bar-warning " data-toggle="tooltip"
                                     data-original-title="<?= ($accepted / count($all_proposals)) * 100 ?>%"
                                     style="width: <?= ($accepted / count($all_proposals)) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END widget-->
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>