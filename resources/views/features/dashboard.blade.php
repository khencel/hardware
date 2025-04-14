@extends('homepage')


@section('header')
    Dashboard
@endsection


@section('content')
    <div class="overview-boxes">
        {{-- <div class="box">
    <div class="right-side">
      <div class="box-topic">Total Order</div>
      <div class="number">40,876</div>
      <div class="indicator">
        <i class='bx bx-up-arrow-alt'></i>
        <span class="text">Up from yesterday</span>
      </div>
    </div>
    <i class='bx bx-cart-alt cart'></i>
  </div>
  <div class="box">
    <div class="right-side">
      <div class="box-topic">Total Sales</div>
      <div class="number">38,876</div>
      <div class="indicator">
        <i class='bx bx-up-arrow-alt'></i>
        <span class="text">Up from yesterday</span>
      </div>
    </div>
    <i class='bx bxs-cart-add cart two' ></i>
  </div>
  <div class="box">
    <div class="right-side">
      <div class="box-topic">Total Profit</div>
      <div class="number">$12,876</div>
      <div class="indicator">
        <i class='bx bx-up-arrow-alt'></i>
        <span class="text">Up from yesterday</span>
      </div>
    </div>
    <i class='bx bx-cart cart three' ></i>
  </div>
  <div class="box">
    <div class="right-side">
      <div class="box-topic">Total Return</div>
      <div class="number">11,086</div>
      <div class="indicator">
        <i class='bx bx-down-arrow-alt down'></i>
        <span class="text">Down From Today</span>
      </div>
    </div>
    <i class='bx bxs-cart-download cart four' ></i>
  </div>
</div>

<div class="sales-boxes">
  <div class="recent-sales box">
    <div class="title">Recent Sales</div>
    <div class="sales-details">
      <ul class="details">
        <li class="topic">Date</li>
        <li><a href="#">02 Jan 2021</a></li>
        <li><a href="#">02 Jan 2021</a></li>
        <li><a href="#">02 Jan 2021</a></li>
        <li><a href="#">02 Jan 2021</a></li>
        <li><a href="#">02 Jan 2021</a></li>
        <li><a href="#">02 Jan 2021</a></li>
        <li><a href="#">02 Jan 2021</a></li>
      </ul>
      <ul class="details">
      <li class="topic">Customer</li>
      <li><a href="#">Alex Doe</a></li>
      <li><a href="#">David Mart</a></li>
      <li><a href="#">Roe Parter</a></li>
      <li><a href="#">Diana Penty</a></li>
      <li><a href="#">Martin Paw</a></li>
      <li><a href="#">Doe Alex</a></li>
      <li><a href="#">Aiana Lexa</a></li>
      <li><a href="#">Rexel Mags</a></li>
       <li><a href="#">Tiana Loths</a></li>
    </ul>
    <ul class="details">
      <li class="topic">Sales</li>
      <li><a href="#">Delivered</a></li>
      <li><a href="#">Pending</a></li>
      <li><a href="#">Returned</a></li>
      <li><a href="#">Delivered</a></li>
      <li><a href="#">Pending</a></li>
      <li><a href="#">Returned</a></li>
      <li><a href="#">Delivered</a></li>
       <li><a href="#">Pending</a></li>
      <li><a href="#">Delivered</a></li>
    </ul>
    <ul class="details">
      <li class="topic">Total</li>
      <li><a href="#">$204.98</a></li>
      <li><a href="#">$24.55</a></li>
      <li><a href="#">$25.88</a></li>
      <li><a href="#">$170.66</a></li>
      <li><a href="#">$56.56</a></li>
      <li><a href="#">$44.95</a></li>
      <li><a href="#">$67.33</a></li>
       <li><a href="#">$23.53</a></li>
       <li><a href="#">$46.52</a></li>
    </ul>
    </div>
    <div class="button">
      <a href="#">See All</a>
    </div>
  </div>
  <div class="top-sales box">
    <div class="title">Top Seling Product</div>
    <ul class="top-sales-details">
      <li>
      <a href="#">
        <img src="images/sunglasses.jpg" alt="">
        <span class="product">Vuitton Sunglasses</span>
      </a>
      <span class="price">$1107</span>
    </li>
    <li>
      <a href="#">
         <img src="images/jeans.jpg" alt="">
        <span class="product">Hourglass Jeans </span>
      </a>
      <span class="price">$1567</span>
    </li>
    <li>
      <a href="#">
       <img src="images/nike.jpg" alt="">
        <span class="product">Nike Sport Shoe</span>
      </a>
      <span class="price">$1234</span>
    </li>
    <li>
      <a href="#">
        <img src="images/scarves.jpg" alt="">
        <span class="product">Hermes Silk Scarves.</span>
      </a>
      <span class="price">$2312</span>
    </li>
    <li>
      <a href="#">
        <img src="images/blueBag.jpg" alt="">
        <span class="product">Succi Ladies Bag</span>
      </a>
      <span class="price">$1456</span>
    </li>
    <li>
      <a href="#">
        <img src="images/bag.jpg" alt="">
        <span class="product">Gucci Womens's Bags</span>
      </a>
      <span class="price">$2345</span>
    <li>
      <a href="#">
        <img src="images/addidas.jpg" alt="">
        <span class="product">Addidas Running Shoe</span>
      </a>
      <span class="price">$2345</span>
    </li>
    <li>
      <a href="#">
       <img src="images/shirt.jpg" alt="">
        <span class="product">Bilack Wear's Shirt</span>
      </a>
      <span class="price">$1245</span>
    </li>
    </ul>
  </div> --}}

        <style scoped>
            tr{
              line-height: 12px;
            }
        </style>

        <div class="container-fluid">
            @php
                $userRoles = auth()->user()->roles()->pluck('role_id')->toArray();
            @endphp
            <input type="hidden" name="roles_input" value="{{ json_encode($userRoles) }}">
            <div class="row" @if(!in_array(12, $userRoles)) style="display:none;" @endif>
                <div class="col border">
                    <div class="row">
                      <div class="col-4">
                          <select name="" id="filterSelect" class="form-control" onchange="selectFilter()">
                            <option value="per_year">Per Year</option>
                            <option value="date_range">Date Range</option>
                        </select>
                      </div>
                      <div class="col">
                        <div>
                          <input type="text" name="daterange" style="display: none" id="daterange" class="form-control" placeholder="Select date range" />
                          <select id="yearSelect" class="form-control" onchange="selectYear()">
                          </select>
                        </div>
                      </div>
                    </div>
                    <div id="myChart" style="width: 95%; height: 400px;"></div>
                </div>
                <div class="col border">
                  <div class="row">
                    <div class="col-4">
                        <select name="" id="list_category" class="form-control" onchange="selectedCategory()">
                          <option value="" selected hidden>--Select Category--</option>
                      </select>
                    </div>
                  </div>
                  <div id="myChart_room" style="width:95%; height:500px;"></div>
                  <div id="totalSalesDisplay" style="text-align: center; margin-top: 10px; font-weight: bold;"></div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <div>
                        <strong>
                            Rooms
                        </strong>
                    </div>
                    <div class="row" id="report_room">

                    </div>
                </div>
            </div>
        </div>


        {{-- modal  --}}
        <div class="modal fade" id="chartModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="sales_report">
                <div class="row">
                  <div class="col">
                    <div class="col" id="printableArea">
                      <img src="{{ asset('img/pantukan_logo.png') }}" class="mt-3" style="width: 70px" alt="">
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="row">
                            <div class="col-5">
                              <strong>Name:</strong>
                            </div>
                            <div class="col">
                              <span id="chart_name_modal">Khenneth S Alaiza</span>
                            </div>
                          </div>
    
                          <div class="row">
                            <div class="col-5">
                              <strong>Employee #:</strong>
                            </div>
                            <div class="col">
                              <span id="emp_num">PEMP-000001</span>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class="row">
                            <div class="col-2">
                              <strong>Date:</strong>
                            </div>
                            <div class="col-5">
                              <span id="current_date">Feb 10, 2025</span>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-2">
                              <strong>Total:</strong>
                            </div>
                            <div class="col-5">
                              <span style="font-weight: bold;font-size:15px" id="total_sales"></span>
                            </div>
                          </div>

                        </div>
                      </div>
                      

                      <hr>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <table class="table" id="sales_report_list">
                      <thead>
                        <th>TR#</th>
                        <th>Customer</th>
                        <th>Payment</th>
                        <th>Date</th>
                      </thead>

                      <tbody>
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success" onclick="printDiv('sales_report')">Print</button>
              </div>
            </div>
          </div>
        </div>
    @endsection

    @section('js')
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="{{ asset('js/helper/app_helper.js') }}"></script>
        
        <script src="{{ asset('js/dashboard/chart.js') }}"></script>
        {{-- <script src="{{ asset('js/dashboard/piechart.js') }}"></script>  --}}
    @endsection
