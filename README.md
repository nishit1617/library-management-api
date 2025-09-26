# Library Management API
## Overview
The Library Management API is a RESTful application that allows users to manage books, authors, and library members efficiently. It supports common operations such as adding, updating, deleting, and viewing books and members, as well as managing borrow/return transactions. The project is built using Laravel and follows best practices for maintainable and scalable backend development.

## Features
- CRUD operations for books, authors, and members
- Borrowing and returning books
- API endpoints for managing library data
- Validation and error handling for all requests

## Setup & Installation
### Prerequisites
- PHP >= 8.1
- Composer
- MySQL or any other supported database
- Git

### Steps
1. Clone the repository: `git clone https://github.com/yourusername/library-management-api.git && cd library-management-api`
2. Install dependencies: `composer install`
3. Create `.env` file: `cp .env.example .env` and update your database credentials in `.env`
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Start the development server: `php artisan serve` (API will be available at `http://localhost:8000`)
   
## Running Tests
Run all tests: `php artisan test`  
Run a specific test file: `php artisan test --filter=TestFileName`

## Architectural & Design Decisions
- Framework: Choose Laravel for its simplicity, elegant syntax, and built-in support for RESTful APIs.
- Database Design: Normalized tables for `books`, `authors`, `members`, and `transactions` to ensure data integrity.
- API Structure: Follows RESTful conventions with resource controllers for modularity.
- Validation & Error Handling: Leveraged Laravel’s built-in validation and exception handling to maintain robust endpoints.

## Swagger

- http://localhost:8000/api/documentation

## Contribution
Contributions are welcome! Please open issues or submit pull requests for improvements or new features.

## License
This project is licensed under the MIT License.
