<?php

/**
 * The UsersController class is a Controller that shows a user a list of users
 * in the database.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class UsersController extends Controller
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

        $loginFields = Login::getFields();
        $this->assign('loginFields', $loginFields);

        $users = Login::queryRecords($this->pdo, ['sort' => 'first_name']);
        $this->assign('users', $users);
    }
}
