<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include '../include/Connection.class.php';
include '../include/Admin.class.php';
include './header.php'; 
$error = "";
$adminObj = new Admin();
$hotel_id = isset($_GET['hotel_id'])?$_GET['hotel_id']:(isset($_POST['hotel_id'])?$_POST['hotel_id']:"");
if(isset($_POST['form_submit'])) {
    $rest_id = isset($_POST['rest_id'])?$_POST['rest_id']:"";
    $hotel_name = isset($_POST['hotel_name'])?$_POST['hotel_name']:"";
    $rest_desc = isset($_POST['rest_desc'])?$_POST['rest_desc']:"";
    $type = isset($_POST['type'])?$_POST['type']:"";
    $address = isset($_POST['address'])?$_POST['address']:"";
    $area = isset($_POST['area'])?$_POST['area']:"";
    $city = isset($_POST['city'])?$_POST['city']:"";
    $state = isset($_POST['state'])?$_POST['state']:"";
    $country = isset($_POST['country'])?$_POST['country']:"";
    $phone_main = isset($_POST['main'])?$_POST['main']:"";
    $phone_alt = isset($_POST['alt'])?$_POST['alt']:"";
    $cuisine = isset($_POST['cuisine'])?$_POST['cuisine']:array();
    $serves = isset($_POST['serves'])?$_POST['serves']:array();
    $alcohol = isset($_POST['alcohol'])?$_POST['alcohol']:"";
    $seating = isset($_POST['seating'])?$_POST['seating']:array();
    $menu_sel = isset($_POST['menu_sel'])?$_POST['menu_sel']:array();
    $alacarte_check = isset($_POST['alacarte_check'])?$_POST['alacarte_check']:array();
    $buffet_check = isset($_POST['buffet_check'])?$_POST['buffet_check']:array();
    $banquet = isset($_POST['banquet'])?$_POST['banquet']:"";
    $opening_time = isset($_POST['opening_time'])?$_POST['opening_time']:"";
    $closing_time = isset($_POST['closing_time'])?$_POST['closing_time']:"";
    $visitor_attraction = isset($_POST['rest_known_for'])?$_POST['rest_known_for']:"";
    $delivery = isset($_POST['delivery'])?$_POST['delivery']:"";
    $value_for_2 = isset($_POST['value_for_two'])?$_POST['value_for_two']:"";
    $alacarte_menu = $_FILES['alacarte_menu'];
    $buffet_menu = $_FILES['buffet_menu'];
    $bar_menu = $_FILES['bar_menu'];
    $banquet_images = $_FILES['banquet_image'];
    $gallery_images = $_FILES['gallery'];
    $cover_pic = $_FILES['cover_pic'];
    $zip_code = isset($_POST['zip_code'])?$_POST['zip_code']:""; 
    $happy_hours = isset($_POST['happy_hours'])?$_POST['happy_hours']:"";
    $wifi = isset($_POST['wifi'])?$_POST['wifi']:"";
    $airconditioned = isset($_POST['airconditioned'])?$_POST['airconditioned']:"";
    $booking = isset($_POST['booking'])?$_POST['booking']:"";
    if($happy_hours == "yes") {
        $hh_opening_time = isset($_POST['hh_opening_time'])?$_POST['hh_opening_time']:"";
        $hh_closing_time = isset($_POST['hh_closing_time'])?$_POST['hh_closing_time']:"";
    } else {
        $hh_opening_time = "";
        $hh_closing_time = "";
    }
    $takeaway = isset($_POST['takeaway'])?$_POST['takeaway']:"no";
    
    $city_hide = isset($_POST['city_hide'])?$_POST['city_hide']:"";
    $state_hide = isset($_POST['state_hide'])?$_POST['state_hide']:"";
    $country_hide = isset($_POST['country_hide'])?$_POST['country_hide']:"";
    $type_hide = isset($_POST['type_hide'])?$_POST['type_hide']:"";
    $cuisine_hide = isset($_POST['cuisine_hide'])?$_POST['cuisine_hide']:"";
    $seating_hide = isset($_POST['seating_hide'])?$_POST['seating_hide']:"";
    
    $field_id[] = "1";
    $field_id[] = "2";
    $field_id[] = "3";
    $field_id[] = "4";
    $field_id[] = "5";
    $field_id[] = "6";
    $field_id[] = "7";
    $field_id[] = "8";
    $field_id[] = "9";
    $field_id[] = "10";
    $field_id[] = "11";
    $field_id[] = "12";
    $field_id[] = "13";
    $field_id[] = "14";
    $field_id[] = "15";
    $field_id[] = "16";
    $field_id[] = "17";
    $field_id[] = "18";
    $field_id[] = "19";
    $field_id[] = "20";
    $field_id[] = "21";
    $field_id[] = "22";
    $field_id[] = "23";
    $field_id[] = "24";
    $field_id[] = "25";
    $field_id[] = "26";
    $field_id[] = "27";
    $field_id[] = "28";
    $field_id[] = "29";
    $field_id[] = "30";
    
    $field_val[] = $hotel_name;
    $field_val[] = trim($rest_desc);
    $field_val[] = $type;
    $field_val[] = $address;
    $field_val[] = $area;
    $field_val[] = $city;
    $field_val[] = $state;
    $field_val[] = $country;
    $field_val[] = $phone_main;
    $field_val[] = $phone_alt;
    $field_val[] = implode(",",$serves);
    $field_val[] = $alcohol;
    $field_val[] = implode(",",$seating);
    $field_val[] = implode(",",$menu_sel);
    $field_val[] = $banquet;
    $field_val[] = $opening_time;
    $field_val[] = $closing_time;
    $field_val[] = $visitor_attraction;
    $field_val[] = implode(",", $cuisine);
    $field_val[] = $delivery;
    $field_val[] = $value_for_2;
    $field_val[] = $cover_pic;
    $field_val[] = $zip_code;
    $field_val[] = $happy_hours;
    $field_val[] = $wifi;
    $field_val[] = $airconditioned;
    $field_val[] = $hh_opening_time;
    $field_val[] = $hh_closing_time;
    $field_val[] = $takeaway;
    $field_val[] = $booking;
    
    $field_norm[] = $hotel_name;
    $field_norm[] = $rest_desc;
    $field_norm[] = $type_hide;
    $field_norm[] = $address;
    $field_norm[] = $area;
    $field_norm[] = $city_hide;
    $field_norm[] = $state_hide;
    $field_norm[] = $country_hide;
    $field_norm[] = $phone_main;
    $field_norm[] = $phone_alt;
    $field_norm[] = implode(",",$serves);
    $field_norm[] = $alcohol;
    $field_norm[] = $seating_hide;
    $field_norm[] = implode(",",$menu_sel);
    $field_norm[] = $banquet;
    $field_norm[] = $opening_time;
    $field_norm[] = $closing_time;
    $field_norm[] = $visitor_attraction;
    $field_norm[] = $cuisine_hide;
    $field_norm[] = $delivery;
    $field_norm[] = $value_for_2;
    $field_norm[] = $cover_pic;
    $field_norm[] = $zip_code;
    $field_norm[] = $happy_hours;
    $field_norm[] = $wifi;
    $field_norm[] = $airconditioned;
    $field_norm[] = $hh_opening_time;
    $field_norm[] = $hh_closing_time;
    $field_norm[] = $takeaway;
    $field_norm[] = $booking;
    //var_dump($field_val);
    
//    if($rest_name != "") {
        //$check_rest = $adminObj->checkRestaurant($rest_name);
        $check_rest = array();
        if(empty($check_rest)) {
          // $rest_id = $adminObj->insertRestaurant($rest_name);
           //$check_hotel = $adminObj->checkHotel($hotel_name);
            $check_hotel = array();
           if(empty($check_hotel)) {
                $adminObj->updateHotel($hotel_name, $hotel_id,$rest_id);                
                for($i=0;$i<count($field_id);$i++) {
                    //var_dump(array($hotel_id, $field_id[$i], $field_val[$i]));
                    if($field_id[$i] == "2") {
                        $field_val[$i] = trim($field_val[$i]);
                        $tag_arr = explode(",",$field_val[$i]);
                        $adminObj->deleteTagHotelXref($hotel_id);
                        foreach($tag_arr as $tag_name) {
                            $tag_name = trim($tag_name);
                            $tag_id = $adminObj->checkTagName($tag_name);
                            if($tag_id == 0) {
                                $tag_id = $adminObj->insertTagName($tag_name);
                            }

                            if($adminObj->checkTagHotelXref($tag_id, $hotel_id)) {
                                $adminObj->insertTagHotelXref($tag_id, $hotel_id);
                            }
                        }

                     }
                    if($adminObj->getHotelDetailsID($hotel_id, $field_id[$i])) {
                        
                        if($field_id[$i] == "22") {
                            $ac_image_path = $adminObj->uploadFile($field_val[$i], "cover_pic");
                            $adminObj->updateHotelDetails($hotel_id, $field_id[$i], $ac_image_path,$ac_image_path);
                        } else {
                            $adminObj->updateHotelDetails($hotel_id, $field_id[$i], $field_val[$i],$field_norm[$i]);
                        }
                    } else {
                        if($field_id[$i] == "22") {
                            $ac_image_path = $adminObj->uploadFile($field_val[$i], "cover_pic");
                            $adminObj->insertHotelDetails($hotel_id, $field_id[$i], $ac_image_path,$ac_image_path);
                        } else {
                           $adminObj->insertHotelDetails($hotel_id, $field_id[$i], $field_val[$i],$field_norm[$i]);
                        }
                    }
                }
                if(!(count($alacarte_menu['name']) == 1 && $alacarte_menu['name'][0] == '')) {
            for($j=0;$j<count($alacarte_menu['name']);$j++) {
                $alacarte_file['name'] = $alacarte_menu['name'][$j];
                $alacarte_file['type'] = $alacarte_menu['type'][$j];
                $alacarte_file['tmp_name'] = $alacarte_menu['tmp_name'][$j];
                $alacarte_file['error'] = $alacarte_menu['error'][$j];
                $alacarte_file['size'] = $alacarte_menu['size'][$j];
                $ac_image_path = $adminObj->uploadFile($alacarte_file, "alacarte");
                if($ac_image_path != "-1") {
                    $adminObj->insertHotelMenu(1, $ac_image_path, $hotel_id);
                }
            }
             
         }
         
         if(!(count($buffet_menu['name']) == 1 && $buffet_menu['name'][0] == '')) {         
            for($j=0;$j<count($buffet_menu['name']);$j++) {
                $buffet_file['name'] = $buffet_menu['name'][$j];
                $buffet_file['type'] = $buffet_menu['type'][$j];
                $buffet_file['tmp_name'] = $buffet_menu['tmp_name'][$j];
                $buffet_file['error'] = $buffet_menu['error'][$j];
                $buffet_file['size'] = $buffet_menu['size'][$j];
                $bf_image_path = $adminObj->uploadFile($buffet_file, "buffet");
                if($bf_image_path != "-1") {
                    $adminObj->insertHotelMenu(2, $bf_image_path, $hotel_id);
                }
            }
         }

         if(!(count($bar_menu['name']) == 1 && $bar_menu['name'][0] == '')) {
            for($j=0;$j<count($bar_menu['name']);$j++) {
                $bar_file['name'] = $bar_menu['name'][$j];
                $bar_file['type'] = $bar_menu['type'][$j];
                $bar_file['tmp_name'] = $bar_menu['tmp_name'][$j];
                $bar_file['error'] = $bar_menu['error'][$j];
                $bar_file['size'] = $bar_menu['size'][$j];
                $bar_image_path = $adminObj->uploadFile($bar_file, "bar");
                if($bar_image_path != "-1") {
                    $adminObj->insertHotelMenu(3, $bar_image_path, $hotel_id);
                }
            }
         }
         
         if(!(count($gallery_images['name']) == 1 && $gallery_images['name'][0] == '')) {         
            for($j=0;$j<count($gallery_images['name']);$j++) {
                $gallery_file['name'] = $gallery_images['name'][$j];
                $gallery_file['type'] = $gallery_images['type'][$j];
                $gallery_file['tmp_name'] = $gallery_images['tmp_name'][$j];
                $gallery_file['error'] = $gallery_images['error'][$j];
                $gallery_file['size'] = $gallery_images['size'][$j];
                $gall_image_path = $adminObj->uploadFile($gallery_file, "gallery");
                if($gall_image_path != "-1") {
                    $adminObj->insertGallery($gall_image_path, $hotel_id);
                }
            }
         }

         if($banquet == "Yes") {
            if(!(count($banquet_images['name']) == 1 && $banquet_images['name'][0] == '')) {             
                for($j=0;$j<count($banquet_images['name']);$j++) {
                    $banquet_file['name'] = $banquet_images['name'][$j];
                    $banquet_file['type'] = $banquet_images['type'][$j];
                    $banquet_file['tmp_name'] = $banquet_images['tmp_name'][$j];
                    $banquet_file['error'] = $banquet_images['error'][$j];
                    $banquet_file['size'] = $banquet_images['size'][$j];
                    $image_path = $adminObj->uploadFile($banquet_file, "banquet");
                    if($image_path != "-1") {
                        $adminObj->insertBanquetImage($image_path, $hotel_id);
                    }
                }
            }
         }

                
                $ala_bf = 0;
                $ala_lunch = 0;
                $ala_dinner = 0;
                $buffet_bf = 0;
                $buffet_lunch = 0;
                $buffet_dinner = 0;
                foreach($menu_sel as $menu_sel_temp) {
                    if($menu_sel_temp == "alacarte") {
                        foreach($alacarte_check as $alacarte_check_temp) {
                            if($alacarte_check_temp == "Breakfast") {
                                $ala_bf = 1;
                            } else if($alacarte_check_temp == "Lunch") {
                                $ala_lunch = 1;
                            } else if($alacarte_check_temp == "Dinner") {
                                $ala_dinner = 1;
                            }
                        }
                    } else if($menu_sel_temp == "Buffet") {
                        foreach($buffet_check as $buffet_check_temp) {
                            if($buffet_check_temp == "Breakfast") {
                                $buffet_bf = 1;
                            } else if($buffet_check_temp == "Lunch") {
                                $buffet_lunch = 1;
                            } else if($buffet_check_temp == "Dinner") {
                                $buffet_dinner = 1;
                            }
                        }
                    }
                }
                $adminObj->updateMenuSelection(1, $ala_bf, $ala_lunch, $ala_dinner,$hotel_id);
                $adminObj->updateMenuSelection(2, $buffet_bf, $buffet_lunch, $buffet_dinner,$hotel_id);
                
           } else {
               $error = "Hotel name exists";
           }
        } else {
            $error = "Restaurant name exists";
        }
}

