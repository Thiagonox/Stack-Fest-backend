<?php

namespace Stack\Fest\Config;

class Response{
    public static function json(int $status, $dados = [])
    {
        http_response_code($status);
        echo json_encode($dados);
        exit;
    }
}
