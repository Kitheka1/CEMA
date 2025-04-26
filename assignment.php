<!-- number 1 -->
<!-- short program  for malaria health program -->
<?php
// start of the function
	function malariaHealthProgram() {
	    echo "\nMalaria Health Information System\n";
	    echo "1. About Malaria\n";
	    echo "2. Prevention Tips\n";
	    echo "3. Symptom Checker\n";
	    echo "4. Exit\n";
	
	    $choice = readline("\nEnter your choice (1-4): ");
//	switch case for options
	    switch ($choice) {
	        case 1:
	            echo "\nAbout Malaria:\n";
	            echo "- Caused by Plasmodium parasites transmitted via mosquito bites\n";
	            echo "- Common in tropical regions\n";
	            echo "- Symptoms appear 10-15 days after infection\n";
	            break;
	            
        case 2:
	            echo "\nPrevention Tips:\n";
	            echo "- Use mosquito nets treated with insecticide\n";
	            echo "- Apply EPA-approved repellents\n";
	            echo "- Wear protective clothing\n";
           echo "- Take prophylactic drugs when traveling to endemic areas\n";
	            break;
            
        case 3:
	            echo "\nSymptom Checker (not a diagnosis):\n";
	            $symptoms = ["Fever", "Chills", "Headache", "Body aches", "Fatigue", "Nausea"];
            $count = 0;
            
	            foreach ($symptoms as $symptom) {
                $answer = strtolower(readline("Do you have $symptom? (y/n): "));
                if ($answer === 'y') {
                  $count++;
                }
	            }
					// condition check
	            if ($count >= 3) {
	            	// display on the screen
	                echo "Warning: You have several malaria symptoms. Please consult a doctor immediately.\n";
            } elseif ($count > 0) {
	                echo "You have some symptoms. Monitor your health and see a doctor if symptoms worsen.\n";
	            } else {
               echo "No common malaria symptoms detected.\n";
            }
            break;
	            
      case 4:
	            echo "Exiting program. Stay healthy!\n";
	            exit;
	            
	        default:
	            echo "Invalid choice. Please try again.\n";
	    }
	    
    // Return to menu
	    malariaHealthProgram();
	}
	
	// Start the program
	malariaHealthProgram();
	?>
<!-- end -->

<!-- number 2 -->
<!-- short program  registering new client into the system -->
<?php
// config.php
$db_host = 'localhost';
$db_user = 'username';
$db_pass = 'password';
$db_name = 'healthcare_system';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);


// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    // Create clients table if not exists
$sql = "CREATE TABLE IF NOT EXISTS clients (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(50),
    phone VARCHAR(20),
    dob DATE,
    address VARCHAR(200),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
    
    $stmt = $conn->prepare("INSERT INTO clients (full_name, email, phone, dob, address, registration_date) 
                           VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $full_name, $email, $phone, $dob, $address);
    
    if ($stmt->execute()) {
        $message = "Registration successful! Client ID: " . $conn->insert_id;
    } else {
        $message = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Registration</title>
</head>
<body>
    <h1>Client Registration</h1>
    
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    
    <form method="post" action="">
        <label>Full Name:</label>
        <input type="text" name="full_name" required><br>
        
        <label>Email:</label>
        <input type="email" name="email"><br>
        
        <label>Phone:</label>
        <input type="tel" name="phone" required><br>
        
        <label>Date of Birth:</label>
        <input type="date" name="dob" required><br>
        
        <label>Address:</label>
        <textarea name="address" required></textarea><br>
        
        <button type="submit" name="Submit">Register</button>
    </form>
</body>
</html>
<!-- end -->

<!-- number 3 -->
<!-- short program the enrolls a client into a program -->
<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'username';
$db_pass = 'password';
$db_name = 'healthcare_system';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to enroll client in program
function enrollClient($conn) {
    echo "\nHealth Program Enrollment System\n";
    
    // Get client ID
    $client_id = readline("Enter client ID: ");
    
    // Verify client exists
    $stmt = $conn->prepare("SELECT id FROM clients WHERE id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "Client not found!\n";
        return;
    }
    
    // Display available programs
    echo "\nAvailable Programs:\n";
    echo "1. Malaria Treatment\n";
    echo "2. Diabetes Management\n";
    echo "3. Hypertension Control\n";
    
    $program_id = readline("Select program to enroll (1-3): ");
    
    // Map selection to program name
    $programs = [
        1 => 'Malaria Treatment',
        2 => 'Diabetes Management',
        3 => 'Hypertension Control'
    ];
    
    if (!isset($programs[$program_id])) {
        echo "Invalid program selection!\n";
        return;
    }
    
    $program_name = $programs[$program_id];
    $enrollment_date = date('Y-m-d');
    
    // Enroll client
    $stmt = $conn->prepare("INSERT INTO program_enrollments 
                          (client_id, program_name, enrollment_date) 
                          VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $client_id, $program_name, $enrollment_date);
    
    if ($stmt->execute()) {
        echo "Successfully enrolled client $client_id in $program_name program\n";
    } else {
        echo "Enrollment failed: " . $stmt->error . "\n";
    }
    
    $stmt->close();
}

// Create tables if they don't exist
$conn->query("CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100)
)");

$conn->query("CREATE TABLE IF NOT EXISTS program_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    program_name VARCHAR(100) NOT NULL,
    enrollment_date DATE NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id)
)");

