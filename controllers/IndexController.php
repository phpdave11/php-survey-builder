<?php

/**
 * The IndexController class is a Controller that shows the home page.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class IndexController extends Controller
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
    }
}
