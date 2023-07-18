<?php
/*
Plugin Name: Meu Plugin de Receita
Description: Plugin que exibe receita a partir de um arquivo CSV.
*/

// Registra o shortcode [csv_receita]
add_shortcode('my_csv_data', 'exibir_receita_csv');

function exibir_receita_csv() {
    ob_start();

    // Caminho para o arquivo CSV de receita
    $caminho_arquivo = plugin_dir_path(__FILE__) . 'imagens.csv';

    if (file_exists($caminho_arquivo)) {
        if (($handle = fopen($caminho_arquivo, 'r')) !== false) {
            echo '<ul>';

            // Ignorar o cabeçalho do CSV
            fgetcsv($handle);

            // Loop pelas linhas do CSV
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                echo '<li>';

                // Exibir a primeira coluna (imagem)
                if (isset($data[0]) && filter_var($data[0], FILTER_VALIDATE_URL)) {
                    echo '<div style="display: flex; align-items: center;">';
                    echo '<img src="' . esc_url($data[0]) . '" alt="" style="max-width: 200px; margin-right: 10px;">';

                    // Exibir o texto
                    if (isset($data[1])) {
                        echo '<p>' . esc_html($data[1]) . '</p>';
                    }

                    echo '</div>';
                }

                echo '</li>';
            }

            echo '</ul>';

            fclose($handle);
        }
    } else {
        echo 'O arquivo CSV de receita não foi encontrado.';
    }

    return ob_get_clean();
}
