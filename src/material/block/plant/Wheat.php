<?php

/*

           -
         /   \
      /         \
   /   PocketMine  \
/          MP         \
|\     @shoghicp     /|
|.   \           /   .|
| ..     \   /     .. |
|    ..    |    ..    |
|       .. | ..       |
\          |          /
   \       |       /
      \    |    /
         \ | /

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.


*/

class WheatBlock extends FlowableBlock{
	public function __construct($meta = 0){
		parent::__construct(WHEAT_BLOCK, $meta, "Wheat Block");
		$this->isActivable = true;
	}

	public function place(Item $item, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		$down = $this->getSide(0);
		if($down->getID() === FARMLAND){
			$this->level->setBlock($block, $this);
			return true;
		}
		return false;
	}
	
	public function onActivate(Item $item, Player $player){
		if($item->getID() === DYE and $item->getMetadata() === 0x0F){ //Bonemeal
			$this->meta = 0x07;
			$this->level->setBlock($this, $this);
			return true;
		}
		return false;
	}

	public function onUpdate($type){
		if($type === BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isFlowable === true){ //Replace wit common break method
				ServerAPI::request()->api->entity->drop($this, BlockAPI::getItem(WHEAT_SEEDS, 0, 1));
				$this->level->setBlock($this, new AirBlock(), false);
				return BLOCK_UPDATE_NORMAL;
			}
		}
		return false;
	}
	
	public function getDrops(Item $item, Player $player){
		$drops = array();
		if($this->meta >= 0x07){
			$drops[] = array(WHEAT, 0, 1);
			$drops[] = array(WHEAT_SEEDS, 0, mt_rand(0, 3));
		}else{
			$drops[] = array(WHEAT_SEEDS, 0, 1);
		}
		return $drops;
	}
}