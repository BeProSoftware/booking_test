<?php

namespace AppTest\Action;

use App\Action\BookingPageAction;
use App\Action\BookingPageFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class BookingPageFactoryTest extends TestCase
{
    /** @var ContainerInterface */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $router = $this->prophesize(RouterInterface::class);

        $this->container->get(RouterInterface::class)->willReturn($router);
    }

    public function testFactoryWithoutTemplate()
    {
        $factory = new BookingPageFactory();
        $this->container->has(TemplateRendererInterface::class)->willReturn(false);

        $this->assertInstanceOf(BookingPageFactory::class, $factory);

        $bookingPage = $factory($this->container->reveal());

        $this->assertInstanceOf(BookingPageAction::class, $bookingPage);
    }

    public function testFactoryWithTemplate()
    {
        $factory = new BookingPageFactory();
        $this->container->has(TemplateRendererInterface::class)->willReturn(true);
        $this->container
            ->get(TemplateRendererInterface::class)
            ->willReturn($this->prophesize(TemplateRendererInterface::class));

        $this->assertInstanceOf(BookingPageFactory::class, $factory);

        $bookingPage = $factory($this->container->reveal());

        $this->assertInstanceOf(BookingPageAction::class, $bookingPage);
    }
}
