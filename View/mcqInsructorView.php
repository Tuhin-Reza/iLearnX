<?php
include '../Controller/fetch_courses.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link rel="stylesheet" href="./style/mcqInsructor.css">
</head>

<body>
    <div class="main-box">
        <form action="../Controller/insertMCQController.php" method="post">
            <h1 class="heading">Insert Quiz Question</h1>
            <div class="input-box">
                <div class="dropdown-area">
                    <label for="courseName">Course Name:</label></br>
                    <select id="course_code" name="course_code" class="drop_down">
                        <?php
                        if (isset($courses) && !empty($courses)) {
                            echo "<option value='' hidden class='selectdd'>Select a course</option>";

                            foreach ($courses as $course) {
                                echo "<option value='" . $course['course_code'] . "' style='background-color: dimgray; color: #f1f1f1;   onmouseover=\"this.style.backgroundColor='#555'; this.style.color='#fff';\"'>"
                                    . $course['course_name'] . "</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No courses found</option>";
                        }
                        ?>

                    </select>
                </div></br>
                <div class="input-area">
                    <label for="quizNumber">Select Quiz Number:</label></br>
                    <select id="quizNumber" name="quizNumber" class="drop_down">
                        <?php
                        for ($i = 1; $i <= 10; $i++) {
                            echo "<option value='Quiz-$i'>Quiz-$i</option>";
                        }
                        ?>
                    </select>
                </div></br>

                <div class="input-area">
                    <label for="question1">Question:</label></br>
                    <textarea id="question" name="question" class="textarea"></textarea></br>
                </div>

                <div class="input-area">
                    <label for="optionA1">Option A:</label></br>
                    <input type="text" id="optionA1" name="optionA1" class="input"></br>
                </div>
                <div class="input-area">
                    <label for="optionB1">Option B:</label></br>
                    <input type="text" id="optionB1" name="optionB1" class="input"></br>
                </div>
                <div class="input-area">
                    <label for="optionC1">Option C:</label></br>
                    <input type="text" id="optionC1" name="optionC1" class="input"></br>
                </div>
                <div class="input-area">
                    <label for="optionD1">Option D:</label></br>
                    <input type="text" id="optionD1" name="optionD1" class="input"></br>
                </div>
                <div class="input-area">
                    <label for="optionE1">Option E:</label></br>
                    <input type="text" id="optionE1" name="optionE1" class="input"></br>
                </div>
                <div class="input-area">
                    <label for="answer">Answer for Question:</label></br>
                    <input type="text" id="answerQ" name="answerQ" class="input"></br>
                </div>
                <input class="submit" type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>

</html>