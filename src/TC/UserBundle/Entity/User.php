<?php

namespace TC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\MessageBundle\Model\ParticipantInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="TC\UserBundle\Repository\UserRepository")
 * @Vich\Uploadable
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    *
    * @ORM\Column(type="string", length=255, nullable=true)
    *
    * @var string
    */
    protected $steam_id;

    /**
     * @var \Date
     *
     * @ORM\Column(name="birthdate", type="date")
     */
    protected $birthdate;

    /**
 	* @Vich\UploadableField(mapping="avatar", fileNameProperty="avatarName", nullable=true)
 	* 
 	* @var File
 	*/
	protected  $avatarFile;

	/**
 	* @ORM\Column(type="string", length=255, nullable=true)
 	*
 	* @var string
 	*/
	protected $avatarName;

	/**
     * @var \DateTime
     *
     * @ORM\Column(name="avatar_update_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="TC\UserBundle\Entity\Groups")
     * @ORM\JoinTable(name="fos_user_user_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
 * @ORM\Column(type="integer", length=6, options={"default":0})
 */
protected $loginCount = 0;
 
/**
 * @var \DateTime
 *
 * @ORM\Column(type="datetime", nullable=true)
 */
protected $firstLogin; 
  
 public function __construct()
    {
        parent::__construct();
        // your own logic
    }
/**
 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $avatar
 *
 * @return Avatar
*/
	public function setAvatarFile(File $avatar = null)
{
    $this->avatarFile = $avatar;

    if ($avatar) 
        $this->updatedAt = new \DateTimeImmutable();
    
    return $this;
}

/**
 * @return File|null
 */
public function getAvatarFile()
{
    return $this->avatarFile;
}

/**
 * @param string $avatarName
 *
 * @return Avatar
 */
public function setAvatarName($avatarName)
{
    $this->avatarName = $avatarName;

    return $this;
}

/**
 * @return string|null
 */
public function getAvatarName()
{
    return $this->avatarName;
}

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set loginCount
     *
     * @param integer $loginCount
     *
     * @return User
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get loginCount
     *
     * @return integer
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * Set firstLogin
     *
     * @param \DateTime $firstLogin
     *
     * @return User
     */
    public function setFirstLogin($firstLogin)
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    /**
     * Get firstLogin
     *
     * @return \DateTime
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    /**
     * Set steamId
     *
     * @param string $steamId
     *
     * @return User
     */
    public function setSteamId($steamId)
    {
        $this->steam_id = $steamId;

        return $this;
    }

    /**
     * Get steamId
     *
     * @return string
     */
    public function getSteamId()
    {
        return $this->steam_id;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }
}
