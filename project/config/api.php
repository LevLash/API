<?php

return [
    'host' => env('ROBOTS_URL', 'http://localhost:8080'),
    'routes' => [
        'register' => "/auth/sign-up/create-account",
        'login' => "/auth/sign-in",
        'list' => '/robot', //get
        'create' => '/robot', //post
        'on' => '/robot/on/{id}', //post
        'off' => '/robot/off/{id}', //post
        'status' => '/robot/status/{id}', //get
        'get_feedback' => '/robot/feedback/{id}', //get,post
        'add_feedback' => '/robot/feedback/{id}', //get,post
    ],
    'debug_mode' => env('ROBOTS_DEBUG', false)
];


//To get all administrator robots, send a GET request to `/robot`;
//
//To register new robot, send a POST request to `/robot`;
//
//To enable robot, send a POST request to `/robot/on/{id}`;
//
//The params of the request must contain:
//- `task=[task]` - task for robot (Mandatory, can't be null);
//
//To disable robot, send a POST request to `/robot/off/{id}`;
//
//To get robot status, send a POST request to `/robot/status/{id}`;
//
//To add robot feedback, send a POST request to `/feedback/{id}`;
//The params of the request must contain:
//- `feedback=[feedback]` - robot feedback (Mandatory, can't be null);
//- `rating=[3]` - task for robot (Mandatory, can't be null, number from 1 to 5);
//
//To get robot feedbacks, send a GET request to `/feedback/{id}`;
//
//In cases where the any of the fields has an invalid format, or field `date` is missing, the code `400 Bad Request` is returned to the client.
