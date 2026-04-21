CREATE DATABASE IF NOT EXISTS interview_analyzer;
USE interview_analyzer;

CREATE TABLE IF NOT EXISTS users (
   id INT AUTO_INCREMENT PRIMARY KEY,
   full_name VARCHAR(100) NOT NULL,
   email VARCHAR(100) NOT NULL UNIQUE,
   password VARCHAR(255) NOT NULL,
   role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
   gender ENUM('male', 'female', 'other') DEFAULT NULL,
   phone VARCHAR(20) DEFAULT NULL,
   profile_image VARCHAR(255) DEFAULT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
   id INT AUTO_INCREMENT PRIMARY KEY,
   category_name VARCHAR(100) NOT NULL UNIQUE,
   description TEXT DEFAULT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS questions (
   id INT AUTO_INCREMENT PRIMARY KEY,
   category_id INT NOT NULL,
   question_text TEXT NOT NULL,
   difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'medium',
   status ENUM('active', 'inactive') DEFAULT 'active',
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS interview_sessions (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   category_id INT NOT NULL,
   total_questions INT NOT NULL DEFAULT 0,
   answered_questions INT NOT NULL DEFAULT 0,
   overall_score DECIMAL(5,2) DEFAULT 0.00,
   performance_level VARCHAR(50) DEFAULT NULL,
   final_feedback TEXT DEFAULT NULL,
   strengths TEXT DEFAULT NULL,
   weaknesses TEXT DEFAULT NULL,
   suggestions TEXT DEFAULT NULL,
   session_status ENUM('started', 'completed') DEFAULT 'started',
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
   FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS answers (
   id INT AUTO_INCREMENT PRIMARY KEY,
   session_id INT NOT NULL,
   question_id INT NOT NULL,
   answer_text TEXT NOT NULL,
   relevance_score DECIMAL(5,2) DEFAULT 0.00,
   clarity_score DECIMAL(5,2) DEFAULT 0.00,
   confidence_score DECIMAL(5,2) DEFAULT 0.00,
   professionalism_score DECIMAL(5,2) DEFAULT 0.00,
   total_score DECIMAL(5,2) DEFAULT 0.00,
   feedback TEXT DEFAULT NULL,
   suggestion TEXT DEFAULT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (session_id) REFERENCES interview_sessions(id) ON DELETE CASCADE,
   FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ai_logs (
   id INT AUTO_INCREMENT PRIMARY KEY,
   session_id INT NOT NULL,
   question_id INT DEFAULT NULL,
   prompt_text LONGTEXT NOT NULL,
   ai_response LONGTEXT NOT NULL,
   model_name VARCHAR(100) DEFAULT 'llama3.2:3b',
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (session_id) REFERENCES interview_sessions(id) ON DELETE CASCADE,
   FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS activity_logs (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   activity_type VARCHAR(100) NOT NULL,
   description TEXT DEFAULT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS settings (
   id INT AUTO_INCREMENT PRIMARY KEY,
   setting_key VARCHAR(100) NOT NULL UNIQUE,
   setting_value TEXT DEFAULT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (full_name, email, password, role)
VALUES ('Admin', 'admin@gmail.com', '$2y$12$8kW6Y3iwizxZISdFRYRKT.qPATQ3nTDzZqr1RStU.M.YNOmIy52/m', 'admin')
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO categories (category_name, description) VALUES
('HR Interview', 'Basic HR interview questions'),
('Software Developer Interview', 'Programming and technical interview questions'),
('Internship Interview', 'Interview questions for internship candidates')
ON DUPLICATE KEY UPDATE description = VALUES(description);

INSERT INTO questions (category_id, question_text, difficulty, status) VALUES
(1, 'Tell me about yourself.', 'easy', 'active'),
(1, 'Why do you want to work with our company?', 'medium', 'active'),
(1, 'What are your strengths and weaknesses?', 'medium', 'active'),
(2, 'What is the difference between GET and POST method in PHP?', 'easy', 'active'),
(2, 'Explain OOP concepts in programming.', 'medium', 'active'),
(2, 'What is database normalization?', 'hard', 'active'),
(3, 'Why should we select you for this internship?', 'medium', 'active'),
(3, 'What skills do you want to improve during this internship?', 'easy', 'active')
ON DUPLICATE KEY UPDATE question_text = VALUES(question_text);

INSERT INTO settings (setting_key, setting_value) VALUES
('ollama_enabled', 'true'),
('ollama_base_url', 'http://host.docker.internal:11434'),
('ollama_model', 'llama3.2:3b'),
('ollama_timeout', '60'),
('ollama_temperature', '0.2')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
