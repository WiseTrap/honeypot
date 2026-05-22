<?php

namespace WiseTrap\Core;

class UpdateService
{
    public function check(): array
    {
        return [
            'success' => false,
            'has_update' => false,
            'message' => 'Petra wasn\'t built in a day! (Feature under development)'
        ];
    }
    public function install(): array
    {
        return [
            'success' => false,
            'message' => 'Petra wasn\'t built in a day! (Feature under development)'
        ];
    }
}