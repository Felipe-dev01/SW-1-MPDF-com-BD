<?php

require 'vendor/autoload.php';

// Configuração do meu banco
$host = '127.0.0.1:3306';
$dbname = 'Docker MySQL';
$username = 'root';
$password = '';

try {
    $pdo = new PDO('mysql:host='.$host. ';dbname=' .$dbname. ';charset=utf8',
    $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta o SQL 
    $query = "SELECT titulo, autor, ano_publicacao, resumo FROM livros";
    $stmt = $pdo -> prepare($query);
    $stmt -> execute();

    // Recupera os dados
    $livros = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    // Cria uma instância
    $mpf= new \Mpdf\Mpdf();

    // Configura o conteúdo do PDF
    $html = '<h1> Biblioteca - Lista de Livros </h1>';
    $html .= '<table border="1" cellpadding="10" cellspacing="0" width="100%">';
    $html .=
    '<tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Ano de Publicação</th>
        <th>Resumo</th>
    </tr>';

    // Popula o HTML com os dados dos livros
    foreach ($livros as $livro) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($livro ['titulo']) . '</td>';
        $html .= '<td>' . htmlspecialchars($livro ['autor']) . '</td>';
        $html .= '<td>'  . htmlspecialchars($livro['ano_publicacao']) . '</td>';
        $html .= '<td>' . htmlspecialchars($livro ['resumo']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    $mpdf -> WriteHTML($html);

    $mpdf  -> Output('lista_de_livros.pdf', \Mpdf\Output\Destination::DOWNLOAD);
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e -> getMessage();  
} catch (\Mpdf\MpdfException $e) {
    echo "Erro ao gerar o PDF: " . $e -> getMessage();  
}

