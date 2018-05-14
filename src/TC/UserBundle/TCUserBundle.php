<?php

namespace TC\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TCUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
