<?php

namespace MF\Controller;

/**
 * Classe responsável por fazer o upload de arquivos em um repositório
 * do GitHub, usando a Rest API do GitHub.
 * Documentação: https://docs.github.com/en/rest/reference/repos#create-or-update-file-contents
 * @author Edssaac
 * @license MIT
 */
class Upload
{
    /**
     * Responsável por guardar uma mensagem de erro. 
     * @var $erro
     */
    private $erro;

    /**
     * Responsável por guardar o nome de usuário do GitHub. 
     * @var $name
     */
    private $name;

    /**
     * Responsável por guardar o email do GitHub. 
     * @var $email
     */
    private $email;

    /**
     * Responsável por guardar o token de segurança. 
     * @var $email
     */
    private $token;

    public function __construct()
    {
        $this->name  = getenv("UPLOAD_NAME");
        $this->email = getenv("UPLOAD_EMAIL");
        $this->token = getenv("UPLOAD_TOKEN");
    }

    /**
     * Método responsável por realizar o upload de um documento para um repositório do GitHub.
     * @param $arquivo => Array com os dados do arquivo a ser feito upload.
     * @param $mensagem => Mensagem de commit.
     * @return boolean
     */
    public function uploadImagem($arquivo, $mensagem = 'novo produto')
    {
        // Verificando se foi passado o arquivo:
        if (!isset($arquivo['foto']['name'])) {
            $this->erro = 'Arquivo vazio ou inválido.';
            return false;
        }

        // Verificando se o caminho do arquivo é valido:
        if (!file_exists($arquivo['foto']['tmp_name'])) {
            $this->erro = 'Caminho do arquivo inválido ou inexistente.';
            return false;
        }

        // Pegando a imagem:
        $imagem = file_get_contents($arquivo['foto']['tmp_name']);
        // Pegando as informações da imagem:
        $imagemInfo = pathinfo($arquivo['foto']['name']);
        // Criando o nome do arquivo:
        $arquivoNome = uniqid() . '.' . $imagemInfo['extension'];

        // Configurações necessárias:
        $post = array(
            // 'sha'=>file_get_contents("sha.txt"), // Usar SHA apenas quando precisar atualizar um arquivo já enviado.
            'message' =>     $mensagem,
            'content' =>     base64_encode($imagem),
            'committer' =>   array(
                'name' =>    $this->name,
                'email' =>  $this->email
            )
        );

        // Passando os parâmetros para o formato JSON:
        $post = json_encode($post);

        // Criando o header:
        $header = [
            'Content-Type: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 YaBrowser/19.9.3.314 Yowser/2.5 Safari/537.36',
            'Authorization: token ' . $this->token
        ];

        // Iniciando o curl:
        $curl = curl_init('https://api.github.com/repos/Edssaac/cf_storage/contents/produtos/' . $arquivoNome);

        // Configurando o curl:
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST =>    'PUT',
            CURLOPT_POSTFIELDS =>       $post,
            CURLOPT_RETURNTRANSFER =>   TRUE,
            CURLOPT_HTTPHEADER =>       $header
        ]);

        // Executando:
        $response = curl_exec($curl);

        // Fechando a conexão:
        curl_close($curl);

        // Transformando o resultando em um Array:
        $arrayResultado = json_decode($response, true);

        // Verificando se houve uma mensagem de erro:
        if (isset($arrayResultado['message'])) {
            $this->erro = 'API retornou o seguinte erro: ' . $arrayResultado['message'];
            var_dump($this->erro);
            return false;
        }

        $dados = [
            'sha'   => $arrayResultado['content']['sha'],
            'name'  => $arrayResultado['content']['name']
        ];

        return $dados;
    }
}

?>