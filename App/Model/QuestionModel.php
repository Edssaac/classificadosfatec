<?php

namespace App\Model;

use App\Model;
use PDO;

class QuestionModel extends Model
{
    public function insertQuestion(array $data): bool
    {
        $result = $this->query(
            "INSERT INTO question (
                ad_id, user_id, question, question_date
            ) VALUES (
                :ad_id, :user_id, :question, NOW()
            )",
            $this->mapToBind([
                'ad_id'     => $data['ad_id'],
                'user_id'   => $data['user_id'],
                'question'  => $data['question']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function insertAnswer(int $question_id, string $answer): bool
    {
        $result = $this->query(
            "UPDATE question 
                SET answer = :answer,
                    answer_date = NOW()
                WHERE question_id = :question_id
            ",
            $this->mapToBind([
                'answer'        => $answer,
                'question_id'   => $question_id
            ])
        );

        return (bool) $result->rowCount();
    }

    public function getQuestions(int $ad_id): array
    {
        $result = $this->query(
            "SELECT q.question_id, q.ad_id, a.user_id AS announcer, q.user_id, u.name, q.question, DATE_FORMAT(q.question_date, '%d/%m/%Y | %Hh%i') as question_date, q.answer, DATE_FORMAT(q.answer_date, '%d/%m/%Y | %Hh%i') as answer_date
                FROM question q
                INNER JOIN user u ON (q.user_id = u.user_id) 
                INNER JOIN ad a ON (q.ad_id = a.ad_id)
                WHERE q.ad_id = :ad_id
                ORDER BY q.question_id DESC
            ",
            $this->mapToBind(['ad_id' => $ad_id])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }
}
