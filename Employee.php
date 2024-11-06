<?php

require_once 'Person.php';

abstract class Employee extends Person {
    private $companyName;
    private $employeeNumber;

    public function __construct($name, $address, $age, $companyName) {
        parent::__construct($name, $address, $age);
        $this->companyName = $companyName;
    }

    public function getCompanyName() {
        return $this->companyName;
    }

    public function setCompanyName($companyName) {
        $this->companyName = $companyName;
    }

    public function getEmployeeNumber() {
        return $this->employeeNumber;
    }

    public function setEmployeeNumber($number) {
        $this->employeeNumber = $number;
    }

    abstract public function earnings();

    public function __toString(): string {
        $basicInfo = $this->getBasicInfo();
        return $basicInfo . $this->getAdditionalInfo();
    }

    protected function getAdditionalInfo(): string {
        return "";
    }

    protected function getEmployeeType(): string {
        return "Regular Employee";
    }

    public function display(): string {
        return $this->__toString();
    }

    public function getBasicInfo(): string {
        return sprintf(
            "Employee #%d\nName: %s\nAddress: %s\nAge: %d\nCompany: %s\nType: %s",
            $this->employeeNumber,
            $this->getName(),
            $this->getAddress(),
            $this->getAge(),
            $this->companyName,
            $this->getEmployeeType()
        );
    }
}
?>