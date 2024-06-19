var appt_form = document.querySelector('.a-container');
var view_form_login = document.getElementById('view-apt-form');
// var btn_tnc = document.querySelector('.book-apt-placeholder button');
var tabs = document.querySelectorAll('.tab-section div');
var book_apt_placeholder = document.querySelector('.book-apt-placeholder');
var view_apt_login = document.querySelector('.view-appointment-login');
var all_apt_info = document.querySelector('.all-apt-info');
var logout_btn = document.querySelector('#logout-btn');
var tab_open = "set";

var imp_text = document.querySelectorAll('#appt-val .val, #v-code-val .val');
var submit_ok_btn = document.querySelector('#ok-btn-submit-popup');

document.addEventListener("DOMContentLoaded", function() { 
    console.log(imp_text);
    
    console.log("hl");
    Request.check_session('tnc_agree');
    APPT_Utils.book_containers_state();
    view_form_login.addEventListener('submit', Request.submitData);
    
    // btn_tnc.addEventListener('click', () => {
    //     TNC.show();
    //     TNC.agree_add_event(Init.tnc_agree_func);
    //     TNC.disagree_add_event(Init.tnc_disagree_func)
    // })

    TNC.hide();
    TNC.show();
    TNC.agree_add_event(Init.tnc_agree_func);
    TNC.disagree_add_event(() => window.location = './index.php');
    
    
    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            tab_el = e.target;
            
            if (tab_el.tagName === "P") tab_el = e.target.parentElement;
            
        if (tab_el.id === 'book-apt') {
            Init.book_tab_click();
    
        } else if (tab_el.id === 'view-apt') {
            Init.view_tab_click();
        }
        
        document.querySelectorAll('#view-apt, #book-apt').forEach(el => el.style.background =
            'var(--dark-grey)');
            
            tab_el.style.background = 'var(--primary-color)';
        })
    })

    imp_text.forEach(el => {
        el.addEventListener('click', APPT_Utils.copy_text)
    })

    submit_ok_btn.addEventListener('click', Submit_Popup.hide);


})




const APPT_Utils = (function() {
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

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function display_appt_data(data) {
        console.log(data);

        var {address, allergicReaction, appointment_date, conditionId, email, end_time, first_time, fullName, hasAllergic, isParticipatedSport, medicalCondition, mnumber, nailCondition, referenceNum, service, sportName, start_time, status, submission_time, userId, verification_code} = data;

        var array_value = [
            appointment_date, `${convertTo12HourFormat(start_time)} - ${convertTo12HourFormat(end_time)}`, service, capitalizeFirstLetter(fullName), address, `${email ? email : 'N/A'}`, mnumber, capitalizeFirstLetter(first_time), `${capitalizeFirstLetter(hasAllergic)} ${allergicReaction ? ', ' + allergicReaction : ''}`, `${capitalizeFirstLetter(isParticipatedSport)} ${sportName ? ', ' + capitalizeFirstLetter(sportName) : ''}`, `${medicalCondition ? medicalCondition : 'N/A'}`, `${nailCondition ? nailCondition : 'N/A'}`
        ]

        
        document.querySelector('.imp-info h5').textContent = referenceNum;
        document.querySelector('.imp-info h6 span').textContent = capitalizeFirstLetter(status);
        if(status === 'pending' || status === 'cancelled') {
            document.querySelector('.imp-info h6 span').style.color = '#fa5757';
        }else if(status === 'confirmed' || status === 'completed') {
            document.querySelector('.imp-info h6 span').style.color = 'green';
        }
        
        document.querySelectorAll('.all-apt-info > div > .content >p:nth-child(even)').forEach((el, index) => {
            el.textContent = array_value[index];
        });
        
        view_apt_login.style.display = 'none';
        all_apt_info.style.display = 'flex';

        console.log(status);

        logout_btn.addEventListener('click', () => Init.logout());

        if(status === 'pending' || status === 'confirmed') {
            document.querySelector('.all-apt-info > button').style.display = 'block';
            document.querySelector('.all-apt-info > button').addEventListener('click', () => Init.cancelBooking(data));
        }if(status === 'completed' || status === 'cancelled') {
            document.querySelector('.all-apt-info > button').style.display = 'none';
         }

    }
    
    function change_form_state(data, key) {
        console.log(key);
        if(key === 'tnc_agree') {
            book_containers_state();
            if (data === 'true' ) {
                 Form_SetUp.showForm();
    
            } else {
                book_apt_placeholder.style.display = 'flex';
                Form_SetUp.hideForm();
            }
        }else if(key === 'view_login') {
            view_containers_state();
            if(data === 'null') {
                view_apt_login.style.display = 'flex';
                all_apt_info.style.display = 'none';
            }else {
                view_apt_login.style.display = 'none';
                all_apt_info.style.display = 'flex';
                // display_appt_data(JSON.parse(data));
                const f = new FormData();
                f.append('action', 'view_again');
                Request.request_submit(f);
            }
        }
    }

    function book_containers_state() {
        view_apt_login.style.display = 'none';
        all_apt_info.style.display = 'none';
    }

    function view_containers_state() {
        appt_form.style.display = 'none';
    }

    async function copy_text(e) {
        try {
            var txt = e.target.textContent;
            console.log(txt);
            await navigator.clipboard.writeText(txt);
            console.log('Content copied to clipboard');

            var x = document.getElementById("snackbar");

            // Add the "show" class to DIV
            x.className = "show";

            // After 3 seconds, remove the show class from DIV
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
          } catch (err) {
            console.error('Failed to copy: ', err);
          }
    }
    
    return {
        display_appt_data,
        change_form_state,
        book_containers_state,
        view_containers_state,
        copy_text
    }
})();



