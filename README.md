# Kerala State Rifle Association MIS

A secure, role-based Management Information System for the Kerala State Rifle Association (KSRA) built with PHP, MySQL, and Tailwind CSS.

## Features

- **Role Hierarchy:** Super Admin, State Admin, District Admin, Institution Admin, Club Admin, and Member roles with tailored dashboards.
- **Organization Management:** Manage KSRA, District Rifle Associations (DRAs), Affiliated Institutions (AIs), and Clubs with hashed URLs.
- **Membership Workflow:** Self-service member registration, OTP verification stubs, multi-organization membership linking, and admin approval flow.
- **Finance Module:** Financial year management, income/expense heads, and ledger entries per organization.
- **Election Publishing:** Record election events and publicly display elected representatives.
- **Security:** CSRF protection, hashed identifiers in URLs, prepared statements, password hashing, and secure session settings.

## Technology Stack

- PHP >= 8.2
- MySQL 8+
- Tailwind CSS (via CDN or compiled assets)
- Composer for autoloading and environment management

## Project Structure

```
app/
  Controllers/        # MVC controllers for authentication, dashboard, organizations, etc.
  Core/               # Framework utilities (router, auth, CSRF, hashing, validation)
  Models/             # Database models using PDO prepared statements
  Views/              # Tailwind-powered Blade-like PHP templates
config/               # Application and database configuration loaders
public/               # Web root with index.php front controller
resources/css/        # Tailwind entry point (optional compilation)
database/migrations/  # SQL migrations for schema setup
database/seeds/       # Seeder scripts for initial data
```

## Getting Started

1. **Install PHP dependencies**
   ```bash
   composer install
   ```

2. **Create environment file**
   ```bash
   cp .env.example .env
   ```

3. **Generate application key and hash salt**
   - Update `APP_KEY` and `HASH_SALT` with secure random strings.

4. **Configure database**
   - Update `.env` with MySQL credentials.
   - Run the SQL in `database/migrations/2024_01_01_000000_create_tables.sql`.

5. **Seed the database (optional)**
   ```bash
   php database/seeds/DatabaseSeeder.php
   ```

6. **Serve the application**
   ```bash
   php -S localhost:8000 -t public
   ```

7. **Login**
   - Seeded credentials: `superadmin@ksra.org` / `ChangeMe123!`

## Security Considerations

- All URLs expose hashed identifiers generated via HMAC + Base64 to avoid leaking sequential IDs.
- CSRF tokens required for all POST actions.
- Passwords hashed using PHP's default (bcrypt/argon2i depending on availability).
- Sessions configured with HttpOnly, Secure, and SameSite protections.

## Tailwind Styling

The sample views use Tailwind CSS via CDN for rapid prototyping. For production, compile `resources/css/app.css` using a Tailwind build pipeline and include the generated stylesheet in `layouts/dashboard.php` and other templates.

## Extending the MIS

- Implement email OTP delivery using your preferred mail provider and the `otps` table.
- Expand role-based authorization checks in controllers before performing CRUD actions.
- Attach file uploads for logos, representative photos, and governance documents.
- Add audit logs and activity history per entity for compliance.

## License

This scaffold is provided for KSRA internal development use.
