# Project Documentation - Supervised Driving Experience

## 📚 Table of Contents
1. [Project Overview](#project-overview)
2. [Database Schema](#database-schema)
3. [File Structure](#file-structure)
4. [API Reference](#api-reference)
5. [Frontend Components](#frontend-components)
6. [Backend Logic](#backend-logic)
7. [Testing Guide](#testing-guide)

---

## Project Overview

### Purpose
Track supervised driving experiences for learner drivers, helping them:
- Meet minimum driving hour requirements
- Track progress across different conditions
- Analyze driving patterns
- Prepare for licensing exams

### Technology Stack
- **Frontend**: HTML5, CSS3, JavaScript (ES6)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Libraries**: Chart.js 4.4.1
- **Server**: Apache/Nginx or PHP built-in server

---

## Database Schema

### Entity-Relationship Diagram

```
weather_conditions (1) ----< (M) driving_experiences
traffic_density (1) ----< (M) driving_experiences
road_types (1) ----< (M) driving_experiences
road_surface_quality (1) ----< (M) driving_experiences
```

### Table: weather_conditions
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(50) | NOT NULL, UNIQUE |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

**Default Data**: Clear, Rain, Snow, Fog, Storm

### Table: traffic_density
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(50) | NOT NULL, UNIQUE |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

**Default Data**: Light, Moderate, Heavy, Standstill

### Table: road_types
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(50) | NOT NULL, UNIQUE |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

**Default Data**: Highway, City Street, Country Road, Gravel, Dirt Trail

### Table: road_surface_quality
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(50) | NOT NULL, UNIQUE |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

**Default Data**: Pristine, Cracked, Potholed

### Table: driving_experiences
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| experience_date | DATE | NOT NULL |
| start_time | TIME | NOT NULL |
| end_time | TIME | NOT NULL |
| kilometers | DECIMAL(10,2) | NOT NULL |
| weather_id | INT | NOT NULL, FK -> weather_conditions(id) |
| traffic_id | INT | NOT NULL, FK -> traffic_density(id) |
| road_type_id | INT | NOT NULL, FK -> road_types(id) |
| surface_quality_id | INT | NOT NULL, FK -> road_surface_quality(id) |
| notes | TEXT | NULL |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

---

## File Structure

### Core Files

#### `index.php`
- Homepage/Dashboard
- Displays quick statistics
- Shows recent experiences
- Navigation hub

#### `pages/add-experience.php`
- Form for entering new driving experience
- Validates input on client and server side
- Displays success/error messages

#### `pages/process-experience.php`
- Backend processing for form submission
- Validates all inputs
- Inserts data into database
- Redirects with status message

#### `pages/summary.php`
- Displays all driving experiences in table
- Shows total kilometers
- Provides search and sort functionality
- Print-friendly layout

#### `pages/statistics.php`
- Displays charts and statistics
- Four main chart types (weather, traffic, road, surface)
- Tables with trip counts and averages

### Configuration Files

#### `includes/config.php`
- Database connection parameters
- Session initialization
- Timezone settings
- Connection function

#### `includes/functions.php`
- Reusable PHP functions
- Database query functions
- Data retrieval functions
- Formatting utilities

### Asset Files

#### `assets/css/style.css`
- Complete styling for all pages
- Responsive design with media queries
- CSS Grid and Flexbox layouts
- Print styles

#### `assets/js/form-validation.js`
- Client-side form validation
- Real-time input feedback
- Date and time validation
- Duration checks

#### `assets/js/table-features.js`
- Search functionality for tables
- Column sorting (ascending/descending)
- Results counter
- Dynamic row filtering

---

## API Reference

### Database Functions

#### `getDBConnection()`
**Returns**: mysqli connection object
**Usage**: `$conn = getDBConnection();`

#### `getWeatherConditions($conn)`
**Parameters**: mysqli connection
**Returns**: Array of weather conditions
**Usage**: `$weather = getWeatherConditions($conn);`

#### `getAllDrivingExperiences($conn)`
**Parameters**: mysqli connection
**Returns**: Array of all experiences with joined data
**Usage**: `$experiences = getAllDrivingExperiences($conn);`

#### `getTotalKilometers($conn)`
**Parameters**: mysqli connection
**Returns**: Float - total kilometers
**Usage**: `$total = getTotalKilometers($conn);`

#### `getStatsByWeather($conn)`
**Parameters**: mysqli connection
**Returns**: Array with count, total_km, avg_km grouped by weather
**Usage**: `$stats = getStatsByWeather($conn);`

### Helper Functions

#### `sanitizeInput($data)`
**Parameters**: String - raw input
**Returns**: String - sanitized input
**Usage**: `$clean = sanitizeInput($_POST['field']);`

#### `formatDate($date)`
**Parameters**: String - date (Y-m-d)
**Returns**: String - formatted date (F j, Y)
**Usage**: `echo formatDate('2025-12-23');`

#### `formatTime($time)`
**Parameters**: String - time (H:i:s)
**Returns**: String - formatted time (g:i A)
**Usage**: `echo formatTime('14:30:00');`

#### `calculateDuration($startTime, $endTime)`
**Parameters**: Two time strings
**Returns**: String - duration (HH:MM)
**Usage**: `$duration = calculateDuration('08:00:00', '10:30:00');`

---

## Frontend Components

### CSS Components

#### Grid Layout
```css
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-md);
}
```

#### Flexbox Navigation
```css
.main-nav {
    display: flex;
    gap: var(--spacing-sm);
    flex-wrap: wrap;
}
```

#### Responsive Tables
- Desktop: Standard table layout
- Mobile (<768px): Card-based layout with data-label attributes

### JavaScript Components

#### Form Validation
- Validates dates (not in future)
- Validates times (end > start)
- Validates kilometers (positive, reasonable)
- Real-time feedback with color coding

#### Table Features
- Dynamic search across all columns
- Column sorting (click header)
- Results counter
- Case-insensitive filtering

---

## Backend Logic

### Form Processing Flow

1. User submits form → `process-experience.php`
2. Check if POST request
3. Sanitize all inputs
4. Server-side validation:
   - Date not in future
   - End time after start time
   - Kilometers > 0
   - Valid foreign key references
5. Prepare SQL statement (prevent injection)
6. Execute INSERT query
7. Set session message (success/error)
8. Redirect back to form

### Data Retrieval Flow

1. Page loads → `summary.php` or `statistics.php`
2. Include config and functions
3. Get database connection
4. Call appropriate function(s)
5. Process results (loop, format)
6. Display in HTML with PHP
7. Close connection

### Security Measures

1. **SQL Injection Prevention**
   - Prepared statements with bind_param
   - Parameterized queries

2. **XSS Prevention**
   - htmlspecialchars() on all output
   - Content Security Policy headers

3. **Input Validation**
   - Server-side validation (primary)
   - Client-side validation (UX)
   - Type checking (intval, floatval)

4. **Session Security**
   - Session management for messages
   - No sensitive data in sessions

---

## Testing Guide

### Manual Testing Checklist

#### Form Submission
- [ ] Submit with all valid data
- [ ] Submit with future date (should fail)
- [ ] Submit with end time before start time (should fail)
- [ ] Submit with negative kilometers (should fail)
- [ ] Submit with missing required fields (should fail)
- [ ] Submit with very large kilometers (confirm prompt)
- [ ] Submit with notes
- [ ] Submit without notes

#### Summary Page
- [ ] View all experiences
- [ ] Verify total kilometers calculation
- [ ] Test search functionality
- [ ] Test column sorting (each column)
- [ ] Test on mobile view
- [ ] Test print functionality

#### Statistics Page
- [ ] Verify charts load
- [ ] Check data matches tables
- [ ] Test on different screen sizes
- [ ] Verify calculations (totals, averages)

#### Navigation
- [ ] Test all navigation links
- [ ] Verify active page highlighting
- [ ] Test on mobile menu

### Browser Testing
- Chrome/Edge (Windows)
- Firefox (Windows)
- Safari (Mac/iOS)
- Chrome Mobile (Android)

### Responsive Testing Breakpoints
- Desktop: 1920px, 1366px, 1024px
- Tablet: 768px
- Mobile: 480px, 375px, 320px

### Performance Testing
- Page load time < 2 seconds
- Database queries optimized (JOINs)
- No N+1 query problems
- CSS/JS minification (production)

---

## Deployment Checklist

### Pre-Deployment
- [ ] Update database credentials in config.php
- [ ] Set `display_errors = Off` in PHP
- [ ] Enable error logging
- [ ] Test all features on staging
- [ ] Backup database
- [ ] Review security settings

### Deployment Steps
1. Upload files via FTP/SFTP
2. Import database.sql
3. Configure config.php
4. Set file permissions (755 for directories, 644 for files)
5. Test homepage
6. Test all features
7. Check error logs

### Post-Deployment
- [ ] Verify all pages load
- [ ] Test form submission
- [ ] Check database connections
- [ ] Monitor error logs
- [ ] Test on mobile devices
- [ ] Verify charts display

---

## Maintenance

### Regular Tasks
- **Daily**: Check error logs
- **Weekly**: Database backup
- **Monthly**: Review and optimize database
- **Quarterly**: Update dependencies (Chart.js)

### Updates
- Keep PHP version current (security)
- Update MySQL when needed
- Monitor Chart.js for updates
- Review and update .htaccess

---

## Support & Resources

### Documentation
- PHP: https://www.php.net/docs.php
- MySQL: https://dev.mysql.com/doc/
- Chart.js: https://www.chartjs.org/docs/
- CSS Grid: https://css-tricks.com/snippets/css/complete-guide-grid/
- Flexbox: https://css-tricks.com/snippets/css/a-guide-to-flexbox/

### Tools
- phpMyAdmin for database management
- Chrome DevTools for debugging
- W3C Validator for HTML validation
- JSLint for JavaScript validation

---

**Last Updated**: December 23, 2025
**Version**: 1.0
**Status**: Production Ready
