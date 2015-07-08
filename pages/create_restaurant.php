<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include '../include/Connection.class.php';
include '../include/Admin.class.php';
include './header.php'; 
$adminObj = new Admin();

if(isset($_POST['form_submit'])) {
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
    $gallery_images = $_FILES['gallery'];
    $cover_pic = $_FILES['cover_pic'];
    
    
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
    $field_val[] = $cover_pic;
    //var_dump($field_val);
    
    $rest_id = $_POST['hotel_id'];
    $check_hotel = $adminObj->checkHotel($hotel_name);
    if(empty($check_hotel)) {
         $hotel_id = $adminObj->insertHotel($hotel_name, $rest_id);
         for($i=0;$i<count($field_id);$i++) {
             if($field_id[$i] == "22") {
                $ac_image_path = $adminObj->uploadFile($field_val[$i], "cover_pic");
                $adminObj->insertHotelDetails($hotel_id, $field_id[$i], $ac_image_path);
             } else {
                $adminObj->insertHotelDetails($hotel_id, $field_id[$i], $field_val[$i]);
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
         $adminObj->insertMenuSelection(1, $ala_bf, $ala_lunch, $ala_dinner,$hotel_id, '1');
         $adminObj->insertMenuSelection(2, $buffet_bf, $buffet_lunch, $buffet_dinner,$hotel_id, '1');

    } else {
        $error = "Hotel name exists";
    }
 }

?>

<body>

    <div id="wrapper">

        <?php include './nav.php'; ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Create Restaurant</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Create Restaurant
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="create_restaurant.php" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Name of the Hotel</label>
                                            <select  name="hotel_id" class="form-control">
                                                <?php 
                                                $hotellist = $adminObj->getRestaurants(); 
                                                foreach($hotellist as $hotels) {
                                                ?>
                                                <option value="<?php echo $hotels['rest_id']; ?>"><?php echo $hotels['rest_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Type</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios1" value="Casual Dining" checked>Casual Dining
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios1" value="Fine Dining" checked>Fine Dining
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios2" value="Cafe">Cafe
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios2" value="Dhaba">Dhaba
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios2" value="Rapid Munch">Rapid Munch
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios2" value="Night Club">Night Club
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios2" value="Dessert Parlor">Dessert Parlor
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios2" value="Pub">Pub
                                                </label>
                                            </div>
                                            <input class="form-control" name="hotel_name" required="required" placeholder="Enter Restaurant/Cafe/Dhaba name">
                                            <p class="help-block">Example "Chawla".</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input class="form-control" name="rest_desc" required="required" placeholder="Description of Hotel">
                                            <p class="help-block">Example "Bakery".</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Visitor Attraction</label>
                                            <input class="form-control" name="rest_known_for" required="required" placeholder="What is the hotel known for ?">
                                            <p class="help-block">Example "The must-go-to place in Chandigarh for THE Butter Chicken experience!".</p>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea class="form-control" name="address" required="required" placeholder="Enter Complete Address here" rows="3"></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Area</label>
                                            <input class="form-control" name="area" required="required" placeholder="Enter the Area">
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
                                            <input name="main" class="form-control" placeholder="Main Number"> <br>
                                            <input name="alt" class="form-control" placeholder="Alternate Number">                                           
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
                                                    <input name="serves[]" type="checkbox" value="Veg">Veg
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="serves[]" type="checkbox" value="Non-Veg">Non-Veg
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Alcohol</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="alcohol" id="optionsRadios1" value="yes" checked>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="alcohol" id="optionsRadios2" value="no">No
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
                                                    <input type="checkbox" name="seating[]" value="Dining">Dining
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="Roof">Open/Roof
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="Outdoor">Outdoor
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="Lounge">Lounge
                                                </label>
                                            </div>
                                             <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seating[]" value="Lounge">Bar
                                                </label>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Menu Selection</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="menu_sel[]" onclick="displayMenuSel('alacarte_check')" value="alacarte">À la carte
                                                </label>
                                            </div>
                                            
                                            <div id="alacarte_check" style="padding-left: 50px; display: none;">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="alacarte_check[]" value="Breakfast">Breakfast
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="alacarte_check[]" value="Lunch">Lunch
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="alacarte_check[]" value="Dinner">Dinner
                                                </label>
                                                
                                            </div>
                                            
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="menu_sel[]"  onclick="displayMenuSel('buffet_check')" value="Buffet">Buffet
                                                </label>
                                            </div>
                                            <div id="buffet_check" style="padding-left: 50px; display: none;">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="buffet_check[]" value="Breakfast">Breakfast
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="buffet_check[]" value="Lunch">Lunch
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="buffet_check[]" value="Dinner">Dinner
                                                </label>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Banquet</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="banquet" id="optionsRadios1" value="Yes" checked>Yes
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="banquet" id="optionsRadios2" value="No">No
                                                </label>
                                            </div>
                                            <input type="file" name="banquet_image[]" multiple="multiple" />
                                        </div>
                                        
                                         <div class="form-group">
                                            <label>Opening Time</label>
                                            <input required="required" data-format="hh:mm:ss" name="opening_time" id="opening_time" class="form-control">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Closing Time</label>
                                            <input required="required" name="closing_time" class="form-control">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Value for 2</label>
                                            <input required="required" name="value_for_two" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Upload Cover Pic</label> <br/>
                                            <input name="cover_pic" type="file">                                            
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
</body>

</html>
