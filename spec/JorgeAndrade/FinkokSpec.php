<?php

namespace spec\JorgeAndrade;

use PhpSpec\ObjectBehavior;

class FinkokSpec extends ObjectBehavior
{
    public function let()
    {
        $username = '';
        $password = '';
        $sandbox = true;
        $this->beConstructedWith($username, $password, $sandbox);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('JorgeAndrade\Finkok');
    }

    public function it_should_create_new_client()
    {
        $rfc = 'XAXX0101000';
        $this->createNewClient($rfc)->shouldReturn('Account Already exists');
    }

    public function it_should_active_a_suspend_client()
    {
        $rfc = 'XAXX0101000';

        $this->activeClient($rfc)->shouldReturn('Account was Activated successfully');
    }

    public function it_should_suspend_a_active_client()
    {
        $rfc = 'XAXX0101000';

        $this->suspendClient($rfc)->shouldReturn('Account was Suspended successfully');
    }

    public function it_should_get_a_lists_of_clients()
    {
        $this->getClients()->shouldReturnAnInstanceOf('stdClass');
    }

    public function it_should_get_a_client_by_rfc()
    {
        $rfc = 'XAXX0101000';
        $this->getClient($rfc)->shouldReturnAnInstanceOf('stdClass');
    }

    public function it_should_timbrar_factura_throw_an_FinkokException()
    {
        $xml = getcwd() . "/xml/.xml";
        $this->shouldThrow('\JorgeAndrade\Exceptions\FinkokException')->duringTimbrar($xml);
    }

    public function it_should_timbrar_factura()
    {
        $xml = getcwd() . "/xml/.xml";
        $this->timbrar($xml)->shouldReturnAnInstanceOf('stdClass');
    }

    public function it_should_cancelar_factura_throw_an_FinkokException()
    {
        $uuids = [''];
        $rfc = '';
        $this->shouldThrow('\JorgeAndrade\Exceptions\FinkokException')->duringCancelar($rfc, $uuids);
    }

}
