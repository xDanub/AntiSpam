<?php
namespace iTzFreeHD\AntiSpam;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as c;

class AntiSpam extends PluginBase implements Listener {

    public $spam = [];

    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        $this->getLogger()->info("Wurde erfolgreich gestartet");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $cfg = new Config($this->getDataFolder().'/config.yml', Config::YAML);
        if (empty($cfg->get('SpammerTimeSec'))) {
            $cfg->set('SpammerTimeSec', 3);
            $cfg->save();
        }
        $this->getScheduler()->scheduleRepeatingTask(new SpamTask($this), $cfg->get('SpammerTimeSec') * 20);
    }

    public function onChat(PlayerChatEvent $event)
    {
        $name = $event->getPlayer()->getName();
        $player = $event->getPlayer();
        if (!in_array($name, $this->spam)) {
            if (!$player->hasPermission('anti.spam')) {
                $this->spam[] = $name;
            }
        } else {
            $event->setCancelled(true);
            $player->sendMessage(c::RED."Bitte warte einen moment bis du wieder schreibst");
        }
    }
}