<?php

namespace Akeneo\Crowdin\Api;

/**
 * Get project details
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see http://crowdin.net/page/api/info
 */
class Info extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->addUrlParameter('key', $this->client->getProjectApiKey());
        
        $path = sprintf(
            "project/%s/info?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );
        $response = $this->client->getHttpClient()->get($path);

        return $response->getBody();
    }
}
