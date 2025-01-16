<?php

namespace classes;

use PDO;
use PDOException;

class Room {
    private $room_id;
    private $room_type;
    private $adult_count;
    private $children_count;
    private $price_per_night;
    private $room_description;
    private $number_of_rooms;
    private $room_inside_normal_image;
    private $room_inside_360view_image;
    private $room_bathroom_360view_image;
    private $room_outdoor_360view_image;
    private $created_by;
    private $modified_by;

    // Constructor
    public function __construct($room_type, $adult_count, $children_count, $price_per_night, $room_description, $number_of_rooms, $room_inside_normal_image, $room_inside_360view_image, $room_bathroom_360view_image, $room_outdoor_360view_image) {
        $this->room_type = $room_type;
        $this->adult_count = $adult_count;
        $this->children_count = $children_count;
        $this->price_per_night = $price_per_night;
        $this->room_description = $room_description;
        $this->number_of_rooms = $number_of_rooms;
        $this->room_inside_normal_image = $room_inside_normal_image;
        $this->room_inside_360view_image = $room_inside_360view_image;
        $this->room_bathroom_360view_image = $room_bathroom_360view_image;
        $this->room_outdoor_360view_image = $room_outdoor_360view_image;
    }

    // Getters
    public function getRoomId() {
        return $this->room_id;
    }

    public function getRoomType() {
        return $this->room_type;
    }

    public function getAdultCount() {
        return $this->adult_count;
    }

    public function getChildrenCount() {
        return $this->children_count;
    }

    public function getPricePerNight() {
        return $this->price_per_night;
    }

    public function getRoomDescription() {
        return $this->room_description;
    }

    public function getNumberOfRooms() {
        return $this->number_of_rooms;
    }

    public function getRoomInsideNormalImage() {
        return $this->room_inside_normal_image;
    }

    public function getRoomInside360ViewImage() {
        return $this->room_inside_360view_image;
    }

    public function getRoomBathroom360ViewImage() {
        return $this->room_bathroom_360view_image;
    }

    public function getRoomOutdoor360ViewImage() {
        return $this->room_outdoor_360view_image;
    }

    public function getCreatedBy() {
        return $this->created_by;
    }

    public function getUpdatedBy() {
        return $this->modified_by;
    }

    // Setters
    public function setRoomType($room_type) {
        $this->room_type = $room_type;
    }

    public function setAdultCount($adult_count) {
        $this->adult_count = $adult_count;
    }

    public function setChildrenCount($children_count) {
        $this->children_count = $children_count;
    }

    public function setPricePerNight($price_per_night) {
        $this->price_per_night = $price_per_night;
    }

    public function setRoomDescription($room_description) {
        $this->room_description = $room_description;
    }

    public function setNumberOfRooms($number_of_rooms) {
        $this->number_of_rooms = $number_of_rooms;
    }

    public function setRoomInsideNormalImage($room_inside_normal_image) {
        $this->room_inside_normal_image = $room_inside_normal_image;
    }

    public function setRoomInside360ViewImage($room_inside_360view_image) {
        $this->room_inside_360view_image = $room_inside_360view_image;
    }

    public function setRoomBathroom360ViewImage($room_bathroom_360view_image) {
        $this->room_bathroom_360view_image = $room_bathroom_360view_image;
    }

    public function setRoomOutdoor360ViewImage($room_outdoor_360view_image) {
        $this->room_outdoor_360view_image = $room_outdoor_360view_image;
    }

    public function setCreatedBy($created_by) {
        $this->created_by = $created_by;
    }

    public function setUpdatedBy($modified_by) {
        $this->modified_by = $modified_by;
    }

