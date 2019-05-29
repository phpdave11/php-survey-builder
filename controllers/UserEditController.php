<?php

/**
 * The UserEditController class is a Controller that allows a user to add another
 * admin user to the database so that that user can also sign into the application.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class UserEditController extends Controller
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

        if (isset($request['action'])) {
            $this->handleAction($request);
        }

        $this->redirect('users.php');
    }

    /**
     * Handle a user submitted action.
     *
     * @param array $request the page parameters from a form post or query string
     */
    protected function handleAction(&$request)
    {
        switch ($request['action']) {
            case 'get_user':
                $this->getUser($request['login_id']);
                break;

            case 'edit_user':
                $this->editUser($request);
                break;

            case 'delete_user':
                $this->deleteUser($request);
                break;
        }
    }

    /**
     * Query the database for a login_id and output JSON for use in JavaScript.
     *
     * @param int $loginID the login_id to look up
     */
    protected function getUser($loginID)
    {
        $login = Login::queryRecordById($this->pdo, $loginID);
        unset($login->password);
        die(json_encode(['login' => $login]));
    }

    /**
     * Update a user based on POST parameters.
     *
     * @param array $request the page parameters from a form post or query string
     */
    protected function editUser(&$request)
    {
        if (! empty($request['login_id'])) {
            $login = Login::queryRecordById($this->pdo, $request['login_id']);
        } else {
            $login = new Login;
        }

        $login->updateValues($request);

        if (! empty($request['password'])) {
            $login->password = Login::cryptPassword($request['password']);
        }

        $this->pdo->beginTransaction();

        $login->storeRecord($this->pdo);

        $this->pdo->commit();
    }

    /**
     * Delete a user based on the login_id specified in the POST parameters.
     *
     * @param array $request the page parameters from a form post or query string
     */
    protected function deleteUser(&$request)
    {
        if (! empty($request['login_id'])) {
            $this->pdo->beginTransaction();

            $login = Login::queryRecordById($this->pdo, $request['login_id']);
            $login->deleteRecord($this->pdo);

            $this->pdo->commit();
        }
    }
}
