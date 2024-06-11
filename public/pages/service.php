<?php
include('../../utils/connection.php');


$query = "SELECT * FROM highlights";
$result = mysqli_query($conn, $query);
$highlights = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/nav.css">
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/appointment_form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

</head>

<body>
    <?php include('../sections/nav.php') ?>


    <?php include('../sections/appointment_form.php') ?>


    <?php include('../sections/tnc.php') ?>

    <?php include('../sections/footer.php') ?>

</body>

</html>



<?php
mysqli_close($conn);
?>