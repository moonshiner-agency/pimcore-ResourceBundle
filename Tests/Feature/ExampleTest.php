<?php

namespace Moonshiner\ResourceBundle\Tests\Feature;

use Pimcore\Model\Document\Page;
use AppBundle\Document\Areabrick\Hero;
use Moonshiner\ResourceBundle\Tests\TestCase;
use Moonshiner\ResourceBundle\Tests\Concerns\InteractsWithDatabase;

class ExampleTest extends TestCase
{
    /** @test */
    public function it_works()
    {
        $page = $this->createHomePage('home');

        $response = $this->get('/home');

        $response->assertOk();
    }

    /** @test */
    public function it_dysplays_a_hero()
    {
        $page = $this->createHomePage('home2')->withAreaBlock(Hero::class, 'content', [
            'title' => 'Hero title',
            'subline' => 'something',
            'text' => 'loooooong text'
        ]);

        $response = $this->get('/home2');

        $response->assertJson([
            "data" => [
                [
                    'title' => 'Hero title',
                    'subline' => 'something',
                    'text' => 'loooooong text'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_dysplays_a_multitple_heros()
    {
        $page = $this->createHomePage('home3')->withAreaBlock(Hero::class, 'content', [
            'title' => 'Hero1',
        ])->withAreaBlock(Hero::class, 'content', [
            'title' => 'Hero2',
        ]);

        $response = $this->get('/home3');

        $response->assertJsonFragment([
            'title' => 'Hero1',
            'title' => 'Hero2',
        ]);
    }

    /** @test */
    public function it_cleans_the_database()
    {

        $page = $this->createHomePage('same-home');

        $response = $this->get('/same-home');

        $response->assertOk();

        InteractsWithDatabase::refresh();

        $page = $this->createHomePage('same-home');

        $response = $this->get('/same-home');

        $response->assertOk();
    }

    protected function createHomePage( $key )
    {
        return $this->factory->create(Page::class, [
            'key' => $key,
            'controller' => '@AppBundle\Controller\ContentController',
            'action' => 'portal',
            'path' => "/{$key}"
        ]);
    }
}
