<?php

namespace Luthfi\SimplePing;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {

    private string $pingMessage;
    private array $cooldowns = [];
    private int $pingCooldown;

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->pingMessage = $this->getConfig()->get("ping-message", "§eYou got pinged!");
        $this->pingCooldown = $this->getConfig()->get("ping-cooldown", 5);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onPlayerChat(PlayerChatEvent $event): void {
        $message = $event->getMessage();
        $players = $this->getServer()->getOnlinePlayers();

        foreach ($players as $player) {
            if (stripos($message, '@' . $player->getName()) !== false) {
                if ($this->canPing($player)) {
                    $player->sendTitle($this->pingMessage, TextFormat::clean($message));
                    $this->cooldowns[$player->getName()] = time();
                }
            }
        }
    }

    /**
     * Check if a player can be pinged.
     *
     * @param Player $player
     * @return bool
     */
    private function canPing(Player $player): bool {
        $lastPing = $this->cooldowns[$player->getName()] ?? 0;
        return (time() - $lastPing) >= $this->pingCooldown;
    }
}
