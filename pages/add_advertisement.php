<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include '../include/Connection.class.php';
include '../include/Admin.class.php';
include './header.php'; 
$adminObj = new Admin();
$error = "";
if(isset($_POST['form_submit'])) {
    
    $hotel_id = isset($_POST['hotel_id'])?$_POST['hotel_id']:"";   
    $type_id = isset($_POST['type'])?$_POST['type']:"";   
    $start_date = isset($_POST['start_date'])?$_POST['start_date']:"";   
    $end_date = isset($_POST['end_date'])?$_POST['end_date']:"";   
    $cover_pic = $_FILES['cover_pic'];
    if($adminObj->checkHotelInAd($hotel_id, $type_id)) {
        $cover_pic_image_path = $adminObj->uploadFile($cover_pic, "advertisement");
        if($cover_pic_image_path != "-1"  || $cover_pic_image_path == "") {
            $adminObj->insertAdvertisement($hotel_id, $cover_pic_image_path,$type_id, $start_date, $end_date);
        } else {
            $error = "Image cannot be uploaded. Please try again !";
        }
    } else {
        $error = "Hotel already exists";
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
                                                ?>
                                                <option value="<?php echo $hotels['hotel_id']; ?>"><?php echo $hotels['hotel_name']." - ".$hotels['rest_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Type</label>
                                            <?php 
                                                $adv_type_arr = $adminObj->getAdvType();
                                                foreach($adv_type_arr as $adv_type) {
                                            ?>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios1" value="<?php echo $adv_type['ad_type_id'] ?>" checked><?php echo $adv_type['ad_type_name']; ?>
                                                </label>
                                            </div>
                                                <?php } ?>                                            
                                        </div>
                                        
                                        
                                         <div class="form-group">
                                            <label>Advertisement Start Date</label>
                                            <input required="required" data-format="hh:mm:ss" name="start_date" id="start_date" class="form-control">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Advertisement End Date</label>
                                            <input required="required" name="end_date" id="end_date" class="form-control">
                                        </div>                                                                                
                                        
                                        <div class="form-group">
                                            <label>Upload Cover Pic</label> <br/>
                                            <input name="cover_pic" type="file">                                            
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
         
    </script>
</body>

</html>
