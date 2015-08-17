<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include '../include/Connection.class.php';
include '../include/Admin.class.php';
include './header.php'; 

$timezone = $_SESSION['time'];
if($timezone != "") {
    $timezone += 60;
}
var_dump($timezone);
$adminObj = new Admin();
$error = "";
$ad_id_get = $adv_arr = $hotel_id_temp = $ad_cover_temp = $ad_start_date_temp = $ad_end_date_temp = "";
$ad_type_id_temp = 1;
$form_submit = 1;
if(isset($_GET['ad_id'])) {
    $ad_id_get = $_GET['ad_id'];
    if(!($adminObj->checkAdId($ad_id_get))) {
        $adv_arr = $adminObj->getAdvertisementFromHotelID($ad_id_get);
        $hotel_id_temp = $adv_arr['ad_hotel_id'];
        $ad_cover_temp = $adv_arr['ad_cover_pic'];
        $ad_type_id_temp = $adv_arr['ad_type_id'];    
        $ad_start_date_temp = date('Y-m-d H:i', strtotime("-$timezone minutes", strtotime($adv_arr['ad_start_date'])));
        $ad_end_date_temp = date('Y-m-d H:i', strtotime("-$timezone minutes", strtotime($adv_arr['ad_end_date'])));
        $form_submit = 2;
    } else {
        $error = "Hotel does not exist";
    }
}
    
if(isset($_POST['form_submit'])) {
    
    $hotel_id = isset($_POST['hotel_id'])?$_POST['hotel_id']:"";   
    $type_id = isset($_POST['type'])?$_POST['type']:"";   
    $start_date = isset($_POST['start_date'])?$_POST['start_date']:"";   
    $end_date = isset($_POST['end_date'])?$_POST['end_date']:"";   
    $cover_pic = $_FILES['cover_pic'];
    
    $start_date_final = date('Y-m-d H:i', strtotime("+$timezone minutes", strtotime($start_date)));
    $end_date_final = date('Y-m-d H:i', strtotime("+$timezone minutes", strtotime($end_date)));
    $form_submit_temp = $_POST['form_submit'];
    if($form_submit_temp == "1") {    
        if($adminObj->checkHotelInAd($hotel_id, $type_id)) {
            $cover_pic_image_path = $adminObj->uploadFile($cover_pic, "advertisement");
            if($cover_pic_image_path != "-1"  || $cover_pic_image_path != "") {
                $adminObj->insertAdvertisement($hotel_id, $cover_pic_image_path,$type_id, $start_date_final, $end_date_final);
            } else {
                $error = "Image cannot be uploaded. Please try again !";
            }
        } else {
            $error = "Hotel already exists";
        }
    } else {
        $ad_id = $_POST['ad_id'];
        if(!($adminObj->checkAdId($ad_id))) {
            $cover_pic_image_path = $adminObj->uploadFile($cover_pic, "advertisement");
            if($cover_pic_image_path != "-1") {
                $adminObj->updateAdvertisement($hotel_id, $cover_pic_image_path,$type_id, $start_date_final, $end_date_final,$ad_id);
            } else {
                $error = "Image cannot be uploaded. Please try again !";
            }
            echo "<script>window.location.href = 'add_advertisement.php?ad_id=$ad_id';</script>";
        } else {
            $error = "Hotel does not exist";
        }
    }
}


?>
<link href="../dist/css/bootstrap-datetimepicker.css" rel="stylesheet">
<body>

    <div id="wrapper">

        <?php include './nav.php'; ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add Advertisement</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Add Advertisement
                        </div>
                        <div class="panel-body">
                            <?php if($error != "") {?>
                            <div class="alert alert-danger">
                             <?php echo $error; ?>
                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="add_advertisement.php" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Name of the Hotel</label>
                                            <select  name="hotel_id" class="form-control">
                                                <?php 
                                                $hotellist = $adminObj->getHotelsList(); 
                                                foreach($hotellist as $hotels) {
                                                    if($hotel_id_temp == $hotels['hotel_id']) {
                                                ?>
                                                <option value="<?php echo $hotels['hotel_id']; ?>" selected="selected"><?php echo $hotels['hotel_name']." - ".$hotels['rest_name']; ?></option>
                                                <?php
                                                    } else {
                                                ?>
                                                <option value="<?php echo $hotels['hotel_id']; ?>"><?php echo $hotels['hotel_name']." - ".$hotels['rest_name']; ?></option>
                                                <?php }
                                                }?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Type</label>
                                            <?php 
                                                $adv_type_arr = $adminObj->getAdvType();
                                                foreach($adv_type_arr as $adv_type) {
                                                    if($ad_type_id_temp == $adv_type['ad_type_id']) {
                                            ?>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios1" value="<?php echo $adv_type['ad_type_id'] ?>" checked><?php echo $adv_type['ad_type_name']; ?>
                                                </label>
                                            </div>
                                            <?php
                                                    } else {
                                            ?>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios1" value="<?php echo $adv_type['ad_type_id'] ?>"><?php echo $adv_type['ad_type_name']; ?>
                                                </label>
                                            </div>
                                                <?php } 
                                                }?>                                            
                                        </div>
                                        
                                        
                                         <div class="form-group">
                                            <label>Advertisement Start Date</label>
                                            <input required="required" value="<?php echo $ad_start_date_temp; ?>" data-format="hh:mm:ss" name="start_date" id="start_date" class="form-control">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Advertisement End Date</label>
                                            <input required="required" value="<?php echo $ad_end_date_temp; ?>" name="end_date" id="end_date" class="form-control">
                                        </div>                                                                                
                                        
                                        
                                        <div class="form-group">
                                            <label>Upload Cover Pic</label> <br/>
                                            <input name="cover_pic" type="file">                                            
                                        </div>
                                        <?php if($ad_cover_temp != "") { ?>
                                        <div class="gall-img" style="float: none;" >
                                            <a href="../images/advertisement/<?php echo $ad_cover_temp; ?>" title="" data-gallery>
                                                <img src="../images/advertisement/<?php echo $ad_cover_temp; ?>" alt="" style="height: 100px; width: 100px;">
                                            </a> <br />                                            
                                        </div>
                                        <?php } ?>
                                        <input type="hidden" name="form_submit" value="<?php echo $form_submit; ?>" />
                                        <?php if($ad_id_get != "") { ?>
                                            <input type="hidden" name="ad_id" value="<?php echo $ad_id_get; ?>" />
                                        <?php }  ?>
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
    <script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap-datetimepicker.min.js"></script>
    <script>
        function displayMenuSel(id)
        {
            $('#'+id).toggle();
        }
    </script>
    
    <script type="text/javascript">
        
        $("#start_date").datetimepicker({
           format: 'yyyy-mm-dd hh:ii'
         });
         
        $("#end_date").datetimepicker({
           format: 'yyyy-mm-dd hh:ii'
         });
         
         $('#image-gallery-button').on('click', function (event) {
            event.preventDefault();
            blueimp.Gallery($('#links a'), $('#blueimp-gallery').data());
        });
         
    </script>
    
    <script type="text/javascript">
    $(document).ready(function() {
        if("<?php echo $timezone; ?>".length==0){
            var visitortime = new Date();
            //var visitortimezone = "GMT " + -visitortime.getTimezoneOffset()/60;
            var visitortimezone = -visitortime.getTimezoneOffset();
            $.ajax({
                type: "GET",
                url: "../timezone.php",
                data: 'time='+ visitortimezone,
                success: function(){
                    location.reload();
                }
            });
        }
    });
</script>
</body>

</html>
