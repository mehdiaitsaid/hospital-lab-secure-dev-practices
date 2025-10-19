CREATE DATABASE IF NOT EXISTS hospital_emr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hospital_emr;

CREATE TABLE roles (
                       id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                       name VARCHAR(50) NOT NULL UNIQUE,
                       description VARCHAR(255),
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
                       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                       role_id INT UNSIGNED,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       password_hash VARCHAR(255) NOT NULL,
                       full_name VARCHAR(255) NOT NULL,
                       phone VARCHAR(50),
                       is_active TINYINT(1) DEFAULT 1,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                       FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE patients (
                          id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                          medical_record_number VARCHAR(100) NOT NULL UNIQUE,
                          first_name VARCHAR(150) NOT NULL,
                          last_name VARCHAR(150) NOT NULL,
                          dob DATE,
                          gender ENUM('male','female','other') DEFAULT 'other',
                          phone VARCHAR(50),
                          email VARCHAR(255),
                          address TEXT,
                          emergency_contact_name VARCHAR(255),
                          emergency_contact_phone VARCHAR(50),
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE appointments (
                              id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                              patient_id BIGINT UNSIGNED NOT NULL,
                              clinician_id BIGINT UNSIGNED,
                              scheduled_at DATETIME NOT NULL,
                              status ENUM('scheduled','cancelled','completed','no_show') DEFAULT 'scheduled',
                              reason VARCHAR(255),
                              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                              updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                              FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
                              FOREIGN KEY (clinician_id) REFERENCES users(id) ON DELETE SET NULL,
                              INDEX (patient_id),
                              INDEX (clinician_id),
                              INDEX (scheduled_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE medical_notes (
                               id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                               patient_id BIGINT UNSIGNED NOT NULL,
                               author_id BIGINT UNSIGNED NOT NULL,
                               appointment_id BIGINT UNSIGNED,
                               note_text MEDIUMTEXT NOT NULL,
                               note_type VARCHAR(100),
                               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                               FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
                               FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
                               FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
                               FULLTEXT KEY ft_note_text (note_text(1000))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE lab_results (
                             id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                             patient_id BIGINT UNSIGNED NOT NULL,
                             ordered_by_id BIGINT UNSIGNED,
                             performed_by_id BIGINT UNSIGNED,
                             appointment_id BIGINT UNSIGNED,
                             test_code VARCHAR(100) NOT NULL,
                             result_text MEDIUMTEXT,
                             result_value VARCHAR(255),
                             result_unit VARCHAR(50),
                             status ENUM('ordered','in_progress','completed','amended','cancelled') DEFAULT 'ordered',
                             recorded_at TIMESTAMP NULL,
                             created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                             FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
                             FOREIGN KEY (ordered_by_id) REFERENCES users(id) ON DELETE SET NULL,
                             FOREIGN KEY (performed_by_id) REFERENCES users(id) ON DELETE SET NULL,
                             FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
                             INDEX (patient_id),
                             INDEX (test_code),
                             INDEX (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE billing (
                         id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                         patient_id BIGINT UNSIGNED NOT NULL,
                         appointment_id BIGINT UNSIGNED,
                         amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                         currency VARCHAR(10) DEFAULT 'USD',
                         status ENUM('pending','paid','cancelled','adjusted') DEFAULT 'pending',
                         description VARCHAR(512),
                         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                         updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                         FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
                         FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
                         INDEX (patient_id),
                         INDEX (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE files (
                       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                       patient_id BIGINT UNSIGNED,
                       uploaded_by_id BIGINT UNSIGNED,
                       appointment_id BIGINT UNSIGNED,
                       filename VARCHAR(512) NOT NULL,
                       mime_type VARCHAR(255),
                       file_size BIGINT UNSIGNED,
                       storage_path VARCHAR(1024),
                       checksum VARCHAR(128),
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL,
                       FOREIGN KEY (uploaded_by_id) REFERENCES users(id) ON DELETE SET NULL,
                       FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
                       INDEX (patient_id),
                       INDEX (uploaded_by_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE audit_logs (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            user_id BIGINT UNSIGNED,
                            patient_id BIGINT UNSIGNED,
                            action VARCHAR(100) NOT NULL,
                            object_type VARCHAR(100),
                            object_id VARCHAR(255),
                            details JSON,
                            ip_address VARCHAR(45),
                            user_agent VARCHAR(512),
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                            FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL,
                            INDEX (user_id),
                            INDEX (patient_id),
                            INDEX (action),
                            INDEX (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sessions (
                          id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                          user_id BIGINT UNSIGNED NOT NULL,
                          session_token CHAR(128) NOT NULL UNIQUE,
                          ip_address VARCHAR(45),
                          user_agent VARCHAR(512),
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          expires_at TIMESTAMP NULL,
                          revoked TINYINT(1) DEFAULT 0,
                          FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                          INDEX (user_id),
                          INDEX (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


                                                                                                           (1, 'sessiontoken_example_admin_1', '127.0.0.1', 'Mozilla/5.0 (lab)', NOW(), DATE_ADD(NOW(), INTERVAL 2 HOUR), 0);