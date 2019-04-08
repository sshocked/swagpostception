<?php

namespace swagpostception\interfaces;

/**
 * Interface Generateable
 * @package swagpostception\interfaces
 */
interface Generateable
{
    /**
     * @param object $data
     * @return mixed
     */
    public function generate ($data): bool;

}