<?php

interface EnviaUnidadeMedidaPcpInterface
{
    public function get(string $url): array;
    public function create(array $unidadeMedida, string $url): object;
}
