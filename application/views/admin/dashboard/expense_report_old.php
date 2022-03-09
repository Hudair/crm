<?php
if ($this->session->userdata('user_type') == 1) {
    $where = array('report' => 0, 'status' => 1);
} else {
    $where = array('report' => 0, 'status' => 1, 'for_staff' => 1);
}
$expense_report_order = get_row('tbl_dashboard', array('name' => 'expense_report') + $where);

?>

<div class="panel panel-custom menu" style="height: 437px;">
    <header class="panel-heading">
        <h3 class="panel-title"><?= lang('expense_report') ?></h3>
    </header>
    <div class="panel-body">
        <form role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard" method="post" class="form-horizontal form-groups-bordered hidden-xs">
            <div class="form-group">
                <label class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('year') ?>
                    <span class="required">*</span></label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" id="expense_report_id" name="year" value="<?php
                                                                                        if (!empty($year)) {
                                                                                            echo $year;
                                                                                        }
                                                                                        ?>" class="form-control years"><span class="input-group-addon"><a href="#"><i class="fa fa-calendar"></i></a></span>
                    </div>
                </div>
                <button type="submit" id="expense_report_btn" data-toggle="tooltip" data-placement="top" title="Search" class="btn btn-custom"><i class="fa fa-search"></i></button>
            </div>
        </form>
        <!--End select input year -->
        <canvas id="buyers" style="height:35vh; width: 100%;"></canvas>
    </div><!-- ./box-body -->

</div>
<script type="text/javascript">
    $(document).ready(function() {
        initdatepicker();
    });
</script>
<?php if (!empty($all_expense) && !empty($expense_report_order)) { ?>
    <script>
        var buyerData = {
            labels: [
                <?php
                foreach ($all_expense as $name => $v_expense) :
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
                    foreach ($all_expense as $v_expense) :
                    ?> "<?php
                        /*if (!empty($v_expense)) {
                            $total_expense = 0;
                            foreach ($v_expense as $exoense) {
                            $total_expense += $exoense->amount;
                            }
                            echo $total_expense;
                            }*/
                        echo $v_expense;
                        ?>",
                    <?php
                    endforeach;
                    ?>
                ]
            }]
        }
        var buyers = document.getElementById('buyers').getContext('2d');
        new Chart(buyers).Line(buyerData);
    </script>
<?php } ?>