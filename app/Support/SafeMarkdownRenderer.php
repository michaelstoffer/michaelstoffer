<?php

namespace App\Support;

use League\CommonMark\GithubFlavoredMarkdownConverter;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerAction;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class SafeMarkdownRenderer
{
    private GithubFlavoredMarkdownConverter $markdown;

    private HtmlSanitizer $sanitizer;

    public function __construct()
    {
        $this->markdown = new GithubFlavoredMarkdownConverter([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);

        $config = (new HtmlSanitizerConfig)
            ->allowSafeElements()
            ->allowElement('code', ['class'])
            ->allowLinkSchemes(['http', 'https', 'mailto'])
            ->allowMediaSchemes(['http', 'https'])
            ->allowRelativeLinks()
            ->allowRelativeMedias()
            ->forceAttribute('a', 'rel', 'noopener noreferrer')
            ->dropAttribute('style', '*')
            ->defaultAction(HtmlSanitizerAction::Block)
            ->withMaxInputLength(1_000_000);

        foreach (['script', 'style', 'iframe', 'object', 'embed', 'svg', 'math', 'form', 'input', 'button', 'textarea', 'select', 'option'] as $element) {
            $config->dropElement($element);
        }

        $this->sanitizer = new HtmlSanitizer($config);
    }

    public function render(string $markdown): string
    {
        return $this->sanitizer->sanitize((string) $this->markdown->convert($markdown));
    }
}