    // Create a new room record
    public function create($con) {
        try {
            $query = "INSERT INTO Room (room_type, adult_count, children_count, price_per_night, room_description, number_of_rooms, room_inside_normal_image, room_inside_360view_image, room_bathroom_360view_image, room_outdoor_360view_image, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->room_type);
            $stmt->bindValue(2, $this->adult_count);
            $stmt->bindValue(3, $this->children_count);
            $stmt->bindValue(4, $this->price_per_night);
            $stmt->bindValue(5, $this->room_description);
            $stmt->bindValue(6, $this->number_of_rooms);
            $stmt->bindValue(7, $this->room_inside_normal_image);
            $stmt->bindValue(8, $this->room_inside_360view_image);
            $stmt->bindValue(9, $this->room_bathroom_360view_image);
            $stmt->bindValue(10, $this->room_outdoor_360view_image);
            $stmt->bindValue(11, $this->created_by);
            $stmt->execute();
            $this->room_id = $con->lastInsertId();
            return ($stmt->rowCount() > 0) ? $this->room_id : false;
        } catch (PDOException $e) {
            die("Error creating room: " . $e->getMessage());
        }
    }

    // Read a room record by ID along with images and amenities
    public static function read($con, $room_id) {
        try {
            // Query to fetch room details along with related images and amenities
            $query = "
                SELECT 
                    r.*, 
                    ri.image_path AS room_image,
                    ra.amenity_name
                FROM 
                    Room r
                LEFT JOIN 
                    RoomImages ri ON r.room_id = ri.room_id
                LEFT JOIN 
                    RoomAmenity ra ON r.room_id = ra.room_id
                WHERE 
                    r.room_id = ?
            ";
            
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $room_id);
            $stmt->execute();
            
            $room = null;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Since each room image is fetched separately, collect them into an array
                $room['room_id'] = $row['room_id'];
                $room['room_type'] = $row['room_type'];
                $room['room_description'] = $row['room_description'];
                $room['room_inside_normal_image'] = $row['room_inside_normal_image'];
                $room['room_inside_360view_image'] = $row['room_inside_360view_image'];
                $room['room_bathroom_360view_image'] = $row['room_bathroom_360view_image'];
                $room['room_outdoor_360view_image'] = $row['room_outdoor_360view_image'];
                
                // Collect all images related to the room
                $room['images'][] = [
                    'image_path' => $row['room_image'],
                ];
                
                // Collect all amenities related to the room
                $room['amenities'][] = [
                    'amenity_name' => $row['amenity_name'],
                ];
            }
            
