/**
 * Validate the survey form to make sure all required questions were answered
 */
function validateForm()
{
    var success = true;

    $('.question_text[data-is_required="1"]').each(function(i, obj)
    {
        var questionID = $(this).data('question_id');
        var questionType = $(this).data('question_type');
        var answerCount = 0;
        switch (questionType)
        {
            case 'input':
                answerCount += $('input[name="question_id[' + questionID + ']"]').val().length > 0 ? 1 : 0;
                break;
            case 'textarea':
                answerCount += $('textarea[name="question_id[' + questionID + ']"]').val().length > 0 ? 1 : 0;
                break;
            case 'radio':
            case 'checkbox':
                answerCount += $('input[name="question_id[' + questionID + '][]"]:checked').length;
                break;
        }

        if (answerCount == 0)
        {
            $(this).addClass('incomplete');
            success = false;
        }
        else
        {
            $(this).removeClass('incomplete');
        }
    });

    if (! success)
        alert('Not all required fields were filled in. Please enter values for the fields with red labels.');

    return success;
}

/**
 * Page load - initialize handlers
 */
$(function()
{
    $('#submitButton').button().click(function()
    {
        return validateForm();
    });
});
