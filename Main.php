<?php

require_once 'EmployeeRoster.php';
require_once 'CommissionEmployee.php';
require_once 'HourlyEmployee.php';
require_once 'PieceWorker.php';

class Main {

    private EmployeeRoster $roster;
    private $size;
    private $repeat;

    private function clearScreen() {
        echo "\033[2J\033[;H";  // ANSI escape sequence to clear screen and move cursor to top
    }

    public function start() {
        $this->clearScreen();
        $this->repeat = true;

        $this->size = readline("Enter the size of the roster: ");

        if ($this->size < 1) {
            echo "Invalid input. Please try again.\n";
            readline("Press \"Enter\" key to continue...");
            $this->start();
        } else {
            $this->roster = new EmployeeRoster($this->size);
            $this->entrance();
        }
    }

    public function entrance() {
        $choice = 0;

        while ($this->repeat) {
            $this->clearScreen();
            $this->menu();
            $choice = readline("Select Menu: ");
            $this->clearScreen();  // Clear before showing next screen

            switch ($choice) {
                case 1:
                    $this->addMenu();
                    break;
                case 2:
                    $this->deleteMenu();
                    break;
                case 3:
                    $this->otherMenu();
                    break;
                case 0:
                    $this->repeat = false;
                    break;
                default:
                    echo "Invalid input. Please try again.\n";
                    readline("Press \"Enter\" key to continue...");
                    break;
            }
        }
        echo "Process terminated.\n";
        exit;
    }

    public function menu() {
        echo "Roster size: " . $this->size . PHP_EOL; 
        echo "Available slots: " . $this->roster->getAvailableSlots() . PHP_EOL;
        echo "*** EMPLOYEE ROSTER MENU ***\n";
        echo "[1] Add Employee\n";
        echo "[2] Delete Employee\n";
        echo "[3] Other Menu\n";
        echo "[0] Exit\n";
    }

    public function addMenu() {
        if ($this->roster->getAvailableSlots() === 0) {
            echo "Currently, our roster is full.\n";
            readline("Press \"Enter\" key to continue...");
            return;
        }

        $name = $this->readString("Enter name: ");
        $address = $this->readString("Enter address: ");
        $age = $this->readInt("Enter age: ");
        $cName = $this->readString("Enter company name: ");
        $this->empType($name, $address, $age, $cName);
    }

    public function empType($name, $address, $age, $cName) {
        $this->clearScreen();
        echo "---Employee Details \n";
        echo "Enter name: $name\n";
        echo "Enter address: $address\n";
        echo "Enter company name: $cName\n";
        echo "Enter age: $age\n";
        echo "[1] Commission Employee       [2] Hourly Employee       [3] Piece Worker\n";
        $type = $this->readInt("Type of Employee: ");

        switch ($type) {
            case 1:
                $regularSalary = $this->readInt("Enter regular salary: ");
                $itemSold = $this->readInt("Enter items sold: ");
                $commission_rate = $this->readPercentage("Enter commission rate (in %): ");
                $employee = new CommissionEmployee($name, $address, $age, $cName, $regularSalary, $itemSold, $commission_rate);
                $this->roster->add($employee);
                $this->repeat();
                break;
            case 2:
                $hoursWorked = $this->readInt("Enter hours worked: ");
                $rate = $this->readInt("Enter rate: ");
                $employee = new HourlyEmployee($name, $address, $age, $cName, $hoursWorked, $rate);
                $this->roster->add($employee);
                $this->repeat();
                break;
            case 3:
                $numberItems = $this->readInt("Enter number of items: ");
                $wagePerItem = $this->readInt("Enter wage per item: ");
                $employee = new PieceWorker($name, $address, $age, $cName, $numberItems, $wagePerItem);
                $this->roster->add($employee);
                $this->repeat();
                break;
            default:
                echo "Invalid input. Please try again.\n";
                readline("Press \"Enter\" key to continue...");
                $this->empType($name, $address, $age, $cName);
                break;
        }
    }