            return $room;
        } catch (PDOException $e) {
            die("Error reading room: " . $e->getMessage());
        }
    }


    // Update an existing room record
    public function update($con, $room_id, $roomType, $adultCount, $childrenCount, $pricePerNight, $room_description, $number_of_rooms, $room_inside_normal_image, $room_inside_360view_image, $room_bathroom_360view_image, $room_outdoor_360view_image) {
        try {
            $query = "UPDATE Room SET room_type = ?, adult_count = ?, children_count = ?, price_per_night = ?, room_description = ?, number_of_rooms = ?, room_inside_normal_image = ?, room_inside_360view_image = ?, room_bathroom_360view_image = ?, room_outdoor_360view_image = ? WHERE room_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $roomType);
            $stmt->bindValue(2, $adultCount);
            $stmt->bindValue(3, $childrenCount);
            $stmt->bindValue(4, $pricePerNight);
            $stmt->bindValue(5, $room_description);
            $stmt->bindValue(6, $number_of_rooms);
            $stmt->bindValue(7, $room_inside_normal_image);
            $stmt->bindValue(8, $room_inside_360view_image);
            $stmt->bindValue(9, $room_bathroom_360view_image);
            $stmt->bindValue(10, $room_outdoor_360view_image);
            $stmt->bindValue(11, $room_id);
            $stmt->execute();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            die("Error updating room: " . $e->getMessage());
        } 
}

    // Delete a room record
    public static function delete($con, $room_id) {
        try {
            $query = "DELETE FROM Room WHERE room_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $room_id);
            $stmt->execute();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            die("Error deleting room: " . $e->getMessage());
        }
    }

    // Get All Room details
    public static function getAllRooms($con) {
        try {
            $query = "SELECT * FROM Room";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch amenities and images for each room
            foreach ($rooms as &$room) {
                $room['amenities'] = RoomAmenity::readByRoomId($con, $room['room_id']);
                $room['images'] = RoomImages::readByRoomId($con, $room['room_id']);
            }

            return $rooms;
        } catch (PDOException $e) {
            die("Error fetching rooms: " . $e->getMessage());
        }
    }


    // Add an amenity to a room
    public function addAmenity($con, $amenity_name) {
        $roomAmenity = new RoomAmenity($this->room_id, $amenity_name);
        return $roomAmenity->create($con);
    }

    // Get all amenities for a room
    public function getAmenities($con) {
        return RoomAmenity::readByRoomId($con, $this->room_id);
    }

    // Add an image to a room
    public function addImage($con, $image_path) {
        $roomImage = new RoomImages($this->room_id, $image_path);
        return $roomImage->create($con);
    }

    // Get all images for a room
    public function getImages($con) {
        return RoomImages::readByRoomId($con, $this->room_id);
    }

    // Find available room count within a particular room type for the specified date range
    public static function findAvailableRoomCount($con, $check_in_date, $check_out_date, $room_type) {
        try {
            // Query to find the total number of rooms of the specified type
            $queryTotalRooms = "
                SELECT number_of_rooms
                FROM Room
                WHERE room_type = ?
            ";
            
            $stmtTotalRooms = $con->prepare($queryTotalRooms);
            $stmtTotalRooms->bindValue(1, $room_type, PDO::PARAM_STR);
            $stmtTotalRooms->execute();
            $totalRoomsResult = $stmtTotalRooms->fetch(PDO::FETCH_ASSOC);
            
            if (!$totalRoomsResult) {
                return "No rooms found for the specified type.";
            }
    
            $totalRooms = $totalRoomsResult['number_of_rooms'];
    
            // Query to find the number of booked rooms of the specified type within the date range
            $queryBookedRooms = "
                SELECT SUM(rv.number_of_room) as booked_count
                FROM Reservation rv
                INNER JOIN Room r ON rv.room_id = r.room_id
                WHERE r.room_type = ?
                AND NOT (
                    rv.check_out_date <= ?
                    OR rv.check_in_date >= ?
                )
            ";
            
            $stmtBookedRooms = $con->prepare($queryBookedRooms);
            $stmtBookedRooms->bindValue(1, $room_type, PDO::PARAM_STR);
            $stmtBookedRooms->bindValue(2, $check_in_date, PDO::PARAM_STR);
            $stmtBookedRooms->bindValue(3, $check_out_date, PDO::PARAM_STR);
            $stmtBookedRooms->execute();
            $bookedRoomsResult = $stmtBookedRooms->fetch(PDO::FETCH_ASSOC);
    
            $bookedRooms = $bookedRoomsResult['booked_count'] ? $bookedRoomsResult['booked_count'] : 0;
    
            // Calculate the available rooms
            $availableRooms = $totalRooms - $bookedRooms;
    
            return $availableRooms;
        } catch (PDOException $e) {
            die("Error finding available room count: " . $e->getMessage());
        }
    }

    public static function filterAvailableRooms($con, $check_in_date, $check_out_date, $number_of_adult, $number_of_children) {
        try {
            // Query to find the rooms that match the criteria
            $query = "
                SELECT r.*, 
                       (r.number_of_rooms - COALESCE(SUM(rv.number_of_room), 0)) AS available_rooms
                FROM Room r
                LEFT JOIN Reservation rv ON r.room_id = rv.room_id
                  AND NOT (rv.check_out_date <= ? OR rv.check_in_date >= ?)
                WHERE r.adult_count >= ? 
                  AND r.children_count >= ?
                GROUP BY r.room_id
                HAVING available_rooms > 0
            ";
            
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $check_in_date, PDO::PARAM_STR);
            $stmt->bindValue(2, $check_out_date, PDO::PARAM_STR);
            $stmt->bindValue(3, $number_of_adult, PDO::PARAM_INT);
            $stmt->bindValue(4, $number_of_children, PDO::PARAM_INT);
            $stmt->execute();
            
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Fetch amenities and images for each room
            foreach ($rooms as &$room) {
                $room['amenities'] = RoomAmenity::readByRoomId($con, $room['room_id']);
                $room['images'] = RoomImages::readByRoomId($con, $room['room_id']);
            }
    
            return $rooms;
        } catch (PDOException $e) {
            die("Error filtering available rooms: " . $e->getMessage());
        }
    }

    //select distinct room types
    public static function getAllRoomTypes($con) {
        try {
            // Define the SQL query to select distinct room types
            $query = "SELECT DISTINCT room_type FROM Room";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $roomTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $roomTypes;
        } catch (PDOException $e) {
            die("Error fetching room types: " . $e->getMessage());
        }
    }

    //select the minimum and maximum room prices
    public static function getMinAndMaxRoomPrice($con) {
        try {
            // Define the SQL query to select the minimum and maximum room prices
            $query = "SELECT MIN(price_per_night) AS min_price, MAX(price_per_night) AS max_price FROM Room";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $priceRange = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Return the price range
            return $priceRange;
        } catch (PDOException $e) {
            die("Error fetching room prices: " . $e->getMessage());
        }
    }

    
    public static function filterRooms($con, $check_in_date, $check_out_date, $number_of_adult, $number_of_children, $selected_price, $room_type) {
        try {
            // Base query to filter rooms with given criteria
            $query = "
                SELECT r.*, 
                       (r.number_of_rooms - COALESCE(SUM(rv.number_of_room), 0)) AS available_rooms
                FROM Room r
                LEFT JOIN Reservation rv ON r.room_id = rv.room_id
                  AND NOT (rv.check_out_date <= ? OR rv.check_in_date >= ?)
                WHERE r.adult_count >= ? 
                  AND r.children_count >= ?
                  AND r.price_per_night <= ?
            ";

            // Append room type condition if specified
            if (!empty($room_type)) {
                $query .= " AND r.room_type = ?";
            }

            $query .= " GROUP BY r.room_id HAVING available_rooms > 0";

            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $check_in_date, PDO::PARAM_STR);
            $stmt->bindValue(2, $check_out_date, PDO::PARAM_STR);
            $stmt->bindValue(3, $number_of_adult, PDO::PARAM_INT);
            $stmt->bindValue(4, $number_of_children, PDO::PARAM_INT);
            $stmt->bindValue(5, $selected_price, PDO::PARAM_INT);

            // Bind room type if specified
            if (!empty($room_type)) {
                $stmt->bindValue(6, $room_type, PDO::PARAM_STR);
            }

            $stmt->execute();
            
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch amenities and images for each room
            foreach ($rooms as &$room) {
                $room['amenities'] = RoomAmenity::readByRoomId($con, $room['room_id']);
                $room['images'] = RoomImages::readByRoomId($con, $room['room_id']);
            }

            return $rooms;
        } catch (PDOException $e) {
            die("Error filtering rooms: " . $e->getMessage());
        }
    }

    public static function filterByTypeAndPrice($con, $room_type, $selected_price) {
    try {
        // Define the SQL query to filter rooms by type and selected price
        $query = "
            SELECT * FROM Room
            WHERE room_type = ?
            AND price_per_night <= ?
        ";

        // Prepare and execute the query
        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $room_type, PDO::PARAM_STR);
        $stmt->bindValue(2, $selected_price, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the results
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch amenities and images for each room
        foreach ($rooms as &$room) {
            $room['amenities'] = RoomAmenity::readByRoomId($con, $room['room_id']);
            $room['images'] = RoomImages::readByRoomId($con, $room['room_id']);
        }

        return $rooms;
    } catch (PDOException $e) {
        die("Error filtering rooms by type and price: " . $e->getMessage());
    }
}

