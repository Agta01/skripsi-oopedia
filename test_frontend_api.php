<?php
$context = stream_context_create([
    'http' => [
        'method'        => 'POST',
        'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content'       => http_build_query([
            'files' => [
                'file-0' => [
                    'filename' => 'Main.java',
                    'content' => "import java.util.Scanner;\n\npublic class LuasPersegiPanjang {\n    public static void main(String[] args) {\n        Scanner input = new Scanner(System.in);\n\n        System.out.print(\"Masukkan panjang: \");\n        int panjang = input.nextInt();\n\n        System.out.print(\"Masukkan lebar: \");\n        int lebar = input.nextInt();\n\n        int luas = panjang * lebar;\n\n        System.out.println(\"Luas persegi panjang adalah: \" + luas);\n\n        input.close();\n    }\n}"
                ]
            ],
            'action' => 'run',
            'stdin' => "5\n3"
        ])
    ]
]);

$response = file_get_contents('http://127.0.0.1:8000/virtual-lab/execute', false, $context);
if (preg_match('/Terminal Output.*?<pre[^>]*>(.*?)<\/pre>/s', $response, $m)) {
    echo "Output: \n" . strip_tags($m[1]);
} else {
    echo "Not found";
}
