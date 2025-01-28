# Laravel News Aggregator

A Laravel-based project for aggregating news from multiple sources. This README provides instructions on how to set up, run, and test the project locally.

---

## Prerequisites

Ensure you have the following installed on your local machine:

- PHP (version 8.2 or higher)
- Composer
- MySQL or a supported database

---

## Local Setup Instructions

Follow these steps to set up the project locally:

1. **Clone the Repository**  
   Clone the project repository to your local machine.
   ```bash
   git clone <repository_url>
   cd <project_directory>
   ```

2. **Install Dependencies**  
    Install all required PHP dependencies using Composer.
    ```bash
    composer install
    ```

3. **Environment Setup**  
    Copy the example environment file and configure it.
    ```bash
    cp .env.example .env
    ```
    1. Configure your database settings in the `.env` file.
    2. Add API credentials for the supported news APIs (e.g., NEWSAPI_KEY, NYT_KEY, GUARDIAN_KEY).

4. **Generate Application Key**  
    Generate the application key for your Laravel app.
    ```bash
    php artisan key:generate
    ```
5. **Run Migrations**
    Run database migrations to set up the required tables.
    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6. **Start the Development Server**
    Start the Laravel development server.
    ```bash
    php artisan serve
    ```

7. **Start the Queue Listener**
    Start the queue listener to process background jobs.
    ```bash
    php artisan queue:work
    ```

8. **Start the Scheduler**
    Start the scheduler to run scheduled tasks.
    ```bash
    php artisan schedule:work
    ```

9. **Postman Collection**
    Import the Postman collection for API testing.


