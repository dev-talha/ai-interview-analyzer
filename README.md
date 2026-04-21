# AI-Based Interview Performance Analyzer

A production-minded academic project built with raw PHP, Bootstrap 5, MySQL, and Ollama integration.

## Highlights
- Responsive user-facing interview practice platform
- Dedicated admin panel for categories, questions, reports, users, and Ollama settings
- Admin-managed Ollama base URL, model, timeout, and temperature
- Dockerized stack with PHP-Apache, MySQL, and phpMyAdmin
- Clean monolithic project structure with controllers, models, services, and views

## Default Credentials
- Admin: `admin@gmail.com`
- Password: `admin123`

## Quick Start
1. Extract the zip.
2. Open the project folder.
3. Run:
   ```bash
   docker compose up --build
   ```
4. Open:
   - App: `http://localhost:8080`
   - phpMyAdmin: `http://localhost:8081`
5. Log in as admin and adjust Ollama settings from **Admin > AI Settings**.

## Ollama Notes
- The Docker setup already maps `host.docker.internal` so the PHP app can call an Ollama instance running on the host machine.
- Example host command:
  ```bash
  ollama serve
  ollama pull llama3.2:3b
  ```
- Then save these values in Admin > AI Settings:
  - Base URL: `http://host.docker.internal:11434`
  - Model: `llama3.2:3b`

## Structure
- `app/Controllers` - route handlers
- `app/Models` - data access
- `app/Services` - Ollama and interview evaluation logic
- `resources/views` - Bootstrap UI
- `database/migrations` - schema and seed data
- `public` - front controller and assets
- `docker-compose.yml` - local deployment stack

## Scope Coverage
This implementation covers the required user registration/login, categories, questions, interview sessions, AI evaluation storage, performance reports, history, admin controls, and Docker deployment.
