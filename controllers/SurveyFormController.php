<?php

/**
 * The SurveyFormController class is a Controller that allows a user to take a survey.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class SurveyFormController extends Controller
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

        if (isset($request['action'])) {
            $this->handleAction($request);
        }
    }

    /**
     * Handle a user submitted action.
     *
     * @param array $request the page parameters from a form post or query string
     */
    protected function handleAction(&$request)
    {
        switch ($request['action']) {
            case 'add_survey_response':
                $this->addSurveyResponse($request);
                break;
        }
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

    /**
     * Set the values for the survey object based on form parameters.
     *
     * @param Survey         $survey         the survey object
     * @param SurveyResponse $surveyResponse the survey response object to update
     * @param array          $request        the page parameters from a form post or query string
     */
    protected function setSurveyResponseValues(Survey $survey, SurveyResponse $surveyResponse, &$request)
    {
        $surveyResponse->survey_id = $survey->survey_id;
        $surveyResponse->time_taken = gmdate('Y-m-d H:i:s');
        $surveyResponse->answers = [];

        if (! empty($request['question_id'])) {
            foreach ($request['question_id'] as $questionID => $answerArray) {
                if (! is_array($answerArray)) {
                    $answerArray = [$answerArray];
                }

                foreach ($answerArray as $answerValue) {
                    $surveyAnswer = new SurveyAnswer;
                    $surveyAnswer->question_id = $questionID;
                    $surveyAnswer->answer_value = $answerValue;
                    $surveyResponse->answers[] = $surveyAnswer;
                }
            }
        }
    }

    /**
     * Store survey answers and survey response record in database.
     *
     * @param SurveyResponse $surveyResponse the survey response object to store
     */
    protected function storeSurveyResponse(SurveyResponse $surveyResponse)
    {
        $surveyResponse->storeRecord($this->pdo);

        foreach ($surveyResponse->answers as $answer) {
            $answer->survey_response_id = $surveyResponse->survey_response_id;
            $answer->storeRecord($this->pdo);
        }
    }

    /**
     * Add a survey response based on POST parameters.
     *
     * @param array $request the page parameters from a form post or query string
     */
    protected function addSurveyResponse(&$request)
    {
        $this->pdo->beginTransaction();

        // Get survey from database or create a new survey object
        $survey = $this->getSurvey($request);

        $surveyResponse = new SurveyResponse;

        // Set values on survey response object
        $this->setSurveyResponseValues($survey, $surveyResponse, $request);

        // Store survey answers and survey response record in database
        $this->storeSurveyResponse($surveyResponse);

        $this->pdo->commit();

        $this->redirect('survey_thank_you.php?survey_id=' . $survey->survey_id);
    }
}
