/*
document.addEventListener('DOMContentLoaded', function () {
    // Handle session edit buttons
    document.querySelectorAll('.edit').forEach(button => {
        button.onclick = function () {
            let sessionId = this.id.split('-')[1];
            fetch('../actions/get_session_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `session_id=${sessionId}`
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('popupCourseID').value = data.CourseID;
                document.getElementById('SessionDate').value = data.SessionDate;
                document.getElementById('SessionTime').value = data.StartingTime;
                document.getElementById('Location').value = data.Location;
                document.getElementById('duration').value = data.Duration;
                document.getElementById('maxParticipants').value = data.MaxParticipants;
                document.querySelector('#edit_session').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
        };
    });

    // Handle session delete buttons
    document.querySelectorAll('.delete').forEach(button => {
        button.onclick = function () {
            let sessionId = this.id.split('-')[1];
            document.querySelector('#deleteSession input[name="SessionID"]').value = sessionId;
            document.querySelector('#deleteSession').style.display = 'block';
        };
    });

    // Close pop-ups
    document.querySelectorAll('.closeButton').forEach(button => {
        button.onclick = function () {
            this.parentElement.style.display = 'none';
        };
    });

    // Submit forms for edit and delete
    document.getElementById('edit_session form').onsubmit = function (event) {
        event.preventDefault();
        fetch('../actions/edit_session.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.text())
        .then(text => {
            alert(text);
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    };

    document.getElementById('deleteSession form').onsubmit = function (event) {
        event.preventDefault();
        fetch('../actions/delete_session.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.text())
        .then(text => {
            alert(text);
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    };
});
*/


document.addEventListener('DOMContentLoaded', function () {
    // Handle session edit buttons
    document.querySelectorAll('.edit').forEach(button => {
        button.onclick = function () {
            let sessionId = this.getAttribute('data-session-id');
            fetch('../actions/get_session_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'session_id=${sessionId}'
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('popupSessionID').value = sessionId; // Make sure this ID is correct
                document.getElementById('popupTypeID').value = data.TypeID; // Assuming there's a select element for type
                document.getElementById('popupSessionDate').value = data.SessionDate;
                document.getElementById('popupSessionTime').value = data.StartingTime;
                document.getElementById('popupLocation').value = data.Location;
                document.getElementById('popupDuration').value = data.Duration;
                document.getElementById('popupMaxParticipants').value = data.MaxParticipants;
                document.querySelector('#edit_session').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
        };
    });

    // Handle session delete buttons
    document.querySelectorAll('.delete').forEach(button => {
        button.onclick = function () {
            let sessionId = this.getAttribute('data-session-id');
            document.querySelector('#deleteSession input[name="SessionID"]').value = sessionId;
            document.querySelector('#deleteSession').style.display = 'block';
        };
    });

    // Close pop-ups
    document.querySelectorAll('.closeButton').forEach(button => {
        button.onclick = function () {
            this.parentElement.style.display = 'none';
        };
    });

    // Submit forms for edit and delete
    document.getElementById('edit_session form').onsubmit = function (event) {
        event.preventDefault();
        fetch('../actions/edit_session.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.text())
        .then(text => {
            alert(text);
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    };

    document.getElementById('deleteSession form').onsubmit = function (event) {
        event.preventDefault();
        fetch('../actions/delete_session.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.text())
        .then(text => {
            alert(text);
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    };
});
