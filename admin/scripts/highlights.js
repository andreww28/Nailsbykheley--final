const newBtn = document.getElementById('new-btn');
const modal = document.querySelector('.modall');
const closeBtn = modal.querySelector('#close-modal-btn');
const errorP = document.getElementById('error-p');
const highlightForm = document.getElementById('highlight-form');
const imageInput = document.getElementById('image-btn');
const imagePreview = document.getElementById('image-preview');
const imageIdInput = document.getElementById('highlight-id');
const modal_title  = document.getElementById('modal-title');


document.addEventListener("DOMContentLoaded", function() {
    // Show modal for adding new theme
    newBtn.addEventListener('click', () => {
        Form.reset();
        popUp.showModal();
    });

    // Close modal
    closeBtn.addEventListener('click', () => {
        popUp.closeModal();
    });


    imageInput.addEventListener('change', Form.imageOnChange);

    Init.setEventOnEditBtn();
    Init.setEventOnRemoveBtn();

    highlightForm.addEventListener('submit', Request.submitData);

});


const Init = (function() {
    function setEventOnEditBtn() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const highlightValue = this.parentNode.previousElementSibling.firstElementChild.getAttribute('src'); //get the image tag
                const highlightId = this.getAttribute('data-highlight-id');
                Form.fillModalAndShow(highlightValue, highlightId);
                popUp.showModal();
            });
        });
    }

    function setEventOnRemoveBtn() {
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.addEventListener('click', function() {
                popUp.show_remove_confirm_dialog(this);
            });
        });
    }

    function updateTable(highlights) {
        const tbody = document.querySelector('table tbody');
        tbody.innerHTML = ''; 

        highlights.forEach(function(highlight, index) {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${index + 1}</td>
                <td>
                    <img src="../assets/uploads/highlight/${highlight.image_path}" class="highlight_img" />
                </td>
                <td class="btn-div">
                    <button class="edit-btn" data-highlight-id="${highlight.id}"
                        id="${highlight.id}"><i class='bx bxs-edit-alt'></i></button>
                    <button class="remove-btn" data-highlight-id="${highlight.id}"
                        id="${highlight.id}"><i class='bx bxs-trash-alt'></i></button>
                </td>
            `;

            tbody.appendChild(row);
        });

        Init.setEventOnEditBtn();
        Init.setEventOnRemoveBtn();
    }

    return {
        setEventOnEditBtn,
        setEventOnRemoveBtn,
        updateTable
    }
})();

const Form = (function() {
    const errorP = document.querySelector("form h6");
    const update_btn = document.querySelector('#update-data-btn');
    const form_add_btn = document.querySelector('#add-data-btn');

    function reset() {
        highlightForm.reset();
        modal_title.textContent = 'Add New Item';
        imageInput.nextElementSibling.textContent = "Select a file";
        imagePreview.src = '../assets/img/placeholder.svg';
        errorP.textContent = '';
        modal_title.textContent = 'Add New Item';
        imageInput.required = true;
        imageIdInput.value = null;
        update_btn.style.display = 'none';
        form_add_btn.style.display = 'block';
    }

    function fillModalAndShow(highlightValue, highlightId) {
        imageInput.nextElementSibling.textContent = "1 file selected";
        imagePreview.src = highlightValue;
        imageIdInput.value = highlightId;
        update_btn.style.display = 'block';
        form_add_btn.style.display = 'none';
        modal_title.textContent = 'Update an Item';
        imageInput.required = false;
    }

    function imageOnChange(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
        const filename = file ? "1 file selected" : 'Choose a file';
        this.nextElementSibling.textContent = filename;
    }

    function showError(msg) {
        Form.errorP.style.display = 'block';
        Form.errorP.textContent = msg;
    }

    return {
        reset,
        imageOnChange,
        fillModalAndShow,
        showError
    }
})();

const popUp = (function() {
    function showMessage(title) {
        Swal.fire({
            position: "top right",
            icon: "success",
            title: title,
            showConfirmButton: false,
            timer: 1500
        });
    }
    // Close modal function
    function closeModal() {
        modal.style.visibility = 'hidden';
        modal.style.opacity = '0';
    }

    // Show modal function
    function showModal() {
        closeModal();
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
    }

    function show_remove_confirm_dialog(button) {
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
                Request.removeData(button);
            }
          });
    }

    return {
        closeModal,
        showModal,
        show_remove_confirm_dialog,
        showMessage
    }
})();

const Request = (function() {
    function submitData(event) {
        event.preventDefault();
        const formData = new FormData(highlightForm);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/update_highlights.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        popUp.showMessage(response.message);
                        popUp.closeModal();
                        Init.updateTable(response.highlights);
                    } else {
                        Form.showError(response.message);
                    }
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };
        
        xhr.send(formData);
    }

    function removeData(button) {
        const row = button.parentNode.parentNode;
        const highlightId = button.getAttribute('data-highlight-id');
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/update_highlights.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        row.parentNode.removeChild(row);
                        popUp.showMessage(response.message);
                    } else {
                        popUp.showMessage(response.message);
                    }
                } else {
                    alert('Error occurred while processing your request.'); // Error message
                }
            }
        };
        xhr.send('highlight-id=' + encodeURIComponent(highlightId) + '&action=remove');
    }

    return {
        submitData,
        removeData
    }
})();


