<?php

namespace App\Model;

use App\Model;
use PDO;

class UserModel extends Model
{
    public function insertUser(array $data): bool
    {
        $result = $this->query(
            "INSERT INTO user (
                `name`, birth_date, phone, institution, email, `password`, token
            ) VALUES (
                :name, :birth_date, :phone, :institution, :email, :password, :token
            )",
            $this->mapToBind([
                'name'          => $data['name'],
                'birth_date'    => $data['birth_date'],
                'phone'         => $data['phone'],
                'institution'   => $data['institution'],
                'email'         => $data['email'],
                'password'      => password_hash($data['password'], PASSWORD_DEFAULT),
                'token'         => $data['token']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function updateUser(array $data): bool
    {
        $result = $this->query(
            "UPDATE user 
                SET `name` = :name,
                    birth_date = :birth_date,
                    phone = :phone,
                    institution = :institution
                WHERE user_id = :user_id
            ",
            $this->mapToBind([
                'name'          => $data['name'],
                'birth_date'    => $data['birth_date'],
                'phone'         => $data['phone'],
                'institution'   => $data['institution'],
                'user_id'       => $data['user_id']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function updatePassword(int $user_id, string $password): bool
    {
        $result = $this->query(
            "UPDATE user 
                SET user_id = :user_id,
                    `password` = :password
                WHERE user_id = :user_id
            ",
            $this->mapToBind([
                'user_id'   => $user_id,
                'password'  => password_hash($password, PASSWORD_DEFAULT)
            ])
        );

        return (bool) $result->rowCount();
    }

    public function updateAccess(int $user_id): bool
    {
        $result = $this->query(
            "UPDATE user 
                SET last_access = NOW()
                WHERE user_id = :user_id
            ",
            $this->mapToBind(['user_id' => $user_id])
        );

        return (bool) $result->rowCount();
    }

    public function updateToken(int $user_id, bool $clean_token = false): string
    {
        $token = $clean_token ? '' : sha1(uniqid(mt_rand(), true));

        $this->query(
            "UPDATE user 
                SET token = :token
                WHERE user_id = :user_id
            ",
            $this->mapToBind([
                'user_id'   => $user_id,
                'token'     => $token
            ])
        );

        return $token;
    }

    public function authenticate(string $email, string $password): bool
    {
        $result = $this->query(
            "SELECT `password`
                FROM user
                WHERE email = :email
            ",
            $this->mapToBind(['email' => $email])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return (isset($fetch['password']) && password_verify($password, $fetch['password']));
    }

    public function block(string $email): bool
    {
        $result = $this->query(
            "UPDATE user 
                SET `active` = 0,
                    `password` = :password
                WHERE user_id = :user_id
            ",
            $this->mapToBind([
                'email' => $email,
                'password' => password_hash(time(), PASSWORD_DEFAULT)
            ])
        );

        return (bool) $result->rowCount();
    }

    public function getProfile(int $user_id): array
    {
        $result = $this->query(
            "SELECT `name`, birth_date, phone, institution, email, COUNT(rating) AS ratings, SUM(rating) AS score
                FROM user u
                LEFT JOIN ad a ON (u.user_id = a.user_id) 
                LEFT JOIN review r ON (a.ad_id = r.ad_id)
                WHERE u.user_id = :user_id
            ",
            $this->mapToBind(['user_id' => $user_id])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetch : []);
    }

    public function getUserByEmail(string $email): array
    {
        $result = $this->query(
            "SELECT *
                FROM user
                WHERE email = :email
            ",
            $this->mapToBind(['email' => $email])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetch : []);
    }

    public function getUserByRecover(string $email, string $birth_date): array
    {
        $result = $this->query(
            "SELECT user_id, `name`
                FROM user
                WHERE email = :email AND birth_date = :birth_date
            ",
            $this->mapToBind([
                'email'         => $email,
                'birth_date'    => $birth_date
            ])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetch : []);
    }

    public function getUserByToken(string $token): array
    {
        $result = $this->query(
            "SELECT *
                FROM user
                WHERE token = :token
            ",
            $this->mapToBind(['token' => $token])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetch : []);
    }

    public function checkInactivity(string $email): bool
    {
        $result = $this->query(
            "SELECT user_id
                FROM user
                WHERE email = :email AND active = 1
            ",
            $this->mapToBind(['email' => $email])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return empty($fetch['user_id']);
    }

    public function validateHash(string $email, string $token): bool
    {
        $result = $this->query(
            "UPDATE user 
                SET token = '',
                    `active` = 1
                WHERE email = :email AND token = :token
            ",
            $this->mapToBind([
                'email' => $email,
                'token' => $token
            ])
        );

        return (bool) $result->rowCount();
    }

    public function isAdministrator(string $email): bool
    {
        $result = $this->query(
            "SELECT user_id
                FROM user
                WHERE email = :email AND `admin` = 1
            ",
            $this->mapToBind(['email' => $email])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return isset($fetch['user_id']);
    }

    public function getUsersReport(array $selected_fields): array
    {
        $available_fields = [
            'cb_birth_date'     => 'DATE_FORMAT(birth_date, "%d/%m/%Y") as birth_date',
            'cb_telephone'      => 'phone',
            'cb_email'          => 'email',
            'cb_last_access'    => 'DATE_FORMAT(last_access, "%d/%m/%Y | %Hh%i") as last_access'
        ];

        $selected = ['name'];

        foreach ($selected_fields as $field) {
            if (isset($available_fields[$field])) {
                $selected[] = $available_fields[$field];
            }
        }

        $fields = implode(', ', $selected);

        $result = $this->query(
            "SELECT $fields
                FROM user
            "
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetches : []);
    }
}
