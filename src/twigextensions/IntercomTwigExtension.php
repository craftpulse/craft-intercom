<?php

namespace craftpulse\intercom\twigextensions;

use craftpulse\intercom\Intercom;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IntercomTwigExtension extends AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name.
     */
    public function getName(): string
    {
        return 'Intercom';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('intercomChatbot', [$this, 'intercomChatbot']),
        ];
    }

    public function intercomChatbot(): void
    {
        print Intercom::$plugin->intercom->renderChatBot();
    }
}
