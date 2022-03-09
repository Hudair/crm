<style>

    .menu-border-transparent {
        border-color: transparent !important;
        height: 40px;
        color: #a9a3a3;
        background-color: rgba(255, 255, 255, .1);
        /*width: 100%;*/
    }

    input[type="search"]::-webkit-search-cancel-button {
        -webkit-appearance: searchfield-cancel-button;
    }
    .inner-addon {
        position: relative;
    }
    .left-addon .fa {
        left: 0px;
    }
    .inner-addon .fa {
        position: absolute;
        pointer-events: none;
        padding: 13px;
    }
    .left-addon input {
        padding-left: 30px;
    }


</style>
<aside class="aside">
    <!-- START Sidebar (left)-->
    <?php
    $user_id = $this->session->userdata('user_id');
    $profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
    $user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
    ?>
    <div class="aside-inner">
        <nav data-sidebar-anyclick-close="" class="sidebar <?= config_item('show-scrollbar') ?>">
            <!-- START sidebar nav-->
            <ul class="nav">
                <!-- START user info-->
                <li class="has-user-block">
                    <a href="<?= base_url('admin/user/user_details/' . $user_id) ?>">
                        <div id="user-block" class="block">
                            <div class="item user-block">
                                <!-- User picture-->
                                <div class="user-block-picture">
                                    <div class="user-block-status">
                                        <img src="<?= base_url() . $profile_info->avatar ?>" alt="Avatar" width="60"
                                             height="60"
                                             class="img-thumbnail img-circle">
                                        <div class="circle circle-success circle-lg"></div>
                                    </div>
                                </div>
                                <!-- Name and Job-->
                                <div class="user-block-info">
                                    <span class="user-block-name"><?= $profile_info->fullname ?></span>
                                    <span class="user-block-role"></i> <?= lang('online') ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- END user info-->
            <div class="inner-addon left-addon" style="width: 95%">
                <i class="fa fa-search"></i>
                <input type="search" id="s-menu" class="form-control menu-border-transparent" placeholder="<?= lang('search_menu') ?>"/>
            </div>
            <br/>

            <?php
            echo $this->menu->dynamicMenu();

            //if (!empty($pinned_details)) { ?>
                <ul class="nav pinned" id="nav_pinned_cont">
                    <?php //$this->load->view("admin/components/nav_pinned"); ?>
                </ul>
            <?php //} ?>
            <!-- Iterates over all sidebar items-->
            <ul class="nav pinned" id="nav_pinned_cont_2">
                <?php //$this->load->view("admin/components/nav_pinned_2"); ?>
            </ul>
            <script>
                $(document).ready(function () {  ins_data(base_url+'admin/dashboard/pinned_menu_items')   });
            </script>
            <!-- END sidebar nav-->
        </nav>
    </div>
    <!-- END Sidebar (left)-->
</aside>
