<?php
include('../utils/connection.php');

$output = array();
$sql = "SELECT g.*, IFNULL(t.theme, 'Not set') AS theme FROM gallery g LEFT JOIN themes t ON g.theme_id = t.theme_id";

$totalQuery = mysqli_query($conn, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
    0 => 'id',
    1 => 'image_path',
    2 => 'title',
    3 => '_type',
    4 => 'theme', // Use 'theme' instead of 'theme_id' for display
);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE g.title LIKE '%" . $search_value . "%'";
    $sql .= " OR t.theme LIKE '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];

    // Handle sorting for the theme column
    if ($columns[$column_name] == 'theme') {
        $sql .= " ORDER BY t.theme " . $order;
    } else {
        $sql .= " ORDER BY " . $columns[$column_name] . " " . $order;
    }
} else {
    $sql .= " ORDER BY g.id DESC"; // Default sorting if not provided
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT " . $start . ", " . $length;
}

$query = mysqli_query($conn, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = '<img src="' . "../assets/uploads/gallery/" . $row['image_path'] . '"class="image-gallery" />';
    $sub_array[] = $row['title'];
    $sub_array[] = $row['_type'];
    $sub_array[] = $row['theme'];

    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $total_all_rows,
    'recordsFiltered' => $total_all_rows,
    'data' => $data,
);
echo json_encode($output);
