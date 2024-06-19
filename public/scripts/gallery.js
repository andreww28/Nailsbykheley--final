




var gallery_container = document.querySelector('.main-content');
var theme_select_tag = document.querySelector('.input-container select');
var search_bar = document.querySelector('.search-query');
var data = null;


document.addEventListener("DOMContentLoaded", async function() { 
    data = await Request.fetch_gallery_data();
    DOM_manipulate.set_gallery(data.gallery_items);
    DOM_manipulate.set_theme_options();

    theme_select_tag.addEventListener('change', Events.select_theme_on_change);
    search_bar.addEventListener('input', Events.search_event);

    document.querySelector('#AptBtn').addEventListener('click', () => document.location.href = '../pages/appointment.php');
});


const DOM_manipulate = (function() {
    function set_gallery(datas) {
        gallery_container.innerHTML = "";
        console.log(datas.length, datas.length == 0)
        if(datas.length >= 0) {
            datas.forEach(item => {
                gallery_container.innerHTML += `
                        <a download="../../admin/assets/uploads/gallery/${item.image_path}" href="../../admin/assets/uploads/gallery/${item.image_path}" data-sub-html=".caption" class="card ${item.theme_id}">
                            <img src="../../admin/assets/uploads/gallery/${item.image_path}" />
                            <div class="caption">
                                <h6>${item.title}</h6>
                                <p></p>
                            </div>
                        </a>
                `
            });
        }
        
        if(datas.length == 0) {
            gallery_container.innerHTML += '<p class="no-items-gallery">No gallery item found.</p>';
        }
    
        lightGallery(gallery_container, {
            subHtmlSelectorRelative: true
        });
    }

    function set_theme_options() {
        data.themes.forEach(item => {
            var opt = document.createElement('option');
            opt.value = item.theme_id;
            opt.innerHTML = item.theme;
            theme_select_tag.appendChild(opt);
        });
    }

    return {set_gallery, set_theme_options}
})();

const Events = (function() {
    function select_theme_on_change(e) {
        search_bar.value = "";

        let text = e.target.options[e.target.selectedIndex].text;
        console.log(text);
        if(text === 'All') {
            DOM_manipulate.set_gallery(data.gallery_items)
        }else {
            let d = data.gallery_items.filter(item => item.theme_id === e.target.value);
            DOM_manipulate.set_gallery(d);
        }
    }

    function search_event(e) {
        let val = e.target.value;
        let d;
        console.log(data.gallery_items);
        if(val === "") {
            DOM_manipulate.set_gallery(data.gallery_items);
        }else {
            if(theme_select_tag.options[theme_select_tag.selectedIndex].text === 'All') {
                d = data.gallery_items.filter(item => item.title.toLowerCase().includes(val.toLowerCase()));
            }else {
                d = data.gallery_items.filter(item => item.title.toLowerCase().includes(val.toLowerCase()) && item.theme_id === theme_select_tag.value);
            }
            DOM_manipulate.set_gallery(d);
        }
    }

    return {
        select_theme_on_change,
        search_event
    }
})();

const Request = (function() {
    function fetch_gallery_data() {
        return new Promise((resolve, reject) => {
    
            var xhr = new XMLHttpRequest();
        
            // Define the request parameters
                var url = '../pages/gallery.php';
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
                xhr.send();
        });
    }
    return {
        fetch_gallery_data
    }
})();