<?php
$action = $this->uri->segment(4);
if ($this->session->userdata('user_type') == 1) {
    $where = array('status' => 1);
} else {
    $t_where = array('for_staff' => 1);
    $where = $where + $t_where;
}
$payments_report_order = get_row('tbl_dashboard', array('name' => 'payments_report') + $where);
if (!empty($payments_report_order)) {
    $yearly_overview = $this->admin_model->get_yearly_overview($yearly);
?>


    <div class="panel panel-custom menu" style="height: 437px;">
        <header class="panel-heading">
            <h3 class="panel-title"><?= lang('payments_report') ?></h3>
        </header>
        <div class="panel-body">
            <form role="form" id="form" action="<?php echo base_url(); ?>admin/dashboard/index/payments" method="post" class="form-horizontal form-groups-bordered hidden-xs">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('year') ?>
                        <span class="required">*</span></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" name="yearly" id="payment_year_id" value="<?php
                                                                                            if (!empty($yearly)) {
                                                                                                echo $yearly;
                                                                                            }
                                                                                            ?>" class="form-control years"><span class="input-group-addon"><a href="#"><i class="fa fa-calendar"></i></a></span>
                        </div>
                    </div>
                    <button type="submit" data-toggle="tooltip" data-placement="top" title="Search" id="payments_report_btn" class="btn btn-custom"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <canvas id="yearly_report" style="height:35vh; width: 100%;" class="col-sm-12"></canvas>
        </div><!-- ./box-body -->
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            initdatepicker();
        });
    </script>
    <?php if (!empty($yearly_overview)) { ?>
        <script>
            var buyerData = {

                labels: [
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        $month_name = date('F', strtotime($yearly . '-' . $i));
                    ?> "<?php echo $month_name; ?>", <?php }; ?>
                ],
                datasets: [{
                    fillColor: "rgba(172,194,132,0.4)",
                    strokeColor: "#ACC26D",
                    pointColor: "#fff",
                    pointStrokeColor: "#9DB86D",
                    data: [
                        <?php
                        foreach ($yearly_overview as $v_overview) :
                        ?> "<?php
                            echo $v_overview;
                            ?>",
                        <?php
                        endforeach;
                        ?>
                    ]
                }]
            };
            var buyers = document.getElementById('yearly_report').getContext('2d');
            new Chart(buyers).Line(buyerData);
        </script>
<?php }
} ?>