<?php

namespace App\Model;

use App\Model;
use PDO;

class SolicitationModel extends Model
{
    public function insertSolicitation(array $data): bool
    {
        $accepted_fields = [
            'user_id',
            'title',
            'description',
            'type',
            'expiry_date'
        ];

        foreach ($data as $name => $value) {
            if (!in_array($name, $accepted_fields) || empty($value)) {
                unset($data[$name]);
            }
        }

        $fields = implode(', ', array_keys($data));
        $data = $this->mapToBind($data);

        $result = $this->query(
            "INSERT INTO solicitation (
                $fields
            ) VALUES (
                " . implode(', ', array_keys($data)) . "
            )",
            $data
        );

        return (bool) $result->rowCount();
    }

    public function updateSolicitation(array $data): bool
    {
        $result = $this->query(
            "UPDATE solicitation 
                SET title = :title,
                    `description` = :description,
                    `type` = :type,
                    expiry_date = :expiry_date
                WHERE solicitation_id = :solicitation_id
            ",
            $this->mapToBind([
                'solicitation_id'   => $data['solicitation_id'],
                'title'             => $data['title'],
                'description'       => $data['description'],
                'type'              => $data['type'],
                'expiry_date'       => $data['expiry_date']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function deleteSolicitation(int $solicitation_id): bool
    {
        $result = $this->query(
            "DELETE FROM solicitation 
                WHERE solicitation_id = :solicitation_id
            ",
            $this->mapToBind(['solicitation_id' => $solicitation_id])
        );

        return (bool) $result->rowCount();
    }

    public function getSolicitations(array $filters = []): array
    {
        $where[] = "(expiry_date > CURDATE() OR expiry_date IS NULL)";

        if (isset($filters['title'])) {
            $where[] = "s.title LIKE CONCAT('%', :title, '%')";
        }

        if (isset($filters['type'])) {
            $where[] = "s.type IN ('" . implode("', '", $filters['type']) . "')";

            unset($filters['type']);
        }

        $where = implode(" AND ", $where);

        $result = $this->query(
            "SELECT s.solicitation_id, s.user_id, u.name, s.title, s.description, DATE_FORMAT(s.solicitation_date, '%d/%m/%Y | %Hh%i') as solicitation_date, s.expiry_date, s.type 
                FROM solicitation s
                INNER JOIN user u ON (s.user_id = u.user_id)
                WHERE $where
                ORDER BY s.solicitation_id DESC
            ",
            $this->mapToBind($filters)
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function getSolicitation(int $solicitation_id): array
    {
        $result = $this->query(
            "SELECT s.solicitation_id, s.user_id, u.name, u.phone, s.title, s.description, DATE_FORMAT(s.solicitation_date, '%d/%m/%Y | %Hh%i') as solicitation_date, s.expiry_date, s.type 
                FROM solicitation s
                INNER JOIN user u ON (s.user_id = u.user_id)
                WHERE s.solicitation_id = :solicitation_id AND (expiry_date > CURDATE() OR expiry_date IS NULL)
                ORDER BY s.solicitation_id DESC
            ",
            $this->mapToBind(['solicitation_id' => $solicitation_id])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetch : [];
    }

    public function getSolicitationsByUserId(int $user_id): array
    {
        $result = $this->query(
            "SELECT solicitation_id, title, `type` 
                FROM solicitation
                WHERE user_id = :user_id AND (expiry_date > CURDATE() OR expiry_date IS NULL) 
                ORDER BY solicitation_date DESC
            ",
            $this->mapToBind(['user_id' => $user_id])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }
}
