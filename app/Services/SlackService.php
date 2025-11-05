<?php

namespace App\Services;

class SlackService implements NotificationProvider {
    public function sendNotification(string $message): void
    {
        // Here we would implement Slack API call
        // For example: Http::post('your_slack_webhook_url', [...] )
    }
}
