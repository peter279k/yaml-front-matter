<?php

namespace spec\Spatie\YamlFrontMatter;

use PhpSpec\ObjectBehavior;
use Spatie\YamlFrontMatter\Document;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class YamlFrontMatterSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(YamlFrontMatter::class);
    }

    public function it_can_parse_valid_front_matter()
    {
        $contents = "
        ---
        foo: bar
        ---

        Lorem ipsum.
        ";

        $this->parse($contents)->shouldHaveType(Document::class);
        $this->parse($contents)->shouldHaveFrontMatter(['foo' => 'bar']);
        $this->parse($contents)->shouldHaveBodyContaining('Lorem ipsum.');
    }

    public function it_falls_back_to_empty_front_matter_with_the_original_as_body()
    {
        $contents = "
        ---
        foo: bar
        --

        Lorem ipsum.
        ";

        $this->parse($contents)->shouldHaveType(Document::class);
        $this->parse($contents)->shouldHaveFrontMatter([]);
        $this->parse($contents)->shouldHaveBodyContaining('foo: bar');
        $this->parse($contents)->shouldHaveBodyContaining('Lorem ipsum.');
    }

    public function it_parses_contents_without_a_body()
    {
        $contents = "
        ---
        foo: bar
        ---
        ";

        $this->parse($contents)->shouldHaveType(Document::class);
        $this->parse($contents)->shouldHaveFrontMatter(['foo' => 'bar']);
    }

    public function getMatchers() : array
    {
        return [
            'haveFrontMatter' => function (Document $subject, $value) {
                return $subject->matter() === $value;
            },
            'haveBodyContaining' => function (Document $subject, $value) {
                return str_contains($subject->body(), $value);
            },
        ];
    }
}
