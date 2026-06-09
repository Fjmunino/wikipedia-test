<?php

require_once "classes/DB.php";

header('Content-Type: application/json');

function saveSearchTerm(): void
{
    $searchTerm = filter_input(INPUT_POST, 'searchTerm');
    if(!$searchTerm){
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Se requiere el término de búsqueda']);
        return;
    }
    $db = new DB();
    $db->saveSearchTerm($searchTerm);
    http_response_code(200);
    echo json_encode(['success' => true, 'data' => $db->getSearchTerms()]);
}

function listSearchTerms(): void
{
    $db = new DB();
    http_response_code(200);
    echo json_encode(['success' => true, 'data' => $db->getSearchTerms()]);
}

$action = filter_input(INPUT_GET, 'action') ?? filter_input(INPUT_POST, 'action');

if(!$action){
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No se reconoce la acción solicitada.']);
    exit;
}

try{
    match($action){
        'saveSearchTerm' => saveSearchTerm(),
        'listSearchTerms' => listSearchTerms(),
        default => throw new \InvalidArgumentException("No se reconoce la acción solicitada")
    };
}catch(\InvalidArgumentException $e){
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}catch(\Throwable $e){
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}