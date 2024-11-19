<?php

namespace ComBank\Bank;

use ComBank\Support\Traits\ApiTrait;  

class Person {

    private $name;
    private $idCard;
    private $email;

    public $phone_number;
    private $age;

  
    use ApiTrait;  

    public function __construct($name, $idCard, $email, $phone_number) {
        $this->name = $name;
        $this->idCard = $idCard;
        $this->email = $email;
        $this->phone_number = $phone_number;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getIdCard() {
        return $this->idCard;
    }

    public function setIdCard($idCard) {
        $this->idCard = $idCard;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getAge() {
        return $this->age;
    }

    public function setAge($age) : void {
        $this->age = $age;
    }
    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function setPhoneNumber($phone_number) : void {
        $this->phone_number = $phone_number;
    }

   
}

