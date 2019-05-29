<?php

/**
 * The Question class is a Model representing the question table, used to store questions
 * associated with a survey and to get choices assoicated with the question.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class Question extends Model
{
    public $choices = [];
    // The primary key used to uniquely identify a record
    protected static $primaryKey = 'question_id';

    // The list of fields in the table
    protected static $fields = [
        'question_id',
        'survey_id',
        'question_type',
        'question_text',
        'is_required',
        'question_order',
    ];

    /**
     * Get the choice records associated with this question, and assign to
     * $choices instance variable.
     *
     * @param PDO $pdo the database to search in
     */
    public function getChoices(PDO $pdo)
    {
        $search = ['question_id' => $this->question_id, 'sort' => 'choice_order'];
        $this->choices = Choice::queryRecords($pdo, $search);
    }

    /**
     * Get a unique id for this object, using the primary key if the record
     * has been stored in the database, otherwise a generated unique id.
     *
     * @return string|int returns a unique id
     */
    public function getUniqueId()
    {
        if (! empty($this->question_id)) {
            return $this->question_id;
        } else {
            static $uniqueID;
            if (empty($uniqueID)) {
                $uniqueID = __CLASS__ . uniqid();
            }

            return $uniqueID;
        }
    }

    /**
     * Delete an array of question ids.
     *
     * @param PDO   $pdo          the database to search in
     * @param array $question_ids the array of question ids to delete
     */
    public static function deleteQuestions(PDO $pdo, $question_ids)
    {
        if (! empty($question_ids)) {
            $sql = 'delete from question where question_id in (' . implode(',', array_fill(0, count($question_ids), '?')) . ')';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($question_ids));
        }
    }
}
