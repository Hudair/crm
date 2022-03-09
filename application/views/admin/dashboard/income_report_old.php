<?php
if ($this->session->userdata('user_type') == 1) {
    $where = array('report' => 0, 'status' => 1);
} else {
    $where = array('report' => 0, 'status' => 1, 'for_staff' => 1);
}
$income_report_order = get_row('tbl_dashboard', array('name' => 'income_report') + $where);
if ($this->session->userdata('user_type') == '1') { ?>

    <div class="panel panel-custom menu" style="height: 437px;">
        <header class="panel-heading">
            <h3 class="panel-title"><?= lang('income_report') ?></h3>
        </header>
        <div class="panel-body">
            <form role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/Income" method="post" class="form-horizontal form-groups-bordered hidden-xs">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('year') ?>
                        <span class="required">*</span></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" id="income_report_id" name="Income" value="<?php

                                                                                            if (!empty($Income)) {
                                                                                                echo $Income;
                                                                                            }
                                                                                            ?>" class="form-control years"><span class="input-group-addon"><a href="#"><i class="fa fa-calendar"></i></a></span>
                        </div>
                    </div>
                    <button type="submit" id="income_report_btn" data-toggle="tooltip" data-placement="top" title="Search" class="btn btn-custom"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <!--Sales Chart Canvas -->
            <canvas id="income" style="height:35vh; width: 100%;"></canvas>
        </div><!-- ./box-body -->

    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            initdatepicker();
        });
    </script>
    <?php if (!empty($all_income) && !empty($income_report_order)) { ?>
        <script>
            var buyerData = {

                labels: [
                    <?php
                    foreach ($all_income as $name => $v_income) :
                        $month_name = date('F', strtotime($year . '-' . $name));
                    ?> "<?php echo $month_name; ?>",
                    <?php endforeach; ?>
                ],
                datasets: [{
                    fillColor: "rgba(172,194,132,0.4)",
                    strokeColor: "#ACC26D",
                    pointColor: "#fff",
                    pointStrokeColor: "#9DB86D",
                    data: [
                        <?php
                        foreach ($all_income as $v_income) :
                        ?> "<?php
                            /*               if (!empty($v_income)) {
                                $total_income = 0;
                                foreach ($v_income as $income) {
                                $total_income += $income->amount;
                                }

                                echo $total_income;
                                }*/

                            echo $v_income;
                            ?>",
                        <?php
                        endforeach;
                        ?>
                    ]
                }]
            }
            var buyers = document.getElementById('income').getContext('2d');
            new Chart(buyers).Line(buyerData);
        </script>
    <?php } ?>


<?php } ?>