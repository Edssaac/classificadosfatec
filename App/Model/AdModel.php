<?php

namespace App\Model;

use App\Model;
use PDO;

class AdModel extends Model
{
    public function insertAd(array $data): int
    {
        $accepted_fields = [
            'user_id',
            'title',
            'description',
            'price',
            'discount',
            'discount_date',
            'status',
            'type',
            'expiry_date'
        ];

        $data['status'] = 1;

        foreach ($data as $name => $value) {
            if (!in_array($name, $accepted_fields) || empty($value)) {
                unset($data[$name]);
            }
        }

        $fields = implode(', ', array_keys($data));
        $data = $this->mapToBind($data);

        $this->query(
            "INSERT INTO ad (
                $fields
            ) VALUES (
                " . implode(', ', array_keys($data)) . "
            )",
            $data
        );

        return $this->lastInsertId();
    }

    public function updateAd(array $data): bool
    {
        $result = $this->query(
            "UPDATE ad 
                SET title = :title,
                    `description` = :description,
                    price = :price,
                    discount = :discount,
                    discount_date = :discount_date,
                    expiry_date = :expiry_date
                WHERE ad_id = :ad_id
            ",
            $this->mapToBind([
                'title'         => $data['title'],
                'description'   => $data['description'],
                'price'         => $data['price'],
                'discount'      => $data['discount'],
                'discount_date' => $data['discount_date'],
                'expiry_date'   => $data['expiry_date'],
                'ad_id'         => $data['ad_id']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function deleteAd(int $ad_id): bool
    {
        $result = $this->query(
            "DELETE FROM ad 
                WHERE ad_id = :ad_id
            ",
            $this->mapToBind(['ad_id' => $ad_id])
        );

        return (bool) $result->rowCount();
    }

    public function getAdByUserId(int $user_id): array
    {
        $result = $this->query(
            "SELECT ad_id, title, `type`
                FROM ad
                WHERE user_id = :user_id AND (expiry_date > CURDATE() OR expiry_date IS NULL) 
                ORDER BY ad_date DESC
            ",
            $this->mapToBind(['user_id' => $user_id])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function getRecentAds(int $quantity): array
    {
        $result = $this->query(
            "SELECT a.ad_id, a.title, a.description, p.photo_name 
                FROM ad a
                INNER JOIN product p ON (a.ad_id = p.ad_id)
                WHERE (a.expiry_date > CURDATE() OR a.expiry_date IS NULL) 
                ORDER BY a.ad_date DESC
                LIMIT $quantity
            "
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }
}
