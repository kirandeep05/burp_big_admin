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

$type = $_POST["type"];
switch ($type) {
    case "quick_search": 
            $restObj = new Restaurant();
            $fixed = $restObj->getQuickSearch();
            $random = $restObj->getRandomCuisines();
                        
            echo str_replace("\/", "/", json_encode(array("fixed"=>$fixed ,"random" => $random)));
            
        break;
          
        
    default: 
        echo "Default";
        break;
}
