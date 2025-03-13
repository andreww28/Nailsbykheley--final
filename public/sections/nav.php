<nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
    </label>
    <a class="logo" href="index.php"><img src="../assets/img/logo.jpg" alt="Logo" srcset="" id="logo"></a>
    <ul>
        <li><a class="active" href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="service.php">Services</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="appointment.php">Appointment</a></li>
    </ul>
</nav>

<script>
    var current_page = window.location.href.split('/');
    current_page = current_page[current_page.length - 1];

    var child_num = 0;


    switch (current_page) {
        case 'index.php':
            child_num = 1;
            break;
        case 'about.php':
            child_num = 2;
            break;
        case 'service.php':
            child_num = 3;
            break;
        case 'gallery.php':
            child_num = 4;
            break;
        case 'appointment.php':
            child_num = 5;
            break;
    }

    var anchor_tag = document.querySelector(`nav ul li:nth-child(${child_num}) a`);

    document.querySelectorAll('nav ul li a').forEach(el => {
        el.classList.remove('active');
        if (anchor_tag === el) {
            el.href = '#';
        }
    })

    anchor_tag.classList.add('active');
</script>