# Courses Manager Demo

A simple Laravel application to manage courses, instructors, lessons, ratings, and user interactions.  
This demo includes APIs for courses and instructors with features like pagination, average course ratings, and CRUD operations.

---

## Technologies Used

- **Laravel 12**  
- **PHP 8+**  
- **SQLite / MySQL / PostgreSQL** (configurable in .env)  
- **Composer** for dependency management  
- **PHPUnit** for testing  

---

## Installation

1. Clone the repository

```bash
git clone https://github.com/ftorrens/courses_manager.git
cd courses_manager
```

2. Install PHP dependencies

```bash
composer update
```

3. Create .env file

```bash
cp .env.example .env
```

4. Create .env file

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations and seed the database

```bash
php artisan migrate --seed
```

5. Start the development server

```bash
php artisan serve
```

The API will be accessible at http://127.0.0.1:8000

## Running Tests

```bash
php artisan test
```
This will run all the PHPUnit tests located in tests/Feature and tests/Unit.

## API Endpoints (Demo)


GET /api/courses → List courses (supports pagination, sorting, and filtering)

GET /api/courses/{id} → Show single course with instructor and average rating

POST /api/courses → Create a course

PUT /api/courses/{id} → Update a course

DELETE /api/courses/{id} → Delete a course

GET /api/courses/instructors → List instructors (paginated, optimized for large datasets)

## Note

For production, consider moving the instructors endpoint to a dedicated InstructorController to follow RESTful conventions.

Average course rating is calculated via a dedicated CourseRatingService to separate business logic from controllers.

---

Happy Coding! 😉