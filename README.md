# Laravel QR Code Generator Project

## Installation Guide

Follow these steps to set up and run the project:

### 1. Clone the Repository
```sh
git clone https://github.com/sobir-swe/QR-Code-Generate.git
cd QR-Code-Generate
```

### 2. Install Dependencies
```sh
composer install
```

### 3. Create and Configure the Database
1. Create a new MySQL database:
   ```sql
   CREATE DATABASE your_database_name;
   ```
2. Copy the `.env.example` file and rename it to `.env`:
   ```sh
   cp .env.example .env
   ```
3. Update the `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```
4. Clear configuration cache:
   ```sh
   php artisan config:clear
   php artisan cache:clear
   ```

### 4. Generate Application Key
```sh
php artisan key:generate
```

### 5. Run Migrations and Seed Database (if necessary)
```sh
php artisan migrate --seed
```

### 6. Install Required Packages
Ensure you have the following package installed for QR Code generation:
```sh
composer require simplesoftwareio/simple-qrcode
composer require khanamiryan/qrcode-detector-decoder

```

### 7. Start the Development Server
```sh
php artisan serve
```
Now, you can access the project at `http://127.0.0.1:8000/`.

---

## Additional Notes
- Ensure that MySQL is running before migrating the database.
- If you need to reset the database, use:
  ```sh
  php artisan migrate:refresh --seed
  ```
- Use `php artisan tinker` to interact with the database directly.
- If you face any issues, clear the application cache:
  ```sh
  php artisan config:clear && php artisan cache:clear
  ```

Happy coding! 🚀

