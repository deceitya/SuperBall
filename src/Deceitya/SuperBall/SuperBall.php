<?php
namespace Deceitya\SuperBall;

use pocketmine\entity\projectile\Snowball;
use pocketmine\block\Block;
use pocketmine\math\RayTraceResult;
use pocketmine\math\Vector3;

class SuperBall extends Snowball
{
    /** @var Vector3 */
    private $next;

    public function entityBaseTick(int $tickDiff = 1): bool
    {
        if ($this->closed) {
            return false;
        }

        $this->next = $this->getMotion()->subtract(0, $this->gravity);

        return true;
    }

    public function onHitBlock(Block $block, RayTraceResult $hitResult): void
    {
        $vec = $this->subtract($block);

        $motion = null;
        if ($vec->x == 0 || $vec->x == 1) {
            $motion = new Vector3($this->next->x, $this->next->y, $this->next->z * -1);
        } elseif ($vec->y == 0 || $vec->y == 1) {
            $motion = new Vector3($this->next->x, $this->next->y * -1, $this->next->z);
        } elseif ($vec->z == 0 || $vec->z == 1) {
            $motion = new Vector3($this->next->x * -1, $this->next->y, $this->next->z);
        }

        $new = self::createEntity(
            'SuperBall',
            $this->getLevel(),
            self::createBaseNBT($this->add($motion), $motion)
        );
        $new->setOwningEntity($this->getOwningEntity());

        $this->close();
        $new->spawnToAll();
    }
}
