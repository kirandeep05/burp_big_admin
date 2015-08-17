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
$hotel_script = array();
$ad_id_get = $adv_arr = $hotel_id_temp = $ad_cover_temp = $ad_start_date_temp = $ad_end_date_temp = $script = "";
$form_submit = 1;
$flag = 0;
if(isset($_GET['city_group_name'])) {
    $city_group_name = $_GET['city_group_name'];
    $script = '$( "select[name=city_id] > option" ).each(function(index, value){
            console.log($(this).text());
            if($(this).text() == "'.$city_group_name.'") {
                $("select[name=city_id]").val($(this).val()).change();
                city_select();
            }
        });';
    $adv_arr = $adminObj->getSponsoredAdvertisement($city_group_name);
    $ad_res = array();
    foreach ($adv_arr as $adv) {
        $ad_res[$adv['strip']][$adv['order']-1] = $adv;
    }
    $flag = 1;
    $form_submit = 2;
}
    
if(isset($_POST['form_submit'])) {
    
    $city_group_name_n = isset($_POST['city_group_name'])?$_POST['city_group_name']:"0";
    for($k=1 ; $k <= 10; $k++ ) {
        ${"hotel_id_".$k} = isset($_POST["hotel_id_$k"])?$_POST["hotel_id_$k"]:"";
        ${"start_date_".$k} = isset($_POST["start_date_$k"])?$_POST["start_date_$k"]:"";
        ${"end_date_".$k} = isset($_POST["end_date_$k"])?$_POST["end_date_$k"]:"";
        ${"ad_text_".$k} = isset($_POST["ad_text_$k"])?$_POST["ad_text_$k"]:"";
        ${"cover_pic_".$k} = isset($_FILES["cover_pic_$k"])?$_FILES["cover_pic_$k"]:array();
    }    
    $type_id = 2;
        for($j = 1; $j <= count($hotel_id_1); $j++) {
            for($l=1; $l<=10; $l++) {
                if($form_submit == "2" ) {
                    if($adminObj->checkSponsoredAdExist($j, $l, $city_group_name)) {
                        if(${"hotel_id_$l"}[$j-1] == "0") {
                            $adminObj->deleteSponsoredAd($j,$l,$city_group_name);
                        } else {
                            $file['name'] = ${"cover_pic_".$l}['name'][$j-1];
                            $file['type'] = ${"cover_pic_".$l}['type'][$j-1];
                            $file['tmp_name'] = ${"cover_pic_".$l}['tmp_name'][$j-1];
                            $file['error'] = ${"cover_pic_".$l}['error'][$j-1];
                            $file['size'] = ${"cover_pic_".$l}['size'][$j-1];
                            $cover_pic_image_path = $adminObj->uploadFile($file, "advertisement");
                            $start_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime(${"start_date_".$l}[$j-1])));
                            $end_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime(${"end_date_".$l}[$j-1])));
                            if($cover_pic_image_path == "-1") {
                                $cover_pic_image_path = "";
                            }
                            $adminObj->updateSponsoredAdvertisement(${"hotel_id_".$l}[$j-1], $cover_pic_image_path, $start_date_final, $end_date_final, $city_group_name, $j, $l, ${"ad_text_".$l}[$j-1]);
                        }
                    } else {
                        if(${"hotel_id_$l"}[$j-1] == "0") {
                            continue;
                        }
                        $file['name'] = ${"cover_pic_".$l}['name'][$j-1];
                        $file['type'] = ${"cover_pic_".$l}['type'][$j-1];
                        $file['tmp_name'] = ${"cover_pic_".$l}['tmp_name'][$j-1];
                        $file['error'] = ${"cover_pic_".$l}['error'][$j-1];
                        $file['size'] = ${"cover_pic_".$l}['size'][$j-1];
                        $cover_pic_image_path = $adminObj->uploadFile($file, "advertisement");
                        $start_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime(${"start_date_".$l}[$j-1])));
                        $end_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime(${"end_date_".$l}[$j-1])));
                        $adminObj->insertAdvertisement(${"hotel_id_".$l}[$j-1], $cover_pic_image_path,$type_id, $start_date_final, $end_date_final,$city_group_name_n,$j,$l,${"ad_text_".$l}[$j-1]);
                    }
                } else {
                    if(${"hotel_id_$l"}[$j-1] == "0") {
                        continue;
                    }

                    $file['name'] = ${"cover_pic_".$l}['name'][$j-1];
                    $file['type'] = ${"cover_pic_".$l}['type'][$j-1];
                    $file['tmp_name'] = ${"cover_pic_".$l}['tmp_name'][$j-1];
                    $file['error'] = ${"cover_pic_".$l}['error'][$j-1];
                    $file['size'] = ${"cover_pic_".$l}['size'][$j-1];
                    $cover_pic_image_path = $adminObj->uploadFile($file, "advertisement");
                    $start_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime(${"start_date_".$l}[$j-1])));
                    $end_date_final = date('Y-m-d h:i', strtotime("-$timezone minutes", strtotime(${"end_date_".$l}[$j-1])));
                    if($cover_pic_image_path != "-1"  || $cover_pic_image_path != "") {
                        $adminObj->insertAdvertisement(${"hotel_id_".$l}[$j-1], $cover_pic_image_path,$type_id, $start_date_final, $end_date_final,$city_group_name_n,$j,$l,${"ad_text_".$l}[$j-1]);
                    } else {
                        $error = "Image cannot be uploaded. Please try again !";
                    }
                }
                
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
                                                if($form_submit == 1) {
                                                    $data_arr = $adminObj->getSponsCitiesGroup();
                                                } else {
                                                    $data_arr = $adminObj->getActiveCitiesGroup();
                                                }
                                                foreach( $data_arr as $city_act) {
                                                    $city_obj[$city_act['city_group_id']]['id'][] = $city_act['city_id'];
                                                    $city_obj[$city_act['city_group_id']]['name'] = $city_act['group_name'];
                                                }
                                                foreach($city_obj as $key=>$value) {
                                                    echo "<option value='".implode(",",$city_obj[$key]['id'])."'>".$city_obj[$key]['name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                        if($flag == "0") {
                                        ?>
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
                                                    <label>Advertisement Text</label>
                                                    <input name="ad_text_<?php echo $i ?>[]" class="form-control">
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
                                        <?php 
                                                    }
                                                }
                                                ?>
                                            
                                                </div>
                                            </div>
                                        </div>
                                       <?php
                                        } else {                                            
                                            foreach($ad_res as $key=>$value) {                                           
                                        ?>
                                        <div class="panel panel-default strip" id="<?php echo $key ?>">
                                            <div class="panel-heading">
                                                Strip <?php echo $key ?>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                            <?php
                                            for($i=1;$i<=10;$i++) {
                                                $data = isset($value[$i-1])?$value[$i-1]:array();
                                                if(!empty($data) && $data['order'] == $i) { ?>
                                                    <label><?php echo "$i. " ?>Name of the Hotel</label>
                                                <select  name="hotel_id_<?php echo $i; ?>[]" class="form-control hotels_arr">
                                                    <option value="0">Select Hotel</option>
                                                    <?php echo "<option value='{$data['ad_hotel_id']}'>Hotel Name</option>";
                                                    $hotel_script[] = "$(\"#$key select[name='hotel_id_$i\[\]']\").val('{$data['ad_hotel_id']}')";
                                                    ?>
                                                </select>
                                                <div class="form-group">
                                                    <label>Advertisement Start Date</label>
                                                    <?php 
                                                    $start_date_final_temp = date('Y-m-d h:i', strtotime("+$timezone minutes", strtotime($data['ad_start_date'])));                                                    
                                                    ?>
                                                    <input value="<?php echo $start_date_final_temp; ?>" data-format="hh:mm:ss" name="start_date_<?php echo $i ?>[]>" class="form-control dates">
                                                </div>

                                                <div class="form-group">
                                                    <label>Advertisement End Date</label>
                                                    <?php 
                                                    $end_date_final_temp = date('Y-m-d h:i', strtotime("+$timezone minutes", strtotime($data['ad_end_date'])));
                                                    ?>
                                                    <input value="<?php echo $end_date_final_temp; ?>" name="end_date_<?php echo $i ?>[]" class="form-control dates">
                                                </div>                                                                                

                                                <div class="form-group">
                                                    <label>Advertisement Text</label>
                                                    <input value="<?php echo $data['text']; ?>" name="ad_text_<?php echo $i ?>[]" class="form-control">
                                                </div>
                                                    
                                                <div class="form-group">
                                                    <label>Upload Cover Pic</label> <br/>
                                                    <input name="cover_pic_<?php echo $i; ?>[]" type="file">                                            
                                                </div>
                                                <?php if($data['ad_cover_pic'] != "") { ?>
                                                <div class="gall-img" style="float: none;" >
                                                    <a href="../images/advertisement/<?php echo $data['ad_cover_pic']; ?>" title="" data-gallery>
                                                        <img src="../images/advertisement/<?php echo $data['ad_cover_pic']; ?>" alt="" style="height: 100px; width: 100px;">
                                                    </a> <br />                                            
                                                </div>
                                                <?php 
                                                    }
                                                } else {
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
                                                    <label>Advertisement Text</label>
                                                    <input value="<?php echo $ad_end_date_temp; ?>" name="ad_text_<?php echo $i ?>[]" class="form-control">
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
                                                <?php 
                                                }
                                               } 
                                            }
                                            echo "</div></div></div>";
                                            }
                                        
                                        } ?>
                                        <input type="hidden" name="city_group_name" />
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
        
        
        <?php echo $script; ?>
        
        $( "select[name=city_id]" ).change(function () {            
                city_select();
        });
        
        
        
        function city_select()
        {
            var city_ids = $( "select[name=city_id] option:selected" ).val();
                var city_name = $( "select[name=city_id] option:selected" ).html();
                $("input[name=city_group_name]").val(city_name);
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
                    <?php echo implode(";",$hotel_script); ?>
                    });
                    
        }
        
        $("#add_strip").on('click',function(event){
            event.preventDefault();
            var id = $(".strip:last").attr('id');
//            var appendDiv = jQuery($(".strip:last")[0].outerHTML);
//            appendDiv.attr('id', ++id).insertAfter(".strip:last");
            var html = '<div class="panel panel-default strip" id="'+(++id)+'">'+
                                            '<div class="panel-heading">'+
                                                'Strip 1'+
                                            '</div>'+
                                            '<div class="panel-body">'+
                                                '<div class="form-group">'+
                                            <?php
                                            for($i=1;$i<=10;$i++) {
                                            ?>
                                                '<label><?php echo "$i. " ?>Name of the Hotel</label>'+
                                                '<select  name="hotel_id_<?php echo $i; ?>[]" class="form-control hotels_arr">'+
                                                    '<option value="0">Select Hotel</option>'+
                                                '</select>'+
                                                '<div class="form-group">'+
                                                    '<label>Advertisement Start Date</label>'+
                                                    '<input value="<?php echo $ad_start_date_temp; ?>" data-format="hh:mm:ss" name="start_date_<?php echo $i ?>[]>" class="form-control dates">'+
                                               ' </div>'+

                                                '<div class="form-group">'+
                                                    '<label>Advertisement End Date</label>'+
                                                    '<input value="<?php echo $ad_end_date_temp; ?>" name="end_date_<?php echo $i ?>[]" class="form-control dates">'+
                                                '</div> '+                                                                               

                                                '<div class="form-group">'+
                                                    '<label>Advertisement Text</label>'+
                                                    '<input value="<?php echo $ad_end_date_temp; ?>" name="ad_text_<?php echo $i ?>[]" class="form-control">'+
                                                '</div>'+

                                                '<div class="form-group">'+
                                                    '<label>Upload Cover Pic</label> <br/>'+
                                                    '<input name="cover_pic_<?php echo $i; ?>[]" type="file">'+                                            
                                                '</div>'+
                                        <?php 
                                                }
                                                ?>
                                            
                                               ' </div>'+
                                            '</div>'+
                                        '</div>';
            $(".strip:last").after(html);
            city_select();
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
