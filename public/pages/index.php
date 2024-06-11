<?php
include('../../utils/connection.php');


$query = "SELECT * FROM highlights";
$result = mysqli_query($conn, $query);
$highlights = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT * FROM gallery WHERE _type='featured'";
$result = mysqli_query($conn, $query);
$featured_item_gallery = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
    <link rel="stylesheet" href="../styles/loading.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
    <?php include('../sections/nav.php') ?>
    <!-- <?php include('../sections/tnc.php') ?> -->

    <section class="hero">
        <div class="hero-content">
            <h1>Unlock Your Nail Potential with Gel Perfection</h1>
            <p><span style="color: var(--primary-color); font-weight: 700">Nailsbykheley</span> is dedicated to bringing
                unique designs
                mixed with expert techniques. Offering services
                like softgel extensions and extensions removal.</p>
            <button type="button" id="appointment-btn" class="btn">MAKE AN APPOINTMENT</button>
        </div>

        <div class="hero-imgs">
            <div class="img-hero img-hero1">
                <img src="../assets/img/nail1.jpg" alt="">
            </div>
            <div class="img-hero img-hero2">
                <img src="../assets/img/nail2.jpg" alt="">

            </div>
            <div class="img-hero img-hero3">
                <img src="../assets/img/nail4.jpg" alt="">

            </div>
        </div>

    </section>

    <div class="tagline-section">
        <h6> "It's a nailcessity" </h6>
    </div>

    <?php
    if (!empty($highlights)) { ?>
    <section class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php foreach ($highlights as $index => $highlight) {

                ?>
            <div class="swiper-slide"><img
                    src="<?php echo "../../admin/assets/uploads/highlight/" . $highlight['image_path'] ?>"></div>
            <?php

                } ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </section>
    <?php
    }
    ?>

    <section class="featured-section">
        <h2>Featured Works</h2>
        <?php
        if (!empty($featured_item_gallery)) { ?>
        <section class="swiper featuredSwiper">
            <div class="swiper-wrapper">
                <?php foreach ($featured_item_gallery as $index => $item) {

                    ?>
                <div class="swiper-slide"><img
                        src="<?php echo "../../admin/assets/uploads/gallery/" . $item['image_path'] ?>"></div>
                <?php

                    } ?>
            </div>
            <!-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> -->
            <div class="swiper-pagination"></div>
        </section>
        <?php
        }
        ?>
        <button class="view-more-gallery">View More</button>
    </section>

    <section class="LCard">
        <div class="img-container">
            <img src="../assets/img/frontCard.jpg" alt="Loyalty Card">
        </div>


        <div class=" Loyaltycard ">
            <h2> Loyalty Card </h2>

            <p>Explore the advantages of our Loyalty Card Program! Receive a complimentary reward with every
                10th
                purchase, a gesture of our gratitude for your loyal support.</p>
        </div>

    </section>



    <section class="contact-section">
        <div class="contact">

            <div class="map-div">
                <!-- sample lang -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3871.8986614784308!2d121.60758207508788!3d13.964637192330304!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTPCsDU3JzUyLjciTiAxMjHCsDM2JzM2LjYiRQ!5e0!3m2!1sen!2sph!4v1717567820699!5m2!1sen!2sph"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="contact-content">
                <h2>Contact Us</h2>
                <form action="" id="contact-form">
                    <div>
                        <label for>Email: </label>
                        <input type="text" name="email" id="email" placeholder="your_email@gmail.com" required>
                    </div>

                    <div>
                        <label>Subject: </label>
                        <input type="text" name="subject" id="subject" placeholder=" Subject places" required>
                    </div>

                    <div>
                        <label for="message "> Message: </label>
                        <textarea id="message" name="message" rows="8" required> </textarea>
                    </div>

                    <div class="sendbtn">
                        <button type="submit" id="submit-btn" name="send">Send</button>
                    </div>
                </form>
                <div class="other-contact-info">
                    <p>(+63)936 372 3865</p>
                    <div class="soc-media">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram-square"></i></a>
                    </div>
                </div>
            </div>

            <div class="loading-bg">
                <div class="loading-container">
                    <div class="loading"></div>
                    <div id="loading-text">Sending</div>
                </div>
            </div>
        </div>
    </section>

    <!-- <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
    <div class="elfsight-app-257cf3d3-ce03-46af-9f60-95acc872d295" data-elfsight-app-lazy></div> -->


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        // effect: 'cards'
    });

    var swiper2 = new Swiper(".featuredSwiper", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: true,
        },
        pagination: {
            el: ".swiper-pagination",
        },
    });

    var appointment_f_wrapper = document.querySelector('.a-container');
    var appointment_f_box = document.querySelector('.form-box');
    var appointment_btn = document.getElementById('appointment-btn');

    appointment_btn.addEventListener("click", function() {
        window.location.href = "./appointment.php";
    });

    document.querySelector('.view-more-gallery').addEventListener('click', () => window.location.href =
        './gallery.php');
    </script>

    <?php include('../sections/tnc.php') ?>


    <?php include('../sections/footer.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    const form = document.getElementById('contact-form');
    const loading_c = document.querySelector('.loading-bg');

    form.addEventListener('submit', submitData);

    function submitData(e) {
        e.preventDefault();
        loading_c.style.display = 'flex';

        // Ensure the form element is correctly referenced
        const form = e.target;
        const formData = new FormData(form);
        formData.append('action', 'send_from_home');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/send_email.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            document.querySelector('.loading-bg').style.display = 'none';
                            console.log(loading_c.style.display);
                            Swal.fire({
                                position: "center",
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            form.reset();
                        } else {
                            alert(response.message);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        alert('There was an error processing the response.');
                    }
                } else {
                    console.error('Error:', xhr.status);
                    alert('There was an error with the request.');
                }
            }
        };

        xhr.send(formData);
    }
    </script>
</body>

</html>



<?php
mysqli_close($conn);
?>