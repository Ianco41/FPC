<?php
include 'conn.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$query = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);

if ($query !== '') {
    $sql = "SELECT ID, PARTNUMBER FROM PRODUCT_LIST WHERE PARTNUMBER LIKE '%$query%' ORDER BY PARTNUMBER";
    $result = odbc_exec($conn, $sql);

    $suggestions = [];
    while ($row = odbc_fetch_array($result)) {
        $id = htmlspecialchars($row['ID']);
        $partNumber = htmlspecialchars($row['PARTNUMBER']);
        $suggestions[] = "$id|$partNumber"; // Return ID and Part Number separated by "|"
    }

    echo implode("\n", $suggestions);
}
?>
