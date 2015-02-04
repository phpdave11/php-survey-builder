/**
 * Clear the fields on the user edit dialog
 */
function clearUserEditDialog()
{
    $.each(loginFields, function(i, key)
    {
        $('#' + key).val('');
    });
}

/**
 * Show the user edit dialog, and add a delete button if the user exists
 *
 * @param bool isNew whether or not this is a new user
 */
function showUserEditDialog(isNew)
{
    var buttonsObj = {};

    if (! isNew)
    {
        buttonsObj['Delete'] = function()
        {
            if (confirm("Are you sure you want to delete this user?"))
            {
                $('#action').val('delete_user');
                $('#user_edit_form').submit();
            }
        };
    }

    buttonsObj['Save'] = function()
    {
        if (validateForm())
            $('#user_edit_form').submit();
    }

    $('#user_edit_dialog').dialog({
        width: 420,
        buttons: buttonsObj
    });
}

/**
 * Validate the form to ensure all fields are filled in
 */
function validateForm()
{
    var valid = true;
    var fields = [];

    if ($('#first_name').val().length == 0)
        fields.push("First name");

    if ($('#last_name').val().length == 0)
        fields.push("Last name");

    if ($('#email').val().length == 0)
        fields.push("E-mail");

    if ($('#login_id').val() == 0 && $('#password').val().length == 0)
        fields.push("Password");

    if (fields.length > 0)
        valid = false;

    if (! valid)
        alert("Please fill in the following field(s): " + fields.join(", "));

    return valid;
}

/**
 * Add a new user
 */
function addUser()
{
    clearUserEditDialog();
    showUserEditDialog(true);

    return false;
}

/**
 * Edit an existing user
 */
function editUser()
{
    var loginID = $(this).data('login_id');

    var params = {
        'action': 'get_user',
        'login_id': loginID
    };

    var callbackFunction = function(data)
    {
        if (typeof data.login !== 'undefined')
        {
            clearUserEditDialog();

            $.each(data.login, function(key, value)
            {
                $('#' + key).val(value);
            });

            showUserEditDialog(false);
        }
    }

    $.post('user_edit.php', params, callbackFunction, 'json');

    return false;
}

/**
 * Page load - initialize handlers
 */
$(function()
{
    $('#add_user_button').button().click(addUser);
    $('.edit_user').button().click(editUser);
});
