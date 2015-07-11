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
    
    

    
}