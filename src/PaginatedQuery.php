<?php


namespace App;


use \Exception;
use \PDO;

class PaginatedQuery
{

    private string $query;
    private string $queryCount;
    private ?PDO $piloteData;
    private int $perPage;
    private ?int $count = null;
    private ?array $items = null;

    public function __construct(
        string $query,
        string $queryCount,
        ?PDO $piloteData = null,
        $perPage = 12
    )
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->piloteData = $piloteData ?: Connect::getPdo();
        $this->perPage = $perPage;
    }

    public function getItems(string $classMapping): array{
        if($this->items === null){
            $currentPage = $this->getCurrentPage();
            $pages = $this->getCountPages();
            if($currentPage > $pages) {
                throw new Exception('Cette page n\'existe pas');
            }
            $offset = $this->perPage * ($currentPage - 1 );
            $this->items = $this->piloteData
                ->query($this->query . " LIMIT {$this->perPage} OFFSET {$offset}")
                ->fetchAll(PDO::FETCH_CLASS, $classMapping)
            ;
        }
        return $this->items;
    }

    public function previousLink(string $link)
    {
        $currentPage = $this->getCurrentPage();
        if($currentPage <= 1) return null;
        if($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return "<a href='{$link}' class='btn btn-primary'>&laquo; Page précédente</a>";
    }

    public function nextLink(string $link)
    {
        $currentPage = $this->getCurrentPage();
        if($currentPage >= $this->getCountPages()) return null;
        return "<a href='{$link}?page=".($currentPage + 1) ."' class='btn btn-primary ms-auto'>Page suivante &raquo;</a>";
    }

    private function getCurrentPage(): int
    {
        return URL::getPositiveInt('page', 1);
    }

    private function getCountPages(): int
    {
        if($this->count === null){
            $this->count = (int)$this->piloteData
                ->query($this->queryCount)
                ->fetch(PDO::FETCH_NUM)[0]
            ;
        }

        return ceil($this->count / $this->perPage);
    }
}