    private function readString($prompt) {
        do {
            $input = readline($prompt);
            if (preg_match("/^[a-zA-Z\s]+$/", $input)) {
                return $input;
            } else {
                echo "Invalid input. Please enter letters only.\n";
            }
        } while (true);
    }

    private function readInt($prompt) {
        do {
            $input = readline($prompt);
            if (filter_var($input, FILTER_VALIDATE_INT) !== false) {
                return (int)$input;
            } else {
                echo "Invalid input. Please enter an integer.\n";
            }
        } while (true);
    }

    private function readPercentage($prompt) {
        do {
            $input = readline($prompt);
            if (filter_var($input, FILTER_VALIDATE_FLOAT) !== false && $input >= 0 && $input <= 100) {
                return (float)$input;
            } else {
                echo "Invalid input. Please enter a percentage between 0 and 100.\n";
            }
        } while (true);
    }

    public function deleteMenu() {
        $this->clearScreen();
        if ($this->roster->getRosterSize() - $this->roster->getAvailableSlots() === 0) {
            echo "There are currently no employees.\n";
            readline("Press \"Enter\" key to continue...");
            return;
        }
        
        while (true) {
            $this->clearScreen();
            echo "***List of Employees on the current Roster***\n";
            $this->roster->displayForDeletion();
            
            echo "\nPress [0] to return\n";
            $employeeNumber = readline("Enter employee number to delete: ");
            
            if ($employeeNumber === '0') {
                $this->clearScreen();
                return;
            }
            
            if ($this->roster->remove($employeeNumber)) {
                readline("Press \"Enter\" key to continue...");
                
                if ($this->roster->getRosterSize() - $this->roster->getAvailableSlots() === 0) {
                    $this->clearScreen();
                    echo "There are currently no employees.\n";
                    readline("Press \"Enter\" key to continue...");
                    return;
                }
            } else {
                readline("Press \"Enter\" key to continue...");
            }
        }
    }

    public function otherMenu() {
        $this->clearScreen();
        do {
            echo "[1] Display\n";
            echo "[2] Count\n";
            echo "[3] Payroll\n";
            echo "[0] Return\n";
            $choice = readline("Select Menu: ");

            switch ($choice) {
                case 1:
                    $this->displayMenu();
                    break;
                case 2:
                    $this->countMenu();
                    break;
                case 3:
                    $this->payrollMenu();
                    break;
                case 0:
                    return;
                default:
                    echo "Invalid input. Please try again.\n";
                    readline("Press \"Enter\" key to continue...");
                    break;
            }
            $this->clearScreen();
        } while (true);
    }

    private function displayMenu() {
        do {
            $this->clearScreen();
            echo "[1] Display All Employee\n";
            echo "[2] Display Commission Employee\n";
            echo "[3] Display Hourly Employee\n";
            echo "[4] Display Piece Worker\n";
            echo "[0] Return\n";
            $displayChoice = readline("Select Menu: ");

            $this->clearScreen();
            switch ($displayChoice) {
                case 0:
                    return;
                case 1:
                    if ($this->roster->getRosterSize() - $this->roster->getAvailableSlots() === 0) {
                        echo "There are currently no employees.\n";
                    } else {
                        $this->roster->display();
                    }
                    readline("Press \"Enter\" key to continue...");
                    break;
                case 2:
                    $this->clearScreen();
                    if ($this->roster->countCE() === 0) {
                        echo "There are currently no commission employees.\n";
                        readline("Press \"Enter\" key to continue...");
                    } else {
                        $this->roster->displayCE();
                        readline("Press \"Enter\" key to continue...");
                    }
                    break;
                case 3:
                    $this->clearScreen();
                    if ($this->roster->countHE() === 0) {
                        echo "There are currently no hourly employees.\n";
                        readline("Press \"Enter\" key to continue...");
                    } else {
                        $this->roster->displayHE();
                        readline("Press \"Enter\" key to continue...");
                    }
                    break;
                case 4:
                    $this->clearScreen();
                    if ($this->roster->countPE() === 0) {
                        echo "There are currently no piece workers.\n";
                        readline("Press \"Enter\" key to continue...");
                    } else {
                        $this->roster->displayPE();
                        readline("Press \"Enter\" key to continue...");
                    }
                    break;
                default:
                    echo "Invalid Input!\n";
                    readline("Press \"Enter\" key to continue...");
                    break;
            }
        } while (true);
    }

