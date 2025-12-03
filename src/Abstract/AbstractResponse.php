<?php

abstract class AbstractResponse
{
    /**
     * Contenu de la réponse
     *
     * @var string
     */
    protected string $content = '';

    /**
     * Code de statut HTTP
     *
     * @var int
     */
    protected int $statusCode = 200;

    /**
     * En-têtes de la réponse
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Versions HTTP supportées
     */
    protected const array SUPPORTED_HTTP_VERSIONS = ['1.0', '1.1', '2.0'];

    /**
     * Codes de statut HTTP courants
     */
    protected const array HTTP_STATUS_CODES = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable'
    ];

    /**
     * Constructeur
     *
     * @param string $content Contenu de la réponse
     * @param int $statusCode Code de statut HTTP
     * @param array $headers En-têtes de la réponse
     */
    public function __construct(string $content = '', int $statusCode = 200, array $headers = [])
    {
        $this->setContent($content);
        $this->setStatusCode($statusCode);
        $this->setHeaders($headers);
    }

    /**
     * Définir le contenu de la réponse
     *
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Obtenir le contenu de la réponse
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Définir le code de statut HTTP
     *
     * @param int $statusCode
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        if (!array_key_exists($statusCode, self::HTTP_STATUS_CODES)) {
            throw new InvalidArgumentException("Code de statut HTTP invalide: $statusCode");
        }
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Obtenir le code de statut HTTP
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Ajouter un en-tête à la réponse
     *
     * @param string $name Nom de l'en-tête
     * @param string $value Valeur de l'en-tête
     * @return self
     */
    public function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Définir les en-têtes de la réponse
     *
     * @param array $headers
     * @return self
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Obtenir un en-tête spécifique ou tous les en-têtes
     *
     * @param string|null $name Nom de l'en-tête
     * @return mixed
     */
    public function getHeader(?string $name = null): mixed
    {
        if ($name === null) {
            return $this->headers;
        }
        return $this->headers[$name] ?? null;
    }

    /**
     * Supprimer un en-tête
     *
     * @param string $name Nom de l'en-tête
     * @return self
     */
    public function removeHeader(string $name): self
    {
        unset($this->headers[$name]);
        return $this;
    }

    /**
     * Définir le type de contenu
     *
     * @param string $contentType
     * @return self
     */
    public function setContentType(string $contentType): self
    {
        return $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Définir une réponse JSON
     *
     * @param array $data Données à encoder en JSON
     * @param int $statusCode Code de statut HTTP
     * @return self
     */
    public function json(array $data, int $statusCode = 200): self
    {
        $this->setContent(json_encode($data));
        $this->setStatusCode($statusCode);
        $this->setContentType('application/json');
        return $this;
    }

    /**
     * Redirection vers une URL
     *
     * @param string $url URL de redirection
     * @param int $statusCode Code de statut (301 ou 302)
     * @return self
     */
    public function redirect(string $url, int $statusCode = 302): self
    {
        $this->setStatusCode($statusCode);
        $this->addHeader('Location', $url);
        $this->setContent('');
        return $this;
    }

    /**
     * Envoyer les en-têtes HTTP
     *
     * @return void
     */
    protected function sendHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        // Envoyer le statut HTTP
        http_response_code($this->statusCode);

        // Envoyer les en-têtes
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
    }
}