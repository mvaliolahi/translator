<?php
/**
 * Created by PhpStorm.
 * User: m.valiolahi
 * Date: 11/7/2017
 * Time: 7:04 PM
 */

namespace Tests\Unit;


use Mvaliolahi\Translator\Translator;
use Tests\TestCase;

/**
 * Class TranslatorTest
 * @package Tests\Unit
 */
class TranslatorTest extends TestCase
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected $locale = 'en';

    /**
     * @var string
     */
    protected $resourcePath = __DIR__ . '/../Resources/lang/';

    /** @test */
    public function it_should_be_able_to_set_path_for_resources()
    {
        $this->assertEquals(
            "{$this->resourcePath}{$this->locale}",
            $this->translator->getResourcesPath()
        );
    }

    /** @test */
    public function it_should_be_able_to_extract_file_and_search_key()
    {
        [$file, $key] = $this->callMethod(
            'extractFileAndSearchKey',
            $this->translator,
            ['messages.success']
        );

        $this->assertEquals('messages', $file);
        $this->assertEquals('success', $key);
    }

    /** @test */
    public function it_should_be_able_to_get_translate_from_file()
    {
        $result = $this->callMethod('translate', $this->translator, ['messages', 'test']);

        $this->assertEquals('Test', $result);
    }

    /**
     * @test
     * @expectedException \Mvaliolahi\Translator\Exceptions\TranslationFileNotFoundException
     */
    public function it_should_throw_an_exception_if_translation_file_not_found()
    {
        $translator = new Translator([
            'locale' => 'fa',
            'resourcePath' => __DIR__ . '/../Resources/xxx'
        ]);
        $result = $translator->of('test');
    }

    /** @test */
    public function it_should_be_able_to_translate_messages_in_english()
    {
        $result = $this->translator->of('test');
        $this->assertEquals('Test', $result);
    }

    /** @test */
    public function it_should_be_able_to_translate_messages_persian()
    {
        $this->translator = new Translator([
            'locale' => 'fa',
            'resourcePath' => $this->resourcePath
        ]);

        $result = $this->translator->of('test');
        $this->assertEquals('امتحان', $result);
    }

    /** @test */
    public function it_should_replace_place_holders_in_translation()
    {
        $result = $this->translator->of('login', ['user' => 'Meysam Valiolahi']);
        $this->assertEquals('Hello dear, Meysam Valiolahi', $result);
    }

    /** @test */
    public function it_should_replace_more_than_one_place_holders_in_translation()
    {
        $result = $this->translator->of('permission', [
            'user' => 'Meysam Valiolahi',
            'section' => 'Post-Article'
        ]);
        $this->assertEquals('Dear Meysam Valiolahi, you have not access to Post-Article.', $result);
    }

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->translator = new Translator([
            'locale' => $this->locale,
            'resourcePath' => $this->resourcePath
        ]);
    }
}