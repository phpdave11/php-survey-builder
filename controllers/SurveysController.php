<?php

/**
 * The SurveysController class is a Controller that shows a user a list of surveys
 * in the database.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class SurveysController extends Controller
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

        $surveys = Survey::queryRecords($this->pdo, ['sort' => 'survey_name']);
        $this->assign('surveys', $surveys);

        if (isset($request['status']) && $request['status'] == 'deleted') {
            $this->assign('statusMessage', 'Survey deleted successfully');
        }
    }
}
