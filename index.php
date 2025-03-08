<!--for php back-END -->
<?php
include "conn.php";

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y'); // Get year from filter or use current year

if ($db_type == "access") {
    // Query for MS Access (using ODBC)
    $query = "SELECT * FROM FPC WHERE YEAR(date) = $year ORDER BY ID DESC";
    $result = odbc_exec($conn, $query);

    if (!$result) {
        die("Query failed: " . odbc_errormsg());
    }

    // Fetch data from ODBC
    $data = [];
    while ($row = odbc_fetch_array($result)) {
        $data[] = $row;
    }
} else {
    // Query for MySQL
    $query = "SELECT * FROM FPC WHERE YEAR(date) = $year ORDER BY ID DESC";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Fetch data from MySQL
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SNR</title>

    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet"> --.
    <!-- <link href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

    <<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/DataTables/datatables.min.css" />
    
</head>
<body>
        <!-- Header Section -->
    <header class="bg-primary text-white text-center py-4">
        <h1>REWORK AND SORTING COST Y25</h1>
    </header>
    <div class="container mt-2 d-flex">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3>Flexible Printed Circuit Table</h3>
            </div>
            
            <div class="card-body">
            <div class="container">
                <div class="container mt-2">
                    <div class="d-flex justify-content-end p-2">
                        <a href="New_form.php">
                        <button type="button" class="btn btn-success">ADD New FPC</button>
                        </a>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">  
                <form method="GET" action="" class="row g-3" id="year-form">
                    <div class="col-auto">
                        <label for="year" class="col-form-label">Select Year:</label>
                    </div>
                    <div class="col-auto">
                        <select name="year" id="year" class="form-select">
                            <?php
                            // Display the last 10 years as options (from 2025 to 2015)
                            for ($i = 2025; $i >= 2015; $i--) {
                                echo "<option value='$i' " . ($i == $year ? 'selected' : '') . ">$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="toggleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Show/Hide Columns
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="toggleDropdown" id="toggleButtons">
                        <!-- Dynamic toggle buttons will be added here -->
                    </ul>
                </div>
            </div>
            <div class="table-responsive" style="overflow-x: auto; max-width: 100%;">
                <table id="myTable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <!-- Adjust column names based on the fields from your FPC table -->
                            <th>ID</th>
                            <th>FISCAL YR</th>
                            <th>MONTH</th>
                            <th>DATE</th>
                            <th>CATEGORY</th>
                            <th>TRIGGER</th>
                            <th>NT/NF</th>
                            <th>ISSUE</th>
                            <th>PART NO.</th>
                            <th>PART NAME</th>
                            <th>LOT/ SUBLOT</th>
                            <th>IN</th>
                            <th>OUT</th>
                            <th>REJECT</th> 
                            <!--Add more columns as necessary -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Array of columns you want to include in the data-* attributes
                            $data_columns = ['ID', 'FY', 'MONTH', 'DATE', 'CATEGORY', 'TRIGGER', 'NT_NF', 'ISSUE', 'PART_NO', 'PRODUCT', 'LOT_SUBLOT', 'IN_VALUE', 'OUT_VALUE', 'REJECT'];

                            // Loop through each row of data and create table rows
                            foreach ($data as $row) {
                                echo "<tr class='table-row' id='triggerElement' data-bs-toggle='modal' data-bs-target='#reservationModal' 
                                    data-id='" . htmlspecialchars($row['ID']) . "'>";

                                // Output table cells for each row
                                foreach ($data_columns as $column) {
                                    echo "<td>" . htmlspecialchars($row[$column]) . "</td>";
                                }

                                // Close the table row
                                echo "</tr>";
                            }
                            if ($db_type == "access") {
                                // Close the ODBC result
                                odbc_free_result($result);

                                // Close the connection
                                odbc_close($conn);
                            }
                        ?>  
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Row Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Modal content will be dynamically inserted here -->
                <p><strong>ID:</strong> <span id="modalID"></span></p>
                <p><strong>Fiscal Year:</strong> <span id="modalFY"></span></p>
                <p><strong>Month:</strong> <span id="modalMonth"></span></p>
                <p><strong>Date:</strong> <span id="modalDate"></span></p>
                <p><strong>Category:</strong> <span id="modalCategory"></span></p>
                <p><strong>Trigger:</strong> <span id="modalTrigger"></span></p>
                <p><strong>NT/NF:</strong> <span id="modalNTNF"></span></p> <!-- Added NT/NF field -->
                <p><strong>Issue:</strong> <span id="modalIssue"></span></p> <!-- Added Issue field -->
                <p><strong>Part Number:</strong> <span id="modalPartNumber"></span></p> <!-- Added Part Number field -->
                <p><strong>Product Name:</strong> <span id="modalProductName"></span></p> <!-- Added Product Name field -->
                <p><strong>Lot/Sublot:</strong> <span id="modalLotSublot"></span></p> <!-- Added Lot/Sublot field -->
                <p><strong>In:</strong> <span id="modalIn"></span></p> <!-- Added In field -->
                <p><strong>Out:</strong> <span id="modalOut"></span></p> <!-- Added Out field -->
                <p><strong>Reject:</strong> <span id="modalReject"></span></p> <!-- Added Reject field -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


    <!-- jQuery -->
    <!--<script src="https://code.jquery.com/jquery-3.7.1.js"></script>-->
    <!-- Bootstrap Bundle (includes Bootstrap JS and Popper.js) -->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>-->
    <!-- DataTables JS -->
    <!-- <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>-->
    <!-- DataTables Bootstrap 5 integration -->
    <!-- <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>-->   

    <script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/bootstrap/js/all.min.js"></script>
    <script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
    <script src="assets/DataTables/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = new DataTable('#myTable', {
                info: true,        
                ordering: true,    
                order: [[0, 'desc']],
                layout: {
                    topStart: 'info',
                    bottom: 'paging',
                    bottomStart: null,
                    bottomEnd: null
                }
            });

            var $thead = $('#myTable thead tr');
            var columnsToToggle = [];
            var hiddenColumns = ["FISCAL YR", "MONTH", "NT/NF", "LOT/ SUBLOT"]; // Columns to be hidden initially

            // Extract column names dynamically
            $thead.find('th').each(function (index) {
                if (index !== 0) { // Skip ID column
                    var colName = $(this).text().trim();
                    var isHidden = hiddenColumns.includes(colName);

                    columnsToToggle.push({ index: index, name: colName, hidden: isHidden });

                    // Hide the column initially if it's in the hiddenColumns list
                    if (isHidden) {
                        table.column(index).visible(false);
                    }
                }
            });

            // Generate dropdown items dynamically
            columnsToToggle.forEach(function (col) {
                $('#toggleButtons').append(
                    `<li>
                        <a class="dropdown-item">
                            <input type="checkbox" class="toggle-column" data-column="${col.index}" ${col.hidden ? '' : 'checked'}> ${col.name}
                        </a>
                    </li>`
                );
            });

            // Toggle column visibility on checkbox change
            $(document).on('change', '.toggle-column', function () {
                var columnIdx = $(this).data('column');
                var column = table.column(columnIdx);
                column.visible(!column.visible());
            });
        })
    </script>
    
    <script>
        let Ids;

        // Event delegation to handle row click events
        document.querySelector('tbody').addEventListener('click', function (event) {
        // Ensure the clicked element is a table row
        const row = event.target.closest('.table-row');
        if (row) {
            const Id = row.dataset.id; // Get the booking ID
            Ids = Id;

            modal(Ids);
        }
        });
        function modal(Id) {
        fetch(`GET_row.php?booking_id=${Id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Booking not found');
                    return;
                }

                // Populate the modal with the fetched data
                document.getElementById('modalBookingId').textContent = data.bookingId;
                document.getElementById('modalFY').textContent = data.fiscalYear;  // Assuming fiscal year is in the data
                document.getElementById('modalMonth').textContent = data.month;  // Assuming month is in the data
                document.getElementById('modalDate').textContent = data.date;  // Assuming date is in the data
                document.getElementById('modalCategory').textContent = data.category;
                document.getElementById('modalTrigger').textContent = data.trigger;
                document.getElementById('modalNTNF').textContent = data.ntnf;  // Assuming NT/NF is in the data
                document.getElementById('modalIssue').textContent = data.issue;  // Assuming Issue is in the data
                document.getElementById('modalPartNumber').textContent = data.partNumber;  // Assuming part number is in the data
                document.getElementById('modalProductName').textContent = data.productName;  // Assuming product name is in the data
                document.getElementById('modalLotSublot').textContent = data.lotSublot;  // Assuming lot/sublot is in the data
                document.getElementById('modalIn').textContent = data.in;  // Assuming In field is in the data
                document.getElementById('modalOut').textContent = data.out;  // Assuming Out field is in the data
                document.getElementById('modalReject').textContent = data.reject;  // Assuming Reject field is in the data
            });
        }

    </script>
    <script>
    // JavaScript to trigger automatic form submission when a new year is selected
    document.getElementById("year").addEventListener("change", function() {
        // Submit the form when the user selects a new year
        document.getElementById("year-form").submit();
    });
</script>

</body>
</html>