<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin {

    protected $con;

    function __construct() {

        $this->con = new Connection();
    }

    public function getCuisines() {

        $query = "SELECT `id`, `name` FROM `bb_cuisine` WHERE  `active` = :active";

        $qh = $this->con->getQueryHandler($query, array("active" => "1"));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getMenuSelection($type_id, $hotel_id) {

        $query = "SELECT `breakfast`, `lunch`, `dinner` FROM `bb_menu_selection` WHERE `type_id` = :type_id AND `hotel_id` = :hotel_id";

        $qh = $this->con->getQueryHandler($query, array("type_id" => $type_id,"hotel_id"=>$hotel_id));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data = $res;
        }

        return $data;
    }
    
    
    public function checkRestaurant($name) {

        $query = "SELECT `rest_id` FROM `bb_rest` WHERE LOWER(`rest_name`) = :rest_name";

        $qh = $this->con->getQueryHandler($query, array("rest_name" => $name));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getRestaurantName($hotel_id) {

        $query = "SELECT br.`rest_name`, bh.`hotel_name`   FROM `bb_hotel` bh, `bb_rest` br WHERE bh.`hotel_id` = :hotel_id AND bh.`rest_id` = br.`rest_id`";

        $qh = $this->con->getQueryHandler($query, array("hotel_id" => $hotel_id));
        return $qh->fetch(PDO::FETCH_ASSOC);
    }
    
    public function checkHotel($name) {

        $query = "SELECT `hotel_id` FROM `bb_hotel` WHERE LOWER(`hotel_name`) = :hotel_name";

        $qh = $this->con->getQueryHandler($query, array("hotel_name" => $name));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getRestaurants() {

        $query = "SELECT `rest_id`, `rest_name` FROM `bb_rest` WHERE `active` = '1'";

        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getRestaurantList() {

        $query = "SELECT bh.`hotel_id`, bh.`hotel_name`, br.`rest_name`,  bh.`active`, bh.`updated_date` FROM "
                . "`bb_hotel` bh,`bb_rest` br "
                . "WHERE bh.`rest_id` = br.`rest_id`";

        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function insertRestaurant($name) {

        $query = "INSERT INTO `bb_rest`(`rest_name`,`active`) VALUES (:rest_name,:active)";

        $bindParams = array("rest_name" => $name, "active" => "1");

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function insertHotel($name, $rest_id) {

        $query = "INSERT INTO `bb_hotel`(`hotel_name`, `rest_id`, `active`) VALUES (:hotel_name,:rest_id,:active)";

        $bindParams = array("hotel_name" => $name,"rest_id"=>$rest_id, "active" => "1");

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function updateHotel($name, $hotel_id) {

        $query = "UPDATE `bb_hotel` SET `hotel_name` = :hotel_name, `updated_date`= NOW() WHERE `hotel_id` = :hotel_id";

        $bindParams = array("hotel_name" => $name,"hotel_id"=>$hotel_id);

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function insertHotelMenu($menu_type,$image_path, $hotel_id) {

        $query = "INSERT INTO `bb_hotel_menu`(`menu_type`, `image_path`, `hotel_id`) VALUES (:menu_type,:image_path,:hotel_id)";

        $bindParams = array("menu_type" => $menu_type,"image_path"=>$image_path, "hotel_id" => $hotel_id);

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function insertMenuSelection($menu_type_id, $breakfast, $lunch, $dinner,$hotel_id, $active) {

        $query = "INSERT INTO `bb_menu_selection`(`type_id`, `breakfast`, `lunch`, `dinner`, `hotel_id`, `active`) "
                . "VALUES (:type_id,:breakfast,:lunch,:dinner,:hotel_id,:active)";

        $bindParams = array("type_id" => $menu_type_id,"breakfast"=>$breakfast, "lunch" => $lunch, "dinner" => $dinner,"hotel_id"=>$hotel_id ,"active" => $active);

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function updateMenuSelection($menu_type_id, $breakfast, $lunch, $dinner,$hotel_id) {

        $query = "UPDATE `bb_menu_selection` SET `breakfast`= :breakfast,`lunch`= :lunch,`dinner`= :dinner, `updated_date`= NOW() "
                . "WHERE `type_id`= :type_id AND `hotel_id`= :hotel_id";

        $bindParams = array("type_id" => $menu_type_id,"breakfast"=>$breakfast, "lunch" => $lunch, "dinner" => $dinner,"hotel_id"=>$hotel_id);

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function insertBanquetImage($image_path, $hotel_id) {

        $query = "INSERT INTO `bb_banquet`( `image_path`, `hotel_id`) VALUES (:image_path,:hotel_id)";

        $bindParams = array("hotel_id"=>$hotel_id ,"image_path" => $image_path);

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function insertHotelDetails($hotel_id, $hotel_field_id, $hotel_field_val) {

        $query = "INSERT INTO `bb_hotel_details`(`hotel_id`, `hotel_field_id`, `hotel_field_val`, `active`) "
                . "VALUES "
                . "(:hotel_id, :hotel_field_id, :hotel_field_val,:active)";

        $bindParams = array("hotel_id" => $hotel_id,"hotel_field_id"=>$hotel_field_id,"hotel_field_val"=>$hotel_field_val, "active" => "1");

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function updateHotelDetails($hotel_id, $hotel_field_id, $hotel_field_val) {

        $query = "UPDATE `bb_hotel_details` SET `hotel_field_val`=:hotel_field_val WHERE "
                . "`hotel_id` = :hotel_id AND `hotel_field_id` = :hotel_field_id";

        $bindParams = array("hotel_id" => $hotel_id,"hotel_field_id"=>$hotel_field_id,"hotel_field_val"=>$hotel_field_val);

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function insertGallery($image_path, $hotel_id) {

        $query = "INSERT INTO `bb_gallery`(`image_path`, `hotel_id`) VALUES (:image_path,:hotel_id)";

        $bindParams = array("hotel_id" => $hotel_id,"image_path"=>$image_path);

        $id = $this->con->insertQuery($query, $bindParams);

        return $id;
    }
    
    public function getGallery($hotel_id) {

        $query = "SELECT `gallery_id`, `image_path` FROM `bb_gallery` WHERE  `hotel_id` = :hotel_id";

        $qh = $this->con->getQueryHandler($query, array("hotel_id"=>$hotel_id));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getMenu($hotel_id,$menu_type) {

        $query = "SELECT `menu_id`,  `image_path`  FROM `bb_hotel_menu` WHERE `menu_type` = :menu_type AND `hotel_id` = :hotel_id";

        $qh = $this->con->getQueryHandler($query, array("hotel_id"=>$hotel_id, "menu_type"=>$menu_type));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getHotelDetails($hotel_id) {

        $query = "SELECT `hotel_id`, `hotel_field_id`, `hotel_field_val`, `active` FROM `bb_hotel_details` WHERE `hotel_id` = :hotel_id";

        $qh = $this->con->getQueryHandler($query, array("hotel_id"=>$hotel_id));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function uploadFile($file,$type) {


        if ($file["error"] > 0) {

            //echo json_encode(array("error" => "Error: " . $file["error"], "code" => "0"));
            //exit();
            return "";
        }
        $access_token = uniqid();
        $filename = $access_token . "-" . time() . session_id() . ".jpg";

        while (file_exists("../images/".$type."/" . $filename)) {

            $filename = $access_token . time() . session_id() . ".jpg";
        }

        if (move_uploaded_file($file["tmp_name"], "../images/" .$type."/" . $filename)) {

            return $filename;
        } else {

            return "-1";
        }
    }

    
}