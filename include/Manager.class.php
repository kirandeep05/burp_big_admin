<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Manager {

    protected $con;

    function __construct() {

        $this->con = new Connection();
    }

    public function getHotelTimingData($hotel_id, $food_type) {
        $query = "SELECT * FROM `bb_hotel_timing_seats` WHERE `hotel_id` = $hotel_id AND `food_type_id` = $food_type ";
        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while ($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data = $res;
        }
        return $data;
    }

    public function getHotelAvailabilityData($hotel_id, $food_type) {
        $today = date('Y-m-d');
        $start = date("Y-m-01", strtotime($today));
        $end = date("Y-m-t", strtotime($today));
        $month_day_count = date("t", strtotime($today));
        $query = "SELECT date, status FROM `bb_hotel_availability` WHERE `hotel_id` = $hotel_id AND `food_type_id` = $food_type AND `date` BETWEEN '$start' AND '$end' ";
        $qh = $this->con->getQueryHandler($query, array());
        $holidays = array();
        while ($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $holidays[$res['date']] = $res['status'];
        }
        $avail_days = array();
        for ($dt = 0; $dt <= $month_day_count - 1; $dt++) {
            $list_date = date("Y-m-d", strtotime("$start + " . ($dt) . " day"));
            $avail_days[$list_date] = 'available';
        }
        $data = array_merge($avail_days, $holidays);
        return $data;
    }

    public function getMasterHotelAvailabilityData($hotel_id) {
        $today = date('Y-m-d');
        $start = date("Y-m-01", strtotime($today));
        $end = date("Y-m-t", strtotime($today));
        $month_day_count = date("t", strtotime($today));
        $query = "SELECT date, status FROM `bb_hotel_availability` WHERE `hotel_id` = $hotel_id AND `status` = 'master-un-available' AND `date` BETWEEN '$start' AND '$end' ";
        $qh = $this->con->getQueryHandler($query, array());
        $holidays = array();
        while ($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $holidays[$res['date']] = $res['status'];
        }
        $avail_days = array();
        for ($dt = 0; $dt <= $month_day_count - 1; $dt++) {
            $list_date = date("Y-m-d", strtotime("$start + " . ($dt) . " day"));
            $avail_days[$list_date] = 'available';
        }
        $data = array_merge($avail_days, $holidays);
        return $data;
    }

    public function insertHotelAvailabilityData($data) {
//        print_r($data);die;
        $res = array();
        foreach ($data['available-days'] as $key => $days) {
            if ($days == 'un-available') {
                $res[$key] = $data['available-days'];
            } else {
                unset($data['available-days'][$key]);
            }
        }
        $query = $this->con->getQueryHandler("DELETE FROM `bb_hotel_timing_seats` WHERE `hotel_id` = '" . $data['hotel_id'] . "' AND `food_type_id` = '" . $data['food_type_id'] . "'");
        $query1 = $this->con->insertQuery("INSERT INTO `bb_hotel_timing_seats` SET `hotel_id` = '" . $data['hotel_id'] . "', `food_type_id` = '" . $data['food_type_id'] . "', `opening_time`='" . $data['opening-time'] . "',`closing_time`='" . $data['closing-time'] . "',`time_interval`='" . $data['time-interval'] . "',`seats`='" . $data['avaliable-seats'] . "',`update_by`=5 ");
        $avail_delete_query = $this->con->insertQuery("DELETE FROM `bb_hotel_availability` WHERE `hotel_id` = '" . $data['hotel_id'] . "' AND `food_type_id` = '" . $data['food_type_id'] . "' AND `status` = 'un-available'");
        foreach ($data['available-days'] as $key => $udate) {
            $avail_insert_query = $this->con->insertQuery("INSERT INTO `bb_hotel_availability` SET `hotel_id` = '" . $data['hotel_id'] . "', `food_type_id` = '" . $data['food_type_id'] . "',  `date` = '" . $key . "', `status` = 'un-available'");
        }
    }

    public function insertMasterHotelAvailabilityData($data) {
        $res = array();
        foreach ($data['available-days'] as $key => $days) {
            if ($days == 'master-un-available') {
                $res[$key] = $data['available-days'];
            } else {
                unset($data['available-days'][$key]);
            }
        }
        $avail_delete_query = $this->con->insertQuery("DELETE FROM `bb_hotel_availability` WHERE `hotel_id` = '" . $data['hotel_id'] . "' AND `status` = 'master-un-available'");
        foreach ($data['available-days'] as $key => $udate) {
            for ($i = 1; $i <= 3; $i++) {
                $avail_insert_query = $this->con->insertQuery("INSERT INTO `bb_hotel_availability` SET `hotel_id` = '" . $data['hotel_id'] . "', `food_type_id` = '" . $i . "',  `date` = '" . $key . "', `status` = 'master-un-available'");
            }
        }
    }

    public function getUserId($username, $password) {
        $query = "SELECT user_id FROM `bb_user_login` WHERE `user_name` = :username AND `password` = :password";

        $qh = $this->con->getQueryHandler($query, array("username" => $username, "password" => $password));
        $data = array();
        while ($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data = $res;
        }

        return $data;
    }

    public function getHotelId($user_id) {
        $query = "SELECT * FROM `bb_hotel_users` WHERE `user_id` = :user_id";

        $qh = $this->con->getQueryHandler($query, array("user_id" => $user_id));
        $data = array();
        while ($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data = $res;
        }
        return $data;
    }

    public function getHotelMenuType($hotel_id) {
        $query = "SELECT `hotel_field_val` FROM `bb_hotel_details` WHERE `hotel_id` = :hotel_id AND `hotel_field_id` = 14 ";

        $qh = $this->con->getQueryHandler($query, array("hotel_id" => $hotel_id));
        $data = array();
        while ($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data = $res;
        }
        return $data;
    }

    public function getHotelMenuFood($hotel_id, $type_id) {
        $query = "SELECT * FROM `bb_menu_selection` WHERE `hotel_id` = :hotel_id AND `type_id` = :type_id ";

        $qh = $this->con->getQueryHandler($query, array("hotel_id" => $hotel_id, "type_id" => $type_id));
        $data = array();
        while ($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data['food_type'] = array(
                'breakfast' => $res['breakfast'],
                'lunch' => $res['lunch'],
                'dinner' => $res['dinner']
            );
        }
        return $data;
    }
    public function getHotelavailbility($hotel_id, $food_id) {
        $query_manager_table = "SELECT `opening_time`, `closing_time`, `seats` FROM `bb_hotel_timing_seats` WHERE `hotel_id` = :hotel_id AND `food_type_id` = :food_type_id ";
        $qh = $this->con->getQueryHandler($query_manager_table, array("hotel_id" => $hotel_id, "food_type_id" => $food_id));
        $data = array();
        while ($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data = $res;
        }
//        while ($res_o = $qh_o->fetch(PDO::FETCH_ASSOC)) {
//            $data = $res;
//        }
        return $data;
    }

}
