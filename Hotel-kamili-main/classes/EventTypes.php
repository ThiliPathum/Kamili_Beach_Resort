<?php 

class EventTypes {
    private $event_type_id;
    private $event_name;
    private $created_at;
    private $modified_at;

    public function __construct($event_name) {
        $this->event_name = $event_name;
    }

    // Getters
    public function getEventTypeId() {
        return $this->event_type_id;
    }

    public function getEventName() {
        return $this->event_name;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getModifiedAt() {
        return $this->modified_at;
    }

    // Setters
    public function setEventName($event_name) {
        $this->event_name = $event_name;
    }

    // Method to fetch all event types from the database
    public static function getAllEventType($con) {
        try {
            $query = "SELECT * FROM EventTypes";
            $stmt = $con->prepare($query);
            $stmt->execute();
            
            // Fetch all rows as associative array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Error fetching event types: " . $e->getMessage());
        }
    }

    // Function to get the event name by ID
    public static function getEventNameById($con, $eventTypeId) {
        try {
            // SQL query to fetch the event_name where event_type_id matches
            $query = "SELECT event_name FROM EventTypes WHERE event_type_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$eventTypeId]);
    
            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['event_name'] : null; // Return event name or null if not found
    
        } catch (PDOException $e) {
            die("Error fetching event name by ID: " . $e->getMessage());
        }
    }


    // Aysha

    // Insert data
    public function insertEvent($con)
    {
        $stmt = $con->prepare("INSERT INTO eventtypes (event_name) VALUES (:event_name)");
        $stmt->bindParam(':event_name', $this->event_name);
        $stmt->execute();
        return $con->lastInsertId(); // Return the last inserted ID
    }

    // Method to update an existing event
    public function updateEvent($con, $event_type_id, $deco_types, $deco_price, $image_paths)
    {
        try {
            $stmt = $con->prepare("UPDATE eventtypes 
                                    SET event_name = :event_name, 
                                        deco_types = :deco_types, 
                                        deco_price = :deco_price, 
                                        image_paths = :image_paths,
                                        modified_at = NOW() 
                                    WHERE event_type_id = :event_type_id");
            $stmt->bindParam(':event_name', $this->event_name);
            $stmt->bindParam(':deco_types', $deco_types);
            $stmt->bindParam(':deco_price', $deco_price);
            $stmt->bindParam(':image_paths', $image_paths);
            $stmt->bindParam(':event_type_id', $event_type_id);
            $stmt->execute();

            return $stmt->rowCount() > 0; // Return true if the update was successful
        } catch (\PDOException $e) {
            die("Error updating event: " . $e->getMessage());
        }
    }

    // Method to delete an event type by ID
public static function deleteEventType($con, $eventTypeId) {
    try {
        // Start a transaction
        $con->beginTransaction();

        // First, delete any related decoration options
        $deleteDecorationsQuery = "DELETE FROM DecorationOptions WHERE event_type_id = ?";
        $stmt = $con->prepare($deleteDecorationsQuery);
        $stmt->execute([$eventTypeId]);

        // Now delete the event type
        $deleteEventQuery = "DELETE FROM EventTypes WHERE event_type_id = ?";
        $stmt = $con->prepare($deleteEventQuery);
        $stmt->execute([$eventTypeId]);

        // Commit the transaction
        $con->commit();

        return $stmt->rowCount() > 0; // Return true if the deletion was successful
    } catch (\PDOException $e) {
        // Rollback the transaction if an error occurred
        $con->rollBack();
        die("Error deleting event type: " . $e->getMessage());
    }

}
}

?>