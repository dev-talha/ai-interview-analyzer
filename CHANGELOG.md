# AI Interview Analyzer - Changelog

All notable changes to this project will be documented in this file.

## [Unreleased / Today] - 2026-04-21

### Added
- **Speech-To-Text (STT) Integration:** Introduced the ability for users to record audio answers seamlessly.
- **Dual STT Approaches:** 
  - **Approach A (Browser Native):** Completely free, client-side real-time transcription using the browser's built-in `webkitSpeechRecognition`.
  - **Approach B (Groq Cloud API):** Lightning-fast backend processing utilizing Groq's APIs running the `whisper-large-v3` model.
- **STT Admin Configurations:** Added a new "Speech-To-Text (STT) Settings" section in the Admin Dashboard (`Admin > Settings`).
  - Added toggles to switch globally between Approach A and Approach B.
  - Added secure input fields for managing the `groq_api_key` and `groq_base_url`.
- **Audio Processing Pipeline:** Deployed a new backend REST endpoint (`POST /interview/transcribe`) inside `InterviewController.php` to accept audio `webm` blobs from the frontend, stream them to Groq via `cURL`, and return transcribed text asynchronously.
- **Dynamic Frontend Recording:** Embedded an interactive "🎤 Record" floating button inside question textareas, bound to Javascript `MediaRecorder` logic representing active states and process loaders.

---

## [Initial Phase Updates] - Up to 2026-04-20

### Added
- **Ollama Integration Modes:** Integrated support for both Local Ollama architecture and Remote Ollama Cloud API.
- **Admin AI Settings Panel:** Built a dashboard configuration to modify Connection Modes, Base URLs, Timeouts, and Temperatures.
- **System Prompt Management:** Gave admins complete control over the rigid AI system prompt evaluating candidates via the settings file.
- **AJAX Model Fetching:** Created a single-click "Fetch Models" feature scanning active Ollama APIs and outputting clickable model payloads.
- **Pagination and Search System:** Hardcoded raw SQL query `LIMIT`, `OFFSET`, and `LIKE` architectures to introduce pagination and dynamic search to `Admin > Users` and `Admin > Reports`.
- **Data Deletion Security:** Attached conditional form submissions with Javascript confirmation prompts allowing Admins to delete Users and Reports safely.

### Changed
- **Optimized Question Queries:** Updated logic across `Question::byCategory` to pull exactly `5` randomized questions per test session instead of static dumping `ORDER BY RAND() LIMIT 5`.
- **Login Experience:** Altered UI behaviors dynamically hiding "Create Account/Sign In" routing for already authenticated sessions replacing links pointing directly to the `/dashboard`.
- **Dashboard Shortcuts:** Adjusted the "Practice Now" buttons to memorize the `category_id` parameter, instantly pre-selecting the relevant context dropdown in the `interview/start` phase.

### Improved
- **AI Processing Loading Indicators:** Fixed duplicate API calls during `Submit for AI Analysis` by disabling interactions and attaching a Bootstrap spinner visually representing logic wait-times.
- **Strict Evaluator Fallback:** Configured `OllamaService` to gracefully parse non-JSON generated models or prompt hallucinations by calculating algorithmic text-weight comparisons to deter application crashing.
