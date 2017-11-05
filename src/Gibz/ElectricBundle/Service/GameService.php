<?php

namespace Gibz\ElectricBundle\Service;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Voryx\ThruwayBundle\Annotation\Register;

class GameService implements ContainerAwareInterface
{
    private $state = [];
    private $chance = 25;
    private $container;

    public function __construct()
    {
        $this->setState([
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

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getState(): array
    {
        return $this->state;
    }

    /**
     * @param array $state
     */
    public function setState(array $state)
    {
        $this->state = $state;
    }

    private function getChance(): bool
    {
        $rand = mt_rand(1, 100);
        if ($rand <= $this->chance) {
            return true;
        }
        return false;
    }

    /**
     * @Register("ru.electric.new")
     */
    public function new()
    {
        return $this->getState();
    }

    /**
     * @Register("ru.electric.click")
     * @param $pos
     * @return mixed
     */
    public function click($pos): array
    {
        $state = $this->getState();
        $state['field'][$pos[0]]['is_clicked'] = true;
        $state['field'][$pos[0]]['is_on'] = true;
        $state['counter']++;
        $this->setState($state);

        return $state;
    }
}