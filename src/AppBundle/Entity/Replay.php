<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-05-20
 * Time: 21:02
 */

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Replay File
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class Replay
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var Battle
     *
     * @ORM\ManyToOne(targetEntity="Battle", inversedBy="replays")
     */
    private $battle;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="cwReplays")
     */
    private $player;

    /**
     * @var string
     * @ORM\Column(type="string", length=80)
     */
    private $playerName;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    private $fileName;

    /**
     * @var UploadedFile
     *
     * @Vich\UploadableField(mapping="replay_file", fileNameProperty="fileName")
     */
    private $replayFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return UploadedFile
     */
    public function getReplayFile()
    {
        return $this->replayFile;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param $fileName
     *
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @param $player
     *
     * @return $this
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * @param File|null $replay
     *
     * @return $this
     */
    public function setReplayFile(File $replay = null)
    {
        $this->replayFile = $replay;

        if ($replay) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @param Battle $battle
     *
     * @return $this
     */
    public function setBattle(Battle $battle)
    {
        $this->battle = $battle;

        return $this;
    }

    /**
     * Get Battle
     *
     * @return Battle
     */
    public function getBattle()
    {
        return $this->battle;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Replay
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // check if file is wotreplay extension
        if ($this->getReplayFile()->getClientOriginalExtension() !== 'wotreplay')
        {
            $context->buildViolation('This is not wotreplay file!')
            ->addViolation();
            return;
        }
        $filename = $this->getReplayFile()->getRealPath();
        if (!is_readable($filename) || ($fp = @fopen($filename, "rb")) === false) {
            $context->buildViolation("Cannot read file $filename")
                ->addViolation();
        }
        elseif ($fp) {
            fseek($fp, 4);
            $blocks = sprintf("%u", unpack("I", fread($fp, 4))[1]);
            if ($blocks == 0 || $blocks > 5) {
                $context->buildViolation("Incompatible replay file, Blocks: $blocks")
                    ->addViolation();
            }
            fclose($fp);
        }

    }

    /**
     * Set playerName
     *
     * @param string $playerName
     *
     * @return Replay
     */
    public function setPlayerName($playerName)
    {
        $this->playerName = $playerName;

        return $this;
    }

    /**
     * Get playerName
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->playerName;
    }
}
