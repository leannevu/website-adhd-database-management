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
        $sql = "SELECT * FROM student";
        if (isset($_POST['itemText'])) {
            $selectedText = $_POST['itemText'];
            $sql .= " WHERE studentName = ?";
        }
    
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters if they exist
        if (isset($selectedText)) {
            $stmt->bind_param("s", $selectedText);
        }
    
        // Execute the prepared statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
    
            echo json_encode($data); // Send data as JSON
        } else {
            error_log("SQL Error: " . $stmt->error);
            echo json_encode(['error' => 'Database query failed']);
        }
    
        // Close statement
        $stmt->close();
     } else if ($action == 'fetchStudentAssignments') {
        if (isset($_POST['itemText'])) {
            $selectedText = $_POST['itemText'];
            $filterOption = $_POST['filterOption'];
            if ($filterOption == 'All') {
                $sql = "SELECT * FROM student INNER JOIN assignment ON assignment.studentId = student.studentId WHERE studentName = ?";
            } else { 
                $sql = "SELECT * FROM student INNER JOIN assignment ON assignment.studentId = student.studentId WHERE studentName = ? AND status = ?";
            }
    
            // Prepare the SQL statement
            $stmt = $conn->prepare($sql);
            
            // Bind parameters
            if ($filterOption == 'All') {
                $stmt->bind_param("s", $selectedText);
            } else {
                $stmt->bind_param("ss", $selectedText, $filterOption);
            }
    
            // Execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
    
                echo json_encode($data); // Send data as JSON
            } else {
                error_log("SQL Error: " . $stmt->error);
                echo json_encode(['error' => 'Database query failed']);
            }
    
            // Close statement
            $stmt->close();
        }
    }
    
    else if ($action == 'fetchAssignment') {
        if (isset($_POST['itemText'])) {
            $selectedText = $_POST['itemText'];
            $sql = "SELECT * FROM class INNER JOIN assignment ON assignment.className = class.className WHERE assignmentName = ?";
    
            // Prepare the SQL statement
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $selectedText);
    
            // Execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
    
                echo json_encode($data); // Send data as JSON
            } else {
                error_log("SQL Error: " . $stmt->error);
                echo json_encode(['error' => 'Database query failed']);
            }
    
            // Close statement
            $stmt->close();
        }
    }
    
    else if ($action == 'fetchStudentClasses') {
        if (isset($_POST['studentId'])) {
            $studentId = $_POST['studentId'];
            $sql = "SELECT DISTINCT className FROM assignment WHERE studentId = ?";
    
            // Prepare the SQL statement
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $studentId);
    
            // Execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
    
                echo json_encode($data); // Send data as JSON
            } else {
                error_log("SQL Error: " . $stmt->error);
                echo json_encode(['error' => 'Database query failed']);
            }
    
            // Close statement
            $stmt->close();
        }
    } else if ($action == 'fetchStudentAbout') {
        if (isset($_POST['studentId'])) {
            $studentId = $_POST['studentId'];
            $sql = "SELECT guardianName, guardianNumber, schoolName, student.studentId FROM guardian INNER JOIN student ON student.studentId = guardian.studentId WHERE student.studentId = ?";
    
            // Prepare the SQL statement
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $studentId);
    
            // Execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
    
                echo json_encode($data); // Send data as JSON
            } else {
                error_log("SQL Error: " . $stmt->error);
                echo json_encode(['error' => 'Database query failed']);
            }
    
            // Close statement
            $stmt->close();
        }
    } else if ($action == 'submitAssignment') {
        $studentId = $_POST['studentId'];
        $assignmentName = $_POST['assignmentName'];
        $status = $_POST['status'];
        $due = $_POST['due'];
        $className = $_POST['className'];
    
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO assignment (studentId, assignmentName, status, due, className) VALUES (?, ?, ?, ?, ?)");
    
        // Bind parameters to the prepared statement
        $stmt->bind_param("issss", $studentId, $assignmentName, $status, $due, $className);
    
        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Record inserted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }
    
        // Close statement
        $stmt->close();
        }

    $conn->close();
?>