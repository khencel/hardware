function selectDateCalendarChart(){
    const selectedDate = event.target.value;
    changeDate(selectedDate)
}


async function changeDate(date){
    currentStartDate = new Date(date);

    const year = currentStartDate.getFullYear();
    const month = String(currentStartDate.getMonth() + 1).padStart(2, '0');
    const formattedDate = `${year}-${month}`;

    await getBookDate(formattedDate);
    generateSchedule(currentStartDate);
    
}

async function showModalBooked(booked_id){
    var data = await booked_list;
    var reservations = null 

    data.forEach(element => {
        if(element.reservation_room_details_id == booked_id){
            reservations = element
            start_date_value_after_Action = reservations.start_date;
        }
    });
    
    handleReservationClick(reservations)
}


$(document).ready(function() {
    
    const selectedOptions = new Set();
    
    let selectedIdsArray = [];


    function setDefaultValues(defaultIds) {
        if (!defaultIds || !Array.isArray(defaultIds)) {
            return false;
        }
        
        // Clear current selections
        selectedOptions.clear();
        
        // Add each default ID to the selected options
        defaultIds.forEach(id => {
            // Convert to string to ensure consistency
            selectedOptions.add(id.toString());
        });
        
        // Update checkboxes to match the new selection
        $dropdownItemsContainer.find('input[type="checkbox"]').each(function() {
            const $checkbox = $(this);
            const value = $checkbox.val();
            $checkbox.prop('checked', selectedOptions.has(value));
        });
        
        // Update the display and array
        updateSelectedDisplay();
        updateSelectedIdsArray();
        
        return true;
    }
  
    const $searchInput = $('#searchInput');
    const $multiSelectTrigger = $('#multiSelectTrigger');
    const $dropdownMenu = $('.dropdown-menu');
    const $dropdownItemsContainer = $('#dropdownItemsContainer');

   
    function renderDropdownItems(items) {
        const itemsHtml = items.map(item => `
            <li>
                <label class="dropdown-item">
                    <input type="checkbox" class="form-check-input me-2" value="${item.id}"> ${item.display_name}
                </label>
            </li>
        `).join('');
        
        $dropdownItemsContainer.html(itemsHtml);
    }

   
    function loadDropdownItems() {
        // Show loading state
        $dropdownItemsContainer.html('<li class="text-center py-2">Loading...</li>');

        $.ajax({
            url: '/api/show-all-category',
            method: 'GET',
            success: function(response) {
                renderDropdownItems(response);
            },
            error: function(xhr, status, error) {
                $dropdownItemsContainer.html('<li class="text-center py-2 text-danger">Error loading items</li>');
                console.error('Error loading dropdown items:', error);
            }
        });
    }


    function updateSelectedIdsArray() {
        // Convert Set to Array
        selectedIdsArray = Array.from(selectedOptions);
        selectedIdsCategoryArray = selectedIdsArray
        // Log the array for debugging
        console.log('Selected IDs Array:', selectedIdsArray);
        changeDate(currentStartDate)
        
    }

    // Handle search functionality
    $searchInput.on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.dropdown-item').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).closest('li').toggle(text.includes(searchTerm));
        });
    });

    // Handle checkbox changes using event delegation
    $dropdownMenu.on('change', 'input[type="checkbox"]', function() {
        const $checkbox = $(this);
        const value = $checkbox.val();
        const text = $checkbox.parent().text().trim();

        if ($checkbox.is(':checked')) {
            selectedOptions.add(value);
        } else {
            selectedOptions.delete(value);
        }
        updateSelectedDisplay();
        updateSelectedIdsArray();
    });

    // Update the display of selected items
    function updateSelectedDisplay() {
        if (selectedOptions.size === 0) {
            $multiSelectTrigger.html('<span class="placeholder">Select options...</span>');
        } else {
            const selectedHTML = Array.from(selectedOptions).map(value => {
                // Make the selector more specific by scoping it to the dropdown items container
                const $checkbox = $dropdownItemsContainer.find(`input[value="${value}"]`);
                const text = $checkbox.parent().text().trim();
                return `
                    <span class="selected-item">
                        ${text}
                        
                    </span>
                `;
            }).join('');
            
            $multiSelectTrigger.html(selectedHTML);
        }
    }
    
    // Handle remove item clicks using event delegation
    $multiSelectTrigger.on('click', '.remove-item', function(e) {
        e.stopPropagation();
        const value = $(this).data('value');
        selectedOptions.delete(value);
        // Make the selector more specific by scoping it to the dropdown items container
        $dropdownItemsContainer.find(`input[value="${value}"]`).prop('checked', false);
        updateSelectedDisplay();
        updateSelectedIdsArray();
    });

    // Prevent dropdown from closing when clicking inside
    $dropdownMenu.on('click', function(e) {
        if (!$(e.target).is('a')) {
            e.stopPropagation();
        }
    });

    // Focus search input when dropdown opens
    $('.dropdown').on('shown.bs.dropdown', function() {
        $searchInput.focus();
        // Load items if not already loaded
        if ($dropdownItemsContainer.children().length === 0) {
            loadDropdownItems();
        }
    });

    // Clear search when dropdown closes
    $('.dropdown').on('hidden.bs.dropdown', function() {
        $searchInput.val('');
        $('.dropdown-item').closest('li').show();
    });

    // Optional: Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $dropdownMenu.removeClass('show');
        }
    });

    // Optional: Refresh dropdown items
    function refreshDropdownItems() {
        loadDropdownItems();
    }
    
    // Function to get the selected IDs array (can be called externally)
    function getSelectedIds() {
        return selectedIdsArray;
    }
    
    // Optionally expose the getSelectedIds function globally
    window.getSelectedIds = getSelectedIds;
});

