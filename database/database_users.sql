-- Add users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add user_id column to driving_experiences table (if not exists)
-- First check if column exists, if error occurs, ignore it
ALTER TABLE `driving_experiences` 
ADD COLUMN `user_id` int(11) DEFAULT NULL AFTER `id`;

-- Add index on user_id (ignore if exists)
ALTER TABLE `driving_experiences`
ADD KEY `idx_user_id` (`user_id`);

-- Add foreign key constraint with unique name
ALTER TABLE `driving_experiences`
ADD CONSTRAINT `fk_driving_exp_user` 
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
