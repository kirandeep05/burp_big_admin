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
        $query = "SELECT `ad_id`, `ad_hotel_id`,`hotel_name`, CONCAT('$url/admin/images/advertisement/',`ad_cover_pic`) as ad_cover_pic "
                . "FROM `bb_advertisement` ba,`bb_hotel` bh WHERE ba.`ad_hotel_id` = bh.`hotel_id` and ba.`active` = '1' "
                . "AND `ad_type_id` = '1' "
                . "AND (`ad_start_date` <= NOW() AND `ad_end_date` >= NOW()) "
                . "AND  `ad_hotel_id` IN ("
                . "SELECT `hotel_id` FROM `bb_hotel_details` WHERE `hotel_field_id` = '6' AND `hotel_field_val` IN (".implode(",",$city_ids).")"
                . ") LIMIT 6";

        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getCities($active = "1") {

        $query = "SELECT `city_id`, `city_name` FROM `bb_city` WHERE `active` = :active";

        $qh = $this->con->getQueryHandler($query, array("active" => $active));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getCitiesGroup($active = "1") {

        $query = "SELECT  `city_id`,`group_name` FROM `bb_city_group` cg, `bb_city_group_name` cgn "
                . "WHERE cg.`city_group_id` = cgn.`group_id` AND `active` = :active";

        $qh = $this->con->getQueryHandler($query, array("active" => $active));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getSingleRestDetail($hotel_id) {

        $query = "SELECT hd.`hotel_id`,`field_name`, `hotel_field_val`,`hotel_name` FROM `bb_hotel_details` hd,`bb_hotel_fields` hf,`bb_hotel` hh "
                . "WHERE `hotel_field_id` = `field_id` "
                . "AND hh.`hotel_id` IN (".  implode(",", $hotel_id).")  AND "
                . "hh.`hotel_id` = hd.`hotel_id`";

        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        $i=0;
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[$res['hotel_id']][$res['field_name']] = $res['hotel_field_val'];
            $data[$res['hotel_id']]['hotel_name'] = $res['hotel_name'];
            $data[$res['hotel_id']]['hotel_id'] = $res['hotel_id'];
        }

        return $data;
    }
    
    public function getCuisineFromID($id) {

        $query = "SELECT `name` FROM `bb_cuisine` WHERE `id` IN ($id)";

        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getMenu($hotel_id) {

        $query = "SELECT `menu_id`, `image_path`,`type_name` FROM `bb_hotel_menu` hm,`bb_menu_type` mt "
                . "WHERE `hotel_id` = :hotel_id AND `menu_type`  = `type_id`";

        $qh = $this->con->getQueryHandler($query, array("hotel_id"=>$hotel_id));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }

    public function getGallery($hotel_id) {

        $url = Constants::WEB_URL;
        $query = "SELECT `gallery_id`, CONCAT('$url/admin/images/gallery/',`image_path`) AS image_path FROM `bb_gallery` WHERE `hotel_id` = :hotel_id";

        $qh = $this->con->getQueryHandler($query, array("hotel_id"=>$hotel_id));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getBanquet($hotel_id) {
        
        $url = Constants::WEB_URL;
        $query = "SELECT `banquet_id`, CONCAT('$url/admin/images/banquet/',`image_path`) AS image_path FROM `bb_banquet` WHERE `hotel_id` = :hotel_id";

        $qh = $this->con->getQueryHandler($query, array("hotel_id"=>$hotel_id));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getHotelIDFromHotelDetails($field_id,$field_val) {
                
        $query = "SELECT `hotel_id` FROM `bb_hotel_details` WHERE `hotel_field_id` = :field_id AND `hotel_field_val` LIKE :field_val";

        $qh = $this->con->getQueryHandler($query, array("field_id"=>$field_id,"field_val"=>$field_val));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res['hotel_id'];
        }

        return $data;
    }
    
    public function getHotelIDFromMenuSel($field_id,$field_val) {
                
        $query = "SELECT `hotel_id` FROM `bb_menu_selection` WHERE `$field_id` = '$field_val'";

        $qh = $this->con->getQueryHandler($query, array("field_id"=>$field_id,"field_val"=>$field_val));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res['hotel_id'];
        }

        return $data;
    }
    
}