$script = array();
$rest_name_arr = $adminObj->getRestaurantName($hotel_id);
$hotel_name = $rest_name_arr['hotel_name'];
$rest_name = $rest_name_arr['rest_name'];
$details = $adminObj->getHotelDetails($hotel_id);
$seating_arr = array();
$booking_yes_checked = $booking_no_checked = $hh_yes_checked = $ta_yes_checked = $ta_no_checked = $visitor_attraction = $cover_pic_data = $value_for_2 = $zip_code_data = $hh_no_checked = $wifi_yes_checked = $wifi_no_checked = $airconditioned_data = $ac_yes_checked = $ac_no_checked = $hh_opening_time_data = $hh_closing_time_data = $dhaba_checked = $rest_checked = $cafe_checked = $club_checked = $des_checked = $pub_checked = $cas_checked = $fine_checked = $veg_checked = $nonveg_checked = $alcohol_yes = $alcohol_no = $dine_checked = $roof_checked = $lounge_checked = $bar_checked = $rapid_checked = $outdoor_checked = $alacarte_check = $buffet_check = $al_bf_check = $al_lunch_check = $al_dinner_check = $buffet_bf_check = $buffet_lunch_check = $buffet_dinner_check = $banquet_yes_check = $banquet_no_check = $delivery_yes = $delivery_no = "";
foreach($details as $detail) {
    if($detail['hotel_field_id'] == 2) {
        $rest_desc = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 3) {
        $type = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 4) {
        $address = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 5) {
        $area = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 6) {
        $city_id = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 7) {
        $state_id = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 8) {
        $country_id = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 9) {
        $main_phone = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 10) {
        $alt_phone = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 11) {
        $serves_arr = explode(",",$detail['hotel_field_val']);
        foreach ($serves_arr as $serves) {
            if($serves == "Veg") {
                $veg_checked = "checked";
            } else {
                $nonveg_checked = "checked";
            }
        }
    } else if($detail['hotel_field_id'] == 12) {
        $alcohol = $detail['hotel_field_val'];
        if($alcohol == "yes") {
            $alcohol_yes = "checked";
        } else {
            $alcohol_no = "checked";
        }
    } else if($detail['hotel_field_id'] == 13) {
        $seating_arr = explode(",",$detail['hotel_field_val']);        
    } else if($detail['hotel_field_id'] == 14) {
        $menu_sel_arr = explode(",",$detail['hotel_field_val']);
        foreach($menu_sel_arr as $menu_sel) {
            if($menu_sel == "alacarte") {
                $alacarte_check = "checked";
                $menu_sel_arr_temp = $adminObj->getMenuSelection("1", $hotel_id);
                if($menu_sel_arr_temp['breakfast'] == "1") {
                    $al_bf_check = "checked";
                }
                if($menu_sel_arr_temp['lunch'] == "1") {
                    $al_lunch_check = "checked";
                }
                
                if($menu_sel_arr_temp['dinner'] == "1") {
                    $al_dinner_check = "checked";
                }
                $script[] = "<script>$(document).ready(function(){ $('#alacarte_check').toggle(); });</script>";
            } else {
                $buffet_check = "checked";
                $menu_sel_arr_temp = $adminObj->getMenuSelection("2", $hotel_id);
                if($menu_sel_arr_temp['breakfast'] == "1") {
                    $buffet_bf_check = "checked";
                } 
                
                if($menu_sel_arr_temp['lunch'] == "1") {
                    $buffet_lunch_check = "checked";
                }  
                
                if($menu_sel_arr_temp['dinner'] == "1") {
                    $buffet_dinner_check = "checked";
                }
                $script[] = "<script>$(document).ready(function(){ $('#buffet_check').toggle(); });</script>";
            }
        }
    } else if($detail['hotel_field_id'] == 15) {
        $banquet = $detail['hotel_field_val'];
        if ($banquet == "Yes") {
            $banquet_yes_check = "checked";
        } else {
            $banquet_no_check = "checked";
        }
    } else if($detail['hotel_field_id'] == 16) {
        $opening_time = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 17) {
        $closing_time = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 18) {
        $visitor_attraction = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 19) {
        $cuisines_arr = explode(",",$detail['hotel_field_val']);
    } else if($detail['hotel_field_id'] == 20) {
        $delivery_check = $detail['hotel_field_val'];
        if($delivery_check == "yes") {
          $delivery_yes = "checked";  
        } else {
          $delivery_no = "checked";  
        }
    } else if($detail['hotel_field_id'] == 21) {
        $value_for_2 = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 22) {
        $cover_pic_data = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 23) {
        $zip_code_data = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 24) {
        $happy_hours_data = $detail['hotel_field_val'];
        if($happy_hours_data == "yes"){
            $hh_yes_checked = "checked";
        } else {
            $hh_no_checked = "checked";
        }
    } else if($detail['hotel_field_id'] == 25) {
        $wifi_data = $detail['hotel_field_val'];
        if($wifi_data == "yes"){
            $wifi_yes_checked = "checked";
        } else {
            $wifi_no_checked = "checked";
        }
    } else if($detail['hotel_field_id'] == 26) {
        $airconditioned_data = $detail['hotel_field_val'];
        if($airconditioned_data == "yes"){
            $ac_yes_checked = "checked";
        } else {
            $ac_no_checked = "checked";
        }
    } else if($detail['hotel_field_id'] == 27) {
        $hh_opening_time_data = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 28) {
        $hh_closing_time_data = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 29) {
        $takeaway_data = $detail['hotel_field_val'];
        if($takeaway_data == "yes"){
            $ta_yes_checked = "checked";
        } else {
            $ta_no_checked = "checked";
        }
    } else if($detail['hotel_field_id'] == 30) {
        $booking_data = $detail['hotel_field_val'];
        if($booking_data == "1"){
            $booking_yes_checked = "checked";
        } else {
            $booking_no_checked = "checked";
        }
    }
} 


