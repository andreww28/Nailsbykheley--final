




document.addEventListener("DOMContentLoaded", function() {

    Password.add_events();
    Navbar.add_events();
    Sidebar.add_events();

    Notifications.set_notif_count();
});


const Password = (function() {
    const modal_pass = document.querySelector('.modal-pass');
    function show_menu() {
        modal_pass.style.visibility = 'visible';
        modal_pass.style.opacity = '1';
    }
    
    function hide_menu() {
        modal_pass.style.visibility = 'hidden';
        modal_pass.style.opacity = '0';
    }

    function add_events() {
        document.querySelector("#close-btn").addEventListener('click', hide_menu);
        document.querySelector("#change-pass-btn").addEventListener('click', show_menu);
        document.querySelector("#change-btn").addEventListener('click', change_btn_event);
    }

    function change_btn_event(e) {
        hide_menu();
        e.preventDefault();
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirm"
            }).then((result) => {
            if (result.isConfirmed) {
                change_pass_post();
            }
        });
    }
    
    function change_pass_post() {
        var error_p = document.querySelector('.modal-pass #error-p');
        var oldPass = document.querySelector(".modal-pass #old_pass");
        var newPass = document.querySelector(".modal-pass #new_pass");
        var confirmPass = document.querySelector("#confirm_pass");
    
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../utils/update_password.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
        xhr.onload = function() {
            var response = JSON.parse(this.responseText);
            if (response.success) {
                hide_menu();
                Swal.fire({
                    title: response.message,
                    confirmButtonText: "Okay",
                    icon: "success"
                    }).then((result) => {

                    if (result.isConfirmed) {
                        window.location.href = "../admin/login.php";
                    }
                    });
            } else {
                // Display error message
                Password.show_menu();
                error_p.textContent = response.message;
                error_p.style.display = 'block';
    
                oldPass.value = "";
                newPass.value = "";
                confirmPass.value = "";

            }
        };
    
        xhr.send("old_pass=" + oldPass.value + "&new_pass=" + newPass.value + "&confirm_pass=" + confirmPass.value);
    }

    return {
        show_menu,
        hide_menu,
        change_pass_post,
        add_events
    }
})();

const Sidebar = (function() {
    const submenuItems = document.querySelectorAll(".submenu_item");

    function add_events() {
        submenuItems.forEach((item, index) => {
            item.addEventListener("click", () => {
                item.classList.toggle("show_submenu");
                submenuItems.forEach((item2, index2) => {
                    if (index !== index2) {
                        item2.classList.remove("show_submenu");
                    }
                });
            });
        });
    }

    return {
        add_events
    }
})();

const Navbar = (function() {
    const profile_container = document.querySelector('.profile-container');
    const bell_icon = document.querySelector('.notif-div > div');

    function profile_container_event() {
        document.querySelector('.dp-menu').classList.toggle('active');
    }

    function bell_icon_event() {
        document.querySelector('.notif-container').classList.toggle('active');
    }

    function add_events() {
        profile_container.addEventListener('click', profile_container_event)
        bell_icon.addEventListener('click', Notifications.bell_icon_event)
        Notifications.add_events();
    }

    return {
        add_events
    }
})();


