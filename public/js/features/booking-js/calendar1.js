let scheduleRanges = null;
const DAYS_PER_PAGE = 15;
let currentStartDate = new Date();
let booked_list = null
let selectedIdsCategoryArray = null
let defaultCatID = null
let start_date_value_after_Action = null



async function getBookDate(month) {
    const myUrl = "/api/get-schedule-data";
    const myData = {
        "month": month,
        "room_category_id": selectedIdsCategoryArray?selectedIdsCategoryArray:[1]
    };

    try {
        const book_data = await store_data(myUrl, myData);
        console.log(book_data);
        
        scheduleRanges = book_data.data;
        return scheduleRanges;
    } catch (error) {
        console.error('Error fetching schedule data:', error);
        return null;
    }
}

async function handleMonthChange() {
    const selectedMonth = document.getElementById('monthSelect').value;
    currentStartDate = new Date(selectedMonth + '-01');
    
    
    // Fetch new data for the selected month
    await getBookDate(selectedMonth);
  
    // Generate schedule with the new data
    generateSchedule(currentStartDate);
    updateNavigationButtons();
}

function getDatesInRange(startDate, endDate) {
    let dateList = [];
    let currentDate = new Date(startDate);
    let stopDate = new Date(endDate);

    while (currentDate <= stopDate) {
        dateList.push(currentDate.toISOString().split("T")[0]);
        currentDate.setDate(currentDate.getDate() + 1);
    }
    return dateList;
}

function expandSchedule(scheduleRanges) {
    booked_list = get_all_booking()
    if (!scheduleRanges) return {};
    
    let expandedSchedule = {};

    for (let room in scheduleRanges) {
        expandedSchedule[room] = {};
        let roomSchedules = scheduleRanges[room];
        
        for (let person in roomSchedules) {
            let userBookings = roomSchedules[person];
            
            userBookings.forEach(schedule => {
                let dates = getDatesInRange(schedule.start, schedule.end);
                
                dates.forEach(day => {
                    if (!expandedSchedule[room][day]) {
                        expandedSchedule[room][day] = {};
                    }
                    if (!expandedSchedule[room][day][person]) {
                        expandedSchedule[room][day][person] = {};
                    }
                    
                    if (schedule.AM) {
                        expandedSchedule[room][day][person].AM = schedule.AM;
                        expandedSchedule[room][day][person].booking_id = schedule.booking_id;
                        expandedSchedule[room][day][person].reservation_room_details_id = schedule.reservation_room_details_id;
                        if (schedule.radiusLeft) expandedSchedule[room][day][person].radiusLeft = true;
                        if (schedule.radiusRight) expandedSchedule[room][day][person].radiusRight = true;
                    }
                    if (schedule.PM) {
                        expandedSchedule[room][day][person].PM = schedule.PM;
                        expandedSchedule[room][day][person].booking_id = schedule.booking_id;
                        expandedSchedule[room][day][person].reservation_room_details_id = schedule.reservation_room_details_id;
                        if (schedule.radiusLeft) expandedSchedule[room][day][person].radiusLeft = true;
                        if (schedule.radiusRight) expandedSchedule[room][day][person].radiusRight = true;
                    }
                });
            });
        }
    }

    return expandedSchedule;
}

