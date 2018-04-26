<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private $Beacon_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $Beacon_max;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $QRCode;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="Location")
     */
    private $Events;

    public function __construct()
    {
        $this->Events = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBeaconMin(): ?int
    {
        return $this->Beacon_min;
    }

    public function setBeaconMin(int $Beacon_min): self
    {
        $this->Beacon_min = $Beacon_min;

        return $this;
    }

    public function getBeaconMax(): ?int
    {
        return $this->Beacon_max;
    }

    public function setBeaconMax(int $Beacon_max): self
    {
        $this->Beacon_max = $Beacon_max;

        return $this;
    }

    public function getQRCode(): ?string
    {
        return $this->QRCode;
    }

    public function setQRCode(string $QRCode): self
    {
        $this->QRCode = $QRCode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->Events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->Events->contains($event)) {
            $this->Events[] = $event;
            $event->setLocation($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->Events->contains($event)) {
            $this->Events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getLocation() === $this) {
                $event->setLocation(null);
            }
        }

        return $this;
    }
}
