<?php
require_once 'config/bd.php';

function getContactInfo() {
    $conn = getDbConnection();
    $sql = "SELECT * FROM contact_info WHERE status = 1";
    $stmt = $conn->query($sql);

    $contacts = [];
    while ($row = $stmt->fetch()) {
        $contacts[] = $row;
    }
    return $contacts;
}

function addContactInfo() {
    $conn = getDbConnection();
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $social_media = $data['social_media'];

    $sql = "INSERT INTO contact_info (name, email, phone, social_media, status) VALUES (:name, :email, :phone, :social_media, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':social_media', $social_media);

    if ($stmt->execute()) {
        return ['message' => 'Contacto se agrego correctamente'];
    } else {
        return ['error' => 'Error: ' . $stmt->errorInfo()[2]];
    }
}

function updateContactInfo() {
    $conn = getDbConnection();
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $social_media = $data['social_media'];

    $sql = "UPDATE contact_info SET name = :name, email = :email, phone = :phone, social_media = :social_media WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':social_media', $social_media);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        return ['message' => 'Contacto se actualizo correctamente'];
    } else {
        return ['error' => 'Error: ' . $stmt->errorInfo()[2]];
    }
}

function disableContactInfo() {
    $conn = getDbConnection();
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "UPDATE contact_info SET status = 0 WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        return ['message' => 'Contacto se deshabilito correctamente'];
    } else {
        return ['error' => 'Error: ' . $stmt->errorInfo()[2]];
    }
}

function enableContactInfo() {
    $conn = getDbConnection();
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "UPDATE contact_info SET status = 1 WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        return ['message' => 'Contacto se habilito correctamente'];
    } else {
        return ['error' => 'Error: ' . $stmt->errorInfo()[2]];
    }
}
?>