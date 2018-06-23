<?php
namespace iTzFreeHD\AntiSpam;


use pocketmine\scheduler\Task;

class SpamTask extends Task {


    private $plugin;

    public function __construct(AntiSpam $plugin) {
        $this->plugin = $plugin;

    }

    public function onRun(int $currentTick)
    {

        if ($this->plugin instanceof AntiSpam)
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $name = $player->getName();

            if (in_array($name, $this->plugin->spam)) {
                unset($this->plugin->spam[array_search($name, $this->plugin->spam)]);
            }
        }
    }
}
