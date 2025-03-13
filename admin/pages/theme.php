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

$query = "SELECT * FROM themes";
$result = mysqli_query($conn, $query);
$themes = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script> -->
    <title>Theme - ADMIN</title>
</head>

<body>
    <div class="main">
        <div class="main-content">
            <h4>List of theme</h4>

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
                            <th>Theme</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($themes)) {
                            foreach ($themes as $index => $theme) {
                                if ($theme['theme'] !== "All") {
                        ?>
                                    <tr>
                                        <td><?php echo $index; ?></td>
                                        <td><?php echo $theme['theme']; ?></td>
                                        <td class="btn-div">
                                            <button class="edit-btn" data-theme-id="<?php echo $theme['theme_id']; ?>" id="<?php echo $theme['theme_id'] ?>"><i class='bx bxs-edit-alt'></i></button>
                                            <button class="remove-btn" data-theme-id="<?php echo $theme['theme_id']; ?>" id="<?php echo $theme['theme_id'] ?>"><i class='bx bxs-trash-alt'></i></button>
                                        </td>
                                    </tr>
                        <?php
                                }
                            }
                        } else {
                            echo "<tr><td colspan='3'>No themes found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>



            <div class="modall">
                <div class="modal-wrapper">
                    <i class='bx bx-x' id="close-modal-btn"></i>

                    <div class="content">
                        <h5>Add New Item</h5>
                        <form id="theme-form" method="post">
                            <div>
                                <label for="theme">Theme:</label>
                                <input type="text" name="theme" id="theme" required>
                                <input type="hidden" name="theme-id" id="theme-id">
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

    <script src="../scripts/theme.js"></script>

    <style>
        body {
            color: var(--grey-color);
        }

        body.swal2-shown>[aria-hidden='true'] {
            transition: 0.1s filter;
            filter: blur(3px);
        }

        .table-wrapper {
            width: 100%;
            max-width: 40em;
            min-width: 10em;
            overflow-x: scroll;
        }

        #theme-table {
            border-collapse: collapse;
            width: 100%;


        }

        #theme-table thead tr {
            color: var(--bg-dark);
            font-size: var(--body);
            font-weight: 700;
            text-align: left;
        }

        #theme-table th,
        #theme-table td {
            padding: 0.75em 1.5em;
        }

        #theme-table tr {
            border-bottom: 2px solid #dddddd;
            text-wrap: wrap;
        }

        #theme-table tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        td button {
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-div {
            display: flex;
            flex-direction: row;
            gap: 3px;
        }

        @media (max-width: 420px) {

            #theme-table tbody td {
                padding: 1em;
            }
        }






        .active4 {
            background-color: #ff74ab;
        }

        .main-content {
            padding: 2em;
            display: flex;
            flex-direction: column;
            gap: 2em;
        }

        #new-btn {
            margin-bottom: 1em;
            padding: 0.5em 1em;
            background-color: var(--primary-color);
            color: var(--white-color);
            outline: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            cursor: url("../assets/img/cursor.cur"), auto !important;
        }

        #new-btn:hover {
            background-color: #E697B6;
        }

        #complete-btn {
            background: var(--primary-color);
        }

        #cancel-btn {
            background-color: red;
        }

        .image-gallery {
            width: 5em;
            aspect-ratio: 1/1;
            object-fit: cover;
        }

        button span,
        button i {
            color: var(--font-white);
        }

        #image-btn {
            color: var(--font-white);
            background: var(--primary-color);
        }

        @media screen and (max-width: 420px) {
            #theme-table td {
                padding: 0.25em 5em;
            }
        }


        /* modal */
        .modall {
            position: absolute;
            z-index: 999;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: #000000c9;
            visibility: hidden;
            opacity: 0;
            transition: 0.5s;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modall>.modal-wrapper {
            background-color: var(--dark-bg);
            padding: 1em;
            position: relative;
        }

        .modall>.modal-wrapper i {
            position: absolute;
            top: 10px;
            right: 10px;
            color: var(--font-white);
            font-size: 2rem;
            cursor: pointer;
        }



        .modall>.modal-wrapper .content form {
            margin-top: 1em;
            display: flex;
            flex-direction: column;
            gap: 1em;
        }

        .modall>.modal-wrapper .content form div {
            display: flex;
            flex-direction: column;
        }

        .modall>.modal-wrapper .content form div label {
            color: var(--font-white);
            font-size: var(--small);
        }

        .modall>.modal-wrapper .content form input {
            padding: 1em 2em;
            color: var(--font-dark);
        }

        .modall>.modal-wrapper .content form button {
            background-color: var(--primary-color);
            outline: none;
            padding: 0.5em 1em;
            color: var(--font-white);
            border: none;
        }


        .modal-active {
            visibility: visible;
            opacity: 1;
        }

        form h6 {
            font-size: var(--small);
            color: #da7096;
            font-family: var(--body-font);
            display: none;
            font-weight: 300;
        }

        td button {
            outline: none;
            padding: 0.5em 1em;
            color: var(--font-white);
            border: none;
        }

        .edit-btn {
            background-color: green;
        }

        .remove-btn {
            background-color: red;
        }
    </style>

</body>

</html>

<?php
mysqli_close($conn);
?>