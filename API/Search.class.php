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

    public function getHotelIdsFromSearchValues($search_value,$limit = "",$filter = array()) {

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
        $filter_val = (empty($filter))?"":" AND ";  
        $filter_arr = array();
        if(!empty($filter)) {
            foreach($filter as $key=>$val) {
                $filter_arr[] = " (hotel_field_id = $key AND hotel_field_norm REGEXP '$val')";
            }
            $filter_val .= implode(" AND ", $filter_arr);
            $query = "SELECT DISTINCT(`hotel_id`) FROM `bb_hotel_details` WHERE `hotel_id` IN ("
                    . "SELECT DISTINCT(`hotel_id`) FROM `bb_hotel_details` WHERE ".$bindParams.")".$filter_val;
        } else {
            $query = "SELECT DISTINCT(`hotel_id`) FROM `bb_hotel_details` WHERE ".$bindParams.$limit_query ;        
        }
                        
        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = intval($res['hotel_id']);
        }
        $hotel_ids = implode(",",$data);
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