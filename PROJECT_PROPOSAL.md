# Web Development 1 - Project Proposal

**Student:** [Your Name]
**Course:** Web Development 1 (Term 2.2, 3 EC)
**Lecturers:** M. de Haan / Dan Breczinski
**Date:** January 7, 2026

---

## Project Title
**SquadSport** - Sports Match Management Platform

---

## 1. Problem Statement / Use Case

Many sports enthusiasts in Amsterdam struggle to find pick-up games and organized sports activities that match their skill level and availability. Existing solutions are often fragmented across social media groups, WhatsApp chats, or require downloading multiple apps.

**SquadSport** solves this problem by providing a centralized platform where users can:
- Discover upcoming sports matches in their city
- Join games based on their skill level and preferred sport
- Track their match history and performance
- Participate in organized tournaments and community events

This is an authentic use case that addresses a real need in the local sports community.

---

## 2. Target Users

### Primary Users (Players)
- Sports enthusiasts looking for pick-up games
- Players who want to track their performance
- People new to Amsterdam seeking sports communities

### Secondary Users (Organizers/Admins)
- Sports venue managers
- Tournament organizers
- Community sports coordinators

---

## 3. Core Features

### User Features
1. **User Registration & Authentication**
   - Secure account creation with email/password
   - Session-based authentication
   - Password hashing for security

2. **Match Discovery**
   - Browse upcoming matches by sport, location, and date
   - Real-time filtering without page refresh (AJAX)
   - View available player slots

3. **Match Participation**
   - Join/leave matches with instant updates
   - Track match history with win/loss records
   - Manage personal sports skill levels (1-5 rating)

4. **User Profiles**
   - Edit personal information (name, city, bio)
   - Add multiple sports with skill ratings
   - View participation history

### Admin Features
1. **Dashboard & Statistics**
   - Overview of users, matches, and tournaments
   - Quick access to management tools

2. **Content Management**
   - Create, update, delete matches
   - Manage sports types and locations
   - Organize tournaments
   - Create community events
   - Approve match results (win/loss)

### API Features
1. **Public JSON API**
   - Today's matches endpoint
   - Upcoming tournaments endpoint
   - Open for potential mobile app integration

---

## 4. Technical Implementation

### Technologies
- **Backend:** PHP 8+ with PDO and MariaDB
- **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JavaScript
- **Infrastructure:** Docker (NGINX, PHP-FPM, MariaDB)
- **Architecture:** Custom MVC pattern with routing

### Database Schema
```
users               - User accounts and authentication
matches             - Sports match information
match_participants  - Join table for match participation
sports              - Sports types (Football, Basketball, etc.)
locations           - Venue information
tournaments         - Tournament management
user_sports         - User skill levels per sport
events              - Community events
```

### MVC Architecture
- **Models:** Data layer with PDO prepared statements
- **Views:** HTML templates with proper separation
- **Controllers:** Request handling and business logic
- **Custom Router:** Query-string based routing (`?route=controller/action`)

---

## 5. How This Project Meets Course Requirements

### Required Functionality ✅
- **Authentic use case:** Sports community platform with real-world application
- **Goes beyond lectures:** Custom features like real-time match joining, AJAX filtering, skill-based matching
- **Original work:** All functionality designed and implemented independently

### CSS (2 points target) ✅
- Bootstrap 5.3.3 framework for responsive design
- Custom styling with CSS variables and modern design
- Fully responsive (mobile, tablet, desktop)
- Professional appearance with gradients and glass morphism
- Transition effects on interactive elements

### Sessions (1 point) ✅
- Session-based authentication
- User login state management
- Flash messaging system
- CSRF token storage

### Security (1 point) ✅
- **XSS Prevention:** `htmlspecialchars()` on all output
- **SQL Injection Prevention:** PDO prepared statements throughout
- **CSRF Protection:** Tokens on all forms and AJAX requests
- **Password Security:** Bcrypt hashing with `password_hash()`
- **Input Validation:** Email validation, required fields, type checking
- **Route Protection:** Authentication and authorization middleware

### MVC (1-2 points) ✅
- Clear MVC separation with 9 controllers and 10 models
- Complete CRUD operations for all entities
- Custom routing system
- Repository pattern with BaseModel class
- Organized view templates by feature

### API (1 point) ✅
- Public JSON endpoints (`/api/matches/today`, `/api/tournaments/upcoming`)
- Authenticated endpoints for match join/leave
- AJAX endpoints for filtering
- Proper HTTP status codes and JSON responses

### JavaScript (1 point) ✅
- Real-time match filtering with AJAX
- Join/leave matches without page refresh
- Fetch API for JSON communication
- Dynamic UI updates based on server responses

### Legal/Accessibility (1 point) ✅
- **WCAG 2.1 Level AA compliance:**
  - Semantic HTML5 elements
  - Form labels with proper associations
  - ARIA attributes for navigation
  - Responsive design for all devices
  - Sufficient color contrast
  - Focus states on interactive elements

- **GDPR compliance:**
  - Clear data collection disclosure
  - User rights (access, rectification, erasure)
  - Secure data handling
  - No third-party data sharing
  - Privacy-first approach

---

## 6. Unique/Advanced Features

Beyond basic requirements, SquadSport includes:

1. **Smart Match Filtering**
   - Multi-criteria filtering (sport, location, date)
   - AJAX-based updates without page reload
   - Real-time available slot calculation

