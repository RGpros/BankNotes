<?php

namespace ArdaaArslann;

use ArdaaArslann\NoteMain;
use pocketmine\{Player, Server};
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\block\Block;

class NoteEvent implements Listener {
  
  public function __construct(NoteMain $plugin){
    $this->p = $plugin;
  }
  
  public function onInteract(PlayerInteractEvent $e){
    $g = $e->getPlayer();
    $item = $g->getInventory()->getItemInHand();
    $custom = $item->getCustomName();
    $block = $e->getBlock();
    if($item->getId() == 339){
    if($item->getNamedTag()->hasTag("money")){
    if($g->hasPermission("banknotes.use")){
    if($block->getId() == Block::ITEM_FRAME_BLOCK){
    $g->sendMessage("§f» §4Bug Yapmaya Kalkışma!");
    $e->setCancelled();
    }else{
    $miktar = $item->getNamedTag()->getInt("money");
    $item->setCount($item->getCount() - 1);
    $g->getInventory()->setItemInHand($item);
    $g->sendMessage("§f» §a{$miktar} §7TL Değerindeki Çek Bozduruldu!");
    EconomyAPI::getInstance()->addMoney($g, $miktar);
    }
    }else $g->sendMessage("§f» §cÇek Kullanmaya Yetkin Yok!");
    }
    }
  }
}