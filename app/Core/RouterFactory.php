<?php declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList();

        // Api
        // V1

        $router->withModule('Api:V1')
            ->withPath('api/')
            ->addRoute('invoice[/<id \d+>]', 'Invoice:default')
            ->addRoute('invoice/<id \d+>/status', 'Invoice:status')
            ->addRoute('invoice/<id \d+>/logs', 'Invoice:logs');

        // Common

        $router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}
}
