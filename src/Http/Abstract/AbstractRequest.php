<?php

namespace YouRoute\Http\Abstract;
abstract class AbstractRequest
{
    /**
     * Paramètres de requête (GET)
     *
     * @var array
     */
    protected array $query = [];

    /**
     * Corps de la requête (POST)
     *
     * @var array
     */
    protected array $body = [];

    /**
     * En-têtes de la requête
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Méthode HTTP de la requête
     *
     * @var string
     */
    public string $method {
        get {
            return $this->method;
        }
    }

    /**
     * URI de la requête
     *
     * @var string
     */
    public string $uri {
        get {
            return $this->uri;
        }
    }

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Initialiser la requête avec les données HTTP
     *
     * @return void
     */
    private function initialize(): void
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        $this->headers = getallheaders() ?: [];

        // Remplir les paramètres de requête
        $this->query = $_GET ?? [];

        // Remplir le corps de la requête selon la méthode
        if ($this->isMethod('POST') || $this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $contentType = $this->headers['Content-Type'] ?? '';

            // Traiter le corps de la requête en JSON
            if (str_contains($contentType, 'application/json')) {
                $input = file_get_contents('php://input');
                $this->body = json_decode($input, true) ?: [];
            } else {
                $this->body = $_POST ?? [];
            }
        } else {
            $this->body = [];
        }
    }

    /**
     * Obtenir une valeur du paramètre de requête ou tout les paramètres
     *
     * @param ?string $key
     * @param mixed $default
     * @return mixed
     */
    public function getQuery(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->query;
        }

        return $this->query[$key] ?? $default;
    }

    /**
     * Obtenir une valeur du corps de la requête ou tout le corps
     *
     * @param ?string $key
     * @param ?mixed $default
     * @return mixed
     */
    public function getBody(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->body;
        }

        return $this->body[$key] ?? $default;
    }

    /**
     * Obtenir une valeur d'en-tête ou tous les en-têtes
     *
     * @param ?string $key
     * @param mixed $default
     * @return mixed
     */
    public function getHeader(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->headers;
        }
        return $this->headers[$key] ?? $default;
    }

    /**
     * Vérifier si la requête utilise une méthode spécifique
     *
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return strtoupper($this->method) === strtoupper($method);
    }
}