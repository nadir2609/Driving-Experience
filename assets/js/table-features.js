/**
 * Table Features JavaScript
 * Add sorting, filtering, and search functionality to tables
 */

document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('experiencesTable');
    
    if (table) {
        // Add search functionality
        addSearchBox(table);
        
        // Add sorting to table headers
        addSortingToHeaders(table);
    }
});

/**
 * Add search box above the table
 */
function addSearchBox(table) {
    const searchContainer = document.createElement('div');
    searchContainer.style.marginBottom = '1rem';
    searchContainer.style.display = 'flex';
    searchContainer.style.gap = '1rem';
    searchContainer.style.flexWrap = 'wrap';
    searchContainer.style.alignItems = 'center';
    
    // Search input
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Search experiences...';
    searchInput.style.padding = '0.75rem';
    searchInput.style.border = '2px solid #E5E7EB';
    searchInput.style.borderRadius = '0.5rem';
    searchInput.style.fontSize = '1rem';
    searchInput.style.flex = '1';
    searchInput.style.minWidth = '250px';
    
    // Results counter
    const resultsCounter = document.createElement('span');
    resultsCounter.style.color = '#6B7280';
    resultsCounter.style.fontSize = '0.875rem';
    
    searchContainer.appendChild(searchInput);
    searchContainer.appendChild(resultsCounter);
    
    // Insert before table
    table.parentElement.insertBefore(searchContainer, table.parentElement.firstChild);
    
    // Update counter initially
    updateCounter();
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const tbody = table.querySelector('tbody');
        const rows = tbody.getElementsByTagName('tr');
        let visibleCount = 0;
        
        for (let row of rows) {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
        
        updateCounter(visibleCount, rows.length);
    });
    
    function updateCounter(visible = null, total = null) {
        if (visible === null) {
            const tbody = table.querySelector('tbody');
            total = tbody.getElementsByTagName('tr').length;
            visible = total;
        }
        resultsCounter.textContent = `Showing ${visible} of ${total} experiences`;
    }
}

/**
 * Add sorting functionality to table headers
 */
function addSortingToHeaders(table) {
    const headers = table.querySelectorAll('thead th');
    
    headers.forEach((header, index) => {
        // Skip the last column (Notes) from sorting
        if (index === headers.length - 1) return;
        
        header.style.cursor = 'pointer';
        header.style.userSelect = 'none';
        header.title = 'Click to sort';
        
        // Add sort indicator
        const indicator = document.createElement('span');
        indicator.textContent = ' ⇅';
        indicator.style.opacity = '0.5';
        header.appendChild(indicator);
        
        let sortDirection = 'asc';
        
        header.addEventListener('click', function() {
            // Remove indicators from other headers
            headers.forEach(h => {
                const ind = h.querySelector('span');
                if (ind && h !== header) {
                    ind.textContent = ' ⇅';
                    ind.style.opacity = '0.5';
                }
            });
            
            // Sort the table
            sortTable(table, index, sortDirection);
            
            // Update indicator
            indicator.textContent = sortDirection === 'asc' ? ' ▲' : ' ▼';
            indicator.style.opacity = '1';
            
            // Toggle direction for next click
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        });
    });
}

/**
 * Sort table by column
 */
function sortTable(table, columnIndex, direction) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        let aValue = a.cells[columnIndex].textContent.trim();
        let bValue = b.cells[columnIndex].textContent.trim();
        
        // Try to parse as number
        const aNum = parseFloat(aValue.replace(/[^0-9.-]/g, ''));
        const bNum = parseFloat(bValue.replace(/[^0-9.-]/g, ''));
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === 'asc' ? aNum - bNum : bNum - aNum;
        }
        
        // String comparison
        if (direction === 'asc') {
            return aValue.localeCompare(bValue);
        } else {
            return bValue.localeCompare(aValue);
        }
    });
    
    // Re-append rows in sorted order
    rows.forEach(row => tbody.appendChild(row));
}
