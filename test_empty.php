<?php
$context = stream_context_create([
    'http' => [
        'method'        => 'POST',
        'header'        => "Content-Type: application/json\r\nAccept: application/json\r\n",
        'content'       => json_encode([
            'compiler' => 'openjdk-jdk-21+35',
            'code'     => 'class Main { public static void main(String[] args) { System.out.println("Hello"); } }',
            'stdin'    => '',
            'options'  => ''
        ]),
        'ignore_errors' => true,
    ]
]);
$rawBody = file_get_contents('https://wandbox.org/api/compile.json', false, $context);
echo "Result:\n";
var_dump($http_response_header[0]);
echo $rawBody;