const Init = (function() {
    function tnc_agree_func(){
        //Request.set_session('tnc_agree', 'true');
        TNC.hide();
        if (tab_open === 'set') {
            appt_form.style.display = 'block';
            APPT_Utils.book_containers_state();
        }
    }

    function tnc_disagree_func() {
        Request.set_session('tnc_agree', 'false');
        TNC.hide();
        if (tab_open === 'set') {
            appt_form.style.display = 'none';
            book_apt_placeholder.style.display = 'flex';
        }
    }

    function book_tab_click() {
        //Request.check_session('tnc_agree');
        tab_open = 'set';
        //Request.check_session('tnc_agree');
        APPT_Utils.book_containers_state();
        TNC.show();
        TNC.agree_add_event(Init.tnc_agree_func);
        TNC.disagree_add_event(() => window.location = './index.php');
    }

    function view_tab_click() {
        tab_open = 'view';
        APPT_Utils.view_containers_state();
        Request.check_session('view_login');
    }

    function logout() {
        
        var msg = "Are you sure you want to logout?";
        
        PopUp.showConfirm(msg, () => {
            Request.unset_session('view_login');
            all_apt_info.style.display = 'none';
            view_apt_login.style.display = 'flex';
            view_form_login.reset();

            document.querySelectorAll('.all-apt-info > div > .content >p:nth-child(even)').forEach((el, index) => {
                el.textContent = '';
            });
        })
    }

    function cancelBooking(data) {
        var msg = "";

        console.log(data.status);

        if(data.status === 'pending') {
            msg = "Are you sure you want to cancel this booking? This action is irreversible.";
            PopUp.showConfirm(msg, () => {
                Request.setCancelStatus(data, "delete_pending");
            });
        }else if(data.status === 'confirmed') {
            msg = "Are you sure you want to cancel this booking? A 50% cancellation fee will be charged for confirmed bookings";
            PopUp.showConfirm(msg, () => {
                Request.setCancelStatus(data, "delete_confirmed");
            });
        }
        
    }

    return {
        tnc_agree_func,
        tnc_disagree_func,
        book_tab_click,
        view_tab_click,
        logout,
        cancelBooking
    }
})();


const Request = (function() {
    function unset_session(key) {
        const data = {
            key: key,
            action: 'unset_session'
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

    function set_session(key, val) {
        const data = {
            key: key,
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

    function check_session(k) {
        console.log(k);
        const data = {
            action: 'check_tnc_agree',
            key: k,
            val: 'check'
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
                console.log(data);
                APPT_Utils.change_form_state(data.message, data.key);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    function submitData(event) {
        console.log('fhjfsdhjksdf')
        event.preventDefault();
        const formData = new FormData(view_form_login);
        request_submit(formData);
    }

    function request_submit(data) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/view_appointments.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log(response.message)
                        APPT_Utils.display_appt_data(response.data);
                    } else {
                        PopUp.showMessage(response.message, 'error');
                    }
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        }
        xhr.send(data);
    }

    function setCancelStatus(data, action) {
        const xhr = new XMLHttpRequest();

        const requestBody = 'ids=' + JSON.stringify([data.referenceNum]) + `&action=${action}`; // Serialize array to JSON string
        xhr.open('POST', '../../api/appointment.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                            Request.unset_session('view_login');
                            all_apt_info.style.display = 'none';
                            view_apt_login.style.display = 'flex';
                            view_form_login.reset();
                            notification_add_cancel(data.referenceNum);
                            send_email(data);
                    } else {
                        PopUp.show_message(response.message, "error");
                    }
                } else {
                    alert('Error occurred while processing your request.'); // Error message
                }
            }
        };

        xhr.send(requestBody);
    }

    function notification_add_cancel(refNo) {
        const title = "Appointment Cancellation";
        const msg = `${refNo} cancelled his/her appointment.`

        const xhr = new XMLHttpRequest();
        const url = '../../api/notifications.php';
        const requestBody = `action=add_data&title=${title}&msg=${msg}`;

        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Request successful, handle response if needed
                    console.log(xhr.responseText);
                    console.log(refNo);
                } else {
                    // Error handling
                    console.error('Error:', xhr.statusText);
                }
            }
        };

        xhr.send(requestBody);
    }

    function send_email(data) {
        const requestBody = `action=send_from_view_appt&refNo=${data.referenceNum}&name=${data.fullName}&mnumber=${data.mnumber}&email=${data.email}`;
        const xhr = new XMLHttpRequest();
            xhr.open('POST', '../../api/send_email.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                console.log(response.message);
                                alert(response.message);
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

            xhr.send(requestBody);
    }


    return {
        set_session,
        unset_session,
        check_session,
        submitData,
        setCancelStatus,
        request_submit
    }
})();


const Submit_Popup = (function() {
    var container = document.querySelector('.submit-popup');

    function show() {
        container.style.display = 'flex';
    }

    function hide() {
        container.style.display = 'none';
    }

    function set_important_text(ref, verif) {
        imp_text[0].textContent = ref;
        imp_text[1].textContent = verif;
    }

    return {
        show, hide, set_important_text
    }
})();


const PopUp = (function() {
    function showMessage(msg, icon) {
        Swal.fire({
            position: "center",
            icon: icon,
            title: msg,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function showConfirm(msg, callback) {
        Swal.fire({
            text: msg,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }

    return {
        showConfirm, showMessage
    }
})();