document.addEventListener("DOMContentLoaded", function () {
  var questionCount = 0;

  document.getElementById("addQuizQuestion").addEventListener("click", addQuizQuestion);

  document.addEventListener("click", function (event) {
    if (event.target.classList.contains("eraseQuestion")) {
      event.target.closest(".quizQuestion").remove();
      updateQuestionNumbers();
      updateTotalPoints();
    }
  });

  document.addEventListener("change", function (event) {
    if (event.target.matches("select[name='points[]']")) {
      updateTotalPoints();
    }
  });

  $("#submitQuizQuestionButton").click(function () {
    $("#insertQuizQuestionForm").submit();
  });

  $("#insertQuizQuestionForm").submit(function (event) {
    event.preventDefault();
    if (validateForm()) {
      $.ajax({
        url: "../controller/InstructorQuizServiceController.php",
        method: "POST",
        data:
          $("#insertQuizQuestionForm").serialize() +
          "&action=insertQuizQuestion" +
          "&cid=" + cid +
          "&iid=" + iid,
        success: function (response) {
          $("#insertQuizQuestionForm")[0].reset();
          $("#addQuizQuestionModal").modal("hide");
          $(
            "#insertQuizQuestionModal input[type=text], #insertQuizQuestionModal select"
          ).val("");
          $("#insertQuizQuestion").empty();
          updateTotalPoints();
          alert("your quiz insert successfully");
          quizData();
        },
        error: function (xhr, status, error) {
          alert("Error occurred");
          alert("Error occurred: " + error);
          quizData();
        },
      });
    }
  });

  function validateForm() {
    var isValid = true;
    $("input[type='text']").each(function () {
      if (!$(this).val()) {
        alert("Please fill out all fields.");
        isValid = false;
        return false;
      }
    });
    $("select[name='correctOption[]']").each(function () {
      if ($(this).val() === "") {
        alert("Please select correct options for all questions.");
        isValid = false;
        return false;
      }
    });
    $("select[name='points[]']").each(function () {
      if ($(this).val() === "") {
        alert("Please select points for all questions.");
        isValid = false;
        return false;
      }
    });
    return isValid;
  }
  quizData();
});

function addQuizQuestion() {
  var insertQuizQuestionDiv = document.getElementById("insertQuizQuestion");
  var newQuestionDiv = document.createElement("div");
  newQuestionDiv.className = "quizQuestion";

  questionCount = insertQuizQuestionDiv.querySelectorAll(".quizQuestion").length + 1; // Recalculate question count

  newQuestionDiv.innerHTML = `
       <div class='quizQuestionBox'>

       <div class="form-horizontal">
       <div class="form-group notificationGroup">

           <div class="col-sm-8">
               <div class="input-group questionCountingBox">
                   <button type="button" class="btn btn-primary notificationButton questionCounting">
                       Question No <span class="badge badge-light">${questionCount}</span>
                   </button>
               </div>
           </div>

           <div class="col-sm-4  notification">
               <div class="input-group">
                   <button type="button" class="btn btn-info notificationButton pointCounting">
                       Total Points <span class="badge badge-light totalPointsLabel">0</span>
                   </button>

                   <button type="button" class="btn crudBtn-labeled btn-danger crudButton eraseQuestion removeAddQuestion">
                       <span class="crudBtn-label"><i class="glyphicon glyphicon-remove"></i></span>
                       Remove
                   </button>
               </div>

           </div>
       </div>

       <div class="form-group">
           <div class="col-sm-12">
               <div class="input-group ">
                   <span class="input-group-addon inputLabel" style="padding-right: 47px;">Question</span>
                   <textarea id="question" name="question[]" class="form-control quizQuestionInputField" rows="3" placeholder="write question here..."></textarea>
               </div>
           </div>
       </div>

       <div class="form-group">
           <div class="col-sm-6">
               <div class="input-group questionOption">
                   <span class="input-group-addon inputLabel">A</span>
                   <input type="text" id="optionA" name="optionA[]" class="form-control inputField" placeholder="write option A">
               </div>
           </div>
           <div class="col-sm-6">
               <div class="input-group questionOption">
                   <span class="input-group-addon inputLabel">B</span>
                   <input type="text" id="optionB" name="optionB[]" class="form-control inputField" placeholder="write option B">
               </div>
           </div>
       </div>

       <div class="form-group ">
           <div class="col-sm-6">
               <div class="input-group questionOption">
                   <span class="input-group-addon inputLabel">C</span>
                   <input type="text" id="optionC" name="optionC[]"  class="form-control inputField" placeholder="write option C">
               </div>
           </div>
           <div class="col-sm-6">
               <div class="input-group questionOption">
                   <span class="input-group-addon inputLabel">D</span>
                   <input type="text" id="optionD" name="optionD[]" class="form-control inputField" placeholder="write option D">
               </div>
           </div>
       </div>

       <div class="form-group">
           <div class="col-sm-6">
               <div class="input-group questionOption">
                   <span class="input-group-addon inputLabel">Correct Option</span>
                   <select id="correctOption" name="correctOption[]" class="form-control inputField" required>
                       <option value="A">A</option>
                       <option value="B">B</option>
                       <option value="C">C</option>
                       <option value="D">D</option>
                   </select>
               </div>
           </div>
           <div class="col-sm-6">
               <div class="input-group questionOption">
                   <span class="input-group-addon inputLabel" style="padding-right: 67px;">Point</span>
                   <select id="points" name="points[]" class="form-control inputField" onchange="updateTotalPoints()">
                       <option value="1">1</option>
                       <option value="2">2</option>
                       <option value="3">3</option>
                       <option value="4">4</option>
                       <option value="5">5</option>
                   </select>
               </div>
           </div>
       </div>
   </div>    
       </div>
    `;
  insertQuizQuestionDiv.appendChild(newQuestionDiv);
  updateTotalPoints();
  updateQuestionNumbers();
  $(".modal-footer").removeClass("hidden-footer");
}

function updateTotalPoints() {
  var totalPoints = 0;
  var selectElements = document.querySelectorAll("select[name='points[]']");
  selectElements.forEach(function (select) {
    if (select.value !== "") {
      totalPoints += parseInt(select.value);
    }
  });
  var totalPointsLabels = document.querySelectorAll(".totalPointsLabel");
  totalPointsLabels.forEach(function (label) {
    label.innerText = totalPoints;
  });
}

function updateQuestionNumbers() {
  var questionNumbers = document.querySelectorAll(".questionCountingBox .badge");
  questionNumbers.forEach(function (number, index) {
    number.innerText = index + 1;
  });
}
