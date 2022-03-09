<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ZoomAPI
{
    /*The API Key, Secret, & URL will be used in every function.*/
    private $api_key = '';
    private $api_secret = '';
    private $api_url = 'https://api.zoom.us/v2/';

    // Function to send HTTP POST Requests Used by every function below to make HTTP POST call

    public function __construct()
    {
        $this->api_key = config_item('zoom_api_key');
        $this->api_secret = config_item('zoom_api_secret');
    }
    private function generateJWT()
    {

        $token = array(
            'iss' => $this->api_key,
            'exp' => time() + 60,
        );
        $header = array(
            'typ' => 'JWT',
            'alg' => 'HS256',
        );

        $toSign =
            self::urlsafeB64Encode(json_encode($header))
            . '.' .
            self::urlsafeB64Encode(json_encode($token));
        $signature = hash_hmac('SHA256', $toSign, $this->api_secret, true);

        return $toSign . '.' . self::urlsafeB64Encode($signature);
    }
    static function urlsafeB64Encode($string)
    {
        return str_replace('=', '', strtr(base64_encode($string), '+/', '-_'));
    }



    function sendRequest($calledFunction, $data, $type = null)
    {
        /*Creates the endpoint URL*/
        $request_url = $this->api_url . $calledFunction;

        $headers = array(
            'Authorization: Bearer ' . $this->generateJWT(),
            'Content-Type: application/json',
            'Accept: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        if (!empty($type)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
            if (!empty($data)) {
                $postFields = json_encode($data);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            }
        } else {
            $postFields = json_encode($data);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            $response = new stdClass();
            $response->error = $err;
            $response = json_encode($response);
        } else {
            if (empty($response)) {
                $response = new stdClass();
                $ff = explode('/', $calledFunction);
                $response->id = end($ff);
                $response = json_encode($response);
            }
            return json_decode($response);
        }
    }


    // Functions for management of users (Ref: https://support.zoom.us/hc/en-us/articles/201363033-REST-User-API)

    function createUser($userEmail, $userType)
    {
        $createAUserArray = array();
        $createAUserArray['email'] = $userEmail;
        $createAUserArray['type'] = $userType;
        return $this->sendRequest('user/create', $createAUserArray);
    }

    function autoCreateAUser($userEmail, $userType, $userPassword)
    {
        $autoCreateAUserArray = array();
        $autoCreateAUserArray['email'] = $userEmail;
        $autoCreateAUserArray['type'] = $userType;
        $autoCreateAUserArray['password'] = $userPassword;
        return $this->sendRequest('user/autocreate', $autoCreateAUserArray);
    }

    function custCreateAUser($userEmail, $userType)
    {
        $custCreateAUserArray = array();
        $custCreateAUserArray['email'] = $userEmail;
        $custCreateAUserArray['type'] = $userType;
        return $this->sendRequest('user/custcreate', $custCreateAUserArray);
    }

    function deleteUser($userId)
    {
        $deleteUserArray = array();
        $deleteUserArray['id'] = $userId;
        return $this->sendRequest('user/delete', $deleteUserArray);
    }

    function listUsers()
    {
        $listUsersArray = array();
        return $this->sendRequest('user/list', $listUsersArray);
    }

    function listPendingUsers()
    {
        $listPendingUsersArray = array();
        return $this->sendRequest('user/pending', $listPendingUsersArray);
    }

    function getUserInfo($userId)
    {
        $getUserInfoArray = array();
        $getUserInfoArray['id'] = $userId;
        return $this->sendRequest('user/get', $getUserInfoArray);
    }

    function getUserInfoByEmail($userEmail, $userLoginType)
    {
        $getUserInfoByEmailArray = array();
        $getUserInfoByEmailArray['email'] = $userEmail;
        $getUserInfoByEmailArray['login_type'] = $userLoginType;
        return $this->sendRequest('user/getbyemail', $getUserInfoByEmailArray);
    }

    function updateUserInfo($userId)
    {
        $updateUserInfoArray = array();
        $updateUserInfoArray['id'] = $userId;
        return $this->sendRequest('user/update', $updateUserInfoArray);
    }

    function updateUserPassword($userId, $userNewPassword)
    {
        $updateUserPasswordArray = array();
        $updateUserPasswordArray['id'] = $userId;
        $updateUserPasswordArray['password'] = $userNewPassword;
        return $this->sendRequest('user/updatepassword', $updateUserPasswordArray);
    }

    function setUserAssistant($userId, $userEmail, $assistantEmail)
    {
        $setUserAssistantArray = array();
        $setUserAssistantArray['id'] = $userId;
        $setUserAssistantArray['host_email'] = $userEmail;
        $setUserAssistantArray['assistant_email'] = $assistantEmail;
        return $this->sendRequest('user/assistant/set', $setUserAssistantArray);
    }

    function deleteUserAssistant($userId, $userEmail, $assistantEmail)
    {
        $deleteUserAssistantArray = array();
        $deleteUserAssistantArray['id'] = $userId;
        $deleteUserAssistantArray['host_email'] = $userEmail;
        $deleteUserAssistantArray['assistant_email'] = $assistantEmail;
        return $this->sendRequest('user/assistant/delete', $deleteUserAssistantArray);
    }

    function revokeSSOToken($userId, $userEmail)
    {
        $revokeSSOTokenArray = array();
        $revokeSSOTokenArray['id'] = $userId;
        $revokeSSOTokenArray['email'] = $userEmail;
        return $this->sendRequest('user/revoketoken', $revokeSSOTokenArray);
    }

    function deleteUserPermanently($userId, $userEmail)
    {
        $deleteUserPermanentlyArray = array();
        $deleteUserPermanentlyArray['id'] = $userId;
        $deleteUserPermanentlyArray['email'] = $userEmail;
        return $this->sendRequest('user/permanentdelete', $deleteUserPermanentlyArray);
    }

    function createMeeting($data = array(), $meetingId = null)
    {
        $createAMeetingArray = array();
        $createAMeetingArray['topic'] = $data['topic'];
        $createAMeetingArray['type']       = !empty($data['type']) ? $data['type'] : 2; //Scheduled

        $createAMeetingArray['start_time'] = str_replace(' ', 'T', $data['meeting_time']);
        $createAMeetingArray['duration']   = !empty($data['duration']) ? $data['duration'] : 60;
        $createAMeetingArray['agneda'] = $data['notes'];
        
        $settings = json_decode($data['additional']);

        $createAMeetingArray['settings']   = array(
            'host_video' => in_array('allow_host_video', $settings) ? true : false,
            'join_before_host' => in_array('allow_join_before_host', $settings) ? true : false,
            'participant_video' => in_array('allow_participant_vedio', $settings) ? true : false,
            'mute_upon_entry' => in_array('allow_mute_upon_entry', $settings) ? true : false,
            'waiting_room' => in_array('allow_waiting_room', $settings) ? true : false,
            'show_share_button' => in_array('allow_share_button', $settings) ? true : false,
            'auto_recording' => in_array('allow_automatically_record', $settings) ? 'cloud' : 'none',
            'alternative_hosts' => isset($alternative_host_ids) ? $alternative_host_ids : "",
            'registrants_email_notification' => isset($alternative_host_ids) ? $alternative_host_ids : "",
        );
        if (!empty($meetingId)) {
            return $this->sendRequest('meetings/' . $meetingId, $createAMeetingArray, 'PATCH');
        } else {
            return $this->sendRequest('users/me/meetings', $createAMeetingArray);
        }
    }

    function deleteMeeting($meetingId)
    {
        return $this->sendRequest('meetings/' . $meetingId, '', 'DELETE');
    }

    function listMeetings($userId)
    {
        $listMeetingsArray = array();
        $listMeetingsArray['host_id'] = $userId;
        return $this->sendRequest('meeting/list', $listMeetingsArray);
    }

    function getMeetingInfo($meetingId)
    {
        return $this->sendRequest('meetings/' . $meetingId, '', 'GET');
    }

    function updateMeetingInfo($meetingId)
    {
        $updateMeetingInfoArray = array();
        $updateMeetingInfoArray['id'] = $meetingId;
        $updateMeetingInfoArray['host_id'] = $userId;
        return $this->sendRequest('meeting/update', $updateMeetingInfoArray);
    }

    function endAMeeting($meetingId)
    {
        $endAMeetingArray = array();
        $endAMeetingArray['action'] = 'end';
        return $this->sendRequest('meetings/' . $meetingId . '/status', $endAMeetingArray, 'PUT');
    }

    function listRecording($userId)
    {
        $listRecordingArray = array();
        $listRecordingArray['host_id'] = $userId;
        return $this->sendRequest('recording/list', $listRecordingArray);
    }




    // Functions for management of webinars (Ref: https://support.zoom.us/hc/en-us/articles/204484645-REST-Webinar-API)

    function createAWebinar($userId, $topic)
    {
        $createAWebinarArray = array();
        $createAWebinarArray['host_id'] = $userId;
        $createAWebinarArray['topic'] = $topic;
        $createAWebinarArray['option_audio'] = 'both';
        $createAWebinarArray['type'] = '5';
        return $this->sendRequest('webinar/create', $createAWebinarArray);
    }

    function deleteAWebinar($webinarId, $userId)
    {
        $deleteAWebinarArray = array();
        $deleteAWebinarArray['id'] = $webinarId;
        $deleteAWebinarArray['host_id'] = $userId;
        return $this->sendRequest('webinar/delete', $deleteAWebinarArray);
    }

    function listWebinars($userId)
    {
        $listWebinarsArray = array();
        $listWebinarsArray['host_id'] = $userId;
        return $this->sendRequest('webinar/list', $listWebinarsArray);
    }

    function getWebinarInfo($webinarId, $userId)
    {
        $getWebinarInfoArray = array();
        $getWebinarInfoArray['id'] = $webinarId;
        $getWebinarInfoArray['host_id'] = $userId;
        return $this->sendRequest('webinar/get', $getWebinarInfoArray);
    }

    function updateWebinarInfo($webinarId, $userId)
    {
        $updateWebinarInfoArray = array();
        $updateWebinarInfoArray['id'] = $webinarId;
        $updateWebinarInfoArray['host_id'] = $userId;
        return $this->sendRequest('webinar/update', $updateWebinarInfoArray);
    }

    function endAWebinar($webinarId, $userId)
    {
        $endAWebinarArray = array();
        $endAWebinarArray['id'] = $webinarId;
        $endAWebinarArray['host_id'] = $userId;
        return $this->sendRequest('webinar/end', $endAWebinarArray);
    }

    // Functions for management of Dashboard (Ref: https://support.zoom.us/hc/en-us/articles/208403693-REST-Dashboard-API)

    function getMeetingList($type = 1, $from, $to)
    {
        $createADashboardArray = array();
        $createADashboardArray['type'] = $type;
        $createADashboardArray['from'] = $from;
        $createADashboardArray['to'] = $to;
        return $this->sendRequest('metrics/meetings', $createADashboardArray);
    }

    function getMeetingDetails($meeting_id, $type)
    {
        $createADashboardArray = array();
        $createADashboardArray['meeting_id'] = $meeting_id;
        $createADashboardArray['type'] = $type;
        return $this->sendRequest('metrics/meetingdetail', $createADashboardArray);
    }

    function getWebnairList($type = 1, $from, $to)
    {
        $createADashboardArray = array();
        $createADashboardArray['type'] = $type;
        $createADashboardArray['from'] = $from;
        $createADashboardArray['to'] = $to;
        return $this->sendRequest('metrics/webinars', $createADashboardArray);
    }

    function getWebnairDetails($meeting_id, $type)
    {
        $createADashboardArray = array();
        $createADashboardArray['meeting_id'] = $meeting_id;
        $createADashboardArray['type'] = $type;
        return $this->sendRequest('metrics/webinardetail', $createADashboardArray);
    }

    function getUserQoS($meeting_id, $type, $user_id)
    {
        $createADashboardArray = array();
        $createADashboardArray['meeting_id'] = $meeting_id;
        $createADashboardArray['type'] = $type;
        $createADashboardArray['user_id'] = $user_id;
        return $this->sendRequest('metrics/qos', $createADashboardArray);
    }

    function zoomRoomList()
    {
        return $this->sendRequest('metrics/zoomrooms');
    }

    function getCRCPortUsage($from, $to)
    {
        $createADashboardArray = array();
        $createADashboardArray['from'] = $from;
        $createADashboardArray['to'] = $to;
        return $this->sendRequest('metrics/crc', $createADashboardArray);
    }

    // Functions for management of Report (Ref: https://support.zoom.us/hc/en-us/articles/201363083-REST-Report-API)

    function getDailyReport($year, $month)
    {
        $createAccountReportArray = array();
        $createAccountReportArray['year'] = $year;
        $createAccountReportArray['month'] = $month;
        return $this->sendRequest('report/getdailyreport', $createAccountReportArray);
    }

    function getAccountReport($from, $to)
    {
        $createAccountReportArray = array();
        $createAccountReportArray['from'] = $from;
        $createAccountReportArray['to'] = $to;
        return $this->sendRequest('report/getaccountreport', $createAccountReportArray);
    }

    function getUserReport($user_id, $from, $to)
    {
        $createAccountReportArray = array();
        $createAccountReportArray['user_id'] = $user_id;
        $createAccountReportArray['from'] = $from;
        $createAccountReportArray['to'] = $to;
        return $this->sendRequest('report/getuserreport', $createAccountReportArray);
    }

    function getAudioReport($from, $to)
    {
        $createAccountReportArray = array();
        $createAccountReportArray['from'] = $from;
        $createAccountReportArray['to'] = $to;
        return $this->sendRequest('report/getaudioreport', $createAccountReportArray);
    }

    // Functions for management of Archived Chat Messages (Ref: https://support.zoom.us/hc/en-us/articles/208064196-REST-Archived-Chat-Messages-API)

    function getChatHistoryList($access_token, $from, $to)
    {
        $createChattArray = array();
        $createChattArray['access_token'] = $access_token;
        $createChattArray['from'] = $from;
        $createChattArray['to'] = $to;
        return $this->sendRequest('chat/list', $createChattArray);
    }

    function getChatMessage($access_token, $session_id, $from, $to)
    {
        $createChattArray = array();
        $createChattArray['access_token'] = $access_token;
        $createChattArray['session_id'] = $session_id;
        $createChattArray['from'] = $from;
        $createChattArray['to'] = $to;
        return $this->sendRequest('chat/get', $createChattArray);
    }

    // Functions for management of Archived Chat Messages (Ref: https://support.zoom.us/hc/en-us/articles/208064196-REST-Archived-Chat-Messages-API)

    function getIMGroupsList()
    {
        return $this->sendRequest('im/group/list');
    }

    function getIMGroupsInfo($group_id)
    {
        $createIMArray = array();
        $createIMArray['id'] = $group_id;

        return $this->sendRequest('im/group/get', $createIMArray);
    }

    function createIMGroup($name)
    {
        $createIMArray = array();
        $createIMArray['name'] = $name;

        return $this->sendRequest('im/group/create', $createIMArray);
    }

    function editIMGroup($group_id)
    {
        $createIMArray = array();
        $createIMArray['id'] = $group_id;

        return $this->sendRequest('im/group/edit', $createIMArray);
    }

    function deleteIMGroup($group_id)
    {
        $createIMArray = array();
        $createIMArray['id'] = $group_id;

        return $this->sendRequest('im/group/delete', $createIMArray);
    }

    function AddIMGroupMember($group_id, $member_ids)
    {
        $createIMArray = array();
        $createIMArray['id'] = $group_id;
        $createIMArray['member_ids'] = $member_ids;

        return $this->sendRequest('im/group/member/add', $createIMArray);
    }

    function deleteIMGroupMember($group_id, $member_ids)
    {
        $createIMArray = array();
        $createIMArray['id'] = $group_id;
        $createIMArray['member_ids'] = $member_ids;

        return $this->sendRequest('im/group/member/delete', $createIMArray);
    }

    // Functions for management of Cloud Recording API (Ref: https://support.zoom.us/hc/en-us/articles/206324325-REST-Cloud-Recording-API)

    function getRecordingList($host_id)
    {
        $createCloudRecordingArray = array();
        $createCloudRecordingArray['host_id'] = $host_id;

        return $this->sendRequest('recording/list', $createCloudRecordingArray);
    }

    function getRecordingForMachine($host_id)
    {
        $createCloudRecordingArray = array();
        $createCloudRecordingArray['host_id'] = $host_id;

        return $this->sendRequest('mc/recording/list', $createCloudRecordingArray);
    }

    function getRecording($meeting_id)
    {
        $createCloudRecordingArray = array();
        $createCloudRecordingArray['meeting_id'] = $meeting_id;

        return $this->sendRequest('recording/get', $createCloudRecordingArray);
    }

    function deleteRecording($meeting_id)
    {
        $createCloudRecordingArray = array();
        $createCloudRecordingArray['meeting_id'] = $meeting_id;

        return $this->sendRequest('recording/delete', $createCloudRecordingArray);
    }
}
