<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include '../include/Connection.class.php';
include '../include/Admin.class.php';
include './header.php'; 
$adminObj = new Admin();

if(isset($_POST['form_submit'])) {
    $rest_name = isset($_POST['rest_name'])?$_POST['rest_name']:"";
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
    $cuisine = isset($_POST['cuisine'])?$_POST['cuisine']:"";
    $serves = isset($_POST['serves'])?$_POST['serves']:"";
    $alcohol = isset($_POST['alcohol'])?$_POST['alcohol']:"";
    $seating = isset($_POST['seating'])?$_POST['seating']:"";
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
    
    $field_val[] = $rest_desc;
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
    
    
    if($rest_name != "") {
        $check_rest = $adminObj->checkRestaurant($rest_name);
        if(empty($check_rest)) {
           $rest_id = $adminObj->insertRestaurant($rest_name);
           $check_hotel = $adminObj->checkHotel($hotel_name);
           if(empty($check_hotel)) {
                $hotel_id = $adminObj->insertHotel($hotel_name, $rest_id);
                for($i=0;$i<count($field_id);$i++) {
                    $adminObj->insertHotelDetails($hotel_id, $field_id[$i], $field_val[$i]);
                }
                for($j=0;$j<count($alacarte_menu['name']);$j++) {
                    $alacarte_file['name'] = $alacarte_menu['name'][$j];
                    $alacarte_file['type'] = $alacarte_menu['type'][$j];
                    $alacarte_file['tmp_name'] = $alacarte_menu['tmp_name'][$j];
                    $alacarte_file['error'] = $alacarte_menu['error'][$j];
                    $alacarte_file['size'] = $alacarte_menu['size'][$j];
                    $image_path = $adminObj->uploadFile($alacarte_file, "alacarte");
                    if($image_path != "-1") {
                        $adminObj->insertHotelMenu(1, $image_path, $hotel_id);
                    }
                }
                
                for($j=0;$j<count($buffet_menu['name']);$j++) {
                    $buffet_file['name'] = $buffet_menu['name'][$j];
                    $buffet_file['type'] = $buffet_menu['type'][$j];
                    $buffet_file['tmp_name'] = $buffet_menu['tmp_name'][$j];
                    $buffet_file['error'] = $buffet_menu['error'][$j];
                    $buffet_file['size'] = $buffet_menu['size'][$j];
                    $image_path = $adminObj->uploadFile($buffet_file, "buffet");
                    if($image_path != "-1") {
                        $adminObj->insertHotelMenu(2, $image_path, $hotel_id);
                    }
                }
                
                for($j=0;$j<count($bar_menu['name']);$j++) {
                    $bar_file['name'] = $bar_menu['name'][$j];
                    $bar_file['type'] = $bar_menu['type'][$j];
                    $bar_file['tmp_name'] = $bar_menu['tmp_name'][$j];
                    $bar_file['error'] = $bar_menu['error'][$j];
                    $bar_file['size'] = $bar_menu['size'][$j];
                    $image_path = $adminObj->uploadFile($bar_file, "bar");
                    if($image_path != "-1") {
                        $adminObj->insertHotelMenu(3, $image_path, $hotel_id);
                    }
                }
                
                if($banquet == "Yes") {
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
                $adminObj->insertMenuSelection(1, $ala_bf, $ala_lunch, $ala_dinner,$hotel_id, '1');
                $adminObj->insertMenuSelection(2, $buffet_bf, $buffet_lunch, $buffet_dinner,$hotel_id, '1');
                
           } else {
               $error = "Hotel name exists";
           }
        } else {
            $error = "Restaurant name exists";
        }
    } else {
        $error = "Restaurant name cannot be empty";
    }
}

$hotel_id = isset($_GET['hotel_id'])?$_GET['hotel_id']:"-1";
$rest_name_arr = $adminObj->getRestaurantName($hotel_id);
$hotel_name = $rest_name_arr['hotel_name'];
$rest_name = $rest_name_arr['rest_name'];
$details = $adminObj->getHotelDetails($hotel_id);
$dhaba_checked = $rest_checked = $cafe_checked = $veg_checked = $nonveg_checked = $alcohol_yes = $alcohol_no = $dine_checked = $roof_checked = $lounge_checked = $bar_checked = $outdoor_checked = $alacarte_check = $buffet_check = $al_bf_check = $al_lunch_check = $al_dinner_check = $buffet_bf_check = $buffet_lunch_check = $buffet_dinner_check = $banquet_yes_check = $banquet_no_check = "";
foreach($details as $detail) {
    if($detail['hotel_field_id'] == 2) {
        $rest_desc = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 3) {
        $type = $detail['hotel_field_val'];
        if($type == "Dhaba") {
            $dhaba_checked = "checked";
        } else if($type == "Restaurant") {
            $rest_checked = "checked";
        } else if($type == "Cafe") {
            $cafe_checked = "checked";
        }
    } else if($detail['hotel_field_id'] == 4) {
        $address = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 5) {
        $area = $detail['hotel_field_val'];
    } else if($detail['hotel_field_id'] == 6) {
        
    } else if($detail['hotel_field_id'] == 7) {
        
    } else if($detail['hotel_field_id'] == 8) {
        
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
        foreach($seating_arr as $seating) {
            if($seating == "Dining") {
                $dine_checked = "checked";
            } else if($seating == "Roof") {
                $roof_checked = "checked";
            } else if($seating == "Outdoor") {
                $outdoor_checked = "checked";
            } else if($seating == "Lounge") {
                $lounge_checked = "checked";
            } else if($seating == "Bar") {
                $bar_checked = "checked";
            }
        }
    } else if($detail['hotel_field_id'] == 14) {
        $menu_sel_arr = explode(",",$detail['hotel_field_val']);
        foreach($menu_sel_arr as $menu_sel) {
            if($menu_sel == "alacarte") {
                $alacarte_check = "checked";
                $script[] = "<script>$(document).ready(function(){ $('#alacarte_check').toggle(); });</script>";
            } else {
                $buffet_check = "checked";
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
        
    } else if($detail['hotel_field_id'] == 20) {
        
    } else if($detail['hotel_field_id'] == 21) {
        $value_for_2 = $detail['hotel_field_val'];
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
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="create_restaurant.php" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Name of the Hotel</label>
                                            <input class="form-control" name="rest_name" required="required" placeholder="Enter Hotel Name" value="<?php echo $hotel_name ?>">
                                            <p class="help-block">Example "JW Marriot".</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Type</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios1" value="Restaurant" <?php echo $rest_checked; ?>>Restaurant
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios2" value="Cafe" <?php echo $cafe_checked; ?>>Cafe
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios2" value="Dhaba" <?php echo $dhaba_checked; ?>>Dhaba
                                                </label>
                                            </div>
                                            <input class="form-control" name="hotel_name" required="required" placeholder="Enter Restaurant/Cafe/Dhaba name" value="<?php echo $rest_name ?>">
                                            <p class="help-block">Example "Chawla".</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input class="form-control" name="rest_desc" required="required" value="<?php echo $rest_desc ?>" placeholder="Description of Hotel">
                                            <p class="help-block">Example "Bakery".</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Visitor Attraction</label>
                                            <input class="form-control" name="rest_known_for" required="required" placeholder="What is the hotel known for ?" value="<?php echo $visitor_attraction ?>">
                                            <p class="help-block">Example "The must-go-to place in Chandigarh for THE Butter Chicken experience!".</p>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea class="form-control" name="address" required="required" placeholder="Enter Complete Address here" rows="3"><?php echo $address; ?></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Area</label>
                                            <input class="form-control" name="area" required="required" placeholder="Enter the Area" value="<?php echo $area; ?>">
                                            <p class="help-block">Example "Sector-43A".</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>City</label>
                                            <select name="city" class="form-control">
                                                <option value="Chandigarh">Chandigarh</option>
                                                <option value="Delhi">Delhi</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>State</label>
                                            <select name="state" class="form-control">
                                                <option value="Punjab">Punjab</option>
                                                <option value="Haryana">Haryana</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Country</label>
                                            <select name="country" class="form-control">
                                                <option value="India">India</option>
                                                <option value="New Zealand">New Zealand</option>
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
                                                ?>
                                                <option value="<?php echo $cuisine['id']; ?>"><?php echo $cuisine['name']; ?></option>
                                                <?php } ?>
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
                                            <label>Delivery</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="delivery" id="optionsRadios1" value="yes" checked>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="delivery" id="optionsRadios2" value="no">No
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Seating</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="Dining" <?php echo $dine_checked ?>>Dining
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="Roof" <?php echo $roof_checked ?>>Open/Roof
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="Outdoor" <?php echo $outdoor_checked ?>>Outdoor
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="Lounge" <?php echo $lounge_checked ?>>Lounge
                                                </label>
                                            </div>
                                             <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="Bar" <?php echo $bar_checked ?>>Bar
                                                </label>
                                            </div>
                                            
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
                                        </div>
                                        
                                         <div class="form-group">
                                            <label>Opening Time</label>
                                            <input required="required" name="opening_time" class="form-control" value="<?php echo $opening_time ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Closing Time</label>
                                            <input required="required" name="closing_time" class="form-control" value="<?php echo $closing_time ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Value for 2</label>
                                            <input required="required" name="value_for_two" class="form-control" placeholder="Enter Amount" value="<?php echo $value_for_2 ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>All Menu</label> <br/>
                                            <label>À la carte</label> <input name="alacarte_menu[]" type="file" multiple="multiple">
                                            <label>Buffet</label> <input name="buffet_menu[]" type="file" multiple="multiple">
                                            <label>Bar</label> <input name="bar_menu[]" type="file" multiple="multiple">                                                                                       
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Gallery</label>
                                            <input type="file" name="gallery[]" multiple="multiple">                                            
                                        </div>
                                        <input type="hidden" name="form_submit" value="1" />                                        
                                        <button type="submit" class="btn btn-default">Submit Button</button>
                                        <button type="reset" class="btn btn-default">Reset Button</button>
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

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script>
        function displayMenuSel(id)
        {
            $('#'+id).toggle();
        }
    </script>
    <?php
    foreach($script as $display) {
        echo $display;
    }
    ?>
</body>

</html>
