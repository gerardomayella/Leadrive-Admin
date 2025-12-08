# Leadrive Admin Dashboard

## 📖 About
Admin Dashboard system for the Leadrive Application. Built with modern web technologies to ensure performance, scalability, and a premium user experience.

## 👥 Authors
| Nama | NIM |
|------|-----|
| **Angelo Owada Togovio** | 235314053 |
| **Samuel Santoso** | 235314011 |

## 🛠️ Tech Stack
This project utilizes the latest robust technologies:
* **Framework**: [Laravel 12](https://laravel.com)
* **Styling**: [TailwindCSS 4](https://tailwindcss.com)
* **Bundler**: [Vite](https://vitejs.dev)
* **Language**: PHP 8.2+

## 🚀 Getting Started

Follow these steps to set up the project locally.

### Prerequisites
Ensure you have the following installed:
* [PHP 8.2](https://www.php.net/) or higher
* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/) & NPM

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/gerardomayella/Leadrive-Admin.git
   cd Leadrive-Admin
   ```

2. **Navigate to the application directory**
   The main application logic resides in the `LeaDrive-Application-Admin/Application` directory.
   ```bash
   cd LeaDrive-Application-Admin/Application
   ```

3. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

4. **Environment Setup**
   Copy the example environment file and generate the application key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   Configure your database details in the `.env` file, then run the migrations:
   ```bash
   php artisan migrate
   ```

## 💻 Running the Application

You can start the development server using the following command:

```bash
composer run dev
```

Alternatively, you can run the services individually in separate terminals:

```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite Development Server
npm run dev

# Terminal 3: Queue Worker (Optional)
php artisan queue:listen
```

## 📄 License
[MIT](LeaDrive-Application-Admin/Application/LICENSE)
