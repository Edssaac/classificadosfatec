<?php

namespace App\Model;

use App\Model;
use PDO;

class ReviewModel extends Model
{
    public function insertRate(array $data): bool
    {
        $result = $this->query(
            "INSERT INTO review (
                ad_id, user_id, `comment`, rating, review_date
            ) VALUES (
                :ad_id, :user_id, :comment, :rating, NOW()
            )",
            $this->mapToBind([
                'ad_id'     => $data['ad_id'],
                'user_id'   => $data['user_id'],
                'comment'   => $data['comment'],
                'rating'    => $data['rating']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function getReviews(int $ad_id): array
    {
        $result = $this->query(
            "SELECT r.user_id, u.name, r.comment, r.rating, DATE_FORMAT(r.review_date, '%d/%m/%Y | %Hh%i') as review_date
                FROM review r
                INNER JOIN user u ON (r.user_id = u.user_id)
                WHERE r.ad_id = :ad_id
                ORDER BY r.ad_id DESC
            ",
            $this->mapToBind(['ad_id' => $ad_id])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }
}
