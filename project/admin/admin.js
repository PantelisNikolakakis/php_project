$(document).ready(function() {
    // Click event for Delete button
    //$('.delete_user').click(function(e) {
    $(document).on("click", ".delete_user", function (e) {
        e.preventDefault(); 
        var userId = $(this).attr('user-id'); // Get the user-id from the button
        var row = $(this).closest('tr'); // Get the row containing the delete button

        if (confirm('Are you sure you want to delete this user?')) {
            // Make an AJAX call to delete_user.php
            $.ajax({
                url: 'delete_user.php',  // PHP script that will handle the delete operation
                type: 'POST',  // Sending POST request
                data: { user_id: userId },  // Send user-id to the server
                success: function(response) {
                    if (response == 'success') {
                        // If successful, remove the row from the table
                        row.fadeOut();
                    } else {
                        alert('There was an error deleting the user.');
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the user.');
                }
            });
        }
    });

    // Click event for approve button
    $(document).on("click", ".approve_request", function (e) {
        e.preventDefault(); 
        var btn = $(this);
        var row = $(this).closest('tr'); // Get the row containing the delete button
        var requestId = $(this).attr('data-id'); // Get the user-id from the button\
        
        // Make an AJAX call to approve_request.php
        $.ajax({
            url: 'approve_request.php',  // PHP script that will handle the delete operation
            type: 'POST',  // Sending POST request
            data: { request_id: requestId },  // Send user-id to the server
            success: function(response) {
                //alert(response)
                if (response == 'success') {
                    // If successful, remove the row from the table
                    btn.fadeOut();
                    row.find(".status_text").text("approved");

                    if (btn.parent('td').attr('data-status') != '0'){
                        btn.parent('td').append(`<button data-id=${requestId} class="btn btn-danger reject_request" title="approve"> <i class="fas fa-x"></i> </button> `)
                    }
                    btn.parent('td').attr('data-status' , '1')
                } else {
                    alert('There was an error approving the request.');
                }
            },
            error: function() {
                alert('There was an error approving the request.');
            }
        });
    });
    
    // Click event for reject button
    $(document).on("click", ".reject_request", function (e) {
        e.preventDefault(); 
        var btn = $(this);
        var row = $(this).closest('tr'); // Get the row containing the delete button
        var requestId = $(this).attr('data-id'); // Get the user-id from the button\
        
        // Make an AJAX call to approve_request.php
        $.ajax({
            url: 'reject_request.php',  // PHP script that will handle the delete operation
            type: 'POST',  // Sending POST request
            data: { request_id: requestId },  // Send user-id to the server
            success: function(response) {
                if (response == 'success') {
                    // If successful, remove the row from the table
                    btn.fadeOut();
                    row.find(".status_text").text("rejected");

                    if (btn.parent('td').attr('data-status') != '0'){
                        btn.parent('td').append(`<button data-id=${requestId} class="btn btn-success approve_request" title="approve"> <i class="fas fa-check"></i> </button> `)
                    }
                    btn.parent('td').attr('data-status' , '2')
                } else {
                    alert('There was an error rejecting the request.');
                }
            },
            error: function() {
                alert('There was an error rejecting the request.');
            }
        });
    });
});