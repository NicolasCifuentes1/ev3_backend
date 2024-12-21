<?php
require_once 'config/bd.php';

function getContactInfo() {
    global $conn;
    $sql = "SELECT * FROM contact_info WHERE status = 1";
    $result = $conn->query($sql);

    $contacts = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
    }
    return $contacts;
}

function addContactInfo() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $social_media = $data['social_media'];

    $sql = "INSERT INTO contact_info (name, email, phone, social_media, status) VALUES ('$name', '$email', '$phone', '$social_media', 1)";
    if ($conn->query($sql) === TRUE) {
        return ['message' => 'Contacto se agrego correctamente'];
    } else {
        return ['error' => 'Error: ' . $conn->error];
    }
}

function updateContactInfo() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $social_media = $data['social_media'];

    $sql = "UPDATE contact_info SET name='$name', email='$email', phone='$phone', social_media='$social_media' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        return ['message' => 'Contacto se actualizo correctamente'];
    } else {
        return ['error' => 'Error: ' . $conn->error];
    }
}

function disableContactInfo() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "UPDATE contact_info SET status=0 WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        return ['message' => 'Contacto se deshabilito correctamente'];
    } else {
        return ['error' => 'Error: ' . $conn->error];
    }
}

function enableContactInfo() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "UPDATE contact_info SET status=1 WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        return ['message' => 'Contacto se habilito correctamente'];
    } else {
        return ['error' => 'Error: ' . $conn->error];
    }
}
?>