<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include '../include/Connection.class.php';
include '../include/Admin.class.php';
include './header.php'; 

$timezone = $_SESSION['time'];
if($timezone != "") {
    //$timezone += 60;
}
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
        $ad_start_date_temp = date('Y-m-d h:i', strtotime("+$timezone minutes", strtotime($adv_arr['ad_start_date'])));
        $ad_end_date_temp = date('Y-m-d h:i', strtotime("+$timezone minutes", strtotime($adv_arr['ad_end_date'])));
        $form_submit = 2;
    } else {
        $error = "Hotel does not exist";
    }
}
    
if(isset($_POST['form_submit'])) {
    
    for($k=1 ; $k <= 10; $k++ ) {
        ${"hotel_id_".$k} = isset($_POST["hotel_id_$k"])?$_POST["hotel_id_$k"]:"";
        ${"start_date_".$k} = isset($_POST["start_date_$k"])?$_POST["start_date_$k"]:"";
        ${"end_date_".$k} = isset($_POST["end_date_$k"])?$_POST["end_date_$k"]:"";
        ${"cover_pic_".$k} = isset($_FILES["cover_pic_$k"])?$_FILES["cover_pic_$k"]:array();
    }    
    $type_id = 2;
        for($j = 1; $j <= count($hotel_id_1); $j++) {
            for($l=1; $l<=10; $l++) {
                if(${"hotel_id_$l"}[$j-1] == "0") {
                    continue;
                }
                if($adminObj->checkHotelInAd(${"hotel_id_".$l}[$j-1], $type_id)) {
                    $file['name'] = ${"cover_pic_".$l}['name'][$j-1];
                    $file['type'] = ${"cover_pic_".$l}['type'][$j-1];
                    $file['tmp_name'] = ${"cover_pic_".$l}['tmp_name'][$j-1];
                    $file['error'] = ${"cover_pic_".$l}['error'][$j-1];
                    $file['size'] = ${"cover_pic_".$l}['size'][$j-1];
                    $cover_pic_image_path = $adminObj->uploadFile($file, "advertisement");
                    $start_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime(${"start_date_".$l}[$j-1])));
                    $end_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime(${"end_date_".$l}[$j-1])));
                    if($cover_pic_image_path != "-1"  || $cover_pic_image_path != "") {
                        $adminObj->insertAdvertisement(${"hotel_id_".$l}[$j-1], $cover_pic_image_path,$type_id, $start_date_final, $end_date_final,$j,$l);
                    } else {
                        $error = "Image cannot be uploaded. Please try again !";
                    }
                } else {
                    $error = "Hotel already exists";
                }
            }
        }

//    $type_id = isset($_POST['type'])?$_POST['type']:"";   
//    $start_date = isset($_POST['start_date'])?$_POST['start_date']:"";   
//    $end_date = isset($_POST['end_date'])?$_POST['end_date']:"";   
//    $cover_pic = $_FILES['cover_pic'];
    
//    $start_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime($start_date)));
//    $end_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime($end_date)));
    $form_submit_temp = $_POST['form_submit'];
//    if($form_submit_temp == "1") {    
//        if($adminObj->checkHotelInAd($hotel_id, $type_id)) {
//            $cover_pic_image_path = $adminObj->uploadFile($cover_pic, "advertisement");
//            if($cover_pic_image_path != "-1"  || $cover_pic_image_path != "") {
//                $adminObj->insertAdvertisement($hotel_id, $cover_pic_image_path,$type_id, $start_date_final, $end_date_final);
//            } else {
//                $error = "Image cannot be uploaded. Please try again !";
//            }
//        } else {
//            $error = "Hotel already exists";
//        }
//    } else {
//        $ad_id = $_POST['ad_id'];
//        if(!($adminObj->checkAdId($ad_id))) {
//            $cover_pic_image_path = $adminObj->uploadFile($cover_pic, "advertisement");
//            if($cover_pic_image_path != "-1") {
//                $adminObj->updateAdvertisement($hotel_id, $cover_pic_image_path,$type_id, $start_date_final, $end_date_final,$ad_id);
//            } else {
//                $error = "Image cannot be uploaded. Please try again !";
//            }
//            echo "<script>window.location.href = 'add_advertisement.php?ad_id=$ad_id';</script>";
//        } else {
//            $error = "Hotel does not exist";
//        }
//    }
}


