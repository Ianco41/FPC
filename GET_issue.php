<?php
// Include the existing connection
include 'conn.php';

// Get the search query safely
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Prevent SQL injection by allowing only alphanumeric characters and spaces
$query = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);

if ($query !== '') {

    if ($query !== '') {
        $suggestions = [];
    
        if ($db_type === "access") {
            // ODBC (MS Access) Query with Prepared Statement
            $sql = "SELECT DISTINCT ISSUE FROM FPC WHERE ISSUE LIKE ?";
            $stmt = odbc_prepare($conn, $sql);
            $searchQuery = "%$query%";
    
            if ($stmt) {
                odbc_execute($stmt, [$searchQuery]);
    
                while ($row = odbc_fetch_array($stmt)) {
                    $issue = trim($row['ISSUE']);
                    if (!empty($issue)) {
                        $suggestions[] = htmlspecialchars($issue);
                    }
                }
            }
        } elseif ($db_type === "mysql") {
            // MySQL Query with Prepared Statement
            $sql = "SELECT DISTINCT ISSUE FROM fpc WHERE ISSUE LIKE ?";
            $stmt = mysqli_prepare($conn, $sql);
            $searchQuery = "%$query%";
    
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $searchQuery);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
    
                while ($row = mysqli_fetch_assoc($result)) {
                    $issue = trim($row['ISSUE']);
                    if (!empty($issue)) {
                        $suggestions[] = htmlspecialchars($issue);
                    }
                }
                mysqli_stmt_close($stmt);
            }
        }
    /*// Fetch distinct ISSUE from the `FPC` table
    $sql = "SELECT DISTINCT ISSUE FROM FPC WHERE ISSUE LIKE '%$query%' ORDER BY ISSUE";
    $result = odbc_exec($conn, $sql);

    // Output unique suggestions as a newline-separated string
    $suggestions = [];
    while ($row = odbc_fetch_array($result)) {
        $issue = trim($row['ISSUE']); // Remove spaces
        if (!empty($issue)) {
            $suggestions[] = htmlspecialchars($issue); // Prevent XSS
        }
    }*/
    }

    echo implode("\n", $suggestions); // Send response as newline-separated values
}
?>