const Notifications = (function() {
    var notif_container = document.querySelector('.notif-list');

    function fetch_notifications(action) {
        return new Promise((resolve, reject) => {
    
            var xhr = new XMLHttpRequest();
        
            const requestBody =  `action=${action}`;
            var url = '../../api/notifications.php';
            xhr.open('POST', url, true);
    
            // Set the appropriate header
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
            // Define the callback function
            xhr.onreadystatechange = function() {
                if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                    // Handle the response from PHP (data fetched from the database)
                    resolve(JSON.parse(xhr.responseText));
                }
            };
    
            // Send the request
            xhr.send(requestBody);
        });
    }

    function update_notifications(id, action, is_all){
        console.log(id)
        const xhr = new XMLHttpRequest();
        const url = '../../api/notifications.php';
        const requestBody = `action=${action}&${is_all ? "all=" + is_all : `id=${id}` }`;

        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Request successful, handle response if needed
                    console.log(xhr.responseText);
                    set_notif_count();
                } else {
                    // Error handling
                    console.error('Error:', xhr.statusText);
                }
            }
        };

        xhr.send(requestBody);
    }

    async function set_notif_count() {

        var data = await Notifications.fetch_notifications('get_notif_count');
        var count = parseInt(data.count[0].count);
        console.log(data)
        if(count === 0) {
            document.querySelector('.notif-number-div').style.display = 'none';
        }else {
            document.querySelector('.notif-number-div').style.display = 'flex';
            document.querySelector('#notification-count').textContent = count;
        }
    }

    async function bell_icon_event(e) {
        document.querySelector('.notif-container').classList.toggle('active');
        var data = await Notifications.fetch_notifications('get_data');
        console.log(data);
        Notifications.display_data(data.data);
    }

    function get_date_time(timestamp) {
        const date = new Date(timestamp);
        const formattedDate = date.toLocaleString('en-US', { month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true });
        return formattedDate;
    }

    function get_ref_no_from_msg(title, msg) {
        const msg_arr = msg.split(" ");
        const refNo = title === "Cancel Appointment" ? msg_arr[0] : msg_arr[msg_arr.length - 1].slice(0,-1).replace(/[\(\)]/g, '');
        return refNo;
    }

    function display_data(data) {
        notif_container.innerHTML = "";

        if(data.length === 0) {
            set_empty_notif();
            return;
        }

        data.forEach(d => {
            if(d.title === 'New Appointment') {
                notif_container.innerHTML += `
                    <div class="notif-item ${d.read_status}" id="item${d.id}">
                        <i class="fas fa-user-clock"></i>
                        <h6>${d.title}</h6>
                        <p>You have new pending appointment (<span class="notif-refNo">${get_ref_no_from_msg(d.title, d.message)}</span>).</p>
                        <p class="notif-date">${get_date_time(d.timestamp)}</p>
                        ${d.read_status === "unread" ? `<div class=" read-indicator">
                        </div>` : ""}
                        <i class="fas fa-times remove-btn-notif" id="${d.id}"></i>
                    </div>
                
                    `
            } else if(d.title === 'Cancel Appointment') {
                notif_container.innerHTML += `
                    <div class="notif-item cancel ${d.read_status}" id="item${d.id}">
                        <i class="fas fa-user-minus"></i>
                        <h6>${d.title}</h6>
                        <p><span class="notif-refNo">${get_ref_no_from_msg(d.title, d.message)}</span> cancelled his/her appointment.</p>
                        <p class="notif-date">${get_date_time(d.timestamp)}</p>
                        ${d.read_status === "unread" ? `<div class="read-indicator">
                        </div>` : ""}
                        <i class="fas fa-times remove-btn-notif" id="${d.id}"></i>
                    </div>
                
                    `
            }
        });

        add_close_notif_events();
        add_notif_item_events();
    }

    function add_close_notif_events() {
        document.querySelectorAll('.remove-btn-notif').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation();
                const id = this.getAttribute('id');
                update_notifications(id, "remove_data");
                // Optionally, remove the notification from the UI
                this.closest('.notif-item').remove();
            });
        });
    }

    function add_notif_item_events() {
        document.querySelectorAll('.notif-item').forEach(el => {
            const refNo = el.querySelector('.notif-refNo').textContent;
            el.addEventListener('click', (e) => {
                let id = parseInt(el.getAttribute('id').match(/\d+/)[0])
                update_notifications(id, "update_status");
                if(el.classList.contains('cancel')){
                    window.location.href = "../pages/cancelledAppointment.php" + "?param=" + encodeURIComponent(refNo);
                }else {
                    window.location.href = "../pages/pendingAppointment.php" + "?param=" + encodeURIComponent(refNo);
                }
            })

        });
    }

    function add_events() {

        add_close_notif_events();
        add_notif_item_events();

        document.querySelector('#notif-mark-btn').addEventListener('click', () => {
            document.querySelectorAll('.read-indicator').forEach(el => el.remove());
            document.querySelectorAll('.notif-item').forEach(el => {
                el.classList.replace('unread', 'read');
            })
            update_notifications(0, "update_status", 'all');
        })
        document.querySelector('#notif-clear-all').addEventListener('click', () =>{
            update_notifications(0, "remove_data", 'all');
            set_empty_notif();
        })
    }

    function set_empty_notif() {
        notif_container.innerHTML = "";
        notif_container.innerHTML += `<p id="notif-placeholder-text">No new notifications at the moment.</p>`
    }

    return {
        fetch_notifications,
        bell_icon_event,
        display_data,
        add_events,
        set_notif_count
    }

})();


// profile_container.addEventListener('click', () => {
//     document.querySelector('.dp-menu').classList.toggle('active');
// })

// console.log(bell_icon)
// bell_icon.addEventListener('click', () => {
//     console.log('hi')
//     document.querySelector('.notif-container').classList.toggle('active');
// })


// submenuItems.forEach((item, index) => {
//     item.addEventListener("click", () => {
//         item.classList.toggle("show_submenu");
//         submenuItems.forEach((item2, index2) => {
//             if (index !== index2) {
//                 item2.classList.remove("show_submenu");
//             }
//         });
//     });
// });


// document.querySelector("#close-btn").addEventListener('click', hide_menu);
// document.querySelector("#change-pass-btn").addEventListener('click', show_menu);
// document.querySelector("#change-btn").addEventListener('click', (e) => {
//     e.preventDefault();
//     Swal.fire({
//         title: "Are you sure?",
//         text: "You won't be able to revert this!",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Confirm"
//         }).then((result) => {
//         if (result.isConfirmed) {
//             change_pass();
//         }
//         });
// })

// function show_menu() {
//     document.querySelector('.modal-pass').style.visibility = 'visible';
//     document.querySelector('.modal-pass').style.opacity = '1';
// }

// function hide_menu() {
//     document.querySelector('.modal-pass').style.visibility = 'hidden';
//     document.querySelector('.modal-pass').style.opacity = '0';
// }

// function change_pass() {
//     var error_p = document.querySelector('.modal-pass #error-p');
//     var oldPass = document.querySelector(".modal-pass #old_pass").value;
//     var newPass = document.querySelector(".modal-pass #new_pass").value;
//     var confirmPass = document.querySelector("#confirm_pass").value;

//     var xhr = new XMLHttpRequest();
//     xhr.open('POST', '../utils/update_password.php', true);
//     xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

//     xhr.onload = function() {
//         var response = JSON.parse(this.responseText);
//         if (response.success) {
//             hide_menu();
//             Swal.fire({
//                 title: response.message,
//                 confirmButtonText: "Okay",
//                 icon: "success"
//                 }).then((result) => {
//                 /* Read more about isConfirmed, isDenied below */
//                 if (result.isConfirmed) {
//                     window.location.href = "../admin/login.php";
//                 }
//                 });
//         } else {
//             // Display error message
//             error_p.textContent = response.message;
//             error_p.style.display = 'block';

//             document.querySelector('#old_pass').value = "";
//             document.querySelector('#new_pass').value = "";
//             document.querySelector('#confirm_pass').value = "";
//         }
//     };

//     xhr.send("old_pass=" + oldPass + "&new_pass=" + newPass + "&confirm_pass=" + confirmPass);
// }