<?php
include '../include/Connection.class.php';
include '../include/Admin.class.php';

$adminObj = new Admin();

$image_type = isset($_POST['image_type'])?$_POST['image_type']:"";
$image_id = isset($_POST['image_id'])?$_POST['image_id']:"";
if($image_type == "gallery") {
    $adminObj->deleteGallery($image_id);
    echo "Gallery Image deleted";
} else if($image_type == "alacarte") {
    $adminObj->deleteMenuImage($image_id, "1");
    echo "Alacarte Image deleted";
} else if($image_type == "buffet") {
    $adminObj->deleteMenuImage($image_id, "2");
    echo "Buffet Image deleted";
} else if($image_type == "bar") {
    $adminObj->deleteMenuImage($image_id, "3");
    echo "Bar Image deleted";
} else if($image_type == "banquet") {
    $adminObj->deleteBanquetImage($image_id);
    echo "Banquet Image deleted";
} else if($image_type == "hotel") {
    $adminObj->deleteHotel($image_id);
    echo "Hotel deleted";
}