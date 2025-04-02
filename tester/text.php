<?php
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $checkEmail = $conn->query("SELECT email FROM registration WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is Already Registered';
        $_SESSION['active_form'] = 'register';

    }else{
        $conn->query("INSERT INTO registration (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')");

    }
    header("Location index.php");
    exit();
}
?>

<?php
if (isset($_POST['reservation'])) {
    $idreservations = $_POST['idreservations'];
    $name = $_POST['name'];
    $yrandsection = $_POST['yrandsection'];
    $roomno = $_POST['roomno'];
    $reservation_name = $_POST['reservation_time'];
    $reservation_date = $_POST['reservation_date'];
    
    $checkreservation = $conn->query("SELECT idreservations FROM reservations WHERE idreservations = '$idreservations'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['reservations_error'] = 'Email is Already Registered';
        $_SESSION['active_form'] = 'register';

    }else{
        $conn->query("INSERT INTO registration (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')");

    }
    header("Location index.php");
    exit();
}
?>

<td>{$row['idreservations']}</td>
        <td>{$row['name']}</td>
        <td>{$row['yrandsection']}</td>
        <td>{$row['roomno']}</td>
        <td>{$row['reservation_time']}</td>
        <td>{$row['reservation_date']}</td>