// Run enrollment function
enrollClient($conn);

$conn->close();
?>
<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'healthcare_system');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $program_name = $_POST['program_name'];
    $enrollment_date = date('Y-m-d');
    
    $stmt = $conn->prepare("INSERT INTO program_enrollments 
                          (client_id, program_name, enrollment_date) 
                          VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $client_id, $program_name, $enrollment_date);
    
    if ($stmt->execute()) {
        $message = "Enrollment successful!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Program Enrollment</title>
</head>
<body>
    <h1>Enroll Client in Health Program</h1>
    
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
    
    <form method="post">
        <label>Client ID:</label>
        <input type="number" name="client_id" required><br>
        
        <label>Program:</label>
        <select name="program_name" required>
            <option value="Malaria Treatment">Malaria Treatment</option>
            <option value="Diabetes Management">Diabetes Management</option>
            <option value="Hypertension Control">Hypertension Control</option>
        </select><br>
        
        <button type="submit">Enroll Client</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>

<!-- end -->



<!-- Number 4 -->
<!-- short program that searches for a client from a list of registered clients -->
<?php
// Database connection
$db = new mysqli('localhost', 'username', 'password', 'clinic_db');

// Search functionality
if (isset($_GET['search'])) {
    $search_term = "%{$_GET['search']}%";
    $stmt = $db->prepare("SELECT * FROM clients WHERE name LIKE ? OR id = ?");
    $stmt->bind_param('ss', $search_term, $_GET['search']);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Search</title>
    <!-- css -->
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Client Search</h1>
    
    <form method="get">
        <input type="text" name="search" placeholder="Search by name or ID" required>
        <button type="submit">Search</button>
    </form>

    <?php if (isset($results)): ?>
        <h2>Search Results</h2>
        <!-- display the output here -->
        <?php if ($results->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
                <?php while ($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No clients found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

<?php
$db->close();
?>



<!-- Number 5 -->
<!-- short program that views a client's profile, including the programs they are enrolled in. -->
<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'username';
$db_pass = 'password';
$db_name = 'healthcare_system';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to view client profile
function viewClientProfile($conn) {
    echo "\nClient Profile Viewer\n";
    
    // Get client ID
    $client_id = readline("Enter client ID: ");
    
    // Get client basic information
    $client_stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
    $client_stmt->bind_param("i", $client_id);
    $client_stmt->execute();
    $client_result = $client_stmt->get_result();
    
    if ($client_result->num_rows === 0) {
        echo "Client not found!\n";
        return;
    }
    
    $client = $client_result->fetch_assoc();
    
    // Display client information
    echo "\nClient Profile:\n";
    echo "ID: " . $client['id'] . "\n";
    echo "Name: " . $client['name'] . "\n";
    echo "Email: " . $client['email'] . "\n";
    echo "Phone: " . $client['phone'] . "\n";
    echo "Date of Birth: " . $client['dob'] . "\n";
    echo "Address: " . $client['address'] . "\n";
    
    // Get enrolled programs
    $programs_stmt = $conn->prepare("SELECT program_name, enrollment_date FROM program_enrollments WHERE client_id = ?");
    $programs_stmt->bind_param("i", $client_id);
    $programs_stmt->execute();
    $programs_result = $programs_stmt->get_result();
    
    if ($programs_result->num_rows > 0) {
        echo "\nEnrolled Programs:\n";
        echo str_pad("Program Name", 25) . "Enrollment Date\n";
        echo str_repeat("-", 45) . "\n";
        
        while ($program = $programs_result->fetch_assoc()) {
            echo str_pad($program['program_name'], 25) . $program['enrollment_date'] . "\n";
        }
    } else {
        echo "\nThis client is not enrolled in any programs.\n";
    }
    
    $client_stmt->close();
    $programs_stmt->close();
}

// Run the function
viewClientProfile($conn);

$conn->close();
?>
<!-- end -->



<!-- Number 6 -->
<!-- short program that exposes the client profile via an API, so that other systems can retrieve this information. -->
<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Database configuration
$db_host = 'localhost';
$db_user = 'username';
$db_pass = 'password';
$db_name = 'healthcare_system';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed"]));
}

// Handle API request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get client ID from URL parameter
    $client_id = isset($_GET['client_id']) ? intval($_GET['client_id']) : null;
    
    if (!$client_id) {
        http_response_code(400);
        echo json_encode(["error" => "Client ID is required"]);
        exit;
    }

    // Get client information
    $client_stmt = $conn->prepare("SELECT id, name, email, phone, dob, address FROM clients WHERE id = ?");
    $client_stmt->bind_param("i", $client_id);
    $client_stmt->execute();
    $client_result = $client_stmt->get_result();
    
    if ($client_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["error" => "Client not found"]);
        exit;
    }
    
    $client = $client_result->fetch_assoc();
    
    // Get enrolled programs
    $programs_stmt = $conn->prepare("SELECT program_name, enrollment_date FROM program_enrollments WHERE client_id = ?");
    $programs_stmt->bind_param("i", $client_id);
    $programs_stmt->execute();
    $programs_result = $programs_stmt->get_result();
    $programs = $programs_result->fetch_all(MYSQLI_ASSOC);
    end
    // Build response
    $response = [
        "client" => $client,
        "programs" => $programs,
        "timestamp" => date("c")
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}

$conn->close();
?>
<!-- end -->