?>
<link href="../dist/css/bootstrap-datetimepicker.css" rel="stylesheet">
<body>

    <div id="wrapper">

        <?php include './nav.php'; ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add Sponsored Advertisement</h1>
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
                                    <form role="form" action="" method="post" enctype="multipart/form-data">
                                        <button id="add_strip">Add Strip</button>
                                        <div class="form-group">
                                            <label>Select City Group</label>
                                            <select  name="city_id" class="form-control">
                                                <option value="0">Select City</option>
                                                <?php 
                                                foreach($adminObj->getActiveCitiesGroup() as $city_act) {
                                                    $city_obj[$city_act['city_group_id']]['id'][] = $city_act['city_id'];
                                                    $city_obj[$city_act['city_group_id']]['name'] = $city_act['group_name'];
                                                }
                                                foreach($city_obj as $key=>$value) {
                                                    echo "<option value='".implode(",",$city_obj[$key]['id'])."'>".$city_obj[$key]['name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="panel panel-default strip" id="1">
                                            <div class="panel-heading">
                                                Strip 1
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">

                                            <?php
                                            for($i=1;$i<=10;$i++) {
                                            ?>
                                                <label><?php echo "$i. " ?>Name of the Hotel</label>
                                                <select  name="hotel_id_<?php echo $i; ?>[]" class="form-control hotels_arr">
                                                    <option value="0">Select Hotel</option>
                                                </select>
                                                <div class="form-group">
                                                    <label>Advertisement Start Date</label>
                                                    <input value="<?php echo $ad_start_date_temp; ?>" data-format="hh:mm:ss" name="start_date_<?php echo $i ?>[]>" class="form-control dates">
                                                </div>

                                                <div class="form-group">
                                                    <label>Advertisement End Date</label>
                                                    <input value="<?php echo $ad_end_date_temp; ?>" name="end_date_<?php echo $i ?>[]" class="form-control dates">
                                                </div>                                                                                


                                                <div class="form-group">
                                                    <label>Upload Cover Pic</label> <br/>
                                                    <input name="cover_pic_<?php echo $i; ?>[]" type="file">                                            
                                                </div>
                                                <?php if($ad_cover_temp != "") { ?>
                                                <div class="gall-img" style="float: none;" >
                                                    <a href="../images/advertisement/<?php echo $ad_cover_temp; ?>" title="" data-gallery>
                                                        <img src="../images/advertisement/<?php echo $ad_cover_temp; ?>" alt="" style="height: 100px; width: 100px;">
                                                    </a> <br />                                            
                                                </div>
                                                <?php } ?>
                                            <?php 
                                            }
                                            ?>
                                                </div>
                                            </div>
                                        </div>
                                                                                                                        
                                        
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
        
        $(".dates").each(function(){
            $(this).datetimepicker({
                format: 'yyyy-mm-dd hh:ii'
            });
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
        
        $( "select[name=city_id]" ).change(function () {            
                var city_ids = $( "select[name=city_id] option:selected" ).val();                
                $.ajax({
                    method: "POST",
                    url: "location.php",
                    data: { type: "city_id", city_ids: city_ids}
                  })
                    .done(function( msg ) {
                      console.log(msg);
                      myOptions = $.parseJSON(msg) ;
                      mySelect = $( ".hotels_arr" );
                      mySelect.each(function(index) {
                          console.log(index);
                            $(this).html($('<option></option>').val("0").html("Select Hotel"));
                            var this_obj = $(this);
                              $.each(myOptions, function(val,text) {
                                  //console.log(text);
                                   this_obj.append(
                                       $('<option></option>').val(text.hotel_id).html(text.hotel_name)
                                   );
                               });
                             }
                       );
                    });
        });
        
        $("#add_strip").on('click',function(event){
            event.preventDefault();
           var id = $(".strip:last").attr('id');
            var appendDiv = jQuery($(".strip:last")[0].outerHTML);
            appendDiv.attr('id', ++id).insertAfter(".strip:last");
            console.log($(".strip:last .panel-heading").html("Strip "+(id++)));
            $(".dates").each(function(){
                $(this).datetimepicker({
                    format: 'yyyy-mm-dd hh:ii'
                });
            });
            $(window).scrollTop($('.strip:last').offset().top);
        });
    });
</script>
</body>

</html>
