<?php

declare(strict_types=1);

return [

    'form' => [
        'submit' => 'Submit',
        'select' => [
            'placeholder' => 'Select option',
            'search_placeholder' => 'Search…',
        ],
        'password' => [
            'label' => 'Password',
            'confirmation_label' => 'Confirm password',
        ],
        'file' => [
            'placeholder' => 'No file chosen',
        ],
        'dropzone' => [
            'title' => 'Drop files here or click to upload',
            'sub' => 'Supports any file type',
            'aria_label' => 'Upload files',
            'remove' => 'Remove file',
        ],
        'avatar' => [
            'upload' => 'Upload photo',
            'remove' => 'Remove',
        ],
        'image_grid' => [
            'add' => 'Add image',
            'remove' => 'Remove image',
        ],
        'repeater' => [
            'add' => 'Add item',
            'remove' => 'Remove',
        ],
    ],

    'alert' => [
        'dismiss' => 'Dismiss',
    ],

    'banner' => [
        'dismiss' => 'Dismiss',
    ],

    'stepper' => [
        'step_of' => 'Step :current of :total',
    ],

    'error_page' => [
        'maintenance_progress' => 'Maintenance progress',
        '404' => [
            'title' => 'Page not found',
            'description' => "The page you're looking for has moved, been deleted, or never existed. Double-check the URL or head back to the dashboard.",
        ],
        '500' => [
            'title' => 'Something went wrong',
            'description' => 'We hit an unexpected snag on our end. Our team has been automatically notified and is working on a fix. Try refreshing in a moment.',
        ],
        '403' => [
            'title' => 'Access denied',
            'description' => "You don't have permission to view this page. If you think this is a mistake, contact your account administrator or reach out to support.",
        ],
        '401' => [
            'title' => 'Session expired',
            'description' => "Your session timed out for security. Sign in again to continue \u{2014} your progress has been saved and you'll pick up right where you left off.",
        ],
        '503' => [
            'title' => 'Back very shortly',
            'description' => "We're performing scheduled maintenance to improve reliability and performance. Sit tight \u{2014} we'll be up again in just a few minutes.",
        ],
    ],

    'calendar' => [
        'placeholder' => 'Select date',
        'range_placeholder' => 'Select range',
        'today' => 'Today',
        'apply' => 'Apply',
        'clear' => 'Clear',
        'prev_month' => 'Previous month',
        'next_month' => 'Next month',
        'time' => 'Time',
        'start_time' => 'Start time',
        'end_time' => 'End time',
    ],

];
