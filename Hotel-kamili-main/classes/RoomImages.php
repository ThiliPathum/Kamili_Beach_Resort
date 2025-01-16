<?php

namespace classes;

use PDO;
use PDOException;

class RoomImages {
    private $room_image_id;
    private $room;
    private $image_path;
    private $created_by;
    private $modified_by;

    // Constructor
    public function __construct($room, $image_path) {
        $this->room = $room;
        $this->image_path = $image_path;
    }

    // Getters
    public function getRoomImageId() {
        return $this->room_image_id;
    }

    public function getRoomId() {
        return $this->room;
    }

    public function getImagePath() {
        return $this->image_path;
    }

    // Setters
    public function setRoomId($room) {
        $this->room = $room;
    }

    public function setImagePath($image_path) {
        $this->image_path = $image_path;
    }

    public function setCreatedBy($created_by) {
        $this->created_by = $created_by;
    }

    public function setUpdatedBy($modified_by) {
        $this->modified_by = $modified_by;
    }

    // Create a new room image record
    public function create($con) {
        try {
            $query = "INSERT INTO RoomImages (room_id, image_path, created_by) VALUES (?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->room);
            $stmt->bindValue(2, $this->image_path);
            $stmt->bindValue(3, $this->created_by);
            $stmt->execute();
            $this->room_image_id = $con->lastInsertId();
            return ($stmt->rowCount() > 0) ? $this->room_image_id : false;
        } catch (PDOException $e) {
            die("Error creating room image: " . $e->getMessage());
        }
    }

    // Read room images by room ID
    public static function readByRoomId($con, $room) {
        try {
            $query = "SELECT * FROM RoomImages WHERE room_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $room);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error reading room images: " . $e->getMessage());
        }
    }

    // Delete a room image record
    public static function delete($con, $room_image_id) {
        try {
            $query = "DELETE FROM RoomImages WHERE room_image_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $room_image_id);
            $stmt->execute();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            die("Error deleting room image: " . $e->getMessage());
        }
    }

    public static function getImagesByRoomId($con, $room_id) {
        $stmt = $con->prepare("SELECT DISTINCT image_path FROM RoomImages WHERE room_id = ?");
        $stmt->execute([$room_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
