<?php
session_start();
$_SESSION['cid'] = 'C101';
$_SESSION['iid'] = 'I0';
$_SESSION['qzid'] = 'Quiz 10';

// if (isset($_GET['cid']) && isset($_GET['iid'])) {
//     $_SESSION['cid'] = $_GET['cid'];
//     $_SESSION['iid'] = $_GET['iid'];
// } else {
//     header("Location: error_message.php");
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quiz Module Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <link rel="stylesheet" href="./css/quizViewTable.css">
    <link rel="stylesheet" href="./css/insertQuizQuestionForm.css">
    <style>

    </style>
</head>

<body>
    <section id="quizViewTable" class="quizviewtable">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="quizTable">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="tableCaption">
                                    <div class="tableCaptionTitle">
                                        <h2>Manage <b>Quizzes</b></h2>
                                    </div>
                                    <div class="">
                                        <a href="#addQuizQuestionModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Quiz</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <table >
                                    <thead>
                                        <tr>
                                            <th>Quiz Id</th>
                                            <th>Quiz Title</th>
                                            <th>Total Point</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="insertQuizQuestionSection">

        <div id="loadder-area">
            <div id="loadder"></div>
        </div>

        <div class="modal fade" id="addQuizQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addQuizQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class='modal-title' id='quizNumberHeading'>You are insert : <span class='quizNumber'></span></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="quizQuestionContainer">

                            <form id="insertQuizQuestionForm">
                                <input type="hidden" id="quizNumberInput" name="quizNumberInput" readonly>
                                <div class="form-horizontal">
                                    <div class="form-group">

                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon inputLabel" style="padding-right: 43px;">Quiz
                                                    Title</span>
                                                <input type="text" id="qzTitle" name="qzTitle" class="form-control inputField" id="inlineFormInputGroupUsername" placeholder="Write quiz title ">
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div id="insertQuizQuestion">
                                </div>
                            </form>
                            <div class="form-group addMoreQuestionButton">
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <button type="button" id="addQuizQuestion" class="btn crudBtn-labeled btn-primary crudButton ">
                                            <span class="crudBtn-label"><i class="glyphicon glyphicon-plus"></i></span>Add More
                                            Question</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer hidden-footer" style="background-color: #F5F5F5;">
                        <div>
                            <button type="button" id="submitQuizQuestionButton" class="btn crudBtn-labeled btn-success crudButton ">
                                <span class="crudBtn-label"><i class="glyphicon glyphicon-ok"></i></span>Submit Quiz</button>
                        </div>
                        <div>
                            <button type="button" id="closeButton" class="btn crudBtn-labeled btn-danger closeButton crudButton" data-dismiss="modal">
                                <span class="crudBtn-label"><i class="glyphicon glyphicon-remove"></i></span>Close From</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="updateQuizQuestionSection">
        <div class="modal fade" id="updateQuizQuestionModel" tabindex="-1" role="dialog" aria-labelledby="updateQuizQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="updateQuizQuestionModalLabel">Update Quiz Question Form</h4>
                    </div>
                    <div class="modal-body">
                        <form id="updateQuizQuestionForm">
                            <div id="updateQuizQuestions">
                            </div>
                            <div class="form-group addMoreQuestionButton">
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <button type="button" id="addMoreUpdateQuizQuestion" class="btn crudBtn-labeled btn-primary crudButton ">
                                            <span class="crudBtn-label"><i class="glyphicon glyphicon-plus"></i></span>Add More
                                            Question</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="background-color: #F5F5F5;">
                        <div>
                            <button type="button" id="updateQuizQuestionSubmitForm" class="btn crudBtn-labeled btn-success crudButton ">
                                <span class="crudBtn-label"><i class="glyphicon glyphicon-ok"></i></span>Update Quiz</button>
                        </div>
                        <div>
                            <button type="button" id="closeButton" class="btn crudBtn-labeled btn-danger closeButton crudButton" data-dismiss="modal">
                                <span class="crudBtn-label"><i class="glyphicon glyphicon-remove"></i></span>Close From</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <script src="./js/quizData.js"></script>
    <script src="./js/insertQuizQuestion.js"></script>
    <script src="/js/updateQuizQuestion.js"></script>


    <script>
        var cid = '<?php echo isset($_SESSION["cid"]) ? $_SESSION["cid"] : "" ?>';
        var iid = '<?php echo isset($_SESSION["iid"]) ? $_SESSION["iid"] : "" ?>';
    </script>

</body>

</html>