# MMFC Library Management System

Laravel-based library management platform for MMFC. Handles book cataloging, patron registration, RFID attendance, student and employee ID card generation, and related library operations.

Repository: [github.com/borskenetic/mmfc](https://github.com/borskenetic/mmfc)

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MySQL / MariaDB

## Installation

1. Clone the repository:

```bash
git clone https://github.com/borskenetic/mmfc.git
cd mmfc
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install frontend dependencies and build assets:

```bash
npm install
npm run build
```

4. Configure environment:

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials and `APP_URL`.

5. Run migrations:

```bash
php artisan migrate
```

6. Link public storage (if needed):

```bash
php artisan storage:link
```

7. Start the development server:

```bash
php artisan serve
```

For frontend hot reload during development:

```bash
npm run dev
```

## Upload directories

User-uploaded files (profile pictures, signatures, formal photos) are stored outside version control. After cloning, ensure these folders exist and are writable:

- `images/formal_pictures`
- `images/profile_pictures`
- `images/signatures`
- `images/student_profiles`
- `images/student_signatures`
- `images/id_templates` (ID card templates — included in the repo)

## Main features

- Book and ebook management
- Patron (student) and employee registration with admin approval
- RFID attendance scanning and logs
- Student and employee ID card generation
- User accounts and role-based access

## Public registration routes

- Student registration: `/register`
- Employee registration: same page, Employee tab

## Security notes

- Never commit `.env` or uploaded user media to the repository.
- Use strong database credentials in production.
- Set `APP_DEBUG=false` in production.

## License

MIT
