<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../include/Connection.class.php';

class Restaurant {

    protected $con;

    function __construct() {

        $this->con = new Connection();
    }

    public function getRandomCuisines() {

        $query = "SELECT `id`, `name` FROM `bb_cuisine` WHERE active = :active ORDER BY RAND() LIMIT 3";

        $qh = $this->con->getQueryHandler($query, array("active" => "1"));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getQuickSearch() {

        $query = "SELECT `qs_id` as id, `qs_name` as name FROM `bb_quick_search` WHERE `qs_active` = :qs_active ORDER BY `qs_order`";

        $qh = $this->con->getQueryHandler($query, array("qs_active" => "1"));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getTrendingAds($city_ids) {

        $url = Constants::WEB_URL;
        $query = "SELECT `ad_id`, `ad_hotel_id`, CONCAT('$url/admin/images/advertisement/',`ad_cover_pic`) as ad_cover_pic FROM `bb_advertisement` WHERE `active` = '1' "
                . "AND `ad_type_id` = '1' "
                . "AND (`ad_start_date` <= NOW() AND `ad_end_date` >= NOW()) "
                . "AND  `ad_hotel_id` IN ("
                . "SELECT `hotel_id` FROM `bb_hotel_details` WHERE `hotel_field_id` = '6' AND `hotel_field_val` IN (".implode(",",$city_ids).")"
                . ")";

        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getCities() {

        $query = "SELECT `city_id`, `city_name` FROM `bb_city` WHERE `active` = :active";

        $qh = $this->con->getQueryHandler($query, array("active" => "1"));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    

    
}