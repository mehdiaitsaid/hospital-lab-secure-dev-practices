# Hospital EMR — Practice Project (Insecure Starter)

**Purpose**  
This repository is an educational PHP-based Electronic Medical Records (EMR) web application designed for hands-on practice. It intentionally contains common anti-patterns and security weaknesses so learners can study, identify, and fix them. The primary goals are to teach **software engineering best practices** (clean architecture, design patterns, modularity, testing) and to provide realistic exercises in **application security** and secure coding.

> ⚠️ **IMPORTANT — LAB USE ONLY**  
> This project is a deliberately insecure starter. Run it only in a controlled, isolated environment (local VM, isolated Docker network, or air-gapped machine). Do **not** deploy this code to production or expose it to the public internet. Use only synthetic test data.

---

## Key learning areas

- Modular PHP application structure and simple MVC-like organization
- Common design patterns and where to apply them in PHP
- Secure coding practices: input validation, prepared statements, secure session handling
- Configuration and secret management (identify and move insecure patterns to secure storage)
- Testing basics and how to validate fixes

---

## Repository layout

```bash
hospital_emr/
├── index.php
├── config.php
├── db_connect.php
├── login.php
├── logout.php
├── dashboard.php
├── patients.php
├── patient_details.php
├── appointments.php
├── billing.php
├── upload.php
├── download.php
├── assets/ (css, js, img)
├── includes/ (header, footer, navbar, functions)
├── models/ (User.php, Patient.php, ...)
├── keys/secret_key.txt
├── sql/ (schema + seed data)
└── README.md
```


---

## Features (demo)

- Simple login and session handling
- Patient list and detailed patient view
- Appointment management
- Lab results and medical notes viewing
- Billing records and basic file upload/download
- Simple, readable code intended for modification and improvement

---

## Quick start (instructor / local run)

1. Prepare an isolated environment (VM or Docker) with PHP and MySQL/MariaDB.
2. Create a database and import `sql/hospital_emr.sql` and `sql/seed_data.sql`.
3. Update `config.php` to point to the local database (the provided config is intentionally basic).
4. Serve the application over HTTP on the lab network (TLS can be enabled later as an exercise).
5. Use a browser to access the app (e.g., `http://localhost:<port>`).

> Note: The starter config and code are intentionally minimal and contain insecure patterns for teaching purposes. Review and secure them before exposing beyond a lab environment.

---

