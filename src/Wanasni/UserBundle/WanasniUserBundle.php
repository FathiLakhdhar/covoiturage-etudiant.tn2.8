<?php

namespace Wanasni\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WanasniUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
