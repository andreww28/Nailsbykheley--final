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
    <title>NAILSBYKHELEY - SERVICE</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/nav.css">
    <link rel="stylesheet" href="../styles/service.css">
    <link rel="stylesheet" href="../styles/appointment_form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

</head>

<body>
    <?php include('../sections/nav.php') ?>

    <section class="banner">
        <div class="banner-content">
            <h2>Services</h2>
            <p>We offer luxurious experience in a way that this will bring confidence to other women who trust our
                business.</p>
        </div>
    </section>

    <div class="main-service-container">
        <div class="service-offers">
            <div class="active-offer" id="extension-offer">
                <img src="../assets/img/nail-extension.png" alt="nail extension icon">
                <p>Soft Gel Extension</p>
            </div>
            <div id="removal-offer">
                <img src="../assets/img/nail-extension.png" alt="nail extension icon">
                <p>Soft Gel Removal</p>
            </div>
        </div>

        <div class="pricing-extensions">
            <div class="pricing-heading">
                <h3>Soft Gel Extensions Pricelist</h3>
                <p>Php 700.00 (Base Price)</p>
                <p>Upgrade your nails with our softgel extensions. Choose from various styles and lengths for a perfect
                    look.</p>
            </div>

            <div class="pricing-container-additional">
                <h6>Additional</h6>
                <div class="content">
                    <div>
                        <p>Plain Color</p>
                        <p>+150</p>
                    </div>
                    <div>
                        <p>French TIp</p>
                        <p>+150</p>
                    </div>
                    <div>
                        <p>Chrome</p>
                        <p>+150</p>
                    </div>
                    <div>
                        <p>Cat Eye</p>
                        <p>+150</p>
                    </div>
                    <div>
                        <p>Ombre/Airbrush</p>
                        <p>+150</p>
                    </div>
                    <div>
                        <p>3D Art</p>
                        <p>+150</p>
                    </div>
                    <div>
                        <p>Hand Paint</p>
                        <p>+150</p>
                    </div>
                    <div>
                        <p>Hand Sculpt</p>
                        <p>+150</p>
                    </div>
                    <div>
                        <p>Charms</p>
                        <p>+150</p>
                    </div>
                    <div>
                        <p>Unli Charms</p>
                        <p>+150</p>
                    </div>
                </div>
            </div>

            <div class="pricing-container-length">
                <h6>Length</h6>
                <div class="content">
                    <div>
                        <p>Short</p>
                        <p>as is</p>
                    </div>
                    <div>
                        <p>Medium</p>
                        <p>+50</p>
                    </div>
                    <div>
                        <p>Long</p>
                        <p>+100</p>
                    </div>
                    <div>
                        <p>Extra Long</p>
                        <p>+150</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pricing-removal">
            <div class="pricing-heading">
                <h3>Removal Fee</h3>
            </div>

            <div class="pricing-container-removal">
                <div class="content">
                    <div>
                        <p>Removal Only (Without new set, my work)</p>
                        <p>Php 200.00</p>
                    </div>
                    <div>
                        <p>Removal Only (Without new set, not my work)</p>
                        <p>Php 250.00</p>
                    </div>
                    <div>
                        <p>Removal (my work) + New Set</p>
                        <p>Php 150.00</p>
                    </div>
                    <div>
                        <p>Removal (not my work) + New Set</p>
                        <p>Php 200.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="after-care">
        <div class="after-care-content">
            <h2>After Care Instructions</h2>
            <div class="instructions">
                <ul>
                    <li>Do not use your nails as tools/never pick or peel off products.</li>
                    <li>Keep your hands and nails moisturized.</li>
                    <li>Avoid long periods in water.</li>
                    <li>Do not rip off your nail extensions. It will damage your natural nails.</li>
                    <li>Your nail extensions can last from 2 weeks to months depending on how you take care Of your
                        nails.</li>
                </ul>
                <img src="../assets/img/nail3.jpg" alt="nail">
            </div>
            <button onclick="window.location.href='./appointment.php'">MAKE AN APPOINTMENT</button>
        </div>
    </section>

    <?php include('../sections/tnc.php') ?>

    <?php include('../sections/footer.php') ?>

    <script>
        const extension_container = document.querySelector(".pricing-extensions");
        const removal_container = document.querySelector(".pricing-removal");

        const extension_btn = document.querySelector('#extension-offer');
        const removal_btn = document.querySelector('#removal-offer');

        extension_btn.addEventListener('click', () => {
            removal_container.style.display = 'none';
            extension_container.style.display = 'flex';

            extension_btn.classList.remove('active-offer');
            removal_btn.classList.remove('active-offer');

            extension_btn.classList.add('active-offer');
        });

        removal_btn.addEventListener('click', () => {
            removal_container.style.display = 'flex';
            extension_container.style.display = 'none';

            removal_btn.classList.remove('active-offer');
            extension_btn.classList.remove('active-offer');

            removal_btn.classList.add('active-offer');
        });
    </script>

</body>

</html>



<?php
mysqli_close($conn);
?>