# Blog Platform API

This project is a RESTful API for a blog platform built with Laravel. The API provides CRUD functionality for blog posts, user authentication, and role-based permissions. Users are either **Admins** or **Authors**, with different permissions for managing posts. Also use repository pattern for better for better code organization.

## Features

1. **User Authentication and Roles**
   - JWT-based user authentication.
   - Two user roles: **Admin** and **Author**.
   - Admins can manage all CRUD; authors can manage only their own CRUD.

2. **Blog Post Management**
   - Create, update, delete, and view blog posts.
   - Posts assigned to the logged-in author automatically.

3. **Comments System**
   - Authenticated users can comment on posts.

4. **Search and Filter**
   - Search posts by title, author, or category.
   - Filter posts by category, author, and date range.

5. **Caching and Pagination**
   - Cached list of blog posts for optimized loading.
   - Paginated results for list endpoints.

---

## Requirements

- PHP >= 8.1
- Composer
- Laravel 10
- SQLite 
- Postman 

## Installation

 1. Clone the Repository

```bash
git clone https://github.com/MedhatElbesy/DevoTrack.git
cd BlogPlatform

2. Install Dependencies

```bash
composer install

3. Configure Environment Variables

```bash
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
JWT_SECRET=<your_jwt_secret_key>

4. Generate Application and JWT Keys

```bash
php artisan key:generate
php artisan jwt:secret
```
## Unit and Feature Tests

```bash
php artisan test
```
### RESTful API
- **API Endpoints**: A set of RESTful API endpoints to manage all the features of the system.
here you can navigate the Api Postman documentation : https://documenter.getpostman.com/view/33682696/2sAXxMgtwS



