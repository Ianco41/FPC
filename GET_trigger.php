<?php
// Include the existing connection
include 'conn.php';

// Get the search query safely
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Prevent SQL injection by allowing only alphanumeric characters and spaces
$query = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);

if ($query !== '') {

    /*
    // Fetch distinct TRIGGER from the `FPC` table
    $sql = "SELECT DISTINCT TRIGGER FROM FPC WHERE TRIGGER LIKE '%$query%' ORDER BY TRIGGER";
    $result = odbc_exec($conn, $sql);

    // Output unique suggestions as a newline-separated string
    
    while ($row = odbc_fetch_array($result)) {
        $trigger = trim($row['TRIGGER']); // Remove spaces
        if (!empty($trigger)) {
            $suggestions[] = htmlspecialchars($trigger); // Prevent XSS
        }
    }
    */
    $suggestions = [];

    if ($db_type === "access") {
        // ODBC (MS Access) Query with Prepared Statement
        $sql = "SELECT DISTINCT TRIGGER FROM FPC WHERE TRIGGER LIKE ?";
        $stmt = odbc_prepare($conn, $sql);
        $searchQuery = "%$query%";

        if ($stmt) {
            odbc_execute($stmt, [$searchQuery]);

            while ($row = odbc_fetch_array($stmt)) {
                $trigger = trim($row['TRIGGER']);
                if (!empty($trigger)) {
                    $suggestions[] = htmlspecialchars($trigger);
                }
            }
        }
    } elseif ($db_type === "mysql") {
        // MySQL Query with Prepared Statement
        $sql = "SELECT DISTINCT `TRIGGER` FROM fpc WHERE `TRIGGER` LIKE ?";
        $stmt = mysqli_prepare($conn, $sql);
        $searchQuery = "%$query%";

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $searchQuery);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $trigger = trim($row['TRIGGER']);
                if (!empty($trigger)) {
                    $suggestions[] = htmlspecialchars($trigger);
                }
            }
            mysqli_stmt_close($stmt);
        }
    }


    echo implode("\n", $suggestions); // Send response as newline-separated values
}
?>
