<?php

namespace Hytlenz;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\{Player, Server};
use pocketmine\command\{Command, CommandSender};
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener{
  
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("Information has been revealed - Lentou");
		
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->getResource("config.yml");
		$this->config = $this->getConfig()->getAll();
	}
	
	public function onDisable(){
		$this->getLogger()->info("Information has been vanished - Lentou");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		switch($cmd->getName()){
			case "info":
				$this->infoForm($sender);
				break;
		}
		return true;
	}
	
	public function infoForm($sender){
		$form = new SimpleForm(function (Player $sender, $data) {
            		if (is_null($data)) return true;
            		$buttons = array_keys($this->config["wiki"]);
            		if (count($buttons) == $data) return;
            		$button = $buttons[$data];
			$this->pageForm($sender, $button);
        	});
        	$form->setTitle($this->config["wikipedia"]["title"]);
        	$form->setContent(implode("\n", str_replace("{player}", $sender->getName(), $this->config["wikipedia"]["content"])));
        	foreach(array_keys($this->config["wiki"]) as $wiki) {
            		$form->addButton(
				$this->config["wiki"]["$wiki"]["button"][0],
				$this->config["wiki"]["$wiki"]["button"][1],
				$this->config["wiki"]["$wiki"]["button"][2]
			);
        	}
        	$form->sendToPlayer($sender);
	}

	public function pageForm($sender, $button){
		$form = new SimpleForm(function (Player $sender, $data) {
            		if (is_null($data)) return true;
            		switch ($data) {
                		case 0:
					$this->infoForm($sender);
				break;
            		}
        	});
        	$form->setTitle($this->config["wiki"]["$button"]["title"]);
        	$form->setContent(implode("\n", $this->config["wiki"]["$button"]["content"]));
        	$form->addButton($this->config["wikipedia"]["return"]);
        	$form->sendToPlayer($sender);
	}
	
}
