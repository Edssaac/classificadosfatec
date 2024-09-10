<?php

namespace Library;

use Exception;

class Upload
{
    public static function image($file, $message = 'novo produto'): array
    {
        $image = file_get_contents($file['foto']['tmp_name']);

        $image_data = pathinfo($file['foto']['name']);

        $file_name = uniqid() . '.' . $image_data['extension'];

        $post_data = [
            'message' => $message,
            'content' => base64_encode($image),
            'committer' => [
                'name'  => $_ENV["UPLOAD_NAME"],
                'email' => $_ENV["UPLOAD_EMAIL"]
            ]
        ];

        $post_data = json_encode($post_data);

        $headers = [
            'Content-Type: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 YaBrowser/19.9.3.314 Yowser/2.5 Safari/537.36',
            'Authorization: token ' . $_ENV["UPLOAD_TOKEN"]
        ];

        $curl = curl_init($_ENV["UPLOAD_PATH"] . $file_name);

        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST   => 'PUT',
            CURLOPT_POSTFIELDS      => $post_data,
            CURLOPT_RETURNTRANSFER  => TRUE,
            CURLOPT_HTTPHEADER      => $headers
        ]);

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        if (isset($response['message'])) {
            throw new Exception('Erro ao salvar imagem no GitHub: ' . $response['message']);
        }

        return [
            'sha' => $response['content']['sha'],
            'name' => $response['content']['name']
        ];
    }
}
