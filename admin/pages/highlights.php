<?php
include('../../utils/connection.php');
session_start();
session_regenerate_id(true);
if (!isset($_SESSION['username'])) {
    header("location: login.php");
}

if (isset($_POST["logout"])) {
    session_destroy();
    header('location: login.php');
}

include("../sections/admin_nav.php");

$query = "SELECT * FROM highlights";
$result = mysqli_query($conn, $query);
$highlights = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme - ADMIN</title>
    <link rel="stylesheet" href="../styles/highlights.css" />
</head>

<body>
    <div class="main">
        <div class="main-content">
            <h4>Highlights</h4>

            <div class="action-div">
                <button id="new-btn">
                    <i class='bx bx-plus-medical'></i>
                    <span>New</span>
                </button>
            </div>

            <div class="table-wrapper">

                <table id="theme-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Picture</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($highlights)) {
                            foreach ($highlights as $index => $highlight) {

                        ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><img src="<?php echo '../assets/uploads/highlight/' . $highlight['image_path']; ?>"
                                    class="highlight_img" />
                            </td>
                            <td class="btn-div">
                                <button class="edit-btn" data-highlight-id="<?php echo $highlight['id']; ?>"
                                    id="<?php echo $highlight['id'] ?>"><i class='bx bxs-edit-alt'></i></button>
                                <button class="remove-btn" data-highlight-id="<?php echo $highlight['id']; ?>"
                                    id="<?php echo $highlight['id'] ?>"><i class='bx bxs-trash-alt'></i></button>
                            </td>
                        </tr>
                        <?php

                            }
                        } else {
                            echo "<tr><td colspan='3'>No highlights found found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="modall">
                <div class="modal-wrapper">
                    <i class='bx bx-x' id="close-modal-btn"></i>

                    <div class="content">
                        <h5 id="modal-title">Add New Item</h5>
                        <form id="highlight-form" method="post">
                            <div class="image-input-div">
                                <input type="hidden" name="highlight-id" id="highlight-id">
                                <label>Image:</label>
                                <input type="file" name="image" id="image-btn" accept="image/*" required>
                                <label for="image-btn">Choose a file</label>
                            </div>
                            <div>
                                <label>Preview:</label>
                                <img id="image-preview" src="../assets/img/placeholder.svg" alt="Image Preview">
                            </div>

                            <h6>hello</h6>
                            <button type="submit" name="add" id="add-data-btn">Add</button>
                            <button type="submit" name="update" id="update-data-btn">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../scripts/highlights.js"></script>

</body>

</html>

<?php
mysqli_close($conn);
?>