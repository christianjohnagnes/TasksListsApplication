# Project README

## Application Description

This project is intended for an exam. Please do not make any changes to the code.

## Setup Requirements

- **PHP version:** 8.1.10
- **Laravel version:** 10.48
- **Database name:** laravel
- **Database Username:** root
- **Database Password:** (leave blank)

## How to Use

1. **Additional Setup and Migrations:**
   1. Run `composer install`.
   2. Rename the file `.env.example` to `.env`.
   3. Run `php artisan key:generate`.
   4. Run `php artisan migrate`. If you encounter the error `Unknown database 'laravel'`, create a database named `laravel` in MySQL.
2. **Seed the Database:**
   - Run `php artisan db:seed`. Alternatively, you can load the `{folder}.test` in Laragon or any other similar environment.
3. **Register a New User:**
   - Navigate to the "Register" button located at the upper right side of the system.
   - Register a new user.
4. **Login:**
   - Go to the login page and log in with the credentials you just created.
5. **Create and Manage Tasks:**
   - After logging in, you can create tasks by navigating to the left side middle of the home page.
   - After submitting tasks, you will see them listed next to the "Create Task" button.
   - Actions for each task:
     - *Note: If you haven’t seen the other icons, please click the first column or the title data to view the rest of the features.*
     - **Check icon:** Complete
     - **Times icon:** Incomplete
     - **Edit icon:** Update task
     - **Trash icon:** Remove task
6. **Categories and Search:**
   - You can see categories above the table; tasks are categorized accordingly.
   - You can search tasks using the search field above the table.
7. **Logout:**
   - You can locate the logout button in the upper-right corner of the home page.

## Authentication

Once logged in, you can create, edit, and delete tasks. You will be able to see categories for each priority, overdue tasks, and completed tasks. These features align with the goal of the application.

## Design Decisions

During the development of this exam project, I followed these steps:

1. Researched the concept of to-do lists and their impact on users.
2. Designed the database with two main tables: `users` and `todo_items`, as these are essential for the functionality of to-do lists.
3. Designed the pages before developing the backend.
4. Implemented the backend functionality and validated it to achieve the project goals.
5. Tested the application to identify and fix any issues before publishing it to GitHub.
6. Redesigned the to-do list modules to be extremely user-friendly.
7. Created this README file as the final step.

## Explanation

### Authentication

The application includes authentication to prevent unauthorized access. Attempting to manipulate the URL to access the dashboard or home page directly will result in validation of your session and redirection to the previous page.

If you try to access the login or register page while authenticated, you will be redirected back to the home page.

### Database Design

The database consists of two main tables: `users` and `todo_items`.

Although a relational model is not used, a foreign key relationship is established:

- In the `todo_items` table, the `user_id` references the `id` column in the `users` table.

In the `todo_items` model:

```php
public function users()
{
    return $this->hasMany(Users::class, 'id', 'user_id');
}
```

## Contact
	Developer: Christian John Agnes
	Facebook: Replace `https://www.facebook.com/agneschristianjohn`
	Purpose: Exam	
	
Feel free to adjust any part of the README to better fit your needs!