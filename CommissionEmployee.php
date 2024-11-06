<?php

require_once 'Employee.php';

class CommissionEmployee extends Employee {
    private float $regularSalary;
    private int $itemSold;
    private float $commission_rate;

    public function __construct(
        string $name, 
        string $address, 
        int $age, 
        string $companyName, 
        float $regularSalary, 
        int $itemSold, 
        float $commission_rate
    ) {
        parent::__construct($name, $address, $age, $companyName);
        
        if ($regularSalary < 0) {
            throw new InvalidArgumentException("Regular salary cannot be negative");
        }
        if ($itemSold < 0) {
            throw new InvalidArgumentException("Items sold cannot be negative");
        }
        if ($commission_rate < 0 || $commission_rate > 100) {
            throw new InvalidArgumentException("Commission rate must be between 0 and 100");
        }

        $this->regularSalary = $regularSalary;
        $this->itemSold = $itemSold;
        $this->commission_rate = $commission_rate / 100;
    }

    public function getRegularSalary() {
        return $this->regularSalary;
    }

    public function getItemSold() {
        return $this->itemSold;
    }

    public function getCommissionRate() {
        return $this->commission_rate;
    }

    public function setRegularSalary(float $regularSalary): void {
        if ($regularSalary < 0) {
            throw new InvalidArgumentException("Regular salary cannot be negative");
        }
        $this->regularSalary = $regularSalary;
    }

    public function setItemSold(int $itemSold): void {
        if ($itemSold < 0) {
            throw new InvalidArgumentException("Items sold cannot be negative");
        }
        $this->itemSold = $itemSold;
    }

    public function setCommissionRate(float $commission_rate): void {
        if ($commission_rate < 0 || $commission_rate > 100) {
            throw new InvalidArgumentException("Commission rate must be between 0 and 100");
        }
        $this->commission_rate = $commission_rate / 100;
    }

    public function earnings(): float {
        return $this->regularSalary + ($this->itemSold * $this->commission_rate);
    }

    protected function getEmployeeType(): string {
        return "Commission Employee";
    }

    protected function getAdditionalInfo(): string {
        return sprintf(
            "\nRegular Salary: %.2f\nItems Sold: %d\nCommission Rate: %.1f%%",
            $this->regularSalary,
            $this->itemSold,
            $this->commission_rate * 100
        );
    }
}
?>