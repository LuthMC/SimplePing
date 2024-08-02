<?php

namespace Luthfi\SimplePing;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;

class Main extends PluginBase implements Listener {

    private string $pingMessage;

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->pingMessage = $this->getConfig()->get("ping-message", "You got pinged!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("SimplePing Enabled!");
    }

    public function onDisable(): void {
        $this->getLogger()->info("SimplePing Disabled!");
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onPlayerChat(PlayerChatEvent $event): void {
        $message = $event->getMessage();
        $players = $this->getServer()->getOnlinePlayers();

        foreach ($players as $player) {
            if (strpos($message, '@' . $player->getName()) !== false) {
                $player->sendTitle($this->pingMessage, $message);
            }
        }
    }
}
