const themeInput = document.getElementById('theme');

document.addEventListener("DOMContentLoaded", function() {
    const newBtn = document.getElementById('new-btn');    
    
    // Show modal for adding new theme
    newBtn.addEventListener('click', () => {
        themeInput.value = "";
        PopUp.showModal();
        document.querySelector("form h6").style.display = 'none';
        document.querySelector('#add-data-btn').style.display = 'block';
        document.querySelector('#update-data-btn').style.display = 'none';
    });
    
    Table.addBtnsEvent();
    Form.addFormEvents();
});

const Request = (function() {
    const errorP = document.getElementById('error-p');

    function handleRemoveButtonClick(button) {
        const row = button.parentNode.parentNode;
        const themeId = button.getAttribute('data-theme-id');
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/update_theme.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        row.parentNode.removeChild(row);
                        PopUp.showMessage(response.message, 'success');
                        Table.updateTable(response.themes)
                    } else {
                        PopUp.showMessage(response.message, 'error');

                    }
                } else {
                    alert('Error occurred while processing your request.'); // Error message
                }
            }
        };
        xhr.send('theme-id=' + encodeURIComponent(themeId) + '&action=remove');
    }

    function submitData(event) {
        event.preventDefault();
        const formData = new FormData(document.getElementById('theme-form'));
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/update_theme.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        PopUp.showMessage(response.message, 'success')
                        Table.updateTable(response.themes);
                        Form.reset_form();
                        PopUp.closeModal();
                    } else {
                        
                        document.querySelector("form h6").style.display = 'block';
                        document.querySelector("form h6").textContent = response.message;

                        errorP.style.display = 'block';
                        errorP.textContent = response.message;
                    }
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };
        if (!themeInput.value.trim()) {
            console.log(errorP.textContent);
            return; // Stop further execution
        }
        xhr.send(formData);
    }

    return {
        handleRemoveButtonClick,
        submitData
    }

})()

const Form = (function() {
    const themeForm = document.getElementById('theme-form');
    const closeBtn = document.querySelector('#close-modal-btn');


    function fillDataForm(theme, themeId) {
        const themeInput = document.getElementById('theme');
        const themeIdInput = document.getElementById('theme-id');
        const updateBtn = document.getElementById('update-data-btn');
        const addBtn = document.getElementById('add-data-btn');


        themeInput.value = theme;
        themeIdInput.value = themeId;
        updateBtn.style.display = 'block';
        addBtn.style.display = 'none';

        console.log(updateBtn.style.display);
        console.log(addBtn.style.display);

    }

    function addFormEvents () {
        themeForm.addEventListener('submit', Request.submitData);
        closeBtn.addEventListener('click', () => PopUp.closeModal());
    }

    function reset_form(){
        themeForm.reset();
        document.querySelector('#theme-id').value = null;
    }

    



    return {
        fillDataForm,
        addFormEvents,
        reset_form
    }
}()) 


const Table = (function() {
    function addEditButtonEvent() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const themeValue = button.parentNode.parentNode.querySelector(
                    'td:nth-child(2)').textContent;
                const themeId = button.getAttribute('data-theme-id');
                Form.fillDataForm(themeValue, themeId);
                PopUp.showModal();
            });
        });
    }

    function addRemoveBtnEvent() {
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.addEventListener('click', function() {
                PopUp.show_remove_confirm_dialog(() => Request.handleRemoveButtonClick(button));
            });
        });
    }

    function updateTable(themes) {

        const tbody = document.querySelector('table tbody');
        tbody.innerHTML = ''; // Clear existing table rows
        themes.forEach(function(theme, index) {
            const row = document.createElement('tr');
            if(theme.theme !== 'All') {
                row.innerHTML = '<td>' + (index) + '</td><td>' + theme.theme +
                    '</td><td><button class="edit-btn" data-theme-id="' + theme.theme_id +
                    '"><i class="bx bxs-edit-alt"></i></button><button class="remove-btn" data-theme-id="' + theme.theme_id +
                    '"><i class="bx bxs-trash-alt"></i></button></td>';
                tbody.appendChild(row);
            }
        });

        // Reapply event listeners for edit buttons
        Table.addBtnsEvent();
    }

    function addBtnsEvent() {
        addEditButtonEvent();
        addRemoveBtnEvent();
    }

    return {
        addBtnsEvent,
        updateTable
    }
})()


const PopUp = (function() {
    const modal = document.querySelector('.modall');

    function closeModal() {
        modal.style.visibility = 'hidden';
        modal.style.opacity = '0';
        Form.reset_form();
    }

    // Show modal function
    function showModal() {
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
    }

    function showMessage(msg, icon) {
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
        closeModal,
        showMessage,
        showModal,
        show_remove_confirm_dialog,
    }
})();