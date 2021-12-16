<?php

namespace Hytlenz;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\command\{Command, CommandSender};

use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use dktapps\pmforms\FormIcon;

class Main extends PluginBase {
	
	protected function onEnable() : void {
		$this->saveDefaultConfig();
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		switch($cmd->getName()){
			case "info":
				if ($sender instanceof Player) {
					$form = $this->infoForm($sender->getName());
					$sender->sendForm($form);
				}
			break;
		}
		return true;
	}
	
	public function infoForm(string $name) : MenuForm {
		$menuButtons = [];
		$config = $this->getConfig()->getAll();
		foreach (array_keys($config["wiki"]) as $wiki) {
			$menuButtons[] = new MenuOption(
				$config["wiki"]["$wiki"]["button"][0], 
				new FormIcon( 
					($config["wiki"]["$wiki"]["button"][1]), 
					(filter_var($config["wiki"]["$wiki"]["button"][1], FILTER_VALIDATE_URL) ? FormIcon::IMAGE_TYPE_URL : FormIcon::IMAGE_TYPE_PATH) 
				)
			);
		}
		
		return new MenuForm(
			$config["wikipedia"]["title"],
			implode("\n", str_replace("{player}", $name, $config["wikipedia"]["content"])),
			$menuButtons,
			function (Player $submitter, int $selected) use ($config) : void {
				$buttons = array_keys($config["wiki"]);
				if (count($buttons) == $selected) return;
				$button = $buttons[$selected];
				$form = $this->pageForm($button);
				$submitter->sendForm($form);
			},
			
			function(Player $submitter) use ($config) : void {
				$submitter->sendMessage($config["wikipedia"]["thanks"]);
			}
		);
	}

	public function pageForm(string $button) : MenuForm {
		$config = $this->getConfig()->getAll();
		return new MenuForm(
			$config["wiki"]["$button"]["title"],
			implode("\n", $config["wiki"]["$button"]["content"]),
			[
				new MenuOption($config["wikipedia"]["return"])
			],
			function (Player $submitter, int $selected) : void {
				$form = $this->infoForm($submitter->getName());
				$submitter->sendForm($form);
			},
			
			function(Player $submitter) : void {
				$form = $this->infoForm($submitter->getName());
				$submitter->sendForm($form);
			}
		);
	}
	
}
