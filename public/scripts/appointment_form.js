const form = document.querySelector('.a-container form');

document.addEventListener("DOMContentLoaded", function() {
    const select_date_btn = document.querySelector('#select-date-btn');

    Form_SetUp.addBtnEvents();
    select_date_btn.addEventListener('click', (e) => {
        document.querySelector('.calendar-wrapper').classList.toggle('calendar-active');
    })

    Form_SetUp.reset_form();

    Submit_Popup.hide();

})


const Form_SetUp = (function() {
    const nextBtn = document.querySelector('.btn-next');
    const prevBtn = document.querySelector('.btn-prev');
    const steps = document.querySelectorAll('.step');
    const form_steps = document.querySelectorAll('.form-step');
    const close_btn = document.querySelector(".form-box > i");
    const wrapper = document.querySelector('.a-container');
    let active = 1;

    function hideForm() {
        wrapper.style.display = 'none';
    }

    function showForm() {
        console.log("hi")
        wrapper.style.display = 'block';
    }

    function nextBtnAddEvent() {
        nextBtn.addEventListener('click', () => {
            if(active===1) {
                if(Form_Validation.validatePersonalInformation()) {
                    active++;
                }
            }else if(active===2) {
                if(Form_Validation.validateMedicalNailCondition()) {
                    active++;
                }
            }
            
            if (active > steps.length) {
                active = steps.length;
            }
            updateProgress();
        })
        Form_Validation.initValidation();
    }

    function prevBtnAddEvent() {
        prevBtn.addEventListener('click', () => {
            active--;
            if (active < 1) {
                active = 1;
            }
        
            updateProgress();
        })
    }

    function closeBtnAddEvent() {
        close_btn.addEventListener('click', () => {   
            let formFields = document.querySelectorAll('input, textarea');
            let unsavedData = Array.from(formFields).some(field => {
                if (field.type === 'radio' || field.type === 'checkbox') {
                    return field.checked;
                } else {
                    return field.value.trim() !== '';
                }
            });

            console.log(unsavedData);
            hideForm();
            // If there's unsaved data, display confirmation message
            if (unsavedData) {
                Popup.show_confirm_dialog("Closing now will discard your appointment data. Proceed?", () => {
                    hideForm();
                    reset_form();
                }, showForm);
            }
            
        });
    }

    function addFormEvent() {
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            if(Form_Validation.validateAppointmentInformation()) {
                e.preventDefault(); 

                Popup.show_confirm_dialog("Are you sure you want to proceed with the provided appointment details?", () => {
                    Form_Request.submitData();
                }, ()=>{});
            }
        })
    }

    function radioBtnEvent() {
        const radio_btns = document.querySelectorAll('.form-two input[type="radio"]');
        radio_btns.forEach((radio) => {
            radio.addEventListener('click', () => {
                if(radio.parentElement.parentElement.parentElement.lastChild.className === 'error-message') {
                    radio.parentElement.parentElement.parentElement.lastChild.remove();
                }

                let is_specify_div = radio.parentElement.parentElement.nextElementSibling; 
                
                if(is_specify_div) {
                    if(is_specify_div.className === 'specify-div') {
                        if(radio.value === 'yes') {
                            is_specify_div.style.display = 'block';
                        }else if(radio.value === 'no') {
                            if(is_specify_div.style.display === 'block') {
                                is_specify_div.style.display = 'none';
                            }
                        }
                    }   
                }
                
            })
        })

        
        const time_inputs = document.getElementsByName('appointment-time');
        time_inputs.forEach(input => {
            input.addEventListener('click', (e) => {
                let parent_lastC = input.parentElement.parentElement.lastElementChild;
                
                if(parent_lastC.className === 'error-msgg') {
                    parent_lastC.style.display = 'none';
                }
            })
        })
    }
    
 

    function addBtnEvents() {
        nextBtnAddEvent();
        prevBtnAddEvent();
        closeBtnAddEvent();
        addFormEvent();
        radioBtnEvent();
    }
    
    function reset_form() {
        active = 1;
        updateProgress();

        Form_Validation.clearErrorMessages();
        
        form.reset();
        document.querySelector('#select-date-btn').textContent = 'Select a date';
        document.querySelector('#date-picker').value = '';

        prevBtn.disabled = true;
        nextBtn.disabled = false;

        document.querySelector('.time-radio-div').style.display = 'none';
        document.querySelector('#timeRadioButtons').innerHTML = '';
    }

    function updateProgress() {
        steps.forEach((step, i) => {
            if (i === (active - 1)) {
                step.classList.add('active');
                form_steps[i].classList.add('active');
            } else {
                step.classList.remove('active');
                form_steps[i].classList.remove('active');
            }
        });
    
        if (active === 1) {
            prevBtn.disabled = true;
        } else if (active === steps.length) {
            nextBtn.disabled = true;
        } else {
            prevBtn.disabled = false;
            nextBtn.disabled = false;
    
        }
    }

    return {
        hideForm,
        addBtnEvents,
        reset_form,
        showForm
    }
})();


