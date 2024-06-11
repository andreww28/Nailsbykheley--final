let date = new Date();
let year = date.getFullYear();
let month = date.getMonth();

const day = document.querySelector(".calendar-dates");

const currdate = document
	.querySelector(".calendar-current-date");

const prenexIcons = document
	.querySelectorAll(".calendar-navigation span");

// Array of month names
const months = [
	"January",
	"February",
	"March",
	"April",
	"May",
	"June",
	"July",
	"August",
	"September",
	"October",
	"November",
	"December"
];


set_calendar_data(new Date().getDate());



async function  set_calendar_data(selected_day) {
	const item_container = document.querySelector('.appt-list');
	item_container.innerHTML = "";
	let d = new Date(year, month, selected_day);
	
	var formattedDateString = d.toLocaleDateString('en-US', {
		month: 'long',
		day: 'numeric',
		year: 'numeric'
	});

	d.setDate(d.getDate() + 1); 
	var formatted_date = d.toISOString().split('T')[0];

	let appts = await fetch_appointment_by_date(formatted_date);

	document.querySelector('.date-container h5').textContent = formattedDateString ;
	if(appts.length === 0) {
		item_container.innerHTML += `
			<div class="appt-item">
				<div class="appt-item-info">
					<p></p>
					<p>You have no appointments for today!</p>
				</div>
			</div>
			`
		
	}else {
		appts.forEach(appt => {
			item_container.innerHTML += `
			<div class="appt-item">
				<div class="appt-item-info">
					<p id="appt-item-refNo">${appt.referenceNum}</p>
					<p>${appt.time}</p>
					<p>${appt.service}</p>
				</div>
				<div class="appt-item-status" style="background:${appt.status === "pending" ? "red" : "green"}">${appt.status}</div>
			</div>
			`
		})

		let appt_item = document.querySelectorAll('.appt-item');
		appt_item.forEach((el,index) => {
			el.addEventListener('click', (e) => {
				var container = appt_item[index];
				let refNo = container.querySelector('#appt-item-refNo').textContent;
				let status = container.querySelector('.appt-item-status').textContent;

				if(status === 'pending') {
					window.location.href = "../pages/pendingAppointment.php" + "?param=" + encodeURIComponent(refNo);
				}else if(status === 'confirmed') {
					window.location.href = "../pages/confirmedAppointment.php" + "?param=" + encodeURIComponent(refNo);
				}
			})
		})
	}

	
}




// Function to generate the calendar
const manipulate = async () => {
	// Get the first day of the month
	let dayone = new Date(year, month, 1).getDay();
	
	// Get the last date of the month
	let lastdate = new Date(year, month + 1, 0).getDate();
	
	// Get the day of the last date of the month
	let dayend = new Date(year, month, lastdate).getDay();
	
	// Get the last date of the previous month
	let monthlastdate = new Date(year, month, 0).getDate();
	
    let currentMonth = new Date().getMonth() 
	
	// Variable to store the generated calendar HTML
	let lit = "";
	
	var appt_data = await fetch_all_appointment(month+1);

	// Loop to add the last dates of the previous month
	for (let i = dayone; i > 0; i--) {
		lit +=
		//`<li class="inactive">${monthlastdate - i + 1}</li>`;
		`<li class="inactive empty"> </li>`;
		
	}

	// Loop to add the dates of the current month
	for (let i = 1; i <= lastdate; i++) {
        let less_than = (month === currentMonth && i < date.getDate())
        ? "inactive" : "valid";


		// Check if the current date is today
		let isToday = i === date.getDate()
			&& month === new Date().getMonth()
			&& year === new Date().getFullYear()
			? "active-day"
			: "";

		lit += set_status_date(appt_data, isToday, less_than, i, month + 1);

        // if(i == 28) {
        //     lit += `<li class="${isToday} ${less_than}">
        //     <p>${i}</p>
        //     <div class="status-div">
        //         <div class="pending status"></div>
        //         <div class="confirmed status"></div>
        //         <div class="full-slot status"></div>
        //     </div>
            
        // </li>`
        // }
        // else {
        //     lit += `<li class="${isToday} ${less_than}">${i}</li>`;
        // }

        
	}

	// Loop to add the first dates of the next month
	for (let i = dayend; i < 6; i++) {
		//lit += `<li class="inactive">${i - dayend + 1}</li>`
		lit += `<li class="inactive empty"> </li>`

	}

	// Update the text of the current date element 
	// with the formatted current month and year
	currdate.innerText = `${months[month]} ${year}`;

	// update the HTML of the dates element 
	// with the generated calendar
	day.innerHTML = lit;

    document.querySelectorAll(".valid").forEach(valid_day => {
        valid_day.addEventListener('click', async (e) => {
			set_calendar_data(e.target.innerText);
        })
    })
}

