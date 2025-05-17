<?php
require_once __DIR__ . '/../connection.php';

/**
 * Utility functions for the Kebele Service Management System
 */

// Use the global database connection
global $database;

/**
 * Check if a date is a weekend (Saturday or Sunday)
 * @param string $date Date in Y-m-d format
 * @return bool True if weekend, false otherwise
 */
function isWeekend($date) {
    $dayOfWeek = date('N', strtotime($date));
    return ($dayOfWeek >= 6); // 6 = Saturday, 7 = Sunday
}

/**
 * Get available time slots for a given date
 * @param string $date Date in Y-m-d format
 * @param int $serviceId Service ID to check availability for
 * @return array Array of available time slots
 */
function getAvailableTimeSlots($date, $serviceId) {
    global $database;
    
    // Get all time slots
    $slots = [];
    $query = "SELECT slot_id, start_time, end_time FROM time_slots WHERE is_active = 1";
    $result = $database->query($query);
    
    while ($row = $result->fetch_assoc()) {
        // Check if slot is available (less than 3 appointments)
        $countQuery = "SELECT COUNT(*) as count FROM appointment 
                      WHERE appodate = ? AND scheduleid = ? AND status != 'cancelled'";
        $stmt = $database->prepare($countQuery);
        $stmt->bind_param("si", $date, $row['slot_id']);
        $stmt->execute();
        $countResult = $stmt->get_result();
        $count = $countResult->fetch_assoc()['count'];
        
        if ($count < 3) {
            $slots[] = [
                'slot_id' => $row['slot_id'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'available' => 3 - $count
            ];
        }
    }
    
    return $slots;
}

/**
 * Check if a resident can book a service
 * @param int $residentId Resident ID
 * @param int $serviceId Service ID
 * @param string $date Date in Y-m-d format
 * @param int $slotId Time slot ID
 * @return array Array with 'can_book' boolean and 'message' string
 */
function canBookService($residentId, $serviceId, $date, $slotId) {
    global $database;
    
    // Check if resident already has this service booked
    $query = "SELECT COUNT(*) as count FROM appointment 
              WHERE resid = ? AND service_id = ? AND status != 'cancelled'";
    $stmt = $database->prepare($query);
    $stmt->bind_param("ii", $residentId, $serviceId);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    
    if ($count > 0) {
        return [
            'can_book' => false,
            'message' => 'You have already booked this service'
        ];
    }
    
    // Check if resident has any other service in the same time slot
    $query = "SELECT COUNT(*) as count FROM appointment 
              WHERE resid = ? AND appodate = ? AND scheduleid = ? AND status != 'cancelled'";
    $stmt = $database->prepare($query);
    $stmt->bind_param("isi", $residentId, $date, $slotId);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    
    if ($count > 0) {
        return [
            'can_book' => false,
            'message' => 'You already have an appointment in this time slot'
        ];
    }
    
    return [
        'can_book' => true,
        'message' => 'Service can be booked'
    ];
}

/**
 * Get available staff for a service and time slot
 * @param int $serviceId Service ID
 * @param string $date Date in Y-m-d format
 * @param int $slotId Time slot ID
 * @return array Array of available staff members
 */
function getAvailableStaff($serviceId, $date, $slotId) {
    global $database;
    
    // Get staff assigned to this service
    $query = "SELECT s.stuid, s.stuname 
              FROM staff s 
              JOIN staff_service ss ON s.stuid = ss.staff_id 
              WHERE ss.service_id = ? AND ss.is_primary = 1";
    $stmt = $database->prepare($query);
    $stmt->bind_param("i", $serviceId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $availableStaff = [];
    while ($staff = $result->fetch_assoc()) {
        // Check if staff member has any appointments in this time slot
        $checkQuery = "SELECT COUNT(*) as count FROM appointment 
                      WHERE staff_id = ? AND appodate = ? AND scheduleid = ? AND status != 'cancelled'";
        $checkStmt = $database->prepare($checkQuery);
        $checkStmt->bind_param("isi", $staff['stuid'], $date, $slotId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $count = $checkResult->fetch_assoc()['count'];
        
        if ($count == 0) {
            $availableStaff[] = $staff;
        }
    }
    
    return $availableStaff;
}

/**
 * Create a new appointment
 * @param int $residentId Resident ID
 * @param int $serviceId Service ID
 * @param string $date Date in Y-m-d format
 * @param int $slotId Time slot ID
 * @param int|null $staffId Staff ID (optional, will auto-assign if null)
 * @return array Array with 'success' boolean and 'message' string
 */
function createAppointment($residentId, $serviceId, $date, $slotId, $staffId = null) {
    global $database;
    
    // Validate booking
    $canBook = canBookService($residentId, $serviceId, $date, $slotId);
    if (!$canBook['can_book']) {
        return [
            'success' => false,
            'message' => $canBook['message']
        ];
    }
    
    // If no staff specified, get available staff
    if ($staffId === null) {
        $availableStaff = getAvailableStaff($serviceId, $date, $slotId);
        if (empty($availableStaff)) {
            return [
                'success' => false,
                'message' => 'No staff available for this time slot'
            ];
        }
        $staffId = $availableStaff[0]['stuid']; // Assign first available staff
    }
    
    // Create appointment
    $query = "INSERT INTO appointment (resid, service_id, staff_id, appodate, scheduleid, status) 
              VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = $database->prepare($query);
    $stmt->bind_param("iiisi", $residentId, $serviceId, $staffId, $date, $slotId);
    
    if ($stmt->execute()) {
        return [
            'success' => true,
            'message' => 'Appointment created successfully',
            'appointment_id' => $stmt->insert_id
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Failed to create appointment: ' . $database->error
        ];
    }
}

/**
 * Get Ethiopian date (assuming Ethiopian calendar starts from 2017-09-25)
 * @param string $gregorianDate Date in Y-m-d format
 * @return string Ethiopian date in Y-m-d format
 */
function getEthiopianDate($gregorianDate) {
    // This is a simplified version. In production, use a proper Ethiopian calendar library
    $startDate = new DateTime('2017-09-25');
    $currentDate = new DateTime($gregorianDate);
    $interval = $startDate->diff($currentDate);
    
    // Convert to Ethiopian date (simplified)
    $ethiopianYear = 2017 + floor($interval->days / 365);
    $ethiopianMonth = 1 + floor(($interval->days % 365) / 30);
    $ethiopianDay = 1 + ($interval->days % 30);
    
    return sprintf('%04d-%02d-%02d', $ethiopianYear, $ethiopianMonth, $ethiopianDay);
}

/**
 * Format time for display
 * @param string $time Time in H:i:s format
 * @return string Formatted time (e.g., "2:30 AM")
 */
function formatTime($time) {
    return date('g:i A', strtotime($time));
}

/**
 * Get service details
 * @param int $serviceId Service ID
 * @return array|null Service details or null if not found
 */
function getServiceDetails($serviceId) {
    global $database;
    
    $query = "SELECT * FROM services WHERE service_id = ?";
    $stmt = $database->prepare($query);
    $stmt->bind_param("i", $serviceId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Get all active services
 * @return array Array of active services
 */
function getAllServices() {
    global $database;
    
    $query = "SELECT * FROM services WHERE is_active = 1 ORDER BY category, name";
    $result = $database->query($query);
    
    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    
    return $services;
}

/**
 * Get services by category
 * @param string $category Service category
 * @return array Array of services in the category
 */
function getServicesByCategory($category) {
    global $database;
    
    $query = "SELECT * FROM services WHERE category = ? AND is_active = 1 ORDER BY name";
    $stmt = $database->prepare($query);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    
    return $services;
} 