const Form_Calendar = (function() {
    const appointmentDate = document.getElementById('appointmentDate');
    const timeSelection = document.querySelector('.time-radio-div');
    const timeRadioButtons = document.querySelector('#timeRadioButtons');

    function checkAvailability(date) {
        console.log(date);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/update_calendar_form.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                updateForm(response);
            }
        };

        xhr.send('date=' + date);
    }

    function req_get_off_days() {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../../api/update_calendar_form.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        resolve(response);
                    } catch (e) {
                        reject(e);
                    }
                } else {
                    reject(new Error('Request failed with status ' + xhr.status));
                }
            };

            xhr.send('action=get_off_days');
        })
    }

    function checkIfDayFullSlot(month) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../../api/update_calendar_form.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        resolve(response);
                    } catch (e) {
                        reject(e);
                    }
                } else {
                    reject(new Error('Request failed with status ' + xhr.status));
                }
            };
    
            xhr.onerror = function () {
                reject(new Error('Request failed'));
            };
    
            xhr.send('month=' + month);
        });
    }

    function updateForm(data) {
        console.log(data);
        // Clear previous options

        timeRadioButtons.innerHTML = '';

        // Populate the time radio buttons
        data.availableTimes.forEach((time, index) => {
            const timeRange = Utils.formatTimeRangeReadable(time);
            const div = document.createElement('div');
            div.className = data.occupiedTimes.includes(time) ? 'time-invalid' : 'time-valid';

            const input = document.createElement('input');
            input.type = 'radio';
            input.name = 'appointment-time';
            input.value = time;
            input.id = `time${index}`;
            if (data.occupiedTimes.includes(time)) {
                input.disabled = true;
            }

            const label = document.createElement('label');
            label.htmlFor = `time${index}`;
            label.textContent = timeRange;

            div.appendChild(input);
            div.appendChild(label);
            timeRadioButtons.appendChild(div);
        });

        // Show the time selection
        timeSelection.style.display = 'block';
    }

    return {
        checkAvailability,
        checkIfDayFullSlot,
        req_get_off_days,
    }

})();


const Submit_Popup = (function() {
    var container = document.querySelector('.submit-popup');

    function show() {
        container.style.display = 'flex';
    }

    function hide() {
        if(container) {
            container.style.display = 'none';
        }
    }

    function set_important_text(ref, verif) {
        imp_text[0].textContent = ref;
        imp_text[1].textContent = verif;
    }

    return {
        show, hide, set_important_text
    }
})();


