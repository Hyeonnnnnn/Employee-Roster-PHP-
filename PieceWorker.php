<?php

require_once 'Employee.php';

class PieceWorker extends Employee {
    private $numberItems;
    private $wagePerItem;

    public function __construct($name, $address, $age, $companyName, $numberItems, $wagePerItem) {
        parent::__construct($name, $address, $age, $companyName);
        $this->numberItems = $numberItems;
        $this->wagePerItem = $wagePerItem;
    }

    public function getNumberItems() {
        return $this->numberItems;
    }

    public function getWagePerItem() {
        return $this->wagePerItem;
    }

    public function setNumberItems($numberItems) {
        $this->numberItems = $numberItems;
    }

    public function setWagePerItem($wagePerItem) {
        $this->wagePerItem = $wagePerItem;
    }

    public function earnings() {
        return $this->numberItems * $this->wagePerItem;
    }

    protected function getEmployeeType(): string {
        return "Piece Worker";
    }

    protected function getAdditionalInfo(): string {
        return sprintf(
            "\nNumber of Items: %d\nWage per Item: %.2f",
            $this->numberItems,
            $this->wagePerItem
        );
    }
}
?>