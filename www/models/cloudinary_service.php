<?php
class CloudinaryService {
    private $cloudName;
    private $apiKey;
    private $apiSecret;
    
    public function __construct() {
        // Carregar variáveis do .env
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $_ENV[trim($key)] = trim($value);
                }
            }
        }
        
        $this->cloudName = 'dqkdvkuyk'; // Seu cloud name
        $this->apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? '';
        $this->apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';
    }
    
    public function uploadFile($file, $resourceType = 'auto') {
        $timestamp = time();
        $publicId = 'nota_' . uniqid();
        
        // Preparar parâmetros para upload
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
            'resource_type' => $resourceType
        ];
        
        // Gerar assinatura
        $signature = $this->generateSignature($params);
        $params['signature'] = $signature;
        $params['api_key'] = $this->apiKey;
        
        // Preparar dados para upload
        $postData = $params;
        $postData['file'] = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
        
        // Fazer upload via cURL
        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/{$resourceType}/upload";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $result = json_decode($response, true);
            return [
                'success' => true,
                'url' => $result['secure_url'],
                'public_id' => $result['public_id']
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Erro no upload: ' . $response
            ];
        }
    }
    
    private function generateSignature($params) {
        // Remover signature e api_key se existirem
        unset($params['signature'], $params['api_key']);
        
        // Ordenar parâmetros
        ksort($params);
        
        // Criar string de parâmetros
        $paramString = '';
        foreach ($params as $key => $value) {
            $paramString .= $key . '=' . $value . '&';
        }
        $paramString = rtrim($paramString, '&');
        
        // Gerar assinatura
        return sha1($paramString . $this->apiSecret);
    }
    
    public function deleteFile($publicId, $resourceType = 'image') {
        $timestamp = time();
        
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp
        ];
        
        $signature = $this->generateSignature($params);
        $params['signature'] = $signature;
        $params['api_key'] = $this->apiKey;
        
        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/{$resourceType}/destroy";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
}
?>