<?php

/**
 * The Choice class is a Model representing the choice table, used to store choices
 * associated with a question.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class Choice extends Model
{
    // The primary key used to uniquely identify a record
    protected static $primaryKey = 'choice_id';

    // The list of fields in the table
    protected static $fields = [
        'choice_id',
        'question_id',
        'choice_text',
        'choice_order',
    ];

    /**
     * Get a unique id for this object, using the primary key if the record
     * has been stored in the database, otherwise a generated unique id.
     *
     * @return string|int returns a unique id
     */
    public function getUniqueId()
    {
        if (! empty($this->choice_id)) {
            return $this->choice_id;
        } else {
            static $uniqueID;
            if (empty($uniqueID)) {
                $uniqueID = __CLASS__ . uniqid();
            }

            return $uniqueID;
        }
    }

    /**
     * Delete an array of choice ids.
     *
     * @param PDO   $pdo        the database to search in
     * @param array $choice_ids the array of choice ids to delete
     */
    public static function deleteChoices(PDO $pdo, $choice_ids)
    {
        if (! empty($choice_ids)) {
            $sql = 'delete from choice where choice_id in (' . implode(',', array_fill(0, count($choice_ids), '?')) . ')';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($choice_ids));
        }
    }
}
