<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SoftPassio\Components\Doctrine\EntityInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="App\Repository\DateKeyRepository")
 */
class DateKey implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Serializer\Expose()
     * @Serializer\Groups({"List"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Expose()
     * @Serializer\Groups({"List"})
     */
    private $generatedKey;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getGeneratedKey(): ?string
    {
        return $this->generatedKey;
    }

    public function setGeneratedKey(string $generatedKey): self
    {
        $this->generatedKey = $generatedKey;

        return $this;
    }
}
