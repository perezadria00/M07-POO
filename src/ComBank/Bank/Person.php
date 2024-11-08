<?php

namespace ComBank\Bank;

class Person {

    private $name;
    private $idCard;
    private $email;

    public function __construct($name, $idCard, $email) {
        $this->name = $name;
        $this->idCard = $idCard;
        $this->email = $email;
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
}
