<?php

namespace App\FrontApi\Dto\Interfaces\Request;

interface CreateEmployeeRequestInterface
{
    /**
     * @return string
     */
    public function getFirstName(): string;
    /**
     * @return string
     */
    public function getLastName(): string;
    /**
     * @return string
     */
    public function getEmail(): string;
    /**
     * @return string
     */
    public function getParentEmail(): string;
    /**
     * @return int
     */
    public function getCategoryId(): int;
}
