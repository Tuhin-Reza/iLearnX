$(document).ready(function () {
  var studentId = "S101";
  var CID = "C101";
  var quizID = "QZ 7";
  var totalMarks = 0;
  var percentage;

  var questions = [];
  var currentQuestionIndex = 0;
  var selectedOptions = [];
  var quizId = "";
  var quizAnswers = {};

  $(".quizButton").on("click", function () {
    quizId = $(this).data("qzid");

    $.ajax({
      type: "GET",
      url: "../controller/StudentQuizViewServiceController.php",
      data: {
        action: "fetchQuestions",
        cid: CID,
        qzid: quizID,
      },
      success: function (response) {
        if (response && response.questions) {
          var studentQuiz = response.quizzes;
          questions = response.questions;
          displayQuestion(currentQuestionIndex);
          $("#studentQuizViewTitle .quizTitleDegine").text(studentQuiz.qztitle);
          $("#studentQuizViewTotalPoint .quizTotalPointDegine").text(
            studentQuiz.totpoint
          );
        } else {
          $("#studentViewQuizQuestions").html(
            "<p>No questions found for this quiz.</p>"
          );
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });

  function displayQuestion(index) {
    var quizQuestion = questions[index];
    var quizQuestionHtml = `
      <div class="quizQuestionPlusPointBox">
          <div class="questionLeft">
              <h6 class="question"><span>Q${index + 1}: ${quizQuestion.qutext}</span></h6>
          </div>
          <div class="pointRight">
              <span class="badge badge-light">${quizQuestion.point} Point</span>
          </div>
      </div>
        <div class="optionArea">
            <div class="form-check radiobtn">
                <input class="form-check-input" type="radio" id="optionA" name="option" value="A">
                <label class="form-check-label" for="optionA">${
                  quizQuestion.op_a
                }</label>
            </div>
            <div class="form-check radiobtn">
                <input class="form-check-input" type="radio" id="optionB" name="option" value="B">
                <label class="form-check-label" for="optionB">${
                  quizQuestion.op_b
                }</label>
            </div>
            <div class="form-check radiobtn">
                <input class="form-check-input" type="radio" id="optionC" name="option" value="C">
                <label class="form-check-label" for="optionC">${
                  quizQuestion.op_c
                }</label>
            </div>
            <div class="form-check radiobtn">
                <input class="form-check-input" type="radio" id="optionD" name="option" value="D">
                <label class="form-check-label" for="optionD">${
                  quizQuestion.op_d
                }</label>
            </div>
        </div>
    </div>`;
    $("#studentViewQuizQuestions").html(quizQuestionHtml);
    $("#studentQuizViewPoint .quizPointDegine").text(quizQuestion.point);

    var selectedOption = selectedOptions[currentQuestionIndex];
    if (selectedOption) {
      $("input[name='option'][value='" + selectedOption + "']").prop(
        "checked",
        true
      );
    }

    if (index === 0) {
      $("#prevBtn").hide();
    } else {
      $("#prevBtn").show();
    }

    if (index === questions.length - 1) {
      $("#nextBtn").hide();
      $("#submitBtn").show();
    } else {
      $("#nextBtn").show();
      $("#submitBtn").hide();
    }
  }

  $("#nextBtn").on("click", function () {
    var checkedOption = $("input[name='option']:checked").val();
    if (checkedOption) {
      selectedOptions[currentQuestionIndex] = checkedOption;
      if (currentQuestionIndex < questions.length - 1) {
        currentQuestionIndex++;
        displayQuestion(currentQuestionIndex);
      }
    } else {
      alert("Please select an option before moving to the next question.");
    }
  });

  $("#prevBtn").on("click", function () {
    if (currentQuestionIndex > 0) {
      currentQuestionIndex--;
      displayQuestion(currentQuestionIndex);
    }
  });

  $(document).on("click", "#submitBtn", function () {
    var checkedOption = $("input[name='option']:checked").val();
    if (checkedOption) {
      selectedOptions[currentQuestionIndex] = checkedOption;

      var confirmationHtml = `
<div>
    <ul>`;
      for (var i = 0; i < selectedOptions.length; i++) {
        confirmationHtml += `<li>Question ${i + 1}: ${selectedOptions[i]}</li>`;
        confirmationHtml += `<li>Question ${i + 1} (ID: ${
          questions[i].quid
        })</li>`;
        confirmationHtml += `<li>Question ${i + 1} (IDS: ${
          questions[i].ans_op
        })</li>`;

        if (selectedOptions[i] == questions[i].ans_op) {
          totalMarks += parseInt(questions[i].point);
        }
        quizAnswers[questions[i].quid] = selectedOptions[i];
      }
      var totalPoints = questions.reduce(
        (acc, question) => acc + parseInt(question.point),
        0
      );
      percentage = (totalMarks / totalPoints) * 100;
      console.log(percentage);

      if (percentage >= 70) {
        var passingGrade = true;
        alertBox(percentage, passingGrade);
      } else {
        var passingGrade = false;
        alertBox(percentage, passingGrade);
      }
    }else {
      alert("Please select an option before submit your quiz.");
    }

  });

  function finalSubmitQuiz() {
    $.ajax({
      type: "POST",
      url: "./t7.php",
      data: {
        cid: CID,
        qzid: quizID,
        totalMarks: totalMarks,
        percentage: percentage,
      },
      success: function (response) {
        console.log("Student Id: ", studentId);
        console.log("Course Id: ", CID);
        console.log("Quiz Id:", quizID);
        console.log("Total Marks:", totalMarks);
        console.log("Percentage:", percentage);
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  }

  $(document).on("click", "#prevBtn", function () {
    if (currentQuestionIndex > 0) {
      displayQuestion(currentQuestionIndex);
    }
  });

  $(document).on("change", "input[name='option']", function () {
    var checkedOption = $(this).val();
    selectedOptions[currentQuestionIndex] = checkedOption;
  });

  function alertBox(percentage, passingGrade) {
    const passingCriteria = passingGrade;

    if (passingCriteria) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success swalbtn",
          cancelButton: "btn btn-danger swalbtn",
          title: "swal-title",
          text: "swal-text",
        },

        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "Are you sure?",
          html: "<span class='swal-text'>You want to submit your quiz !</span>",
          allowHtml: true,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, submit it!",
          cancelButtonText: "No, cancel!",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            finalSubmitQuiz();
            swalWithBootstrapButtons
              .fire({
                title: `You Got ${percentage.toFixed(0)}%`,
                html: "<span class='swal-text'>Your quiz submitted.</span>",
                allowHtml: true,
                icon: "success",
              })
              .then((result) => {
                if (result.isConfirmed) {
                  $("#studentQUizViewModalCenter").modal("hide");
                }
              });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons
              .fire({
                title: "Cancelled",
                html: "<span class='swal-text'>Your quiz submission cancelled.</span>",
                allowHtml: true,
                icon: "error",
              })
              .then((result) => {
                if (result.isConfirmed) {
                  $("#studentQUizViewModalCenter").modal("hide");
                }
              });
          }
        });
    } else {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success swalbtn",
          cancelButton: "btn btn-danger swalbtn",
        },
        buttonsStyling: false,
      });
      swalWithBootstrapButtons
        .fire({
          icon: "error",
          title: `You Got ${percentage.toFixed(0)}%`,
          html: "<span class='swal-text'>You have not achieved the minimum passing criteria. Please try again.</span>",
          allowHtml: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            $("#studentQUizViewModalCenter").modal("hide");
          }
        });
    }
  }

  function resetValues() {
    totalMarks = 0;
    percentage = 0;
    questions = [];
    currentQuestionIndex = 0;
    selectedOptions = [];
    quizId = "";
    quizAnswers = {};
  }
  $("#studentQUizViewModalCenter").on("hidden.bs.modal", function () {
    resetValues();
  });
});
