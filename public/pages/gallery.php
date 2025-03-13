<?php
include('../../utils/connection.php');


function getData($query, $conn)
{
    $data = array();
    // Prepare statement
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Fetch data as associative array
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        // Free result set
        mysqli_free_result($result);
    }

    return $data;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql1 = "SELECT * FROM gallery";
    $sql2 = "SELECT * FROM themes";

    $gallery_items = getData($sql1, $conn);
    $themes = getData($sql2, $conn);

    // Close connection
    // Convert the result to JSON and send it back to JavaScript

    $output = array(
        'gallery_items' => $gallery_items,
        'themes' => $themes
    );

    echo json_encode($output);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAILSBYKHELEY - GALLERY</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/nav.css">
    <link rel="stylesheet" href="../styles/gallery.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css" />


    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

</head>

<body>
    <?php include('../sections/nav.php') ?>


    <main>
        <section class="banner">
            <div class="banner-content">
                <h2>Browse Our Stunning Soft Gel Nail Designs</h2>
                <p>Explore our gallery filled with exquisite soft gel nail extensions. From timeless classics to bold
                    statement pieces, find the perfect design to elevate your style.</p>
                <div class="input-container">
                    <i class="fas fa-search"></i>
                    <input type="search" class="search-query" placeholder="Search" value="">
                    <select name="themes">

                    </select>
                </div>
            </div>
        </section>
        <section class="main">
            <div class="main-content">

            </div>

        </section>
        <section class="appointment">
            <input type="button" value="MAKE AN APPOINTMENT" id="AptBtn">
        </section>

    </main>

    <?php include('../sections/tnc.php') ?>

    <?php include('../sections/footer.php') ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>

    <script src="../scripts/gallery.js"></script>

</body>

</html>



<?php
mysqli_close($conn);
?>