// Add this method to the Room class
public static function getRoomPriceByRoomId($con, $room_id) {
    try {
        // Define the SQL query to get the price per night by room ID
        $query = "SELECT price_per_night FROM Room WHERE room_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $room_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return the price if found, otherwise return false
        return $result ? $result['price_per_night'] : false;
    } catch (PDOException $e) {
        die("Error fetching room price: " . $e->getMessage());
    }
}

public static function getReservedRoomCount($con, $room_id, $check_in_date, $check_out_date) {
    try {
        // Query to find the total number of rooms reserved for the specified room ID within the date range
        $query = "
            SELECT SUM(rv.number_of_room) as reserved_count
            FROM Reservation rv
            WHERE rv.room_id = ?
            AND NOT (
                rv.check_out_date <= ?
                OR rv.check_in_date >= ?
            )
        ";

        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $room_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $check_in_date, PDO::PARAM_STR);
        $stmt->bindValue(3, $check_out_date, PDO::PARAM_STR);
        $stmt->execute();
        $reservedRoomsResult = $stmt->fetch(PDO::FETCH_ASSOC);

        $reservedRooms = $reservedRoomsResult['reserved_count'] ? $reservedRoomsResult['reserved_count'] : 0;

        return $reservedRooms;
    } catch (PDOException $e) {
        die("Error finding reserved room count: " . $e->getMessage());
    }
}

