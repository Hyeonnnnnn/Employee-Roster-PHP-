<?php

class EmployeeRoster {
    private $rosterSize;
    private $availableSlots;
    private $employees = [];
    private $nextEmployeeNumber = 1;

    public function __construct($size) {
        $this->rosterSize = $size;
        $this->availableSlots = $size;
        $this->employees = array_fill(0, $size, null);
    }

    public function add($employee) {
        if ($this->availableSlots > 0) {
            for ($i = 0; $i < $this->rosterSize; $i++) {
                if ($this->employees[$i] === null) {
                    $employee->setEmployeeNumber($i + 1);
                    $this->employees[$i] = $employee;
                    $this->updateAvailableSlots();
                    break;
                }
            }
        } else {
            echo "No available slots to add a new employee.\n";
        }
    }

    public function updateAvailableSlots() {
        $this->availableSlots = count(array_filter($this->employees, fn($e) => $e === null));
    }

    public function remove($employeeNumber) {
        if (!is_numeric($employeeNumber)) {
            echo "Invalid input. Please enter a number.\n";
            return false;
        }

        $employeeNumber = (int)$employeeNumber;
        
        // Check if the number is greater than roster size
        if ($employeeNumber > $this->rosterSize) {
            echo "Invalid employee number.\n";
            return false;
        }

        // Check if the number is less than or equal to 0
        if ($employeeNumber <= 0) {
            echo "Invalid employee number.\n";
            return false;
        }

        $index = $employeeNumber - 1;

        // Check if the slot is vacant
        if ($this->employees[$index] === null) {
            echo "This slot is currently vacant.\n";
            return false;
        }

        // Remove the employee
        $this->employees[$index] = null;
        $this->updateAvailableSlots();
        echo "Employee #" . $employeeNumber . " has been removed from the roster.\n";
        return true;
    }

    public function count() {
        return $this->getRosterSize() - $this->getAvailableSlots();
    }

    // Update count methods to be consistent
    public function countCE() {
        return count(array_filter($this->employees, fn($e) => $e instanceof CommissionEmployee));
    }

    public function countHE() {
        return count(array_filter($this->employees, fn($e) => $e instanceof HourlyEmployee));
    }

    public function countPE() {
        return count(array_filter($this->employees, fn($e) => $e instanceof PieceWorker));
    }

    public function display() {
        if (empty($this->employees)) {
            echo "There are currently no employees.\n";
            return;
        }
        foreach ($this->employees as $employee) {
            if ($employee !== null) {
                echo $employee->getBasicInfo() . "\n";
                echo "--------------------------\n";
            }
        }
    }

    public function displayCE() {
        $found = false;
        foreach ($this->employees as $employee) {
            if ($employee instanceof CommissionEmployee) {
                $found = true;
                echo $employee->getBasicInfo() . "\n";
                echo "--------------------------\n";
            }
        }
        if (!$found) {
            echo "There are currently no commission employees.\n";
        }
    }

    public function displayHE() {
        $found = false;
        foreach ($this->employees as $employee) {
            if ($employee instanceof HourlyEmployee) {
                $found = true;
                echo $employee->getBasicInfo() . "\n";
                echo "--------------------------\n";
            }
        }
        if (!$found) {
            echo "There are currently no hourly employees.\n";
        }
    }

    public function displayPE() {
        $found = false;
        foreach ($this->employees as $employee) {
            if ($employee instanceof PieceWorker) {
                $found = true;
                echo $employee->getBasicInfo() . "\n";
                echo "--------------------------\n";
            }
        }
        if (!$found) {
            echo "There are currently no piece workers.\n";
        }
    }

    public function payroll() {
        foreach ($this->employees as $employee) {
            if ($employee !== null) {
                echo $employee . "\nEarnings: " . $employee->earnings() . "\n";
                echo "--------------------------\n";
            }
        }
    }

    public function getRosterSize() {
        return count($this->employees);
    }

    public function getAvailableSlots() {
        return $this->availableSlots;
    }

    public function displayForDeletion() {
        if ($this->count() === 0) {
            echo "There are currently no employees.\n";
            return;
        }
        foreach ($this->employees as $index => $employee) {
            if ($employee !== null) {
                // Only show the employee number here, then show the rest of the info
                echo "Employee #" . ($index + 1) . "\n";
                echo "Name: " . $employee->getName() . "\n";
                echo "Address: " . $employee->getAddress() . "\n";
                echo "Age: " . $employee->getAge() . "\n";
                echo "Company Name: " . $employee->getCompanyName() . "\n";
                echo "Type: " . $this->getEmployeeType($employee) . "\n";
                echo "--------------------------\n";
            }
        }
    }

    private function getEmployeeType($employee) {
        if ($employee instanceof CommissionEmployee) {
            return "Commission Employee";
        } elseif ($employee instanceof HourlyEmployee) {
            return "Hourly Employee";
        } elseif ($employee instanceof PieceWorker) {
            return "Piece Worker";
        }
        return "Unknown";
    }
}
?>