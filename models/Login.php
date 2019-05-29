<?php

/**
 * The Login class is a Model representing the login table, used to store login information
 * for users that can log into the application.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class Login extends Model
{
    // The primary key used to uniquely identify a record
    protected static $primaryKey = 'login_id';

    // The list of fields in the table
    protected static $fields = [
        'login_id',
        'email',
        'password',
        'first_name',
        'last_name',
    ];

    /**
     * Query the database for an email using a case-insensitive lookup.
     *
     * @param PDO    $pdo   the database object to search in
     * @param string $email the email to look up
     *
     * @return Login returns the Login object or null if not found
     */
    public static function queryRecordByEmail(PDO $pdo, $email)
    {
        $where = 'lower(email) = :email';
        $params = ['email' => strtolower($email)];

        return parent::queryRecordWithWhereClause($pdo, $where, $params);
    }

    /**
     * Generate a password hash using the SHA512 algorithm, using openssl's
     * random pseudo bytes function to create salt.
     *
     * @param string $password the password to hash
     *
     * @return string returns the hashed password
     */
    public static function cryptPassword($password)
    {
        $salt = '$6$rounds=50000$' . base64_encode(openssl_random_pseudo_bytes(10));

        return crypt($password, $salt);
    }

    /**
     * Compare a plain-text password to the password hash stored in the Login record.
     *
     * @param string $password the plain-text password to compare
     *
     * @return bool returns true if the password matches or false if the password doesn't match
     */
    public function validatePassword($password)
    {
        if (crypt($password, $this->password) == $this->password) {
            return true;
        } else {
            return false;
        }
    }
}
