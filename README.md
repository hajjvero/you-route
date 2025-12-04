# You-Route

You-Route est une bibliothèque de routage PHP légère basée sur les attributs PHP. Elle permet de définir facilement des routes HTTP pour vos applications web en utilisant des annotations PHP modernes.

## Fonctionnalités

- Routage basé sur les attributs PHP 8+
- Support des méthodes HTTP (GET, POST, PUT, DELETE, etc.)
- Paramètres d'URL dynamiques
- Intégration facile avec les contrôleurs
- Support Docker pour le développement

## Prérequis

- PHP >= 8.4
- Composer

## Installation

Ajoutez la dépendance à votre projet via Composer :

```bash
composer require hajjvero/you-route
```

## Utilisation

### 1. Définition des routes

Utilisez l'attribut `#[Route]` pour définir vos routes :

```php
<?php

use YouRoute\Attribute\Route;
use YouRoute\Http\Response;

class HomeController
{
    #[Route(path: '/', name: 'home')]
    public function index(): Response
    {
        return new Response('<h1>Bienvenue sur la page d\'accueil</h1>');
    }
    
    #[Route(path: '/user/{id}', name: 'user_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return new Response("<h1>Profil utilisateur #$id</h1>");
    }
}
```

### 2. Configuration du routeur

```php
<?php

use YouRoute\Http\HttpRoute;
use YouRoute\Http\Request;

// Initialiser le routeur
$routeur = new HttpRoute(__DIR__ . '/src/Controller');

// Résoudre la requête
$request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
$routeur->resolve($request);
```

### 3. Structure du projet

```
your-project/
├── src/
│   └── Controller/
│       └── HomeController.php
├── vendor/
├── composer.json
└── index.php
```

## Méthodes HTTP supportées

Vous pouvez spécifier les méthodes HTTP autorisées pour chaque route :

```php
#[Route(path: '/api/users', methods: 'POST')] // Une seule méthode
#[Route(path: '/api/users/{id}', methods: ['PUT', 'DELETE'])] // Plusieurs méthodes
```

## Paramètres d'URL

Définissez des paramètres dynamiques dans vos URLs avec la syntaxe `{paramètre}` :

```php
#[Route(path: '/article/{id}/{slug}', name: 'article_show')]
public function show(int $id, string $slug): Response
{
    // $id et $slug sont automatiquement extraits de l'URL
}
```

## Préfixe de routes avec les classes

Vous pouvez également définir un préfixe pour toutes les routes d'une classe en appliquant l'attribut `#[Route]` au niveau de la classe :

```php
<?php

use YouRoute\Attribute\Route;
use YouRoute\Http\Response;

#[Route(path: '/api')]
class ApiController
{
    #[Route(path: '/users', methods: 'GET')]
    public function getUsers(): Response
    {
        return new Response('<h1>Liste des utilisateurs</h1>');
    }
    
    #[Route(path: '/users/{id}', methods: 'GET')]
    public function getUser(int $id): Response
    {
        return new Response("<h1>Détails de l'utilisateur #$id</h1>");
    }
}
```

Dans cet exemple, les routes seront accessibles via `/api/users` et `/api/users/{id}`.

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à soumettre des issues ou des pull requests.

## Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## Auteur

- **Hamza Hajjaji** - [hajjvero](https://github.com/hajjvero)