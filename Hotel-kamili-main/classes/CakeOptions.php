<?php

namespace classes;

class CakeOptions
{
    private $cake_option_id; // INT, Primary Key
    private $cake_size;      // INT (weight in kilograms)
    private $cake_type;      // VARCHAR(50)
    private $cake_price;     // DECIMAL(10, 2)
    private $created_at;     // TIMESTAMP
    private $modified_at;    // TIMESTAMP

    // Constructor to initialize properties
    public function __construct($cake_size, $cake_type, $cake_price)
    {
        $this->cake_size = $cake_size;
        $this->cake_type = $cake_type;
        $this->cake_price = $cake_price;
        // created_at and modified_at will be handled by the database
    }

    // Getter for cake_option_id
    public function getCakeOptionId()
    {
        return $this->cake_option_id;
    }

    // Getter and Setter for cake_size
    public function getCakeSize()
    {
        return $this->cake_size;
    }

    public function setCakeSize($cake_size)
    {
        $this->cake_size = $cake_size;
    }

    // Getter and Setter for cake_type
    public function getCakeType()
    {
        return $this->cake_type;
    }

    public function setCakeType($cake_type)
    {
        $this->cake_type = $cake_type;
    }

    // Getter and Setter for cake_price
    public function getCakePrice()
    {
        return $this->cake_price;
    }

    public function setCakePrice($cake_price)
    {
        $this->cake_price = $cake_price;
    }

    // Getter for created_at (optional, since it's handled by the database)
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    // Getter for modified_at (optional, since it's handled by the database)
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    // Method to fetch all cake options from the database
    public static function getAllCakeOptions($con)
    {
        try {
            $query = "SELECT * FROM CakeOptions";
            $stmt = $con->prepare($query);
            $stmt->execute();

            // Fetch all rows as associative array
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Error fetching cake options: " . $e->getMessage());
        }
    }

    public static function getCakePriceByCakeOptionId($con, $cake_option_id)
    {
        try {
            $query = "SELECT cake_price FROM CakeOptions WHERE cake_option_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$cake_option_id]);

            // Fetch the result as an associative array
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            // If the result is found, return the cake price
            if ($result) {
                return $result['cake_price'];
            } else {
                return null;  // Return null if no record is found
            }
        } catch (\PDOException $e) {
            die("Error fetching cake price by ID: " . $e->getMessage());
        }
    }

    // Aysha
    // insert new Cake type

    public static function insertCakeType($con, $cake_size, $cake_type, $cake_price) {
        try {
            if (is_array($cake_type) && is_array($cake_size) && is_array($cake_price) &&
                count($cake_type) === count($cake_size) && count($cake_price) === count($cake_size)) {
    
                // Begin transaction
                $con->beginTransaction();
    
                // Prepare the insert statement
                $query = "INSERT INTO CakeOptions (cake_size, cake_type, cake_price, created_at, modified_at) VALUES (?, ?, ?, NOW(), NOW())";
                $stmt = $con->prepare($query);
    
                // Loop through each cake type and execute the insert statement
                for ($i = 0; $i < count($cake_type); $i++) {
                    // Bind values
                    $stmt->execute([$cake_size[$i], $cake_type[$i], $cake_price[$i]]);
                }
    
                // Commit the transaction
                $con->commit();
                return true; // Insertion successful
            }
        } catch (\PDOException $e) {
            $con->rollBack();  // Rollback transaction on failure
            $_SESSION['errors'] = ["Error inserting new cake design: " . $e->getMessage()];
            header('Location: AddCake.php');
            exit();
        }
    }

    // Delete a cake record
    public static function deleteCake($con, $cake_option_id)
    {
        try {
            $query = "DELETE * FROM cakeoptions WHERE cake_option_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $cake_option_id, \PDO::PARAM_INT);
            $stmt->execute();
            return ($stmt->rowCount() > 0);
        } catch (\PDOException $e) {
            error_log("Error deleting cake record: " . $e->getMessage()); // Log the error
            return false;
        }
    }
    
}
