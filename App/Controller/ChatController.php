<?php

namespace App\Controller;

use App\Controller;
use App\Model\QuestionModel;
use App\Model\ReviewModel;
use App\Model\CommentModel;

class ChatController extends Controller
{
    public function getQuestions()
    {
        $this->authenticatePage();

        $question_model = new QuestionModel();

        $questions = $question_model->getQuestions($_POST['ad_id']);

        $user = [
            'user_id' => $_SESSION['user_id'],
            'name' => $_SESSION['name']
        ];

        $html = '';

        foreach ($questions as $data) {
            $question = "
                <!-- Pergunta -->
                <div class='row g-0'>
                    <div class='col-2 col-md-1'>
                        <i class='fa-regular fa-circle-user user-default-size'></i>
                    </div>
                    <div class='col-10 col-md-11'>
                        <div class='row'>
                            <div class='col-12 text-start order-1'>
                                " . $data['name'] . "
                            </div>
                            <div class='col-12 text-end order-3'>
                                <small class='text-muted'>" . $data['question_date'] . "</small>
                            </div>
                            <div class='col-12 mt-1 order-2'>
                                <textarea class='form-control input-grey-color text-justify-content' disabled>" . $data['question'] . "</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            ";

            $answer = '';

            if (!empty($data['answer'])) {
                $answer = "
                    <!-- Resposta -->
                    <div class='row g-0 answer'>
                        <div class='col-1'>
                        </div>
                        <div class='col-2 col-lg-1'>
                            <i class='fa-regular fa-circle-user user-default-size'></i>
                        </div>
                        <div class='col-9 col-lg-10'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    " . $user['name'] . "
                                </div>
                                <div class='col-12 text-end order-3'>
                                    <small class='text-muted'>" . $data['answer_date'] . "</small>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea class='form-control input-grey-color answer text-justify-content' disabled>" . $data['answer'] . "</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            } else if ($user['user_id'] === $data['anunciador']) {
                $answer = "
                    <!-- Resposta -->
                    <div class='row g-0 answer'>
                        <div class='col-1'>
                        </div>
                        <div class='col-2 col-lg-1'>
                            <i class='fa-regular fa-circle-user user-default-size'></i>
                        </div>
                        <div class='col-9 col-lg-10'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    " . $user['name'] . "
                                </div>
                                <div class='col-12 text-end order-3'>
                                <div class='row align-items-center'>
                                    <div class='col-12 col-lg-7'></div>
                                    <div class='col-6 col-lg-2 text-end' id='status-" . $data['question_id'] . "'></div>
                                    <div class='col-6 col-lg-3 text-end'>
                                        <button type='button' class='button-input text-light mt-2 answer' data-duvida='" . $data['question_id'] . "'>Responder</button>
                                    </div>
                                </div>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea class='form-control input-grey-color text-justify-content answer-" . $data['question_id'] . "'></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            }

            $html .= "
                <div class='mb-3'>
                    $question
                    $answer
                </div>
            ";
        }

        echo $html;
    }

    public function question()
    {
        $this->authenticatePage();

        $question = new QuestionModel();

        $data = [
            'ad_id' => $_POST['ad_id'],
            'user_id' => $_POST['user_id'],
            'question' => $_POST['text_content']
        ];

        if ($question->insertQuestion($data)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível comentar!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function answer()
    {
        $this->authenticatePage();

        $question = new QuestionModel();

        if ($question->insertAnswer($_POST['duvida'], $_POST['answer'])) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível responder!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function getReviews()
    {
        $this->authenticatePage();

        $review = new ReviewModel();

        $ratings = $review->getReviews($_POST['ad_id']);

        $html = '';

        foreach ($ratings as $rating) {
            $stars = '';

            for ($star = 1; $star <= 5; $star++) {
                if ($star <= $rating['rating']) {
                    $stars .= "<i class='fa-solid fa-star'></i>";
                } else {
                    $stars .= "<i class='fa-regular fa-star'></i>";
                }
            }

            $html .= "
                <div class='mb-3'>
                    <!-- Avaliação -->
                    <div class='row g-0'>
                        <div class='col-2 col-md-1'>
                            <i class='fa-regular fa-circle-user user-default-size'></i>
                        </div>
                        <div class='col-10 col-md-11'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    <div class='row'>
                                        <div class='col-8'>
                                            <p class='mb-0'>" . $rating['name'] . "</p>
                                        </div>
                                        <div class='text-danger user-select-none col-4 text-end'>
                                            <small class='rating-stars-size'>
                                                $stars
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-12 text-end order-3'>
                                    <small class='text-muted'>" . $rating['review_date'] . "</small>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea rows='2' class='form-control input-grey-color' disabled>" . $rating['comment'] . "</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ";
        }

        echo $html;
    }

    public function rate()
    {
        $this->authenticatePage();

        $review = new ReviewModel();

        $data = [
            'ad_id' => $_POST['ad_id'],
            'user_id' => $_POST['user_id'],
            'comment' => $_POST['text_content'],
            'rating' => $_POST['rating']
        ];

        if ($review->insertRate($data)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível avaliar!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function getComments()
    {
        $this->authenticatePage();

        $comment_model = new CommentModel();

        $comments = $comment_model->getComments($_POST['solicitation_id']);

        $html = '';

        foreach ($comments as $comment) {
            $html .= "
                <div class='mb-3'>
                    <!-- Pergunta -->
                    <div class='row g-0'>
                        <div class='col-2 col-md-1'>
                            <i class='fa-regular fa-circle-user user-default-size'></i>
                        </div>
                        <div class='col-10 col-md-11'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    " . $comment['name'] . "
                                </div>
                                <div class='col-12 text-end order-3'>
                                    <small class='text-muted'>" . $comment['comment_date'] . "</small>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea class='form-control input-grey-color text-justify-content' disabled>" . $comment['comment'] . "</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ";
        }

        echo $html;
    }

    public function comment()
    {
        $this->authenticatePage();

        $comment = new CommentModel();

        $data = [
            'solicitation_id' => $_POST['solicitacao'],
            'user_id' => $_POST['user_id'],
            'comment' => $_POST['text_content']
        ];

        if ($comment->insertComment($data)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível comentar!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }
}
