<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 8/11/18
 * Time: 4:14 PM
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use \App\Entity\ResearchUser as ResearchUser;
/**
 * @ORM\Entity
 * @ORM\Table(name="essay")
 */
class Essay {

    /**
     * @ORM\Id()
     * @ORM\Column(name="essay_id", type = "integer")
     * @ORM\GeneratedValue(strategy = "IDENTITY")
     * @var integer
     */
    protected $essay_id;

    /**
     * @var string
     * @ORM\Column(name="essay", type="text", nullable=false)
     */
    protected $essay;

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
     * @var ResearchUser
     * @ORM\ManyToOne(targetEntity="ResearchUser", inversedBy="essays")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    protected $researchUser;


    public function __construct($essay=null, $createdBy=null, $directedToUser='') {
        $this->researchUser     = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->modifiedAt = new \DateTime();
    }


    /**
     *
     * @return string
     */
    public function getEssay() {
        return $this->essay;
    }

    public function setMessage($essay) {
        $this->essay = $essay;
    }

    /**
     *
     * @return int
     */
    public function getId() {
        return $this->essay_id;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getModifiedAt() {
        return $this->modifiedAt;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Essay
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return Essay
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Set researchUser
     *
     * @param \App\Entity\ResearchUser $researchUser
     * @return ResearchUser
     */
    public function setUser(ResearchUser $researchUser = null)
    {
        $this->researchUser = $researchUser;

        return $this->researchUser;
    }

    /**
     * Get researchUser
     *
     * @return \App\Entity\ResearchUser
     */
    public function getUser()
    {
        return $this->researchUser;
    }

}
