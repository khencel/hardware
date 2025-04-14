// $(document).ready(function() {
    // Sample data - this could come from an API, JSON file, or database
   
    // const item_data = [
    //     // Slide 1 - Only include items that should be shown
    //     { name: "Winter Boots", category: "100", type: "normal" },
    //     { name: "Wool Scarf", category: "CATEGORY", type: "special" },
    //     { name: "Beanie Hat", category: "CATEGORY", type: "normal" },
    //     { name: "Slim Fit Chinos", category: "CATEGORY", type: "normal" },
    //     { name: "Levi's 501", category: "CATEGORY", type: "brand" },
    //     { name: "Polo Shirt", category: "CATEGORY", type: "normal" },
        
    //     { name: "Hanes Red Shirt", category: "CATEGORY", type: "normal" },
    //     { name: "Lee Bootcut Jeans", category: "CATEGORY", type: "normal" },
    //     { name: "Leggings", category: "CATEGORY", type: "normal" },
    //     { name: "Crew Sock", category: "CATEGORY", type: "normal" },
    //     { name: "Crew Sock", category: "CATEGORY", type: "normal" },
    //     { name: "Hanes Red Shirt", category: "CATEGORY", type: "normal" },
    //     { name: "Lee Bootcut Jeans", category: "CATEGORY", type: "normal" },
    //     { name: "Leggings", category: "CATEGORY", type: "normal" },
    //     { name: "Crew Sock", category: "CATEGORY", type: "normal" },
    //     { name: "Crew Sock", category: "CATEGORY", type: "normal" },
    //     { name: "Hanes Red Shirt", category: "CATEGORY", type: "normal" },
    //     { name: "Lee Bootcut Jeans", category: "CATEGORY", type: "normal" },
    //     { name: "Leggings", category: "CATEGORY", type: "normal" },
    //     { name: "Crew Sock", category: "CATEGORY", type: "normal" },
    //     { name: "Crew Sock", category: "CATEGORY", type: "normal" },
    //     { name: "Hanes Red Shirt", category: "CATEGORY", type: "normal" },
    //     { name: "Lee Bootcut Jeans", category: "CATEGORY", type: "normal" },
    //     { name: "Leggings", category: "CATEGORY", type: "normal" },
    //     { name: "Crew Sock", category: "CATEGORY", type: "normal" },
    //     { name: "Crew Sock", category: "CATEGORY", type: "normal" },
    // ];
    
    // Function to initialize slider with dynamic content
    function initSlider(data, itemsPerSlide = 18) {
        const $slider = $('#clothesSlider');
        const $dots = $('#sliderDots');
        
        // Filter out empty items
        const filteredData = data.filter(item => item.type !== 'empty' && item.name !== '');
        
        // Calculate number of slides needed
        const slideCount = Math.ceil(filteredData.length / itemsPerSlide);
        
        // Clear existing content
        $slider.empty();
        $dots.empty();
        
        // If no data, display a message
        if (filteredData.length === 0) {
            $slider.html('<div class="slide"><div class="no-items">No items to display</div></div>');
            return;
        }
        
        // Generate slides
        for (let i = 0; i < slideCount; i++) {
            // Create slide
            const $slide = $('<div class="slide"></div>');
            
            // Add items to slide
            const startIndex = i * itemsPerSlide;
            const endIndex = Math.min(startIndex + itemsPerSlide, filteredData.length);
            
            for (let j = startIndex; j < endIndex; j++) {
                const item = filteredData[j];
                let categoryClass = '';
                
                // Determine category class based on type
                switch(item.type) {
                    case 'normal':
                        categoryClass = 'category-normal';
                        break;
                    case 'special':
                        categoryClass = 'category-special';
                        break;
                    case 'brand':
                        categoryClass = 'category-brand';
                        break;
                    case 'outOfStock':
                        categoryClass = 'out-of-stock';
                        break;
                    default:
                        categoryClass = 'category-normal';
                }
                
                // Create normal item
                const $itemHtml = $(`
                    <div class="item" id="selectedItem">
                        <div class="item-name">${item.name}</div>
                        <div class="item-category ${categoryClass}">₱${item.price}</div>
                    </div>
                `);
                $itemHtml.data('item', item);
                // Add to slide
                $slide.append($itemHtml);
            }
            
            // Only add slides that have items
            if (endIndex > startIndex) {
                // Add slide to slider
                $slider.append($slide);
                
                // Add corresponding dot
                const dotClass = i === 0 ? 'dot active' : 'dot';
                $dots.append(`<span class="${dotClass}" data-index="${i}"></span>`);
            }
        }
        
        setupSliderInteraction();
    }
    
    // Set up swipe and interaction
    function setupSliderInteraction() {
        const $sliderWrapper = $('.slider-wrapper');
        const $slider = $('.slider');
        const $dots = $('.dot');
        const slideCount = $('.slide').length;
        let currentIndex = 0;
        
        // Handle dot clicks
        $dots.on('click', function() {
            currentIndex = parseInt($(this).attr('data-index'));
            updateSlider();
        });
        
        // Set up swipe using the TouchSwipe plugin
        if ($.fn.swipe) {
            $sliderWrapper.swipe({
                swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
                    if (direction === 'left') {
                        goToSlide(currentIndex + 1);
                    } else if (direction === 'right') {
                        goToSlide(currentIndex - 1);
                    }
                },
                threshold: 50,
                allowPageScroll: "vertical"
            });
        } else {
            console.warn('TouchSwipe plugin not loaded');
            
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
                        goToSlide(currentIndex + 1);
                    } else {
                        goToSlide(currentIndex - 1);
                    }
                }
            });
        }
        
        // Go to specific slide with boundary checks
        function goToSlide(index) {
            // Handle bounds
            if (index < 0) {
                index = slideCount - 1;
            } else if (index >= slideCount) {
                index = 0;
            }
            
            currentIndex = index;
            updateSlider();
        }
        
        function updateSlider() {
            // Update slider position
            $slider.css('transform', `translateX(-${currentIndex * 100}%)`);
            
            // Update active dot
            $dots.removeClass('active');
            $(`.dot[data-index="${currentIndex}"]`).addClass('active');
        }
        
        // Add keyboard navigation
        $(document).on('keydown', function(e) {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                e.preventDefault();
                if (e.key === 'ArrowLeft') {
                    goToSlide(currentIndex - 1);
                } else {
                    goToSlide(currentIndex + 1);
                }
            }
        });
        
        // Auto-rotate (optional)
        const autoSlideInterval = setInterval(function() {
            goToSlide(currentIndex + 1);
        }, 5000);
        
        // Stop auto-rotation on user interaction
        $sliderWrapper.on('mouseenter', function() {
            clearInterval(autoSlideInterval);
        });
    }
    
    // Initialize the slider with data
    // initSlider(item_data);
    
    // Example of how to update the slider with new data
    // This could be triggered by an API call, user interaction, etc.
    // Uncomment to test:
    /*
    $('#updateButton').on('click', function() {
        const newData = fetchNewData(); // Your function to get new data
        initSlider(newData);
    });
    */
    
    // Example function to fetch new data from an API
    // In a real application, this would make an AJAX request
    function fetchNewData() {
        // Make your API call here
        // return $.ajax({ url: '/api/clothes', method: 'GET' });
        
        // For demo purposes, just return some new static data
        return [
            { name: "New Product 1", category: "Category", type: "normal" },
            { name: "New Product 2", category: "Category", type: "special" },
            // Add more items...
        ];
    }
// });