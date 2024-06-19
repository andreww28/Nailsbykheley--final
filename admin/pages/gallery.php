<?php
session_start();
session_regenerate_id(true);

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit(); // Exit to prevent further execution
}

if (isset($_POST["logout"])) {
    session_destroy();
    header('location: login.php');
}

include("../sections/admin_nav.php");
include("../../utils/connection.php");

// Retrieve themes from the database and encode as JSON
$themes_json = getThemesAsJson($conn);

// Function to get themes from database and encode as JSON
function getThemesAsJson($conn)
{
    $themes = array();
    $result = mysqli_query($conn, "SELECT theme_id, theme FROM themes");
    while ($row = mysqli_fetch_assoc($result)) {
        $themes[$row['theme']] = $row['theme_id'];
    }
    return json_encode($themes);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="../styles/gallery.css" />
</head>

<body>
    <div class="main">
        <div class="main-content">
            <h4>Gallery</h4>
            <div class="custom-control">
                <div>
                    <div class="action-btn">
                        <button id="new-btn"><i class='bx bx-plus-medical'></i><span>New</span></button>
                        <button id="edit-btn"><i class='bx bxs-edit-alt'></i><span>Edit</span></button>
                        <button id="remove-btn"><i class='bx bxs-trash-alt'></i><span>Remove</span></button>
                    </div>
                    <div class='selection-control'>
                        <button id="selectAll-btn">
                            <span>Select All</span>
                        </button>
                        <button id="deselect-btn">
                            <span>Deselect</span>
                        </button>
                    </div>
                </div>
            </div>
            <table id="gallery-table" class="display stripe" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Theme</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Theme</th>
                    </tr>
                </tfoot>
            </table>
            <!-- Modal for adding/editing items -->
            <?php include("../sections/gallery_modal.php"); ?>
        </div>
    </div>
    <div id="themes-data" data-themes='<?php echo $themes_json; ?>'></div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="../scripts/gallery.js"></script>
</body>

</html>

<?php
mysqli_close($conn);
?>