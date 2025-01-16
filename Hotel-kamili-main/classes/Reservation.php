<?php 

namespace classes;

use PDO;
use PDOException;
use Exception;

class Reservation {
    private $reservation_id;
    private $customer;
    private $room;
    private $check_in_date;
    private $check_out_date;
    private $number_of_adult;
    private $number_of_children;
    private $number_of_room;
    private $total_price;
    private $payment_status;
    private $created_by;
    private $modified_by;

    public function __construct($customer, $room, $check_in_date, $check_out_date, $number_of_adult, $number_of_children, $number_of_room, $total_price, $payment_status = 'pending') {
        $this->customer = $customer;
        $this->room = $room;
        $this->check_in_date = $check_in_date;
        $this->check_out_date = $check_out_date;
        $this->number_of_adult = $number_of_adult;
        $this->number_of_children = $number_of_children;
        $this->number_of_room = $number_of_room;
        $this->total_price = $total_price;
        $this->payment_status = $payment_status;
    }

    public function getReservationId() {
        return $this->reservation_id;
    }

    public function getCustomerId() {
        return $this->customer;
    }

    public function getRoomId() {
        return $this->room;
    }

    public function getCheckInDate() {
        return $this->check_in_date;
    }

    public function getCheckOutDate() {
        return $this->check_out_date;
    }

    public function getNumberOfAdult() {
        return $this->number_of_adult;
    }

    public function getNumberOfChildren() {
        return $this->number_of_children;
    }

    public function getNumberOfRoom() {
        return $this->number_of_room;
    }

    public function getTotalPrice() {
        return $this->total_price;
    }

    public function getPaymentStatus() {
        return $this->payment_status;
    }

    public function setCustomerId($customer) {
        $this->customer = $customer;
    }

    public function setRoomId($room) {
        $this->room = $room;
    }

    public function setCheckInDate($check_in_date) {
        $this->check_in_date = $check_in_date;
    }

    public function setCheckOutDate($check_out_date) {
        $this->check_out_date = $check_out_date;
    }

    public function setNumberOfAdult($number_of_adult) {
        $this->number_of_adult = $number_of_adult;
    }

    public function setNumberOfChildren($number_of_children) {
        $this->number_of_children = $number_of_children;
    }

    public function setNumberOfRoom($number_of_room) {
        $this->number_of_room = $number_of_room;
    }

    public function setTotalPrice($total_price) {
        $this->total_price = $total_price;
    }

    public function setPaymentStatus($payment_status) {
        $this->payment_status = $payment_status;
    }

    public function setCreatedBy($created_by) {
        $this->created_by = $created_by;
    }

    public function setUpdatedBy($modified_by) {
        $this->modified_by = $modified_by;
    }

    public function create($con) {
        try {
            $query = "INSERT INTO Reservation (customer_id, room_id, check_in_date, check_out_date, number_of_adult, number_of_children, number_of_room, total_price, payment_status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->customer);
            $stmt->bindValue(2, $this->room);
            $stmt->bindValue(3, $this->check_in_date);
            $stmt->bindValue(4, $this->check_out_date);
            $stmt->bindValue(5, $this->number_of_adult);
            $stmt->bindValue(6, $this->number_of_children);
            $stmt->bindValue(7, $this->number_of_room);
            $stmt->bindValue(8, $this->total_price);
            $stmt->bindValue(9, $this->payment_status);
            $stmt->bindValue(10, $this->created_by);
            $stmt->execute();
            $this->reservation_id = $con->lastInsertId();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            die("Error creating reservation: " . $e->getMessage());
        }
    }

    public function insertReservedRoomTypeId($con, $reservation_id, $reserved_room_type_id) {
        try {
            $query = "INSERT INTO ReservedRoomTypeId (reservation_id, reserved_room_type_id) VALUES (?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $reservation_id);
            $stmt->bindValue(2, $reserved_room_type_id);
            $stmt->execute();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            die("Error inserting reserved room type ID: " . $e->getMessage());
        }
    }

