<?php
include 'conn.php';

$partId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($partId > 0) {
    $sql = "SELECT PARTNAME FROM PRODUCT_LIST WHERE ID = $partId";
    $result = odbc_exec($conn, $sql);

    if ($row = odbc_fetch_array($result)) {
        echo htmlspecialchars($row['PARTNAME']);
    }
}
?>
