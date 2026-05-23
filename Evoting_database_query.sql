-- =========================
-- USERS TABLE
-- =========================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unique_id VARCHAR(50) UNIQUE,
    name VARCHAR(100),
    cnic VARCHAR(20) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    has_voted BOOLEAN DEFAULT 0
);

-- =========================
-- ADMIN TABLE
-- =========================
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

INSERT INTO admin (username, password)
VALUES ('admin', '1234');

-- =========================
-- CANDIDATES TABLE
-- =========================
CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unique_id VARCHAR(50),
    name VARCHAR(100),
    age INT,
    party VARCHAR(100),
    city VARCHAR(100),
    picture VARCHAR(255),
    UNIQUE (unique_id)
);

-- =========================
-- VOTES TABLE
-- =========================
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    candidate_id INT
);

-- =========================
-- SETTINGS TABLE
-- =========================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    is_result_declared BOOLEAN DEFAULT 0,
    winner_id INT DEFAULT NULL,
    is_election_active BOOLEAN DEFAULT 0,
    start_time DATETIME,
    end_time DATETIME
);

-- Insert default settings row ONLY if not exists
INSERT INTO settings (id, is_result_declared, winner_id, is_election_active, start_time, end_time)
VALUES (1, 0, NULL, 0, NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY))
ON DUPLICATE KEY UPDATE id=id;