function generateSchedule(startDate) {
    const endDate = new Date(startDate);
    endDate.setDate(endDate.getDate() + DAYS_PER_PAGE - 1);

    const tableHead1 = document.getElementById("dateRow");
    const tableHead2 = document.getElementById("amPmRow");
    const tableBody = document.getElementById("tableBody");

    const scheduleData = expandSchedule(scheduleRanges);

    tableHead1.innerHTML = "<th>Room</th>";
    tableHead2.innerHTML = "<th></th>";
    tableBody.innerHTML = "";

    let dateList = getDatesInRange(startDate, endDate);

    // Generate headers
    dateList.forEach(date => {
        let th = document.createElement("th");
        th.setAttribute("colspan", "2");
        const dateObj = new Date(date);
        const month = dateObj.toLocaleString('en-US', { month: 'short' });
        const day = dateObj.getDate();
        const year = dateObj.getFullYear();
        th.textContent = `${month} ${day}, ${year}`;
        
        const currentDate = new Date();
        if (currentDate.toISOString().split('T')[0] === date) {
            th.classList.add('current-date');
        }
        
        tableHead1.appendChild(th);

        let amTh = document.createElement("th");
        amTh.textContent = "AM";
        tableHead2.appendChild(amTh);

        let pmTh = document.createElement("th");
        pmTh.textContent = "PM";
        tableHead2.appendChild(pmTh);
    });

    for (let room in scheduleData) {
        
        let tr = document.createElement("tr");
        let tdRoom = document.createElement("td");
        tdRoom.textContent = room;
        tdRoom.style.backgroundColor = "white";
        
        const currentDate = new Date();
        const currentDateStr = currentDate.toISOString().split('T')[0];
        if (dateList.includes(currentDateStr)) {
            tdRoom.classList.add('current-date-room');
        }
        
        tr.appendChild(tdRoom);

        let slots = [];
        
        dateList.forEach(date => {
            let daySchedule = scheduleData[room][date] || {};
           
    
            ['AM', 'PM'].forEach(period => {
                let slotInfo = { person: null, class: null, radiusLeft: false, radiusRight: false, booking_id: null, reservation_room_details_id:null };
                for (let person in daySchedule) {
                    
                    
                    if (daySchedule[person][period]) {
                        
                        slotInfo.person = person;
                        slotInfo.class = daySchedule[person][period];
                        slotInfo.radiusLeft = daySchedule[person].radiusLeft;
                        slotInfo.radiusRight = daySchedule[person].radiusRight;
                        slotInfo.booking_id = daySchedule[person].booking_id;
                        slotInfo.reservation_room_details_id = daySchedule[person].reservation_room_details_id;
                        break;
                    }
                }
                slots.push(slotInfo);
            });
        });
        
        let i = 0;
        while (i < slots.length) {
            let currentSlot = slots[i];
            
            
            if (currentSlot.person) {
                let colspan = 1;
                while (i + colspan < slots.length && 
                       slots[i + colspan].person === currentSlot.person &&
                       slots[i + colspan].class === currentSlot.class) {
                    colspan++;
                }
                
                let td = document.createElement('td');
                let backgroundDiv = document.createElement('div');
                backgroundDiv.classList.add(currentSlot.class);
                
                const currentDate = new Date();
                const thisDate = new Date(dateList[Math.floor(i/2)]);
                if (currentDate.toISOString().split('T')[0] === dateList[Math.floor(i/2)]) {
                    td.classList.add('current-date');
                    // backgroundDiv.style.backgroundColor = '#ffff00';
                    // backgroundDiv.style.color = '#000000';
                }
                
                if (currentSlot.radiusLeft) {
                    backgroundDiv.classList.add('radius-left');
                }
                if (currentSlot.radiusRight || slots[i + colspan - 1].radiusRight) {
                    backgroundDiv.classList.add('radius-right');
                }
                
                backgroundDiv.textContent = currentSlot.person;
                
                if (currentSlot.reservation_room_details_id) {
                    backgroundDiv.style.cursor = 'pointer';
                    backgroundDiv.onclick = () => showModalBooked(currentSlot.reservation_room_details_id);
                }
                
                td.appendChild(backgroundDiv);
                td.setAttribute('colspan', colspan);
                tr.appendChild(td);
                
                i += colspan;
            } else {
                let td = document.createElement('td');
                tr.appendChild(td);
                i++;
            }
        }
        
        tableBody.appendChild(tr);
    }

    updatePageInfo();
}

async function nextPage() {
    currentStartDate.setDate(currentStartDate.getDate() + DAYS_PER_PAGE);
    // Check if we need to fetch data for the next month
    const nextMonth = new Date(currentStartDate).toISOString().slice(0, 7);
    await getBookDate(nextMonth);
    generateSchedule(currentStartDate);
    updateNavigationButtons();
}

async function previousPage() {
    currentStartDate.setDate(currentStartDate.getDate() - DAYS_PER_PAGE);
    // Check if we need to fetch data for the previous month
    const prevMonth = new Date(currentStartDate).toISOString().slice(0, 7);
    await getBookDate(prevMonth);
    generateSchedule(currentStartDate);
    updateNavigationButtons();
}

function updateNavigationButtons() {
    const currentMonth = document.getElementById('monthSelect').value;
    const currentMonthStart = new Date(currentMonth + '-01');
    const nextMonthStart = new Date(currentMonthStart);
    nextMonthStart.setMonth(nextMonthStart.getMonth() + 1);
    nextMonthStart.setDate(0);

    document.getElementById('prevBtn').disabled = 
        currentStartDate <= currentMonthStart;
    
    const endDate = new Date(currentStartDate);
    endDate.setDate(endDate.getDate() + DAYS_PER_PAGE - 1);
    document.getElementById('nextBtn').disabled = 
        endDate >= nextMonthStart;

    updatePageInfo();
}

function updatePageInfo() {
    const endDate = new Date(currentStartDate);
    endDate.setDate(endDate.getDate() + DAYS_PER_PAGE - 1);
    document.getElementById('pageInfo').textContent = 
        `${currentStartDate.toISOString().split('T')[0]} to ${endDate.toISOString().split('T')[0]}`;
}


// Initial load
async function initializeSchedule() {
    const initialMonth = currentStartDate.toISOString().slice(0, 7);
    console.log(initialMonth);
    
    await getBookDate(initialMonth);
    generateSchedule(currentStartDate);
    updateNavigationButtons();

    loadNationalities();
    loadallAddOns()
    loadCategory()
    
}

// Start the application
initializeSchedule();









