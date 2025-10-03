<?php
// 代码生成时间: 2025-10-04 03:42:26
// 引入Slim框架
require 'vendor/autoload.php';

// 创建Slim应用实例
$app = new \Slim\App();

// 定义文档协作平台的路由和逻辑
$app->get('/documents[/{documentId}]', 'getDocuments');
$app->post('/documents', 'createDocument');
$app->put('/documents/{documentId}', 'updateDocument');
$app->delete('/documents/{documentId}', 'deleteDocument');

// 定义文档协作平台的数据存储
class DocumentStorage {
    private $documents = [];

    public function getDocument($documentId) {
        if (isset($this->documents[$documentId])) {
            return $this->documents[$documentId];
        } else {
            throw new Exception('Document not found');
        }
    }

    public function createDocument($document) {
        $this->documents[] = $document;
        return $this->documents[count($this->documents) - 1];
    }

    public function updateDocument($documentId, $document) {
        if (isset($this->documents[$documentId])) {
            $this->documents[$documentId] = $document;
            return $this->documents[$documentId];
        } else {
            throw new Exception('Document not found');
        }
    }

    public function deleteDocument($documentId) {
        if (isset($this->documents[$documentId])) {
            unset($this->documents[$documentId]);
        } else {
            throw new Exception('Document not found');
        }
    }
}

// 实现文档协作平台的逻辑
function getDocuments($request, $response, $args) {
    $documentStorage = new DocumentStorage();
    if (isset($args['documentId'])) {
        $document = $documentStorage->getDocument($args['documentId']);
        $response->getBody()->write(json_encode($document));
    } else {
        $response->getBody()->write(json_encode($documentStorage->documents));
    }
    return $response->withHeader('Content-Type', 'application/json');
}

function createDocument($request, $response, $args) {
    $documentStorage = new DocumentStorage();
    $document = json_decode($request->getBody(), true);
    $createdDocument = $documentStorage->createDocument($document);
    $response->getBody()->write(json_encode($createdDocument));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
}

function updateDocument($request, $response, $args) {
    $documentStorage = new DocumentStorage();
    $document = json_decode($request->getBody(), true);
    $updatedDocument = $documentStorage->updateDocument($args['documentId'], $document);
    $response->getBody()->write(json_encode($updatedDocument));
    return $response->withHeader('Content-Type', 'application/json');
}

function deleteDocument($request, $response, $args) {
    $documentStorage = new DocumentStorage();
    $documentStorage->deleteDocument($args['documentId']);
    return $response->withStatus(204);
}

// 运行Slim应用
$app->run();
