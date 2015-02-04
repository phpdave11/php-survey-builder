var question_id_counter = 1;
var choice_id_counter = 1;

/**
 * Get a new question id to associate choice ids with this question id
 *
 * @return string question_id
 */
function getUniqueQuestionId()
{
    return 'Question' + question_id_counter++;
}

/**
 * Get a new choice id
 *
 * @return string choice_id
 */
function getUniqueChoiceId()
{
    return 'Choice' + choice_id_counter++;
}

/**
 * Add a new choice to a question
 */
function addChoice()
{
    var choices = $(this).parents('.question').find('.choices');
    var questionID = $(this).parents('.question').data('question_id');
    var choiceID = getUniqueChoiceId();
    var newChoiceHtml = choiceHtml.replace(/QUESTION_ID/g, questionID).replace(/CHOICE_ID/g, choiceID);
    choices.append(newChoiceHtml);

    initDeleteChoiceButton('div.choice[data-choice_id="' + choiceID + '"] button.delete_choice');

    renumberChoices(questionID);

    // Focus on new choice text box
    $('div.choice[data-choice_id="' + choiceID + '"] input.choice_text').focus();

    return false;
}

/**
 * Add a new question to the survey
 */
function addQuestion()
{
    var questions = $(this).parents('.questions_container').find('.questions');
    var questionID = getUniqueQuestionId();
    var newQuestionHtml = questionHtml.replace(/QUESTION_ID/g, questionID);
    questions.append(newQuestionHtml);

    initQuestionTypeDropDown('div[data-question_id="' + questionID + '"] select.question_type');
    initSortableChoices('div.choices[data-question_id="' + questionID + '"]');
    initAddChoiceButton('div[data-question_id="' + questionID + '"] button.add_choice');
    initDeleteQuestionButton('div[data-question_id="' + questionID + '"] button.delete_question');
    initMoveQuestionUpButton('div[data-question_id="' + questionID + '"] button.move_question_up');
    initMoveQuestionDownButton('div[data-question_id="' + questionID + '"] button.move_question_down');

    renumberQuestions();

    // Focus on new question text box
    $('div[data-question_id="' + questionID + '"] input.question_text').focus();

    return false;
}

/**
 * Delete the survey
 */
function deleteSurvey()
{
    $('#action').val('delete_survey');
    if (confirm("Are you sure you want to delete this survey?"))
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * Delete a choice from a question
 */
function deleteChoice()
{
    var questionID = $(this).parents('.question').data('question_id');
    var choiceID = $(this).parents('div.choice').data('choice_id');
    $('div.choice[data-choice_id="' + choiceID + '"]').remove();

    renumberChoices(questionID);

    return false;
}

/**
 * Delete a question from the survey
 */
function deleteQuestion()
{
    $(this).parents('.question').remove();

    renumberQuestions();

    return false;
}

/**
 * Renumber the questions
 */
function renumberQuestions()
{
    $('div.question').each(function(i, obj)
    {
        $(obj).find('.question_number').text(i + 1);
    });
}

/**
 * Move a question up
 */
function moveQuestionUp()
{
    var question = $(this).parents('div.question');
    if (question.prev().length > 0)
        question.prev().before(question);
    renumberQuestions();

    return false;
}

/**
 * Move a question down
 */
function moveQuestionDown()
{
    var question = $(this).parents('div.question');
    if (question.next().length > 0)
        question.next().after(question);
    renumberQuestions();

    return false;
}

/**
 * Renumber a question's choices
 */
function renumberChoices(questionID)
{
    $('div.question[data-question_id="' + questionID + '"]').find('div.choice').each(function(i, obj)
    {
        $(obj).find('.choice_number').text(i + 1);
    });
}

/**
 * Handle a question_type change, hide the choices if it is an open text field
 */
function questionTypeChange()
{
    var showChoices = false;

    switch ($(this).val())
    {
        case 'radio':
        case 'checkbox':
            showChoices = true;
            break;
    }

    var choices = $(this).parents('.question').find('.choices_container');

    if (showChoices)
        choices.show();
    else
        choices.hide();
}

/**
 * Make the choices sortable and renumber the choices when a choice is re-sorrted
 */
function initSortableChoices(selector)
{
    $(selector).sortable({
        disable: true,
        placeholder: "ui-state-highlight",
        items: 'div.choice',
        stop: function(event, ui)
        {
            renumberChoices($(this).parents('div.question').data('question_id'));
        }
    });
}

/**
 * Create a jQuery UI add choice button
 */
function initAddChoiceButton(selector)
{
    $(selector).button({ icons: { primary: 'ui-icon-plusthick' } }).on('click', addChoice);
}

/**
 * Create a jQuery UI delete choice button and delete the choice when clicked
 */
function initDeleteChoiceButton(selector)
{
    $(selector).button({ icons: { primary: 'ui-icon-trash' } }).on('click', deleteChoice);
}

/**
 * Create a jQuery UI delete question button and delete the question when clicked
 */
function initDeleteQuestionButton(selector)
{
    $(selector).button({ icons: { primary: 'ui-icon-trash' } }).on('click', deleteQuestion);
}

/**
 * Create a jQuery UI button that moves the question up when clicked
 */
function initMoveQuestionUpButton(selector)
{
    $(selector).button({ icons: { primary: 'ui-icon-arrowthick-1-n' } }).on('click', moveQuestionUp);
}

/**
 * Create a jQuery UI button that moves the question down when clicked
 */
function initMoveQuestionDownButton(selector)
{
    $(selector).button({ icons: { primary: 'ui-icon-arrowthick-1-s' } }).on('click', moveQuestionDown);
}

/**
 * Handle when the question type is changed
 */
function initQuestionTypeDropDown(selector)
{
    $(selector).on('keyup change', questionTypeChange);
}

/**
 * Validate the form to ensure required fields are filled in
 */
function validateForm()
{
    if ($('#survey_name').val().length == 0)
    {
        alert('Please enter the survey title');
        return false;
    }
    return true;
}

/**
 * Page load - initialize handlers
 */
$(function()
{
    $('#submitButton').button({ icons: { primary: 'ui-icon-disk' }}).click(validateForm);
    $('#delete_survey').button({ icons: { primary: 'ui-icon-closethick' }}).click(deleteSurvey);
    $('#add_question').button({ icons: { primary: 'ui-icon-plusthick' }}).click(addQuestion);

    initQuestionTypeDropDown('select.question_type');
    initSortableChoices("div.choices");
    initAddChoiceButton('.add_choice');
    initDeleteChoiceButton('.delete_choice');
    initDeleteQuestionButton('.delete_question');
    initMoveQuestionUpButton('.move_question_up');
    initMoveQuestionDownButton('.move_question_down');
});