?>

<body>

    <div id="wrapper">

        <?php include './nav.php'; ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Restaurant</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Edit Restaurant
                        </div>
                        <div class="panel-body">
                             <?php if($error != "") {?>
                            <div class="alert alert-danger">
                             <?php echo $error; ?>
                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="edit_single_rest.php" id="create_form" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Name of the Hotel</label>
                                            <select  name="rest_id" class="form-control">
                                                <?php 
                                                $hotellist = $adminObj->getRestaurants(); 
                                                foreach($hotellist as $hotels) {
                                                    if($hotels['rest_name'] == $rest_name_arr['rest_name']) {
                                                        $check_hotel_name = "selected";
                                                    } else {
                                                        $check_hotel_name = "";
                                                    }
                                                ?>
                                                <option value="<?php echo $hotels['rest_id']; ?>" <?php echo $check_hotel_name; ?> ><?php echo $hotels['rest_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Type</label>
                                            <?php 
                                                $typelist = $adminObj->getEstablishmentType();
                                                foreach($typelist as $type_temp) {
                                                    if($type == $type_temp['est_id']) {
                                                        $checked_temp = "checked";
                                                    } else {
                                                        $checked_temp = "";
                                                    }
                                            ?>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios1" <?php echo $checked_temp; ?> value="<?php echo $type_temp['est_id'] ?>" ><span><?php echo $type_temp['est_name'] ?></span>
                                                </label>
                                            </div>
                                            
                                                <?php } ?>
                                            <input class="form-control" name="hotel_name" required="required" placeholder="Enter Restaurant/Cafe/Dhaba name" value="<?php echo $hotel_name ?>">
                                            <p class="help-block">Example "Chawla".</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Tags</label>
                                            <input class="form-control" name="rest_desc" data-role="tagsinput" value="<?php echo $rest_desc ?>" placeholder="Tags for Hotel">
                                            <p class="help-block">Example "Bakery".</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Visitor Attraction</label>
                                            <input class="form-control" name="rest_known_for" placeholder="What is the hotel known for ?" value="<?php echo $visitor_attraction ?>">
                                            <p class="help-block">Example "The must-go-to place in Chandigarh for THE Butter Chicken experience!".</p>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea class="form-control" name="address" required="required" placeholder="Enter Complete Address here" rows="3"><?php echo $address; ?></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Zip Code</label>
                                            <input class="form-control" name="zip_code" required="required" placeholder="Zip Code" value="<?php echo $zip_code_data; ?>" >                                            
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Area</label>
                                            <input class="form-control" name="area" required="required" placeholder="Enter the Area" value="<?php echo $area; ?>">
                                            <p class="help-block">Example "Sector-43A".</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Country</label>
                                            <select name="country" class="form-control">
                                                <option value="0">Select Country</option>
                                                <?php
                                                $country_arr = $adminObj->getCountry();
                                                foreach($country_arr as $country_temp) {
                                                    if($country_id == $country_temp['Code']) {
                                                ?>   
                                                <option value="<?php echo $country_temp['Code'] ?>" selected="selected"><?php echo $country_temp['Name'] ?></option>
                                                <?php
                                                    } else {
                                                ?>
                                                    <option value="<?php echo $country_temp['Code'] ?>"><?php echo $country_temp['Name'] ?></option>
                                                <?php 
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>State</label>
                                            <select name="state" class="form-control">
                                               <option value="0">Select State</option> 
                                               <?php 
                                               $state_arr = $adminObj->getState($country_id);
                                                foreach($state_arr as $state_temp) {
                                                    if($state_id == $state_temp['District']) {
                                                ?>
                                               <option value="<?php echo $state_temp['District'] ?>" selected="selected"><?php echo $state_temp['District'] ?></option>
                                                <?php
                                                    } else {
                                                ?>
                                                    <option value="<?php echo $state_temp['District'] ?>"><?php echo $state_temp['District'] ?></option>
                                                <?php 
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                                                                
                                        <div class="form-group">
                                            <label>City</label>
                                            <select name="city" class="form-control">  
                                                <option value="0">Select City</option>
                                                <?php 
                                               $city_arr = $adminObj->getCity($state_id, $country_id);
                                                foreach($city_arr as $city_temp) {
                                                    if($city_id == $city_temp['ID']) {
                                                ?>
                                               <option value="<?php echo $city_temp['ID'] ?>" selected="selected"><?php echo $city_temp['Name'] ?></option>
                                                <?php
                                                    } else {
                                                ?>
                                                    <option value="<?php echo $city_temp['ID'] ?>"><?php echo $city_temp['Name'] ?></option>
                                                <?php 
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input name="main" class="form-control" placeholder="Main Number" value="<?php echo $main_phone; ?>"> <br>
                                            <input name="alt" class="form-control" placeholder="Alternate Number" value="<?php echo $alt_phone; ?>">                                           
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Cuisine</label>
                                            <select  name="cuisine[]" multiple class="form-control">
                                                <?php 
                                                $cuisinelist = $adminObj->getCuisines(); 
                                                foreach($cuisinelist as $cuisine) {
                                                    if(in_array($cuisine['id'], $cuisines_arr)) {                                                       
                                                ?>
                                                    <option value="<?php echo $cuisine['id']; ?>" selected><?php echo $cuisine['name']; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $cuisine['id']; ?>"><?php echo $cuisine['name']; ?></option>
                                                <?php        } 
                                                }?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Serves</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="serves[]" type="checkbox" value="Veg" <?php echo $veg_checked; ?>>Veg
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="serves[]" type="checkbox" value="Non-Veg" <?php echo $nonveg_checked; ?>>Non-Veg
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Alcohol</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="alcohol" id="optionsRadios1" value="yes" <?php echo $alcohol_yes; ?>>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="alcohol" id="optionsRadios2" value="no" <?php echo $alcohol_no; ?>>No
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Take away</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="takeaway" id="optionsRadios1" value="yes" <?php echo $ta_yes_checked; ?>>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="takeaway" id="optionsRadios2" value="no" <?php echo $ta_no_checked; ?> >No
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Happy Hours</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="happy_hours" id="optionsRadios1" value="yes" <?php echo $hh_yes_checked ?> >Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="happy_hours" id="optionsRadios2" value="no" <?php echo $hh_no_checked ?> >No
                                                </label>
                                            </div>
                                            <div id="hh_time">
                                                <div class="form-group">
                                                    <label>Opening Time</label>
                                                    <input required="required" name="hh_opening_time" class="form-control" id="hh_opening_time" value="<?php echo $hh_opening_time_data ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label>Closing Time</label>
                                                    <input required="required" name="hh_closing_time" class="form-control" id="hh_closing_time" value="<?php echo $hh_closing_time_data ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Wifi</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="wifi" id="optionsRadios1" value="yes" <?php echo $wifi_yes_checked ?>>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="wifi" id="optionsRadios2" value="no" <?php echo $wifi_no_checked ?>>No
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Air Conditioned</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="airconditioned" id="optionsRadios1" value="yes" <?php echo $ac_yes_checked ?>>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="airconditioned" id="optionsRadios2" value="no"  <?php echo $ac_no_checked ?>>No
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Delivery</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="delivery" id="optionsRadios1" value="yes" <?php echo $delivery_yes; ?>>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="delivery" id="optionsRadios2" value="no" <?php echo $delivery_no; ?>>No
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Booking</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="booking" id="optionsRadios1" value="1"  <?php echo $booking_yes_checked; ?>>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="booking" id="optionsRadios2" value="0" <?php echo $booking_no_checked; ?>>No
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Seating</label>
                                            <?php 
                                            $seating_arr_temp = $adminObj->getSeating(); 
                                            foreach($seating_arr_temp as $seating) {
                                                if(in_array($seating['seating_id'], $seating_arr)) {
                                                    $checked = "checked";
                                                } else {
                                                    $checked = "";
                                                }
                                            ?>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="<?php echo $seating['seating_id'] ?>" <?php echo $checked ?>><span><?php echo $seating['seating_name'] ?></span>
                                                </label>
                                            </div>                                            
                                            <?php } ?>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Menu Selection</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="menu_sel[]" onclick="displayMenuSel('alacarte_check')" value="alacarte" <?php echo $alacarte_check ?>>À la carte
                                                </label>
                                            </div>
                                            
                                            <div id="alacarte_check" style="padding-left: 50px; display: none;">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="alacarte_check[]" value="Breakfast" <?php echo $al_bf_check ?>>Breakfast
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="alacarte_check[]" value="Lunch" <?php echo $al_lunch_check ?>>Lunch
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="alacarte_check[]" value="Dinner" <?php echo $al_dinner_check ?>>Dinner
                                                </label>
                                                
                                            </div>
                                            
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="menu_sel[]"  onclick="displayMenuSel('buffet_check')" value="Buffet" <?php echo $buffet_check ?>>Buffet
                                                </label>
                                            </div>
                                            <div id="buffet_check" style="padding-left: 50px; display: none;">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="buffet_check[]" value="Breakfast" <?php echo $buffet_bf_check ?>>Breakfast
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="buffet_check[]" value="Lunch" <?php echo $buffet_lunch_check ?>>Lunch
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="buffet_check[]" value="Dinner" <?php echo $buffet_dinner_check ?>>Dinner
                                                </label>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Banquet</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="banquet" id="optionsRadios1" value="Yes" <?php echo $banquet_yes_check; ?>>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="banquet" id="optionsRadios2" value="No" <?php echo $banquet_no_check; ?>>No
                                                </label>
                                            </div>
                                            <input type="file" name="banquet_image[]" multiple="multiple" />
                                            <div id="links" >
                                            <?php 
                                            $banquet_images_arr = $adminObj->getBanquetImage($hotel_id);                                            
                                            foreach($banquet_images_arr as $banquet_image ) {
                                            ?>
                                            <div class="gall-img">
                                            <a href="../images/banquet/<?php echo $banquet_image['image_path']; ?>" title="" data-gallery>
                                                <img src="../images/banquet/<?php echo $banquet_image['image_path']; ?>" alt="">
                                            </a> <br />
                                            <button style="margin-top: 5px;" onclick="deleteImage('<?php echo $banquet_image['banquet_id'] ?>','banquet')">Delete</button>
                                            </div>
                                            <?php } ?>  
                                                <div style="clear: both"></div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label>Opening Time</label>
                                            <input required="required" name="opening_time" class="form-control" id="opening_time" value="<?php echo $opening_time ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Closing Time</label>
                                            <input required="required" name="closing_time" class="form-control" id="closing_time" value="<?php echo $closing_time ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Value for 2</label>
                                            <input name="value_for_two" class="form-control" placeholder="Enter Amount" value="<?php echo $value_for_2 ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Upload Cover Pic</label> <br/>
                                            <input name="cover_pic" type="file"> 
                                            <div id="links" >
                                            <?php                                             
                                            if($cover_pic_data != "") {
                                            ?>
                                            <div class="gall-img">
                                            <a href="../images/cover_pic/<?php echo $cover_pic_data ?>" title="" data-gallery>
                                                <img src="../images/cover_pic/<?php echo $cover_pic_data; ?>" alt="">
                                            </a> <br />                                            
                                            </div>
                                            <?php } ?>  
                                                <div style="clear: both">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>All Menu</label> <br/>
                                            <label>À la carte</label> <input name="alacarte_menu[]" type="file" multiple="multiple">
                                            <div id="links" >
                                            <?php 
                                            $alacarte_images_arr = $adminObj->getMenu($hotel_id,"1");                                            
                                            foreach($alacarte_images_arr as $alacarte_image) {
                                            ?>
                                            <div class="gall-img">
                                            <a href="../images/alacarte/<?php echo $alacarte_image['image_path']; ?>" title="" data-gallery>
                                                <img src="../images/alacarte/<?php echo $alacarte_image['image_path']; ?>" alt="">
                                            </a> <br />
                                            <button style="margin-top: 5px;" onclick="deleteImage('<?php echo $alacarte_image['menu_id'] ?>','alacarte')">Delete</button>
                                            </div>
                                            <?php } ?>  
                                                <div style="clear: both">
                                            </div>
                                            <label>Buffet</label> <input name="buffet_menu[]" type="file" multiple="multiple">
                                            <div id="links" >
                                            <?php 
                                            $buffet_images_arr = $adminObj->getMenu($hotel_id,"2");
                                            foreach($buffet_images_arr as $buffet_image) {
                                            ?>
                                                <div class="gall-img">
                                                <a href="../images/buffet/<?php echo $buffet_image['image_path']; ?>" title="" data-gallery>
                                                    <img src="../images/buffet/<?php echo $buffet_image['image_path']; ?>" alt="">
                                                </a> <br />
                                                <button style="margin-top: 5px;" onclick="deleteImage('<?php echo $buffet_image['menu_id'] ?>','buffet')">Delete</button>
                                                </div>
                                            <?php } ?>  
                                                <div style="clear: both">
                                            </div>
                                            <label>Bar</label> <input name="bar_menu[]" type="file" multiple="multiple">
                                            <div id="links" >
                                            <?php 
                                            $bar_images_arr = $adminObj->getMenu($hotel_id,"3");
                                            foreach($bar_images_arr as $bar_image) {
                                            ?>
                                                <div class="gall-img">
                                                    <a href="../images/bar/<?php echo $bar_image['image_path']; ?>" title="" data-gallery />
                                                    <img src="../images/bar/<?php echo $bar_image['image_path']; ?>" alt="" />
                                                    </a> <br />
                                                    <button style="margin-top: 5px;" onclick="deleteImage('<?php echo $bar_image['menu_id'] ?>','bar')">Delete</button>                                                                                                    
                                                </div>
                                            <?php } ?>
                                                <div style="clear: both">
                                        </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Gallery</label>
                                            <input type="file" name="gallery[]" multiple="multiple">                                            
                                        </div>
                                        <!--<div class="form-group">
                                            <button id="image-gallery-button" type="button" class="btn btn-primary btn-lg">
                                                <i class="glyphicon glyphicon-picture"></i>
                                                Launch Image Gallery
                                            </button>
                                        </div>-->
                                        <div id="links" >
                                            <?php 
                                            $gallery_images_arr = $adminObj->getGallery($hotel_id);
                                            foreach($gallery_images_arr as $gallery_image) {
                                            ?>
                                            <div class="gall-img">
                                            <a href="../images/gallery/<?php echo $gallery_image['image_path']; ?>" title="" data-gallery>
                                                <img src="../images/gallery/<?php echo $gallery_image['image_path']; ?>" alt="">
                                            </a> <br />
                                            <button style="margin-top: 5px;" onclick="deleteImage('<?php echo $gallery_image['gallery_id'] ?>','gallery')">Delete</button>  
                                            </div>
                                            <?php } ?>
                                            <div style="clear: both">
                                        </div>
                                        <input type="hidden" name="hotel_id" value="<?php echo $hotel_id ?>" />
                                        <input type="hidden" name="form_submit" value="1" />
                                        <input type="hidden" name="city_hide" />   
                                        <input type="hidden" name="state_hide" />   
                                        <input type="hidden" name="country_hide" />
                                        <input type="hidden" name="type_hide" />
                                        <input type="hidden" name="cuisine_hide" />
                                        <input type="hidden" name="seating_hide" />  
                                        <button type="submit" class="btn btn-default">Submit Button</button>
                                        <button id="cancel" class="btn btn-default">Cancel</button>
                                    </form>
                                </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script type="text/javascript" src="../js/bootstrap-timepicker.js"></script>
    <script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    <script src="../js/bootstrap-image-gallery.min.js"></script>
    <script src="../dist/js/bootstrap-tagsinput.js"></script>
    <script src="../dist/js/typeahead.bundle.js"></script>
    <script>
        function displayMenuSel(id)
        {
            $('#'+id).toggle();
        }
        
        $('#image-gallery-button').on('click', function (event) {
            event.preventDefault();
            blueimp.Gallery($('#links a'), $('#blueimp-gallery').data());
        });
        
        $('#cancel').on('click', function (event) {
            event.preventDefault();
            window.location.href = 'http://www.burpbig.com/admin/pages/edit_restaurant.php';
        });
    </script>
    <script type="text/javascript">
            $('#opening_time').timepicker({
                template: false,
                showInputs: false,
                minuteStep: 5
            });
            
            $('#closing_time').timepicker({
                template: false,
                showInputs: false,
                minuteStep: 5
            });
            
             $('#hh_opening_time').timepicker({
                template: false,
                showInputs: false,
                minuteStep: 5
            });
            
            $('#hh_closing_time').timepicker({
                template: false,
                showInputs: false,
                minuteStep: 5
            });
            
            $(document).ready(function(){
                    var alc_val = $("input[name=alcohol]:checked").val();
                    if(alc_val === "yes") {
                        $("input[name=happy_hours]:radio").prop("disabled",false);                    
                    } else {
                        $("input[name=happy_hours]:radio").prop("disabled",true);
                        $("#hh_time").hide();
                    }

                    var happy_hr = $("input[name=happy_hours]:checked").val();
                    if(happy_hr === "yes") {
                        $("input[name=happy_hours]:radio").prop("disabled",false);
                        $("#hh_time").show();
                    } else {                    
                        $("#hh_time").hide();
                    }


                    $("input[name=alcohol]:radio").change(function () {
                    var value = $(this).val();
                    if(value === "yes") {
                        $("input[name=happy_hours]:radio").prop("disabled",false);
                        $('input[name=happy_hours]:radio').filter('[value="yes"]').attr('checked', true);
                        $("#hh_time").show();
                    } else {
                        $('input[name=happy_hours]:radio').filter('[value="yes"]').attr('checked', false);
                        $('input[name=happy_hours]:radio').filter('[value="no"]').attr('checked', false);
                        $("input[name=happy_hours]:radio").prop("disabled",true);                    
                        $("#hh_time").hide();
                    }
                    });

                    $("input[name=happy_hours]:radio").change(function () {
                    var value = $(this).val();
                    if(value === "no") {
                        $("#hh_time").hide();
                    } else {
                        $("#hh_time").show();                    
                    }
                    });

                    $( "select[name=country]" ).change(function () {            
                    var country_code = $( "select[name=country] option:selected" ).val();
                    $.ajax({
                        method: "POST",
                        url: "location.php",
                        data: { type: "state", country_code: country_code }
                      })
                        .done(function( msg ) {
                          myOptions = $.parseJSON(msg) ;
                          mySelect = $( "select[name=state]" );
                          mySelect.html($('<option></option>').val("0").html("Select State"));
                            $.each(myOptions, function(val,text) {
                                //console.log(text.District);
                                 mySelect.append(
                                     $('<option></option>').val(text.District).html(text.District)
                                 );
                             });
                        });
                    });

                    $( "select[name=state]" ).change(function () {            
                    var country_code = $( "select[name=country] option:selected" ).val();
                    var state = $( "select[name=state] option:selected" ).val();
                    $.ajax({
                        method: "POST",
                        url: "location.php",
                        data: { type: "city", country_code: country_code, district: state }
                      })
                        .done(function( msg ) {
                          myOptions = $.parseJSON(msg) ;
                          mySelect = $( "select[name=city]" );
                          mySelect.html($('<option></option>').val("0").html("Select City"));
                            $.each(myOptions, function(val,text) {
                                //console.log(text);
                                 mySelect.append(
                                     $('<option></option>').val(text.ID).html(text.Name)
                                 );
                             });
                        });
                    });

                    $('#create_form').on('submit', function(e){
                        //e.preventDefault();
                        var country = $( "select[name=country] option:selected" ).html();
                        var state = $( "select[name=state] option:selected" ).html();
                        var city = $( "select[name=city] option:selected" ).html();
                        var type = $( "input[name=type]:checked" ).next().html();
                        var cuisine = new Array();
                        var seating = new Array();
                        $( "select[name='cuisine[]'] option:selected" ).each(function(i, selected){ 
                            cuisine.push($(selected).text()); 
                          });

                          $( "input[name='seating[]']:checked" ).each(function(i, selected){ 
                            seating.push($(selected).next().html()); 
                          });

                        var cuisine_join = cuisine.join(",");
                        var seating_join = seating.join(",");
                        
                        //console.log(country+"---"+city+"---"+state+"---"+type+"---"+cuisine_join+"---"+seating_join+"---")
                        
                        $("input[name=country_hide]").val(country);
                        $("input[name=state_hide]").val(state);
                        $("input[name=city_hide]").val(city);
                        $("input[name=type_hide]").val(type);
                        $("input[name=cuisine_hide]").val(cuisine_join);
                        $("input[name=seating_hide]").val(seating_join);
                    });
                
                
                    var citynames = new Bloodhound({
                      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                      queryTokenizer: Bloodhound.tokenizers.whitespace,
                      prefetch: {
                        url: 'tagnames.json',
                        filter: function(list) {
                          return $.map(list, function(cityname) {
                            return { name: cityname }; });
                        }
                      }
                    });
                    citynames.initialize();

//                    $('.bootstrap-tagsinput input:nth(0)').tagsinput({
//                      typeaheadjs: {
//                        name: 'citynames',
//                        displayKey: 'name',
//                        valueKey: 'name',
//                        source: citynames.ttAdapter()
//                      }
//                    });                                
             });     
            
            function deleteImage(image_id,image_type)
            {
                var bool = confirm('Do you want to delete this image ?');
                if(bool) {
                $.ajax({
                    method: "POST",
                    url: "ajax.php",
                    data: { image_id: image_id, image_type: image_type }
                  })
                    .done(function( msg ) {
                      alert( msg );
                    });
                }
            }
        </script>
    <?php
    foreach($script as $display) {
        echo $display;
    }
    ?>
</body>

</html>
