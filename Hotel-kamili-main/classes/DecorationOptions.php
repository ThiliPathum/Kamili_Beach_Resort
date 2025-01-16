<?php 

class DecorationOptions {
    private $decoration_id;
    private $event_type_id;
    private $decoration_name;
    private $decoration_image;
    private $decoration_price;
    private $created_at;
    private $modified_at;

    public function __construct($event_type_id, $decoration_name, $decoration_image, $decoration_price) {
        $this->event_type_id = $event_type_id;
        $this->decoration_name = $decoration_name;
        $this->decoration_image = $decoration_image;
        $this->decoration_price = $decoration_price;
    }

    // Getters
    public function getDecorationId() {
        return $this->decoration_id;
    }

    public function getEventTypeId() {
        return $this->event_type_id;
    }

    public function getDecorationName() {
        return $this->decoration_name;
    }

    public function getDecorationImage() {
        return $this->decoration_image;
    }

    public function getDecorationPrice() {
        return $this->decoration_price;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getModifiedAt() {
        return $this->modified_at;
    }

    // Setters
    public function setDecorationName($decoration_name) {
        $this->decoration_name = $decoration_name;
    }

    public function setDecorationImage($decoration_image) {
        $this->decoration_image = $decoration_image;
    }

    public function setDecorationPrice($decoration_price) {
        $this->decoration_price = $decoration_price;
    }

    // Method to fetch a decoration option by its ID
    public static function getDecorationById($con, $decoration_id) {
        try {
            $query = "SELECT * FROM DecorationOptions WHERE decoration_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$decoration_id]);
            
            // Fetch the result as an associative array
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Error fetching decoration by ID: " . $e->getMessage());
        }
    }

    // Function to fetch decoration options by event type ID
    public static function getDecorationOptionsByEventTypeID($con, $event_type_id) {
        try {
            $query = "SELECT * FROM DecorationOptions WHERE event_type_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$event_type_id]);
            
            // Fetch all results as an associative array
            $decorations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $decorations;
        } catch (PDOException $e) {
            die("Error fetching decoration options by event type ID: " . $e->getMessage());
        }
    }

    public static function getDecorationPriceByDecorationId($con, $decoration_id) {
        try {
            $query = "SELECT decoration_price FROM DecorationOptions WHERE decoration_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$decoration_id]);
            
            // Fetch the result as an associative array
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // If the result is found, return the decoration price
            if ($result) {
                return $result['decoration_price'];
            } else {
                return null;  // Return null if no record is found
            }
        } catch (PDOException $e) {
            die("Error fetching decoration price by ID: " . $e->getMessage());
        }
    }
    
    // Method to insert decoration options
    public static function insertDecorationOptions($con, $event_type_id, $decoration_names, $decoration_images, $decoration_prices) {
        try {
            // Check if the input arrays are not null and have the same count
            if (is_array($decoration_names) && is_array($decoration_images) && is_array($decoration_prices) &&
                count($decoration_names) === count($decoration_images) && count($decoration_images) === count($decoration_prices)) {

                // Begin transaction
                $con->beginTransaction();
                
                // Prepare the insert statement
                $query = "INSERT INTO DecorationOptions (event_type_id, decoration_name, decoration_image, decoration_price, created_at) VALUES (?, ?, ?, ?, NOW())";
                $stmt = $con->prepare($query);

                // Loop through each decoration type and execute the insert statement
                for ($i = 0; $i < count($decoration_names); $i++) {
                    // Bind values
                    $stmt->execute([$event_type_id, $decoration_names[$i], $decoration_images[$i], $decoration_prices[$i]]);
                }

                // Commit the transaction
                $con->commit();
                return true; // Insertion successful
            } else {
                throw new InvalidArgumentException("Input arrays must be of the same length and not null.");
            }
        } catch (PDOException $e) {
            // Rollback the transaction on error
            $con->rollBack();
            die("Error inserting decoration options: " . $e->getMessage());
        } catch (InvalidArgumentException $e) {
            die($e->getMessage());
        }
    }

    public static function updateDecorationImage($con, $decoration_image, $event_type_id) {
        $query = "UPDATE DecorationOptions SET decoration_image = ? WHERE event_type_id = ?";
        $stmt = $con->prepare($query);
        $stmt->execute([$decoration_image, $event_type_id]);
    }
    
    // delete decoration based on eventTypeId

    public static function deleteEvent($con, $event_type_id) {
        try {
            // Begin transaction
            $con->beginTransaction();
    
            // First, delete from the DecorationOptions table
            $queryDeleteDeco = "DELETE FROM DecorationOptions WHERE event_type_id = ?";
            $stmtDeleteDeco = $con->prepare($queryDeleteDeco);
            $stmtDeleteDeco->bindValue(1, $event_type_id);
            $stmtDeleteDeco->execute();
    
            // Now delete related entries from the eventTypes table
            $queryDeleteEventType = "DELETE FROM eventTypes WHERE event_type_id = ?";
            $stmtDeleteEventType = $con->prepare($queryDeleteEventType);
            $stmtDeleteEventType->bindValue(1, $event_type_id);
            $stmtDeleteEventType->execute();
    
            // Commit the transaction
            $con->commit();
            return true;
        } catch (PDOException $e) {
            // Rollback the transaction on error
            $con->rollBack();
            die("Error deleting event: " . $e->getMessage());
        }
    }
}    

?>