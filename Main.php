<?php

require_once 'EmployeeRoster.php';
require_once 'CommissionEmployee.php';
require_once 'HourlyEmployee.php';
require_once 'PieceWorker.php';

class Main {

    private EmployeeRoster $roster;
    private $size;
    private $repeat;

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
            $this->clear();
            $this->menu();
            $choice = readline("Select Menu: ");

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
        $this->clear();
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
        $this->clear();
        if ($this->roster->getRosterSize() - $this->roster->getAvailableSlots() === 0) {
            echo "There are currently no employees.\n";
            readline("Press \"Enter\" key to continue...");
            return;
        }
        
        while (true) {
            $this->clear();
            echo "***List of Employees on the current Roster***\n";
            $this->roster->displayForDeletion();
            
            echo "\nPress [0] to return\n";
            $employeeNumber = readline("Enter employee number to delete: ");
            
            if ($employeeNumber === '0') {
                return;
            }
            
            if ($this->roster->remove($employeeNumber)) {
                readline("Press \"Enter\" key to continue...");
            } else {
                readline("Press \"Enter\" key to continue...");
            }
            
            if ($this->roster->getRosterSize() - $this->roster->getAvailableSlots() === 0) {
                echo "There are currently no employees.\n";
                readline("Press \"Enter\" key to continue...");
                return;
            }
        }
    }

    public function otherMenu() {
        $this->clear();
        echo "[1] Display\n";
        echo "[2] Count\n";
        echo "[3] Payroll\n";
        echo "[0] Return\n";
        $choice = readline("Select Menu: ");

        switch ($choice) {
            case 1:
                $this->clear();
                echo "Roster size: " . $this->roster->getRosterSize() . PHP_EOL;
                echo "Available slots: " . $this->roster->getAvailableSlots() . PHP_EOL;
                echo "*** EMPLOYEE ROSTER MENU ***" . PHP_EOL;
                echo "[1] Display All Employee\n";
                echo "[2] Display Commission Employee\n";
                echo "[3] Display Hourly Employee\n";
                echo "[4] Display Piece Worker\n";
                echo "[0] Return\n";
                $displayChoice = readline("Select Menu: ");

                switch ($displayChoice) {
                    case 0:
                        $this->otherMenu(); // Return to other menu
                        break;
                    case 1:
                        if ($this->roster->getRosterSize() - $this->roster->getAvailableSlots() === 0) {
                            echo "There are currently no employees.\n";
                        } else {
                            $this->roster->display();
                        }
                        break;
                    case 2:
                        if ($this->roster->countCE() === 0) {
                            echo "There are currently no employees.\n";
                        } else {
                            $this->roster->displayCE();
                        }
                        break;
                    case 3:
                        if ($this->roster->countHE() === 0) {
                            echo "There are currently no employees.\n";
                        } else {
                            $this->roster->displayHE();
                        }
                        break;
                    case 4:
                        if ($this->roster->countPE() === 0) {
                            echo "There are currently no employees.\n";
                        } else {
                            $this->roster->displayPE();
                        }
                        break;
                    default:
                        echo "Invalid Input!";
                        break;
                }
                readline("\nPress \"Enter\" key to continue...");
                break;
            case 2:
                $this->clear();
                echo "[1] Count All Employee\n";
                echo "[2] Count Commission Employee\n";
                echo "[3] Count Hourly Employee\n";
                echo "[4] Count Piece Worker\n";
                echo "[0] Return\n";
                $countChoice = readline("Select Menu: ");

                switch ($countChoice) {
                    case 0:
                        $this->otherMenu(); // Return to other menu
                        break;
                    case 1:
                        echo "Total Employees: " . $this->roster->count() . "\n";
                        break;
                    case 2:
                        echo "Total Commission Employees: " . $this->roster->countCE() . "\n";
                        break;
                    case 3:
                        echo "Total Hourly Employees: " . $this->roster->countHE() . "\n";
                        break;
                    case 4:
                        echo "Total Piece Workers: " . $this->roster->countPE() . "\n";
                        break;
                    default:
                        echo "Invalid Input!";
                        break;
                }
                readline("\nPress \"Enter\" key to continue...");
                break;
            case 3:
                if ($this->roster->getRosterSize() - $this->roster->getAvailableSlots() === 0) {
                    echo "There are currently no employees.\n";
                } else {
                    $this->roster->payroll();
                }
                readline("Press \"Enter\" key to continue...");
                break;
            case 0:
                break;
            default:
                echo "Invalid input. Please try again.\n";
                readline("Press \"Enter\" key to continue...");
                $this->otherMenu();
                break;
        }
    }

    public function clear() {
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            for ($i = 0; $i < 50; $i++) {
                echo "\n";
            }
        } else {
            system('clear');
        }
    }

    public function clearScreen() {
        $this->clear();
    }

    public function repeat() {
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