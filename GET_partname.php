<?php
    include 'conn.php';
    
    $partId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($partId > 0) {
        if ($db_type === "access") {
            // ODBC (MS Access) Query with Prepared Statement
            $sql = "SELECT PARTNAME FROM PRODUCT_LIST WHERE ID = ?";
            $stmt = odbc_prepare($conn, $sql);
            
            if ($stmt) {
                odbc_execute($stmt, [$partId]);
                if ($row = odbc_fetch_array($stmt)) {
                    echo htmlspecialchars($row['PARTNAME']);
                }
            }
        } elseif ($db_type === "mysql") {
            // MySQL Query with Prepared Statement
            $sql = "SELECT PARTNAME FROM PRODUCT_LIST WHERE ID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $partId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($row = mysqli_fetch_assoc($result)) {
                    echo htmlspecialchars($row['PARTNAME']);
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    /*include 'conn.php';

    $partId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($partId > 0) {
        $sql = "SELECT PARTNAME FROM PRODUCT_LIST WHERE ID = $partId";
        $result = odbc_exec($conn, $sql);

        if ($row = odbc_fetch_array($result)) {
            echo htmlspecialchars($row['PARTNAME']);
        }
    }*/
?>
