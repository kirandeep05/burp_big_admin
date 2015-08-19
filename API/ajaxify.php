<?php 
include_once '../admin/API/Restaurant.class.php';

$type = $_POST['type'];
$hotel_id = $_POST['hotel']
$dinning_id = $_POST['dinning']

if($type === 'quota'){
	$res = new Restaurant();
	$quota = $res -> getDinningQuota($hotel_id, $dinning_id);
	return $quota;
}
?>