async function loadCalendar(startDate,category_id = 0) {
    const rooms = await loadRoom(category_id);
    const date_value = loadDate(startDate);
    
    $("#calendar_book thead, #calendar_book tbody").empty();

    const dateFormatter = new Intl.DateTimeFormat("en-US", {
        weekday: "short",
        month: "short",
        day: "numeric",
    });

    // Create header row
    const headerRow = [
        '<th class="sticky-left" style="width:150px;">Rooms</th>',
        ...date_value.map(date => {
            const formattedDate = dateFormatter.format(new Date(date));
            const dayOfWeek = new Date(date).getDay(); // 0 = Sunday, 6 = Saturday
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
            return `<th class="${isWeekend ? 'weekend' : ''}" style="width:150px;font-size:10px;">${formattedDate}</th>`;
        }),
    ];
    $("#calendar_book thead").html(headerRow.join(""));

    // Generate body rows
    const tbodyRows = [];
    for (const room of rooms) {
        const roomReservations = await getReservationsForRoom(room, date_value);

        if (roomReservations.length === 0) {
            const emptyRow = `<tr><td class="border sticky-left">${room}</td>` +
                date_value.map(() => '<td class="border" style="border-color:gray !important"></td>').join('') +
                "</tr>";
            tbodyRows.push(emptyRow);
        } else {
            roomReservations.forEach((reservations, index) => {
                
                let row = "<tr>";
                if (index === 0) {
                    row += `<td class="border sticky-left" rowspan="${roomReservations.length}">${room}</td>`;
                }
                date_value.forEach(date => {
                    const reservation = reservations.find(res => res.date_list.includes(date));
                    
                    if (reservation) {
                        const startIndex = reservation.date_list.indexOf(date);
                        if (startIndex === 0) {
                            const daysSpanning = reservation.date_list.filter(d => date_value.includes(d)).length;
                            var transaction_type = "Group";
                            if (reservation.other_rooms.length == 0){
                                transaction_type = "Single"
                            }

                            if (daysSpanning > 0 && daysSpanning <= date_value.length) {
                                const bgColor = getReservationColor(reservation.status);
                                row += `<td class="border clickable-reservation text-capitalize" 
                                             colspan="${daysSpanning}" 
                                             style="background-color:${bgColor}; text-align:center; vertical-align:middle; 
                                             padding:2px; border-radius:15px; color:white;font-size:12px; cursor:pointer;border-color:gray !important"
                                             data-reservation='${JSON.stringify(reservation)}'>
                                            ${reservation.name} (${transaction_type})
                                        </td>`;
                            }
                        }
                    } else {
                        row += '<td class="border" style="border-color:gray !important"></td>'; // Empty cell for dates without reservations
                    }
                });
                row += "</tr>";
                tbodyRows.push(row);
            });
        }
    }
    $("#calendar_book tbody").html(tbodyRows.join(""));

    // Event delegation for click handler
    $("#calendar_book").off("click").on("click", ".clickable-reservation", function () {
        const reservationData = $(this).data("reservation");
        handleReservationClick(reservationData);
    });
}

$(document).ready(() => {
    const today = new Date();
    const initialMonth = today.toISOString().slice(0, 7); // Format as YYYY-MM
    $("#datePicker").val(initialMonth);
    selected_category = 0

    const debouncedLoadCalendar = debounce(date => loadCalendar(date + "-01"), 300);

    // Initialize calendar
    loadCalendar(initialMonth + "-01",selected_category);

    // Reload calendar on date picker change
    $("#datePicker").on("change", function () {
        debouncedLoadCalendar($(this).val());
        selectedMonth = $(this).val()
    });
    
    loadNationalities();
    loadCategory();
    loadallAddOns()
});