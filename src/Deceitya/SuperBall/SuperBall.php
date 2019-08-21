<?php
namespace Deceitya\SuperBall;

use pocketmine\entity\projectile\Snowball;
use pocketmine\block\Block;
use pocketmine\math\RayTraceResult;
use pocketmine\math\Vector3;

class SuperBall extends Snowball
{
    public function onHitBlock(Block $block, RayTraceResult $hitResult): void
    {
        $vec = $this->subtract($block);
        $originalMotion = $this->getMotion() ?? new Vector3(0, 0, 0);

        $motion = null;
        if ($vec->z == 0 || $vec->z == 1) {
            $motion = new Vector3($originalMotion->x, $originalMotion->y, $originalMotion->z * -1);
        } elseif ($vec->y == 0 || $vec->y == 1) {
            $motion = new Vector3($originalMotion->x, $originalMotion->y * -1, $originalMotion->z);
        } elseif ($vec->x == 0 || $vec->x == 1) {
            $motion = new Vector3($originalMotion->x * -1, $originalMotion->y, $originalMotion->z);
        }

        // ほんとはこうしたいけど雪玉が動いてくれない...解決策があれば教えてください、エロい人
        // $this->teleport($this->add($motion));
        // $this->setMotion($motion);

        if ($motion->length() > 0.5) {
            $new = self::createEntity(
                'SuperBall',
                $this->getLevel(),
                self::createBaseNBT($this->add($motion), $motion)
            );
            $new->setOwningEntity($this->getOwningEntity());

            $new->spawnToAll();
        }

        parent::onHitBlock($block, $hitResult);
    }
}
