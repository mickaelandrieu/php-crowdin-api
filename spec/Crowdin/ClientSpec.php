<?php

namespace spec\Crowdin;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Akeneo', 'my_key');
    }

    function it_has_a_project_identifier()
    {
        $this->getProjectIdentifier()->shouldReturn('Akeneo');
    }

    function it_has_a_project_api_key()
    {
        $this->getProjectApiKey()->shouldReturn('my_key');
    }
}