// Fetch room details by ID
public static function getRoomById($con, $room_id) {
    try {
        // Query to fetch room details by ID
        $query = "
            SELECT *
            FROM Room
            WHERE room_id = ?
        ";

        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $room_id, PDO::PARAM_INT);
        $stmt->execute();

        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($room) {
            return new Room(
                $room['room_type'],
                $room['adult_count'],
                $room['children_count'],
                $room['price_per_night'],
                $room['room_description'],
                $room['number_of_rooms'],
                $room['room_inside_normal_image'],
                $room['room_inside_360view_image'],
                $room['room_bathroom_360view_image'],
                $room['room_outdoor_360view_image']
            );
        } else {
            return null; // Return null if room not found
        }
    } catch (PDOException $e) {
        die("Error fetching room details: " . $e->getMessage());
    }
}

public static function getAllRoomAvailableCountWithinASpecificDate($con, $specific_date) {
    try {
        // Convert the specific date to a format that can be compared in SQL queries
        $formatted_date = date('Y-m-d', strtotime($specific_date));
        
        // Query to find the total number of rooms for each type
        $queryTotalRooms = "
            SELECT room_type, SUM(number_of_rooms) AS total_rooms
            FROM Room
            GROUP BY room_type
        ";
        
        $stmtTotalRooms = $con->prepare($queryTotalRooms);
        $stmtTotalRooms->execute();
        $totalRoomsResult = $stmtTotalRooms->fetchAll(PDO::FETCH_ASSOC);
        
        // Initialize an array to store the available room counts by type
        $availableRoomCounts = [];
        
        foreach ($totalRoomsResult as $row) {
            $room_type = $row['room_type'];
            $total_rooms = $row['total_rooms'];
            
            // Query to find the number of booked rooms for the specific date
            $queryBookedRooms = "
                SELECT SUM(rv.number_of_room) as booked_count
                FROM Reservation rv
                INNER JOIN Room r ON rv.room_id = r.room_id
                WHERE r.room_type = ?
                AND ? BETWEEN rv.check_in_date AND rv.check_out_date
            ";
            
            $stmtBookedRooms = $con->prepare($queryBookedRooms);
            $stmtBookedRooms->bindValue(1, $room_type, PDO::PARAM_STR);
            $stmtBookedRooms->bindValue(2, $formatted_date, PDO::PARAM_STR);
            $stmtBookedRooms->execute();
            $bookedRoomsResult = $stmtBookedRooms->fetch(PDO::FETCH_ASSOC);
            
            $booked_rooms = $bookedRoomsResult['booked_count'] ? $bookedRoomsResult['booked_count'] : 0;
            
            // Calculate the available rooms for the specific date
            $available_rooms = $total_rooms - $booked_rooms;
            
            // Store the available room count by type
            $availableRoomCounts[$room_type] = $available_rooms;
        }
        
        // Calculate the total number of available rooms across all types
        $totalAvailableRooms = array_sum($availableRoomCounts);
        
        return $totalAvailableRooms;
    } catch (PDOException $e) {
        die("Error finding available room count: " . $e->getMessage());
    }
}

// Fetch reserved room count for a specific date
public static function getReservedRoomCountByDate($con, $specific_date) {
    try {
        // Convert the specific date to a format that can be compared in SQL queries
        $formatted_date = date('Y-m-d', strtotime($specific_date));
        
        // Query to find the total number of reserved rooms for the specific date
        $query = "
            SELECT SUM(rv.number_of_room) as reserved_count
            FROM Reservation rv
            WHERE ? BETWEEN rv.check_in_date AND rv.check_out_date
        ";

        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $formatted_date, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $reservedRooms = $result['reserved_count'] ? $result['reserved_count'] : 0;

        return $reservedRooms;
    } catch (PDOException $e) {
        die("Error finding reserved room count: " . $e->getMessage());
    }
}





    


}
?>