    private function countMenu() {
        do {
            $this->clearScreen();
            echo "[1] Count All Employee\n";
            echo "[2] Count Commission Employee\n";
            echo "[3] Count Hourly Employee\n";
            echo "[4] Count Piece Worker\n";
            echo "[0] Return\n";
            $countChoice = readline("Select Menu: ");

            $this->clearScreen();
            switch ($countChoice) {
                case 0:
                    return;
                case 1:
                    echo "Total Employees: " . $this->roster->count() . "\n";
                    readline("Press \"Enter\" key to continue...");
                    break;
                case 2:
                    echo "Total Commission Employees: " . $this->roster->countCE() . "\n";
                    readline("Press \"Enter\" key to continue...");
                    break;
                case 3:
                    echo "Total Hourly Employees: " . $this->roster->countHE() . "\n";
                    readline("Press \"Enter\" key to continue...");
                    break;
                case 4:
                    echo "Total Piece Workers: " . $this->roster->countPE() . "\n";
                    readline("Press \"Enter\" key to continue...");
                    break;
                default:
                    echo "Invalid Input!\n";
                    readline("Press \"Enter\" key to continue...");
                    break;
            }
        } while (true);
    }

    private function payrollMenu() {
        do {
            $this->clearScreen();
            if ($this->roster->getRosterSize() - $this->roster->getAvailableSlots() === 0) {
                echo "There are currently no employees.\n";
            } else {
                $this->roster->payroll();
            }
            echo "\n[0] Return\n";
            $choice = readline("Select Menu: ");
            
            if ($choice === '0') {
                return;
            }
        } while (true);
    }

    public function repeat() {
        $this->clearScreen();
        if ($this->roster->getRosterSize() - $this->roster->getAvailableSlots() < $this->size) {
            echo "Employee Added!\n";
            $this->roster->updateAvailableSlots(); // Ensure available slots are updated
            $c = readline("Add more ? (y to continue): ");
            if (strtolower($c) == 'y')
                $this->addMenu();
            else
                $this->entrance();
        } else {
            echo "Roster is Full\n";
            readline("Press \"Enter\" key to continue...");
            $this->entrance();
        }
    }

    function displayAllEmployees($employees) {
        if (empty($employees)) {
            echo "There are currently no employees.\n";
            return;
        }
    }

    function displayCommissionEmployees($employees) {
        $found = false;
        foreach ($employees as $index => $employee) {
            if ($employee instanceof CommissionEmployee) {
                if (!$found) $found = true;
                echo $employee->display($index + 1) . "\n";
                echo "------------------------\n";
            }
        }
        if (!$found) {
            echo "There are currently no employees.\n";
        }
    }

    function displayHourlyEmployees($employees) {
        $found = false;
        foreach ($employees as $index => $employee) {
            if ($employee instanceof HourlyEmployee) {
                if (!$found) $found = true;
                echo $employee->display($index + 1) . "\n";
                echo "------------------------\n";
            }
        }
        if (!$found) {
            echo "There are currently no employees.\n";
        }
    }

    function displayPieceWorkers($employees) {
        $found = false;
        foreach ($employees as $index => $employee) {
            if ($employee instanceof PieceWorker) {
                if (!$found) $found = true;
                echo $employee->display($index + 1) . "\n";
                echo "------------------------\n";
            }
        }
        if (!$found) {
            echo "There are currently no employees.\n";
        }
    }

    function deleteEmployee($employees) {
        if (empty($employees)) {
            echo "There are currently no employees.\n";
            return false;
        }
    }
}
?>