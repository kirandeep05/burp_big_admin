<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../include/Connection.class.php';

class Search {

    protected $con;

    function __construct() {

        $this->con = new Connection();
    }

    public function getHotelIdsFromSearchValues($search_value) {

        $searchArray = explode(" ",$search_value);
        $countSearchArray = count($searchArray);
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

        $query = "SELECT DISTINCT(`hotel_id`) FROM `bb_hotel_details` WHERE ".$bindParams ;
        $qh = $this->con->getQueryHandler($query, array());
        $data = array();
        while($res = $qh->fetch(PDO::FETCH_ASSOC)) {
            $data[] = intval($res['hotel_id']);
        }
        $hotel_ids = implode(",",$data);
        return $data;
    }

}