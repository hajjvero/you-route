<?php

namespace Http;

class Response extends \AbstractResponse
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