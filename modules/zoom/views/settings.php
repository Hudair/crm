<div class="panel panel-custom">
    <div class="panel-heading"><?= lang('zoom') . ' ' . lang('settings') ?></div>
    <div class="panel-body">
        <div class="text-center mb"><a class="text-center" href="https://devforum.zoom.us/t/finding-your-api-key-secret-credentials-in-marketplace/3471">for api and secret see the docs</a></div>
        <form role="form" data-parsley-validate="" novalidate="" action="<?php echo base_url(); ?>admin/zoom/settings" method="post" class="form-horizontal form-groups-bordered">

            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('zoom_api_key') ?></label>
                <div class="col-lg-6">
                    <input type="text" name="zoom_api_key" value="<?php echo set_value('', config_item('zoom_api_key')); ?>" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('zoom_secret_key') ?></label>
                <div class="col-lg-6">
                    <input type="text" name="zoom_api_secret" value="<?php echo set_value('', config_item('zoom_api_secret')); ?>" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-6">
                    <div class="pull-left">
                        <button type="submit" name="user_email_integration" class="btn btn-sm btn-primary"><?= lang('save_changes'); ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>