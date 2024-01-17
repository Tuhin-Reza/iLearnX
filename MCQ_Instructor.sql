CREATE TABLE quiz_questions (
    id SERIAL PRIMARY KEY,
    course_code VARCHAR(255) NOT NULL,
    quizNumber VARCHAR(10) NOT NULL, -- Assuming quiz numbers are alphanumeric like "Quiz-1"
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    option_e VARCHAR(255) NOT NULL,
    correct_answer VARCHAR(255) NOT NULL
);


select * from quiz_questions
