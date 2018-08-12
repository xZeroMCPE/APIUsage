<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/11/2018
 * Time: 9:03 PM
 */

namespace xZeroMCPE\APIUsage;


use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use xZeroMCPE\UltraFaction\Faction\Event\FactionClaimEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionCreateEvent;

class APIUsage extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /*
     *  Let's stop them from creating a faction if they don't have 64x diamond ingot! >:)
     */
    public function onCreate(FactionCreateEvent $event){

        $player = $event->getPlayer();

        if(!$player->getInventory()->contains(Item::get(Item::DIAMOND, 0, 64))){
            $player->sendMessage("You can't create a faction because you don't have 64x diamonds! :/");
            $event->setCancelled();
        }

        /*
         *  Maybe also disallow some names?
         */
        $notAllowed = [
            'owner',
            'admin',
            'mod',
            'zero'
        ];
        if(in_array(strtolower($event->getFactionName()), $notAllowed)){
            $player->sendMessage("You can't use that as your faction name!");
            $event->setCancelled();
        }
    }

    /*
     *  Disallow a faction from claiming if they have less than 5 members.
     */
    public function onClaim(FactionClaimEvent $event){

        $player = $event->getPlayer();
        $faction = $event->getFaction();

        if(count($faction->getMembers()) < 5){
            $player->sendMessage("You can't claim here because you don't have enough members");
            $event->setCancelled();
        }
    }
}