let selectedItemText = ''; // Variable to store the text of the selected item
let selectedAssignment = '';
var selectedOption = 'All';

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
            console.log('Selected Item Text:', selectedItemText); // Log or use the text as needed

                    // Send the selectedItemText to the PHP server
                    console.log('hello');
        $.ajax({
            url: 'database.php',
            type: 'POST',
            data: {
                itemText: selectedItemText,
                action: 'fetch'
            },
            dataType: 'json',
            success: function(response) {
                console.log('Server response:', response);
                console.log(response[0].studentId);
                document.getElementById("studentIdText").value = response[0].studentId;
                document.getElementById("studentAddressText").value = response[0].studentAddress;
                console.log(response[0].studentId);
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
                console.log('Server response:', response); // This will show you the raw response
                if (response && response.length > 0) {
                    document.getElementById("classNameText").value = response[0].className;
                    document.getElementById("teacherText").value = response[0].teacherName;
                    document.getElementById("due").value = response[0].due;
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
            fetchAssignments();
        });

                // Function to show tabs and open the first one
        document.getElementById('hideStudentButton').addEventListener('click', function() {
            // Display the tabs container
            document.querySelector('.tabs').style.display = 'none';
            document.querySelector('.viewStudentsContainer').style.display = 'none';
        });

        document.getElementById('filterAssignments').addEventListener('change', function() {
            selectedOption = this.options[this.selectedIndex].text;
            console.log('Selected value:', selectedOption);
            fetchAssignments();
        });

});


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
