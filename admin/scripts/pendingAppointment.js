// const table = Table_functions.init_table('#example', "../../api/fetch_appointment.php")
var table = null;
var current_status = ""
document.addEventListener("DOMContentLoaded", function() {
    Custom_Controls.addEvents();
    // Table_functions.addEvents(table);
});


const Init_APPT = (function() {
    function set_current_status(st) {
        current_status = st;
    }
    
    function get_current_status() {
        return current_status;
    }

    function mail_content(action) {
        if(action === 'cancel') {
            var subject =  'Appointmoent Cancellation - NAILSBYKHELEY';
            var re
        }
    }

    return {
        set_current_status,
        get_current_status
    }
})();

const Table_functions = (function() {
    function init_table(table_id, url) {
        return new DataTable(table_id, {
            scrollX: true,
            processing: true,
            serverSide: true,
            order: [],
            info: true,
            ajax: {
                url: url,
                type: "post",
                data: {'registered_from' : document.querySelector('input[name="registered_from"]').value, 'registered_to' : document.querySelector('input[name="registered_to"]').value, 'c_t' : Init_APPT.get_current_status() }
            },
            columnDefs: [{
                targets: [0, 5],
                orderable: true,
            }]
        });
    }

    function update_data_on_date_filter_change() {
        let min = document.querySelector('input[name="registered_from"]').value;
        let max = document.querySelector('input[name="registered_to"]').value;
        table.context[0].ajax.data = {'registered_from' : min, 'registered_to' : max, 'c_t' : Init_APPT.get_current_status() }
        // Update DataTable data
        table.draw();
    }

    function deselect_all_selected_row() {
        document.querySelectorAll('tbody tr.selected').forEach(el => el.classList.remove('selected'));
    }

    function select_all_rows() {
        document.querySelectorAll('tbody tr').forEach(row => row.classList.add('selected'));
    }

    function get_selected_reference_num() {
        var datas = table.rows('.selected').data()
        var ids = [];
        for(let i =0; i < datas.length; i++ ) {
            ids.push(datas[i][0]);
        }

        return ids;
    }


    function addEvents(table) {
        table.on('click', 'tbody tr', function (e) {
            e.currentTarget.classList.toggle('selected');
        });
    }

    function set_var_table(tb) {
        table = tb;
    }

    return {
        init_table,
        update_data_on_date_filter_change,
        addEvents,
        deselect_all_selected_row,
        select_all_rows,
        get_selected_reference_num,
        set_var_table,
    }
})();


const Custom_Controls = (function() {
    const view_btn = document.getElementById('view-btn');
    // const new_btn = document.getElementById('new-btn');
    // const confirm_btn = document.getElementById('confirm-btn');
    // const remove_btn = document.getElementById('remove-btn');

    const select_all_btn = document.getElementById('selectAll-btn');
    const deselect_btn = document.getElementById('deselect-btn');
    const reset_filter_btn = document.getElementById('reset-filter');

    const min_date_node = document.querySelector('input[name="registered_from"]');
    const max_date_node = document.querySelector('input[name="registered_to"]'); 


    function new_btn_event() {
        Form_SetUp.showForm();
        Table_functions.deselect_all_selected_row();
    }

    function remove_btn_event(action, msg) {
        console.log(action);
        var datas = table.rows('.selected').data()
        if(datas.length < 1) {
            Popup1.show_message('Please ensure at least one row is selected.', 'warning') ;
            return;
        }

        var ids = Table_functions.get_selected_reference_num();
        Popup1.show_confirm_dialog(msg, () => Request.remove_datas(ids, action));
    }

    async function view_btn_event() {
        var datas = table.rows('.selected').data();       
        Table_functions.deselect_all_selected_row();       
        if(datas.length != 1) {
            Popup1.show_message('Please ensure only one row is selected.', 'warning') ;
            return;
        }

        var id = datas[0][0];

        try {
            var {address, allergicReaction, appointment_date, conditionId, email, end_time, first_time, fullName, hasAllergic, isParticipatedSport, medicalCondition, mnumber, nailCondition, referenceNum, service, sportName, start_time, status, userId} = await Request.retrieve_all_information(id);
                
            var array_value = [fullName, address, `${email ? email : 'N/A'}`, mnumber, first_time, `${hasAllergic} ${allergicReaction ? ', ' + allergicReaction : ''}`, `${isParticipatedSport} ${sportName ? ', ' + sportName : ''}`, medicalCondition, nailCondition, service, appointment_date, `${Full_info_functions.convertTo12HourFormat(start_time)} - ${Full_info_functions.convertTo12HourFormat(end_time)}`, status, referenceNum];


            Full_info_functions.display_info(array_value);
            Full_info_functions.show_full_info_popup();
        } catch (error) {
            // Handle errors
            Popup1.show_message(error, "error");
        }
    }

    function confirm_btn_event(action, msg) {
        var datas = table.rows('.selected').data()
        console.log(datas);
        if(datas.length < 1) {
            Popup1.show_message('Please ensure at least one row is selected.', 'warning') ;
            return;
        }
        
        var ids = Table_functions.get_selected_reference_num();
        Popup1.show_confirm_dialog(msg, () => Request.update_status(ids,action));
    }

    function reset_filter_btn_event() {
        if(min_date_node.value != "" || max_date_node.value != ""){
            min_date_node.value = null || "";
            max_date_node.value = null || "";
            Table_functions.update_data_on_date_filter_change();
            Table_functions.deselect_all_selected_row();
        }

        table.search("").draw();
    }

    function min_date_node_event() {
        max_date_node.min = min_date_node.value;

        if(new Date(max_date_node.value).getTime() < new Date(min_date_node.value).getTime()) {
            max_date_node.value = min_date_node.value;
        } 
        Table_functions.update_data_on_date_filter_change();
    }

    function addEvents() {
        // new_btn.addEventListener('click', new_btn_event);
        // confirm_btn.addEventListener('click', confirm_btn_event);
        // remove_btn.addEventListener('click', remove_btn_event);
        view_btn.addEventListener('click', view_btn_event);
        select_all_btn.addEventListener('click', Table_functions.select_all_rows);
        deselect_btn.addEventListener('click', Table_functions.deselect_all_selected_row);
        reset_filter_btn.addEventListener('click', reset_filter_btn_event);
        min_date_node.addEventListener('change', min_date_node_event);
        max_date_node.addEventListener('change', Table_functions.update_data_on_date_filter_change);

    }

    return {
        addEvents,
        new_btn_event,
        confirm_btn_event,
        remove_btn_event
    }
})();

