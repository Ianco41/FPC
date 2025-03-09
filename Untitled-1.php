<?php
header("Content-Type: application/json");
$host = "localhost"; // Change to your database host
$user = "root"; // Your database username
$password = ""; // Your database password
$dbname = "database3"; // Your database name

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

$query = "SELECT DISTINCT CATEGORY FROM fpc"; // Change 'name' and 'suggestions_table' as needed
$result = $conn->query($query);

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row["CATEGORY"];
}

echo json_encode($suggestions);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Input with Suggestions & Toggle Dropdown</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .suggestions {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .suggestion-item {
            padding: 8px;
            cursor: pointer;
        }
        .suggestion-item:hover, .suggestion-item.active {
            background: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="position-relative">
            <div class="input-group">
                <input type="text" id="comboInput" class="form-control" placeholder="Type at least 2 letters...">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownButton"></button>
            </div>
            <div id="suggestionBox" class="suggestions"></div>
        </div>
    </div>

    <script>
    const input = document.getElementById('comboInput');
    const suggestionBox = document.getElementById('suggestionBox');
    const dropdownButton = document.getElementById('dropdownButton');
    const form = document.querySelector("form"); // Ensure there's a form element
    
    let suggestions = [];
    let activeIndex = -1;
    let isDropdownOpen = false;

    // Fetch suggestions from PHP
    async function fetchSuggestions() {
        try {
            const response = await fetch('fetch_suggestion.php');
            suggestions = await response.json();
        } catch (error) {
            console.error("Error fetching suggestions:", error);
        }
    }

    function showSuggestions(filteredList) {
        suggestionBox.innerHTML = "";
        if (filteredList.length > 0) {
            suggestionBox.style.display = "block";
            filteredList.forEach((item, index) => {
                let div = document.createElement("div");
                div.classList.add("suggestion-item");
                div.textContent = item;
                div.setAttribute("data-index", index);
                div.onclick = function() {
                    input.value = item;
                    suggestionBox.style.display = "none";
                    isDropdownOpen = false;
                };
                suggestionBox.appendChild(div);
            });
        } else {
            suggestionBox.style.display = "none";
        }
    }

    input.addEventListener('keyup', function(event) {
        let filter = this.value.toLowerCase();
        activeIndex = -1;

        if (filter.length >= 2) {
            let filtered = suggestions.filter(item => item.toLowerCase().includes(filter));
            showSuggestions(filtered);
        } else {
            suggestionBox.style.display = "none";
        }

        let items = document.querySelectorAll(".suggestion-item");
        if (event.key === "ArrowDown" && items.length > 0) {
            activeIndex = (activeIndex + 1) % items.length;
            updateActiveItem(items);
        } else if (event.key === "ArrowUp" && items.length > 0) {
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            updateActiveItem(items);
        } else if (event.key === "Enter") {
    if (activeIndex >= 0 && items.length > 0) {
        event.preventDefault(); // Prevent form submission if selecting suggestion
        input.value = items[activeIndex].textContent;
        suggestionBox.style.display = "none";
        isDropdownOpen = false;
    } 
    else {
        suggestionBox.style.display = "none"; // Allow normal form submission
    }
}
    });

    function updateActiveItem(items) {
    items.forEach(item => item.classList.remove("active"));
    if (activeIndex >= 0) {
        items[activeIndex].classList.add("active");
        input.value = items[activeIndex].textContent; // Autofill input
    }
}

    dropdownButton.addEventListener('click', function() {
        if (isDropdownOpen) {
            suggestionBox.style.display = "none";
            isDropdownOpen = false;
        } else {
            activeIndex = -1;
            showSuggestions(suggestions);
            isDropdownOpen = true;
        }
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestionBox.contains(e.target) && !dropdownButton.contains(e.target)) {
            suggestionBox.style.display = "none";
            isDropdownOpen = false;
        }
    });

    // Fetch suggestions on page load
    fetchSuggestions();
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>