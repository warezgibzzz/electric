<?php

namespace Gibz\ElectricBundle\Entity;

/**
 * GameState
 */
class GameState
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $uniqId;

    /**
     * @var string
     */
    private $state;

    public function __construct()
    {
        $this->setState([
            "state" => "game",
            "field" => [
                "00" => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                "01" => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                "02" => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                "03" => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                "04" => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                10 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                11 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                12 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                13 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                14 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                20 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                21 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                22 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                23 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                24 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                30 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                31 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                32 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                33 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                34 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                40 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                41 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                42 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                43 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
                44 => [
                    'is_clicked' => false,
                    'is_on' => false,
                ],
            ],
            'counter' => 0
        ]);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uniqId
     *
     * @param string $uniqId
     *
     * @return GameState
     */
    public function setUniqId($uniqId)
    {
        $this->uniqId = $uniqId;

        return $this;
    }

    /**
     * Get uniqId
     *
     * @return string
     */
    public function getUniqId()
    {
        return $this->uniqId;
    }

    /**
     * Set state
     *
     * @param array $state
     *
     * @return GameState
     */
    public function setState($state)
    {
        $this->state = serialize($state);

        return $this;
    }

    /**
     * Get state
     *
     * @return array
     */
    public function getState()
    {
        return unserialize($this->state);
    }
}

