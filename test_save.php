<?php
$context = stream_context_create([
    'http' => [
        'method'        => 'POST',
        'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content'       => http_build_query([
            'files' => [
                'file-0' => [
                    'filename' => 'Main.java',
                    'content' => "public class LuasPersegiPanjang {\n    public static void main(String[] args) {\n        \n        // Nilai langsung ditentukan\n        int panjang = 10;\n        int lebar = 5;\n\n        // Perhitungan\n        int luas = panjang * lebar;\n\n        // Output\n        System.out.println(\"Panjang: \" + panjang);\n        System.out.println(\"Lebar: \" + lebar);\n        System.out.println(\"Luas persegi panjang adalah: \" + luas);\n    }\n}"
                ]
            ],
            'action' => 'run',
            'stdin' => ""
        ])
    ]
]);

$response = file_get_contents('http://127.0.0.1:8000/virtual-lab/execute', false, $context);
file_put_contents('scratch_out.html', $response);
