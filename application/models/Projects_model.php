<?php
/**
 * Description of Project_Model
 *
 * @author NaYeM
 */
class Projects_Model extends MY_Model{

    public $_table_name;
    public $_order_by;
    public $_primary_key;


    public $_select_for_overdue;




public
function overdue_projects($total_rows = FALSE)
{

    $select = "tbl_project.project_id, tbl_project.permission, tbl_project.project_name, tbl_project.end_date, tbl_project.project_status,

        (SELECT tbl_client.name FROM tbl_client WHERE tbl_client.client_id =  tbl_project.client_id) AS client_name ,
        CASE 
        WHEN  tbl_project.calculate_progress IS NULL  THEN tbl_project.progress
        
        WHEN  tbl_project.calculate_progress = 'through_project_hours'
        
        THEN CAST(COALESCE((
        
        (SELECT COALESCE(SUM(tbl_tasks_timer.end_time - tbl_tasks_timer.start_time), 0) / 3600 FROM tbl_tasks_timer
        WHERE tbl_tasks_timer.project_id = tbl_project.project_id AND tbl_tasks_timer.start_time != 0 AND tbl_tasks_timer.end_time != 0) *100 /
        
        ((SUBSTRING_INDEX(tbl_project.estimate_hours, ':', 1)*3600 +
        
        SUBSTRING_INDEX(tbl_project.estimate_hours, ':', -1)*60))
        
        ), 0) as decimal(3, 0))
        
        WHEN  tbl_project.calculate_progress = 'through_tasks'
        
        THEN CAST(COALESCE((
        
        COALESCE(((SELECT count(1) FROM tbl_task WHERE tbl_task.project_id = tbl_project.project_id AND tbl_task.task_status = 'completed')  * 100 ), 0) /
        
        COALESCE((SELECT count(1) FROM tbl_task WHERE tbl_task.project_id = tbl_project.project_id ), 0)
        
        ), 0)as decimal(3, 0))
        
        END AS final_progress ";

    $today = date('Y-m-d h:i:s');

    $where = array('tbl_project.project_status !=' => 'completed', 'tbl_project.end_date <' => $today);
    //$where = array('tbl_project.project_status !=' => 'completed', 'tbl_project.end_date <' => 'CURDATE()');

    if($total_rows) {return  $this->get_permission('tbl_project', $where, $select, true);}

    return  $this->get_permission('tbl_project', $where, $select);
    }




}