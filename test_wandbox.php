<?php
$payload = [
    'compiler' => 'openjdk-jdk-21+35',
    'code'     => "import java.util.Scanner;\n\nclass LuasPersegiPanjang {\n    public static void main(String[] args) {\n        Scanner input = new Scanner(System.in);\n\n        // Input panjang dan lebar\n        System.out.print(\"Masukkan panjang: \");\n        int panjang = input.nextInt();\n\n        System.out.print(\"Masukkan lebar: \");\n        int lebar = input.nextInt();\n\n        // Proses perhitungan\n        int luas = panjang * lebar;\n\n        // Output hasil\n        System.out.println(\"Luas persegi panjang adalah: \" + luas);\n\n        input.close();\n    }\n}",
    'stdin'    => "5\n3",
    'options'  => ''
];

$jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);
$context = stream_context_create([
    'http' => [
        'method'        => 'POST',
        'header'        => "Content-Type: application/json\r\nAccept: application/json\r\n",
        'content'       => $jsonPayload,
        'ignore_errors' => true,
    ]
]);

$rawBody = file_get_contents('https://wandbox.org/api/compile.json', false, $context);
echo "Result:\n";
var_dump($http_response_header[0]);
echo $rawBody;
