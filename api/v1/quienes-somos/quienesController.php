<?php
require_once 'config/bd.php';

function getAboutInfo() {
    $conn = getDbConnection();
    $sql = "SELECT * FROM about_info WHERE id = 1"; // Asumo que sera solo un registro con id 1 XD
    $stmt = $conn->query($sql);

    if ($row = $stmt->fetch()) {
        return $row;
    } else {
        return ['error' => 'No se encontró la información'];
    }
}

function updateAboutInfo() {
    $conn = getDbConnection();
    $data = json_decode(file_get_contents("php://input"), true);
    $title = $data['title'];
    $description = $data['description'];

    $sql = "UPDATE about_info SET title = :title, description = :description WHERE id = 1"; 
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);

    if ($stmt->execute()) {
        return ['message' => 'Información actualizada correctamente'];
    } else {
        return ['error' => 'Error: ' . $stmt->errorInfo()[2]];
    }
}
?>

