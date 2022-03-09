<?php
if ($this->session->userdata('user_type') == 1) {
    $where = array('report' => 0, 'status' => 1);
} else {
    $where = array('report' => 0, 'status' => 1, 'for_staff' => 1);
}
$income_expense_order = get_row('tbl_dashboard', array('name' => 'income_expense') + $where);

if (!empty($income_expense)) { ?>
    <!-- DONUT CHART -->
    <div class="panel panel-custom menu" style="height: 437px;">
        <header class="panel-heading">
            <h3 class="panel-title"><?= lang('income_expense') ?></h3>
        </header>
        <div class="panel-body">
            <form role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/month" method="post" class="form-horizontal form-groups-bordered hidden-xs">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('month') ?>
                        <span class="required">*</span></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" id="income_vs_expense_month_id" name="month" value="<?php
                                                                                                    if (!empty($month)) {
                                                                                                        echo $month;
                                                                                                    }
                                                                                                    ?>" class="form-control monthyear"><span class="input-group-addon"><a href="#"><i class="fa fa-calendar"></i></a></span>
                        </div>
                    </div>
                    <button type="submit" id="income_vs_expense_btn" data-toggle="tooltip" data-placement="top" title="Search" class="btn btn-custom"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <div class="chart" id="sales-chart" style="height:35vh; width: 100%;"></div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <script type="text/javascript">
        $(document).ready(function() {
            initdatepicker();
        });
    </script>
    <?php if (!empty($income_expense)) { ?>

        <script type="text/javascript">
            $(function() {
                "use strict";
                var donut = new Morris.Donut({
                    element: 'sales-chart',
                    resize: true,
                    colors: ["#00a65a", "#f56954"],
                    data: [{
                            label: "<?= lang('Income') ?>",
                            value: <?php
                                    echo $total_vincome = $income_expense['total_income'];
                                    ?>
                        },
                        {
                            label: "<?= lang('Expense') ?>",
                            value: <?php

                                    echo $total_vexpense = $income_expense['total_expense'];
                                    ?>
                        },
                    ],
                    hideHover: 'auto'
                });
            });
        </script>
<?php }
} ?>