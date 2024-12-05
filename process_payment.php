<?php
// Verifica se houve uma sessão iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifique se os dados estão disponíveis na sessão
if (!isset($_SESSION['fn_areceber'])) {
    echo "Nenhum dado encontrado! Por favor, acesse primeiro o arquivo get_receivables.";
    exit;
}

// Recuperar os dados armazenados na sessão
$data = $_SESSION['fn_areceber'];

// Verificar e acessar as chaves de forma segura
$id = isset($data['id']) ? $data['id'] : 'Não definido';
$filial_id = isset($data['filial_id']) ? $data['filial_id'] : 'Não definido';
$data_atual = date('d/m/Y'); // Obtendo a data atual no formato brasileiro
$id_conta = isset($data['id_conta']) ? (int)$data['id_conta'] : 0;
$tipo_recebimento = isset($data['tipo_recebimento']) ? $data['tipo_recebimento'] : 'Não definido';
$documento = isset($data['documento']) ? $data['documento'] : 'Não definido';
$valor = isset($data['valor_aberto']) ? (float)$data['valor_aberto'] : 0.00;// Garantir que o valor_aberto seja um número

// Aqui você pode definir os valores para o POST (se necessário, você pode mapear mais valores do array $data)
$_conta = 2;
$historico = "Fatura paga por API";

// Dados a serem enviados no POST
$post_fields = json_encode(array(
    "filial_id" => $filial_id,
    "id_receber" => $id,
    "conta_" => $_conta,
    "id_conta" => $id_conta,
    "tipo_recebimento" => $tipo_recebimento,
    "data" => $data_atual,
    "documento" => $documento,
    "pdesconto" => "",
    "vdesconto" => "",
    "id_motivo_desconto" => "",
    "pacrescimo" => "",
    "vacrecimo" => "",
    "valor_parcela" => $valor,
    "credito" => $valor,
    "debito" => $valor,
    "valor_total_recebido" => $valor,
    "historico" => $historico,
    "tipo_r" => "T",
    "previsao" => "M",
    "tipo_lanc" => "R"
));

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://demo.ixcsoft.com.br/webservice/v1/fn_areceber_recebimentos_baixas_novo',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_SSL_VERIFYPEER => false, // Ignora SSL
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $post_fields, // Usando a string JSON gerada
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Basic MTA1OjM2ZjJlOGQ0YzkxMjNhNjc1ODVhNTEwMzQ4N2YyMmYyNDQxNmRhNGM3YzljNTlkNDZkMTEyMTVlNGQ0NWFiMDY=',
    'Cookie: Path=/; IXC_Session=f0husbofbtakma4d8ctev3fejc'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

// Exibir a resposta
echo $response;
?>
