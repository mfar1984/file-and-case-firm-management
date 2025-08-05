# File and Case Firm Management System

A comprehensive Laravel-based case management system designed specifically for Malaysian law firms. This system provides efficient management of court cases, client information, document handling, and calendar scheduling.

## 🏛️ Features

### Core Management
- **Case Management**: Track court cases with status updates and document management
- **Client Management**: Maintain client information with contact details and status tracking
- **Partner Management**: Manage law firm partners and their case assignments
- **File Management**: Physical file tracking with IN/OUT status and location management

### Calendar & Scheduling
- **FullCalendar.js Integration**: Interactive calendar for court hearings, client meetings, and deadlines
- **Event Types**: Color-coded events (Court Hearings, Client Meetings, Deadlines, Follow-ups, Case Filing)
- **Multiple Views**: Month, Week, Day, and List views
- **Event Management**: Add, edit, and manage calendar events

### User Interface
- **Modern Design**: Clean, professional interface using Tailwind CSS
- **Responsive Layout**: Works on desktop, tablet, and mobile devices
- **Material Icons**: Consistent iconography throughout the application
- **Dynamic Breadcrumbs**: Easy navigation with breadcrumb trails
- **Color-coded Status**: Visual status indicators for cases and clients

### Authentication & Security
- **Laravel Breeze**: Built-in authentication system
- **Role-based Access**: Different access levels for administrators, partners, and clients
- **Password Security**: Argon2 password hashing with salt
- **Session Management**: Secure session handling

## 🚀 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and npm (for frontend assets)

### Step 1: Clone the Repository
```bash
git clone https://github.com/mfar1984/file-and-case-firm-management.git
cd file-and-case-firm-management
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=firm
DB_USERNAME=root
DB_PASSWORD=root
```

### Step 5: Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### Step 6: Build Assets
```bash
npm run build
```

### Step 7: Create Admin User
```bash
php artisan tinker
```
```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@naaelahsaleh.my',
    'password' => Hash::make('password'),
    'email_verified_at' => now(),
]);
```

### Step 8: Start the Server
```bash
php artisan serve
```

Visit `http://localhost:8000` and login with:
- Email: `admin@naaelahsaleh.my`
- Password: `password`

## 📋 Usage Guide

### Dashboard Overview
The main dashboard provides:
- **Statistics Cards**: Total cases, active cases, clients, and partners
- **Recent Activity**: Latest cases and upcoming hearings
- **Quick Actions**: Access to main functions

### Case Management
1. Navigate to **Case** in the sidebar
2. View all cases in a table format
3. Use the **+** button to change case status:
   - Consultation
   - Quotation
   - Open file
   - Proceed
   - Closed file
   - Cancel
4. Use action buttons for View, Edit, and Delete

### Client Management
1. Navigate to **Client List** in the sidebar
2. View all clients with their contact information
3. Use action buttons:
   - **Block Icon**: Ban/Unban client
   - **Eye Icon**: View client details
   - **Edit Icon**: Modify client information
   - **Delete Icon**: Remove client

### Calendar Management
1. Navigate to **Calendar** in the sidebar
2. View events in different formats:
   - **Month View**: Overview of all events
   - **Week View**: Detailed weekly schedule
   - **Day View**: Hourly breakdown
   - **List View**: Chronological list
3. Filter events by type using the filter dropdown
4. Add new events using the "Add Event" button

### File Management
1. Navigate to **File Management** in the sidebar
2. Track physical files with IN/OUT status
3. Monitor file locations and return dates
4. Receive notifications for overdue files

## 🎨 UI Components

### Color Scheme
- **Primary**: Blue (`#3b82f6`)
- **Success**: Green (`#10b981`)
- **Warning**: Yellow (`#f59e0b`)
- **Danger**: Red (`#ef4444`)
- **Purple**: Case status (`#8b5cf6`)

### Event Types
- **🏛️ Court Hearings**: Red
- **👥 Client Meetings**: Blue
- **📋 Deadlines**: Yellow
- **📞 Follow-ups**: Green
- **📝 Case Filing**: Purple

### Font System
- **Primary Font**: Poppins
- **Icon Font**: Material Icons
- **Base Size**: 11px (minimum) to 13px (maximum)

## 🗂️ Project Structure

```
├── app/
│   ├── Http/Controllers/    # Application controllers
│   ├── Models/             # Eloquent models
│   └── Providers/          # Service providers
├── resources/
│   ├── views/              # Blade templates
│   │   ├── components/     # Reusable UI components
│   │   ├── layouts/        # Layout templates
│   │   └── settings/       # Settings pages
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/                 # Application routes
├── database/               # Migrations and seeders
└── public/                 # Public assets
```

## 🔧 Configuration

### Database
The system uses MySQL with the following main tables:
- `users` - User accounts and authentication
- `cases` - Case information and status
- `clients` - Client details and contact information
- `partners` - Law firm partners
- `files` - Physical file tracking
- `events` - Calendar events and scheduling

### Environment Variables
Key environment variables in `.env`:
```env
APP_NAME="Naeelah Firm"
APP_ENV=local
APP_DEBUG=true
DB_DATABASE=firm
DB_USERNAME=root
DB_PASSWORD=root
```

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` for security
3. Configure your web server (Apache/Nginx)
4. Set up SSL certificate
5. Configure database for production
6. Run `php artisan config:cache`
7. Run `php artisan route:cache`

### Recommended Hosting
- **Shared Hosting**: Compatible with most PHP hosting providers
- **VPS**: DigitalOcean, Linode, or AWS EC2
- **Cloud**: Laravel Forge, Heroku, or AWS Elastic Beanstalk

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
- Create an issue on GitHub
- Contact: admin@naaelahsaleh.my

## 🔄 Version History

- **v1.0.0** - Initial release with core case management features
- **v1.1.0** - Added calendar integration and client management
- **v1.2.0** - Enhanced UI with responsive design and Material Icons

---

**Built with ❤️ for Malaysian Law Firms**
