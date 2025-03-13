<div class="full-info-wrapper">
    <div class="full-info-container">
        <i class="fas fa-times"></i>
        <h5>APPT-20240526-00001</h5>
        <div class="full-info-content">
            <div class="personal-info">
                <p>Full Name:</p>
                <p>John Andrew E. San Victores</p>
                <p>Address:</p>
                <p>Brgy. Bataan Sampaloc, Quezon</p>
                <p>Email:</p>
                <p>johnandrewsanvictores@gmail.com</p>
                <p>Mobile Number:</p>
                <P>09167003378</P>
            </div>

            <div class="user-condition">
                <p>First Time of Manicure/Pedicure: </p>
                <p>Yes</p>
                <p> Experienced allergic reactions or irritations from nail or skin products:</p>
                <p>Yes, peanuts</p>
                <p>Engage in hands-on hobbies or sports activities:</p>
                <p>Yes, Chess</p>
                <p>Medical/Skin Condition: </p>
                <p>Diabetes</p>
                <p>Nail Condition: </p>
                <p>Normal</p>
            </div>

            <div class="appointment-info">
                <p>Service:</p>
                <p>Soft-Gel Extension</p>
                <p>Appointment Date: </p>
                <p>2024-05-06</p>
                <p>Appoinment Time: </p>
                <p>1:00AM - 2:00AM</p>
                <p>Status:</p>
                <p>Pending</p>
            </div>
        </div>
    </div>
</div>

<style>
    .full-info-wrapper {
        position: fixed;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, .8);
        top: 0;
        left: 0;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: -999;
        opacity: 0;
        transform: scale(0);

    }

    .full-info-container {
        background-color: var(--font-white);
        color: var(--font-dark);
        border: 4px solid var(--primary-color);
        border-radius: 10px;
        position: relative;
        transition: 0.5s ease;
        opacity: 0;
        transform: scale(0);
    }

    .full-info-container h5 {
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        width: 100%;
        background-color: var(--primary-color);
        color: var(--font-white);
        padding: 0.25em 0.5em;
        font-family: var(--body-font);
    }

    .full-info-container i {
        position: absolute;
        top: 10px;
        right: 10px;
        color: var(--font-white);
        font-size: 1.5rem;
        cursor: pointer;
    }

    .full-info-content {
        padding: 1em 2em;
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    .full-info-content div {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 1em 2em;
    }

    .full-info-container div p {
        width: 100%;
        max-width: 32ch;
    }

    .full-info-container div p:nth-child(odd) {
        font-weight: 700;
        color: var(--primary-color);
    }
</style>

<script>
    const Full_info_functions = (function() {
        const full_info_container = document.querySelector('.full-info-container');
        const full_info_wrapper = document.querySelector('.full-info-wrapper');

        function display_info(array_value) {
            document.querySelectorAll('.full-info-content > div >p:nth-child(even)').forEach((el, index) => {
                el.textContent = array_value[index];
                document.querySelector('.full-info-wrapper').style.display = 'flex';
            })

            document.querySelector('.full-info-container h5').textContent = array_value[array_value.length - 1];
        }

        function show_full_info_popup() {
            full_info_wrapper.style.zIndex = "9999";
            full_info_wrapper.style.opacity = '1';
            full_info_wrapper.style.transform = 'scale(1)';
            full_info_container.style.opacity = '1';
            full_info_container.style.transform = 'scale(1)';
        }

        function hide_full_info_popup() {
            full_info_wrapper.style.zIndex = "-1";
            full_info_wrapper.style.opacity = '0';
            full_info_wrapper.style.transform = 'scale(0)';
            full_info_container.style.opacity = '0';
            full_info_container.style.transform = 'scale(0)';

        }

        function convertTo12HourFormat(time24) {
            // Parse the input time
            var time = time24.split(':');
            var hours = parseInt(time[0]);
            var minutes = parseInt(time[1]);

            // Convert to 12-hour format
            var suffix = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // If hours is 0, set it to 12

            // Format the time
            var time12 = hours + ':' + (minutes < 10 ? '0' + minutes : minutes) + ' ' + suffix;

            return time12;
        }

        return {
            display_info,
            hide_full_info_popup,
            show_full_info_popup,
            convertTo12HourFormat
        }
    })();

    document.querySelector('.full-info-container i').addEventListener('click', Full_info_functions.hide_full_info_popup);
</script>