<?php

namespace Wanasni\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WanasniMessageBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}
