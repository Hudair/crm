<?php
if ($this->session->userdata('user_type') == 1) {
    $where = array('report' => 0, 'status' => 1);
} else {
    $where = array('report' => 0, 'status' => 1, 'for_staff' => 1);
}
$finance_overview_order = get_row('tbl_dashboard', array('name' => 'finance_overview') + $where);
?>
<div class="panel panel-custom menu">
    <header class="panel-heading">
        <h3 class="panel-title">
            <!-- <div class="col-sm-5"> -->
            <?= lang('finance') . ' ' . lang('overview') ?>
            <!-- </div> -->
            <div class="pull-right hidden-xs" style="margin-top: -8px;">
                <form role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/finance_overview" method="post" class="form-horizontal form-groups-bordered">
                    <div class="pull-left">
                        <input type="text" id="finance_overview_year" name="finance_overview" value="<?php
                                                                                                        if (!empty($finance_year)) {
                                                                                                            echo $finance_year;
                                                                                                        }
                                                                                                        ?>" class="form-control years">
                    </div>
                    <div class="pull-right">
                        <button type="submit" style="font-size: 15px" id="finance_overview_btn" data-toggle="tooltip" data-placement="top" title="Search" class="btn btn-custom"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
        </h3>
    </header>
    <div class="">
        <div class="col-sm-2 text-center">

        </div>
        <div class="col-sm-3 text-center">
            <span class="h4 font-bold m-t block"><?= display_money($total_annual['total_income'], default_currency()); ?></span>
            <small class="text-muted m-b block"><?= lang('total_annual') . ' ' . lang('income') ?></small>
        </div>
        <div class="col-sm-3 text-center">
            <span class="h4 font-bold m-t block"><?= display_money($total_annual['total_expense'], default_currency()); ?></span>
            <small class="text-muted m-b block"><?= lang('total_annual') . ' ' . lang('expense') ?></small>
        </div>
        <div class="col-sm-3 text-center ">
            <span class="h4 font-bold m-t block"><?= display_money($total_annual['total_profit'], default_currency()); ?></span>
            <small class="text-muted m-b block"><?= lang('total_annual') . ' ' . lang('profit') ?></small>
        </div>
        <div class="col-sm-2 text-center">

        </div>

        <table style="position:absolute;top:40px;right:16px;;font-size:smaller;color:#545454">
            <tbody>
                <tr>
                    <td class="legendColorBox">
                        <div style="border:1px solid #ccc;padding:1px">
                            <div style="width:4px;height:0;border:5px solid #4e96cdc7;overflow:hidden"></div>
                        </div>
                    </td>
                    <td class="legendLabel">Expense</td>
                </tr>
                <tr>
                    <td class="legendColorBox">
                        <div style="border:1px solid #ccc;padding:1px">
                            <div style="width:4px;height:0;border:5px solid #3d9e78;overflow:hidden"></div>
                        </div>
                    </td>
                    <td class="legendLabel">Income</td>
                </tr>
            </tbody>
        </table>
        <!--End select input year -->
        <!--Sales Chart Canvas -->
        <canvas id="finance_overview" style="height:40vh; width: 100%;"></canvas>
    </div><!-- ./box-body -->

</div>

<script type="text/javascript">
    $(document).ready(function() {
        initdatepicker();
    });
</script>
<?php if (!empty($finance_overview_order)) { ?>
    <script type="text/javascript">
        (function(window, document, $, undefined) {
            $(function() {
                if (typeof Chart === 'undefined') return;
                var lineData = {
                    labels: [
                        <?php
                        foreach ($finance_overview_by_year as $m_name => $v_finance_overview) :
                            $overview_month = date('F', strtotime($finance_year . '-' . $m_name));
                        ?> "<?php echo $overview_month; ?>",
                        <?php endforeach; ?>
                    ],
                    datasets: [{
                            label: 'Expense',
                            fillColor: '#71b9cb99',
                            strokeColor: '#4e96cd',
                            pointColor: '#4e96cdc7',
                            pointStrokeColor: '#fff',
                            pointHighlightFill: '#4283b9',
                            pointHighlightStroke: '#4e96cd',
                            data: [<?php
                                    foreach ($finance_overview_by_year as $v_finance_overview) :
                                    ?> "<?php echo $v_finance_overview['total_expense']; ?>",
                                <?php
                                    endforeach;
                                ?>
                            ]
                        },
                        {
                            label: 'Income',
                            fillColor: '#47c79263',
                            strokeColor: '#49b78c',
                            pointColor: '#3d9e78',
                            pointStrokeColor: '#fff',
                            pointHighlightFill: '#3cb986',
                            pointHighlightStroke: '#49b78c',
                            data: [<?php
                                    foreach ($finance_overview_by_year as $v_finance_overview) :
                                    ?> "<?php echo $v_finance_overview['total_income']; ?>",
                                <?php
                                    endforeach;
                                ?>
                            ]
                        }
                    ]
                };

                var lineOptions = {
                    scaleShowGridLines: true,
                    scaleGridLineColor: 'rgba(0,0,0,.05)',
                    scaleGridLineWidth: 2,
                    bezierCurve: true,
                    bezierCurveTension: 0.4,
                    pointDot: true,
                    pointDotRadius: 3,
                    pointDotStrokeWidth: 2,
                    pointHitDetectionRadius: 20,
                    datasetStroke: true,
                    datasetStrokeWidth: 2,
                    datasetFill: true,
                    responsive: true
                };
                var linectx = document.getElementById("finance_overview").getContext("2d");
                var lineChart = new Chart(linectx).Line(lineData, lineOptions);
            });
        })(window, document, window.jQuery);
    </script>
<?php } ?>