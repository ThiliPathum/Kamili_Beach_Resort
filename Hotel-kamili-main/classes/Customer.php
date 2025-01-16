<?php
namespace classes;

use PDO;
use PDOException;

class Customer {
    private $customer_id;
    private $full_name;
    private $email;
    private $telephone;
    private $address;
    private $country;

    public function __construct($full_name, $email, $telephone, $address, $country) {
        $this->full_name = $full_name;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->address = $address;
        $this->country = $country;
    }

    public function getCustomerId() {
        return $this->customer_id;
    }

    public function getFullName() {
        return $this->full_name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setFullName($full_name) {
        $this->full_name = $full_name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function create($con) {
        try {
            $query = "INSERT INTO Customer (full_name, email, telephone, address, country) VALUES (?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->full_name);
            $stmt->bindValue(2, $this->email);
            $stmt->bindValue(3, $this->telephone);
            $stmt->bindValue(4, $this->address);
            $stmt->bindValue(5, $this->country);
            $stmt->execute();
            $this->customer_id = $con->lastInsertId();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            die("Error creating customer: " . $e->getMessage());
        }
    }

    public static function read($con, $customer_id) {
        try {
            $query = "SELECT * FROM Customer WHERE customer_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $customer_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error reading customer: " . $e->getMessage());
        }
    }

    public function update($con) {
        try {
            $query = "UPDATE Customer SET full_name = ?, email = ?, telephone = ?, address = ?, country = ? WHERE customer_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->full_name);
            $stmt->bindValue(2, $this->email);
            $stmt->bindValue(3, $this->telephone);
            $stmt->bindValue(4, $this->address);
            $stmt->bindValue(5, $this->country);
            $stmt->bindValue(6, $this->customer_id);
            $stmt->execute();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            die("Error updating customer: " . $e->getMessage());
        }
    }

    public static function delete($con, $customer_id) {
        try {
            $query = "DELETE FROM Customer WHERE customer_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $customer_id);
            $stmt->execute();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            die("Error deleting customer: " . $e->getMessage());
        }
    }

    public static function getLastInsertedId($con) {
        try {
            return $con->lastInsertId();
        } catch (PDOException $e) {
            die("Error retrieving last inserted ID: " . $e->getMessage());
        }
    }
}
?>
