<div class="modall">
    <div class="modal-wrapper-gallery">
        <i class='bx bx-x' id="close-modal-btn"></i>
        <div class="content">
            <h5 id="modal-title">Add New Item</h5>
            <form id="add-item-form" method="POST" enctype="multipart/form-data">
                <div>
                    <input type="hidden" name="gallery_id" value="" />
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div class="theme-div">
                    <label for="themes">Theme:</label>
                    <select name="themes" id="themes" required>
                        <option value="84" selected="selected">All</option>
                        <?php

                        $theme_query = mysqli_query($conn, "SELECT * FROM themes");
                        while ($theme_row = mysqli_fetch_assoc($theme_query)) {
                            if ($theme_row['theme'] !== "All") { ?>
                        <option value="<?php echo $theme_row['theme_id']; ?>">
                            <?php echo $theme_row['theme']; ?>
                        </option>
                        <?php }
                        }
                        ?>
                    </select>
                </div>
                <div class="image-type">
                    <label for="img_type">Type:</label>
                    <div>
                        <input type="radio" name="img_type" value="featured">
                        <label for="f-radio">Featured</label>
                        <input type="radio" name="img_type" value="normal">
                        <label for="n-radio">Normal</label>


                    </div>
                </div>
                <div class="image-input-div">
                    <label>Image:</label>
                    <input type="file" name="image" id="image-btn" accept="image/*" required>
                    <label for="image-btn">Choose a file</label>
                </div>
                <div>
                    <label>Preview:</label>
                    <img id="image-preview" src="../assets/img/placeholder.svg" alt="Image Preview">
                </div>
                <h6 id="error-msg"></h6>
                <div>
                    <button type="submit" name="add" id="add-data-btn">Add</button>
                    <button type="submit" id="update-data-btn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>