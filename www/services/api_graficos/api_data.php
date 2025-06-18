<?php
require_once './fetch_data.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$allowed_functions = [
    'getCountUsuarios',
    'getUsuarios',
    'getUsuariosPorMes',
    'getCountNotas',
    'getNotas',
    'getCountLembrete',
    'getLembrete'
];

$results = [];

if (isset($input['functions']) && is_array($input['functions'])) {
    foreach ($input['functions'] as $func) {
        if (in_array($func, $allowed_functions) && function_exists($func)) {
            try {
                $results[$func] = $func();
            } catch (Exception $e) {
                $results[$func] = ['error' => $e->getMessage()];
            }
        } else {
            $results[$func] = ['error' => 'Função não permitida'];
        }
    }
} else {
    $results['error'] = 'Nenhuma função informada';
}

echo json_encode($results);
exit;
?>