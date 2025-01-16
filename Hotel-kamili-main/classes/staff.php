<?php

namespace classes;

use PDO;
use PDOException;

class staff
{
    private $staff_id;
    private $first_name;
    private $last_name;
    private $user_name;
    private $password;
    private $nic_no;
    private $email;
    private $contact_no;
    private $role;
    private $token_hash;
    private $expiryTime;

    public function __construct($f_name, $l_name, $u_name, $pwd, $nic, $email, $contact_no, $role)
    {
        $this->first_name = $f_name;
        $this->last_name = $l_name;
        $this->user_name = $u_name;
        $this->password = $pwd;
        $this->nic_no = $nic;
        $this->email = $email;
        $this->contact_no = $contact_no;
        $this->role = $role;
    }

    public function getStaffId()
    {
        return $this->staff_id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getUserName()
    {
        return $this->user_name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getNicNo()
    {
        return $this->nic_no;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getContactNumber()
    {
        return $this->contact_no;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getToken_hash()
    {
        return $this->token_hash;
    }

    public function getExpiryTime()
    {
        return $this->expiryTime;
    }

    public function setStaffId($staff_id)
    {
        $this->staff_id = $staff_id;
    }

    public function setFirstName($f_name)
    {
        $this->first_name = $f_name;
    }

    public function setLastName($l_name)
    {
        $this->last_name = $l_name;
    }

    public function setUserName($u_name)
    {
        $this->user_name = $u_name;
    }

    public function setPassword($pwd)
    {
        $this->password = $pwd;
    }

    public function setNic($nic)
    {
        $this->nic_no = $nic;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setContactNo($contact_no)
    {
        $this->contact_no = $contact_no;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function setToken_hash($token_hash)
    {
        $this->token_hash = $token_hash;
    }

    public function setExpiryTime($expiryTime)
    {
        $this->expiryTime = $expiryTime;
    }

    public function register($con)
    {
        try {
            $query = "INSERT INTO staff(firstname, lastname, username, pwd, nic, email, contact_no, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $this->first_name);
            $pstmt->bindValue(2, $this->last_name);
            $pstmt->bindValue(3, $this->user_name);
            $pstmt->bindValue(4, $this->password);
            $pstmt->bindValue(5, $this->nic_no);
            $pstmt->bindValue(6, $this->email);
            $pstmt->bindValue(7, $this->contact_no);
            $pstmt->bindValue(8, $this->role);
            $pstmt->execute();

            return ($pstmt->rowCount() > 0);
        } catch (PDOException $exc) {
            die("Error in Staff register class" . $exc->getMessage());
        }
    }

    public function isUsernameUnique($user_name, $con)
    {
        $query = "SELECT COUNT(*) FROM staff WHERE username = ?";
        $pstmt = $con->prepare($query);
        $pstmt->execute([$user_name]);
        $count = $pstmt->fetchColumn();
        return $count == 0;
    }

    public function checkEmailAvailability($email, $con)
    {
        $query = "SELECT COUNT(*) FROM staff WHERE email = ?";
        $pstmt = $con->prepare($query);
        $pstmt->execute([$email]);
        $count = $pstmt->fetchColumn();
        return $count == 0;
    }

    public function authenticate($con)
    {
        try {
            $query = "SELECT * FROM Staff WHERE username = ?";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $this->user_name);
            $pstmt->execute();

            $rs = $pstmt->fetch(PDO::FETCH_OBJ);
            if (!empty($rs)) {
                $db_pwd = $rs->pwd;
                if (password_verify($this->password, $db_pwd)) {
                    $this->staff_id = $rs->staff_id;
                    $this->first_name = $rs->firstname;
                    $this->last_name = $rs->lastname;
                    $this->user_name = $rs->username;
                    $this->nic_no = $rs->nic;
                    $this->email = $rs->email;
                    $this->contact_no = $rs->contact_no;
                    $this->role = $rs->role;
                    $this->password = null;

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $exc) {
            die("Error in Staff authenticate class" . $exc->getMessage());
        }
    }

    public function setHashToken($con, $tokenValue, $expiryTime)
    {
        try {
            $reset_token_hash = $tokenValue;
            $reset_token_expires_at = $expiryTime;
            $query = "UPDATE Staff SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
            $pstmt = $con->prepare($query);
            $pstmt->bindParam(1, $reset_token_hash);
            $pstmt->bindParam(2, $reset_token_expires_at);
            $pstmt->bindParam(3, $this->email);
            $pstmt->execute();

            return ($pstmt->rowCount() > 0);
        } catch (PDOException $exc) {
            // Throw exception for handling at a higher level
            die("Error in Staff setHashToken method: " . $exc->getMessage());
        }
    }

    public function getAllStaffDetailsByToken($con, $token)
    {
        try {
            $reset_token = $token;
            $query = "SELECT * FROM Staff WHERE reset_token_hash = ?";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $reset_token);
            $pstmt->execute();

            $staffDetails = $pstmt->fetch(PDO::FETCH_ASSOC);

            if ($staffDetails) {
                $this->staff_id = $staffDetails['staff_id'];
                $this->first_name = $staffDetails['firstname'];
                $this->last_name = $staffDetails['lastname'];
                $this->user_name = $staffDetails['username'];
                $this->nic_no = $staffDetails['nic'];
                $this->email = $staffDetails['email'];
                $this->contact_no = $staffDetails['contact_no'];
                $this->role = $staffDetails['role'];
                $this->token_hash = $staffDetails['reset_token_hash'];
                $this->expiryTime = $staffDetails['reset_token_expires_at'];

                return $staffDetails;
            } else {
                return false;
            }
        } catch (PDOException $exc) {
            die("Error in Staff getAllStaffDetailsByToken method: " . $exc->getMessage());
        }
    }

    public function updateResetPassword($con, $password_hash, $staff_id)
    {
        try {
            $query = "UPDATE Staff SET pwd=?, reset_token_hash=NULL, reset_token_expires_at=NULL WHERE staff_id = ?";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $password_hash);
            $pstmt->bindValue(2, $staff_id);
            $pstmt->execute();

            return ($pstmt->rowCount() > 0);
        } catch (PDOException $exc) {
            die("Error in updateResetPassword function in staff class" . $exc->getMessage());
        }
    }

    public function getAllStaff($con)
    {
        try {
            $query = "SELECT * FROM staff ORDER BY created_at DESC"; //chnges done here
            $pstmt = $con->prepare($query);
            $pstmt->execute();
            return $pstmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exc) {
            die("Error fetching all staff: " . $exc->getMessage());
        }
    }

    public function getStaffById($con, $staff_id)
    {
        try {
            $query = "SELECT * FROM staff WHERE staff_id = ?";
            $pstmt = $con->prepare($query);
            $pstmt->execute([$staff_id]);
            $staffDetails = $pstmt->fetch(PDO::FETCH_ASSOC);

            if ($staffDetails) {
                $this->staff_id = $staffDetails['staff_id'];
                $this->first_name = $staffDetails['firstname'];
                $this->last_name = $staffDetails['lastname'];
                $this->user_name = $staffDetails['username'];
                $this->nic_no = $staffDetails['nic'];
                $this->email = $staffDetails['email'];
                $this->contact_no = $staffDetails['contact_no'];
                $this->role = $staffDetails['role'];

                return $staffDetails;
            } else {
                return false;
            }
        } catch (PDOException $exc) {
            die("Error fetching staff by ID: " . $exc->getMessage());
        }
    }

    public function updateStaff($con, $staff_id, $first_name, $last_name, $nic_no, $email, $contact_no, $role)
    {
        try {
            $query = "UPDATE staff SET firstname = ?, lastname = ?, nic = ?, email = ?, contact_no = ?, role = ? WHERE staff_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $first_name);
            $stmt->bindValue(2, $last_name);
            $stmt->bindValue(3, $nic_no);
            $stmt->bindValue(4, $email);
            $stmt->bindValue(5, $contact_no);
            $stmt->bindValue(6, $role);
            $stmt->bindValue(7, $staff_id);

            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                error_log("No rows affected");
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error updating staff: " . $e->getMessage());
            return false;
        }
    }


    // Delete a staff record
    public static function delete($con, $staff_id)
    {
        try {
            $query = "DELETE FROM staff WHERE staff_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $staff_id, PDO::PARAM_INT);
            $stmt->execute();
            return ($stmt->rowCount() > 0);
        } catch (PDOException $e) {
            error_log("Error deleting staff: " . $e->getMessage()); // Log the error
            return false;
        }
    }

    public function fetchNotifications($con, $staff_id)
    {
        try {
            // Query to fetch notifications for the specific staff member
            $query = "SELECT * FROM staff WHERE staff_id = ? ORDER BY created_at DESC";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $staff_id);
            $pstmt->execute();

            // Fetch all notifications
            $notifications = $pstmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the notifications
            return $notifications;
        } catch (PDOException $exc) {
            die("Error fetching notifications: " . $exc->getMessage());
        }
    }
}
