<?php

namespace Hytlenz;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\command\{Command, CommandSender};

use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use dktapps\pmforms\FormIcon;

class Main extends PluginBase implements Listener{
  
	protected function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("Information has been revealed - Lentou");
		
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->config = $this->getConfig()->getAll();
	}
	
	protected function onDisable() : void {
		$this->getLogger()->info("Information has been vanished - Lentou");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		switch($cmd->getName()){
			case "info":
				$form = $this->infoForm();
				$player->sendForm($form);
			break;
		}
		return true;
	}
	
	public function infoForm() : MenuForm {
		return new MenuForm(
			$this->config["wikipedia"]["title"],
			implode("\n", str_replace("{player}", $sender->getName(), $this->config["wikipedia"]["content"])),
			foreach (array_keys($this->config["wiki"]) as $wiki) {
				new MenuOption(
					$this->config["wiki"]["$wiki"]["button"][0], 
					new FormIcon($this->config["wiki"]["$wiki"]["button"][1], $this->config["wiki"]["$wiki"]["button"][2])
				)
			},
			function (Player $submitter, int $selected) : void {
				$buttons = array_keys($this->config["wiki"]);
				if (count($buttons) == $selected) return;
				$button = $buttons[$selected];
				$this->pageForm($submmiter, $button);
			},
			
			function(Player $submitter) : void {
				$submitter->sendMessage("Thank you for Using");
			}
		);
	}

	public function pageForm($sender, $button) : MenuForm {
		return new MenuForm(
			$this->config["wiki"]["$button"]["title"],
			implode("\n", $this->config["wiki"]["$button"]["content"]),
			[
				new MenuOption($this->config["wikipedia"]["return"])
			],
			function (Player $submitter, int $selected) : void {
				$form = $this->infoForm();
				$submitter->sendForm($form);
			},
			
			function(Player $submitter) : void {
				$form = $this->infoForm();
				$submitter->sendForm($form);
			}
		);
	}
	
}
