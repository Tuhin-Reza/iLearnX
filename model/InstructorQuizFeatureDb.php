<?php
include '../db/db_connection.php';

function fetchQuizzesFromDB($conn, $cid, $iid)
{
    $quizzes = [];
    $qz_query = "SELECT * FROM Quiz WHERE cid='C101' AND iid='I0' ORDER BY CAST(SUBSTRING(qzid FROM '[0-9]+') AS INTEGER) ASC";
    $result = pg_query($conn, $qz_query);

    if (!$result) {
        $error_message = pg_last_error($conn);
        echo "Database query error: $error_message";
        return false;
    }
    while ($row = pg_fetch_assoc($result)) {
        $quizzes[] = $row;
    }
    return $quizzes;
}

function getNextQuizNumber($conn, $cid, $iid)
{
    $existingQuizzes = [];
    $query = "SELECT qzid FROM Quiz WHERE cid = '$cid' AND iid = '$iid'";
    $result = pg_query($conn, $query);

    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $existingQuizzes[] = $row['qzid'];
        }
        if (!empty($existingQuizzes)) {
            $maxNumber = 1;
            foreach ($existingQuizzes as $quiz) {
                $number = (int) filter_var($quiz, FILTER_SANITIZE_NUMBER_INT);
                if ($number > $maxNumber) {
                    $maxNumber = $number;
                }
            }
            return "Quiz " . ($maxNumber + 1);
        } else {
            return "Quiz 1";
        }
    } else {
        return "Error fetching quiz number";
    }
}

function fetchQuestionsFromDB($conn, $qzId, $cid)
{
    $query = "SELECT * FROM Question WHERE qzid = '$qzId' AND cid = '$cid' ORDER BY quid ASC";
    $result = pg_query($conn, $query);

    $questions = [];

    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $questions[] = $row;
        }
        return $questions;
    } else {
        return ['error' => 'Error executing the database query'];
    }
}

function insertQuiz($conn, $qzid, $qztitle, $totalPoints, $cid, $iid)
{
    $query = "INSERT INTO quiz (qzid, qztitle, totpoint, cid, iid)
                      VALUES ('$qzid', '$qztitle', '$totalPoints', '$cid', '$iid')";
    $result = pg_query($conn, $query);
    if (!$result) {
        return ['error' => "Error: " . pg_last_error($conn)];
    }
    return true;
}

function insertQuestions($conn, $questions, $optionsA, $optionsB, $optionsC, $optionsD, $correctOptions, $points, $qzid, $cid)
{
    for ($i = 0; $i < count($questions); $i++) {
        $quid = "Q" . ($i + 1);
        $qutext = $questions[$i];
        $op_a = $optionsA[$i];
        $op_b = $optionsB[$i];
        $op_c = $optionsC[$i];
        $op_d = $optionsD[$i];
        $ans_op = $correctOptions[$i];
        $point = $points[$i];

        $query = "INSERT INTO question (quid, qutext, op_a, op_b, op_c, op_d, ans_op, point, qzid, cid)
                          VALUES ('$quid', '$qutext', '$op_a', '$op_b', '$op_c', '$op_d', '$ans_op', '$point', '$qzid', '$cid')";
        $result = pg_query($conn, $query);
        if (!$result) {
            return ['error' => "Error: " . pg_last_error($conn)];
        }
    }
    return true;
}

function fetchQuestionPoints($conn, $quid, $cid, $qzid)
{
    $getQuestionPointsQuery = "SELECT point FROM Question WHERE quid = '$quid' AND cid = '$cid' AND qzid = '$qzid'";
    $questionPointsResult = pg_query($conn, $getQuestionPointsQuery);
    if ($questionPointsResult) {
        $questionPointsRow = pg_fetch_assoc($questionPointsResult);
        return $questionPointsRow['point'];
    } else {
        return false;
    }
}

function deleteQuestion($conn, $quid, $cid, $qzid)
{
    $delete_query = "DELETE FROM question WHERE quid = '$quid' AND cid = '$cid' AND qzid = '$qzid'";
    $deleteResult = pg_query($conn, $delete_query);
    return $deleteResult;
}

function updateQuizPoint($conn, $cid, $qzid, $questionPoint)
{
    $updateQuizPointQuery = "UPDATE Quiz SET totpoint = totpoint - $questionPoint WHERE  cid = '$cid' AND qzid = '$qzid'";
    $updateQuizPointResult = pg_query($conn, $updateQuizPointQuery);
    return $updateQuizPointResult;
}

function updateQuestionIds($conn, $cid, $qzid)
{
    $selectQuery = "SELECT quid FROM Question WHERE cid = '$cid' AND qzid = '$qzid' ORDER BY CAST(SUBSTRING(quid FROM '[0-9]+') AS INTEGER) ASC";
    $selectResult = pg_query($conn, $selectQuery);
    if ($selectResult) {
        $existingQuids = [];
        while ($row = pg_fetch_assoc($selectResult)) {
            $existingQuids[] = $row['quid'];
        }

        $totalQuestions = count($existingQuids);

        if ($totalQuestions > 0) {
            for ($i = 0; $i < $totalQuestions; $i++) {
                $newQuid = 'Q' . ($i + 1);
                $oldQuid = $existingQuids[$i];
                if ($newQuid != $oldQuid) {
                    $updateQuery = "UPDATE Question SET quid = '$newQuid' WHERE cid = '$cid' AND qzid = '$qzid' AND quid = '$oldQuid'";
                    $updateResult = pg_query($conn, $updateQuery);
                    if (!$updateResult) {
                        return false;
                    }
                }
            }
            return true;
        }
    }
    return false;
}

function updateQuestion($conn, $quid, $questionText, $optionA, $optionB, $optionC, $optionD, $correctOption, $points, $qzid, $cid)
{
    $update_query = "UPDATE question SET
                        qutext = '{$questionText}',
                        op_a = '{$optionA}',
                        op_b = '{$optionB}',
                        op_c = '{$optionC}',
                        op_d = '{$optionD}',
                        ans_op = '{$correctOption}',
                        point = '{$points}'
                        WHERE quid = '{$quid}' AND cid = '$cid' AND qzid = '$qzid'";
    return pg_query($conn, $update_query);
}

function insertQuestion($conn, $quid, $questionText, $optionA, $optionB, $optionC, $optionD, $correctOption, $points, $qzid, $cid)
{
    $insert_query = "INSERT INTO question (quid, qutext, op_a, op_b, op_c, op_d, ans_op, point, qzid, cid)
                        VALUES ('$quid', '{$questionText}', '{$optionA}', '{$optionB}', '{$optionC}', '{$optionD}', '{$correctOption}', '{$points}', '$qzid', '$cid')";
    return pg_query($conn, $insert_query);
}

function updateQuizTotalPoints($conn, $totalPoints, $qzid, $cid)
{
    $update_total_points_query = "UPDATE quiz SET totpoint = '$totalPoints' WHERE qzid = '$qzid' AND cid = '$cid'";
    return pg_query($conn, $update_total_points_query);
}

function deleteQuestions($conn, $qzid, $cid)
{
    $deleteQuestionsQuery = "DELETE FROM question WHERE qzid = '$qzid' AND cid = '$cid'";
    return pg_query($conn, $deleteQuestionsQuery);
}

function deleteQuiz($conn, $qzid, $cid, $iid)
{
    $deleteQuizQuery = "DELETE FROM quiz WHERE qzid = '$qzid' AND cid = '$cid' AND iid = '$iid'";
    return pg_query($conn, $deleteQuizQuery);
}
?>
