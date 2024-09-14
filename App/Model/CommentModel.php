<?php

namespace App\Model;

use App\Model;
use PDO;

class CommentModel extends Model
{
    public function insertComment(array $data): bool
    {
        $result = $this->query(
            "INSERT INTO `comment` (
                solicitation_id, user_id, `comment`, comment_date
            ) VALUES (
                :solicitation_id, :user_id, :comment, NOW()
            )",
            $this->mapToBind([
                'solicitation_id'   => $data['solicitation_id'],
                'user_id'           => $data['user_id'],
                'comment'           => $data['comment']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function getComments(int $solicitation_id): array
    {
        $result = $this->query(
            "SELECT c.user_id, u.name, c.comment, DATE_FORMAT(c.comment_date, '%d/%m/%Y | %Hh%i') as comment_date
                FROM comment c
                INNER JOIN user u ON (c.user_id = u.user_id)
                WHERE c.solicitation_id = :solicitation_id
                ORDER BY c.comment_id DESC
            ",
            $this->mapToBind(['solicitation_id' => $solicitation_id])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }
}
