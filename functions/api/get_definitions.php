<?php
// Get DataTables parameters
$draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : $step;  // Use $step as default
$search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';

// Column ordering
$order_column = isset($_GET['order'][0]['column']) ? intval($_GET['order'][0]['column']) : 0;
$order_dir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'asc';

// Map DataTables column index to database column name
$columns = ['wd.def_id', 'w.word', 'w.language', 'wd.definition', 'w.source'];
$order_by = $columns[$order_column];

try {
    // Query to get total records without filtering
    $total_query = "SELECT COUNT(*) as total FROM word_definitions";
    $total_stmt = $conn->prepare($total_query);
    $total_stmt->execute();
    $total_records = $total_stmt->get_result()->fetch_assoc()['total'];

    // Base query for filtered records
    $query = "SELECT wd.def_id as id, w.word, w.language, wd.definition, w.source 
              FROM word_definitions wd
              JOIN words w ON wd.word_id = w.id";

    // Add search condition if search value exists
    $search_condition = "";
    if (!empty($search)) {
        $search_condition = " WHERE w.word LIKE ? OR wd.definition LIKE ? OR w.source LIKE ?";
    }

    // Query to get filtered records count
    $filtered_count_query = "SELECT COUNT(*) as filtered_total FROM word_definitions wd 
                             JOIN words w ON wd.word_id = w.id" . $search_condition;

    if (!empty($search)) {
        $search_param = "%$search%";
        $filtered_count_stmt = $conn->prepare($filtered_count_query);
        $filtered_count_stmt->bind_param("sss", $search_param, $search_param, $search_param);
        $filtered_count_stmt->execute();
    } else {
        $filtered_count_stmt = $conn->prepare($filtered_count_query);
        $filtered_count_stmt->execute();
    }

    $filtered_records = $filtered_count_stmt->get_result()->fetch_assoc()['filtered_total'];

    // Final query with search, order, and pagination
    $final_query = $query . $search_condition . " ORDER BY w.word ASC, $order_by $order_dir LIMIT ? OFFSET ?";

    if (!empty($search)) {
        $stmt = $conn->prepare($final_query);
        $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $length, $start);
    } else {
        $stmt = $conn->prepare($final_query);
        $stmt->bind_param("ii", $length, $start);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare data for DataTables
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Format response for DataTables
    $response = [
        "draw" => $draw,
        "recordsTotal" => $total_records,
        "recordsFiltered" => $filtered_records,
        "data" => $data
    ];

    echo json_encode($response);

} catch (Exception $e) {
    // Return error in format DataTables can understand
    $response = [
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
        "error" => $e->getMessage()
    ];

    echo json_encode($response);
}
exit;