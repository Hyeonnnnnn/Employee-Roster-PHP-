<?php

class EmployeeRoster {
    private $rosterSize;
    private $availableSlots;
    private $employees = [];
    private $nextEmployeeNumber = 1;

    public function __construct($size) {
        $this->rosterSize = $size;
        $this->availableSlots = $size;
    }

    public function add($employee) {
        if ($this->availableSlots > 0) {
            $employee->setEmployeeNumber($this->nextEmployeeNumber++);
            $this->employees[] = $employee;
            $this->updateAvailableSlots();
        } else {
            echo "No available slots to add a new employee.\n";
        }
    }

    public function updateAvailableSlots() {
        $this->availableSlots = $this->rosterSize - count($this->employees);
    }

    public function remove($employeeNumber) {
        foreach ($this->employees as $key => $employee) {
            if ($employee->getEmployeeNumber() == $employeeNumber) {
                echo "Employee #" . $employee->getEmployeeNumber() . " has been removed from the roster.\n";
                unset($this->employees[$key]);
                $this->employees = array_values($this->employees); // Reindex array
                $this->reindexEmployeeNumbers();
                $this->updateAvailableSlots();
                return true;
            }
        }
        echo "Employee not found.\n";
        return false;
    }

    private function reindexEmployeeNumbers() {
        $number = 1;
        foreach ($this->employees as $employee) {
            $employee->setEmployeeNumber($number++);
        }
        $this->nextEmployeeNumber = $number;
    }

    public function count() {
        return count(array_filter($this->employees));
    }

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
}
?>