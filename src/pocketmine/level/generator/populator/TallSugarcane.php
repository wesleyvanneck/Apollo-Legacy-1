<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://mcper.cn
 *
 */

namespace pocketmine\level\generator\populator;

use pocketmine\block\BlockFactory;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use pocketmine\level\generator\populator\VariableAmountPopulator;

class TallSugarcane extends VariableAmountPopulator{
	/** @var ChunkManager */
	private $level;
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		$this->level = $level;
		$amount = $this->getAmount($random);
		for($i = 0; $i < $amount; ++$i){
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($x, $z);

			if($y !== -1 and $this->canTallSugarcaneStay($x, $y, $z)){
				$this->level->setBlockIdAt($x, $y, $z, BlockFactory::SUGARCANE_BLOCK);
				$this->level->setBlockDataAt($x, $y, $z, 1);
			}
		}
	}

	private function canTallSugarcaneStay($x, $y, $z){
		$b = $this->level->getBlockIdAt($x, $y, $z);
		return ($b === BlockFactory::AIR) and $this->level->getBlockIdAt($x, $y - 1, $z) === BlockFactory::SUGARCANE_BLOCK;
	}

	private function getHighestWorkableBlock($x, $z){
		for($y = 127; $y >= 0; --$y){
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if($b !== BlockFactory::AIR and $b !== BlockFactory::LEAVES and $b !== BlockFactory::LEAVES2){
				break;
			}
		}

		return $y === 0 ? -1 : ++$y;
	}
}
