# Project README

## Application Description

This project is intended for an exam. Please do not make any changes to the code.

## Setup Requirements

- **PHP version:** 8.1.10
- **Laravel version:** 10.48
- **Database name:** Laravel
- **Database Username:** root
- **Database Password:** (leave blank)

## How to Use

1. Additional setup and migrations:
	1. composer install
    2. Change the filename .env.example to .env
    4. php artisan key:generate
    5. php artisan migrate
2. Seed the database:
	php artisan db:seed Alternatively, you can load the {folder}.test in Laragon or any other similar environment.
3. Navigate to the "Register" button located at the upper right side of the system.
4. Register a new user.
5. Go to the login page and log in with the credentials you just created.

## Authentication
	
	#Once logged in, you can create, edit, and delete tasks. You will be able to see the categories for each priority, overdue tasks, and completed tasks. These will be aligned with each data's goal.

## Explanation
 # Authentication
 The application has authentication in place to prevent unauthorized access. If you attempt to manipulate the URL to access the dashboard or home page directly, the application will validate your session and redirect you to the page you were on before the attempt.

If you are on the home page and try to go to the login or register page, you will be redirected back to the home page if the authentication is valid.

	# Database Design
	The database consists of two main tables: Users and todo_items.

Although a relational model is not used, a foreign key relationship has been established:

In the todo_items table, user_id references the id column in the users table.
In the todo_items model:

public function users()
{
    return $this->hasMany(Users::class, 'id', 'user_id');
}

This README should provide clear and precise instructions and explanations about the application.

