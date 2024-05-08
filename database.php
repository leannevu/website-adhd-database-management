<?php 
    $servername = "localhost"; 
    $username = "root"; 
    $password = "1234"; 
    $dbname = "adhd"; 
     
    // Connect the database with the server 
    $conn = new mysqli($servername, $username, $password, $dbname); 
     
    // If error occurs  
    if ($conn->connect_errno) {
        error_log("Failed to connect to MySQL: " . $conn->connect_error); // Log error to error log
        echo json_encode(['error' => 'Database connection failed']);
        exit();
    } 

    header('Content-Type: application/json'); // Set header to application/json

    $action = $_POST['action'];

    if ($action == 'fetch') {
        $sql = "SELECT * FROM student;"; 
        if (isset($_POST['itemText'])) {
            $selectedText = $_POST['itemText'];
        $sql = "SELECT * FROM student WHERE studentName = '".$selectedText."';";
        } 
        $result = $conn->query($sql);
        if (!$result) {
            error_log("SQL Error: " . $conn->error);
            echo json_encode(['error' => 'Database query failed']);
            exit;
        }
        $data = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        echo json_encode($data); // Send data as JSON

    } else if ($action == 'fetchStudentAssignments') {
        if (isset($_POST['itemText'])) {
            $selectedText = $_POST['itemText'];
            $filterOption = $_POST['filterOption'];
            if ($filterOption == 'All') {
                $sql = "SELECT * FROM student INNER JOIN assignment ON assignment.studentId = student.studentId
        WHERE studentName = '".$selectedText."';";
            } else { 
                $sql = "SELECT * FROM student INNER JOIN assignment ON assignment.studentId = student.studentId
        WHERE studentName = '".$selectedText."' AND status = '".$filterOption."';";
            }
        $result = $conn->query($sql);
        if (!$result) {
            error_log("SQL Error: " . $conn->error);
            echo json_encode(['error' => 'Database query failed']);
            exit;
        }
        $data = [];
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        echo json_encode($data); // Send data as JSON

    } else if ($action == 'fetchAssignment') {
        if (isset($_POST['itemText'])) {
            $selectedText = $_POST['itemText'];
        $sql = "SELECT * FROM class
         INNER JOIN assignment ON assignment.className = class.className
        WHERE assignmentName = '".$selectedText."'";
        $result = $conn->query($sql);
        if (!$result) {
            error_log("SQL Error: " . $conn->error);
            echo json_encode(['error' => 'Database query failed']);
            exit;
        }
        $data = [];
        }
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        echo json_encode($data); // Send data as JSON

    }

    $conn->close();
?>