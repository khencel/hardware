@extends('homepage')

@section('content')
<style scoped>
    .bottom_details_font{
        color: #8d8d8d;
        font-weight: bold;
        font-size: 17px;
        margin-left: 5px
    },
    .slider-container {
        width: 100%;
        max-width: 800px;
        text-align: center;
    }
    
    .slider-title {
        color: #666;
        font-size: 28px;
        margin-bottom: 20px;
        font-weight: normal;
        text-align: left;
    }
    
    .category-header {
        color: #666;
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: normal;
        text-align: center;
    }
    
    .slider-wrapper {
        position: relative;
        overflow: hidden;
        touch-action: pan-y;
    }
    
    .slider {
        display: flex;
        transition: transform 0.4s ease;
        touch-action: pan-x;
    }
    
    .slide {
        min-width: 100%;
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 10px;
    }
    
    .item {
        display: flex;
        flex-direction: column;
        border-radius: 0;
        overflow: hidden;
    }
    
    .item-name {
        background-color: #ababab;
        color: white;
        padding: 15px 5px;
        font-size: 16px;
        text-align: center;
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 80px;
    }
    
    .item-category {
        padding: 8px 5px;
        color: white;
        text-align: center;
        font-size: 14px;
        text-transform: uppercase;
    }
    
    .category-normal {
        background-color: #37BCB7;
    }
    
    .category-special {
        background-color: #CF7C43;
    }
    
    .category-brand {
        background-color: #C14F3D;
    }
    
    .out-of-stock {
        background-color: #E26A3F;
    }
    
    .dots {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    
    .dot {
        width: 10px;
        height: 10px;
        background-color: #bbb;
        border-radius: 50%;
        margin: 0 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .dot.active {
        background-color: #666;
    }
    
    @media (max-width: 600px) {
        .slide {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(5, 1fr);
        }
    },

    .bottom_button{
        background: #37BCB7
    }


</style>
    <div class="container-fluid bg-white">
        <div class="row">
            <div class="col-md-6 border" style="padding-left: 0%;padding-right:0%">
                <div style="height: 53vh;overflow:auto">
                    <table class="table" id="cart_table">
                        <thead style="background:#A39484;position: sticky; top: 0; z-index: 1;">
                            <th style="width: 50%">Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Subtotal</th>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div style="height: 30vh;">
                    <div class="row m-0" >
                        <div class="col-md-6" style="padding-left: 0%;padding-right:0%;height: 30vh;">
                            <div class="row m-0">
                                <div class="col p-0">
                                    <div class=" p-1" style="height: 10vh">
                                        <button class="btn btn-danger w-100 h-100" style="font-weight:bold;color:white">Void</button>
                                    </div>
                                    <div class=" p-1" style="height: 10vh">
                                        <button class="btn  w-100 h-100" style="background: #37BCB7;font-weight:bold;color:white">Hold Order</button>
                                    </div>
                                    <div class=" p-1" style="height: 10vh">
                                        <button class="btn  w-100 h-100" style="background: #37BCB7;font-weight:bold;color:white">Print Check</button>
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class=" p-1" style="height: 10vh">
                                        <button class="btn  w-100 h-100" style="background: #37BCB7;font-weight:bold;color:white">Refund</button>
                                    </div>
                                    <div class=" p-1" style="height: 10vh">
                                        <button class="btn  w-100 h-100" style="background: #37BCB7;font-weight:bold;color:white">Item 1</button>
                                    </div>
                                    <div class=" p-1" style="height: 10vh">
                                        <button class="btn  w-100 h-100" style="background: #37BCB7;font-weight:bold;color:white">Save Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding-left: 0%;padding-right:0%;height: 30vh;">
                            <div class="row">
                                <div class="col">
                                    <div style="height: 6vh;" class="border-bottom border-dark">
                                        <div class="row m-0">
                                            <div class="col p-0 bottom_details_font">SUBTOTAL</div>
                                            <div class="col p-0 bottom_details_font text-end" style="font-size: 20px">₱<span id="order_subtotal"> 0,00</span></div>
                                        </div>
                                    </div>
                                    <div style="height: 6vh;" class="border-bottom border-dark">
                                        <div class="row m-0">
                                            <div class="col p-0 bottom_details_font">DISCOUNT</div>
                                            <div class="col p-0 bottom_details_font text-end" style="font-size: 20px">₱ 0,00</div>
                                        </div>
                                    </div>
                                    <div style="height: 6vh;" class="border-bottom border-dark">
                                        <div class="row m-0">
                                            <div class="col p-0 bottom_details_font">TAX</div>
                                            <div class="col p-0 bottom_details_font text-end" style="font-size: 20px">₱ 0,00</div>
                                        </div>
                                    </div>
                                    <div style="height: 6vh;" class="border-bottom border-dark">
                                        <div class="row m-0">
                                            <div class="col p-0 bottom_details_font text-dark">ORDER TOTAL</div>
                                            <div class="col p-0 bottom_details_font text-end" style="font-size: 20px">₱<span id="order_total"> 0,00</div>
                                        </div>
                                    </div>
                                    <div style="height: 6vh;" class=" p-1">
                                        <button class="btn btn-success w-100 h-100">CHECKOUT</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 border">
                <div class="slider-container" style="height: 55vh;">
                    {{-- <h1 class="slider-title"></h1> --}}
                    
                    <div class="slider-wrapper">
                        <div class="slider" id="clothesSlider">
                            <!-- Slides will be dynamically generated -->
                        </div>
                    </div>
                    
                    <div class="dots" id="sliderDots">
                        <!-- Dots will be dynamically generated -->
                    </div>
                </div>

                <div class="">
                    @include('pos.category_slider')
                </div>
            </div>
        </div>
    </div>

    @include('pos.modal.add_item_modal')
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.19/jquery.touchSwipe.min.js"></script>
<script src="{{ asset('js/helper/app_helper.js') }}"></script>
<script src="{{ asset('js/pos/pos_category.js') }}"></script>
<script src="{{ asset('js/pos/pos_order.js') }}"></script>
<script src="{{ asset('js/pos/pos_action.js') }}"></script>




@endsection