    public static function read($con, $reservation_id) {
        try {
            $query = "SELECT * FROM Reservation WHERE reservation_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $reservation_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error reading reservation: " . $e->getMessage());
        }
    }

    public function update($con) {
        try {
            $query = "UPDATE Reservation SET customer_id = ?, room_id = ?, check_in_date = ?, check_out_date = ?, number_of_adult = ?, number_of_children = ?, number_of_room = ?, total_price = ?, payment_status = ? WHERE reservation_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->customer);
            $stmt->bindValue(2, $this->room);
            $stmt->bindValue(3, $this->check_in_date);
            $stmt->bindValue(4, $this->check_out_date);
            $stmt->bindValue(5, $this->number_of_adult);
            $stmt->bindValue(6, $this->number_of_children);
            $stmt->bindValue(7, $this->number_of_room);
            $stmt->bindValue(8, $this->total_price);
            $stmt->bindValue(9, $this->payment_status);
            $stmt->bindValue(10, $this->reservation_id);
            $stmt->execute();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            die("Error updating reservation: " . $e->getMessage());
        }
    }

    // public static function delete($con, $reservation_id) {
    //     try {
    //         $query = "DELETE FROM Reservation WHERE reservation_id = ?";
    //         $stmt = $con->prepare($query);
    //         $stmt->bindValue(1, $reservation_id);
    //         $stmt->execute();
    //         return ($stmt->rowCount() > 0);
    //     } catch (PDOException $e) {
    //         die("Error deleting reservation: " . $e->getMessage());
    //     }
    // }

    public static function delete($con, $id) {
        try {
            // Fetch related entries from reservedroomtypeid
            $queryFetchRelated = "SELECT id FROM reservedroomtypeid WHERE reservation_id = ?";
            $stmtFetchRelated = $con->prepare($queryFetchRelated);
            $stmtFetchRelated->bindValue(1, $id);
            $stmtFetchRelated->execute();
            $relatedIds = $stmtFetchRelated->fetchAll(PDO::FETCH_COLUMN);
    
            // Delete related entries from reservedroomtypeid
            $queryDeleteRelated = "DELETE FROM reservedroomtypeid WHERE reservation_id = ?";
            $stmtDeleteRelated = $con->prepare($queryDeleteRelated);
            $stmtDeleteRelated->bindValue(1, $id);
            $stmtDeleteRelated->execute();

            // Fetch related entries from eventcustomizations
            $queryFetchRelated = "SELECT customization_id FROM eventcustomizations WHERE reservation_id = ?";
            $stmtFetchRelated = $con->prepare($queryFetchRelated);
            $stmtFetchRelated->bindValue(1, $id);
            $stmtFetchRelated->execute();
            $relatedIds = $stmtFetchRelated->fetchAll(PDO::FETCH_COLUMN);
    
            // Delete related entries from eventcustomizations
            $queryDeleteRelated = "DELETE FROM eventcustomizations WHERE reservation_id = ?";
            $stmtDeleteRelated = $con->prepare($queryDeleteRelated);
            $stmtDeleteRelated->bindValue(1, $id);
            $stmtDeleteRelated->execute();
    
            // Now delete the reservation itself
            $queryDeleteReservation = "DELETE FROM reservation WHERE reservation_id = ?";
            $stmtDeleteReservation = $con->prepare($queryDeleteReservation);
            $stmtDeleteReservation->bindValue(1, $id);
            $stmtDeleteReservation->execute();
    
            return true;
        } catch (PDOException $e) {
            die("Error deleting reservation: " . $e->getMessage());
        }
    }
    
    public static function filterAvailableRooms($con, $check_in_date, $check_out_date, $number_of_adult, $number_of_children) {
        try {
            $query = "
                SELECT r.*
                FROM Room r
                WHERE r.room_id NOT IN (
                    SELECT rv.room_id
                    FROM Reservation rv
                    WHERE NOT (
                        rv.check_out_date <= :check_in_date
                        OR rv.check_in_date >= :check_out_date
                    )
                )
                AND r.adult_count >= :number_of_adult
                AND r.children_count >= :number_of_children
            ";
            
            $stmt = $con->prepare($query);
            $stmt->bindValue(':check_in_date', $check_in_date, PDO::PARAM_STR);
            $stmt->bindValue(':check_out_date', $check_out_date, PDO::PARAM_STR);
            $stmt->bindValue(':number_of_adult', $number_of_adult, PDO::PARAM_INT);
            $stmt->bindValue(':number_of_children', $number_of_children, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error filtering available rooms: " . $e->getMessage());
        }
    }

    public static function getAllReservations($con, $limit = 10, $offset = 0)
    {
        try {
            $query = "
                SELECT reservation.*, customer.full_name, room.room_id
                FROM Reservation
                JOIN Customer ON reservation.customer_id = customer.customer_id
                JOIN Room ON reservation.room_id = room.room_id
                ORDER BY reservation.created_at DESC
                LIMIT :limit OFFSET :offset
            ";
            $stmt = $con->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error fetching all reservations: " . $e->getMessage());
        }
    }

    public static function getTotalReservations($con)
{
    try {
        $query = "SELECT COUNT(*) as total FROM Reservation";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        die("Error fetching total reservations: " . $e->getMessage());
    }
}
    

    // cancel reservation

    public static function cancelReservation($con, $reservation_id)
    {
        try {
            $query = "UPDATE reservation SET reservation_status = 'cancelled' WHERE reservation_id = :reservation_id";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error canceling reservation: " . $e->getMessage());
        }
    }

    public static function getReservationById($con, $reservationId)
    {
        try {
            $query = "
                SELECT reservation.*, customer.full_name, room.room_id
                FROM Reservation
                JOIN Customer ON reservation.customer_id = customer.customer_id
                JOIN Room ON reservation.room_id = room.room_id
                WHERE reservation.reservation_id = ?
                ORDER BY reservation.created_at DESC
            ";
            $stmt = $con->prepare($query);
            $stmt->execute([$reservationId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error fetching reservation by ID: " . $e->getMessage());
        }
    }

    public static function getAllReservationsByRoomId($con, $room_id) {
        try {
            $query = "
                SELECT reservation.*
                FROM Reservation
                WHERE room_id = ?
            ";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $room_id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Return an empty array if no reservations found
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            // You can log the error here instead of dying
            error_log("Error fetching reservations by room ID: " . $e->getMessage());
            return []; // Return an empty array on error
            }}

    public static function cancelAndMoveToCancellation($con, $id, $reservationId, $cancellationReason) {
        try {
            // Start a transaction
            $con->beginTransaction();
    
            // Fetch reservation data first to insert into Cancellation table
            $reservationDataQuery = "SELECT * FROM Reservation WHERE reservation_id = ?";
            $stmtFetch = $con->prepare($reservationDataQuery);
            $stmtFetch->execute([$reservationId]);
            $reservationData = $stmtFetch->fetch(PDO::FETCH_ASSOC);
    
            // If the reservation data exists, insert into the Cancellation table
            if ($reservationData) {
                $insertCancellationQuery = "
                    INSERT INTO Cancellation 
                    (reservation_id, customer_id, room_id, check_in_date, check_out_date, number_of_adult, number_of_children, number_of_room, total_price, payment_status, cancellation_reason) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmtInsert = $con->prepare($insertCancellationQuery);
                $success = $stmtInsert->execute([
                    $reservationData['reservation_id'],
                    $reservationData['customer_id'],
                    $reservationData['room_id'],
                    $reservationData['check_in_date'],
                    $reservationData['check_out_date'],
                    $reservationData['number_of_adult'],
                    $reservationData['number_of_children'],
                    $reservationData['number_of_room'],
                    $reservationData['total_price'],
                    $reservationData['payment_status'],
                    $cancellationReason
                ]);
    
                // Only proceed to delete if the insert into Cancellation was successful
                if ($success) {
                    // Step 1: Fetch and delete related entries from reservedroomtypeid
                    $queryFetchRelatedRoom = "SELECT id FROM reservedroomtypeid WHERE reservation_id = ?";
                    $stmtFetchRelatedRoom = $con->prepare($queryFetchRelatedRoom);
                    $stmtFetchRelatedRoom->bindValue(1, $reservationId);
                    $stmtFetchRelatedRoom->execute();
                    $relatedRoomIds = $stmtFetchRelatedRoom->fetchAll(PDO::FETCH_COLUMN);
    
                    if (!empty($relatedRoomIds)) {
                        $queryDeleteRelatedRoom = "DELETE FROM reservedroomtypeid WHERE reservation_id = ?";
                        $stmtDeleteRelatedRoom = $con->prepare($queryDeleteRelatedRoom);
                        $stmtDeleteRelatedRoom->bindValue(1, $reservationId);
                        $stmtDeleteRelatedRoom->execute();
                    }
    
                    // Step 2: Fetch and delete related entries from eventcustomizations
                    $queryFetchRelatedCustomization = "SELECT customization_id  FROM eventcustomizations WHERE reservation_id = ?";
                    $stmtFetchRelatedCustomization = $con->prepare($queryFetchRelatedCustomization);
                    $stmtFetchRelatedCustomization->bindValue(1, $reservationId);
                    $stmtFetchRelatedCustomization->execute();
                    $relatedCustomizationIds = $stmtFetchRelatedCustomization->fetchAll(PDO::FETCH_COLUMN);
    
                    if (!empty($relatedCustomizationIds)) {
                        $queryDeleteRelatedCustomization = "DELETE FROM eventcustomizations WHERE reservation_id = ?";
                        $stmtDeleteRelatedCustomization = $con->prepare($queryDeleteRelatedCustomization);
                        $stmtDeleteRelatedCustomization->bindValue(1, $reservationId);
                        $stmtDeleteRelatedCustomization->execute();
                    }
    
                    // Step 3: After deleting related records, delete the reservation itself
                    $deleteReservationQuery = "DELETE FROM Reservation WHERE reservation_id = ?";
                    $stmtDelete = $con->prepare($deleteReservationQuery);
                    $stmtDelete->bindValue(1, $reservationId);
                    $stmtDelete->execute();
    
                    // Commit the transaction after all operations succeed
                    $con->commit();
                    return true;
                } else {
                    // Rollback and return error if insertion to Cancellation fails
                    $con->rollBack();
                    throw new Exception("Failed to insert into Cancellation table. Reservation was not deleted.");
                }
            } else {
                throw new Exception("Reservation not found.");
            }
        } catch (PDOException $e) {
            // Roll back the transaction if something fails
            $con->rollBack();
            die("Error canceling reservation and moving to cancellation: " . $e->getMessage());
        } catch (Exception $e) {
            $con->rollBack();
            die($e->getMessage());
        }
    }
    
    
    
}
?>
