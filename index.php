<html>
   <head>
      <meta charset = "UTF-8">
      <link rel="stylesheet" href="styles.css">
      </link>
      <title>ADHD Database</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   </head>
   <body>
      <script src="index.js"></script>
      <h1>ADHD Database Management</h1>
      <div class="container">
         <div id="scroll-list">
            <ul>
               <!-- Script will add names in here -->
            </ul>
            <script>
               // jQuery to fetch and display data
               function fetchData() {
               $.ajax({
                   url: 'database.php',
                   type: 'POST',
                   data: { action: 'fetch' },
                   dataType: 'json',  // Ensure this is set if you expect JSON
                   success: function(students) {
                       // Parse the JSON response
                       var htmlContent = '';
               
                       // Loop through each student and create HTML content
                       for (var i = 0; i < students.length; i++) {
                           htmlContent += '<li>' + students[i].studentName +
                                          '</li>';
                       }
               
                       // Append the HTML content to the table body
                       $('#scroll-list ul').html(htmlContent);
               
                       //Click on the first list item
                       const firstListItem = document.querySelector('#scroll-list ul li');
                       if (firstListItem) {
                       firstListItem.click(); // Simulate click on the first list item
                   }
                       fetchAssignments();
                   },
                   error: function(xhr) {
                       console.log("An error occurred: " + xhr.status + " " + xhr.statusText);
                   }
               });
               }
               
               // Call fetchData on page load
               $(document).ready(function() {
               fetchData();
               });
            </script>
         </div>
         <div id="text-boxes">
            <label for="studentIdText">Student Id</label>
            <input type="text" id="studentIdText" value="Student Id" readonly>
            <label for="studentIdText">Student Address</label>
            <input type="text" id="studentAddressText" value="Student Address" readonly>
            <button id="viewStudentButton">View Student Profile</button>
            <button id="hideStudentButton">Hide Student Profile</button>
         </div>
      </div>
      <div class="tabs" style="display: none;">
         <button class="tab" onclick="openTab(event, 'assignmentsTab')">Assignments</button>
         <button class="tab" onclick="openTab(event, 'addAssignmentsTab')">Add Assignment</button>
         <button class="tab" onclick="openTab(event, 'aboutTab')">About</button>
      </div>
      <div class="viewStudentsContainer" style="display: none;">
         <div id="assignmentsTab" class="tab-content">
            <h2>Hm</h2>
            <div id="assignmentsTabContainer">
               <div id="scroll-list-assignments">
                  <ul>
                     <!-- Script will add names in here -->
                  </ul>
                  <script>
                     // jQuery to fetch and display data
                     function fetchAssignments() {
                     $.ajax({
                         url: 'database.php',
                         type: 'POST',
                         data: {      
                             itemText: selectedItemText,
                             filterOption: selectedOption,
                             action: 'fetchStudentAssignments'
                         },
                         dataType: 'json',  // Ensure this is set if you expect JSON
                         success: function(response) {
                             // Parse the JSON response
                             //console.log('Server response:', response);
                             var htmlContent = '';
                     
                             // Loop through each student and create HTML content
                             for (var i = 0; i < response.length; i++) {
                                 htmlContent += '<li>' + response[i].assignmentName +
                                                '</li>';
                             }
                     
                             // Append the HTML content to the table body
                             $('#scroll-list-assignments ul').html(htmlContent);
                     
                             //Click on the first list item
                             const firstListAssignment = document.querySelector('#scroll-list-assignments ul li');
                             document.querySelector("#assignmentsTab h2").innerHTML = selectedItemText + "'s Assignment Details"
                            if (firstListAssignment) {
                                firstListAssignment.click(); // Simulate click on the first list item
                         }
                         },
                         error: function(xhr) {
                             console.log("An error occurred: " + xhr.status + " " + xhr.statusText);
                         }
                     
                     });
                     }
                     // Call fetchData on page load
                     $(document).ready(function() {
                     });
                  </script>
                  <label for="filterAssignments"></label>
                  <select id="filterAssignments">
                     <option>All</option>
                     <option>Complete</option>
                     <option>Incomplete</option>
                  </select>
               </div>
               <div id="text-boxes-assignments">
                  <label for="classNameText">Class Name</label>
                  <input type="text" id="classNameText" value="Class Name" readonly>
                  <label for="teacherNameText">Teacher Name</label>
                  <input type="text" id="teacherText" value="Teacher Name" readonly>
                  <label for="due">Due</label>
                  <input type="text" id="due" value="Due" readonly>
                  <label for="status">Status</label>
                  <input type="text" id="status" value="Status" readonly>
               </div>
            </div>
         </div>
      </div>
      <div id="addAssignmentsTab" class="tab-content">
                  <label for="assignmentNameInput">Assignment Name</label>
                  <input type="text" id="assignmentNameInput">
                  <label for="classNameInput">Class Name</label>
                  <select id="classNameInput"></select>
                  <script>
                     // jQuery to fetch and display data
                     function fetchClasses() {
                     $.ajax({
                         url: 'database.php',
                         type: 'POST',
                         data: {      
                             studentId: currentStudentId,
                             action: 'fetchStudentClasses'
                         },
                         dataType: 'json',  // Ensure this is set if you expect JSON
                         success: function(response) {
                             // Parse the JSON response
                             //console.log('Server response:', response);
                             var htmlContent = '';
                     
                             // Loop through each student and create HTML content
                             for (var i = 0; i < response.length; i++) {
                                 htmlContent += '<option>' + response[i].className +
                                                '</option>';
                             }
                     
                             // Append the HTML content to the table body
                             $('#classNameInput').html(htmlContent);
                     
                         },
                         error: function(xhr) {
                             console.log("An error occurred: " + xhr.status + " " + xhr.statusText);
                         }
                     
                     });
                     }
                     // Call fetchData on page load
                     $(document).ready(function() {
                     });
                  </script>
                  <label for="statusInput">Status</label>
                  <select id="statusInput">
                     <option>Complete</option>
                     <option>Incomplete</option>
                  </select>
                  <label for="dueInput">Status</label>
                  <input type="date" id="dueInput">
                  <button id="addAssignmentButton">Submit</button>
      </div>
      <div id="aboutTab" class="tab-content">
         <h3 id="guardianNameText">Guardian Name(s):</h3>
         <h3 id="guardianNumberText">Guardian Number(s):</h3>
         <h3 id="schoolNameText">School Name(s):</h3>
      </div>
      </div>
   </body>
</html>