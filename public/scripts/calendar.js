
const prevNextIcon = document.querySelectorAll("#prev, #next");
const months = ["January", "February", "March", "April", "May", "June", "July",
              "August", "September", "October", "November", "December"];

document.addEventListener("DOMContentLoaded", function() {
    Calendar_Initialize.renderCalendar([]);
    
    prevNextIcon.forEach(icon => { // getting prev and next icons
        icon.addEventListener("click", Calendar_Initialize.updateMonthEvent);
    });
});

const get_off_days_based_month = (data, month) => {
    const filteredDates = data.filter(date => {
        const dateObj = new Date(date); // Create a Date object from the date string
        return dateObj.getMonth() === month; // Check if the month matches the target month
      });
      
      // Extract unique days from filtered dates
      const uniqueDays = [...new Set(filteredDates.map(date => {
        const dateObj = new Date(date);
        return dateObj.getDate(); // Get the day of the month
      }))];

      return uniqueDays;
}


const Calendar_Initialize = (function() {
    const daysTag = document.querySelector(".days"),
    currentDate = document.querySelector(".current-date")
    const disabled_date = [23,24,10];
    var selected_date = "";

    // getting new date, current year and month
    let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();
    let off_days;


    
    
    
    const renderCalendar = (disabled_date) => {
        let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), // getting first day of month
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // getting last date of month
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay() // getting last day of month
        let liTag = "";



        Form_Calendar.req_get_off_days().then(res => {
            off_days = get_off_days_based_month(res.off_days, currMonth);
            
            Form_Calendar.checkIfDayFullSlot(currMonth+1).then(fullSlotDays => {
                console.log('Full slot days:', fullSlotDays.full_slot_days);
                console.log(res.off_days);
                console.log('off days: ', off_days);
                // Perform actions with fullSlotDays

                let full_slot_days = fullSlotDays.full_slot_days.map(day => new Date(day).getDate());

                for (let i = firstDayofMonth; i > 0; i--) { // creating li of previous month last days
                    liTag += `<li class="inactive"></li>`;
                }
            
                for (let i = 1; i <= lastDateofMonth; i++) { // creating li of all days of current month
                    // adding active class to li if the current day, month, and year matched
                    let isToday = i === date.getDate() && currMonth === new Date().getMonth() 
                                && currYear === new Date().getFullYear() ? "active" : "";
        
                    let isValid = (currMonth === date.getMonth() && i < date.getDate() + 2) || full_slot_days.includes(i) || off_days.includes(i) ? "disabled" : "valid"; 
        
                    liTag += `<li class="${isToday} ${isValid}">${i}</li>`;
                }
            
                for (let i = lastDayofMonth; i < 6; i++) { // creating li of next month first days
                    // liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
                    liTag += `<li class="inactive"></li>`;
            
                }
        
                currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
                daysTag.innerHTML = liTag;
            
                document.querySelectorAll('.valid').forEach((d) => d.addEventListener('click', addDayClickEvent));




            }).catch(error => {
                console.error('Error:', error);
            });
        }).catch(error => {
            console.error('Error:', error);
        });



        
    }

    const addDayClickEvent = (e) => {
        const day = parseInt(e.target.textContent, 10);
        const date = new Date(currYear, currMonth, day);
        
        // Format the date as 'yyyy-mm-dd'
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based, so add 1
        const dayOfMonth = String(date.getDate()).padStart(2, '0');
        selected_date = `${year}-${month}-${dayOfMonth}`;
        
        // Set the value of the input field and alert the full date
        document.querySelector('#date-picker').value = selected_date;
        document.querySelector('#date-picker').nextElementSibling.style.display = 'none';
        document.querySelector('#select-date-btn').textContent = selected_date;
        
        //Calendar_Functions.hide_calendar();
        document.querySelector('.calendar-wrapper').classList.toggle('calendar-active');

        console.log(selected_date);
        Form_Calendar.checkAvailability(selected_date);
    }

    const updateMonthEvent = (e) => {
            const icon = e.target;
            console.log(icon)
            console.log(icon.id)
            let month = new Date().getMonth();
    
            if(icon.id === "prev") {
                if(currMonth - month >= 1){
                    currMonth -= 1;
                }
            }else {
                if(currMonth - month <= 0){
                    currMonth += 1;
                }
            }
            
    
            if(currMonth < 0 || currMonth > 11) { // if current month is less than 0 or greater than 11
                // creating a new date of current year & month and pass it as date value
                date = new Date(currYear, currMonth, new Date().getDate());
                currYear = date.getFullYear(); // updating current year with new date year
                currMonth = date.getMonth(); // updating current month with new date month
            } else {
                date = new Date(); // pass the current date as date value
            }
            renderCalendar(disabled_date); // calling renderCalendar function
        
    }

    return {
        renderCalendar,
        updateMonthEvent,
    }
})()
