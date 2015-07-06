<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include '../include/Connection.class.php';
include '../include/Admin.class.php';
include './header.php'; 
$adminObj = new Admin();

if(isset($_POST['form_submit'])) {
    $rest_name = isset($_POST['rest_name'])?$_POST['rest_name']:"";
        
    if($rest_name != "") {
        $check_rest = $adminObj->checkRestaurant($rest_name);
        if(empty($check_rest)) {
           $rest_id = $adminObj->insertRestaurant($rest_name);
        } else {
            $error = "Restaurant name exists";
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
                    <h1 class="page-header">Create Hotel</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Create Hotel
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="create_hotel.php" method="post" >
                                        <div class="form-group">
                                            <label>Name of the Hotel</label>
                                            <input class="form-control" name="rest_name" required="required" placeholder="Enter Hotel Name">
                                            <p class="help-block">Example "JW Marriot".</p>
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
