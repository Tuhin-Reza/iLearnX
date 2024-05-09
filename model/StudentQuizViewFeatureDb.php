<?php
include 'db_connection.php';

if (!$conn) {
    $error_message = "Error: Unable to connect to the database.";
    exit;
}

function fetchStudentViewQuizFromDB($conn, $qzId, $cid)
{
    $qz_query = "SELECT qztitle, totpoint FROM Quiz WHERE qzid='$qzId' AND cid='$cid' ORDER BY CAST(SUBSTRING(qzid FROM '[0-9]+') AS INTEGER) ASC LIMIT 1";
    $result = pg_query($conn, $qz_query);

    if (!$result) {
        $error_message = pg_last_error($conn);
        echo "Database query error: $error_message";
        return false;
    }
    $quiz = pg_fetch_assoc($result);

    if (!$quiz) {
        echo "No quiz found for the provided qzid and cid";
        return false;
    }
    return $quiz;
}


function fetchStudentViewQuizQuestionsFromDB($conn, $qzId, $cid)
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

