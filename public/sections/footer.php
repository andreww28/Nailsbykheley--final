<footer>
    <div class="upper-content">
        <div class="menu-container">
            <h6>MENU</h6>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="appointment.php">Appointment</a></li>
            </ul>
        </div>
        <div class="contact-info">
            <h6>CONTACT INFO</h6>
            <ul>
                <li>(+63)936 372 3865</li>
                <li>kheleyvaleriealcantara@gmail.com</li>
                <li id="full-address">Mt. Apo st. Demesa compound Brgy Gulang-Gulang Lucena City</li>
            </ul>
        </div>

        <div class="social-info">
            <h6>SOCIAL</h6>
            <ul>
                <li><a href="https://facebook.com/profile.php?id=100085055773212" target="_blank">
                        <i class="fab fa-facebook"></i>
                        <p>nailsbykheley</p>
                    </a>
                </li>
                <li><a href="https://facebook.com/profile.php?id=100085055773212" target="_blank">
                        <i class="fab fa-instagram-square"></i>
                        <p>nailsbykheley</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="copyright-container">
        <p>© 2024, <span class="primary-text">NAILSBYKHELEY</span>. All Rights Reserved.</p>
        <button>Terms and Condition</button>
    </div>
</footer>

<script>
    function set_tnc_agree_session(val) {
        const data = {
            key: 'tnc_agree',
            value: val,
            action: 'set_session'
        };

        fetch('appointment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    var footer_tnc_btn = document.querySelector('.copyright-container button');
    footer_tnc_btn.addEventListener('click', () => {
        TNC.show();
        TNC.hide_action_btn();
    })
</script>