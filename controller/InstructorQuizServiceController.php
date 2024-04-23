<?php
include '../model/InstructorQuizFeatureDb.php';

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

        $questions = fetchQuestionsFromDB($conn, $qzId, $cid);

        if (isset($questions['error'])) {
            echo json_encode($questions);
        } else {
            if (count($questions) > 0) {
                echo json_encode($questions);
            } else {
                echo json_encode(['error' => 'No questions found for the provided qzid and cid']);
            }
        }
    } else {
        echo json_encode(['error' => 'qzid or cid parameter missing']);
    }
}

function insertQuizQuestion($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cid = $_POST['cid'] ?? '';
        $iid = $_POST['iid'] ?? '';
        $qzid = $_POST['quizNumberInput'];

        if (!empty($_POST['qzTitle'])) {

            if (isset($_POST['cid']) && isset($_POST['iid']) && isset($_POST['quizNumberInput'])) {
                $requiredFields = ['question', 'optionA', 'optionB', 'optionC', 'optionD', 'correctOption', 'points'];
                foreach ($requiredFields as $field) {
                    if (!isset($_POST[$field])) {
                        echo json_encode(['error' => "Field '$field' is missing"]);
                        exit;
                    }
                }
                $qzid = $_POST['quizNumberInput'];
                $qztitle = $_POST['qzTitle'];
                $questions = $_POST['question'];
                $optionsA = $_POST['optionA'];
                $optionsB = $_POST['optionB'];
                $optionsC = $_POST['optionC'];
                $optionsD = $_POST['optionD'];
                $correctOptions = $_POST['correctOption'];
                $points = $_POST['points'];

                $totalPoints = array_sum($points);

                $quizInsertResult = insertQuiz($conn, $qzid, $qztitle, $totalPoints, $cid, $iid);
                if (isset($quizInsertResult['error'])) {
                    echo json_encode($quizInsertResult);
                    exit;
                }

                $questionInsertResult = insertQuestions($conn, $questions, $optionsA, $optionsB, $optionsC, $optionsD, $correctOptions, $points, $qzid, $cid);
                if (isset($questionInsertResult['error'])) {
                    echo json_encode($questionInsertResult);
                    exit;
                }
                echo json_encode(['success' => 'Quiz insert successfully']);
            } else {
                echo json_encode(['error' => 'Required fields (Course,Instructor & Quiz id) are missing']);
            }
        } else {
            echo json_encode(['error' => 'Quiz Title is empty']);
        }
    }
}


function removeQuizQuestion($conn)
{
    if (isset($_POST['quid']) && isset($_POST['cid']) && isset($_POST['qzid'])) {
        $quid = $_POST['quid'];
        $cid = $_POST['cid'];
        $qzid = $_POST['qzid'];

        $questionPoint = fetchQuestionPoints($conn, $quid, $cid, $qzid);
        if ($questionPoint !== false) {

            $deleteResult = deleteQuestion($conn, $quid, $cid, $qzid);
            if ($deleteResult) {
                $updateQuizPointResult = updateQuizPoint($conn, $cid, $qzid, $questionPoint);
                if ($updateQuizPointResult) {
                    $updateQuestionIdsResult = updateQuestionIds($conn, $cid, $qzid);
                    if ($updateQuestionIdsResult) {
                        echo json_encode(['success' => 'Questions Remove successfully']);
                        return;
                    } else {
                        echo "Error updating question IDs: " . pg_last_error($conn);
                    }
                } else {
                    echo "Error updating quiz point: " . pg_last_error($conn);
                }
            } else {
                echo "Error delete question: " . pg_last_error($conn);
            }
        } else {
            echo "Error fetching question points: " . pg_last_error($conn);
        }
    } else {
        echo "Error: Required data quid,ciid,qzid missing";
    }
}

function updateQuizQuestion($conn)
{
    if (isset($_POST['qzid'], $_POST['cid'], $_POST['questionNumber'])) {
        $cid = $_POST['cid'] ?? '';
        $qzid = $_POST['qzid'] ?? '';
        $questionNumber = $_POST['questionNumber'];

        if(!empty($_POST['quid'])){
            $questionid = $_POST['quid'];
        }
        $questionText = $_POST['question'];
        $optionA = $_POST['optionA'];
        $optionB = $_POST['optionB'];
        $optionC = $_POST['optionC'];
        $optionD = $_POST['optionD'];
        $correctOption = $_POST['correctOption'];
        $points = $_POST['points'];

        $totalPoints = 0;
        $success = false;
        $allFieldsFilled = true;

        // Validate that all fields are filled
        foreach ($questionText as $key => $value) {
            if (empty($value) || empty($optionA[$key]) || empty($optionB[$key]) || empty($optionC[$key]) || empty($optionD[$key]) || empty($correctOption[$key]) || empty($points[$key])) {
                $allFieldsFilled = false;
                break;
            }
        }

        if ($allFieldsFilled) {
            foreach ($questionText as $key => $value) {
                $totalPoints += $points[$key];
                $up_quid = "Q" . ($key + 1);

                if (!empty($questionid[$key])) {
                    $updateResult = updateQuestion($conn, $questionid[$key], $questionText[$key], $optionA[$key], $optionB[$key], $optionC[$key], $optionD[$key], $correctOption[$key], $points[$key], $qzid, $cid);
                    if ($updateResult) {
                        $success = true;
                    } else {
                        $success = false;
                        break;
                    }
                } else {
                    $insertResult = insertQuestion($conn, $up_quid, $questionText[$key], $optionA[$key], $optionB[$key], $optionC[$key], $optionD[$key], $correctOption[$key], $points[$key], $qzid, $cid);
                    if ($insertResult) {
                        $success = true;
                    } else {
                        $success = false;
                        break;
                    }
                }
            }

            if ($success) {
                $updateTotalPointsResult = updateQuizTotalPoints($conn, $totalPoints, $qzid, $cid);
                if ($updateTotalPointsResult) {
                    echo json_encode(['success' => 'Questions updated successfully']);
                } else {
                    echo json_encode(['error' => 'Fail to update total point']);
                }
            } else {
                echo json_encode(['error' => 'Fail to update question']);
            }
        } else {
            echo json_encode(['error' => 'Fill up all the update question fields']);
        }
    } else {
        echo json_encode(['error' => 'Missing Quiz & Course information']);
    }
}


function deleteQuizPlusQuestion($conn)
{
    if (isset($_POST['qzid'], $_POST['cid'], $_POST['iid'])) {
        $qzid = $_POST['qzid'];
        $cid = $_POST['cid'];
        $iid = $_POST['iid'];

        $deleteQuestionsResult = deleteQuestions($conn, $qzid, $cid);
        if (!$deleteQuestionsResult) {
            echo json_encode(['error' => 'Failed to delete questions']);
            exit;
        }

        $deleteQuizResult = deleteQuiz($conn, $qzid, $cid, $iid);
        if (!$deleteQuizResult) {
            echo json_encode(['error' => 'Failed to delete the quiz']);
            exit;
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Missing required parameters']);
    }
}
