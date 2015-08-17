<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include '../include/Connection.class.php';
include '../include/Admin.class.php';
include './header.php'; 
$adminObj = new Admin();
?>
<body>

    <div id="wrapper">

     <?php include './nav.php'; ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Sponsored Ads</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Edit Sponsored Advertisements
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>City Group Name</th>                                            
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php 
                                     $i = 1;
                                     foreach($adminObj->getSponsoredAdsCity() as $city_arr) { 
                                         if($i%2 == 0) {
                                             $class = "even";
                                         } else {
                                             $class = "odd";
                                         }
                                         $i++;
                                         ?>                                        
                                        <tr class="<?php echo $class ?> gradeX">
                                            <td><?php echo $city_arr['city'] ?></td>                                            
                                            <td><button onclick="location.href='add_sponsored_ads.php?city_group_name=<?php echo $city_arr['city'] ?>'">Edit</button></td>
                                        </tr>
                                     <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
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

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
    
    function deleteHotel(image_id,image_type)
    {
        var bool = confirm('Do you want to delete this hotel ?');
        if(bool) {
            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: { image_id: image_id, image_type: image_type }
              })
                .done(function( msg ) {
                  alert( msg );
                });
                location.href = location.href;
            }
    }
    </script>

</body>

</html>
