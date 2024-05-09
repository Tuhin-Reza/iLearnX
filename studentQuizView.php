<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="./css/alertBox.css">
  <link rel="stylesheet" href="./css/studentQuizView.css">
</head>

<body>
  <button class="btn btn-primary quizButton" data-qzid="QZ7" data-toggle="modal" data-target="#studentQUizViewModalCenter">Quiz </button>

  <div class="modal fade" id="studentQUizViewModalCenter" tabindex="-1" role="dialog" aria-labelledby="studentQUizViewModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="studentQuizViewTitlePlusTotalPointBox">
            <div>
              <h4 class='modal-title' id='studentQuizViewTitle'><span class='quizTitleDegine'></span></h4>
            </div>
            <div class="studentViewQuizTotalPointBox">
              <button type="button" class="btn btn-light" id="studentQuizViewTotalPoint">
                Total Point <span class="badge badge-light quizTotalPointDegine">Total Point</span>
              </button>
            </div>
          </div>
        </div>
        <div class="modal-body">

          <div class="studentViewQuizQuestionsContainer">
            <div id="studentViewQuizQuestions"></div>
          </div>

        </div>
        <div class="modal-footer">
          <div id="studentQuizSubmitButton">
            <div> <button type="button" class="btn btn-danger studentQuizViewCloseBtn" data-dismiss="modal">Cancel</button></div>
            <button type="button" class="btn btn-danger btn-sm studentQuizViewCloseBtnIcon"><span class="glyphicon glyphicon-remove" data-dismiss="modal"></span></button>
            <div> <button type="button" class="btn btn-labeled btn-primary btn-text" id="prevBtn" style="display: none;">
                <span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span>PREVIOUS</button>
            </div>
            <div> <button type="button" class="btn btn-labeled btn-primary btn-text" id="nextBtn" style="display: none;">
                <span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>NEXT</button>
            </div>
            <div><button type="button" class="btn btn-labeled btn-success btn-text" id="submitBtn" style="display: none;">
                <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>SUBMIT</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="/js/studentQuizView.js"></script>
</body>

</html>