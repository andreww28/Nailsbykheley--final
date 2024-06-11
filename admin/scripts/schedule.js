const time_form = document.querySelector('.time-action-div');
const date_form = document.querySelector('.date-select');

document.addEventListener("DOMContentLoaded", function() {
    Form_Time.setEvents();
    time_form.addEventListener('submit', function(e) {
        Request.submitTimeData(e, time_form);
    });

    date_form.addEventListener('submit', function(e) {
        Request.submitTimeData(e, date_form);
    });


    Init.displayData();
    Form_date.init();
})



const Init = (function() {
    function formatTime(timeString) {
        const [hours, minutes] = timeString.split(':');
        const date = new Date();
        date.setHours(hours);
        date.setMinutes(minutes);
        return date.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
    }

    function setEventOnRemoveBtn(_type) {
        
        document.querySelectorAll(`${_type === 'time' ? "#time-table .remove-btn" : "#date-table .remove-btn"}`).forEach(button => {
            button.addEventListener('click', function(e) {
                Popup.show_remove_confirm_dialog(function() {
                    Request.handleRemoveButtonClick(button, _type);
                });
            });
        });
    }

    function updateTimeTable(_type, _data) {
        const tbody = document.querySelector(`${_type === 'time' ? "#time-table tbody" : "#date-table tbody"}`);
        tbody.innerHTML = ''; 
        
        _data.forEach(function(d, index) {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>
                    ${_type === 'time' ? formatTime(d.from_time) : d.from_date}
                </td>
                <td>
                    ${_type === 'time' ? formatTime(d.to_time) : d.to_date}
                </td>
                <td>
                    <button class="remove-btn" ${_type === 'time' ? "data-time-id" : 'data-date-id'} ="${d.id}" id="${d.id}"><i class='bx bxs-trash-alt'></i></button>
                </td>
            `;

            tbody.appendChild(row);
        });

        if(_data.length === 0) {
            var row = document.createElement('tr')
            row.innerHTML = `
                <td colspan='3'>No ${_type === 'time' ? 'available time' : 'off days'} found. </td>
            `

            tbody.appendChild(row);

        }

        Init.setEventOnRemoveBtn(_type);
    }

    function displayData() {
        Request.fetchDataFromPHP((new Date().getMonth() + 1).toString())
        .then(data => {
          updateTimeTable('time', data.available_time);
          updateTimeTable('date', data.off_days);
        });
    }

    return {
        updateTimeTable,
        displayData,
        setEventOnRemoveBtn
    }
})();


const Form_Time = (function() {
    const from_time = document.getElementById('from-time');
    const to_time = document.getElementById('to-time');

    function setEvents() {
        from_time.addEventListener('change', setToTime);
    }

    function reset() {
        time_form.reset();
    }

    function setToTime(event) {
        const fromTimeValue = from_time.value;
        
        // Parse the time value and add an hour
        const [hours, minutes] = fromTimeValue.split(':').map(Number);
        const totalMinutes = hours * 60 + minutes + 60;
        const toHours = Math.floor(totalMinutes / 60) % 24;
        const toMinutes = totalMinutes % 60;
        
        // Format the time to HH:MM format
        const toTimeValue = `${String(toHours).padStart(2, '0')}:${String(toMinutes).padStart(2, '0')}`;
        
        // Set the value of the "to-time" input field
        to_time.value = toTimeValue;
        to_time.min = fromTimeValue; // Set the minimum value
        
        // Ensure "to-time" is not less than "from-time"
        if (to_time.value < fromTimeValue) {
            to_time.value = fromTimeValue;
        }
    }

    return {
        reset,
        setEvents
    }
})();

const Form_date = (function() {
    const from_date = document.getElementById('from-date');
    const to_date = document.getElementById('to-date');
    const month_filter = document.getElementById('month-filter');

    function setDateDefault() {
        const today = new Date().toISOString().split('T')[0];
        from_date.value = today;
        to_date.value = today;
    }

    function init() {
        setDateDefault();
        setEvents();
        setCurrentMonth();
    }

    function setEvents() {
        from_date.addEventListener('change', setToDateMin);
        month_filter.addEventListener('change', retrieveDataBasedOnMonth);  
    }

    function retrieveDataBasedOnMonth(e) {
        Request.fetchDataFromPHP(e.target.value)
        .then(data => {
            Init.updateTimeTable('date', data.off_days);
          });
    }

    function setToDateMin(){
        to_date.min = from_date.value;
        to_date.value = from_date.value;
    }

    function setCurrentMonth() {
        var currentDate = new Date();
        var currentMonth = currentDate.getMonth() + 1;
        document.getElementById('month-filter').value = currentMonth.toString();
    }

    return {
        init,
    }
})();


const Request = (function() {
    function submitTimeData(event, form) {
        event.preventDefault();
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/update_schedule.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Popup.show_message(response.message, 'success');
                        Form_Time.reset();

                        if(response._type === 'time') {
                            Init.updateTimeTable('time', response.available_time);
                        }else if(response._type === 'date') {
                            Init.updateTimeTable('date', response.off_days);
                        }
                    } else {
                        Popup.show_message(response.message, 'error');
                    }
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };

        xhr.send(formData);
    }

    function handleRemoveButtonClick(button, _type) {
        const row = button.parentNode.parentNode;
        var id = null;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/update_schedule.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        row.parentNode.removeChild(row);
                        Popup.show_message(response.message, 'success');
                    } else {
                        Popup.show_message(response.message, "error");
                    }
                } else {
                    alert('Error occurred while processing your request.'); // Error message
                }
            }
        };

        if(_type === 'time') {
            id = button.getAttribute('data-time-id');
            xhr.send('time-id=' + encodeURIComponent(id) + '&action=remove');
        }else if(_type === 'date') {
            id = button.getAttribute('data-date-id');
            xhr.send('date-id=' + encodeURIComponent(id) + '&action=remove');
        }
    }

    function fetchDataFromPHP(month) {
        return fetch(`../../api/fetch_schedule.php?${month ? 'month='+ month : ''}`)
          .then(response => response.json())
          .catch(error => {
            console.error('Error fetching data:', error);
          });
      }

    return {
        submitTimeData,
        fetchDataFromPHP,
        handleRemoveButtonClick
    }
})();

const Popup = (function() {
    function show_message(msg, icon) {
        Swal.fire({
            position: "top right",
            icon: icon,
            title: msg,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function show_remove_confirm_dialog(callback) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
          }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
          });
    }

    return {
        show_message,
        show_remove_confirm_dialog
    }
})();