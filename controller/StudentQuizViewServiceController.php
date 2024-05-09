<?php
include '../model/StudentQuizViewFeatureDb.php';

header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $action = $_GET['action'] ?? '';
    switch ($action) {
        case 'fetchQuizzes':
            fetchQuizzes($conn);
            break;
        case 'fetchQuestions':
            fetchQuestions($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'insertQuizQuestion':
            insertQuizQuestion($conn);
            break;
        case 'removeQuizQuestion':
            removeQuizQuestion($conn);
            break;
        case 'updateQuizQuestion':
            updateQuizQuestion($conn);
            break;
        case 'deleteQuizAndQuestion':
            deleteQuizPlusQuestion($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
}


pg_close($conn);

function fetchQuizzes($conn)
{
    $cid = $_GET['cid'] ?? '';
    $iid = $_GET['iid'] ?? '';

    $quizzes = fetchQuizzesFromDB($conn, $cid, $iid);

    if ($quizzes !== false) {
        $nextQuizNumber = getNextQuizNumber($conn, $cid, $iid);
        echo json_encode(['quizzes' => $quizzes, 'nextQuizNumber' => $nextQuizNumber]);
    } else {
        echo json_encode(['error' => 'Error fetching quiz data']);
    }
}


function fetchQuestions($conn)
{
    if (isset($_GET['qzid']) && isset($_GET['cid'])) {
        $qzId = $_GET['qzid'];
        $cid = $_GET['cid'];

        $questions = fetchStudentViewQuizQuestionsFromDB($conn, $qzId, $cid);
        if (isset($questions['error'])) {
            echo json_encode($questions);
        } else {
            $quizzes = fetchStudentViewQuizFromDB($conn, $qzId, $cid);
            echo json_encode(['questions' => $questions, 'quizzes' => $quizzes]);
        }
    } else {
        echo json_encode(['error' => 'qzid or cid parameter missing']);
    }
}
