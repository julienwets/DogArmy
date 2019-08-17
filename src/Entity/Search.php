<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints\DateTime;

class Search
{

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $homeType;


    /**
     * @var array|null
     */
    private $homeDetails = [];

    /**
     * @var string|null
     */
    private $duringWork;

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function getHomeType()
    {
        return $this->homeType;
    }

    function setHomeType($homeType)
    {
        $this->homeType = $homeType;
    }


    public function getHomeDetails(): ?array
    {
        return $this->homeDetails;
    }

    public function setHomeDetails(?array $homeDetails): self
    {
        $this->homeDetails = $homeDetails;

        return $this;
    }

    function getDuringWork()
    {
        return $this->duringWork;
    }

    function setDuringWork($duringWork)
    {
        $this->duringWork = $duringWork;
    }
}
