<?php
include "header.php";
include "navbar.php";
include "sidebar.php";
require 'config/DBConnection.php';
$user_type = $_SESSION['sF_user_type'];
// print_r($_SESSION);exit;

$conn = new DBConnection();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-10">
          <h1 class="m-0 text-center" style="color: red"><b style="color: black">Welcome</b> Admin</h1>
        </div><!-- /.col -->
        <div class="col-sm-2">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Tag for welcomeing -->
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">
        <marquee>
          <h6 class="m-0" style="color: red">Please Click The Button Below</h6>
        </marquee>
      </div>
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
  <!-- Tag for welcomeing -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <!-- View Site -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>30</h3>
              <p>View Site</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="../index.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- View Site End -->

        <!-- View Site -->
        <div class="col-lg-3 col-6">
          <div class="small-box" style="background-color:#c741e9d4;">
            <div class="inner">
              <?php
              $connection = $conn->connect();
              $statement = $connection->prepare("select count(user_request_id) as u_rqst_id from user_request where is_active = 1");
              $statement->execute();
              $result = $statement->fetch();
              $statement->closeCursor();
              $totle = $result['u_rqst_id'];
              ?>
              <h3><?= $totle ?></h3>
              <p>User Request</p>
              <span><b>Totle Rq :</b> <?= str_pad($totle, 3, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="icon">
              <i class="ion ion-android-contacts"></i>
            </div>
            <a href="addBkph.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- View Site End -->

        <div class="col-lg-3 col-6">
          <div class="small-box" style="background-color:#c741e9d4;">
            <div class="inner">
              <?php
              // $connection = $conn->connect();
              // $statement = $connection->prepare("select count(user_request_id) as u_rqst_id from user_request where is_active = 1");
              // $statement->execute();
              // $result = $statement->fetch();
              // $statement->closeCursor();
              // $totle = $result['u_rqst_id'];
              ?>
              <h3><?= $totle ?></h3>
              <p>User Request</p>
              <!-- <span><b>Totle Rq :</b> <?= str_pad($totle, 3, '0', STR_PAD_LEFT); ?></span> -->
            </div>
            <div class="icon">
              <i class="ion ion-android-contacts"></i>
            </div>
            <a href="addBkph.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box" style="background-color:#c741e9d4;">
            <div class="inner">
              <?php
              // $connection = $conn->connect();
              // $statement = $connection->prepare("select count(user_request_id) as u_rqst_id from user_request where is_active = 1");
              // $statement->execute();
              // $result = $statement->fetch();
              // $statement->closeCursor();
              // $totle = $result['u_rqst_id'];
              ?>
              <h3><?= $totle ?></h3>
              <p>User Request</p>
              <!-- <span><b>Totle Rq :</b> <?= str_pad($totle, 3, '0', STR_PAD_LEFT); ?></span> -->
            </div>
            <div class="icon">
              <i class="ion ion-android-contacts"></i>
            </div>
            <a href="addBackPhoto.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "footer_js.php"; ?>
<?php include "footer.php"; ?>