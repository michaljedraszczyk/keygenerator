<?php

namespace App\Form\Model;

use DateTimeInterface;
use SoftPassio\Components\Form\Model\FormModelInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DateKeyDatesModel implements FormModelInterface
{
    /**
     * @Assert\NotBlank(message="Date from need to be set.")
     * @Assert\Date(message="Value need to be DateTime like string Y-m-d.")
     * @Assert\GreaterThanOrEqual("01-01-1950", message="Minimal date is 01-01-1950")
     */
    private $from;

    /**
     * @Assert\NotBlank(message="Date to need to be set.")
     * @Assert\Date(message="Value need to be DateTime like string Y-m-d.")
     * @Assert\LessThanOrEqual("31-12-2025", message="Maximal date is 31-12-2025")
     */
    private $to;

    /**
     * @Assert\NotBlank(message="Key template need to be defined.")
     * @Assert\Length(min=64, max=64, allowEmptyString=false, exactMessage="Key template need to have exacly {{ limit }} chars.")
     * @Assert\Type(type="alnum", message="Key template should have only alphanumeric chars.")
     */
    private $keyTemplate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getFrom(): ?\DateTimeInterface
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom(?DateTimeInterface $from): void
    {
        $this->from = $from;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getTo(): ?\DateTimeInterface
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo(?DateTimeInterface $to): void
    {
        $this->to = $to;
    }

    /**
     * @return string|null
     */
    public function getKeyTemplate(): ?string
    {
        return $this->keyTemplate;
    }

    /**
     * @param string|null $keyTemplate
     */
    public function setKeyTemplate(?string $keyTemplate): void
    {
        $this->keyTemplate = $keyTemplate;
    }


}
