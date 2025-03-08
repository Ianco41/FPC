<?php
// Include the existing connection
include 'conn.php';

// Get the search query safely
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Prevent SQL injection by allowing only alphanumeric characters and spaces
$query = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);

if ($query !== '') {
    $suggestions = [];

    if ($db_type === "access") {
        // ODBC (MS Access) Query with Prepared Statement
        $sql = "SELECT DISTINCT CATEGORY FROM FPC WHERE CATEGORY LIKE ?";
        $stmt = odbc_prepare($conn, $sql);
        $searchQuery = "%$query%";

        if ($stmt) {
            odbc_execute($stmt, [$searchQuery]);

            while ($row = odbc_fetch_array($stmt)) {
                $category = trim($row['CATEGORY']);
                if (!empty($category)) {
                    $suggestions[] = htmlspecialchars($category);
                }
            }
        }
    } elseif ($db_type === "mysql") {
        // MySQL Query with Prepared Statement
        $sql = "SELECT DISTINCT CATEGORY FROM fpc WHERE CATEGORY LIKE ?";
        $stmt = mysqli_prepare($conn, $sql);
        $searchQuery = "%$query%";

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $searchQuery);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $category = trim($row['CATEGORY']);
                if (!empty($category)) {
                    $suggestions[] = htmlspecialchars($category);
                }
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Send response as newline-separated values
    echo implode("\n", $suggestions);
}
?>

