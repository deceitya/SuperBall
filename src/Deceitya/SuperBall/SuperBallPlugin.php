<?php
namespace Deceitya\SuperBall;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Snowball;

use Deceitya\SuperBall\SuperBall;

class SuperBallPlugin extends PluginBase implements Listener
{
    public function onLoad()
    {
        Entity::registerEntity(SuperBall::class, false, ['SuperBall', 'minecraft:super_ball']);
    }

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onProjectileLaunch(ProjectileLaunchEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Snowball) {
            $ball = Entity::createEntity(
                'SuperBall',
                $entity->getLevel(),
                Entity::createBaseNBT($entity->asVector3(), $entity->getMotion())
            );
            $ball->setOwningEntity($entity->getOwningEntity());

            $entity->close();
            $ball->spawnToAll();
        }
    }
}
