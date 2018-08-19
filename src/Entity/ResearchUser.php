<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 8/11/18
 * Time: 4:06 PM
 */


namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\DateTimeType;
use \App\Entity\Essay as Essay;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="research_user")
 */
class ResearchUser extends BaseUser{

    /**
     * @ORM\Id()
     * @ORM\Column(name="user_id", type = "integer")
     * @ORM\GeneratedValue(strategy = "IDENTITY")
     * @var integer
     */
    protected $user_id;

    /**
     * @var \string
     * @ORM\Column(name="firstName", type="string", nullable=false)
     */
    protected $firstName;
    /**
     * @var \string
     * @ORM\Column(name="lastName", type="string", nullable=false)
     */
    protected $lastName;

    /**
     * @var \DateTime
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="modifiedAt", type="datetime", nullable=false)
     */
    protected $modifiedAt;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Essay", mappedBy="essayUser")
     */
    protected $essays;

    public function __construct($username,$firstname,$lastname,$email) {

        parent::__construct();
        $date = new \DateTime();

        $this->username = $username;
        $this->firstName = $firstname;
        $this->lastName = $lastname;
        $this->email = $email;
        $this->essays     = new ArrayCollection();
        $this->createdAt = $date;
        $this->modifiedAt = $date;
    }

    /**
     *
     * @return int
     */
    public function getId() {
        return $this->user_id;
    }

    /**
     *
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }


    /**
     *
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ResearchUser
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return \DateTime
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this->modifiedAt;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Add essays
     *
     * @param \App\Entity\Essay $essay
     * @return Essay
     */
    public function addEssay(Essay $essay)
    {
        $this->essays[] = $essay;

        return $essay;
    }

    /**
     * Remove essays
     *
     * @param \App\Entity\Essay $essay
     */
    public function removeEssay(Essay $essay)
    {
        $this->essays->removeElement($essay);
    }

    /**
     * Get essays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEssays()
    {
        return $this->essays;
    }

}
