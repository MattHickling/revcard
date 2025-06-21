# RevCard

RevCard is a Laravel-based web application that allows students to generate and answer AI-powered exam questions based on their selected school subject, topic, exam board, and desired number of questions. Teachers can monitor the performance of their students through a dedicated dashboard.

---

## ✨ Key Features

### 🎓 For Students
- Select **subject**, **topic**, **exam board**, and **number of questions**.
- Instantly generate **AI-based exam-style questions**.
- Answer and submit questions directly in the platform.
- Review previous attempts and results.

### 🧑‍🏫 For Teachers
- View results for each student, including:
  - Average scores.
  - Topic-level performance.
  - Recently attempted quizzes.
  - Detailed breakdowns of answers.
- Leave comments and feedback on student performance.

### 🔐 For Admins
- Invite users to register via email using tokenized links.
- Manage users and school associations.
- View and manage school and subject data.

---

## 🧠 How It Works

1. **Student selects preferences**:  
   - Subject  
   - Topic  
   - Exam board  
   - Number of questions

2. **AI generates questions** using natural language generation based on selected parameters.

3. **Student answers questions**, submits the quiz, and receives immediate feedback.

4. **Teachers can log in** to see performance metrics and provide feedback.

---

## 🧰 Tech Stack

- **Backend**: Laravel 10+, PHP 8.1+
- **Frontend**: Blade templates, Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **AI Integration**: Uses an AI model (e.g., GPT) to generate exam questions
- **Authentication**: Laravel Breeze / Jetstream
- **Mail**: SMTP (via Laravel Mail)
- **Roles**: Admin, Teacher, Student

---

## 🚀 Getting Started

## 1. Clone the Project
git clone https://github.com/your-username/revcard.git
cd revcard

## 2. Install Dependencies
composer install
npm install


## 3. Configure Environment
cp .env.example .env
php artisan key:generate

## 4. Update .env with your DB and mail settings:
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your_mail_user
MAIL_PASSWORD=your_mail_pass

## 5. Run Migrations & Seeders
php artisan migrate --seed

## 6. Compile Frontend
npm run dev

## 7. Serve the App
php artisan serve

## 📄 License
This project is open-source and available under the MIT license.