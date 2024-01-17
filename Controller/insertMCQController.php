<?php
include 'databaseConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseCode = $_POST["course_code"];
    $quizNumber = $_POST["quizNumber"]; 
    $question1 = $_POST["question"];
    $optionA1 = $_POST["optionA1"];
    $optionB1 = $_POST["optionB1"];
    $optionC1 = $_POST["optionC1"];
    $optionD1 = $_POST["optionD1"];
    $optionE1 = $_POST["optionE1"];
    $answer1 = $_POST["answerQ"];

    // Insert data into your database table
    $insertQuery = "INSERT INTO quiz_questions (course_code, quizNumber, question_text, option_a, option_b, option_c, option_d, option_e, correct_answer) 
                    VALUES ('$courseCode', '$quizNumber', '$question1', '$optionA1', '$optionB1', '$optionC1', '$optionD1', '$optionE1', '$answer1')";

    $insertResult = pg_query($conn, $insertQuery);

    if ($insertResult) {
        echo "Data inserted successfully!";
    } else {
        echo "Error inserting data: " . pg_last_error($conn);
    }
}
?>
