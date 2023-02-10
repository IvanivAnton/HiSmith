<?php

namespace App\DTO;

class GetNewsDTO
{
    private int $page = 1;
    private string $orderDirection = 'desc';
    private array $fields = [];

    /**
     * @param int $page
     */
    public function setPage(?int $page): void
    {
        if($page) {
            $this->page = $page;
        }
    }

    /**
     * @param string $orderDirection
     */
    public function setOrderDirection(?string $orderDirection): void
    {
        if($orderDirection) {
            $this->orderDirection = $orderDirection;
        }
    }

    /**
     * @param array $fields
     */
    public function setFields(?array $fields ): void
    {
        if($fields) {
            $this->fields = $fields;
        }
    }


    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }


}
