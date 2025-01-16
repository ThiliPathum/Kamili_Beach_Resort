<?php

namespace classes;

use PDO;
use PDOException;

class faq {
    private $faq_id;
    private $faq_question;
    private $faq_answer;

    public function __construct($faq_question, $faq_answer) {
        $this->faq_question = $faq_question;
        $this->faq_answer = $faq_answer;
    }

    public function getFaqId() {
        return $this->faq_id;
    }

    public function getFaqQuestion() {
        return $this->faq_question;
    }

    public function getFaqAnswer() {
        return $this->faq_answer;
    }

    public function setFaqQuestion($faq_question) {
        $this->faq_question = $faq_question;
    }

    public function setFaqAnswer($faq_answer) {
        $this->faq_answer = $faq_answer;
    }

    public function createFaq($con) {
        try {
            $query = "INSERT INTO faq (faq_question, faq_answer) VALUES (?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->faq_question);
            $stmt->bindValue(2, $this->faq_answer);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            die("Error creating FAQ: " . $e->getMessage());
        }
    }

    public function updateFaq($con) {
        try {
            $query = "UPDATE faq SET faq_question = ?, faq_answer = ? WHERE faq_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->faq_question);
            $stmt->bindValue(2, $this->faq_answer);
            $stmt->bindValue(3, $this->faq_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            die("Error updating FAQ: " . $e->getMessage());
        }
    }

    public static function deleteFaq($con, $faq_id) {
        try {
            $query = "DELETE FROM faq WHERE faq_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $faq_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            die("Error deleting FAQ: " . $e->getMessage());
        }
    }

    public function getAllFaq($con) {
        try {
            $query = "SELECT * FROM faq";
            $stmt = $con->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error fetching FAQs: " . $e->getMessage());
        }
    }
}
?>