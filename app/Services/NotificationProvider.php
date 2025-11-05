<?php

namespace App\Services;

interface NotificationProvider {
    public function sendNotification(string $message): void;
}
