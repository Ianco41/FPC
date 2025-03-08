<?php
// Database connection
include "conn.php";

// Your SQL query with backticks around column names that have spaces
$query = "SELECT ID, FY, MONTH, DATE, CATEGORY, TRIGGER, `NT_NF`, `ISSUE`, `PART NO`, `PRODUCT`, `LOT/SUBLOT`, `IN`, `OUT`, `REJECT` FROM your_table";

// Execute query and fetch results
$result = $conn->query($query);

// Initialize an array to store the results
$data = array();

if ($result->num_rows > 0) {
    // Fetch each row and add it to the data array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return the data as JSON
echo json_encode($data);

// Close connection
$conn->close();
?>
