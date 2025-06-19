<?php
require __DIR__ . '/../models/log_atividades.php';

function gerar_linhas_tabela_atividades() {
    $atividades = buscar_atividades();
    if (!$atividades) return '';

    $html = '';
    foreach ($atividades as $atividade) {
        $html .= "<tr>\n";
        $html .= "    <td>{$atividade['id_usuario']}</td>\n";
        $html .= "    <td>{$atividade['data_hora']}</td>\n";
        $html .= "    <td>{$atividade['acao']}</td>\n";
        $html .= "</tr>\n";
    }
    return $html;
}
