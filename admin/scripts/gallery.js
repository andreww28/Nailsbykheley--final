const galleryForm = document.getElementById('add-item-form');
const newBtn = document.getElementById('new-btn');
const editBtn = document.getElementById('edit-btn');
const removeBtn = document.getElementById('remove-btn');
const selectAllBtn = document.getElementById('selectAll-btn');
const deselectlBtn = document.getElementById('deselect-btn');
const errorMsg = document.querySelector('#error-msg');
var table = null;

document.addEventListener('DOMContentLoaded', function () {
    table = Table.initDataTable();
    Table.addSelectEvent(table);
    Form.addEvents();

    newBtn.addEventListener('click', function () {
        Form.reset();
        PopUp.openModal()
    });
    
    removeBtn.addEventListener('click', Custom_Controls.remove_event);
    
    editBtn.addEventListener('click', Custom_Controls.edit_event);

    selectAllBtn.addEventListener('click', Table.select_all_rows);

    deselectlBtn.addEventListener('click', Table.deselect_all_selected_row);

    
    galleryForm.addEventListener('submit', (e) => Request.submitData(e, table));

});


const Request = (function() {
    function submitData(event, table) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../api/update_gallery.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(formData);
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        PopUp.showMessage(response.message, 'success');
                        PopUp.closeModal();
                        table.draw();
                        Table.deselect_all_selected_row();
                    } else {
                        errorMsg.style.display = 'block';
                        errorMsg.textContent = response.message;

                    }
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };
        xhr.send(formData);
    }

    function removeData(ids, table) {
        const xhr = new XMLHttpRequest();

        const requestBody = 'ids=' + JSON.stringify(ids) + `&action=remove`;
        xhr.open('POST', '../../api/update_gallery.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Table.deselect_all_selected_row();
                        table.row('.selected').remove().draw(false);
                        PopUp.showMessage(response.message, 'success');
                        document.querySelector('.modall').style.visibility = 'hidden';
                        document.querySelector('.modall').style.opacity = '0';

                    } else {
                        PopUp.showMessage(response.message, 'error');
                    }
                } else {
                    PopUp.showMessage('Error occurred while processing your request.', 'error');
                }
            }
        };
        xhr.send(requestBody);
    } 

    return {
        submitData,
        removeData
    }
})();


const Form = (function() {
    const modal_title =  document.getElementById('modal-title');
    const addDataBtn = document.getElementById('add-data-btn');
    const updateDataBtn = document.getElementById('update-data-btn');
    const imageInput = document.getElementById('image-btn');
    const imagePreview = document.getElementById('image-preview');
    const errorMsg = document.querySelector('#error-msg');
    const title = document.getElementById('title');
    const theme = document.getElementById('themes');
    const id_field = document.querySelector('input[type="hidden"]');
    const closeModalBtn = document.getElementById('close-modal-btn');

    function reset() {
        modal_title.textContent = 'Add New Item';
        galleryForm.reset();
        imageInput.nextElementSibling.textContent = "Select a file";
        imagePreview.src = '../assets/img/placeholder.svg';
        errorMsg.textContent = '';
        addDataBtn.style.display = 'block';
        updateDataBtn.style.display = 'none';
        imageInput.required = true;
        id_field.value = null;
    }

    function fillInfo(selectedRow) {
        const themesDataElement = document.getElementById('themes-data');
        const themesJson = themesDataElement.dataset.themes;
        const themes = JSON.parse(themesJson);

        modal_title.textContent = 'Update Item';
        imageInput.nextElementSibling.textContent = "1 file selected";
        id_field.value = selectedRow[0];
        title.value = selectedRow[2];
        document.querySelector(`input[value="${selectedRow[3]}"]`).checked = true;
        theme.value = themes[selectedRow[4]];
        imagePreview.src = $(selectedRow[1]).attr('src');
        addDataBtn.style.display = 'none';
        updateDataBtn.style.display = 'block';
        imageInput.required = false;
    }

    function addEvents() {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
            const filename = file ? "1 file selected" : 'Choose a file';
            this.nextElementSibling.textContent = filename;
        });

        closeModalBtn.addEventListener('click', function () {
            PopUp.closeModal();
        });
    }

    return {
        reset,
        fillInfo,
        addEvents
    }

})();

const Custom_Controls = (function() {
    function get_selected_ids() {
        var datas = Table.getSelectedRow(table);
        var ids = [];
        for(let i =0; i < datas.length; i++ ) {
            ids.push(datas[i][0]);
        }

        return ids;
    }

    function edit_event(e) {
        const selected_rows = Table.getSelectedRow(table);
          
        if(selected_rows.length != 1) {
            PopUp.showMessage('Please ensure only one row is selected.', 'warning') ;
            Table.deselect_all_selected_row();
            return;
        }

        const selectedRow = selected_rows[0];
        Form.fillInfo(selectedRow);
        PopUp.openModal();
    }

    function remove_event(e) {
        const selected_rows = Table.getSelectedRow(table);

        if(selected_rows.length < 1) {
            PopUp.showMessage('Please ensure at least one row is selected.', 'warning') ;
            return;
        }

        const ids = get_selected_ids();
        PopUp.showConfirm(() => Request.removeData(ids, table));
    }

    return {
        edit_event,
        remove_event
    }
})();


const Table = (function() {
    function initDataTable() {
        return new DataTable('#gallery-table', {
            scrollX: true,
            processing: true,
            serverSide: true,
            order: [],
            info: true,
            ajax: {
                url: "../../api/fetch_gallery.php",
                type: "post"
            },
            columnDefs: [{
                targets: [0, 4],
                orderable: true,
            }],
            columns: [{
                    visible: false,
                    searchable: false
                },
                {
                    orderable: false
                },
                {
                    orderable: false
                },
                {
                    orderable: true
                },
                {
                    orderable: true
                }
            ]
        });
    }


    function addSelectEvent(table) {
        table.on('click', 'tbody tr', function (e) {
            e.currentTarget.classList.toggle('selected');
        });
    }

    function getSelectedRow(table) {
        return table.rows('.selected').data()
    }

    function removeRow(table) {
        table.row('.selected').remove().draw(false);
    }

    function deselect_all_selected_row() {
        document.querySelectorAll('tbody tr.selected').forEach(el => el.classList.remove('selected'));
    }

    function select_all_rows() {
        document.querySelectorAll('tbody tr').forEach(row => row.classList.add('selected'));
    }


    return {
        initDataTable,
        addSelectEvent,
        getSelectedRow,
        removeRow,
        deselect_all_selected_row,
        select_all_rows
    }
})();

const PopUp = (function() {
    const modal = document.querySelector('.modall');
    const errorMsg = document.querySelector('.modall form h6');

    function closeModal() {
        modal.style.visibility = 'hidden';
        modal.style.opacity = '0';
    }

    function openModal() {
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        errorMsg.style.display = "none";
    }

    function showMessage(msg, icon) {
        Swal.fire({
            position: "center",
            icon: icon,
            title: msg,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function showConfirm(callback) {
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
        openModal,
        showMessage,
        showConfirm
    }
})();