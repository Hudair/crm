<div class="row">
    <!-- END widget-->
    <?php
    /*$open = 0;
    $total_open = 0;
    $closed = 0;
    $total_closed = 0;
    $refund = 0;
    $total_refund = 0;
    $void = 0;
    $total_void = 0;
    $all_credit_note = $this->credit_note_model->get_permission('tbl_credit_note');
    if (!empty($all_credit_note)) {
        foreach ($all_credit_note as $v_invoice) {
            if ($v_invoice->status == 'void') {
                $void += count($v_invoice->credit_note_id);
                $total_void += $this->credit_note_model->credit_note_calculation('total', $v_invoice->credit_note_id);
            }
            if ($v_invoice->status == ('open')) {
                $open += count($v_invoice->credit_note_id);
                $total_open += $this->credit_note_model->credit_note_calculation('total', $v_invoice->credit_note_id);
            }
            if ($v_invoice->status == 'closed') {
                $closed += count($v_invoice->credit_note_id);
                $total_closed += $this->credit_note_model->credit_note_calculation('total', $v_invoice->credit_note_id);
            }
            if ($v_invoice->status == ('refund')) {
                $refund += count($v_invoice->credit_note_id);
                $total_refund += $this->credit_note_model->credit_note_calculation('total', $v_invoice->credit_note_id);
            }
        }
    }*/

    $res_void = $this->db->query("SELECT COUNT(tbl_credit_note.credit_note_id) AS void,
                                        COALESCE((SUM(tbl_credit_note_items.total_cost) - SUM(tbl_credit_note.discount_total) +
                                        SUM(tbl_credit_note.tax) +  SUM(tbl_credit_note.adjustment)), 0) AS total_void
                                        FROM  tbl_credit_note
                                        LEFT JOIN tbl_credit_note_items   ON tbl_credit_note.credit_note_id = tbl_credit_note_items.credit_note_id
                                        WHERE tbl_credit_note.status ='void'")->row();

    $void = $res_void->void;
    $total_void = $res_void->total_void;

    $res_open = $this->db->query("SELECT COUNT(tbl_credit_note.credit_note_id) AS void,
                            COALESCE((SUM(tbl_credit_note_items.total_cost) - SUM(tbl_credit_note.discount_total) +
                            SUM(tbl_credit_note.tax) +  SUM(tbl_credit_note.adjustment)), 0) AS total_void
                            FROM  tbl_credit_note
                            LEFT JOIN tbl_credit_note_items   ON tbl_credit_note.credit_note_id = tbl_credit_note_items.credit_note_id
                            WHERE tbl_credit_note.status ='open'")->row();

    $open = $res_open->void;
    $total_open = $res_open->total_void;


    $res_closed = $this->db->query("SELECT COUNT(tbl_credit_note.credit_note_id) AS void,
                            COALESCE((SUM(tbl_credit_note_items.total_cost) - SUM(tbl_credit_note.discount_total) +
                            SUM(tbl_credit_note.tax) +  SUM(tbl_credit_note.adjustment)), 0) AS total_void
                            FROM  tbl_credit_note
                            LEFT JOIN tbl_credit_note_items   ON tbl_credit_note.credit_note_id = tbl_credit_note_items.credit_note_id
                            WHERE tbl_credit_note.status ='closed'")->row();

    $closed = $res_closed->void;
    $total_closed = $res_closed->total_void;

    $res_refund = $this->db->query("SELECT COUNT(tbl_credit_note.credit_note_id) AS void,
                            COALESCE((SUM(tbl_credit_note_items.total_cost) - SUM(tbl_credit_note.discount_total) +
                            SUM(tbl_credit_note.tax) +  SUM(tbl_credit_note.adjustment)), 0) AS total_void
                            FROM  tbl_credit_note
                            LEFT JOIN tbl_credit_note_items   ON tbl_credit_note.credit_note_id = tbl_credit_note_items.credit_note_id
                            WHERE tbl_credit_note.status ='refund'")->row();

    $refund = $res_refund->void;
    $total_refund = $res_refund->total_void;

    $all_credit_note = $this->credit_note_model->get_permission('tbl_credit_note', null, 'SELECT credit_note_id', TRUE);

    ?>
    <div class="col-lg-3">
        <!-- START widget-->
        <div class="panel widget">
            <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                <h3 class="mt0 mb0 "><?= display_money($total_open, default_currency()) ?></h3>
                <p class="text-danger m0"><?= lang('open') ?></p>
            </div>
        </div>
        <!-- END widget-->
    </div>
    <div class="col-lg-3">
        <!-- START widget-->
        <div class="panel widget">
            <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                <h3 class="mt0 mb0 "><?= display_money($total_closed, default_currency()) ?></h3>
                <p class="text-danger m0"><?= lang('closed') ?></p>
            </div>
        </div>
        <!-- END widget-->
    </div>
    <div class="col-lg-3">
        <!-- START widget-->
        <div class="panel widget">
            <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                <h3 class="mt0 mb0 "><?= display_money($total_refund, default_currency()) ?></h3>
                <p class="text-warning m0"><?= lang('refund') ?></p>
            </div>
        </div>
        <!-- END widget-->
    </div>
    <div class="col-lg-3">
        <!-- START widget-->
        <div class="panel widget">
            <div class="panel-body pl-sm pr-sm pt-sm pb0 text-center">
                <h3 class="mt0 mb0 "><?= display_money($total_void, default_currency()) ?></h3>
                <p class="text-success m0"><?= lang('void') ?></p>
            </div>
        </div>
        <!-- END widget-->
    </div>
</div>
<?php if (!empty($all_credit_note)) { ?>
    <div class="row">
        <div class="col-lg-3">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a class="text-purple filter_by_type" style="font-size: 15px"
                               search-type="<?= lang('open') ?>" id="open"
                               href="#"><?= lang('open') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $open ?>
                            / <?= count($all_credit_note) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                             data-original-title="<?= ($open / count($all_credit_note)) * 100 ?>%"
                             style="width: <?= ($open / count($all_credit_note)) * 100 ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-3">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a class="text-purple filter_by_type" style="font-size: 15px"
                               search-type="<?= lang('closed') ?>" id="closed"
                               href="#"><?= lang('closed') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $closed ?>
                            / <?= count($all_credit_note) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                             data-original-title="<?= ($closed / count($all_credit_note)) * 100 ?>%"
                             style="width: <?= ($closed / count($all_credit_note)) * 100 ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-3">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a class="text-warning filter_by_type" style="font-size: 15px"
                               search-type="<?= lang('refund') ?>" id="refund"
                               href="#"><?= lang('refund') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $refund ?>
                            / <?= count($all_credit_note) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-primary " data-toggle="tooltip"
                             data-original-title="<?= ($refund / count($all_credit_note)) * 100 ?>%"
                             style="width: <?= ($refund / count($all_credit_note)) * 100 ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
        <div class="col-lg-3">
            <!-- START widget-->
            <div class="panel widget">
                <div class="pl-sm pr-sm pb-sm">
                    <strong><a class="text-success filter_by_type" style="font-size: 15px"
                               search-type="<?= lang('void') ?>" id="void"
                               href="#"><?= lang('void') ?></a>
                        <small class="pull-right " style="padding-top: 2px"> <?= $void ?>
                            / <?= count($all_credit_note) ?></small>
                    </strong>
                    <div class="progress progress-striped progress-xs mb-sm">
                        <div class="progress-bar progress-bar-warning " data-toggle="tooltip"
                             data-original-title="<?= ($void / count($all_credit_note)) * 100 ?>%"
                             style="width: <?= ($void / count($all_credit_note)) * 100 ?>%"></div>
                    </div>
                </div>
            </div>
            <!-- END widget-->
        </div>
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
        table_url(base_url + "admin/credit_note/credit_noteList/" + filter_by);
    });
</script>