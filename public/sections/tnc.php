<style>
    .modal-tnc-wrapper {
        background-color: rgba(0, 0, 0, 0.8);
        position: fixed;
        width: 100vw;
        height: 100vh;
        top: 0;
        left: 0;
        z-index: 9999;
        padding: 2em;
        z-index: -999;
        opacity: 0;
        transform: scale(0);
        padding: 1em;
    }

    .modal-tnc-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: var(--dark-grey);
        color: var(--font-white);
        width: 100%;
        max-width: 25em;
        padding-bottom: 1em;
        transition: 0.5s ease;
        opacity: 0;
        transform: translate(-50%, -50%) scale(0);
    }

    .title-container {
        width: 100%;
        padding: 1em;
        color: var(--font-white);
        background-color: var(--primary-color);
        text-align: center;
        font-weight: 700;
        position: relative;
    }

    .title-container i {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #fff;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .modal-tnc-content {
        padding: 1em;
    }

    .modal-tnc-content ul {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
        height: 100%;
        max-height: 23em;
        overflow-y: scroll;
    }

    .modal-tnc-content ul li {
        list-style-type: disc;
        list-style: disc;
    }

    .button-div {
        width: 100%;
        display: flex;
        gap: 1em;
        align-items: center;
        justify-content: center;
    }

    .button-div button {
        padding: 0.25em 0.5em;
        border: none;
        outline: none;
        cursor: pointer;
        border-radius: 5px;
    }

    #agree-btn {
        background-color: var(--primary-color);
        color: #fff;
    }

    #disagree-btn {
        background-color: transparent;
        color: #fff;
        border: 2px solid var(--primary-color);
    }

    .button-div button:hover {
        background-color: var(--pink-light);
    }
</style>


<div class="modal-tnc-wrapper">
    <div class="modal-tnc-container">
        <div class="title-container">
            <i class="fas fa-times"></i>
            <p>Terms and Conditions</p>
        </div>

        <div class="modal-tnc-content">
            <ul>
                <li>-To secure your appointment, 100 pesos non refundable downpayment is required.</li>
                <li>-A 50% cancellation fee will be charged for cancellation.</li>
                <li>-We do not tolerate late clients. <strong>Cancel booking</strong> if you are 15 minutes late.</li>
                <li>-Remember that I have clients after you so respect other people's time.</li>
                <li>-No show will result in 50% of the service booked.</li>
                <li>-No show for the second time will no longer be able to book at Nailsbykheley.</li>
                <li>-Keep in mind that your appointment can take anywhere from 1 1/2 to 2 hours depending on the design.
                </li>
                <li>-So if you are expecting fast service. I may not be the nail tech for you.</li>
            </ul>
        </div>
        <div class="button-div">
            <button type="button" id='agree-btn'>I AGREE</button>
            <button type="button" id='disagree-btn'>I DISAGREE</button>
        </div>
    </div>
</div>

<script>
    document.querySelector(".title-container i").addEventListener('click', () => {
        TNC.hide();
    });

    const TNC = (function() {
        var tnc_wrapper = document.querySelector('.modal-tnc-wrapper');
        var tnc_container = document.querySelector('.modal-tnc-container');
        var agree_btn = document.getElementById('agree-btn');
        var disagree_btn = document.getElementById('disagree-btn');


        function show() {
            tnc_wrapper.style.zIndex = "9999";
            tnc_wrapper.style.opacity = '1';
            tnc_wrapper.style.transform = 'scale(1)';
            tnc_container.style.opacity = '1';
            tnc_container.style.transform = 'translate(-50%, -50%) scale(1)';
        };

        function hide() {
            tnc_wrapper.style.zIndex = "-1";
            tnc_wrapper.style.opacity = '0';
            tnc_wrapper.style.transform = 'scale(0)';
            tnc_container.style.opacity = '0';
            tnc_container.style.transform = 'translate(-50%, -50%) scale(0)';

        }

        function agree_add_event(callback) {
            agree_btn.addEventListener('click', callback);
        }

        function disagree_add_event(callback) {
            disagree_btn.addEventListener('click', callback);
        }

        function hide_action_btn() {
            agree_btn.style.display = 'none';
            disagree_btn.style.display = 'none';
        }

        return {
            show,
            hide,
            agree_add_event,
            disagree_add_event,
            hide_action_btn
        }
    })();
</script>