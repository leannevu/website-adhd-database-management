let selectedItemText = ''; // Variable to store the text of the selected item
let selectedAssignment = '';
var selectedOption = 'All';
let currentStudentId = 0;

document.addEventListener('DOMContentLoaded', function() {
    
    const list = document.querySelector('#scroll-list ul');
    list.addEventListener('click', function(e) {
        // Check if the clicked element is a list item
        if (e.target && e.target.nodeName === "LI") {
            // Remove highlighted class from previously selected item
            const current = list.querySelector('.highlighted');
            if (current && current !== e.target) {
                current.classList.remove('highlighted');
            }
            // Toggle highlight class on the clicked item
            e.target.classList.toggle('highlighted');

            // Get the text content of the clicked list item
            selectedItemText = e.target.textContent || e.target.innerText;

        // Send the selectedItemText to the PHP server
        $.ajax({
            url: 'database.php',
            type: 'POST',
            data: {
                itemText: selectedItemText,
                action: 'fetch'
            },
            dataType: 'json',
            success: function(response) {
                //console.log('Server response:', response);
                document.getElementById("studentIdText").value = response[0].studentId;
                currentStudentId = document.getElementById("studentIdText").value; //add value to studentId variable as well

                document.getElementById("studentAddressText").value = response[0].studentAddress;
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });

        }
    });

    const assignmentsList = document.querySelector('#scroll-list-assignments ul');
    assignmentsList.addEventListener('click', function(e) {
        // Check if the clicked element is a list item
        if (e.target && e.target.nodeName === "LI") {
            // Remove highlighted class from previously selected item
            const current = assignmentsList.querySelector('.highlighted');
            if (current && current !== e.target) {
                current.classList.remove('highlighted');
            }
            // Toggle highlight class on the clicked item
            e.target.classList.toggle('highlighted');

            // Get the text content of the clicked list item
            selectedAssignment = e.target.textContent || e.target.innerText;
            console.log('Selected Item Assignment:', selectedAssignment); // Log or use the text as needed

                    // Send the selectedItemText to the PHP server
        $.ajax({
            url: 'database.php',
            type: 'POST',
            data: {
                itemText: selectedAssignment,
                action: 'fetchAssignment'
            },
            dataType: 'json',
            success: function(response) {
                //console.log('Server response:', response); // Raw response
                if (response && response.length > 0) {
                    document.getElementById("classNameText").value = response[0].className;
                    document.getElementById("teacherText").value = response[0].teacherName;
                    document.getElementById("due").value = response[0].due;
                    document.getElementById("status").value = response[0].status;
                } else {
                    console.log("No data received or data is empty.");
                }
            }
        });
    
        }
    });

        // Function to show tabs and open the first one
        document.getElementById('viewStudentButton').addEventListener('click', function() {
            // Display the tabs container
            document.querySelector('.tabs').style.display = 'block';
            document.querySelector('.viewStudentsContainer').style.display = 'block';
            openTab(event, 'assignmentsTab')

            //Reset filter options and 
            document.getElementById('filterAssignments').value = 'All'; // This will select "All"
            selectedOption = "All";
            fetchAssignments();
            fetchClasses();
            fetchAbout();

            //disable viewStudentButton
            document.getElementById('viewStudentButton').disabled = true;

        });

        // Function to show tabs and open the first one
        document.getElementById('hideStudentButton').addEventListener('click', function() {
            // Display the tabs container
            document.querySelector('.tabs').style.display = 'none';
            document.querySelector('.viewStudentsContainer').style.display = 'none';

            //enable viewStudentButton
            document.getElementById('viewStudentButton').disabled = false;
      
        });

                // Function to show tabs and open the first one
                document.getElementById('addAssignmentButton').addEventListener('click', function() {
                    let assignmentNameInput = document.getElementById('assignmentNameInput').value;
                    let classNameInput = document.getElementById('classNameInput').value;
                    let statusInput = document.getElementById('statusInput').value;
                    let dueInput = document.getElementById('dueInput').value;

                    $.ajax({
                        url: 'database.php',
                        type: 'POST',
                        data: {
                            studentId: currentStudentId,
                            assignmentName: assignmentNameInput,
                            status: statusInput,
                            due: dueInput,
                            className: classNameInput,
                            action: 'submitAssignment'
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('Server response:', response); // This will show you the raw response
                        }
                    });
                });

        //Filter assignment everytime a filter is changed
        document.getElementById('filterAssignments').addEventListener('change', function() {
            selectedOption = this.options[this.selectedIndex].text;
            fetchAssignments();
        });

        //make dueInput value to be what the current date is
        var today = new Date();
        var dateStr = today.getFullYear() + '-' + 
                        ('0' + (today.getMonth() + 1)).slice(-2) + '-' + 
                        ('0' + today.getDate()).slice(-2);
        
        document.getElementById('dueInput').value = dateStr;

});

//Open one tab at a time
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    // Get all elements with class="tab-content" and hide them
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tab" and remove the class "active"
    tablinks = document.getElementsByClassName("tab");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

//About tab functionality (based on current student)
function fetchAbout() {
    $.ajax({
        url: 'database.php',
        type: 'POST',
        data: {
            studentId: currentStudentId,
            action: 'fetchStudentAbout'
        },
        dataType: 'json',
        success: function(response) {
            var guardianName = "";
            var guardianNumber = "";
            var schoolName = "";
            
            //Create text for about tab- remove ',' at end
            for (var i = 0; i < response.length; i++) {
                guardianName += response[i].guardianName;
                guardianNumber += response[i].guardianNumber;
                if (i != response.length - 1) {
                    guardianName += ", ";
                    guardianNumber += ", ";
                }
                if (response[i].schoolName != schoolName) {
                    schoolName += response[i].schoolName;
                }
            }
            document.querySelector("#guardianNameText").innerHTML = "Guardian Name(s): " + guardianName;
            document.querySelector("#guardianNumberText").innerHTML = "Guardian Number(s): " + guardianNumber;
            document.querySelector("#schoolNameText").innerHTML = "School Name(s): " + schoolName;
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}




