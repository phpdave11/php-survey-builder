<?php

/**
 * The SurveyThankYouController class is a Controller that thanks a user for taking the survey.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class SurveyThankYouController extends Controller
{
    /**
     * Handle the page request.
     *
     * @param array $request the page parameters from a form post or query string
     */
    protected function handleRequest(&$request)
    {
        $survey = $this->getSurvey($request);
        $this->assign('survey', $survey);
    }

    /**
     * Query the database for a survey_id.
     *
     * @param array $request the page parameters from a form post or query string
     *
     * @return Survey $survey returns a Survey object
     *
     * @throws Exception throws exception if survey id is not specified or not found
     */
    protected function getSurvey(&$request)
    {
        if (! empty($request['survey_id'])) {
            $survey = Survey::queryRecordById($this->pdo, $request['survey_id']);
            if (! $survey) {
                throw new Exception('Survey ID not found in database');
            }
        } else {
            throw new Exception('Survey ID must be specified');
        }

        return $survey;
    }
}
