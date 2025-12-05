# You-Route

You-Route est une bibliothèque de routage PHP légère basée sur les attributs PHP. Elle permet de définir facilement des routes HTTP pour vos applications web en utilisant des attributs PHP modernes.

## Fonctionnalités

- Routage basé sur les attributs PHP 8+
- Support des méthodes HTTP (GET, POST, PUT, DELETE, etc.)
- Paramètres d'URL dynamiques
- Intégration facile avec les contrôleurs
- Architecture modulaire et extensible

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

require_once 'vendor/autoload.php';

use YouRoute\YouRouteKernel;
use YouRoute\Http\Request;

// Initialiser le kernel
$kernel = new YouRouteKernel();

// Résoudre la requête
$request = new Request();
$kernel->run(__DIR__ . '/src/Controller', $request);
```

### 3. Structure du projet

```
your-project/
├── src/
│   ├── Attribute/
│   │   └── Route.php
│   ├── Http/
│   │   ├── Abstract/
│   │   │   ├── AbstractRequest.php
│   │   │   └── AbstractResponse.php
│   │   ├── Request.php
│   │   └── Response.php
│   ├── Router/
│   │   ├── Abstract/
│   │   │   └── AbstractRouteResolver.php
│   │   ├── RouteCollection.php
│   │   ├── RouteDispatcher.php
│   │   └── RouteResolver.php
│   └── YouRouteKernel.php
├── composer.json
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

## Architecture

La bibliothèque suit une architecture modulaire organisée en plusieurs composants principaux :

### Composant Attribute
- [Route.php](src/Attribute/Route.php) : Définition de l'attribut Route utilisé pour annoter les contrôleurs

### Composant Http
- [Abstract/AbstractRequest.php](src/Http/Abstract/AbstractRequest.php) : Classe abstraite pour la gestion des requêtes HTTP
- [Abstract/AbstractResponse.php](src/Http/Abstract/AbstractResponse.php) : Classe abstraite pour la gestion des réponses HTTP
- [Request.php](src/Http/Request.php) : Implémentation concrète de la requête
- [Response.php](src/Http/Response.php) : Implémentation concrète de la réponse

### Composant Router
- [Abstract/AbstractRouteResolver.php](src/Router/Abstract/AbstractRouteResolver.php) : Classe abstraite pour la résolution des routes
- [RouteCollection.php](src/Router/RouteCollection.php) : Collection et gestion des routes
- [RouteDispatcher.php](src/Router/RouteDispatcher.php) : Dispatching des requêtes vers les contrôleurs appropriés
- [RouteResolver.php](src/Router/RouteResolver.php) : Résolution des routes depuis les attributs des classes

### Kernel
- [YouRouteKernel.php](src/YouRouteKernel.php) : Point d'entrée principal de la bibliothèque

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à soumettre des issues ou des pull requests.

## Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## Auteur

- **Hamza Hajjaji** - [hajjvero](https://github.com/hajjvero)