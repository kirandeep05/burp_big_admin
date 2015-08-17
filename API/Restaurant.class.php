<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//include_once '../../include/Connection.class.php';

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
    
    public function getSponsoredAds($city_ids) {

        $strip = $this->getRandomStrip();
        $url = Constants::WEB_URL;
        $query = "SELECT `ad_id`, `ad_hotel_id`,`hotel_name`, CONCAT('$url/admin/images/advertisement/',`ad_cover_pic`) as ad_cover_pic, `text` 
                    FROM `bb_advertisement` ba,`bb_hotel` bh WHERE ba.`ad_hotel_id` = bh.`hotel_id` and ba.`active` = '1' 
                    AND `ad_type_id` = '2' 
                    AND (`ad_start_date` <= NOW() AND `ad_end_date` >= NOW()) 
                    AND `strip` = '$strip'
                    AND  `ad_hotel_id` IN (
                    SELECT `hotel_id` FROM `bb_hotel_details` WHERE `hotel_field_id` = '6' AND `hotel_field_val` IN (".implode(",",$city_ids).")
                    ) ORDER BY `order`";

        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getRandomStrip(){
        $query = "SELECT DISTINCT(`strip`) as no FROM `bb_advertisement` WHERE `ad_type_id` = '2' ORDER BY RAND() LIMIT 1";
        $qh = $this->con->getQueryHandler($query, array());
        $res = $qh->fetch(PDO::FETCH_ASSOC);
        return $res['no'];
        
    }
    
    
    public function getCities($active = "1") {

        $query = "SELECT `ID` as city_id, `Name` as city_name,`District` FROM `city` WHERE `active` = :active";

        $qh = $this->con->getQueryHandler($query, array("active" => $active));
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res;
        }

        return $data;
    }
    
    public function getCity($city_ids) {

        $query = "SELECT `ID` as city_id, `Name` as city_name,`District` FROM `city` WHERE `ID` IN (".  implode(",", $city_ids).")";

        $qh = $this->con->getQueryHandler($query, array());
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

        $query = "SELECT hd.`hotel_id`,`field_name`,`hotel_field_id`, `hotel_field_val`,`hotel_field_norm`,`hotel_name`,`rest_name` "
                . "FROM `bb_hotel_details` hd,`bb_hotel_fields` hf,`bb_hotel` hh,`bb_rest` br "
                . "WHERE `hotel_field_id` = `field_id` "
                . "AND hh.`hotel_id` IN (".  implode(",", $hotel_id).")  AND "
                . "hh.`hotel_id` = hd.`hotel_id` AND br.`rest_id` = hh.`rest_id`";

        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        $i=0;
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[$res['hotel_id']][$res['field_name']."$$"."val"] = $res['hotel_field_val'];
            $data[$res['hotel_id']][$res['field_name']] = $res['hotel_field_norm'];            
            if(trim(strtolower($res['hotel_name'])) == trim(strtolower($res['rest_name']))) {
                $data[$res['hotel_id']]['hotel_name'] = $res['hotel_name'];
            } else {
                $data[$res['hotel_id']]['hotel_name'] = $res['hotel_name']." - ".$res['rest_name'];
            }
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
    
    //AK
    
    /**
     * Get Dinning details based on hotel id
     */
    public function getDinningDetails($hotel_id) {
    
    	$query = "SELECT dn.* FROM `bb_hotel_dinning` dn, `bb_hotel_dinning_timmings` dq where dn.dinning_id = dq.dinning_id and dq.todays_quota > 0 and dq.hotel_id=:hotel_id";
    
    	$qh = $this->con->getQueryHandler($query, array("hotel_id"=>$hotel_id));
    	$data = array();
    	while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
    		$data[] = $res;
    	}
    
    	return $data;
    }
    
    
    /**
     * Get Dinning quota by date
     */
    public function getDinningQuota($hotel_id,$dinning_id, $date) {
    
    	begin:
    	
    	$query = "SELECT `remaining_quota` FROM `bb_hotel_dinning_quota` WHERE `hotel_id`=:hotel_id and `dinning_id`=:dinning_id and `date`=:date";
    
    	$qh = $this->con->getQueryHandler($query, array("hotel_id"=>$hotel_id, "dinning_id"=>$dinning_id, "date"=>$date));
    	$data = array();
    	while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
    		$data[] = $res['remaining_quota'];
    	}
    	// update the quota in the table for specific date if it is not present. Otherwise Cron Job will do this job.
    	if(empty($data)){
    		$newQuery = "SELECT quota from `bb_hotel_dinning_timmings` dq where dq.dinning_id = :dinning_id and dq.hotel_id=:hotel_id";
    		$newData = $this->con->getQueryHandler($newQuery, array("dinning_id"=>$dinning_id, "hotel_id"=>$hotel_id ));
    		
    		while($res = $newData->fetch(PDO::FETCH_ASSOC)) {
    			$quota= $res['quota'];
    		}
    		if(!empty($quota)){
    			$this->updateQuotaByDate($hotel_id, $dinning_id, $date,$quota);
    			goto begin;
    		}else{
    			$date[0] = " Quota not present in Database!! Try Again Later"; 
    			goto end;
    		}
    	}
    	
    	end:
    	return $data;
    }
    
    /**
     * Update Quota by date if not done by cron job.
     */
    public function updateQuotaByDate($hotel_id, $dinning_id, $date,$quota){
    	
    	$query = "INSERT INTO bb_hotel_dinning_quota(hotel_id,dinning_id,date,remaining_quota) VALUES(:hotel_id, :dinning_id, :date, :remaining)";
    	
    	$bindParams = array("hotel_id" => $hotel_id,"dinning_id"=> $dinning_id, "date" => $date, "remaining" => $quota);
    	
    	$id = $this->con->insertQuery($query, $bindParams);
    }
    
    /**
     * Get start and end timmings of Dinning
     */
    public function getDinningTimmings($hotel_id,$dinning_id) {
    
    	$query = "SELECT dq.* FROM `bb_hotel_dinning_timmings` dq where dq.dinning_id =:dinning_id and dq.hotel_id=:hotel_id";
    
    	$qh = $this->con->getQueryHandler($query, array("dinning_id"=>$dinning_id, "hotel_id"=>$hotel_id));
    	$data = array();
    	while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
    		$data['start'] = $res['start_time'];
    		$data['end'] = $res['end_time'];
    	}
    
    	 return $data;
    }
}