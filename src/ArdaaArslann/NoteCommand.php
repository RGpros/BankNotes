<?php

namespace ArdaaArslann;

use pocketmine\{Player, Server};
use pocketmine\command\{Command, CommandSender};
use pocketmine\plugin\{Plugin};
use ArdaaArslann\NoteMain;
use pocketmine\event\Listener;
use ArdaaArslann\Forms\{CustomForm};
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\block\Block;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\utils\TextFormat as C;

class NoteCommand extends Command {
  
  public function __construct(NoteMain $plugin){
    parent::__construct("banknotes", "Güvenli Bir Şekilde Çek Oluştur", "/banknotes");
    $this->setAliases(["cek"]);
    $this->p = $plugin;
  }
  
  public function execute(CommandSender $g, string $label, array $args){
    $result = $this->p->cfg->get("min-money");
    if(EconomyAPI::getInstance()->myMoney($g) < $result){
      $g->sendMessage("§f» §cÇek Oluşturmak İçin Yeterli Paran Yok!");
      return false;
    }
    $this->CekEkran($g);
  }
  
  public function buyule(int $buyu, int $seviye, Item $item){
 		$buyu = Enchantment::getEnchantment($buyu);
 		$buyu = new EnchantmentInstance($buyu, $seviye);
 		$item->addEnchantment($buyu);
 }
  
  public function CekEkran($g){
    $f = new CustomForm(function(Player $g, array $args = null){
    if(is_null($args)) return true;
    $max = $this->p->cfg->get("max-money");
    $min = $this->p->cfg->get("min-money");
    if(empty($args[1])){
    $g->sendMessage("§f» §cÇek Miktar Kısmı Boş Olmamalıdır;");
    return false;
    }
    
    if(!is_numeric($args[1])){
    $g->sendMessage("§f» §cÇek Miktarı Sayısal Olmalıdır!");
    return false;
    }
    
    if($args[1] > $max){
    $g->sendMessage("§f» §cEn Fazla §c{$max} §cTL Değerinde Çek Oluşturabilirsin!");
    return false;
    }
    
    if($args[1] < $min){
    $g->sendMessage("§f» §cEn Az §c{$min} §cTL Değerinde Çek Oluşturabilirsin!");
    return false;
    }
    
    if(!$g->hasPermission("banknotes.create")){
    $g->sendMessage("§f» §4Çek Oluşturma Yetkin Yok!");
    return false;
    }
    
    if(!$g->getInventory()->canAddItem(Item::get(339,1))){
    $g->sendMessage("§f» §4Envanterinde Yer Yok!");
    return false;
    }
    
    if(EconomyAPI::getInstance()->myMoney($g) < $args[1]){
    $g->sendMessage("§f» §4Yeterli Miktarda Paran Yok!");
    return false;
    }
    
    $this->CekVer($g, $g->getName(), $args[1]);
    EconomyAPI::getInstance()->reduceMoney($g, $args[1]);
    $g->sendMessage("§f» §a{$args[1]} §7TL Değerindeki Çek Oluşturuldu!");
    });
    $param = EconomyAPI::getInstance()->myMoney($g);
   $max = $this->p->cfg->get("max-money");
   $min = $this->p->cfg->get("min-money");
   $f->setTitle("Çek Ekranı");
   $f->addLabel("\n§f» §7En Az §a{$min}\n§f» §7En Fazla §a{$max} \n§7TL Değerinde Çek Oluşturabilirsin!\n§7Senin Paran: §a{$param}\n\n");
   $f->addInput("§aMiktar Giriniz", "Örn: 1000");
   $f->addLabel("\n\n");
   $f->sendToPlayer($g);
  }
  
  public function CekVer($g, string $custom, int $amount){
    $item = Item::get(339,0,1);
    $item->setCustomName("§e{$amount} §aTL Değerinde Çek")->setLore(["§bÇeki Yazan:§a {$custom}"]);
    $this->buyule(22,0,$item);
    $item->getNamedTag()->setInt("money",$amount);
    $g->getInventory()->addItem($item);
  }
}