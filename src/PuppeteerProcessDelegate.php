<?php

namespace ScriptBurn\HeadlessScrapper;

use Nesk\Rialto\Interfaces\ShouldHandleProcessDelegation;
use Nesk\Rialto\Traits\UsesBasicResourceAsDefault;

class PuppeteerProcessDelegate implements ShouldHandleProcessDelegation
{
	use UsesBasicResourceAsDefault;

	/**
	 * {@inheritDoc}
	 */
	public function resourceFromOriginalClassName(string $className):  ? string
	{
		$class = "ScriptBurn\\HeadlessScrapper\\Resources\\$className";
		$class2 = "Nesk\\Puphpeteer\\Resources\\$className";

		if (class_exists($class))
		{
			return $class;
		}
		elseif (class_exists($class2))
		{

			return $class2;
		}
		else
		{
			return null;
		}
	}
}
