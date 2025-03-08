<?php
include 'conn.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$query = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);

if ($query !== '') {
    $suggestions = [];
    
    if ($db_type === "access") {
        // ODBC (MS Access) Query with Prepared Statement
        $sql = "SELECT ID, PARTNUMBER FROM PRODUCT_LIST WHERE PARTNUMBER LIKE ? ORDER BY PARTNUMBER";
        $stmt = odbc_prepare($conn, $sql);
        $searchQuery = "%$query%";
        
        if ($stmt) {
            odbc_execute($stmt, [$searchQuery]);
            while ($row = odbc_fetch_array($stmt)) {
                $id = htmlspecialchars($row['ID']);
                $partNumber = htmlspecialchars($row['PARTNUMBER']);
                $suggestions[] = "$id|$partNumber";
            }
        }
    } elseif ($db_type === "mysql") {
        // MySQL Query with Prepared Statement
        $sql = "SELECT ID, PARTNUMBER FROM PRODUCT_LIST WHERE PARTNUMBER LIKE ? ORDER BY PARTNUMBER";
        $stmt = mysqli_prepare($conn, $sql);
        $searchQuery = "%$query%";
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $searchQuery);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $id = htmlspecialchars($row['ID']);
                $partNumber = htmlspecialchars($row['PARTNUMBER']);
                $suggestions[] = "$id|$partNumber";
            }
            mysqli_stmt_close($stmt);
        }

    /*$sql = "SELECT ID, PARTNUMBER FROM PRODUCT_LIST WHERE PARTNUMBER LIKE '%$query%' ORDER BY PARTNUMBER";
    $result = odbc_exec($conn, $sql);

    $suggestions = [];
    while ($row = odbc_fetch_array($result)) {
        $id = htmlspecialchars($row['ID']);
        $partNumber = htmlspecialchars($row['PARTNUMBER']);
        $suggestions[] = "$id|$partNumber"; // Return ID and Part Number separated by "|"
    }*/
}

    echo implode("\n", $suggestions);
}
?>
