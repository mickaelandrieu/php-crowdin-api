<?php

namespace Akeneo\Crowdin\Api;

/**
 * Get supported languages list
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see http://crowdin.net/page/api/supported-languages
 */
class SupportedLanguages extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $path = 'supported-languages';
        
        if (!empty($this->urlParameters)) {
            $path .= sprintf('?%s', $this->getUrlQueryString());
        }
        
        $http     = $this->client->getHttpClient();
        $response = $http->get($path);

        return $response->getBody();
    }
}