const Request = (function() {
    function update_status(ids, action) {

        const xhr = new XMLHttpRequest();

        const requestBody = 'ids=' + JSON.stringify(ids) + `&action=${action}`; // Serialize array to JSON string
        xhr.open('POST', '../../api/appointment.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        
                        table.rows('.selected').remove().draw(false);
                        Popup1.show_message(response.message, 'success');
                        console.log(response.emails);
                        table.draw();

                        console.log(response.data);

                        
                        if(action === 'delete_confirmed' || action === 'confirm_pending') {
                            send_email(action, response.data);
                        }
                    } else {
                        Popup1.show_message(response.message, "error");
                    }
                } else {
                    alert('Error occurred while processing your request.'); // Error message
                }
            }
        };

        xhr.send(requestBody);
        
    }

    function remove_datas(ids, action) {
        const xhr = new XMLHttpRequest();

        const requestBody = 'ids=' + JSON.stringify(ids) + `&action=${action}`; // Serialize array to JSON string
        xhr.open('POST', '../../api/appointment.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log(table.rows('.selected'));
                        table.rows('.selected').remove().draw(false);
                        Popup1.show_message(response.message, 'success');

                        if(action === 'delete_confirmed' || action === 'confirm_pending') {
                            send_email(action, response.data);
                        }
                    } else {
                        Popup1.show_message(response.message, "error");
                    }
                } else {
                    alert('Error occurred while processing your request.'); // Error message
                }
            }
        };

        xhr.send(requestBody);
    }

    function retrieve_all_information(id) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
    
            const requestBody = 'id=' + id + '&action=view_full_info'; // Serialize array to JSON string
            xhr.open('POST', '../../api/appointment.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            resolve(response.data);
                        } else {
                            reject(response.message || 'An error occurred');
                        }
                    } else {
                        reject('Error occurred while processing your request');
                    }
                }
            };
    
            xhr.send(requestBody);
        });
    }

    function send_email(action,data) {
        var data = JSON.stringify(data.filter(d => d.email !== ""));
        

        const requestBody = `action=send_from_admin&data=${data}&user_action=${action}`;
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
                            } else {
                                console.log(response.message);
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
        update_status,
        remove_datas,
        retrieve_all_information
    }
})();

const Popup1 = (function() {
    function show_message(msg, icon) {
        Swal.fire({
            position: "top right",
            icon: icon,
            title: msg,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function show_confirm_dialog(msg, callback) {
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
        show_message,
        show_confirm_dialog
    }
})();