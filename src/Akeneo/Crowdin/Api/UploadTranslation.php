<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;
use Akeneo\Crowdin\Client;
use Akeneo\Crowdin\FileReader;

/**
 *  Upload existing translations to your Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see https://crowdin.net/page/api/upload-translation
 */
class UploadTranslation extends AbstractApi
{
    /** @var FileReader */
    protected $fileReader;

    /** @var array */
    protected $translations;

    /** @var string */
    protected $locale;

    /** @var bool */
    protected $areDuplicatesImported = false;

    /** @var bool */
    protected $areEqualSuggestionsImported = false;

    /** @var bool */
    protected $areImportsAutoApproved = false;

    /**
     * @param Client     $client
     * @param FileReader $fileReader
     */
    public function __construct(Client $client, FileReader $fileReader)
    {
        parent::__construct($client);
        $this->fileReader = $fileReader;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (0 === count($this->translations)) {
            throw new InvalidArgumentException('There are no translations to upload.');
        }

        if (null === $this->locale) {
            throw new InvalidArgumentException('Locale is not set.');
        }

        $path = sprintf(
            "project/%s/upload-translation?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $data[] = [
            'name'      => 'import_duplicates',
            'contents'  => (int)$this->areDuplicatesImported
        ];
        $data[] = [
            'name'      => 'import_eq_suggestions',
            'contents'  => (int)$this->areEqualSuggestionsImported
        ];
        $data[] = [
            'name'      => 'auto_approve_imported',
            'contents'  => (int)$this->areImportsAutoApproved
        ];
        $data[] = [
            'name'      => 'language',
            'contents'  => $this->locale
        ];

        foreach ($this->translations as $crowdinPath => $localFile) {
            $data[] = [
                'name'       => 'files['.$crowdinPath.']',
                'contents'   => $this->fileReader->readStream($localFile)
            ];
        }

        $data = ['multipart' => $data];
        $response = $this->client->getHttpClient()->post($path, $data);

        return $response->getBody();
    }

    /**
     * @param string $crowdinPath the Crowdin file path
     * @param string $localPath   the local file path
     *
     * @throws InvalidArgumentException
     *
     * @return UploadTranslation
     */
    public function addTranslation($crowdinPath, $localPath)
    {
        if (!file_exists($localPath)) {
            throw new InvalidArgumentException(sprintf('File %s does not exist.', $localPath));
        }
        $this->translations[$crowdinPath] = $localPath;

        return $this;
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param bool $importsAutoApproved
     *
     * @throws InvalidArgumentException
     *
     * @return UploadTranslation
     */
    public function setImportsAutoApproved($importsAutoApproved)
    {
        if (!is_bool($importsAutoApproved)) {
            throw new InvalidArgumentException('A boolean is required.');
        }

        $this->areImportsAutoApproved = $importsAutoApproved;

        return $this;
    }

    /**
     * @return bool
     */
    public function areImportsAutoApproved()
    {
        return $this->areImportsAutoApproved;
    }

    /**
     * @param bool $duplicatesImported
     *
     * @throws InvalidArgumentException
     *
     * @return UploadTranslation
     */
    public function setDuplicatesImported($duplicatesImported)
    {
        if (!is_bool($duplicatesImported)) {
            throw new InvalidArgumentException('A boolean is required.');
        }

        $this->areDuplicatesImported = $duplicatesImported;

        return $this;
    }

    /**
     * @return bool
     */
    public function areDuplicatesImported()
    {
        return $this->areDuplicatesImported;
    }

    /**
     * @param bool $equalSuggestionsImported
     *
     * @throws InvalidArgumentException
     *
     * @return UploadTranslation
     */
    public function setEqualSuggestionsImported($equalSuggestionsImported)
    {
        if (!is_bool($equalSuggestionsImported)) {
            throw new InvalidArgumentException('A boolean is required.');
        }

        $this->areEqualSuggestionsImported = $equalSuggestionsImported;

        return $this;
    }

    /**
     * @return bool
     */
    public function areEqualSuggestionsImported()
    {
        return $this->areEqualSuggestionsImported;
    }

    /**
     * @param string $locale
     *
     * @return UploadTranslation
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}