# User Management System

A secure User Management System built with PHP, MySQL, Tailwind CSS, and Node.js. The system handles user data (username, email, password) with full CRUD support. It features session management, role-based access control, and various security measures like password hashing and input validation.

## Tools and Packages Used
- **Visual Studio Code** - Text Editor
- **HTML, CSS, JavaScript** - Frontend Development
- **Node.js** - For installing and managing Tailwind CSS
- **Tailwind CSS** - CSS Framework
- **PHP** - Backend Scripting
- **XAMPP** - Development Environment for MySQL

## Features
- **CRUD Operations**: Provides permission-based CRUD operations for managing users.
- **Session Management**: Secure session management for user authentication.
- **Role-Based Access Control**: Restrict access based on user roles.
- **Password Hashing**: Passwords are securely hashed before storing in the database.
- **Input Validation**: Ensures that all form data is sanitized to prevent SQL injections and XSS attacks.
- **Personalized Dashboard**: Each user has a personalized dashboard.
- **Mobile-Responsive UI**: Built with Tailwind CSS to ensure a responsive and user-friendly design.
- **Light and Dark Theme**: Supports both light and dark theme modes.
- **Error Handling**: Session-based messaging to display errors or success messages.

## Security Measures
- **Input Sanitization**: All user inputs are sanitized before being processed.
- **Password Encryption**: Passwords are encrypted before storing them in the database.
- **Authentication**: Ensures only authorized users can access and edit their own data.
- **Form Validation**: All forms are validated before processing.
- **Redirect on Unauthorized Access**: Unauthorized users are redirected if they attempt to access restricted pages.

## Database Setup
1. **Database Name**: `user_management`
2. **Table Name**: `users`
3. **Table Structure**:
   - `user_id` - INT, Primary Key, Auto-increment
   - `username` - VARCHAR(50), Unique
   - `email` - VARCHAR(255), Unique
   - `password` - VARCHAR(255)
   - `created_at` - TIMESTAMP, Default `CURRENT_TIMESTAMP`

## Project Folder Structure

| Folder/File             | Description                                    |
|-------------------------|------------------------------------------------|
| `node_modules/`          | Dependency folder (auto-generated)             |
| `package.json`           | Project metadata and dependencies              |
| `package-lock.json`      | Project lock file                              |
| `src/`                   | Main source folder                             |
| `src/server.php`         | Contains globally used variables and tasks     |
| `src/output.css`         | Auto-generated CSS file by Tailwind CSS        |
| `src/input.css`          | Custom CSS file                                |
| `src/pages/`             | Main page contents (index.php, login.php, etc.)|
| `src/components/`        | Reusable components (headers, modals, etc.)    |
| `src/controllers/`       | Essential functions for the project            |

## How to Run the Project

### 1. Install Prerequisites
- Install **XAMPP** and **Node.js** if not already installed.

### 2. Set Up Project
- Open the command prompt and navigate to your XAMPP `htdocs` folder:
  - For Windows: `cd C:\xampp\htdocs`
  - For macOS: `cd /Applications/XAMPP/htdocs`
  - For Linux: `cd /opt/lampp/htdocs`
  
- Clone the repository:
  ```bash
  git clone https://github.com/prashantStha308/user_reg
  ```
  - Navigate to the project directory:
  ```bash
  cd user_reg
  ```
### 3. Install Node.js Dependencies
- Install all required packages:
  ```bash
  npm i
  ```
### 4. Set Up XAMPP
- Open XAMPP and start the MySQL server.
  - Go to ```localhost/phpmyadmin```in your browser.

### 5. Create Database and Table
- Create a new database called ```user_management```.
- Create a new table called ```users``` using the structure outlined in the Database Setup section.

### 6. Configure Server
- Edit src/server.php to define your database credentials:
```php
define("USER", 'YOUR_USER');
define("PASSWORD", 'YOUR_PASSWORD');
```
Replace ```YOUR_USER``` and ```YOUR_PASSWORD``` with your database credentials.
### 7. Access the Project
Visit ```localhost/user_reg/src/index.php``` in your browser to access the webpage.

### License
- This project is open-source and available under the MIT License.