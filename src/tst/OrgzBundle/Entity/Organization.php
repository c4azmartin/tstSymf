<?php

namespace tst\OrgzBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="OrganizationRepository")
 * @ORM\Table(name="tst_organization")
 * @ORM\Table(name="organization", uniqueConstraints={@ORM\UniqueConstraint(name="org_id_uindex", columns={"id"}), @ORM\UniqueConstraint(name="org_ogrn_uindex", columns={"ogrn"}), @ORM\UniqueConstraint(name="org_oktmo_uindex", columns={"oktmo"})})
 * @UniqueEntity("ogrn")
 * @UniqueEntity("oktmo")
 */

class Organization{
    /**
     *@var integer
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;


    /**
     * Bidirectional (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="tst\OrgzBundle\Entity\User", mappedBy="organization",
     *     cascade={"persist", "remove"})
     */
    protected $users;

    /**
     * @var string
     *
     * @ORM\Column( type="string",length=255, nullable=false)
     */

    protected $title;

    /**
     * @var integer
     *
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\Length(min=13, max=13)
     */
    protected $ogrn;

    /**
     * @var integer
     *
     * @ORM\Column(type="bigint", unique=true)
     * @Assert\Length(min=11, max=11)
     */
    protected $oktmo;

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Organization
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set ogrn
     *
     * @param integer $ogrn
     * @return Organization
     */
    public function setOgrn($ogrn)
    {
        $this->ogrn = $ogrn;

        return $this;
    }

    /**
     * Get ogrn
     *
     * @return integer 
     */
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * Set oktmo
     *
     * @param integer $oktmo
     * @return Organization
     */
    public function setOktmo($oktmo)
    {
        $this->oktmo = $oktmo;

        return $this;
    }

    /**
     * Get oktmo
     *
     * @return integer 
     */
    public function getOktmo()
    {
        return $this->oktmo;
    }

    /**
     * Add users
     *
     * @param \tst\OrgzBundle\Entity\User $users
     * @return Organization
     */
    public function addUser(\tst\OrgzBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \tst\OrgzBundle\Entity\User $users
     */
    public function removeUser(\tst\OrgzBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
