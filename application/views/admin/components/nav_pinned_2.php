    <?php
    $this->db->select("tbl_project.*", FALSE);
    $this->db->select("tbl_users.*", FALSE);
    $this->db->select("tbl_account_details.*", FALSE);
    $this->db->join('tbl_users', 'tbl_users.user_id = tbl_project.timer_started_by');
    $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_project.timer_started_by');
    $this->db->where(array('timer_status' => 'on'));
    $project_timers = $this->db->get('tbl_project')->result_array();

    $this->db->select("tbl_task.*", FALSE);
    $this->db->select("tbl_users.*", FALSE);
    $this->db->select("tbl_account_details.*", FALSE);
    $this->db->join('tbl_users', 'tbl_users.user_id = tbl_task.timer_started_by');
    $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_task.timer_started_by');
    $this->db->where(array('timer_status' => 'on'));
    $task_timers = $this->db->get('tbl_task')->result_array();

    $user_id = $this->session->userdata('user_id');
    $role = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
    ?>

    <?php
    if (!empty($project_timers)):
        ?>
        <li class="nav-heading"><?= lang('project') . ' ' . lang('start') ?> </li>
    <?php foreach ($project_timers as $p_timer) : if ($role->role_id == 1 || ($role->role_id == 2 && $user_id == $p_timer['user_id'])) : ?>
        <li class="active mb-sm" start="<?php echo $p_timer['timer_status']; ?>">
            <a title="<?php echo $p_timer['project_name'] . " (" . $p_timer['username'] . ")"; ?>"
               data-placement="top" data-toggle="tooltip"
               href="<?= base_url() ?>admin/projects/project_details/<?= $p_timer['project_id'] ?>">
                <img src="<?= base_url() . $p_timer['avatar'] ?>" width="30" height="30"
                     class="img-thumbnail img-circle">
                <span id="project_hour_timer_<?= $p_timer['project_id'] ?>"> 0 </span>
                <!-- SEPARATOR -->
                :
                <!-- MINUTE TIMER -->
                <span id="project_minute_timer_<?= $p_timer['project_id'] ?>"> 0 </span>
                <!-- SEPARATOR -->
                :
                <!-- SECOND TIMER -->
                <span id="project_second_timer_<?= $p_timer['project_id'] ?>"> 0 </span>
                <b class="label label-danger pull-right"> <i class="fa fa-clock-o fa-spin"></i></b>
            </a>
        </li>
    <?php
    //RUNS THE TIMER IF ONLY TIMER_STATUS = 1
    if ($p_timer['timer_status'] == 'on') :

    $project_current_moment_timestamp = strtotime(date("H:i:s"));
    $project_timer_starting_moment_timestamp = $this->db->get_where('tbl_project', array('project_id' => $p_timer['project_id']))->row()->start_time;
    $project_total_duration = $project_current_moment_timestamp - $project_timer_starting_moment_timestamp;

    $project_total_hour = intval($project_total_duration / 3600);
    $project_total_duration -= $project_total_hour * 3600;
    $project_total_minute = intval($project_total_duration / 60);
    $project_total_second = intval($project_total_duration % 60);
    ?>

        <script type="text/javascript">
            // SET THE INITIAL VALUES TO TIMER PLACES
            var timer_starting_hour = <?php echo $project_total_hour; ?>;
            document.getElementById("project_hour_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_hour;
            var timer_starting_minute = <?php echo $project_total_minute; ?>;
            document.getElementById("project_minute_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_minute;
            var timer_starting_second = <?php echo $project_total_second; ?>;
            document.getElementById("project_second_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_second;

            // INITIALIZE THE TIMER WITH SECOND DELAY
            var timer = timer_starting_second;
            var mytimer = setInterval(function () {
                task_run_timer()
            }, 1000);

            function task_run_timer() {
                timer++;

                if (timer > 59) {
                    timer = 0;
                    timer_starting_minute++;
                    document.getElementById("project_minute_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_minute;
                }

                if (timer_starting_minute > 59) {
                    timer_starting_minute = 0;
                    timer_starting_hour++;
                    document.getElementById("project_hour_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_hour;
                }

                document.getElementById("project_second_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer;
            }
        </script>

    <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php
    if (!empty($task_timers)):
        ?>
        <li class="nav-heading"><?= lang('tasks') . ' ' . lang('start') ?> </li>
    <?php
    foreach ($task_timers as $v_task_timer):
    if ($role->role_id == 1 || ($role->role_id == 2 && $user_id == $v_task_timer['user_id'])) :
    ?>
        <li class="mb-sm active" start="<?php echo $v_task_timer['timer_status']; ?>">
            <a title="<?php echo $v_task_timer['task_name'] . " (" . $v_task_timer['username'] . ")"; ?>"
               data-placement="top" data-toggle="tooltip"
               href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task_timer['task_id'] ?>">
                <img src="<?= base_url() . $v_task_timer['avatar'] ?>" width="30" height="30"
                     class="img-thumbnail img-circle">
                <span id="tasks_hour_timer_<?= $v_task_timer['task_id'] ?>"> 0 </span>
                <!-- SEPARATOR -->
                :
                <!-- MINUTE TIMER -->
                <span id="tasks_minute_timer_<?= $v_task_timer['task_id'] ?>"> 0 </span>
                <!-- SEPARATOR -->
                :
                <!-- SECOND TIMER -->
                <span id="tasks_second_timer_<?= $v_task_timer['task_id'] ?>"> 0 </span>
                <b class="label label-danger pull-right"> <i class="fa fa-clock-o fa-spin"></i></b>
            </a>
        </li>
    <?php
    //RUNS THE TIMER IF ONLY TIMER_STATUS = 1
    if ($v_task_timer['timer_status'] == 'on') :

    $task_current_moment_timestamp = strtotime(date("H:i:s"));
    $task_timer_starting_moment_timestamp = $this->db->get_where('tbl_task', array('task_id' => $v_task_timer['task_id']))->row()->start_time;
    $task_total_duration = $task_current_moment_timestamp - $task_timer_starting_moment_timestamp;

    $task_total_hour = intval($task_total_duration / 3600);
    $task_total_duration -= $task_total_hour * 3600;
    $task_total_minute = intval($task_total_duration / 60);
    $task_total_second = intval($task_total_duration % 60);
    ?>

        <script type="text/javascript">
            // SET THE INITIAL VALUES TO TIMER PLACES
            var timer_starting_hour = <?php echo $task_total_hour; ?>;
            document.getElementById("tasks_hour_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_hour;
            var timer_starting_minute = <?php echo $task_total_minute; ?>;
            document.getElementById("tasks_minute_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_minute;
            var timer_starting_second = <?php echo $task_total_second; ?>;
            document.getElementById("tasks_second_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_second;

            // INITIALIZE THE TIMER WITH SECOND DELAY
            var timer = timer_starting_second;
            var mytimer = setInterval(function () {
                task_run_timer()
            }, 1000);

            function task_run_timer() {
                timer++;

                if (timer > 59) {
                    timer = 0;
                    timer_starting_minute++;
                    document.getElementById("tasks_minute_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_minute;
                }

                if (timer_starting_minute > 59) {
                    timer_starting_minute = 0;
                    timer_starting_hour++;
                    document.getElementById("tasks_hour_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_hour;
                }

                document.getElementById("tasks_second_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer;
            }
        </script>

    <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>

