<?php

/**
 * The SurveyChartsController class is a Controller that shows survey results
 * in chart form for a given survey.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class SurveyChartsController extends Controller
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

        $survey = $this->getSurvey($request);
        $survey->getSurveyResponseCounts($this->pdo);
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
            $survey->getQuestions($this->pdo);
            foreach ($survey->questions as $question) {
                $question->getChoices($this->pdo);
            }
        } else {
            throw new Exception('Survey ID must be specified');
        }

        return $survey;
    }
}
