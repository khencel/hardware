let currentYear = new Date().getFullYear();
let chart; 
let type_filter = "year";
let selected
let start_book;
let end_book;


$(document).ready(() => {
  
  getAllRoomCategory()
  
  for (var year = 2025; year <= 2030; year++) {
    $("#yearSelect").append(`<option value="${year}">${year}</option>`);
  }
  
});

async function getAllRoomCategory(){
  myUrl = "/api/show-all-category"
  var category = await get_data(myUrl)
  

  category.forEach(item => {
      const option_list = `
        <option value="${item.id}">${item.display_name}</option>
      `
      $('#list_category').append(option_list)
  });
  selected = category[0]['id']
  var for_sales = `/api/reports/rooms-status?year=${currentYear}`
  var for_room = `/api/reports/bookings?type=year&year=${currentYear}&room_category_id=${category[0]['id']}`
  loadReport(for_sales,for_room)
} 


function selectedCategory(){
  selected =  $('#list_category').val()
  var type = $("#filterSelect").val();
  var for_room = ""

  
  
  if(type == "date_range"){
    for_room = `/api/reports/bookings?type=range&room_category_id=${selected}&from=${start_book}&to=${end_book}`
  }else{
    for_room = `/api/reports/bookings?type=year&year=${currentYear}&room_category_id=${selected}`
  }
  
  drawChartRoom(for_room);
 
}



function selectFilter() {
  var type = $("#filterSelect").val();

  if (type === "per_year") {
      $("#yearSelect").show(); 
      $("#daterange").hide(); 
      loadReport(`/api/reports/rooms-status?year=${currentYear}`)
  } else if(type === "date_range") {
      $("#yearSelect").hide(); 
      $("#daterange").show(); 
  }else{
      $("#yearSelect").hide(); 
      $("#daterange").hide(); 
  }
}

function selectYear(){
  var year = $('#yearSelect').val()
  
  loadReport(`api/reports/rooms-status?year=${year}`,`/api/reports/bookings?type=year&year=${year}&room_category_id=${selected}`)
}


async function loadReport(myUrl,for_room) {
  const res = await get_data(myUrl);
  
  const sales_summary = res.data.sales_summary
  
  drawCharts(sales_summary,for_room)
  $('#report_room').empty();
    res.data.room_statuses.forEach(element => {
    const percent = (element.occupied / element.total) * 100 || 0; // Handle divide-by-zero

    const row = `
      <div class="col-2 p-1">
        <div class="card" style="box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.3);">
          <div class="card-header" style="background:#205099;color:white">
            <strong><small>${element.category_name}</small></strong>
          </div>
          <small>
          <div class="card-body">
            <div class="row">
              <div class="col">Vacant</div>
              <div class="col-4 text-end" style="font-size:14px"><strong class="text-danger">${element.available}</strong></div>
            </div>
            <div class="row">
              <div class="col">Occupied</div>
              <div class="col-4 text-end" style="font-size:14px"><strong class="text-success">${element.occupied}</strong></div>
            </div>
            <div class="row">
              <div class="col">Out of Service</div>
              <div class="col-4 text-end" style="font-size:14px"><strong class="text-default">${element.out_of_service}</strong></div>
            </div>
            <hr>
            <div class="row" style="background:linen">
              <div class="col">Total</div>
              <div class="col-4 text-end" style="font-size:14px">${element.total}</div>
            </div>
            
            <div class="row" style="background:#205099;color:white;font-weight:bold">
              <div class="col">Percentage</div>
              <div class="col-5 text-end" style="font-size:14px">${percent.toFixed(2)}%</div>
            </div>
          </div>
          </small>
        </div>
      </div>
    `;

    $('#report_room').append(row);
  });
}


document.addEventListener("DOMContentLoaded", function () {
  google.charts.load('current', {packages: ['corechart']});
  // google.charts.setOnLoadCallback(drawCharts);
});

function drawCharts(sales_summary,for_room) {
  drawChart(sales_summary);
  drawChartRoom(for_room);
}

async function drawChartRoom(link) {
  if (!google.visualization) {
      console.error("Google Charts failed to load.");
      return;
  }

  const myUrl = link;
  const roomData = await get_data(myUrl);

  if (!roomData || roomData.length === 0) {
      console.warn("No data available for room sales.");
      return;
  }

  // Compute total sales
  const totalSales = roomData.reduce((sum, room) => sum + room.total_sales, 0);
  
  const chartData = [['Room ID', 'Total Sales']];
  roomData.forEach(room => {
      chartData.push([`${room.room_name}`, room.total_sales]);
  });

  const data = google.visualization.arrayToDataTable(chartData);

  const options = {
      title: `Room Sales`,
      backgroundColor: 'transparent',
      legend: { position: 'bottom', textStyle: { color: 'black' } }
  };

  const chart = new google.visualization.BarChart(document.getElementById('myChart_room'));
  chart.draw(data, options);

  // Add total sales under the chart
  const totalSalesDiv = document.getElementById('totalSalesDisplay');
  if (totalSalesDiv) {
      totalSalesDiv.innerHTML = `<b>Total Sales: ${totalSales.toLocaleString()}</b>`;
  }
}



