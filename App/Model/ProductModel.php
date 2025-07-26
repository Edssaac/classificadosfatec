<?php

namespace App\Model;

use PDO;

class ProductModel extends AdModel
{
    public function insertProduct(array $data): bool
    {
        $data['type'] = 'produto';

        $ad_id = $this->insertAd($data);

        if ($ad_id) {
            $result = $this->query(
                "INSERT INTO product (
                    ad_id, photo_name, photo_token, `condition`, operation, quantity
                ) VALUES (
                    :ad_id, :photo_name, :photo_token, :condition, :operation, :quantity
                )",
                $this->mapToBind([
                    'ad_id'         => $ad_id,
                    'photo_name'    => $data['photo_name'],
                    'photo_token'   => $data['photo_token'],
                    'condition'     => $data['condition'],
                    'operation'     => $data['operation'],
                    'quantity'      => $data['quantity']
                ])
            );

            return (bool) $result->rowCount();
        }

        return false;
    }

    public function updateProduct(array $data): bool
    {
        if ($this->updateAd($data)) {
            $result = $this->query(
                "UPDATE product 
                    SET photo_name = :photo_name,
                        photo_token = :photo_token,
                        condition = :condition,
                        operation = :operation,
                        quantity = :quantity,
                    WHERE ad_id = :ad_id
                ",
                $this->mapToBind([
                    'photo_name'    => $data['photo_name'],
                    'photo_token'   => $data['photo_token'],
                    'condition'     => $data['condition'],
                    'operation'     => $data['operation'],
                    'quantity'      => $data['quantity'],
                    'ad_id'         => $data['ad_id']
                ])
            );

            return (bool) $result->rowCount();
        }

        return true;
    }

    public function deleteProduct(int $ad_id): bool
    {
        $result = $this->query(
            "DELETE FROM product 
                WHERE ad_id = :ad_id
            ",
            $this->mapToBind(['ad_id' => $ad_id])
        );

        $this->deleteAd($ad_id);

        return (bool) $result->rowCount();
    }

    public function getProducts(array $filters = []): array
    {
        $where[] = "a.status = '1' AND (a.expiry_date > CURDATE() OR a.expiry_date IS NULL)";

        if (isset($filters['title'])) {
            $where[] = "a.title LIKE CONCAT('%', :title, '%')";
        }

        if (isset($filters['subject'])) {
            $where[] = "m.subject LIKE CONCAT('%', :subject, '%')";
        }

        if (isset($filters['condition'])) {
            $where[] = "p.condition IN ('" . implode("', '", $filters['condition']) . "')";

            unset($filters['condition']);
        }

        if (isset($filters['operation'])) {
            $where[] = "p.operation IN ('" . implode("', '", $filters['operation']) . "')";

            unset($filters['operation']);
        }

        $where = implode(" AND ", $where);

        $result = $this->query(
            "SELECT u.name, a.ad_id, a.title, a.description, DATE_FORMAT(a.ad_date, '%d/%m/%Y | %Hh%i') as ad_date, a.expiry_date, a.price, a.discount, a.discount_date, p.photo_name
                FROM product p
                INNER JOIN ad a ON (p.ad_id = a.ad_id) 
                INNER JOIN user u ON (a.user_id = u.user_id)
                WHERE $where
                ORDER BY a.ad_date DESC
            ",
            $this->mapToBind($filters)
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function getProduct(int $ad_id): array
    {
        $result = $this->query(
            "SELECT u.name, u.phone, a.ad_id, a.user_id, a.title, a.description, DATE_FORMAT(a.ad_date, '%d/%m/%Y | %Hh%i') as ad_date, a.expiry_date, a.price, a.discount, a.discount_date, p.photo_name, p.condition, p.operation, p.quantity
                FROM product p
                INNER JOIN ad a ON (p.ad_id = a.ad_id) 
                INNER JOIN user u ON (a.user_id = u.user_id)
                WHERE a.status = '1' and a.ad_id = $ad_id AND (a.expiry_date > CURDATE() OR a.expiry_date IS NULL)
            ",
            $this->mapToBind(['ad_id' => $ad_id])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetch : [];
    }
}
