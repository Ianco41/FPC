<?php
// Include the existing connection
include 'conn.php';

// Get the search query safely
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Prevent SQL injection by allowing only alphanumeric characters and spaces
$query = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);

if ($query !== '') {
    // Fetch distinct ISSUE from the `FPC` table
    $sql = "SELECT DISTINCT ISSUE FROM FPC WHERE ISSUE LIKE '%$query%' ORDER BY ISSUE";
    $result = odbc_exec($conn, $sql);

    // Output unique suggestions as a newline-separated string
    $suggestions = [];
    while ($row = odbc_fetch_array($result)) {
        $issue = trim($row['ISSUE']); // Remove spaces
        if (!empty($issue)) {
            $suggestions[] = htmlspecialchars($issue); // Prevent XSS
        }
    }

    echo implode("\n", $suggestions); // Send response as newline-separated values
}
?>
