/* Used in restaurant-add.php */

$(document).ready(function(){
	$("#restaurant_country").on("change", function(){
		var selected_value = $(this).val();
		var dropdownBox = $('#restaurant_state');
		dropdownBox.empty();
		dropdownBox.append('<option value="none">Select State</option>');
		dropdownBox.prop("disabled",false);

		$.ajax({
			url: "coordinator-add-loaddata.php",
			type: "POST",
			data: {location_country: selected_value},
			success: function(data,status,details){
				dropdownBox.append(data);
			},
			error: function(data,status,details){
				//append error msg to select
				dropdownBox.append('<option value="none">'+ status + ' ' + d3 + '</option>');
			}
		});
	});

	$("#restaurant_state").on("change", function(){
		var selected_value = $(this).val();
		var dropdownBox = $('#restaurant_city');
		dropdownBox.empty();
		dropdownBox.append('<option value="none">Select City</option>');
		dropdownBox.prop("disabled",false);

		$.ajax({
			url: "coordinator-add-loaddata.php",
			type: "POST",
			data: {location_state: selected_value},
			success: function(data,status,details){
				dropdownBox.append(data);
			},
			error: function(data,status,details){
				//append error msg to select
				dropdownBox.append('<option value="none">'+ status + ' ' + d3 + '</option>');
			}
		});
	});

	$("#restaurant_city").on("change", function(){
		var selected_value = $(this).val();
		var displayArea = $('#coordinator_pincode');
		displayArea.empty();
		//displayArea.append('<option value="none">Select Pin Code</option>');

		$.ajax({
			url: "coordinator-add-loaddata.php",
			type: "POST",
			data: {location_city: selected_value},
			success: function(data,status,details){
				displayArea.append(data);
			},
			error: function(data,status,details){
				//append error msg to select
				displayArea.append('<div class="alert alert-danger">ERROR!.. Please try selecting a city again to load location areas.</div>');
			}
		});
	});
});//end document ready