<?php

namespace Trait;

use Exception;
use FilesystemIterator;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use SplFileInfo;

trait TraitHttpReflection
{
    /**
     * Prepare les Reflections
     *
     * @throws ReflectionException
     * @throws Exception
     * @return ReflectionClass[]
     */
    protected function prepareReflections():array
    {
        $reflections = [];
        foreach ($this->prepareControllers($this->resourceDir) as $controller)
        {
            $reflections[] = new ReflectionClass($controller);
        }
        return $reflections;
    }

    /**
     * Prépare les chemins des contrôleurs à partir d'un répertoire
     *
     * @param string $path Chemin du répertoire à scanner
     * @return array Liste des noms complets des classes (avec namespace)
     * @throws RuntimeException Si le répertoire n'existe pas
     */
    protected function prepareControllers(string $path):array
    {
        // Vérification de l'existence du répertoire
        if (!is_dir($path)) {
            throw new \RuntimeException("Le répertoire '$path' n'existe pas");
        }

        $controllers = [];

        // Utilisation de RecursiveDirectoryIterator pour une meilleure performance
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as /** @var $file SplFileInfo */ $file) {
            // Vérifier que c'est un fichier PHP
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            // Extraire le namespace et le nom de la classe
            if (($fqcn = $this->extractFullyQualifiedClassName($file->getPathname())) !== null) {
                $controllers[] = $fqcn;
            }
        }

        return $controllers;
    }

    /**
     * Extrait le nom complet de la classe (FQCN) d'un fichier PHP
     *
     * @param string $filePath Chemin du fichier
     * @return string|null Le nom complet de la classe ou null si non trouvé
     */
    protected function extractFullyQualifiedClassName(string $filePath): ?string
    {
        $content = file_get_contents($filePath);

        if ($content === false) {
            return null;
        }

        // Extraction du namespace avec une regex plus robuste
        if (!preg_match('/^\s*namespace\s+([a-zA-Z0-9_\\\\]+)\s*;/m', $content, $namespaceMatches)) {
            return null;
        }

        // Extraction du nom de la classe (supporte aussi les classes abstraites et finales)
        if (!preg_match('/class\s+(\w+)/m', $content, $classMatches)) {
            return null;
        }

        return $namespaceMatches[1] . '\\' . $classMatches[1];
    }
}