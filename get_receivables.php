<?php
session_start(); // Inicia a sessão

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://demo.ixcsoft.com.br/webservice/v1/fn_areceber',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_SSL_VERIFYPEER => false, // Ignora SSL
  CURLOPT_POSTFIELDS => '{
    "qtype": "fn_areceber.id_cliente",
    "query": "7",
    "oper": "=",
    "page": "1",
    "rp": "1",
    "grid_param": "[{\"TB\":\"fn_areceber.status\", \"OP\" : \"=\", \"P\" : \"A\"}]"
  }',
  CURLOPT_HTTPHEADER => array(
    'ixcsoft: listar',
    'Content-Type: application/json',
    'Authorization: Basic MTA1OjM2ZjJlOGQ0YzkxMjNhNjc1ODVhNTEwMzQ4N2YyMmYyNDQxNmRhNGM3YzljNTlkNDZkMTEyMTVlNGQ0NWFiMDY=',
    'Cookie: Path=/; IXC_Session=f0husbofbtakma4d8ctev3fejc',
  ),
));

$response = curl_exec($curl);
curl_close($curl);

// Verificar se houve erro no cURL
if ($response === false) {
    $_SESSION['message'] = "Erro ao realizar a requisição.";
    header("Location: post_baixa.php");
    exit;
}

// Decodificar o JSON
$data = json_decode($response, true);

// Verificar se a decodificação foi bem-sucedida
if ($data === null) {
    $_SESSION['message'] = "Erro ao decodificar os dados JSON.";
    header("Location: post_baixa.php");
    exit;
}

// Verificar se os dados esperados estão presentes
if (!isset($data['registros']) || empty($data['registros'])) {
    $_SESSION['message'] = "Nenhum dado encontrado na API.";
    header("Location: post_baixa.php");
    exit;
}

// Pegar o primeiro registro (exemplo)
$firstRecord = $data['registros'][0]; // A API retorna um array dentro da chave 'registros'

// Armazenar os dados na sessão
$_SESSION['fn_areceber'] = $firstRecord;

// Redirecionar para o arquivo post_baixa.php
include 'process_payment.php';
exit;
