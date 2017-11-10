<?php

namespace Gibz\ElectricBundle\Service;


use Doctrine\ORM\Query\Expr\Join;
use Gibz\ElectricBundle\Entity\GameState;
use Gibz\ElectricBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Voryx\ThruwayBundle\Annotation\Register;

class GameService
{
    /**
     * 1 / 25 = 0.04 = 4%
     * @see GameService::getChance()
     */
    const CHANCE = 4;

    /**
     * @var RegistryInterface $container
     */
    private $doctrine;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * GameService constructor.
     * @param RegistryInterface $doctrine
     * @param LoggerInterface $logger
     */
    public function __construct(RegistryInterface $doctrine, LoggerInterface $logger)
    {
        $this->setLogger($logger);
        $this->setDoctrine($doctrine);
    }

    /**
     * @param RegistryInterface|null $doctrine
     */
    public function setDoctrine(RegistryInterface $doctrine = null)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Throwing d100, if dice is lower or equal to chance - you win (nope).
     * @return bool
     */
    private function getChance(): bool
    {
        $rand = mt_rand(1, 100);
        if ($rand <= $this::CHANCE) {
            return true;
        }
        return false;
    }

    /**
     * Generates game uniqid, state, and persists it in database.
     * If uniqid is set, fetches game state from db.
     * @param null $uniqId
     * @return array
     */
    public function generateState($uniqId = null): array
    {
        $em = $this->doctrine->getManager();
        if (!is_null($uniqId)) {
            $this->getLogger()->debug('uid', ['uid' => $uniqId]);
            $repo = $em->getRepository('GibzElectricBundle:GameState');
            $state = $repo->findOneBy(["uniqId" => $uniqId]);
        } else {
            $bytes = openssl_random_pseudo_bytes(25);
            $gid = bin2hex($bytes);

            $state = new GameState();
            $state->setUniqId($gid);
            $fieldState = $state->getState();
            $fieldState['uniqid'] = $gid;
            $state->setState($fieldState);

            $em->persist($state);
            $em->flush();
        }
        $this->getLogger()->debug('state', ['state' => $state]);

        return $state->getState();

    }

    /**
     * @Register("ru.electric.new")
     * @param $args
     * @return array
     */
    public function new($args)
    {
        if (isset($args[0])) {
            return $this->generateState($args[0]);
        } else {
            return $this->generateState();
        }
    }

    /**
     * @Register("ru.electric.click")
     * @param $args
     * @return mixed
     */
    public function click($args): array
    {
        $gameState = $this->doctrine->getRepository('GibzElectricBundle:GameState')->findOneBy(['uniqId' => $args[1]]);

        $state = $gameState->getState();

        if ($state['field'][$args[0]]['is_clicked']) {
            return $state;
        }

        $state['field'][$args[0]]['is_clicked'] = true;
        $state['field'][$args[0]]['is_on'] = true;

        list($x, $y) = str_split((string)$args[0], 1);

        if ($x == 0) {
            $x1 = 0;
            $x2 = 1;
        } else {
            $x1 = $x - 1;
            if ($x == 4) {
                $x2 = 4;
            } else {
                $x2 = $x + 1;
            }
        }

        if ($y == 0) {
            $y1 = 0;
            $y2 = 1;
        } else {
            $y1 = $y - 1;
            if ($y == 4) {
                $y2 = 4;
            } else {
                $y2 = $y + 1;
            }
        }

        for ($xPos = $x1; $xPos <= $x2; $xPos++) {

            for ($yPos = $y1; $yPos <= $y2; $yPos++) {
                $iterPos = $xPos . "" . $yPos;
                if ($state['field'][$iterPos]['is_on'] && !$state['field'][$iterPos]['is_clicked']) {
                    $state['field'][$iterPos]['is_on'] = false;
                } else {
                    $state['field'][$iterPos]['is_on'] = true;
                }
            }
        }

        if ($this->getChance()) {
            $state['field'][array_keys($state['field'])[mt_rand(0, count($state['field']) - 1)]]['is_on'] = true;
        }

        $state['counter']++;

        $cellStates = array_map(function ($cell) {
            return $cell['is_on'];
        }, $state['field']);

        if (!in_array(false, $cellStates)) {
            $state['state'] = 'winner';
        }

        $em = $this->doctrine->getManager();
        $gameState->setState($state);
        $gameState->setCounter($gameState->getCounter() + 1);
        $em->merge($gameState);
        $em->flush();

        return $state;
    }

    /**
     * @Register("ru.electric.save")
     * @param $args
     * @return mixed
     */
    public function save($args)
    {
        $em = $this->doctrine->getManager();
        $userRepo = $em->getRepository('GibzElectricBundle:User');
        $gameRepo = $em->getRepository('GibzElectricBundle:GameState');

        $gameState = $gameRepo->findOneBy(['uniqId' => $args[1]]);

        if (!is_null($gameState)) {
            $cellStates = array_map(function ($cell) {
                return $cell['is_on'];
            }, $gameState->getState()['field']);

            if (!in_array(false, $cellStates)) {
                $user = $userRepo->findOneBy(['name' => strip_tags(stripslashes($args[0]))]);
                if (is_null($user)) {
                    $user = new User();
                    $user->setName(strip_tags(stripslashes($args[0])));
                    $em->persist($user);
                }
                $gameState->setUser($user);
                $em->merge($gameState);
                $em->flush();
            }
        }

        return [
            'status' => 'ok'
        ];
    }

    /**
     * @Register("ru.electric.ladder")
     */
    public function ladder($args)
    {
        $em = $this->doctrine->getManager()->getRepository('GibzElectricBundle:GameState');
        $query = $em->createQueryBuilder('gs');
        $result = $query->select('gs, u')
            ->setMaxResults(10)
            ->where($query->expr()->isNotNull('gs.user'))
            ->orderBy('gs.counter', 'asc')
            ->leftJoin('gs.user', 'u',
                Join::WITH,
                'gs.user = u.id')
            ->getQuery()
            ->getResult();

        $leaders = [];

        if (count($result) > 0) {
            foreach ($result as $item) {
                $this->getLogger()->debug('item', ['item' => $item]);
                $leaders[] = [
                    'name' => $item->getUser()->getName(),
                    'points' => $item->getState()['counter']
                ];
            }

            usort($leaders, function ($left, $right) {
                if ($left['points'] == $right['points']) {
                    return 0;
                }
                return ($left['points'] < $right['points']) ? -1 : 1;
            });
        }

        return ['leaders' => $leaders];
    }
}
