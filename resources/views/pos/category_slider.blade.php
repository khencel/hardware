<style sc>
    .category-slider-container {
        width: 100%;
        max-width: 800px;
        text-align: center;
        margin-top: 30px;
    }
    
    .category-slider-wrapper {
        position: relative;
        overflow: hidden;
        touch-action: pan-y;
    }
    
    .category-slider {
        display: flex;
        transition: transform 0.4s ease;
        touch-action: pan-x;
    }
    
    .category-slide {
        min-width: 100%;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        padding: 10px;
    }
    
    .category-item {
        display: flex;
        flex-direction: column;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .category-item:hover {
        transform: translateY(-5px);
    }
    
    .category-item-icon {
        background-color: #f5f5f5;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #666;
    }
    
    .category-item-name {
        background-color: #666;
        color: white;
        padding: 8px 5px;
        font-size: 14px;
        text-align: center;
        text-transform: uppercase;
    }
    
    .category-item.active .category-item-name {
        background-color: #37BCB7;
    }
    
    @media (max-width: 600px) {
        .category-slide {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
        }
    }
</style>

<div class="category-slider-container mt-4">
    <h2 class="slider-title">Categories</h2>
    
    <div class="category-slider-wrapper">
        <div class="category-slider" id="categorySlider">
            <!-- Category slides will be dynamically generated -->
        </div>
    </div>
    
    <div class="dots" id="categorySliderDots">
        <!-- Dots will be dynamically generated -->
    </div>
</div>