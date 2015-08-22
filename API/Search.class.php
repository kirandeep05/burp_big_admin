<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//include_once '../include/Connection.class.php';

class Search {

    protected $con;

    function __construct() {

        $this->con = new Connection();
    }

    public function getHotelIdsFromSearchValues($search_value,$limit = "") {

        $searchArray = explode(" ",$search_value);
        $countSearchArray = count($searchArray);
        $bindParams = "";
        
        if ($countSearchArray > 1 )
        {
            $bindParamsValue= array();
            for ($i=0;$i<$countSearchArray;$i++){
                $bindParamsValue[] =" hotel_field_norm LIKE '%".$searchArray[$i]."%' ";
            }
            $bindParams = implode(" AND ",$bindParamsValue);
        }else{
            $bindParams = "hotel_field_norm LIKE '%".$search_value."%'";
        }
        $limit_query = "";
        if($limit != "") {
            $limit_query = " LIMIT ".$limit;
        }
        
        $query = "SELECT DISTINCT(`hotel_id`) FROM `bb_hotel_details` WHERE ".$bindParams.$limit_query ;        
                        
        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = intval($res['hotel_id']);
        }
        $hotel_ids = implode(",",$data);
        return $data;
    }
    
    public function getFilterResults($hotel_ids,$filter) { 
        foreach($filter as $key=>$value) {
            if(!empty($hotel_ids)) {
                if($key == "21") {
                    $hotel_ids = $this->getHotelIdsFromPriceFilter($key,$value,$hotel_ids);
                } else {
                    $hotel_ids = $this->getHotelIdsFromFilter($key,$value,$hotel_ids);
                }
                //var_dump($hotel_ids);
            }
        }
        return $hotel_ids;
     }
    
     public function getHotelIdsFromFilter($key,$val,$hotel_ids) {
        //var_dump($hotel_ids);
        $query = "SELECT DISTINCT(`hotel_id`) FROM `bb_hotel_details` WHERE (hotel_field_id = $key AND hotel_field_norm REGEXP '$val') AND `hotel_id` IN (".implode(",",$hotel_ids).")" ;
        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res['hotel_id'];
        }        
        return $data; 
     }
     
     public function getHotelIdsFromPriceFilter($key,$val,$hotel_ids) {
        $price = explode("-", $val);
        $low = $price[0];
        $high = $price[1];
        if($low != "" && $high != "") {
            $query = "SELECT DISTINCT(`hotel_id`) FROM `bb_hotel_details` "
                . "WHERE (hotel_field_id = $key AND (hotel_field_norm > $low AND hotel_field_norm < $high)) AND "
                . "`hotel_id` IN (".implode(",",$hotel_ids).")" ;
        } else if($low == "" && $high != "") {
            $query = "SELECT DISTINCT(`hotel_id`) FROM `bb_hotel_details` "
                . "WHERE (hotel_field_id = $key AND (hotel_field_norm < $high)) AND "
                . "`hotel_id` IN (".implode(",",$hotel_ids).")" ;
        } else if($low != "" && $high == "") {
            $query = "SELECT DISTINCT(`hotel_id`) FROM `bb_hotel_details` "
                . "WHERE (hotel_field_id = $key AND (hotel_field_norm > $low)) AND "
                . "`hotel_id` IN (".implode(",",$hotel_ids).")" ;
        }
        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $res['hotel_id'];
        }        
        return $data; 
     }
    
    public function getSearchSuggestions($search_value) {

        $searchArray = explode(" ",$search_value);
        $countSearchArray = count($searchArray);
        if ($countSearchArray > 1 )
        {
            $bindParamsValue= array();
            for ($i=0;$i<$countSearchArray;$i++){
                $bindParamsValue[] =" LOWER(hotel_field_norm) LIKE LOWER('%".$searchArray[$i]."%') ";
            }
            $bindParams = implode(" AND ",$bindParamsValue);
            }else{
            $bindParams = "LOWER(hotel_field_norm) LIKE LOWER('%".$search_value."%')";
        }

        $query = "SELECT DISTINCT `hotel_field_norm` AS val,  `field_name` AS type FROM `bb_hotel_details` hd,`bb_hotel_fields` hf WHERE ".$bindParams." "
                . "AND hd.`hotel_field_id` = hf.`field_id` AND hd.`hotel_field_id` NOT IN (11,12,16,17,21,22,24,27,28,29,30) AND "
                . "`hotel_id` IN "
                . "(SELECT `hotel_id` FROM `bb_hotel_details` WHERE `hotel_field_id` = '6' AND `hotel_field_val` IN (1073,4080,4081,4082,1,2,3) )" ;
        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_NUM)) {
            $data[] = $res;
        }        
        return $data;
    }
    
    

}