function drawChart(sales_summary) {
  var tag = []
  if (!google.visualization) {
    console.error("Google Charts failed to load.");
    return;
  }
  
  
  const chartData = [['User', 'Sales',"User ID"]]; // Header row
  sales_summary.forEach(item => {
    tag.push(item.sales)
    chartData.push([item.user_name, item.sales, item.user_id]);
  });

  sum = tag.reduce((acc, val) => acc + val, 0);

  if (sum === 0) {
    document.getElementById('myChart').innerHTML = '<div style="text-align: center; font-size: 16px; color: gray;">No records available</div>';
    return;
  }

  

  const data = google.visualization.arrayToDataTable(chartData);

  const options = {
    title: 'Sales Summary',
    is3D: true,
    backgroundColor: 'transparent', 
    legend: { position: 'bottom', textStyle: { color: 'black' } },
    titleTextStyle: { color: 'black' }
  };

  const chart = new google.visualization.PieChart(document.getElementById('myChart'));
  chart.draw(data, options);

  // Click Event Listener for Modal
  google.visualization.events.addListener(chart, 'select',async function () {
    const selectedItem = chart.getSelection()[0];
    if (selectedItem && selectedItem.row !== null) {
      const userName = chartData[selectedItem.row + 1][0]; 
      const sales = chartData[selectedItem.row + 1][1]; 
      const user_id = chartData[selectedItem.row + 1][2]
      let formattedNumber = `PEMP-${String(user_id).padStart(6, '0')}`;
      let today = new Date();
      let formattedDate = today.toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
      var type = $("#filterSelect").val();
      var year = $('#yearSelect').val()

      document.getElementById('modalTitle').innerText = `Details for ${userName}`;

      $('#chart_name_modal').text(userName)
      $('#emp_num').text(formattedNumber)
      $('#current_date').text(formattedDate)
      
      if(type=="per_year"){
        myUrl = `/api/transactions?user_id=${user_id}&year=${year}`
      }else{
        var from = start_book.split("-")
        var from = from[2]+"-"+from[0]+"-"+from[1]

        var to = end_book.split("-")
        var to = to[2]+"-"+to[0]+"-"+to[1]
  
        
        myUrl = `/api/transactions?user_id=${user_id}&start_date=${from}&end_date=${to}`
      }
      
      res = await get_data(myUrl)
      var total = 0
      
      $('#sales_report_list tbody').empty()
      res.transactions.forEach(element => {
        let date = new Date(element.created_at);
        total += parseInt(element.initial_payment)
        const row = `
          <tr>
            <td>${element.transaction_number?"TR#:"+element.transaction_number:"TR#:01252025-000000"}</td>
            <td>${element.customer}</td>
            <td>₱${element.initial_payment}</td>
            <td>${date.toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}</td>
          </tr>
        `
        $('#sales_report_list tbody').append(row)
      });

      $('#total_sales').text(`₱${total}`)
     
      var myModal = new bootstrap.Modal(document.getElementById('chartModal'));
      myModal.show();
    }
  });
}




$(function() {
  $('input[name="daterange"]').daterangepicker({
      opens: 'left'
  }, function(start, end, label) {
      
      start_book = start.format('MM-DD-YYYY')
      end_book = end.format('MM-DD-YYYY')
      var for_room = `/api/reports/bookings?type=range&room_category_id=${selected}&from=${start.format('MM-DD-YYYY')}&to=${end.format('MM-DD-YYYY')}`
      console.log(for_room);
      
      loadReport(`/api/reports/rooms-status?from=${start.format('MM-DD-YYYY')}&to=${end.format('MM-DD-YYYY')}`,for_room)
  });
});



function printDiv(divId) {
  const printContents = document.getElementById(divId).innerHTML;
  const originalContents = document.body.innerHTML;

  // Temporarily change the page content to only the div's content
  document.body.innerHTML = printContents;

  // Show elements with class 'rules_regulation' (if any)
  $('.rules_regulation').show();
  $('.customers_copy').show()

  // Log before printing
  console.log("Attempting to print...");

  // Add event listeners for beforeprint and afterprint
  window.addEventListener("beforeprint", function() {
      console.log("Print dialog is opening...");
  });

  window.addEventListener("afterprint", function() {
      console.log("Print dialog has closed.");
      // You can infer the action here (e.g., print was completed or canceled)
      document.body.innerHTML = originalContents;
      window.location.reload();
  });

  // Trigger the print dialog
  window.print();
}
