var cid = '<?php echo $_SESSION["cid"]; ?>';
var iid = '<?php echo $_SESSION["iid"]; ?>';

$(document).ready(function () {
  quizData();
  $(document).on("click", ".delete", function () {
    var qzid = $(this).data("qzid");
    var cid = $(this).data("cid");
    var iid = $(this).data("iid");
    if (
      confirm(
        "Are you sure you want to delete the quiz and all associated questions?"
      )
    ) {
      deleteQuizPlusAllQuestion(qzid, cid, iid);
    }
  });
});

function quizData() {
  $.ajax({
    url: "../controller/InstructorQuizServiceController.php",
    method: "GET",
    data: {
      action: "fetchQuizzes",
      cid: cid,
      iid: iid,
    },
    dataType: "json",
    success: function (response) {
      console.log(response);
      var quizzes = response.quizzes;
      var nextQuizNumber = response.nextQuizNumber;
      $("#quizNumberHeading .quizNumber").text(nextQuizNumber);
      $("#quizNumberInput").val(nextQuizNumber);

      $("#quizViewTable tbody").empty();
      if (quizzes.length > 0) {
        $.each(quizzes, function (index, quiz) {
          var rowHtml = '<tr class="eachrow">';
          rowHtml += '<td data-label="Quiz Id">' + quiz.qzid + "</td>";
          rowHtml += '<td data-label="Quiz Title">' + quiz.qztitle + "</td>";
          rowHtml += '<td data-label="Total Point">' + quiz.totpoint + "</td>";

          rowHtml += '<td data-label="Action">';
          rowHtml +=
            '<button class="btn btn-success btn-md edit quizQuestionViewButton" type="button" data-toggle="modal" data-placement="top" title="Edit" data-cid="' +
            quiz.cid +
            '" data-qzid="' +
            quiz.qzid +
            '" style="outline: none; border: none;"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
            
          rowHtml +=
            '<button class="btn btn-danger btn-md delete" type="button" data-toggle="modal" data-placement="top" title="Delete" data-qzid="' +
            quiz.qzid +
            '" data-cid="' +
            quiz.cid +
            '" data-iid="' +
            quiz.iid +
            '" style="outline: none; border: none;"><span class="glyphicon glyphicon-trash"></span> Delete</button>';
          rowHtml += "</td>";
          rowHtml += "</tr>";
          $("#quizViewTable tbody").append(rowHtml);
        });
      } else {
        $("#quizViewTable tbody").append(
          '<tr><td colspan="4">No Quizzes found.</td></tr>'
        );
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX request failed:", error);
    },
  });
}

function deleteQuizPlusAllQuestion(qzid, cid, iid) {
  $.ajax({
    type: "POST",
    url: "../controller/InstructorQuizServiceController.php",
    data: {
      qzid: qzid,
      cid: cid,
      iid: iid,
      action: "deleteQuizAndQuestion",
    },
    success: function (response) {
      alert("Quiz and associated questions deleted successfully.");
      console.log(response);
      quizData();
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    },
  });
}
