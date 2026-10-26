# Laravel Solar Service Website Template

A reusable Laravel 13 template for company websites (solar/energy/service businesses) with:

- Frontend public website (Blade + Bootstrap 5 + AOS animations)
- Admin panel with authentication and dynamic content management
- SEO fields, slug-based URLs, image uploads, pagination, and seed data

## Tech Stack

- Laravel 13
- Blade templating engine
- Bootstrap 5
- CKEditor 5 (CDN) for blog editor
- SQLite (default) or MySQL via `.env`

## Included Modules

- Homepage sections (hero, intro, featured services/products, CTA)
- About page content (mission, vision, history)
- Products CRUD + frontend listing/details
- Services CRUD + frontend listing/details
- Team CRUD + frontend listing
- Blog CRUD + frontend listing/details
- Gallery CRUD (photo/video) + frontend photo/video pages
- Contact page + submission storage
- Settings module (logo, favicon, SEO meta, social links)
- Admin password change

## Admin Credentials

- Email: `admin@admin.com`
- Password: `12345678`

Change the password from: `Admin Panel -> Change Password`

## Installation & Run

1. Install dependencies:
    ```bash
    composer install
    ```
2. Copy env and generate key (if needed):
    ```bash
    copy .env.example .env
    php artisan key:generate
    ```
3. Configure database in `.env`.

    Default template setup uses SQLite:

    ```env
    DB_CONNECTION=sqlite
    DB_DATABASE=database/database.sqlite
    ```

4. Create SQLite file (if missing):
    ```bash
    type nul > database\database.sqlite
    ```
5. Link storage and run migrations with seed:
    ```bash
    php artisan storage:link
    php artisan migrate:fresh --seed
    ```
6. Serve application:
    ```bash
    php artisan serve
    ```

## Route Groups

- Frontend routes: public pages in `routes/web.php`
- Admin routes: prefixed with `/admin`, protected by `auth` + `admin` middleware

## Reusability Notes

- Shared frontend layout: `resources/views/frontend/layouts/app.blade.php`
- Shared admin layout: `resources/views/admin/layouts/app.blade.php`
- Modular CRUD views and form partials for each entity
- Most textual site sections are managed from `Settings` and `Pages Content`

## Storage

Uploaded files are stored in:

- `storage/app/public/products`
- `storage/app/public/services`
- `storage/app/public/blogs`
- `storage/app/public/team`
- `storage/app/public/galleries`
- `storage/app/public/settings`

## Optional Enhancements

- Add dark mode switch (frontend/admin)
- Add localization files for multi-language support
- Add API resources/controllers for headless usage
- Add testimonial module
