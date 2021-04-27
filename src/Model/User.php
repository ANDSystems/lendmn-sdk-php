<?php

namespace AndSystems\Lendmn\Model;

class User
{
    protected $lendmnId;

    protected $firstName;

    protected $lastName;

    protected $phoneNumber;

    protected $email;

    /**
     * @return string
     */
    public function getLendmnId()
    {
        return $this->lendmnId;
    }

    public function setLendmnId($id)
    {
        $this->lendmnId = $id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($name)
    {
        $this->firstName = $name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($name)
    {
        $this->lastName = $name;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
}
