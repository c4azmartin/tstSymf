<?php

namespace tst\OrgzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="tst_user", uniqueConstraints={@ORM\UniqueConstraint(name="users_id_uindex", columns={"id"}), @ORM\UniqueConstraint(name="users_inn_uindex", columns={"inn"}), @ORM\UniqueConstraint(name="users_snils_uindex", columns={"snils"})}, indexes={@ORM\Index(name="users_org_fk", columns={"organization"})})
 * @UniqueEntity("snils")
 * @UniqueEntity("inn")
 * @ORM\HasLifecycleCallbacks
 */

class User{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column( type="string",length=255)
     *
     * @Assert\NotBlank()
     */
    protected $secondname;

    /**
     * @var string
     *
     * @ORM\Column( type="string",length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column( type="string",length=255,nullable=true)
     */
    protected $patronymic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date",nullable=true)
     * @Assert\Date()
     */
    protected $date_birth;

    /**
     * @var integer
     *
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=16, max=16)
     */
    protected $inn;

    /**
     * @var integer
     *
     * @ORM\Column(type="bigint", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=13, max=13)
     */
    protected $snils;

    /**
     * Bidirectional (OWNING SIDE - FK)
     *
     * @ORM\ManyToOne(targetEntity="tst\OrgzBundle\Entity\Organization", inversedBy="users")
     * @ORM\JoinColumn(name="organization", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $organization;

    public function __toString()
    {
        return $this->secondname;
    }



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set secondname
     *
     * @param string $secondname
     * @return User
     */
    public function setSecondname($secondname)
    {
        $this->secondname = $secondname;

        return $this;
    }

    /**
     * Get secondname
     *
     * @return string 
     */
    public function getSecondname()
    {
        return $this->secondname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set patronymic
     *
     * @param string $patronymic
     * @return User
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Get patronymic
     *
     * @return string 
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Set date_birth
     *
     * @param \DateTime $dateBirth
     * @return User
     */
    public function setDateBirth($dateBirth)
    {
        $this->date_birth = $dateBirth;

        return $this;
    }

    /**
     * Get date_birth
     *
     * @return \DateTime 
     */
    public function getDateBirth()
    {
        return $this->date_birth;
    }

    /**
     * Set inn
     *
     * @param integer $inn
     * @return User
     */
    public function setInn($inn)
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * Get inn
     *
     * @return integer 
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Set snils
     *
     * @param integer $snils
     * @return User
     */
    public function setSnils($snils)
    {
        $this->snils = $snils;

        return $this;
    }

    /**
     * Get snils
     *
     * @return integer 
     */
    public function getSnils()
    {
        return $this->snils;
    }

    /**
     * Set organization
     *
     * @param \tst\OrgzBundle\Entity\Organization $organization
     * @return User
     */
    public function setOrganization(\tst\OrgzBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \tst\OrgzBundle\Entity\Organization 
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}
