<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=32)
     */
    private $beacon;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $qrCode;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastRefresh;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function getId()
    {
        return $this->id;
    }

    public function getBeacon(): ?int
    {
        return $this->beacon;
    }

    public function setBeacon(int $beacon): self
    {
        $this->beacon = $beacon;

        return $this;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(?string $qrCode): self
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getLastRefresh(): ?\DateTime
    {
        return $this->lastRefresh;
    }

    public function setLastRefresh(\DateTime $lastRefresh): self
    {
        $this->lastRefresh = $lastRefresh;

        return $this;
    }
}
