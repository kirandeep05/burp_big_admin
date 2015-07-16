<?php
 session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

header('Access-Control-Allow-Origin: *');

function __autoload($classname) {
    include $classname . ".class.php";
}
$restObj = new Restaurant();
$type = $_POST["type"];
switch ($type) {
    case "quick_search":            
            $fixed = $restObj->getQuickSearch();
            $random = $restObj->getRandomCuisines();
                        
            echo str_replace("\/", "/", json_encode(array("fixed"=>$fixed ,"random" => $random)));
            
        break;
     case "trending_ads": 
            $city_id = isset($_POST['city_id'])?$_POST['city_id']:"0";
            $city_id_arr = explode(",", $city_id);
            $trending_ads = $restObj->getTrendingAds($city_id_arr);
            if(empty($trending_ads)) {
                $log = "0";
            } else {
                $log = "1";
            }
            echo str_replace("\/", "/", json_encode(array("ads"=>$trending_ads,"log"=>$log)));
            
        break;     
        
    case "get_cities":            
        $cities = $restObj->getCities();
        
        echo str_replace("\/", "/", json_encode(array("cities"=>$cities )));

    break;
        
    default: 
        echo "Default";
        break;
}
