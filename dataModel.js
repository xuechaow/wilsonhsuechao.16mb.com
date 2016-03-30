/** The data model of the client page.
    By Xuechao
	2016/3/30 Last modified.
*/
$(function() {

	// Gather form information from the from
	var info = $('#insertEmployee');
	// Server message display div
	var serverText = $('#txtFromServer');
	var serverErr = $('#errFromServer');

	// Finish the submission in the background using ajax
	$(info).on('submit',function(event) {
		// Disable the original submission button
		event.preventDefault();

		// Serialize the form data.
		var infoData = $(info).serialize();

		// Use the buildin AJAX function to construct a data block and send to server. 
		// Use POST for security concerns.
		// Write individual functions to deal with success response or failure response.
		$.ajax({
			type: 'POST',
			url: $(info).attr('action'),
			data: infoData
		}).done(function(response) {
			// Display the server response in 'success' style
			$(serverErr).removeClass('error');
			$(serverErr).addClass('success');
			$(serverErr).text(response);
			listEmployee("All");
			// Clear the form for next submission
			$('#department').val('');
			$('#employeeNo').val('');
			$('#name').val('');
			$('#Male').prop('checked', true);
			$('#Female').prop('checked', false);
		}).fail(function(data) {
			// Display the server response in 'error' style
			$(serverErr).removeClass('success');
			$(serverErr).addClass('error');

			// Set the message text.
			if (data.responseText !== '') {
				$(serverErr).text(data.responseText);
			} else {
				$(serverErr).text('Send out failed');
			}
		});

	});

});
