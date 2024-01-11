<?php declare(strict_types=1);

namespace Wordless\Application\Mounters\Stub;

use Wordless\Infrastructure\Mounters\StubMounter;

class ThemeStyleCssStubMounter extends StubMounter
{
    private const STUB_UNFILLED_PLACE_KEY = '<[wordless_input_goes_here]>';

    /**
     * @param array $replace_content_dictionary
     * @return $this
     */
    public function setReplaceContentDictionary(array $replace_content_dictionary): StubMounter
    {
        $this->replace_content_dictionary = [
            self::STUB_UNFILLED_PLACE_KEY => $this->mountReplaceContent($replace_content_dictionary),
        ];

        return $this;
    }

    protected function relativeStubFilename(): string
    {
        return 'style.css';
    }

    /**
     * @param string[] $replace_content
     * @return string
     */
    private function mountReplaceContent(array $replace_content): string
    {
        $content = '';

        foreach ($replace_content as $wordpress_info_name => $wordpress_info_description) {
            if (empty($wordpress_info_name) || empty($wordpress_info_description)) {
                continue;
            }

            $content .= $content . PHP_EOL . "$wordpress_info_name: $wordpress_info_description";
        }

        return $content;
    }
}
