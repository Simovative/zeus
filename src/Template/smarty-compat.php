<?php

declare(strict_types=1);

if (! class_exists(\Smarty::class) && class_exists(\Smarty\Smarty::class)) {
	class_alias(\Smarty\Smarty::class, \Smarty::class);
}
