<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include '../include/Connection.class.php';
include '../include/Admin.class.php';

$type = $_POST['type'];

$adminObj = new Admin();
$data = array();
$country_code = isset($_POST['country_code'])?$_POST['country_code']:"0";
if($type == "state") {
    $data = $adminObj->getState($country_code);
} else if($type == "city") {
    $district = $_POST['district'];
    $data = $adminObj->getCity($district, $country_code);
} else if($type == "city_id") {
    $city_ids = $_POST['city_ids'];
    $data = $adminObj->getHotelDetailsFromCityId($city_ids);
}

echo json_encode($data);