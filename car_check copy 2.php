<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navs and Tabs Example</title>
  <!-- Link to Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sarabun&display=swap">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="style.css">
  <script src="js/scripts.js"></script>
</head>

<body>
  <?php include 'navbar.php'; ?>
  <div class="container py-2">
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <a class="btn " style="background-color: #6699cc;" href="car_search.php">ค้นหารายการเช่ารถ</a>

    </div>


    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">สะสม</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">รอยืนยัน</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">สถานะรถ</button>
      </li>

    </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <h3>รายการเช่ารถ</h3>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">ลูกค้า</th>
              <th scope="col">รถ</th>
              <th scope="col">วันที่</th>
              <th scope="col">สถานะ</th>
              <th scope="col">เพิ่มเติม</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once "database.php";
            $sql = "SELECT bookings.*, car_rental.* 
                        FROM bookings
                        JOIN car_rental ON car_rental.bookings_id = bookings.id
                        ORDER BY car_rental.rental_id DESC";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                $bookings_id = $row['bookings_id'];
                $car_id = $row['car_id'];
                $car_brand = $row['car_brand'];
                $car_model = $row['car_model'];
                $customer_name = $row["customer_name"];
                $status = $row["status"];
                $rental_date = $row["rental_date"];

                echo '<tr>';
                echo '<th scope="row">' . $i++ . '</th>';
                echo '<td>' . $customer_name . '</td>';
                echo '<td>' . $car_brand . ' ' . $car_model . '</td>';
                echo '<td>' . $rental_date . '</td>';
                echo '<td>';
                if ($status == 1) {
                  echo '<span class="badge text-bg-warning ">รอการยืนยัน</span>';
                } elseif ($status == 2) {
                  echo '<span class="badge text-bg-info ">รอรับรถ</span>';
                } elseif ($status == 3) {
                  echo '<span class="badge text-bg-success">เช่าอยู่</span>';
                } else {
                  echo '<span class="badge text-bg-dark">สิ้นสุดการเช่า</span>';
                }

                echo '</td>'; ?>
                <td>
                  <div class="d-grid gap-2 d-md-flex">
                    <a class="btn btn-warning me-md-2" href="booking_detail.php?bookings_id=<?php echo $bookings_id; ?>">ดูรายละเอียด</a>
                    <form action="" method="POST">
                      <input type="hidden" name="bookings_id" value="<?php echo $row['bookings_id']; ?>">
                      <button class="btn btn-outline-warning" type="submit" name="delete_data" value="Submit">ลบ</button>
                    </form>
                  </div>



                </td><?php
                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="5">No data found.</td></tr>';
                  }
                      ?>
            <?php
            if (isset($_POST['delete_data'])) {
              $bookings_id = $_POST['bookings_id'];

              // ลบข้อมูลในตาราง car_rental
              $sql = "DELETE FROM car_rental WHERE bookings_id = $bookings_id";
              if ($conn->query($sql) === TRUE) {
                echo "";
              } else {
                echo "เกิดข้อผิดพลาดในการลบข้อมูลในตาราง car_rental: ";
              }

              // ลบข้อมูลในตาราง booking
              $sql = "DELETE FROM bookings WHERE id = $bookings_id";
              if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-danger' role='alert'>
                                    ลบข้อมูลเรียบร้อย <a href='car_check.php'>รีเฟรชข้อมูลใหม่</a>
                                  </div>";
              } else {
                echo "เกิดข้อผิดพลาดในการลบข้อมูลในตาราง booking: ";
              }
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <h3>ยืนยันการเช่า</h3>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">ลูกค้า</th>
              <th scope="col">รถ</th>
              <th scope="col">วันที่</th>
              <th scope="col">สถานะ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once "database.php";

            $sql = "SELECT bookings.*, car_rental.* 
        FROM bookings
        JOIN car_rental ON car_rental.bookings_id = bookings.id
        WHERE bookings.id IN (
            SELECT bookings_id
            FROM car_rental
            GROUP BY bookings_id
            HAVING COUNT(*) = 1
        )";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                $car_id = $row['car_id'];
                $car_brand = $row['car_brand'];
                $car_model = $row['car_model'];
                $customer_name = $row["customer_name"];
                $travel_date = $row['travel_date'];
                $return_date = $row["return_date"];
                $status = $row["status"];
                echo '<tr>';
                echo '<th scope="row">' . $i++ . '</th>';
                echo '<td>' . $customer_name . '</td>';
                echo '<td>' . $car_brand . ' ' . $car_model . '</td>';
                echo '<td>' . $rental_date . '</td>';
                echo '<td>';
                if ($status == 1) {
                  echo '<form method="POST" action="">';
                  echo '<input type="hidden" name="bookingID" value="' . $row['bookings_id'] . '">';
                  echo '<input type="hidden" name="customerName" value="' . $row['customer_name'] . '">';
                  echo '<input type="hidden" name="carID" value="' . $row['car_id'] . '">';
                  echo '<button type="submit" class="btn btn-success" name="confirm_rental">ยืนยันการจอง</button>';
                  echo '</form>';
                } else {
                  echo '<span class="badge text-bg-dark">ไม่สามารถยืนยันการจองได้</span>';
                }
                echo '</td>';
                echo '</tr>';
              }

              if (isset($_POST['confirm_rental'])) {
                $bookingID = $_POST['bookingID'];
                $status = 2;
                $carID = $_POST['carID'];
                $customerName = $_POST['customerName'];
                $currentDateTime = date("Y-m-d H:i:s");

                // เพิ่มข้อมูลใหม่
                $carRentalSql = "INSERT INTO car_rental (bookings_id, customer_name, car_id, status, rental_date) VALUES (?, ?, ?, ?, ?)";
                $carRentalStmt = mysqli_stmt_init($conn);
                $prepareCarRentalStmt = mysqli_stmt_prepare($carRentalStmt, $carRentalSql);

                if ($prepareCarRentalStmt) {
                  mysqli_stmt_bind_param($carRentalStmt, "issis", $bookingID, $customerName, $carID, $status, $currentDateTime);
                  mysqli_stmt_execute($carRentalStmt);
                  echo "<div class='alert alert-success'>เพิ่มข้อมูลสำเร็จ <a href='car_check.php'>รีเฟรชข้อมูลใหม่</a></div>";
                } else {
                  echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูล: " . mysqli_error($conn);
                }
              }
            } else {
              echo '<tr><td colspan="5">No data found.</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <h3>เปลี่ยนสถานะรถ</h3>
        <table class="table table-bordered border-primary text-center">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">รถ</th>

              <th scope="col">สถานะ</th>
              <th scope="col">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once "database.php";
            $sql = "SELECT cars.*, images.image_name 
            FROM cars
            JOIN images ON cars.car_id = images.car_id
            ORDER BY cars.car_id DESC
            ";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                $car_id = $row['car_id'];
                $car_brand = $row['car_brand'];
                $car_model = $row['car_model'];
                $status_car = $row['status_car'];


                echo '<tr>';
                echo '<th scope="row">' . $i++ . '</th>';
                echo '<td>' . $car_brand . ' ' . $car_model . '</td>';
                echo '<td>' . $status_car .  '</td>';
                echo '<td>
                            <form method="POST" action="">
                                <input type="hidden" name="car_id" value="' . $row['car_id'] . '">
                                <input type="hidden" name="status_car" value="' . $row['status_car'] . '">';
                if ($status_car == 'Ready') {
                  echo '<button type="submit" class="btn btn-warning" name="complete_status">ปิดจอง</button>';
                } else {
                  echo '<button type="submit" class="btn btn-outline-warning" name="complete_status">เปิดจอง</button>';
                }
                echo '</form>

                        </td>';
                echo '<td>
                <div class="d-grid gap-2 d-md-flex">
                        <a class="btn btn-sm btn-warning me-md-2" href="car_update.php?car_id=' . $row['car_id'] . '">แก้ไข</a>
                        <form action="" method="POST">
                          <input type="hidden" name="car_id" value="' . $row['car_id'] . '">
                          <button class="btn btn-sm btn-warning" type="submit" name="delete_car" value="Submit">ลบ</button>
                        </form>
                        </div>
                      </td>';



                echo '</tr>';
              }
              if (isset($_POST['delete_car'])) {
                $car_id = $_POST['car_id'];
            
                // ลบข้อมูลในตาราง car_rental
                $sql_car_rental = "DELETE FROM car_rental WHERE car_id = ?";
                $stmt_car_rental = $conn->prepare($sql_car_rental);
                $stmt_car_rental->bind_param("i", $car_id);
                if ($stmt_car_rental->execute()) {
                    echo "";
                } else {
                    echo "เกิดข้อผิดพลาดในการลบข้อมูลในตาราง car_rental: " . $stmt_car_rental->error;
                }
            
                // ลบข้อมูลในตาราง bookings
                $sql_bookings = "DELETE FROM bookings WHERE car_id = ?";
                $stmt_bookings = $conn->prepare($sql_bookings);
                $stmt_bookings->bind_param("i", $car_id);
                if ($stmt_bookings->execute()) {
                    echo "";
                } else {
                    echo "เกิดข้อผิดพลาดในการลบข้อมูลในตาราง bookings: " . $stmt_bookings->error;
                }
            
                // ลบข้อมูลในตาราง images
                $sql_images = "DELETE FROM images WHERE car_id = ?";
                $stmt_images = $conn->prepare($sql_images);
                $stmt_images->bind_param("i", $car_id);
                if ($stmt_images->execute()) {
                    echo "";
                } else {
                    echo "เกิดข้อผิดพลาดในการลบข้อมูลในตาราง images: " . $stmt_images->error;
                }
            
                // ลบข้อมูลในตาราง cars
                $sql_cars = "DELETE FROM cars WHERE car_id = ?";
                $stmt_cars = $conn->prepare($sql_cars);
                $stmt_cars->bind_param("i", $car_id);
                if ($stmt_cars->execute()) {
                    echo "";
                } else {
                    echo "เกิดข้อผิดพลาดในการลบข้อมูลในตาราง cars: " . $stmt_cars->error;
                }
                echo "<div class='alert alert-danger' role='alert'>ลบทั้งหมดเรียบร้อย <a href='car_check.php'>รีเฟรชข้อมูลใหม่</a></div>";
            }
            
            

              if (isset($_POST["complete_status"])) {
                $carID = $_POST["car_id"];
                $status = $_POST["status_car"];

                if ($status == 'Ready') {
                  $sqlUpdate = "UPDATE cars SET status_car = 'Not Ready' WHERE car_id = $carID";
                } elseif ($status == 'Not Ready') {
                  $sqlUpdate = "UPDATE cars SET status_car = 'Ready' WHERE car_id = $carID";
                }

                // ดำเนินการอัปเดต
                if (mysqli_query($conn, $sqlUpdate)) {
                  echo '<div class="alert alert-success">อัปเดตสถานะเรียบร้อยแล้ว<a href="car_check.php"> รีเฟรชข้อมูลใหม่</a></div>';
                } else {
                  echo '<div class="alert alert-danger">เกิดข้อผิดพลาดในการอัปเดตสถานะ<a href="car_check.php"> รีเฟรชข้อมูลใหม่</a></div>';
                }
              }
            } else {
              echo '<tr><td colspan="5">No data found.</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</body>

</html>