const Form_Request = (function() {
    const  allergicSpecify = document.getElementById('allergicSpecify');
    const sportSpecify = document.getElementById('sportSpecify');

    function submitData() {
        const formData = new FormData(form);
        console.log(formData);

        var valid_time = Utils.convertTimeRangeToSQLTime(formData.get("appointment-time"));
        var start_time = valid_time.startTime;
        var end_time = valid_time.endTime;

        formData.append('action', 'add_data');
        formData.append('start_time', start_time);
        formData.append('end_time', end_time);
        formData.append('allergicSpecify', allergicSpecify.value);
        formData.append('sportSpecify', sportSpecify.value);
        formData.delete("appointment-time");

        console.log(formData);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/appointment.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        let data = response.data;
                        Submit_Popup.set_important_text(data.refNo, data.verification_code)
                        Submit_Popup.show();
                        Form_SetUp.reset_form();
                       send_email(data.refNo, data.appt_date, data.start_time, data.end_time, data.service);
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

    function send_email(refNo, appt_date, start_time, end_time, service) {
        console.log('email')
        const requestBody = `action=send_from_appt_form&refNo=${refNo}&appt_date=${appt_date}&start_time=${start_time}&end_time=${end_time}&service=${service}`;
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
        submitData
    }
})();


const Utils = (function() {
    function convertTimeRangeToSQLTime(timeRange) {
        // Split the time range into start and end time
        var times = timeRange.split(" - ");
        var startTime = times[0];
        var endTime = times[1];
    
        // Parse time strings into Date objects
        var startDate = new Date("1970-01-01 " + startTime);
        var endDate = new Date("1970-01-01 " + endTime);
    
        // Convert Date objects into SQL TIME format (HH:MM:SS)
        var sqlStartTime = formatDate(startDate);
        var sqlEndTime = formatDate(endDate);
    
        return { startTime: sqlStartTime, endTime: sqlEndTime };
    }
    
    // Function to format Date object to SQL TIME format
    function formatDate(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
    
        // Add leading zeros if necessary
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
    
        return hours + ':' + minutes + ':' + seconds;
    }

    function formatTimeRangeReadable(timeRange) {
        const [startTime, endTime] = timeRange.split(' - ');
        return `${formatTime(startTime)} - ${formatTime(endTime)}`;
    }

    function formatTime(time) {
        const [hours, minutes] = time.split(':');
        let hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12 || 12; // Convert to 12-hour format
        return `${String(hour).padStart(2, '0')}:${minutes} ${ampm}`;
    }
    
    return {
        convertTimeRangeToSQLTime,
        formatTimeRangeReadable
    }    
})();


const Form_Validation = (function() {
    function validatePersonalInformation() {
        const fullName = document.getElementById('full-name').value.trim();
        const address = document.getElementById('address').value.trim();
        const mobileNumber = document.getElementById('mnumber').value.trim();
        const email = document.getElementById('email').value.trim();
        let isValid = true;

        clearErrorMessages();

        if (fullName === '') {
            showError('full-name', 'Please enter your full name.');
            isValid = false;
        }

        if (address === '') {
            showError('address', 'Please enter your address.');
            isValid = false;
        }

        if (mobileNumber === '') {
            showError('mnumber', 'Please enter your mobile number.');
            isValid = false;
        } else if (!isValidMobileNumber(mobileNumber)) {
            showError('mnumber', 'Please enter a valid mobile number.');
            isValid = false;
        }

        if (email === '') {
            showError('email', 'Please enter your email address.');
            isValid = false;
        } else if (email !== '' && !isValidEmail(email)) {
            showError('email', 'Please enter a valid email address.');
            isValid = false;
        }

        return isValid;
    }

    function showError(elementId, errorMessage) {
        const errorElement = document.createElement('p');
        errorElement.classList.add('error-message');
        errorElement.textContent = errorMessage;
        errorElement.style.color = 'red';
        errorElement.style.fontSize = 'var(--small)';
        errorElement.style.marginTop = '0.5em';
    
        var field = document.getElementById(elementId);
        var parent;

        if(field) {
            parent = field.parentElement;
        }
    
        if (!field) {
            // If element with specified ID not found, append error message to document body
            field = document.getElementsByName(elementId)[0]
            parent = field.parentElement.parentElement.parentElement;
        }

        parent.appendChild(errorElement);
    }
    

    function clearErrorMessages() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(error => error.remove());

        document.querySelectorAll('.form-three .error-msgg').forEach(e_msg => {
            e_msg.style.display = 'none';
        })
    }

    function initValidation() {
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('keyup', () => {
                if(input.nextElementSibling) {
                    if(input.nextElementSibling.className === 'error-message') {
                        input.nextElementSibling.remove();
                    }
                }
                validatePersonalInformation();
            });
        });
    }

    
    function validateMedicalNailCondition() {
        const isFirstTimeManicure = document.querySelector('input[name="isFirsttime"]:checked');
        const hasAllergicReaction = document.querySelector('input[name="allergicText"]:checked');
        const participateInSports = document.querySelector('input[name="sport"]:checked');

        clearErrorMessages();

        let isValid = true;

        if (isFirstTimeManicure === null) {
            showError('isFirsttime', 'Please select whether this is your first time for manicure/pedicure.');
            isValid = false;
        }

        if (hasAllergicReaction === null) {
            showError('allergicText', 'Please select whether you have ever experienced allergic reaction or irritation from any type of nail or skin product.');
            isValid = false;
        }

        if (hasAllergicReaction !== null && hasAllergicReaction.value === 'yes' && document.getElementById('allergicSpecify').value.trim() === '') {
            showError('allergicSpecify', 'Please specify if you have experienced allergic reactions.');
            isValid = false;
        }

        if (participateInSports === null) {
            showError('sport', 'Please select whether you take part in any hands-on hobbies or sports activities.');
            isValid = false;
        }

        if (participateInSports !== null && participateInSports.value === 'yes' && document.getElementById('sportSpecify').value.trim() === '') {
            showError('sportSpecify', 'Please specify your hands-on hobbies or sports activities.');
            isValid = false;
        }

        return isValid;
    }

    function validateAppointmentInformation() {
        // Add validation logic for appointment information step here

        const time_input = document.querySelector('input[name="appointment-time"]:checked');
        const date_input = document.getElementById('date-picker');
        let isValid = true;

        if(time_input === null) {
            document.querySelector('.time-radio-div .error-msgg').style.display = 'block';
            isValid = false;
        }
        
        if(date_input.value === '') {
            date_input.nextElementSibling.style.display = 'block';
            isValid = false;
        }

        return isValid; // Change this based on your validation logic
    }

    function isValidMobileNumber(number) {
        // Add your mobile number validation logic here
        // For example, you might check if the number matches a specific pattern
        if(number.match(/0?9[0-9]{9,10}/)){
            return true; // Change this based on your validation logic
        }else {
            return false;
        }
    }

    function isValidEmail(email) {
        if(email.match(/^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$/)) {
            return true; // Change this based on your validation logic
        } else {
            return false;
        }
    }

    return {
        validatePersonalInformation,
        validateMedicalNailCondition,
        validateAppointmentInformation,
        clearErrorMessages,
        initValidation
    };
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

    function show_confirm_dialog(msg, callback1, calleback2) {
        Swal.fire({
            title: msg,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Yes",
            denyButtonText: `No`
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              callback1();
            } else if (result.isDenied) {
              calleback2();
            }
          });
    }

    return {
        show_message,
        show_confirm_dialog
    }
})();
