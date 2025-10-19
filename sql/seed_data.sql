

INSERT INTO roles (name, description, created_at) VALUES
                                                      ('admin', 'Administrator', NOW()),
                                                      ('doctor', 'Doctor', NOW()),
                                                      ('nurse', 'Nurse', NOW());

INSERT INTO users (role_id, email, password_hash, full_name, phone, is_active, created_at, updated_at) VALUES
                                                                                                           (1, 'admin@lab.local
', '123456', 'Lab Admin', '+212600000001', 1, NOW(), NOW()),
                                                                                                           (2, 'doc.alice@lab.local
', '123456', 'Dr. Alice', '+212600000002', 1, NOW(), NOW()),
                                                                                                           (3, 'nurse.bob@lab.local
', '123456', 'Nurse Bob', '+212600000003', 1, NOW(), NOW());

INSERT INTO patients (medical_record_number, first_name, last_name, dob, gender, phone, email, address, emergency_contact_name, emergency_contact_phone, created_at, updated_at) VALUES
                                                                                                                                                                                     ('MRN0001','John','Doe','1980-01-01','male','+212610000001','john.doe@example.local
','123 Main St, City','Jane Doe','+212610000009', NOW(), NOW()),
                                                                                                                                                                                     ('MRN0002','Jane','Smith','1990-05-05','female','+212610000002','jane.smith@example.local
','45 Oak Ave, City','John Smith','+212610000010', NOW(), NOW()),
                                                                                                                                                                                     ('MRN0003','Sam','Brown','1975-07-20','male','+212610000003','sam.brown@example.local
','78 Pine Rd, City','Sara Brown','+212610000011', NOW(), NOW());

INSERT INTO appointments (patient_id, clinician_id, scheduled_at, status, reason, created_at, updated_at) VALUES
                                                                                                              (1, 2, '2025-09-01 09:00:00', 'completed', 'General consultation', NOW(), NOW()),
                                                                                                              (2, 2, '2025-09-02 11:30:00', 'completed', 'Blood test', NOW(), NOW()),
                                                                                                              (3, 3, '2025-09-03 14:00:00', 'scheduled', 'Follow-up', NOW(), NOW());

INSERT INTO medical_notes (patient_id, author_id, appointment_id, note_text, note_type, created_at, updated_at) VALUES
                                                                                                                    (1, 2, 1, 'Patient reports mild headache for 2 days. Vitals stable. Recommending paracetamol and rest.', 'consultation', NOW(), NOW()),
                                                                                                                    (2, 2, 2, 'Ordered CBC and lipid panel. Awaiting results.', 'order', NOW(), NOW()),
                                                                                                                    (3, 3, 3, 'Follow-up scheduled. Check wound healing and vitals.', 'followup', NOW(), NOW());

INSERT INTO lab_results (patient_id, ordered_by_id, performed_by_id, appointment_id, test_code, result_text, result_value, result_unit, status, recorded_at, created_at, updated_at) VALUES
                                                                                                                                                                                         (2, 2, 3, 2, 'CBC-001', 'CBC normal. WBC: 6.2, RBC: 4.7', 'WBC=6.2;RBC=4.7', '', 'completed', NOW(), NOW(), NOW()),
                                                                                                                                                                                         (1, 2, 3, 1, 'GLU-001', 'Fasting glucose 95 mg/dL', '95', 'mg/dL', 'completed', NOW(), NOW(), NOW());

INSERT INTO billing (patient_id, appointment_id, amount, currency, status, description, created_at, updated_at) VALUES
                                                                                                                    (1, 1, 150.00, 'USD', 'pending', 'Consultation fee', NOW(), NOW()),
                                                                                                                    (2, 2, 250.00, 'USD', 'paid', 'Blood test and lab fees', NOW(), NOW()),
                                                                                                                    (3, 3, 75.00, 'USD', 'pending', 'Follow-up visit', NOW(), NOW());

INSERT INTO files (patient_id, uploaded_by_id, appointment_id, filename, mime_type, file_size, storage_path, checksum, created_at) VALUES
                                                                                                                                       (1, 2, 1, 'xray_john_doe_20250901.pdf', 'application/pdf', 245678, '/storage/patient_1/xray_john_doe_20250901.pdf', 'sha256:examplechecksum1', NOW()),
                                                                                                                                       (2, 3, 2, 'cbc_result_jane_20250902.pdf', 'application/pdf', 123456, '/storage/patient_2/cbc_result_jane_20250902.pdf', 'sha256:examplechecksum2', NOW());

INSERT INTO audit_logs (user_id, patient_id, action, object_type, object_id, details, ip_address, user_agent, created_at) VALUES
                                                                                                                              (2, 1, 'view_patient', 'patient', '1', JSON_OBJECT('note','Viewed patient record from web UI'), '127.0.0.1', 'Mozilla/5.0 (lab)', NOW()),
                                                                                                                              (2, 2, 'view_billing', 'billing', '2', JSON_OBJECT('note','Viewed billing record for patient 2'), '127.0.0.1', 'Mozilla/5.0 (lab)', NOW());

INSERT INTO sessions (user_id, session_token, ip_address, user_agent, created_at, expires_at, revoked) VALUES
                                                                                                           (2, 'sessiontoken_example_doc_alice_1', '127.0.0.1', 'Mozilla/5.0 (lab)', NOW(), DATE_ADD(NOW(), INTERVAL 2 HOUR), 0),
