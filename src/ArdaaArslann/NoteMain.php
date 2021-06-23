<?php

namespace ArdaaArslann;

use pocketmine\plugin\{Plugin, PluginBase};
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\Config;
use pocketmine\event\Listener;

use ArdaaArslann\{NoteEvent, NoteCommand};

class NoteMain extends PluginBase implements Listener {
  
  public static $instance;
  
  public function onEnable(){
    $this->getLogger()->info("§3BankNotes is Enabled");
    @mkdir($this->getDataFolder());
    if(file_exists($this->getDataFolder()."config.yml")){
    unlink($this->getDataFolder()."config.yml");
    $this->saveResource("config.yml");
    }else $this->saveResource("config.yml");
    
    $this->cfg = new Config($this->getDataFolder()."config.yml", Config::YAML);
    $this->getServer()->getPluginManager()->registerEvents(new NoteEvent($this), $this);
    $this->getServer()->getCommandMap()->register("banknotes", new NoteCommand($this));
  }
  
  public function onDisable(){
    $this->getLogger()->info("§3BankNotes is Disabled");
  }
  
  public function onLoad(){
    static::$instance = $this;
  }
  
  public static function getInstance(): NoteMain{
        return self::$instance;
    }
}