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
     * @ORM\Column(type="integer")
     */
    private $beaconMin;

    /**
     * @ORM\Column(type="integer")
     */
    private $beaconMax;

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

    public function getBeaconMin(): ?int
    {
        return $this->beaconMin;
    }

    public function setBeaconMin(int $beaconMin): self
    {
        $this->beaconMin = $beaconMin;

        return $this;
    }

    public function getBeaconMax(): ?int
    {
        return $this->beaconMax;
    }

    public function setBeaconMax(int $beaconMax): self
    {
        $this->beaconMax = $beaconMax;

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