2. **Match Participation System**
   - Automatic capacity management (open → full status)
   - Real-time participant counting
   - Result tracking (win/loss/pending)

3. **Admin Dashboard**
   - Comprehensive management interface
   - Instant result approval with AJAX
   - Participant tracking per match

4. **Skill-Based System**
   - User skill levels per sport (1-5 scale)
   - Multiple sports per user profile
   - Foundation for future skill-matching features

5. **Legacy Password Migration**
   - Supports upgrading old MySQL PASSWORD() hashes to bcrypt
   - Automatic migration on successful login

---

## 7. Development Approach

### Phase 1: Foundation ✅
- Docker environment setup
- Database schema design
- MVC architecture implementation
- Basic routing system

### Phase 2: Core Features ✅
- User authentication system
- Match CRUD operations
- Profile management
- Admin panel

### Phase 3: Advanced Features ✅
- AJAX filtering and real-time updates
- API endpoints
- Match participation system
- Tournament and event management

### Phase 4: Polish & Documentation ✅
- Security hardening (CSRF, XSS, SQL injection prevention)
- Accessibility improvements (WCAG compliance)
- GDPR compliance
- Comprehensive README documentation

---

## 8. Learning Objectives Achieved

Through this project, I have:

1. **Applied MVC Pattern**
   - Implemented proper separation of concerns
   - Created reusable model and controller base classes
   - Organized views by feature

2. **Mastered Database Integration**
   - PDO with prepared statements
   - Complex JOIN queries
   - Database relationships (one-to-many, many-to-many)

3. **Implemented Security Best Practices**
   - CSRF protection
   - XSS prevention
   - SQL injection prevention
   - Secure password handling

4. **Created RESTful APIs**
   - JSON endpoints for public data
   - Authenticated API routes
   - Proper HTTP status codes

5. **Built Interactive UIs**
   - AJAX for seamless user experience
   - Real-time updates without page refresh
   - Responsive design for all devices

6. **Understood Legal Requirements**
   - WCAG accessibility standards
   - GDPR data protection
   - User privacy and rights

---

## 9. Expected Deliverables

### Code Repository ✅
- GitHub repository: `Sukharev-ukr/webdev11`
- Clean commit history
- Comprehensive README with documentation

### Documentation ✅
- Complete README with setup instructions
- Security features documentation with code references
- WCAG compliance documentation
- GDPR compliance statement
- API documentation with examples

### Running Application ✅
- Dockerized environment for easy setup
- Accessible at `http://localhost`
- PHPMyAdmin for database management
- Fully functional with all features implemented

---

## 10. Timeline

- **Week 1:** Planning, database design, MVC setup
- **Week 2:** User authentication, basic CRUD operations
- **Week 3:** Match system, admin panel, AJAX features
- **Week 4:** API implementation, security hardening
- **Week 5:** Documentation, WCAG/GDPR compliance
- **Week 6:** Testing, refinement, final submission

---

## 11. Success Criteria

The project will be considered successful if:

✅ All CRUD operations are functional
✅ MVC pattern is properly implemented
✅ Security measures are in place (CSRF, XSS, SQL injection prevention)
✅ API endpoints return valid JSON
✅ JavaScript updates UI without page refresh
✅ Application is responsive on all devices
✅ WCAG and GDPR compliance is documented
✅ Code is clean, organized, and well-documented

---

## 12. Potential Challenges & Solutions

### Challenge 1: Real-time Updates
- **Solution:** Implemented AJAX with Fetch API and JSON responses

### Challenge 2: Security
- **Solution:** Comprehensive security layer with CSRF tokens, prepared statements, and output sanitization

### Challenge 3: Complex Database Relationships
- **Solution:** Carefully designed schema with proper foreign keys and JOIN queries

### Challenge 4: Accessibility
- **Solution:** Semantic HTML, ARIA attributes, and Bootstrap's built-in accessibility features

---

## 13. Future Enhancements (Post-Submission)

If time permits or for future development:
- Mobile app using the JSON API
- Email notifications for match reminders
- Advanced skill-based match recommendations
- In-app messaging between players
- Payment integration for tournament fees
- Social features (follow players, team creation)

---

## Conclusion

SquadSport is a comprehensive web application that demonstrates proficiency in all Web Development 1 course objectives. The project provides real value to the sports community while showcasing technical skills in PHP, MVC architecture, database design, security, API development, and modern web standards.

I am confident this project meets and exceeds the course requirements, and I look forward to your feedback.

---

**Repository:** https://github.com/Sukharev-ukr/webdev11
**Documentation:** See README.md in repository
**Contact:** [Your Email]

---

## Appendix: Technology Stack Summary

| Category | Technology | Purpose |
|----------|-----------|---------|
| Backend Language | PHP 8+ | Server-side logic |
| Database | MariaDB | Data persistence |
| Database Layer | PDO | Secure database access |
| Frontend Framework | Bootstrap 5.3.3 | Responsive UI |
| JavaScript | Vanilla JS + Fetch API | AJAX interactions |
| Web Server | NGINX | HTTP server |
| Containerization | Docker | Development environment |
| Version Control | Git + GitHub | Code management |
| Architecture | Custom MVC | Code organization |

---

**Signature:**
[Your Name]
Date: January 7, 2026
