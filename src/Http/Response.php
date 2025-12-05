<?php

namespace YouRoute\Http;

use YouRoute\Http\Abstract\AbstractResponse;

class Response extends AbstractResponse
{
    /**
     * Envoyer la réponse au client
     *
     * @return void
     */
    public function send(): void
    {
        $this->sendHeaders();
        echo $this->content;
    }
}