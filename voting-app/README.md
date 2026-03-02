
🗳️ Secure Face Recognition Voting System (Laravel 11)



An advanced, biometric-based electronic voting web application built with Laravel 11 and Face-api.js. This system ensures "One Person, One Vote" by verifying the voter's identity through real-time facial recognition.

🚀 Key Features
Biometric Enrollment: Users register with their CNIC and capture their face unique 128-bit descriptor.

Real-time Recognition: Instant face matching using Euclidean distance (Threshold < 0.6).

Liveness Detection: Anti-spoofing logic to prevent photos/videos from being used as fake IDs.

Secure Voting: Once identity is verified, the 'Cast Vote' panel is unlocked.

Double-Voting Prevention: Database-level flags (has_voted) to ensure integrity.

Fully Responsive: Works on desktop and mobile (with HTTPS).

🛠️ Tech Stack
Backend: Laravel 11 (PHP 8.2+)

Frontend: Blade Templates, Tailwind CSS

AI Engine: Face-api.js (Based on TensorFlow.js)

Database: MySQL / PostgreSQL

Authentication: Laravel Breeze / Fortify

📂 Project Structure (Phases)
Phase	Description
Phase 1	Environment setup, Database Migrations, & Model weights download.
Phase 2	Face Enrollment (Capturing 128-bit descriptors during registration).
Phase 3	Identity Matching & Liveness Detection logic.
Phase 4	Voting Logic, Candidate management, and Middleware security.
Phase 5	Production deployment (SSL/HTTPS setup).
