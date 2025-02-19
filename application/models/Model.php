<?php

class Model extends CI_Model
{
    private $DATA_ARRAY = array('data' => array(), 'error' => '');
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        // $this->load->library('encrypt');
    }

    function check_info($student_id, $school_code)
    {
        $result = $this->db->get_where('tbl_schools', array('code' => $school_code, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            return 301;
        }
        $result = $this->db->get_where('tbl_school_students', array('student_id' => $student_id, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            return 300;
        }
        return 200;
    }

    function get_student($student_id, &$out_array)
    {
        $result = $this->db->get_where('tbl_student', array('student_id' => $student_id))->result_array();
        if (count($result) == 0) {
            $out_array = "Invalid student_id";
            return 400;
        }
        $out_array = $result[0];
        unset($out_array['id']);
        unset($out_array['user_id']);
        return 200;
    }

    function get_students_school($school_code, &$out_array)
    {
        $result = $this->db->get_where('tbl_student', array('school_code' => $school_code))->result_array();
        if (count($result) == 0) {
            $out_array = "Invalid school_code";
            return 400;
        }
        $out_array = $result;
        return 200;
    }

    function check_secret($secret, $id, &$out_array)
    {
        $result = $this->db->get_where('tbl_user', array('id' => $id, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            $out_array['reason'] = "Invalid user.";
            return 400;
        }
        if (strlen($result[0]['secret']) > 0) {
            if ($result[0]['secret'] != $secret) {
                $out_array['reason'] = "Invalid secret code.";
                return 401;
            }
        } else {
            $this->db->where('id', $id);
            $this->db->update('tbl_user', array('secret' => $secret));
            $out_array['reason'] = "Secret code has been set successfully!";
            return 200;
        }
        $out_array['reason'] = "Secret code has been verified successfully!";
        return 200;
    }

    function login($country_code, $number, $password, &$out_array)
    {
        $result = $this->db->get_where('tbl_user', array('type' => 'user', 'country_code' => $country_code, 'number' => $number, 'password' => $password, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            return 400;
        }
        $out_array = $result[0];
        return 200;
    }
    function get_profile($id, &$out_array)
    {
        $result = $this->db->get_where('tbl_user', array('id' => $id, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            return 400;
        }
        $out_array = $result[0];
        $out_array['photo'] = base_url() . 'assets/user/' . $out_array['photo'];
        return 200;
    }
    function update_profile($id, $photo, &$out_array)
    {
        $result = $this->db->get_where('tbl_user', array('id' => $id, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            return 400;
        }
        $this->db->where('id', $id);
        $this->db->update('tbl_user', array('photo' => $photo));
        $out_array = $result[0];
        $out_array['photo'] = base_url() . 'assets/user/' . $photo;
        return 200;
    }
    function confirm_email($email, &$data)
    {
        $data = $this->DATA_ARRAY;
        $result = $this->db->get_where('tbl_user', array('email' => $email, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            $data['error'] = 'Invalid Email!';
            return 400;
        }
        $data['data'] = $result[0];
        return 200;
    }

    function add_student($in_array)
    {
        $result = $this->db->get_where('tbl_student', array('student_id' => $in_array['student_id']))->result_array();
        if (count($result) > 0) {
            $this->db->where('student_id', $in_array['student_id']);
            $in_array['deleted'] = 0;
            $this->db->update('tbl_student', $in_array);
        } else {
            $this->db->insert('tbl_student', $in_array);
        }
        return 200;
    }

    function get_students($user_id, &$out_array)
    {
        $out_array = $this->db->get_where('tbl_student', array('user_id' => $user_id))->result_array();
        return 200;
    }
    function get_student_total_array_by_school(&$data)
    {
        $student_array = array();
        $school_array = array();
        $result = $this->db->get_where('tbl_school_students', array('deleted' => 0))->result_array();
        for ($i = 0; $i < count($result); $i++) {
            if (!in_array($result[$i]['school_code'], $school_array)) {
                array_push($school_array, $result[$i]['school_code']);
                $result1 = $this->db->get_where('tbl_school_students', array('school_code' => $result[$i]['school_code'], 'deleted' => 0))->result_array();
                array_push($student_array, count($result1));
            }
        }
        $data = array('school' => $school_array, 'student' => $student_array);
    }

    function get_student_total_array_by_user(&$data)
    {
        $student_array = array();
        $user_array = array();

        $result = $this->db->get_where('tbl_user', array('deleted' => 0))->result_array();
        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]['type'] != 'user') continue;
            $students = $this->db->get_where('tbl_student', array('user_id' => $result[$i]['id']))->result_array();
            array_push($user_array, $result[$i]['name']);
            array_push($student_array, count($students));
        }
        $data = array('user' => $user_array, 'student' => $student_array);
    }

    function get_students_by_user($user_id)
    {
        $result = $this->db->get_where('tbl_student', array('user_id' => $user_id))->result_array();
        return count($result);
    }

    function get_user_array(&$data)
    {
        $data = $this->DATA_ARRAY;
        $month = date('Y-m', time());
        $data['data'] = $this->db->get_where('tbl_user', array('type' => USER, 'deleted' => 0))->result_array();
        for ($i = 0; $i < count($data['data']); $i++) {
            $data['data'][$i]['students'] = $this->get_students_by_user($data['data'][$i]['id']);
        }
        return 200;
    }

    function get_user_info($userid, $pwd, &$data)
    {
        $data = $this->DATA_ARRAY;
        $result = $this->db->get_where('tbl_user', array('userid' => $userid, 'deleted' => 0, 'type' => 'admin'))->result_array();
        if (count($result) == 0) {
            $data['error'] = 'Invalid UserID!';
            return 400;
        }
        if ($result[0]['password'] != $pwd) {
            $data['error'] = 'Incorrect Password!';
            return 400;
        }
        $data['data'] = $result[0];
        return 200;
    }

    function get_user_profile($id, &$data)
    {
        $data = $this->DATA_ARRAY;
        $result = $this->db->get_where('tbl_user', array('id' => $id, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            $data['error'] = 'Invalid UserID!';
            return 400;
        }
        $data['data'] = $result[0];
        return 200;
    }
    // function update_profile($id, $userid,$name, $cur_password, $new_password)
    // {
    // 	$result = $this->db->get_where('tbl_user', array('id' => $id, 'password' => $cur_password, 'deleted' => 0))->result_array();
    // 	if (count($result) == 0) {
    // 		$data['error'] = 'Password is not correct!';
    // 		return 400;
    //     }
    // 	$result1 = $this->db->get_where('tbl_user', array('userid' => $userid, 'deleted' => 0))->result_array();
    // 	if (count($result1) > 0 && $result1[0]['id'] != $id) {
    // 		$data['error'] = 'UserID already exists!';
    // 		return 401;
    //     }
    // 	$result2 = $this->db->get_where('tbl_user', array('name' => $name, 'deleted' => 0))->result_array();
    // 	if (count($result2) > 0 && $result2[0]['id'] != $id) {
    // 		$data['error'] = 'Name already exists!';
    // 		return 402;
    //     }
    //     $this->db->where('id', $id);
    //     $this->db->update('tbl_user', array('userid' => $userid,'name' => $name, 'password' => $new_password));
    // 	return 200;
    // }

    function set_user_info($name, $userid, $pwd, &$data)
    {
        $data = $this->DATA_ARRAY;
        $result = $this->db->get_where('tbl_user', array('userid' => $userid, 'deleted' => 0))->result_array();
        if (count($result) > 0) {
            $data['error'] = 'The userID already exists!';
            return 400;
        }
        $data['data'] = array('name' => $name, 'userid' => $userid, 'password' => $pwd, 'type' => USER, 'created' => time());
        $this->db->insert('tbl_user', $data['data']);
        $data['data']['id'] = $this->db->insert_id();
        return 200;
    }

    function get_school_array(&$data)
    {
        $data = $this->DATA_ARRAY;
        $this->db->order_by('id', 'DESC');
        $data['data'] = $this->db->get_where('tbl_schools', array('deleted' => 0))->result_array();
        return 200;
    }

    function get_school_name($school_code)
    {
        $school_name = $this->db->get_where("tbl_schools", array('code' => $school_code))->result_array()[0];
        return $school_name["name"];
    }

    // getting students of special school
    function get_school_students_array($school_code, &$data)
    {
        $data = $this->DATA_ARRAY;
        $this->db->order_by('id', 'DESC');
        $data['data'] = $this->db->get_where('tbl_school_students', array('school_code' => $school_code, 'deleted' => 0))->result_array();
        return 200;
    }

    // getting historical photos of students
    function get_student_photos($school_code) {
        $historical_photos_arr = array();
        $this->db->order_by('id', 'DESC');
        $students_array = $this->db->get_where('tbl_school_students', array('school_code'=>$school_code))->result_array();
        for($i=0;$i<count($students_array); $i++) {
            $sub_photos = $this->db->get_where('tbl_photo_history', array('student_id'=>$students_array[$i]['student_id'], 'checked'=>0, 'deleted'=>0))->result_array();
            array_push($historical_photos_arr, $sub_photos);
        }
        // print_r($historical_photos_arr); die();
        return $historical_photos_arr;
    }

    // add new school student
    function add_school_student($school_code, $name, $student_id, $image)
    {
        $result = $this->db->get_where('tbl_school_students', array('student_id' => $student_id))->result_array();
        if (count($result) > 0) {
            return 400;
        }
        $this->db->insert('tbl_school_students', array('name' => $name, 'school_code' => $school_code, 'student_id' => $student_id, 'photo' => $image));
        $cur_time = date("Y-m-d h:i:sa");
        $this->db->insert('tbl_photo_history', array('student_id'=>$student_id, 'photo_path'=>$image, 'uploaded_at'=>$cur_time));
        return 200;
    }

    // uploaded photos
    function get_student_array(&$data)
    {
        $data = $this->DATA_ARRAY;
        $this->db->order_by('id', 'DESC');
        $data['data'] = $this->db->get_where('tbl_student', array('deleted' => 0))->result_array();
        for ($i = 0; $i < count($data['data']); $i++) {
            $data['data'][$i]['user'] = $this->db->get_where('tbl_user', array('id' => $data['data'][$i]['user_id'], 'deleted' => 0))->result_array()[0]['name'];
            $data['data'][$i]['standard_img'] = $this->db->get_where('tbl_school_students', array('student_id' => $data['data'][$i]['student_id'], 'deleted' => 0))->result_array()[0]['photo'];
        }
        return 200;
    }

    function get_unimatched_photos_array(&$data)
    {
        $data = $this->DATA_ARRAY;
        $this->db->order_by('id', 'DESC');
        $data['data'] = $this->db->get_where('tbl_photo_history', array('checked' => 1, 'deleted'=> 0))->result_array();
        for ($i = 0; $i < count($data['data']); $i++) {
            $data['data'][$i]['user'] = $this->db->get_where('tbl_user', array('id' => $data['data'][$i]['user_id'], 'deleted' => 0))->result_array()[0]['name'];
            $data['data'][$i]['standard_img'] = $this->db->get_where('tbl_school_students', array('student_id' => $data['data'][$i]['student_id'], 'deleted' => 0))->result_array()[0]['photo'];
        }
        return 200;
    }

    function get_user_earning_array(&$data, $user_id)
    {
        $data = $this->DATA_ARRAY;
        $this->db->order_by('date', 'DESC');
        $data['data'] = $this->db->get_where('tbl_earning', array('user_id' => $user_id, 'deleted' => 0))->result_array();
        // for ($i=0; $i < count($data['data']); $i++) {
        //     $data['data'][$i]['payment_email'] = $this->get_data_by_id('tbl_payment', $data['data'][$i]['payment_id'], 'email');
        // }
        return 200;
    }

    function get_account_array(&$data)
    {
        $data = $this->DATA_ARRAY;
        $data['data'] = $this->db->get_where('tbl_account', array('deleted' => 0))->result_array();
        for ($i = 0; $i < count($data['data']); $i++) {
            $data['data'][$i]['payment_email'] = $this->get_data_by_id('tbl_payment', $data['data'][$i]['payment_id'], 'site');
            $data['data'][$i]['user_name'] = $this->get_data_by_id('tbl_user', $data['data'][$i]['user_id'], 'name');
        }
        return 200;
    }

    function get_user_account_array(&$data, $user_id)
    {
        $data = $this->DATA_ARRAY;
        $data['data'] = $this->db->get_where('tbl_account', array('user_id' => $user_id, 'deleted' => 0))->result_array();
        for ($i = 0; $i < count($data['data']); $i++) {
            $data['data'][$i]['payment_email'] = $this->get_data_by_id('tbl_payment', $data['data'][$i]['payment_id'], 'email');
        }
        return 200;
    }

    function get_payment_array(&$data)
    {
        $data = $this->DATA_ARRAY;
        $data['data'] = $this->db->get_where('tbl_payment', array('deleted' => 0))->result_array();
        return 200;
    }

    function add_user($name, $email, $password, $school, $phone, $country_code)
    {
        $result = $this->db->get_where('tbl_user', array('country_code' => $country_code, 'number' => $phone, 'type' => 'user', 'deleted' => 0))->result_array();
        if (count($result) > 0) {
            return 400;
        }
        $this->db->insert('tbl_user', array('name' => $name, 'email' => $email, 'type' => 'user', 'password' => $password, 'school_code' => $school, 'country_code' => $country_code, 'number' => $phone, 'created' => time()));
        return 200;
    }

    function edit_user($id, $name, $email, $password, $school, $phone, $country_code)
    {
        $result = $this->db->get_where('tbl_user', array('country_code' => $country_code, 'number' => $phone, 'type' => 'user', 'deleted' => 0))->result_array();
        if (count($result) > 0) {
            if ($result[0]['id'] != $id) {
                return 400;
            }
        }
        $this->db->where('id', $id);
        $this->db->update('tbl_user', array('name' => $name, 'email' => $email, 'type' => 'user', 'password' => $password, 'school_code' => $school, 'country_code' => $country_code, 'number' => $phone, 'created' => time()));
        return 200;
    }

    // school processing
    function add_school($name, $dern, $iep, $status, $school_code)
    {
        $result = $this->db->get_where('tbl_schools', array('code' => $school_code))->result_array();
        if (count($result) > 0) {
            return 400;
        }
        $this->db->insert('tbl_schools', array('name' => $name, 'dern' => $dern, 'iep' => $iep, 'statut' => $status, 'code' => $school_code));
        return 200;
    }

    function edit_school($id, $name, $dern, $iep, $status, $school_code)
    {
        $result = $this->db->query("select * from tbl_schools where id !=" . $id . " and code=" . $school_code)->result();
        if (count($result) > 0) {
            return 400;
        }
        $this->db->where('id', $id);
        $this->db->update('tbl_schools', array('name' => $name, 'dern' => $dern, 'iep' => $iep, 'statut' => $status, 'code' => $school_code));
        return 200;
    }

    function delete_school($s_code)
    {
        $this->db->where('code', $s_code);
        $this->db->update("tbl_schools", array('deleted' => 1));
        $this->db->where('school_code', $s_code);
        $this->db->update("tbl_school_students", array('deleted' => 1));
        $this->db->where('school_code', $s_code);
        $this->db->update("tbl_student", array('deleted' => 1));
        $this->db->where('school_code', $s_code);
        $this->db->update("tbl_photo_history", array('deleted' => 1));
        return 200;
    }

    function edit_school_student($id, $name)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_school_students', array('name' => $name));
        return 200;
    }

    function delete_school_student($s_id)
    {
        $this->db->where('student_id', $s_id);
        $this->db->update("tbl_school_students", array('deleted' => 1));
        $this->db->where('student_id', $s_id);
        $this->db->update("tbl_student", array('deleted' => 1));
        $this->db->where('student_id', $s_id);
        $this->db->update("tbl_photo_history", array('deleted' => 1));
        return 200;
    }


    function edit_payment($id, $email, $password, $site, $date)
    {
        $result = $this->db->get_where('tbl_payment', array('email' => $email, 'deleted' => 0))->result_array();
        if (count($result) > 0) {
            if ($result[0]['id'] != $id) {
                return 400;
            }
        }
        $this->db->where('id', $id);
        $this->db->update('tbl_payment', array('email' => $email, 'password' => $password, 'site' => $site, 'date' => $date));
        return 200;
    }

    function add_earning($info, $task, $amount, $date, $user_id)
    {
        // $result = $this->db->get_where('tbl_payment', array('email' => $payment_email, 'deleted' => 0))->result_array();
        // if (count($result) == 0) {
        //     return 400;
        // }
        $this->db->insert('tbl_earning', array('user_id' => $user_id, 'info' => $info, 'task' => $task, 'amount' => $amount, 'date' => $date));
        return 200;
    }

    function edit_earning($info, $task, $amount, $date, $id)
    {
        // $result = $this->db->get_where('tbl_payment', array('email' => $payment_email, 'deleted' => 0))->result_array();
        // if (count($result) == 0) {
        //     return 400;
        // }
        $this->db->where('id', $id);
        $this->db->update('tbl_earning', array('info' => $info, 'task' => $task, 'amount' => $amount, 'date' => $date));
        return 200;
    }

    function add_account($email, $payment_email, $site, $date, $review, $user_id)
    {
        $result = $this->db->get_where('tbl_account', array('email' => $email, 'deleted' => 0))->result_array();
        if (count($result) > 0) {
            return 401;
        }
        $result = $this->db->get_where('tbl_payment', array('email' => $payment_email, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            return 400;
        }
        $this->db->insert('tbl_account', array('user_id' => $user_id, 'payment_id' => $result[0]['id'], 'site' => $site, 'email' => $email, 'date' => $date, 'review' => $review));
        return 200;
    }

    function edit_account($email, $payment_email, $site, $date, $review, $id)
    {
        $result = $this->db->get_where('tbl_account', array('email' => $email, 'deleted' => 0))->result_array();
        if (count($result) > 0) {
            if ($id != $result[0]['id']) {
                return 401;
            }
        }
        $result = $this->db->get_where('tbl_payment', array('email' => $payment_email, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            return 400;
        }
        $this->db->where('id', $id);
        $this->db->update('tbl_account', array('payment_id' => $result[0]['id'], 'site' => $site, 'email' => $email, 'date' => $date, 'review' => $review));
        return 200;
    }

    function delete_item($table, $id)
    {
        if ($table == "tbl_student") {
            $student = $this->db->get_where($table, array('id' => $id))->result_array()[0];
            $this->db->delete('tbl_student', array('id' => $id));
            unlink(base_url("uploads/" . $student['path']));
            return;
        }
        $this->db->where('id', $id);
        $this->db->update($table, array('deleted' => 1));
    }

    function change_item($table, $id)
    {
        $result = $this->db->get_where($table, array('id' => $id))->result_array();
        $new_value = 0;
        if ($result[0]['locked'] == 0) {
            $new_value = 1;
        }
        $this->db->where('id', $id);
        $this->db->update($table, array('locked' => $new_value));
    }

    function get_data_by_id($table, $id, $field)
    {
        $result = $this->db->get_where($table, array('id' => $id, 'deleted' => 0))->result_array();
        if (count($result) > 0) {
            return $result[0][$field];
        }
        return '';
    }

    function get_users(&$array)
    {
        $array = array();
        $this->db->where('deleted', 0);
        $this->db->where('character_id>', 0);
        $cur_time = time();

        $array = $this->db->get('tbl_user')->result_array();
        for ($i = 0; $i < count($array); $i++) {
            $array[$i]['time'] = date('d/m/Y h:i a', $array[$i]['created']);
            $result = $this->db->get_where('tbl_country', array('phonecode' => $array[$i]['country_code']))->result_array();
            $array[$i]['country_name'] = $result[0]['nicename'];
            $array[$i]['country_flag'] = base_url() . 'assets/images/flags/' . strtolower($result[0]['iso']) . '.png';
            $result = $this->db->get_where('tbl_character', array('id' => $array[$i]['character_id']))->result_array();
            $array[$i]['characterr'] = $result[0]['name'];
            $array[$i]['character'] = base_url() . 'uploadImages/character/' . $result[0]['image'];

            if (($cur_time - $array[$i]['timestamp']) > REALTIME_PERIOD) {
                $this->db->where('id', $array[$i]['id']);
                $this->db->update('tbl_user', array('latitude' => 0, 'longitude' => 0));
            }
        }
        return 200;
    }

    function get_redeem(&$array)
    {
        $array = array();
        $array = $this->db->get_where('tbl_redeem', array('deleted' => 0))->result_array();
        for ($i = 0; $i < count($array); $i++) {
            $array[$i]['time'] = date('d/m/Y h:i a', $array[$i]['created']);
            $result = $this->db->get_where('tbl_user', array('id' => $array[$i]['user_id']))->result_array();
            $array[$i]['user'] = $result[0]['firstname'] . ' ' . $result[0]['lastname'];
        }
        return 200;
    }

    function change_redeem($id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_redeem', array('isread' => 1));
        return 200;
    }

    function fetch_userById($id, &$array)
    {
        $array = array();
        $array = $this->db->get_where('tbl_user', array('id' => $id))->result_array()[0];
        $array['name'] = $array['firstname'] . ' ' . $array['lastname'];
        $result = $this->db->get_where('tbl_country', array('phonecode' => $array['country_code']))->result_array();
        $array['country_name'] = $result[0]['nicename'];
        $array['country_flag'] = base_url() . 'assets/images/flags/' . strtolower($result[0]['iso']) . '.png';
        $result = $this->db->get_where('tbl_character', array('id' => $array['character_id']))->result_array();
        $array['characterr'] = $result[0]['name'];
        $array['character'] = base_url() . 'uploadImages/character/' . $result[0]['image'];


        $array['emergency_count'] = count($this->db->get_where('tbl_emergency', array('user_id' => $id, 'deleted' => 0))->result_array());
        $array['groups'] = '';
        $group_arr = $this->db->get_where('tbl_group', array('deleted' => 0))->result_array();
        for ($i = 0; $i < count($group_arr); $i++) {
            $member_arr = explode(",", $group_arr[$i]['members']);
            if (in_array($id, $member_arr)) {
                if ($array['groups'] == '') {
                    $array['groups'] = $group_arr[$i]['name'];
                } else {
                    $array['groups'] = $array['groups'] . ', ' . $group_arr[$i]['name'];
                }
            }
        }
        return 200;
    }

    function update_password($id, $password, $error)
    {
        $result = $this->db->get_where('tbl_user', array('id' => $id, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            $error = 'Invalid user!';
            return 400;
        }
        $this->db->where('id', $id);
        $this->db->update('tbl_user', array('password' => $password));
        return 200;
    }

    function getIdByEmail($email, &$out_array)
    {
        $result = $this->db->get_where('tbl_user', array('email' => $email, 'deleted' => 0))->result_array();
        if (count($result) == 0) {
            $out_array['reason'] = 'Invalid user!';
            return 400;
        }
        if ($result[0]['isverified'] == 0) {
            $out_array['reason'] = 'Email was not verified!';
            return 400;
        }
        $out_array = $result[0];
        return 200;
    }

    function check_school_code($rd_code)
    {
        $result = $this->db->get_where('tbl_schools', array('code' => $rd_code))->result_array();
        if (count($result) > 0)
            return 1;
        return 0;
    }

    function check_student_id($student_id)
    {
        $result = $this->db->get_where('tbl_school_students', array('student_id' => $student_id))->result_array();
        if (count($result) > 0)
            return "dupulicated";
        return "ok";
    }

    function check_photo($id, $checked_value, $sid, $uploaded, $userid, $school_code)
    {
        if ($checked_value == "1") {
            $this->db->where('student_id', $sid);
            $this->db->update('tbl_school_students', array('photo' => $uploaded));
        }

        $cur_time = date("Y-m-d h:i:sa");
        $array = array("student_id" => $sid, "photo_path" => $uploaded
            ,"school_code" => $school_code,  "uploaded_at" => $cur_time, "user_id"=>$userid, "checked" => ($checked_value=="1"?0:1));
        $this->db->insert('tbl_photo_history', $array);
        $this->db->where('id', $id);
        $this->db->update('tbl_student', array('deleted' => 1));
        return 200;
    }

    function check_photo_all($all_data)
    {
        for ($i = 0; $i < count($all_data); $i++) {
            if (!($all_data[$i]["compare_status"] > 0.4)) {
                $this->db->where('student_id', $all_data[$i]["sid"]);
                $this->db->update('tbl_school_students', array('photo' => $all_data[$i]["path"]));
            }

            $cur_time = date("Y-m-d h:i:sa");
            $array = array("student_id" => $all_data[$i]["sid"], "photo_path" => $all_data[$i]["path"],"school_code" => $all_data[$i]["code"], "uploaded_at" => $cur_time, "user_id"=>$all_data[$i]["userid"]
                , "checked" => ($all_data[$i]["compare_status"] > 0.4)?1:0);
            $this->db->insert('tbl_photo_history', $array);
            $this->db->where('id', $all_data[$i]["id"]);
            $this->db->update('tbl_student', array('deleted' => 1));
        }
        return 200;
    }
}
