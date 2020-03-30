<?php

namespace Hytlenz;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\{Player, Server};
use pocketmine\command\{Command, CommandSender};
use Hytlenz\SimpleForm;

class Main extends PluginBase implements Listener{
  
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("Information has been revealed");
		
		@mkdir($this->getDataFolder());
		$this->saveResource("config.yml");
		$this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
	}
	
	public function onDisable(){
		$this->getLogger()->info("Information has been leashed");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		switch($cmd->getName()){
			case "info":
				$this->infoUI($sender);
				break;
		}
		return true;
	}
	
	public function infoUI($sender){
		$form = new SimpleForm(function (Player $sender, $data){
			if($data === null){}
			switch($data){
				case 0:
					$sender->addTitle($this->cfg->getNested("exit.title"), $this->cfg->getNested("exit.subtitle"));
					break;
				case 1:
					$this->aboutUI($sender);
					break;
				case 2:
					$this->rulesUI($sender);
					break;
				case 3:
				    $this->staffsUI($sender);
				    break;
				case 4:
				    $this->otherUI($sender);
				    break;
			}
		});
		$form->setTitle($this->cfg->getNested("info.title-form"));
		$form->setContent($this->cfg->getNested("info.content"));
		$form->addButton($this->cfg->getNested("exit.button-name"), $this->cfg->getNested("exit.image-type"), $this->cfg->getNested("exit.image-path"));
		$form->addButton($this->cfg->getNested("about.button-name"), $this->cfg->getNested("about.image-type"), $this->cfg->getNested("about.image-path"));
		$form->addButton($this->cfg->getNested("rules.button-name"), $this->cfg->getNested("rules.image-type"), $this->cfg->getNested("rules.image-path"));
		$form->addButton($this->cfg->getNested("staffs.button-name"), $this->cfg->getNested("staffs.image-type"), $this->cfg->getNested("staffs.image-path"));
		if($this->cfg->get("enable-other-form")){
		   $form->addButton($this->cfg->getNested("other.button-name"), $this->cfg->getNested("other.image-type"), $this->cfg->getNested("other.image-path"));
		}
		$form->sendToPlayer($sender);
		return $form;
	}
	
	public function aboutUI($sender){
		$form = new SimpleForm(function (Player $sender, $data){
			if($data == null) {}
		    switch($data){
			    case 0:
				    $this->infoUI($sender);
				    break;
			}
        });
		$form->setTitle($this->cfg->getNested("about.title-form"));
		$form->setContent(implode("\n", $this->cfg->getNested("about.message")));
		$form->addButton($this->cfg->getNested("back.button-name"), $this->cfg->getNested("back.image-type"), $this->cfg->getNested("back.image-path"));
		$form->sendToPlayer($sender);
	}
	
	public function rulesUI($sender){
		$form = new SimpleForm(function (Player $sender, $data){
			if($data == null) {}
		    switch($data){
			    case 0:
				    $this->infoUI($sender);
				    break;
			}
        });
		$form->setTitle($this->cfg->getNested("rules.title-form"));
		$form->setContent(implode("\n", $this->cfg->getNested("rules.message")));
		$form->addButton($this->cfg->getNested("back.button-name"), $this->cfg->getNested("back.image-type"), $this->cfg->getNested("back.image-path"));
		$form->sendToPlayer($sender);
	}
	
	public function staffsUI($sender){
		$form = new SimpleForm(function (Player $sender, $data){
			if($data == null) {}
		    switch($data){
			    case 0:
				    $this->infoUI($sender);
				    break;
			}
        });
		$form->setTitle($this->cfg->getNested("staffs.title-form"));
		$form->setContent(implode("\n", $this->cfg->getNested("staffs.message")));
		$form->addButton($this->cfg->getNested("back.button-name"), $this->cfg->getNested("back.image-type"), $this->cfg->getNested("back.image-path"));
		$form->sendToPlayer($sender);
	}
	
	public function otherUI($sender){
		$form = new SimpleForm(function (Player $sender, $data){
			if($data == null) {}
		    switch($data){
			    case 0:
				    $this->infoUI($sender);
				    break;
			}
        });
		$form->setTitle($this->cfg->getNested("other.title-form"));
		$form->setContent(implode("\n", $this->cfg->getNested("other.message")));
		$form->addButton($this->cfg->getNested("back.button-name"), $this->cfg->getNested("back.image-type"), $this->cfg->getNested("back.image-path"));
		$form->sendToPlayer($sender);
	}
	
}