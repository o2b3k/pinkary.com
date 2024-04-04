<?php

declare(strict_types=1);

use App\Mail\PendingNotifications;
use App\Models\Question;
use App\Models\User;

test('envelope', function () {
    $user = User::factory()->create();

    Question::factory()->create([
        'to_id' => $user->id,
    ]);

    $mail = new PendingNotifications($user);

    $envelope = $mail->envelope();

    expect($envelope->subject)
        ->toBe('🌸 Pinkary: You Have 1 Notification! - '.now()->format('F j, Y'));
});

test('content', function () {
    $user = User::factory()->create();

    Question::factory()->create([
        'to_id' => $user->id,
    ]);

    $mail = new PendingNotifications($user);

    foreach ([
        '# Hello, '.$user->name.'!',
        "We've noticed you have 1 notification. You can view notifications by clicking the button below.",
        'If you no longer wish to receive these emails, you can change your "Mail Preference Time" in your [profile settings]('.config('app.url').'/profile).',
    ] as $line) {
        $mail->assertSeeInText($line);
    }
});