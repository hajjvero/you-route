<?php

namespace YouRoute\Router\Abstract;
use FilesystemIterator;
use SplFileInfo;

abstract class AbstractRouteResolver
{
    protected function loadAllClassNames(string $resourceDir): array
    {
        // Vérification de l'existence du répertoire
        if (!is_dir($resourceDir)) {
            throw new \RuntimeException("Le répertoire '$resourceDir' n'existe pas");
        }

        $controllers = [];

        // Utilisation de RecursiveDirectoryIterator pour une meilleure performance
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($resourceDir, FilesystemIterator::SKIP_DOTS),
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

    private function extractFullyQualifiedClassName(string $filePath): ?string
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