manipulate();

// Attach a click event listener to each icon
prenexIcons.forEach(icon => {

	// When an icon is clicked
	icon.addEventListener("click", () => {
        let currentMonth = new Date().getMonth();
		// Check if the icon is "calendar-prev"
		// or "calendar-next"

        if(icon.id === "calendar-prev") {
            if(month - currentMonth >= 1){
                month -= 1;
            }
        }else if(icon.id === "calendar-next") {
            if(month - currentMonth <= 1){
                month += 1;
            }
        }

        

		// Check if the month is out of range
		if (month < 0 || month > 11) {

			// Set the date to the first day of the 
			// month with the new year
			date = new Date(year, month, new Date().getDate());

			// Set the year to the new year
			year = date.getFullYear();

			// Set the month to the new month
			month = date.getMonth();
		}

		else {

			// Set the date to the current date
			date = new Date();
		}

		// Call the manipulate function to 
		// update the calendar display
		manipulate();
	});
});

function combineStatusByDay(appointments) {
    // Group appointments by day
    const groupedAppointments = {};
    appointments.forEach(appointment => {
        const day = appointment.appointment_date.split('-')[2];
        if (!groupedAppointments[day]) {
            groupedAppointments[day] = [];
        }
        groupedAppointments[day].push(appointment.status);
    });

    // Combine status for each day
    const combinedAppointments = [];
    for (const day in groupedAppointments) {
        const combinedStatus = groupedAppointments[day];
        combinedAppointments.push({ day: day, status: combinedStatus });
    }

    return combinedAppointments;
}

function is_full_slot(statuses, appt_data) {
	var confirmed_length = statuses.filter(st => st === "confirmed").length;
	return confirmed_length === appt_data.available_time.length;
}


function set_status_date(appt_data, is_today, less_than, i, month) {
	// datas = Array.from(new Set(datas.map((d) => {new Date(d.appointment_date).getDate()})));
	var datas = combineStatusByDay(appt_data.data);
	
	for (var j = 0; j < datas.length; j++) {
		if (parseInt(datas[j].day) === i) {
			return `<li class="${is_today} ${less_than}">
			<p>${i}</p>
			<div class="status-div">
				${datas[j].status.includes('pending') && !is_full_slot(datas[j].status, appt_data)? '<div class="pending status"></div>' : '' }
				${datas[j].status.includes('confirmed') && !is_full_slot(datas[j].status, appt_data) ? '<div class="confirmed status"></div>' : ''}
				${is_full_slot(datas[j].status, appt_data) ? '<div class="full-slot status"></div>' : ''}
			</div>`
		}
	  }

	return `<li class="${is_today} ${less_than}">${i}</li>`;
}


function fetch_all_appointment(month) {
	return new Promise((resolve, reject) => {

		var xhr = new XMLHttpRequest();
	
		// Define the request parameters
			var url = '../../api/fetch_appointment.php';
			var params = 'month=' + month;
			xhr.open('POST', url, true);
	
			// Set the appropriate header
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	
			// Define the callback function
			xhr.onreadystatechange = function() {
				if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
					// Handle the response from PHP (data fetched from the database)
					resolve(JSON.parse(xhr.responseText));
				}
			};
	
			// Send the request
			xhr.send(params);
	
	});
}

function fetch_appointment_by_date(date) {
	return new Promise((resolve, reject) => {

		var xhr = new XMLHttpRequest();
	
		// Define the request parameters
			var url = '../../api/fetch_appointment.php';
			var params = 'selected_date=' + date;
			xhr.open('POST', url, true);
	
			// Set the appropriate header
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	
			// Define the callback function
			xhr.onreadystatechange = function() {
				if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
					// Handle the response from PHP (data fetched from the database)
					resolve(JSON.parse(xhr.responseText));
				}
			};
	
			// Send the request
			xhr.send(params);
	
	});
}
