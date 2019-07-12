<?php

/**
 * The Survey class is a Model representing the survey table, used to get questions
 * associated with the survey and to get survey responses and survey counts for reporting.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class Survey extends Model
{
    public $questions = [];
    public $responses = [];
    // The primary key used to uniquely identify a record
    protected static $primaryKey = 'survey_id';

    // The list of fields in the table
    protected static $fields = [
        'survey_id',
        'survey_name',
    ];

    /**
     * Get the question records associated with this survey, and assign to
     * $questions instance variable.
     *
     * @param PDO $pdo the database to search in
     */
    public function getQuestions(PDO $pdo)
    {
        $search = ['survey_id' => $this->survey_id, 'sort' => 'question_order'];
        $this->questions = Question::queryRecords($pdo, $search);
    }

    /**
     * Get all survey responses for this survey and assign it to the
     * $responses instance variable.
     *
     * @param PDO $pdo the database to search in
     */
    public function getSurveyResponses(PDO $pdo)
    {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $groupConcatSql = 'group_concat';
        if ($driver == 'pgsql') {
            $groupConcatSql = 'string_agg';
        }

        if (! empty($this->survey_id)) {
            if (empty($this->questions)) {
                $this->getQuestions($pdo);
            }

            $questionSubSelects = [];
            foreach ($this->questions as $question) {
                $questionSubSelects[] = "(select $groupConcatSql(answer_value, ', ') from survey_answer sa
                                          where sa.survey_response_id = sr.survey_response_id and
                                          sa.question_id = :question_id_{$question->question_id}) as question_{$question->question_id}";
                $params["question_id_{$question->question_id}"] = $question->question_id;
            }
            $questionSubSelectSql = implode(', ', $questionSubSelects);
            $sql = "select sr.*, $questionSubSelectSql from survey_response sr where sr.survey_id = :survey_id";
            $params['survey_id'] = $this->survey_id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $this->responses = $stmt->fetchAll();
        }
    }

    /**
     * Get the survey response counts for every non-open text question for this survey
     * and assign it to the $choice_counts instance variable of each question object.
     *
     * @param PDO $pdo the database to search in
     */
    public function getSurveyResponseCounts(PDO $pdo)
    {
        foreach ($this->questions as $i => $question) {
            if (! in_array($question->question_type, ['radio', 'checkbox'])) {
                unset($this->questions[$i]);
            }
        }

        foreach ($this->questions as $i => $question) {
            $sql = 'select count(*) from survey_answer sa
                    left outer join survey_response sr on sr.survey_response_id = sa.survey_response_id
                    where sr.survey_id = :survey_id
                    and sa.question_id = :question_id
                    and sa.answer_value = :answer_value';
            $stmt = $pdo->prepare($sql);

            $question->max_answer_count = 0;

            foreach ($question->choices as $choice) {
                $params = [
                    'survey_id' => $this->survey_id,
                    'question_id' => $question->question_id,
                    'answer_value' => $choice->choice_text,
                ];
                $stmt->execute($params);
                $stmt->setFetchMode(PDO::FETCH_NUM);
                if ($row = $stmt->fetch()) {
                    $choice->answer_count = $row[0];
                    if ($choice->answer_count > $question->max_answer_count) {
                        $question->max_answer_count = $choice->answer_count;
                    }
                }
            }

            $question->choice_counts = [];
            foreach ($question->choices as $choice) {
                $question->choice_counts[] = [$choice->choice_text, $choice->answer_count];
            }
        }
    }

    /**
     * Get a unique id for this object, using the primary key if the record
     * has been stored in the database, otherwise a generated unique id.
     *
     * @return string|int returns a unique id
     */
    public function getUniqueId()
    {
        if (! empty($this->survey_id)) {
            return $this->survey_id;
        } else {
            static $uniqueID;
            if (empty($uniqueID)) {
                $uniqueID = __CLASS__ . uniqid();
            }

            return $uniqueID;
        }
    }
}
