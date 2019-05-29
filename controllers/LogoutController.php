<?php

/**
 * The LogoutController class is a Controller that allows a user to sign out of the application.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class LogoutController extends Controller
{
    /**
     * Handle the page request.
     *
     * @param array $request the page parameters from a form post or query string
     */
    protected function handleRequest(&$request)
    {
        $user = $this->getUserSession();
        $this->assign('user', $user);

        $this->destroyUserSession();

        $this->redirect('login.php');
    }

    /**
     * Delete the current user session.
     */
    protected function destroyUserSession()
    {
        // Remove the session cookie
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain']);

        // Destroy the session information
        session_destroy();
        unset($this->user);
    }
}
