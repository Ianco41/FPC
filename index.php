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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
        <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="#">CodzSword</a>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Admin Elements
                    </li>
                    <li class="sidebar-item">
                        <a href="#main" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#addnew" class="sidebar-link">
                            <i class="fa-solid fa-add pe-2"></i>
                            Add New
                        </a>
                        <!--<ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Page 1</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Page 2</a>
                            </li>
                        </ul>-->
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-sliders pe-2"></i>
                            Posts
                        </a>
                        <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Post 1</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Post 2</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Post 3</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#auth" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-regular fa-user pe-2"></i>
                            Auth
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Login</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Register</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Forgot Password</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-header">
                        Multi Level Menu
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#multi" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-share-nodes pe-2"></i>
                            Multi Dropdown
                        </a>
                        <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed" data-bs-target="#level-1"
                                    data-bs-toggle="collapse" aria-expanded="false">Level 1</a>
                                <ul id="level-1" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Level 1.1</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Level 1.2</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="image/profile.jpg" class="avatar img-fluid rounded" alt="">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item">Profile</a>
                                <a href="#" class="dropdown-item">Setting</a>
                                <a href="#" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content px-3 py-2" id="main">
                <div class="container-fluid">
                    <div class="mb-3">
                        <h4>Admin Dashboard</h4>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 d-flex">
                            <div class="card flex-fill border-0 illustration">
                                <div class="card-body p-0 d-flex flex-fill">
                                    <div class="row g-0 w-100">
                                        <div class="col-6">
                                            <div class="p-3 m-1">
                                                <h4>Welcome Back, Admin</h4>
                                                <p class="mb-0">Admin Dashboard, CodzSword</p>
                                            </div>
                                        </div>
                                        <div class="col-6 align-self-end text-end">
                                            <img src="image/customer-support.jpg" class="img-fluid illustration-img"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 d-flex">
                            <div class="card flex-fill border-0">
                                <div class="card-body py-4">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <h4 class="mb-2">
                                                $ 78.00
                                            </h4>
                                            <p class="mb-2">
                                                Total Earnings
                                            </p>
                                            <div class="mb-0">
                                                <span class="badge text-success me-2">
                                                    +9.0%
                                                </span>
                                                <span class="text-muted">
                                                    Since Last Month
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Table Element -->
                    <div class="card border-0">
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
            </main>
            <section class="addnew" id="addnew">

            </section>

            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a href="#" class="text-muted">
                                    <strong>CodzSwod</strong>
                                </a>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="#" class="text-muted">Contact</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="text-muted">About Us</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="text-muted">Terms</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="text-muted">Booking</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

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
        $(document).ready(function() {
            var table = new DataTable('#myTable', {
                info: true,
                ordering: true,
                order: [
                    [0, 'desc']
                ],
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
            $thead.find('th').each(function(index) {
                if (index !== 0) { // Skip ID column
                    var colName = $(this).text().trim();
                    var isHidden = hiddenColumns.includes(colName);

                    columnsToToggle.push({
                        index: index,
                        name: colName,
                        hidden: isHidden
                    });

                    // Hide the column initially if it's in the hiddenColumns list
                    if (isHidden) {
                        table.column(index).visible(false);
                    }
                }
            });

            // Generate dropdown items dynamically
            columnsToToggle.forEach(function(col) {
                $('#toggleButtons').append(
                    `<li>
                        <a class="dropdown-item">
                            <input type="checkbox" class="toggle-column" data-column="${col.index}" ${col.hidden ? '' : 'checked'}> ${col.name}
                        </a>
                    </li>`
                );
            });

            // Toggle column visibility on checkbox change
            $(document).on('change', '.toggle-column', function() {
                var columnIdx = $(this).data('column');
                var column = table.column(columnIdx);
                column.visible(!column.visible());
            });
        })
    </script>

    <script>
        let Ids;

        // Event delegation to handle row click events
        document.querySelector('tbody').addEventListener('click', function(event) {
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
                    document.getElementById('modalFY').textContent = data.fiscalYear; // Assuming fiscal year is in the data
                    document.getElementById('modalMonth').textContent = data.month; // Assuming month is in the data
                    document.getElementById('modalDate').textContent = data.date; // Assuming date is in the data
                    document.getElementById('modalCategory').textContent = data.category;
                    document.getElementById('modalTrigger').textContent = data.trigger;
                    document.getElementById('modalNTNF').textContent = data.ntnf; // Assuming NT/NF is in the data
                    document.getElementById('modalIssue').textContent = data.issue; // Assuming Issue is in the data
                    document.getElementById('modalPartNumber').textContent = data.partNumber; // Assuming part number is in the data
                    document.getElementById('modalProductName').textContent = data.productName; // Assuming product name is in the data
                    document.getElementById('modalLotSublot').textContent = data.lotSublot; // Assuming lot/sublot is in the data
                    document.getElementById('modalIn').textContent = data.in; // Assuming In field is in the data
                    document.getElementById('modalOut').textContent = data.out; // Assuming Out field is in the data
                    document.getElementById('modalReject').textContent = data.reject; // Assuming Reject field is in the data
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