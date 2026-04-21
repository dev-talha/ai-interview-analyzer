# AI Interview Analyzer (Dockerized Platform) 🤖🚀

The **AI Interview Analyzer** is a modern, fully-responsive mock interview platform. Built with a monolithic Custom MVC architecture in raw PHP, this highly-customizable system natively utilizes **Ollama LLMs (Local & Cloud)** to evaluate candidate responses, providing deep scorecard insights such as professionalism, clarity, confidence, relevance, and constructive feedback.

## ✨ Highlight & Features
- **Intelligent Interview Sessions:** Randomized sets of questions (5 per session) evaluated seamlessly by an integrated AI.
- **Dual Connection Modes:** Effortlessly switch between a **Local Ollama** architecture or the **Ollama Cloud API** directly from the UI.
- **Advanced Speech-To-Text (STT) Pipelines:** Allow candidates to answer verbally.
  - *Approach A:* Native, completely free browser-based `webkitSpeechRecognition`.
  - *Approach B:* High-speed backend audio processing using Open AI's Whisper V3 running entirely on the **Groq API**.
- **Dynamic System Prompts:** Admins can enforce custom evaluating algorithms, fallback triggers, and formatting structures securely from the dashboard.
- **Role-based Auth:** Dedicated dashboard experiences for regular Users (history, test-taking, profile) vs Administrators (global logs, user deleting, settings).
- **Search & Pagination:** Smooth SQL offsetting enables paginated tables handling large datasets and instant keyword searching across reports and user lists.
- **Robust Fallback Engine:** Algorithmic parser safely generates basic performance scores instantly if the AI hallucinates, preventing the application from crashing.

## 🐳 Docker Hub Quick Start
You do not need to clone the source code to deploy this robust application. It is officially published to Docker Hub (`alpha2001/interview-analyzer:latest`).

### 1. `docker-compose.yml` Blueprint
Create a `docker-compose.yml` file anywhere on your machine and paste the following:
```yaml
services:
  app:
    image: alpha2001/interview-analyzer:2.0
    container_name: interview_app
    ports:
      - "8090:80"
    depends_on:
      - db

  db:
    image: mysql:8.0
    container_name: interview_db
    restart: always
    environment:
      MYSQL_DATABASE: interview_analyzer
      MYSQL_USER: appuser
      MYSQL_PASSWORD: apppassword
      MYSQL_ROOT_PASSWORD: rootpassword
    ports:
      - "3307:3306"
    volumes:
      - db_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: interview_phpmyadmin
    restart: always
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
    ports:
      - "8091:80"

volumes:
  db_data:
```

### 2. Run the Stack
Start your Docker engine and run the service via your terminal:
```bash
docker compose up -d
```
*Note: Since the database volume initializes raw, if the db tables do not auto-import on fresh clones, manually import the `database/migrations/001_init.sql` structure by accessing the phpMyAdmin panel.*

### 3. Application Access
- **Main Application:** `http://localhost:8090`
- **phpMyAdmin:** `http://localhost:8091`

## 🔐 Default Admin Credentials
- **Email:** `admin@gmail.com`
- **Password:** `admin123`

## ⚙️ How to Connect Your AI
Log in to the Admin Dashboard and navigate to the **Settings** view.

1. **Local Ollama Integration:** 
   - Start Ollama on your host machine: `ollama run llama3.2:3b`.
   - In Settings, paste `http://host.docker.internal:11434` into the Base URL. Click 'Fetch Models' to sync automatically.
2. **Cloud AI Integration:** 
   - Switch the toggle from "Local" to "Cloud".
   - Inject your provider's endpoint structure and your private Bearer API key.
3. **Groq Speech-To-Text Mode:**
   - Under STT Configuration, select *Groq Cloud API*.
   - Input your raw API key (e.g., `gsk_XXX`) and click save. The app will utilize Whisper v3 instantly.

## 📁 Source File Structure
- `app/Controllers` - Route handlers mapping business logic.
- `app/Models` - Data queries running `PDO` bind variables.
- `app/Services` - Contains AI engines (`OllamaService`, `InterviewService`).
- `resources/views` - Componentized frontend using Vanilla JS + raw Bootstrap 5.
- `database/migrations` - Includes `001_init.sql` seed configurations.
- `public` - Core Entrypoint Router (`index.php`) wrapping HTTP requests securely.
