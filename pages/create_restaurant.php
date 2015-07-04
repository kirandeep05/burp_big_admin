<!DOCTYPE html>
<html lang="en">

<?php
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
    $menu_sel = isset($_POST['menu_sel'])?$_POST['menu_sel']:"";
    $banquet = isset($_POST['banquet'])?$_POST['banquet']:"";
    $opening_time = isset($_POST['opening_time'])?$_POST['opening_time']:"";
    $closing_time = isset($_POST['closing_time'])?$_POST['closing_time']:"";
    $alacarte_menu = $_FILES['alacarte_menu'];
    $buffet_menu = $_FILES['buffet_menu'];
    $bar_menu = $_FILES['bar_menu'];
    
    
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
    
    $field_val[] = $rest_desc;
    $field_val[] = $type;
    $field_val[] = $address;
    $field_val[] = $area;
    $field_val[] = $city;
    $field_val[] = $state;
    $field_val[] = $country;
    $field_val[] = $phone_main.",".$phone_alt;
    $field_val[] = implode(",", $cuisine);
    $field_val[] = implode(",",$serves);
    $field_val[] = $alcohol;
    $field_val[] = implode(",",$seating);
    $field_val[] = implode(",",$menu_sel);
    $field_val[] = $banquet;
    $field_val[] = $opening_time;
    $field_val[] = $closing_time;
    
    
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
                    $adminObj->insertHotelMenu(1, $image_path, $hotel_id);
                }
                
                for($j=0;$j<count($buffet_menu['name']);$j++) {
                    $buffet_file['name'] = $buffet_menu['name'][$j];
                    $buffet_file['type'] = $buffet_menu['type'][$j];
                    $buffet_file['tmp_name'] = $buffet_menu['tmp_name'][$j];
                    $buffet_file['error'] = $buffet_menu['error'][$j];
                    $buffet_file['size'] = $buffet_menu['size'][$j];
                    $image_path = $adminObj->uploadFile($buffet_file, "buffet");
                    $adminObj->insertHotelMenu(2, $image_path, $hotel_id);
                }
                
                for($j=0;$j<count($bar_menu['name']);$j++) {
                    $bar_file['name'] = $bar_menu['name'][$j];
                    $bar_file['type'] = $bar_menu['type'][$j];
                    $bar_file['tmp_name'] = $bar_menu['tmp_name'][$j];
                    $bar_file['error'] = $bar_menu['error'][$j];
                    $bar_file['size'] = $bar_menu['size'][$j];
                    $image_path = $adminObj->uploadFile($bar_file, "bar");
                    $adminObj->insertHotelMenu(3, $image_path, $hotel_id);
                }
                
           } else {
               $error = "Hotel name exists";
           }
        }
    } else {
        $error = "Restaurant name cannot be empty";
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
                                            <input class="form-control" name="rest_name" required="required" placeholder="Enter Hotel Name">
                                            <p class="help-block">Example "JW Marriot".</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input class="form-control" name="rest_desc" required="required" placeholder="Description of Hotel">
                                            <p class="help-block">Example "Bakery".</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Type</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios1" value="Restaurant" checked>Restaurant
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
                                            <input class="form-control" name="hotel_name" required="required" placeholder="Enter Restaurant/Cafe/Dhaba name">
                                            <p class="help-block">Example "Chawla".</p>
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
                                            <input type="file" name="banquet_image" multiple="multiple" />
                                        </div>
                                        
                                         <div class="form-group">
                                            <label>Opening Time</label>
                                            <input required="required" name="opening_time" class="form-control">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Closing Time</label>
                                            <input required="required" name="closing_time" class="form-control">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>All Menu</label> <br/>
                                            <label>À la carte</label> <input name="alacarte_menu[]" type="file" multiple="multiple">
                                            <label>Buffet</label> <input name="buffet_menu[]" type="file" multiple="multiple">
                                            <label>Bar</label> <input name="bar_menu[]" type="file" multiple="multiple">                                                                                       
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Gallery</label>
                                            <input type="file" name="gallery" multiple="multiple">                                            
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
