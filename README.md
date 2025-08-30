# School SIS (Student Information System)

A comprehensive Laravel-based Student Information System designed to manage all aspects of educational institution operations.

## 🎓 Project Overview

School SIS is a modern, feature-rich student information system built with Laravel 11, Bootstrap 5, and MySQL. It provides a complete solution for managing students, teachers, departments, courses, events, and administrative tasks in educational institutions.

## 📸 Screenshots

> **Note**: Screenshots are referenced below. Please add actual screenshots to the `screenshots/` directory. See `screenshots/README.md` for guidelines.

### 🏠 Dashboard
![Dashboard](screenshots/dashboard.png)
*Main dashboard with statistics, charts, and quick access to all modules*

### 👤 Authentication
![Login](screenshots/login.png)
*Modern login interface with password visibility toggle*

### 📚 Student Management
![Students](screenshots/students.png)
*Student list with search, filter, and CRUD operations*

### 👨‍🏫 Teacher Management
![Teachers](screenshots/teachers.png)
*Teacher profiles and management interface*

### 🏢 Department Management
![Departments](screenshots/departments.png)
*Department organization and administration*

### 📅 Event Management
![Events](screenshots/events.png)
*Event calendar with categorization, priority levels, and scheduling*

### 📊 Grade Management
![Grades](screenshots/grades.png)
*Grade recording and academic performance tracking*

### 📋 Course Management
![Courses](screenshots/courses.png)
*Course catalog and curriculum management*

### 📖 Library Management
![Library](screenshots/library.png)
*Book catalog and borrowing system*

### 💰 Financial Management
![Financial](screenshots/financial.png)
*Fee management and financial reporting*

### 📈 Reports & Analytics
![Reports](screenshots/reports.png)
*Comprehensive reporting and analytics dashboard*

---

## ✨ Features

### 🏫 Core Management
- **Student Management**: Complete student profiles, enrollment tracking, and academic records
- **Teacher Management**: Teacher profiles, assignments, and performance tracking
- **Department Management**: Department organization and administration
- **Course Management**: Course creation, assignment, and tracking
- **Subject Management**: Subject catalog and curriculum management

### 📚 Academic Features
- **Grade Management**: Grade recording, GPA calculation, and academic performance tracking
- **Examination System**: Exam scheduling, result management, and performance analytics
- **Transcript Management**: Academic transcript generation and management
- **Graduate Tracking**: Alumni management and graduation records

### 📅 Administrative Tools
- **Event Management**: School events, calendar management, and scheduling
- **Attendance Tracking**: Student and staff attendance monitoring
- **Timetable Management**: Class scheduling and timetable generation
- **Communication System**: Internal messaging and notifications

### 💰 Financial Management
- **Financial Tracking**: Fee management, payment tracking, and financial reporting
- **Library Management**: Book catalog, borrowing system, and inventory management

### 📊 Reporting & Analytics
- **Comprehensive Reports**: Academic, financial, and administrative reports
- **Dashboard Analytics**: Real-time statistics and performance metrics
- **Data Export**: CSV export functionality for data analysis

### 🔐 Security & Access Control
- **User Management**: Role-based access control and user administration
- **Authentication**: Secure login system with password protection
- **Session Management**: Secure session handling and user tracking

## 🚀 Technology Stack

- **Backend**: Laravel 11 (PHP 8.3+)
- **Frontend**: Bootstrap 5, FontAwesome, Chart.js
- **Database**: MySQL 8.0+
- **Authentication**: Laravel's built-in authentication system
- **UI/UX**: Modern responsive design with interactive elements

## 📋 Requirements

- PHP 8.3 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for asset compilation)
- Web server (Apache/Nginx) or PHP built-in server

## 🛠️ Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd school-sis
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_sis
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 6. Create Sessions Table (if needed)
```bash
php artisan session:table
php artisan migrate
```

### 7. Start the Application
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## 👥 Default Users

After running the seeders, you can log in with:

- **Super Admin**: `admin@school.com` / `password`
- **Department Manager**: `dept@school.com` / `password`
- **Teacher**: `teacher@school.com` / `password`

## 📁 Project Structure

```
school-sis/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   └── web.php             # Web routes
└── public/                 # Public assets
```

## 🔧 Key Features Implementation

### Event Management System
- Full CRUD operations for events
- Event categorization (academic, social, sports, cultural, meeting, other)
- Priority levels (low, medium, high)
- Date and time management with all-day event support
- Color coding and status tracking
- Search and filtering capabilities
- CSV export functionality

### Modern UI/UX
- Responsive Bootstrap 5 design
- Interactive search and filter components
- Scrollable data tables with sticky headers
- Modern authentication forms
- Real-time dashboard analytics
- Auto-hiding alerts and notifications

### Database Design
- Comprehensive migration system
- Proper foreign key relationships
- Indexed columns for performance
- Soft deletes where appropriate
- Audit trails for important operations

## 📊 Database Schema

The system includes the following main tables:
- `users` - User accounts and authentication
- `students` - Student information and profiles
- `teachers` - Teacher information and assignments
- `departments` - Department organization
- `courses` - Course catalog and management
- `subjects` - Subject information
- `events` - Event management and scheduling
- `grades` - Academic grade tracking
- `graduates` - Alumni and graduation records
- `enrollments` - Student course enrollments
- `transcripts` - Academic transcript data

## 🔒 Security Features

- CSRF protection on all forms
- SQL injection prevention through Eloquent ORM
- XSS protection with Blade templating
- Secure password hashing
- Session security management
- Role-based access control

## 📈 Performance Optimizations

- Database indexing on frequently queried columns
- Eager loading of relationships to prevent N+1 queries
- Pagination for large datasets
- Efficient search and filtering algorithms
- Optimized asset loading and caching

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation in the `/docs` folder

## 🔄 Version History

- **v1.0.0** - Initial release with core features
- **v1.1.0** - Added event management system
- **v1.2.0** - Enhanced UI/UX and performance improvements
- **v1.3.0** - Added comprehensive reporting and analytics

---

**Built with ❤️ using Laravel 11**
