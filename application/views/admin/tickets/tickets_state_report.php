<?php
$answered = 0;
$closed = 0;
$open = 0;
$in_progress = 0;

$progress_tickets_info = $this->tickets_model->get_permission('tbl_tickets');

// 30 days before

for ($iDay = 30; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('created >=' => $date . " 00:00:00", 'created <=' => $date . " 23:59:59");

    $tickets_result[$date] = count($this->db->where($where)->get('tbl_tickets')->result());
}

if (!empty($progress_tickets_info)):foreach ($progress_tickets_info as $v_tickets):
    if ($v_tickets->status == 'answered') {
        $answered += count($v_tickets->status);
    }
    if ($v_tickets->status == 'closed') {
        $closed += count($v_tickets->status);
    }
    if ($v_tickets->status == 'open') {
        $open += count($v_tickets->status);
    }
    if ($v_tickets->status == 'in_progress') {
        $in_progress += count($v_tickets->status);
    }
endforeach;
endif;
if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:15px';
    ?>
    <div class="col-sm-12 bg-white p0 report_menu" style="<?= $margin ?>">
        <div class="col-md-4">
            <div class="row row-table pv-lg">
                <div class="col-xs-6">
                    <p class="m0 lead"><?= $answered ?></p>
                    <p class="m0">
                        <small><a class="filter_by_type" id="answered"
                                  href="#"> <?= lang('answered') . ' ' . lang('tickets') ?></a>
                        </small>
                    </p>
                </div>
                <div class="col-xs-6">
                    <p class="m0 lead"><?= $in_progress ?></p>
                    <p class="m0">
                        <small><a class="filter_by_type" id="in_progress"
                                  href="#"><?= lang('in_progress') . ' ' . lang('tickets') ?></a>
                        </small>
                    </p>
                </div>


            </div>
        </div>
        <div class="col-md-3">
            <div class="row row-table pv-lg">
                <div class="col-xs-6">
                    <p class="m0 lead"><?= $open ?></p>
                    <p class="m0">
                        <small><a class="filter_by_type" id="open"
                                  href="#"><?= lang('open') . ' ' . lang('tickets') ?></a>
                        </small>
                    </p>
                </div>
                <div class="col-xs-6">
                    <p class="m0 lead"><?= $closed ?></p>
                    <p class="m0">
                        <small><a class="filter_by_type" id="closed"
                                  href="#"><?= lang('close') . ' ' . lang('tickets') ?></a>
                        </small>
                    </p>
                </div>

            </div>
        </div>
        <div class="col-md-5">
            <div class="row row-table text-center pt">
                <div data-sparkline="" data-bar-color="#23b7e5" data-height="60" data-bar-width="7"
                     data-bar-spacing="6" data-chart-range-min="0"
                     values="<?php
                     if (!empty($tickets_result)) {
                         foreach ($tickets_result as $v_tickets_result) {
                             echo $v_tickets_result . ',';
                         }
                     }
                     ?>">
                </div>

                <span class="easypie-text "><strong><?= lang('last_30_days') ?></strong></span>

            </div>
        </div>
    </div>
<?php } ?>
<script type="text/javascript">

    $('.filter_by_type').on('click', function () {
        var filter_by = $(this).attr('id');
        if (filter_by) {
            filter_by = filter_by;
        } else {
            filter_by = '';
        }
        table_url(base_url + "admin/tickets/ticketsList/" + filter_by);
    });
</script>
