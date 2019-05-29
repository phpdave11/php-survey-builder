<?php

/**
 * The LoginController class is a Controller that allows a user to login.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class LoginController extends Controller
{
    /**
     * Handle the page request.
     *
     * @param array $request the page parameters from a form post or query string
     */
    protected function handleRequest(&$request)
    {
        if (isset($_POST['email']) && isset($_POST['password']) && empty($_POST['email']) && empty($_POST['password'])) {
            throw new Exception('Please enter your e-mail and password.');
        }
        if (! empty($_POST['email']) && empty($_POST['password'])) {
            throw new Exception('Please enter your password.');
        }
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $success = false;

            // Query the database by email
            $login = Login::queryRecordByEmail($this->pdo, $_POST['email']);

            if ($login) {
                $success = $this->authenticateLogin($login, $_POST['password']);
            }

            if ($success) {
                $this->createUserSession($login);

                $this->redirect('index.php');
            } else {
                throw new Exception('Invalid e-mail/password. Please try again.');
            }
        }
    }

    /**
     * Create a new session for a user.
     *
     * @param Login $login the Login object containing the user information
     *                     to store in the session
     */
    protected function createUserSession(Login $login)
    {
        $this->startSession();

        $_SESSION['login'] = $login;
    }

    /**
     * Authenticate a user based on their password.
     *
     * @param Login  $login    the Login object containing the user to be authenticated
     * @param string $password the plain-text password to validate
     *
     * @return bool returns true if the user has been successfully authenticated or
     *              returns false if the user has not been successfully authenticated
     */
    protected function authenticateLogin(Login $login, $password)
    {
        return $login->validatePassword($password);
    }
}
