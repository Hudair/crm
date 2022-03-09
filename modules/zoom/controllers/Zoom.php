<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Class spreadsheet
 */
class Zoom extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (empty(my_id())) {
      redirect('login');
    }
    require_once(module_dirPath(ZOOM_MODULE) . 'libraries/ZoomAPI.php');
  }

  public function index($id = NULL)
  {
    $data['title'] = lang('zoom');
    if (!empty($id)) {
      $data['active'] = 2;
      $data['meeting_info'] = $this->db->where('zoom_meeting_id', $id)->get('tbl_zoom_meeting')->row();
    } else {
      $data['active'] = 1;
    }
    $data['subview'] = $this->load->view('manage', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function settings($id = null)
  {
    $data['active'] = 1;
    $data['title'] = lang('zoom_settings'); //Page title
    $input = $this->admin_model->array_from_post(array('zoom_api_key', 'zoom_api_secret'));
    if (!empty($this->input->post())) {
      if (!empty($input['zoom_api_key']) && !empty($input['zoom_api_secret'])) {
        foreach ($input as $key => $value) {
          $data = array('value' => $value);
          $this->db->where('config_key', $key)->update('tbl_config', $data);
          $exists = $this->db->where('config_key', $key)->get('tbl_config');
          if ($exists->num_rows() == 0) {
            $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
          }
        }
        $type = 'success';
        $message = lang('information_update');
      } else {
        $type = 'error';
        $message = lang('all_field_required');
      }
      set_message($type, $message);
      redirect('admin/zoom');
    }
    $data['subview'] = $this->load->view('settings', $data, TRUE);
    $this->load->view('admin/_layout_main', $data); //page load
  }


  public function meetingList($zoom_meeting_id = null)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_zoom_meeting';
      $this->datatables->join_table = array('tbl_account_details');
      $this->datatables->join_where = array('tbl_account_details.user_id=tbl_zoom_meeting.host');
      $this->datatables->column_order = array('topic', 'fullname', 'status', 'meeting_time');
      $this->datatables->column_search = array('topic', 'fullname', 'status', 'meeting_time');
      $this->datatables->order = array('zoom_meeting_id' => 'desc');

      $where = null;

      $fetch_data = make_datatables($where);

      $data = array();

      $edited = can_action_by_label('zoom', 'edited');
      $deleted = can_action_by_label('zoom', 'deleted');
      foreach ($fetch_data as $_key => $v_meeting) {
        $action = null;
        $sub_array = array();
        $sub_array[] = $v_meeting->topic;
        $sub_array[] = display_datetime($v_meeting->meeting_time);
        $sub_array[] = $v_meeting->notes;
        if ($v_meeting->status == 'finished') {
          $d = 'success';
        } else if ($v_meeting->status == 'canceled') {
          $d = 'danger';
        } else {
          $d = 'primary';
        }
        $change_status = '<div class="btn btn-xs btn-' . $d . '">' . lang($v_meeting->status) . '</div>';
        $ch_url = base_url() . 'zoom/change_status/' . $v_meeting->zoom_meeting_id;
        $astatus_info = array('waiting', 'finished', 'canceled');
        $change_status .= '<div class="btn-group">
        <button class="btn btn-xs btn-default dropdown-toggle"
                data-toggle="dropdown">
            <span class="caret"></span></button>
        <ul class="dropdown-menu animated zoomIn">';
        foreach ($astatus_info as $v_status) {
          $change_status .= '<li><a href="' . $ch_url . '/' . $v_status . '">' . lang($v_status) . '</a></li>';
        }
        $change_status .= '</ul></div>';
        $sub_array[] = $change_status;
        if ($v_meeting->host == my_id()) {
          $sub_array[] = '<a target="_blank" data-toggle="tooltip" data-placement="top" title="' . lang('start_as_host') . '" class="btn btn-warning btn-xs" href="' . base_url('zoom/join/' . url_encode($v_meeting->zoom_meeting_id)) . '"><i class="fa fa-file-video-o"></i> </a>' . ' ';
        } else {
          $sub_array[] = '<a target="_blank" data-toggle="tooltip" data-placement="top" title="' . lang('join_the_meeting') . '" class="btn btn-warning btn-xs" href="' . base_url('zoom/joined/' . url_encode($v_meeting->zoom_meeting_id)) . '"><i class="fa fa-file-video-o"></i> </a>' . ' ';
        }

        if (!empty($edited) || !empty($deleted)) {
          if (!empty($edited)) {
            $action .= btn_edit('admin/zoom/index/' . ($v_meeting->zoom_meeting_id)) . ' ';
          }
          if (!empty($deleted)) {
            $action .= ajax_anchor(base_url("admin/zoom/delete_meeting/" . ($v_meeting->zoom_meeting_id)), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
          }
        }
        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data);
    } else {
      redirect('admin/dashboard');
    }
  }

  public
  function save_meeting($id = null)
  {
    $created = can_action_by_label('zoom', 'created');
    $edited = can_action_by_label('zoom', 'edited');
    if (!empty($created) || !empty($edited) && !empty($id)) {

      $data = $this->admin_model->array_from_post(array('topic', 'meeting_time', 'duration', 'notes', 'host'));
      $data['user_id'] = json_encode($this->input->post('user_id', true));
      $data['client_id'] = json_encode($this->input->post('client_id', true));
      $data['leads_id'] = json_encode($this->input->post('leads_id', true));
      $data['additional'] = json_encode($this->input->post('additional', true));

      $api = new ZoomAPI();
      if (!empty($id)) {
        $meetingId = get_any_field('tbl_zoom_meeting', array('zoom_meeting_id' => $id), 'meetingId');
        $response = $api->createMeeting($data, $meetingId);
      } else {
        $response = $api->createMeeting($data);
      }

      if ($response) {
        if (isset($response->id)) {
          $data['meetingId'] = $response->id;
          if (!empty($response->start_url)) {
            $data['start_url'] = $response->start_url;
            $data['join_url'] = $response->join_url;
            $data['status'] = $response->status;
          }
          $this->admin_model->_table_name = 'tbl_zoom_meeting';
          $this->admin_model->_primary_key = 'zoom_meeting_id';
          $this->admin_model->save($data, $id);

          $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_new_custom_field'),
            'value1' => $data['topic']
          );

          $this->admin_model->_table_name = 'tbl_activities';
          $this->admin_model->_primary_key = 'activities_id';
          $this->admin_model->save($activity);
          // messages for user
          $type = "success";
          $message = lang('meeting_information_saved');
        } else {
          $type = "error";
          $message = $response->message;
        }
      }
    } else {
      $type = "error";
      $message = lang('something_went_wrong');
    }
    set_message($type, $message);
    redirect('admin/zoom');
  }

  public function delete_meeting($id)
  {
    $deleted = can_action_by_label('zoom', 'deleted');
    if (!empty($deleted)) {
      $field_info = $this->db->where('zoom_meeting_id', $id)->get('tbl_zoom_meeting')->row();

      $activity = array(
        'user' => $this->session->userdata('user_id'),
        'module' => 'settings',
        'module_field_id' => $id,
        'activity' => ('activity_delete_custom_field'),
        'value1' => $field_info->topic
      );

      $this->admin_model->_table_name = 'tbl_activities';
      $this->admin_model->_primary_key = 'activities_id';
      $this->admin_model->save($activity);

      $zoom = new ZoomAPI();
      $zoom->deleteMeeting($field_info->meetingId);

      $this->admin_model->_table_name = 'tbl_zoom_meeting';
      $this->admin_model->_primary_key = 'zoom_meeting_id';
      $this->admin_model->delete($id);
      // messages for user
      echo json_encode(array("status" => 'success', 'message' => lang('delete_meeting_info')));
      exit();
    } else {
      echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
      exit();
    }
  }


  public function change_status($zoom_meeting_id, $status)
  {
    $data['meeting_info'] = get_row('tbl_zoom_meeting', array('zoom_meeting_id' => $zoom_meeting_id));
    if (!empty($data['meeting_info'])) {
      $rdata['status'] = $status;
      update('tbl_zoom_meeting', array('zoom_meeting_id' => $zoom_meeting_id), $rdata);
      $type = "success";
      $message = lang('meeting_information_updated');
    } else {
      $type = "error";
      $message = lang('something_went_wrong');
    }
    set_message($type, $message);
    redirect('admin/zoom');
  }

  public function join($zoom_meeting_id)
  {
    $zoom_meeting_id = url_decode($zoom_meeting_id);
    $data['meeting_info'] = get_row('tbl_zoom_meeting', array('zoom_meeting_id' => $zoom_meeting_id));

    if ($data['meeting_info']->host == my_id()) {
      $rdata['status'] = 'finished';
      $rdata['meeting_start'] = date('Y-m-d H:i');
      update('tbl_zoom_meeting', array('zoom_meeting_id' => $zoom_meeting_id), $rdata);
      $url = $data['meeting_info']->start_url;
      $this->send_notify_assign_user($zoom_meeting_id);
    } else {
      $url = $data['meeting_info']->join_url;
    }
    if (!empty($data['meeting_info'])) {
      redirect($url);
    }
  }

  public function send_notify_assign_user($zoom_meeting_id)
  {
    $meeting_info = get_row('tbl_zoom_meeting', array('zoom_meeting_id' => $zoom_meeting_id));
    $email_template = email_templates(array('email_group' => 'meeting_start'));
    $message = $email_template->template_body;
    $subject = $email_template->subject;

    $host = str_replace("{HOST}", fullname($meeting_info->host), $message);
    $Link = str_replace("{MEETING_URL}", base_url('zoom/joined/' . url_encode($zoom_meeting_id)), $host);

    $user = json_decode($meeting_info->user_id);
    if (!empty($user) && is_array($user)) {
      foreach ($user as $id) {
        $USER = str_replace("{USER}", fullname($id), $Link);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $USER);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';
        $params['recipient'] = get_any_field('tbl_users', array('user_id' => $id), 'email');
        $this->admin_model->send_email($params);

        if ($id != $this->session->userdata('user_id')) {
          add_notification(array(
            'to_user_id' => $id,
            'from_user_id' => true,
            'description' => 'lets_have_meetings',
            'link' => base_url('zoom/joined/' . $zoom_meeting_id),
            'value' => $meeting_info->topic,
          ));
          show_notification($user);
        }
      }

      $client = json_decode($meeting_info->client_id);
      if (!empty($client) && is_array($client)) {
        foreach ($client as $client_id) {
          $clientInfo = get_row('tbl_client', array('client_id' => $client_id));
          $USER = str_replace("{USER}", $clientInfo->name, $Link);
          $message = str_replace("{SITE_NAME}", config_item('company_name'), $USER);

          $data['message'] = $message;
          $message = $this->load->view('email_template', $data, TRUE);

          $params['subject'] = $subject;
          $params['message'] = $message;
          $params['resourceed_file'] = '';

          $params['recipient'] = $clientInfo->email;
          $this->admin_model->send_email($params);
        }
      }

      $leads = json_decode($meeting_info->leads_id);
      if (!empty($leads) && is_array($leads)) {
        foreach ($leads as $leads_id) {
          $leadsInfo = get_row('tbl_leads', array('leads_id' => $leads_id));
          $USER = str_replace("{USER}", $leadsInfo->lead_name, $Link);
          $message = str_replace("{SITE_NAME}", config_item('company_name'), $USER);

          $data['message'] = $message;
          $message = $this->load->view('email_template', $data, TRUE);

          $params['subject'] = $subject;
          $params['message'] = $message;
          $params['resourceed_file'] = '';
          $params['recipient'] = $leadsInfo->email;
          $this->admin_model->send_email($params);
        }
      }
    }
  }

  public function joined($zoom_meeting_id = null)
  {
    if (!empty(my_id())) {
      $zoom_meetingId = url_decode($zoom_meeting_id);
      $data['meeting_info'] = get_row('tbl_zoom_meeting', array('zoom_meeting_id' => $zoom_meetingId));
      if (!empty($data['meeting_info'])) {
        if (!empty(staff())) {
          $user = json_decode($data['meeting_info']->user_id);
          $id = my_id();
        } elseif (!empty(client())) {
          $user = json_decode($data['meeting_info']->client_id);
          $id = client_id();
        }
        if (!empty($user) && in_array($id, $user)) {
          $this->join($zoom_meeting_id);
        } else {
          set_message('error', 'there is no permission to you man!');
          redirect('admin/zoom');
        }
      }
    }
  }
}
