$(document).ready(function () {
    var global_cid, global_qzid;
    $(document).on("click", ".quizQuestionViewButton", function() {
        var cid = $(this).data("cid");
        var qzid = $(this).data("qzid");
        global_cid = cid;
        global_qzid = qzid;

        $.ajax({
            type: "GET",
            url: "./db/quizModuleDB.php",
            data: {
                action: 'fetchQuestions',
                cid: cid,
                qzid: qzid,
            },
            success: function(response) {
                $("#updateQuizQuestions").empty();
                $("#updateQuizQuestionsTotalPoints").text("Total Points: 0");
                if (response && response.length > 0) {
                    $.each(response, function(index, updateQuizQuestion) {
                        var questionNumber = index + 1;
                        var updateQuizQuestionHtml = `
                            <div class='quizQuestionBox'>
                                <div class="form-horizontal">
                                    <div class="form-group notificationGroup">
                                        <div class="col-sm-8">
                                            <div class="input-group questionCountingBox">
                                                <button type="button" class="btn btn-primary notificationButton questionCounting">
                                                    Question No <span class="badge badge-light">${questionNumber}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-4  notification">
                                            <div class="input-group">
                                                <button type="button" class="btn btn-info notificationButton pointCounting">
                                                    Total Points <span class="badge badge-light totalPointsLabel">0</span>
                                                </button>
                                                <button type="button" class="btn crudBtn-labeled btn-danger crudButton eraseQuestion removeAddQuestion" data-cid="${updateQuizQuestion.cid}" data-quid="${updateQuizQuestion.quid}" data-qzid="${updateQuizQuestion.qzid}">
                                                   <span class="crudBtn-label"><i class="glyphicon glyphicon-remove"></i></span>
                                                   Remove
                                                </button>
                                            
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" class="form-control" name="qzid" value="${updateQuizQuestion.qzid}" readonly>
                                    <input type="hidden" class="form-control" name="cid" value="${updateQuizQuestion.cid}" readonly>
                                    <input type="hidden" id="questionNumberInput" name="questionNumber" value="${questionNumber}">
                                    <input type="hidden" class="form-control" name="quid[]" value="${updateQuizQuestion.quid}" readonly>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="input-group ">
                                                <span class="input-group-addon inputLabel" style="padding-right: 47px;">Question</span>
                                                <textarea id="question" name="question[]" class="form-control quizQuestionInputField" rows="3" placeholder="write question here...">${updateQuizQuestion.qutext}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="input-group questionOption">
                                                <span class="input-group-addon inputLabel">A</span>
                                                <input type="text" id="optionA" name="optionA[]" class="form-control inputField" placeholder="write option A" value="${updateQuizQuestion.op_a}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group questionOption">
                                                <span class="input-group-addon inputLabel">B</span>
                                                <input type="text" id="optionB" name="optionB[]" class="form-control inputField" placeholder="write option B" value="${updateQuizQuestion.op_b}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="col-sm-6">
                                            <div class="input-group questionOption">
                                                <span class="input-group-addon inputLabel">C</span>
                                                <input type="text" id="optionC" name="optionC[]"  class="form-control inputField" placeholder="write option C" value="${updateQuizQuestion.op_c}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group questionOption">
                                                <span class="input-group-addon inputLabel">D</span>
                                                <input type="text" id="optionD" name="optionD[]" class="form-control inputField" placeholder="write option D" value="${updateQuizQuestion.op_d}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="input-group questionOption">
                                                <span class="input-group-addon inputLabel">Correct Option</span>
                                                <select id="correctOption" name="correctOption[]" class="form-control inputField" required>
                                                    <option value="A" ${updateQuizQuestion.ans_op === 'A' ? 'selected' : ''}>A</option>
                                                    <option value="B" ${updateQuizQuestion.ans_op === 'B' ? 'selected' : ''}>B</option>
                                                    <option value="C" ${updateQuizQuestion.ans_op === 'C' ? 'selected' : ''}>C</option>
                                                    <option value="D" ${updateQuizQuestion.ans_op === 'D' ? 'selected' : ''}>D</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group questionOption">
                                                <span class="input-group-addon inputLabel" style="padding-right: 67px;">Point</span>
                                                <select id="points" name="points[]" class="form-control inputField" onchange="updateTotalPoints()">
                                                    <option value="1" ${updateQuizQuestion.point === "1" ? "selected" : ""}>1</option>
                                                    <option value="2" ${updateQuizQuestion.point === "2" ? "selected" : ""}>2</option>
                                                    <option value="3" ${updateQuizQuestion.point === "3" ? "selected" : ""}>3</option>
                                                    <option value="4" ${updateQuizQuestion.point === "4" ? "selected" : ""}>4</option>
                                                    <option value="5" ${updateQuizQuestion.point === "5" ? "selected" : ""}>5</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        $("#updateQuizQuestions").append(updateQuizQuestionHtml);
                    });
                    updateQuestionNumbers();
                    update_updateQuizQuestionsTotalPoints();
                } else {
                    $("#updateQuizQuestions").empty();
                    $("#updateQuizQuestions").html('<div class="alert alert-info">No data found.</div>');
                    $("#updateQuizQuestionForm").append(`<input type="hidden" name="qzid" value="${qzid}">`);
                    $("#updateQuizQuestionForm").append(`<input type="hidden" name="cid" value="${cid}">`);
                    $("#updateQuizQuestionModel").modal("show");
                }
                $("#updateQuizQuestionModel").modal("show");
            },
        });
    });

    $(document).on("click", "#addMoreUpdateQuizQuestion", function() {
        var updateQuizQuestionsContainer = $("#updateQuizQuestions");
        var questionCount = updateQuizQuestionsContainer.find(".quizQuestionBox").length + 1;
        var updateQuizQuestionHtml = `
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
                                <textarea id="question" name="question[]" class="form-control quizQuestionInputField" rows="3" placeholder="write question here..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <div class="input-group questionOption">
                                <span class="input-group-addon inputLabel">A</span>
                                <input type="text" id="optionA" name="optionA[]" class="form-control inputField" placeholder="write option A" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group questionOption">
                                <span class="input-group-addon inputLabel">B</span>
                                <input type="text" id="optionB" name="optionB[]" class="form-control inputField" placeholder="write option B" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-sm-6">
                            <div class="input-group questionOption">
                                <span class="input-group-addon inputLabel">C</span>
                                <input type="text" id="optionC" name="optionC[]"  class="form-control inputField" placeholder="write option C" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group questionOption">
                                <span class="input-group-addon inputLabel">D</span>
                                <input type="text" id="optionD" name="optionD[]" class="form-control inputField" placeholder="write option D" required>
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
                                <select id="points" name="points[]" class="form-control inputField" onchange="updateTotalPoints()" required>
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
            </div>`;
        updateQuizQuestionsContainer.append(updateQuizQuestionHtml);
        updateQuestionNumbers();
        update_updateQuizQuestionsTotalPoints();
    });

    function update_updateQuizQuestionsTotalPoints() {
        var updateQuizQuestionsTotalPoints = 0;
        $(".inputField[name='points[]']").each(function() {
            var value = $(this).val();
            if (value !== "") {
                updateQuizQuestionsTotalPoints += parseInt(value);
            }
        });
        $(".totalPointsLabel").text(updateQuizQuestionsTotalPoints);
    }
    

    function updateQuestionNumbers() {
        $(".quizQuestionBox").each(function(index) {
            $(this).find(".questionCounting span").text(index + 1);
        });
    }

    $(document).on("click", ".eraseQuestion", function() {
        var cid = $(this).data("cid");
        var quid = $(this).data("quid");
        var qzid = $(this).data("qzid");
    
        if (confirm("Are you sure you want to remove this question?")) {
            $(this).closest(".quizQuestionBox").remove();
            updateQuestionNumbers();
            update_updateQuizQuestionsTotalPoints();
            $.ajax({
                type: "POST",
                url: "./db/quizModuleDB.php",
                data: {
                    action: 'removeQuizQuestion',
                    quid: quid,
                    cid: cid,
                    qzid: qzid
                },
                success: function(response) {
                    alert(JSON.stringify(response));
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            console.log("Action cancelled by user");
        }
    });
    

    $(document).on("click", "#updateQuizQuestionSubmitForm", function() {
        var isValid = true;
        $("#updateQuizQuestionForm input[type=text], #updateQuizQuestionForm select").each(function() {
            if ($(this).val() === "") {
                isValid = false;
                $(this).addClass("is-invalid");
            } else {
                $(this).removeClass("is-invalid");
            }
        });
        if (isValid) {
            var formData = $("#updateQuizQuestionForm").serialize();
            var existingQuestionsData = $("#existingQuizQuestionsForm").serialize();
            var questionNumber = $("#updateQuizQuestions .quizQuestionBox").length;
            formData += "&questionNumber=" + questionNumber;
            var combinedFormData = formData + "&" + existingQuestionsData;
            $.ajax({
                type: "POST",
                url: "/db/quizModuleDB.php",
                data: combinedFormData + "&action=updateQuizQuestion&cid=" + global_cid + "&qzid=" +  global_qzid,
                success: function(response) {
                    console.log("Response:", response);
                    if (response.success) {
                        alert(response.success);
                        $("#updateQuizQuestionModel").modal("hide");
                        $("#updateQuizQuestions").empty();
                        quizData();
                    } else if (responseData.error) {
                        alert(responseData.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("An error occurred while processing the request. Please try again later.");
                }
            });
        } else {
            alert("Please fill in all required fields.");
        }
    });

    $('#updateQuizQuestionModel').on('hidden.bs.modal', function (e) {
        $("#updateQuizQuestions").empty(); // Clear questions
        update_updateQuizQuestionsTotalPoints(); // Reset total points
    });
});
