<?php

/**
 * The SurveyResponse class is a Model representing the survey_response table, used to
 * store an instance of a survey being taken.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class SurveyResponse extends Model
{
    // The primary key used to uniquely identify a record
    protected static $primaryKey = 'survey_response_id';

    // The list of fields in the table
    protected static $fields = [
        'survey_response_id',
        'survey_id',
        'time_taken',
    ];
}
