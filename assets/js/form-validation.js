/**
 * Form Validation JavaScript
 * Client-side validation for driving experience form
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('drivingForm');
    
    if (form) {
        // Get form elements
        const dateInput = document.getElementById('experience_date');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const kilometersInput = document.getElementById('kilometers');
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';
            
            // Validate date - not in future
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate > today) {
                isValid = false;
                errorMessage += 'Date cannot be in the future.\n';
            }
            
            // Validate times - end time must be after start time
            if (startTimeInput.value && endTimeInput.value) {
                const startTime = new Date('2000-01-01 ' + startTimeInput.value);
                const endTime = new Date('2000-01-01 ' + endTimeInput.value);
                
                if (endTime <= startTime) {
                    isValid = false;
                    errorMessage += 'End time must be after start time.\n';
                }
                
                // Check if duration is reasonable (not more than 12 hours)
                const durationHours = (endTime - startTime) / (1000 * 60 * 60);
                if (durationHours > 12) {
                    if (!confirm('The driving duration is more than 12 hours. Are you sure this is correct?')) {
                        isValid = false;
                    }
                }
            }
            
            // Validate kilometers
            const km = parseFloat(kilometersInput.value);
            if (isNaN(km) || km <= 0) {
                isValid = false;
                errorMessage += 'Kilometers must be a positive number.\n';
            } else if (km > 500) {
                if (!confirm('The distance traveled is quite high (' + km + ' km). Are you sure this is correct?')) {
                    isValid = false;
                }
            }
            
            // Show error message if validation fails
            if (!isValid && errorMessage) {
                alert(errorMessage);
                e.preventDefault();
                return false;
            }
        });
        
        // Real-time validation feedback
        kilometersInput.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value > 500) {
                this.style.borderColor = '#F59E0B';
            } else if (value > 0) {
                this.style.borderColor = '#10B981';
            } else {
                this.style.borderColor = '';
            }
        });
        
        // Time validation
        function validateTimes() {
            if (startTimeInput.value && endTimeInput.value) {
                const start = new Date('2000-01-01 ' + startTimeInput.value);
                const end = new Date('2000-01-01 ' + endTimeInput.value);
                
                if (end <= start) {
                    endTimeInput.style.borderColor = '#EF4444';
                } else {
                    endTimeInput.style.borderColor = '#10B981';
                }
            }
        }
        
        startTimeInput.addEventListener('change', validateTimes);
        endTimeInput.addEventListener('change', validateTimes);
        
        // Auto-calculate suggested end time (1 hour after start)
        startTimeInput.addEventListener('change', function() {
            if (this.value && !endTimeInput.value) {
                const start = new Date('2000-01-01 ' + this.value);
                start.setHours(start.getHours() + 1);
                const hours = String(start.getHours()).padStart(2, '0');
                const minutes = String(start.getMinutes()).padStart(2, '0');
                endTimeInput.value = hours + ':' + minutes;
            }
        });
    }
});
