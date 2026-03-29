-- Database: manajemen_tugas
-- Create DB if not exists (run in MySQL: CREATE DATABASE IF NOT EXISTS manajemen_tugas; USE manajemen_tugas;)

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('siswa', 'karyawan') NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Kategori table (subjects/projects)
CREATE TABLE IF NOT EXISTS `kategori` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `color` VARCHAR(20) DEFAULT '#007bff',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tasks table (fixed spec: user_id and category_id)
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `category_id` INT,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `deadline` DATETIME NOT NULL,
  `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium',
  `status` ENUM('unfinished', 'finished') DEFAULT 'unfinished',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `kategori`(`id`)
);

-- Reminders table
CREATE TABLE IF NOT EXISTS `reminders` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `task_id` INT NOT NULL,
  `reminder_time` DATETIME NOT NULL,
  `status` ENUM('active', 'non-active') DEFAULT 'active',
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE
);

-- Sample data
INSERT INTO `kategori` (`name`, `color`) VALUES 
('Matematika', '#ff6b6b'), ('Bahasa Inggris', '#4ecdc4'), ('Project Alpha', '#45b7d1'), ('Project Beta', '#f9ca24');

-- Sample users (password: 'password123' hashed)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES 
('Siswa Test', 'siswa@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('Karyawan Test', 'karyawan@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'karyawan');

