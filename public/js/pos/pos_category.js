
$(document).ready(function() {
    // Sample categories data
    // const categoriesData = [
    //     // Slide 1
    //     { name: "Clothes", active: true },
    //     { name: "Shoes", active: false },
    //     { name: "Accessories", active: false },
    //     { name: "Hats", active: false },
    //     // Slide 2
    //     { name: "Jewelry", active: false },
    //     { name: "Watches", active: false },
    //     { name: "Sportswear", active: false },
    //     { name: "Formal", active: false }
    // ];

    getAllCategory()
    let categoriesData = []

   
    async function getAllCategory(){
        var myUrl = "/api/gel-all-category"
        
        var data = await get_data(myUrl)
        initCategorySlider(data)
        return data
        
    }

    // Function to initialize category slider
    function initCategorySlider(data, itemsPerSlide = 4) {
        const $slider = $('#categorySlider');
        const $dots = $('#categorySliderDots');
        get_intial_item()
        // Calculate number of slides needed
        const slideCount = Math.ceil(data.length / itemsPerSlide);
        
        // Clear existing content
        $slider.empty();
        $dots.empty();
        
        // Generate slides
        for (let i = 0; i < slideCount; i++) {
            // Create slide
            const $slide = $('<div class="category-slide"></div>');
            
            // Add items to slide
            const startIndex = i * itemsPerSlide;
            const endIndex = Math.min(startIndex + itemsPerSlide, data.length);
            
            for (let j = startIndex; j < endIndex; j++) {
                const category = data[j];
                const activeClass = category.active ? 'active' : '';
                
                // Create category item
                const $item = $(`
                    <div class="category-item ${activeClass}">
                        <div class="category-item-name">${category.name}</div>
                    </div>
                `);

                $item.data('category', category);
                // Add to slide
                $slide.append($item);
            }
            
            // Add slide to slider
            $slider.append($slide);
            
            // Add corresponding dot
            const dotClass = i === 0 ? 'dot active' : 'dot';
            $dots.append(`<span class="${dotClass}" data-index="${i}"></span>`);
        }
        
        setupCategorySliderInteraction();
    }
    
    // Set up category slider interaction
    function setupCategorySliderInteraction() {
        const $sliderWrapper = $('.category-slider-wrapper');
        const $slider = $('.category-slider');
        const $dots = $('#categorySliderDots .dot');
        const slideCount = $('.category-slide').length;
        let currentIndex = 0;
        
        // Handle dot clicks
        $dots.on('click', function() {
            currentIndex = parseInt($(this).attr('data-index'));
            updateCategorySlider();
        });
        
        // Set up swipe using the TouchSwipe plugin
        if ($.fn.swipe) {
            $sliderWrapper.swipe({
                swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
                    if (direction === 'left') {
                        goToCategorySlide(currentIndex + 1);
                    } else if (direction === 'right') {
                        goToCategorySlide(currentIndex - 1);
                    }
                },
                threshold: 50,
                allowPageScroll: "vertical"
            });
        } else {
            // Fallback touch handling
            let touchStartX = 0;
            let touchEndX = 0;
            
            $sliderWrapper.on('touchstart', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
            });
            
            $sliderWrapper.on('touchend', function(e) {
                touchEndX = e.originalEvent.changedTouches[0].clientX;
                const distance = touchStartX - touchEndX;
                
                if (Math.abs(distance) > 50) {
                    if (distance > 0) {
                        goToCategorySlide(currentIndex + 1);
                    } else {
                        goToCategorySlide(currentIndex - 1);
                    }
                }
            });
        }
        
        // Go to specific category slide with boundary checks
        function goToCategorySlide(index) {
            // Handle bounds
            if (index < 0) {
                index = slideCount - 1;
            } else if (index >= slideCount) {
                index = 0;
            }
            
            currentIndex = index;
            updateCategorySlider();
        }
        
        function updateCategorySlider() {
            // Update slider position
            $slider.css('transform', `translateX(-${currentIndex * 100}%)`);
            
            // Update active dot
            $dots.removeClass('active');
            $(`#categorySliderDots .dot[data-index="${currentIndex}"]`).addClass('active');
        }
        
        // Handle category item clicks
        $(document).on('click', '.category-item',async function() {
            
            $('.category-item').removeClass('active');
            
            
            $(this).addClass('active');
            
          
            const categoryName = $(this).data('category');
            
            var myUrl = `/api/get-all-item-per-category/${categoryName.id}`
            var data = await get_data(myUrl)
            initSlider(data)
            $('.slider-title').text(categoryName.name);
        });
    }
    
    // Initialize the category slider
    initCategorySlider(categoriesData);
    
    // Example function to update product slider based on selected category
    function updateProductSliderWithCategory(categoryName) {
        // In a real application, this would make an AJAX request to get products
        // for the selected category
        
        // Sample implementation:
        /*
        $.ajax({
            url: `/api/products?category=${categoryName}`,
            method: 'GET',
            success: function(data) {
                // Update the main product slider with new data
                initSlider(data);
            },
            error: function(error) {
                console.error('Error fetching products:', error);
            }
        });
        */
    }

    async function get_intial_item(){
        var myUrl = `/api/get-all-item-per-category/1`
        var data = await get_data(myUrl)
        initSlider(data)
    }
});
