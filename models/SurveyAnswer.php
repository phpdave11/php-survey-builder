<?php

/**
 * The SurveyAnswer class is a Model representing the survey_answer table, used to store a
 * single answer for a question in.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class SurveyAnswer extends Model
{
    // The primary key used to uniquely identify a record
    protected static $primaryKey = 'survey_answer_id';

    // The list of fields in the table
    protected static $fields = [
        'survey_answer_id',
        'survey_response_id',
        'question_id',
        'answer_value',
    ];
}
