<?php

namespace App\Model;

use PDO;

class TutoringModel extends AdModel
{
    public function insertTutoring(array $data): bool
    {
        $data['type'] = 'monitoria';

        $ad_id = $this->insertAd($data);

        if ($ad_id) {
            $result = $this->query(
                "INSERT INTO tutoring (
                    ad_id, `subject`, schedules
                ) VALUES (
                    :ad_id, :subject, :schedules
                )",
                $this->mapToBind([
                    'ad_id'     => $ad_id,
                    'subject'   => $data['subject'],
                    'schedules' => $data['schedules']
                ])
            );

            return (bool) $result->rowCount();
        }

        return false;
    }

    public function updateTutoring(array $data): bool
    {
        if ($this->updateAd($data)) {
            $result = $this->query(
                "UPDATE tutoring 
                    SET `subject` = :subject,
                        schedules = :schedules
                    WHERE ad_id = :ad_id
                ",
                $this->mapToBind([
                    'subject'   => $data['subject'],
                    'schedules' => $data['schedules'],
                    'ad_id'     => $data['ad_id']
                ])
            );

            return (bool) $result->rowCount();
        }

        return false;
    }

    public function deleteTutoring(int $ad_id): bool
    {
        $result = $this->query(
            "DELETE FROM tutoring 
                WHERE ad_id = :ad_id
            ",
            $this->mapToBind(['ad_id' => $ad_id])
        );

        $this->deleteAd($ad_id);

        return (bool) $result->rowCount();
    }

    public function getTutorings(array $filters = []): array
    {
        $where[] = "a.status = '1'";

        if (isset($filters['title'])) {
            $where[] = "a.title LIKE CONCAT('%', :title, '%')";
        }

        if (isset($filters['subject'])) {
            $where[] = "t.subject LIKE CONCAT('%', :subject, '%')";
        }

        if (isset($filters['days'])) {
            $days = [];

            foreach (array_map('intval', $filters['days']) as $day) {
                $days[] = "JSON_CONTAINS(t.schedules, '{\"day\": \"$day\"}', '$')";
            }

            if ($days) {
                $where[] = "(" . implode(" OR ", $days) . ")";
            }

            unset($filters['days']);
        }

        $where = implode(" AND ", $where);

        $result = $this->query(
            "SELECT u.name, a.ad_id, a.title, a.description, DATE_FORMAT(a.ad_date, '%d/%m/%Y | %Hh%i') as ad_date, a.expiry_date, a.price, a.discount, a.discount_date, t.subject
                FROM tutoring t
                INNER JOIN ad a ON (t.ad_id = a.ad_id) 
                INNER JOIN user u ON (a.user_id = u.user_id)
                WHERE $where
                ORDER BY a.ad_date DESC
            ",
            $this->mapToBind($filters)
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function getTutoring(int $ad_id): array
    {
        $result = $this->query(
            "SELECT u.name, u.phone, a.ad_id, a.user_id, a.title, a.description, DATE_FORMAT(a.ad_date, '%d/%m/%Y | %Hh%i') as ad_date, a.expiry_date, a.price, a.discount, a.discount_date, t.subject, t.schedules
                FROM tutoring t
                INNER JOIN ad a ON (t.ad_id = a.ad_id) 
                INNER JOIN user u ON (a.user_id = u.user_id)
                WHERE a.status = '1' AND a.ad_id = :ad_id AND (a.expiry_date > CURDATE() OR a.expiry_date IS NULL)
            ",
            $this->mapToBind(['ad_id' => $ad_id])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetch : [];
    }
}
