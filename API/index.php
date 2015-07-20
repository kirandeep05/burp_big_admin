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
        $groups = $restObj->getCitiesGroup();
        foreach($groups as $group) {
            $group_coallate[$group['group_name']][] = $group['city_id'] ;
        }
        
        foreach($group_coallate as $key=>$value) {
            $cities[] = array("city_id"=>implode(",",$value),"city_name"=>$key);
        }
        echo str_replace("\/", "/", json_encode(array("cities"=>$cities )));

    break;

    case "get_single_rest":            
        $hotel_id = isset($_POST['hotel_id'])?$_POST['hotel_id']:"0";
        $rest_details = $restObj->getSingleRestDetail(array($hotel_id));
        $url = Constants::WEB_URL;
        //var_dump($rest_detail);
        foreach($rest_details as $rest_detail) {
            $data['hotel_name'] = $rest_detail['hotel_name'];
            $data['cuisines'] = $restObj->getCuisineFromID($rest_detail['Cuisine']);
            $data['opening_time'] = $rest_detail['Opening Time'];
            $data['closing_time'] = $rest_detail['Closing Time'];
            $data['visitor_attraction'] = $rest_detail['Visitor Attraction'];
            $data['value_for_2'] = $rest_detail['Value for 2'];
            $data['address'] = $rest_detail['Address'];
            $data['main_phone'] = $rest_detail['Main Phone'];        
            $data['alt_phone'] = $rest_detail['Alternate Phone'];
            if($rest_detail['Cover Pic'] != "") {
                $data['cover_pic'] = $url."/admin/images/cover_pic/".$rest_detail['Cover Pic'];
            } else {
                $data['cover_pic'] = $rest_detail['Cover Pic'];
            }

            $available = array();
            $not_available = array();
            //$check = array($rest_detail['Alcohol'],$rest_detail['Banquet'],$rest_detail['Delivery'],$rest_detail['Take Away'], $rest_detail['Wifi'], $rest_detail['Air Conditioned']);
            foreach($rest_detail as $key => $value) {
                if(strtolower($value) == "yes") {
                    $available[] = $key;
                } else {
                    $not_availble[] = $key;
                }
            }
            $serves_arr = explode(",",$rest_detail['Serves']);
            if(count($serves_arr) <= 1) {
                if($serves_arr[0] == "Veg") {
                    $available[] = "Vegetarian";
                    $not_available[] = "Non-Vegetarian";
                } else {
                    $available[] = "Non-Vegetarian";
                    $not_available[] = "Vegetarian";
                }
            } else {
                $available[] = "Non-Vegetarian";
                $available[] = "Vegetarian";
            }
            $data['available'] = $available;
            $data['not_available'] = $not_available;
            $menu_arr = $restObj->getMenu($hotel_id);

            $data['menu']['A la Carte'] = array();
            $data['menu']['Buffet'] = array();
            $data['menu']['Bar'] = array();
            foreach($menu_arr as $menu) {
                if($menu['type_name'] == "A la Carte") {
                    $prepend = $url."/admin/images/alacarte/";
                } else if ($menu['type_name'] == "Buffet") {
                    $prepend = $url."/admin/images/buffet/";
                } else if ($menu['type_name'] == "Bar") {
                    $prepend = $url."/admin/images/bar/";
                }
                $data['menu'][$menu['type_name']][] = array("image_path"=>$prepend.$menu['image_path'],"image_id"=>$menu['menu_id']);
            }

            $data['Gallery'] = $restObj->getGallery($hotel_id);
            $data['Banquet'] = $restObj->getBanquet($hotel_id);
        }
        echo str_replace("\/", "/", json_encode($data));

    break;
    
    case "quick_search_result":
    
        $search_type = isset($_POST['search_type'])?$_POST['search_type']:"";
        $search_type_id = isset($_POST['search_type_id'])?$_POST['search_type_id']:"";
        if($search_type == "fixed") {
            if($search_type_id == "1") {
                $hotel_ids = $restObj->getHotelIDFromHotelDetails("20", "yes");                
            } else if($search_type_id == "2") {
                $hotel_ids = $restObj->getHotelIDFromMenuSel("breakfast", "1");                
            } else if($search_type_id == "3") {
                $hotel_ids = $restObj->getHotelIDFromMenuSel("lunch", "1");                
            } else if($search_type_id == "4") {
                $hotel_ids = $restObj->getHotelIDFromMenuSel("dinner", "1");                
            } else if($search_type_id == "5") {
                $hotel_ids = $restObj->getHotelIDFromHotelDetails("14", "%Buffet%");                
            }  else if($search_type_id == "6") {
                $hotel_ids = $restObj->getHotelIDFromHotelDetails("3", "6");                
            } else if($search_type_id == "7") {
                $hotel_ids = $restObj->getHotelIDFromHotelDetails("3", "3");                
            }
        } else {
            
        }
        if(empty($hotel_ids)) {
            $hotel_ids = array(0);
        }
        $final_data = array();
        $rest_details = $restObj->getSingleRestDetail($hotel_ids);
        $url = Constants::WEB_URL;
        //var_dump($rest_detail);
        foreach($rest_details as $rest_detail) {
            $hotel_id = $rest_detail['hotel_id'];
            $data['hotel_name'] = $rest_detail['hotel_name'];
            $data['cuisines'] = $restObj->getCuisineFromID($rest_detail['Cuisine']);
            $data['opening_time'] = $rest_detail['Opening Time'];
            $data['closing_time'] = $rest_detail['Closing Time'];
            $data['visitor_attraction'] = $rest_detail['Visitor Attraction'];
            $data['value_for_2'] = $rest_detail['Value for 2'];
            $data['address'] = $rest_detail['Address'];
            $data['main_phone'] = $rest_detail['Main Phone'];        
            $data['alt_phone'] = $rest_detail['Alternate Phone'];
            if($rest_detail['Cover Pic'] != "") {
                $data['cover_pic'] = $url."/admin/images/cover_pic/".$rest_detail['Cover Pic'];
            } else {
                $data['cover_pic'] = $rest_detail['Cover Pic'];
            }

            $available = array();
            $not_available = array();
            //$check = array($rest_detail['Alcohol'],$rest_detail['Banquet'],$rest_detail['Delivery'],$rest_detail['Take Away'], $rest_detail['Wifi'], $rest_detail['Air Conditioned']);
            foreach($rest_detail as $key => $value) {
                if(strtolower($value) == "yes") {
                    $available[] = $key;
                } else {
                    $not_availble[] = $key;
                }
            }
            $serves_arr = explode(",",$rest_detail['Serves']);
            if(count($serves_arr) <= 1) {
                if($serves_arr[0] == "Veg") {
                    $available[] = "Vegetarian";
                    $not_available[] = "Non-Vegetarian";
                } else {
                    $available[] = "Non-Vegetarian";
                    $not_available[] = "Vegetarian";
                }
            } else {
                $available[] = "Non-Vegetarian";
                $available[] = "Vegetarian";
            }
            $data['available'] = $available;
            $data['not_available'] = $not_available;
            $menu_arr = $restObj->getMenu($hotel_id);

            $data['menu']['A la Carte'] = array();
            $data['menu']['Buffet'] = array();
            $data['menu']['Bar'] = array();
            foreach($menu_arr as $menu) {
                if($menu['type_name'] == "A la Carte") {
                    $prepend = $url."/admin/images/alacarte/";
                } else if ($menu['type_name'] == "Buffet") {
                    $prepend = $url."/admin/images/buffet/";
                } else if ($menu['type_name'] == "Bar") {
                    $prepend = $url."/admin/images/bar/";
                }
                $data['menu'][$menu['type_name']][] = array("image_path"=>$prepend.$menu['image_path'],"image_id"=>$menu['menu_id']);
            }

            $data['Gallery'] = $restObj->getGallery($hotel_id);
            $data['Banquet'] = $restObj->getBanquet($hotel_id);
            $final_data[] = $data;
        }
        echo str_replace("\/", "/", json_encode($final_data));
        
    break;
    
    
    default: 
        echo "Default";
        break;
}
