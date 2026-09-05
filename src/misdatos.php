<?php
session_start();
include 'conexion.php';
if (!isset($_SESSION['usuario'])) { header('Location: logininiciosesiones.php'); exit(); }
$usuario = $_SESSION['usuario']; $dni = $_SESSION['dni']; $mensaje = ''; $tipoMensaje = '';
$usuarioSql = mysqli_real_escape_string($mysqli, $usuario); $dniSql = mysqli_real_escape_string($mysqli, $dni);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = trim($_POST['mail'] ?? ''); $password = $_POST['contrasenia'] ?? '';
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) { $mensaje = 'Ingresá un correo electrónico válido.'; $tipoMensaje = 'error'; }
    else {
        $mailSql = mysqli_real_escape_string($mysqli, $mail);
        mysqli_begin_transaction($mysqli);
        try {
            if (!mysqli_query($mysqli, "UPDATE empleados SET mail='$mailSql' WHERE DNI='$dniSql' AND id_usuario='$usuarioSql'")) throw new Exception();
            if ($password !== '') {
                if (strlen($password) < 8) throw new RuntimeException('La contraseña debe tener al menos 8 caracteres.');
                $clave = md5($password); $claveSql = mysqli_real_escape_string($mysqli, $clave);
                if (!mysqli_query($mysqli, "UPDATE usuario SET claveIngreso='$claveSql' WHERE id_usuario='$usuarioSql'")) throw new Exception();
            }
            mysqli_commit($mysqli); $mensaje = 'Tus datos se actualizaron correctamente.'; $tipoMensaje = 'success';
        } catch (RuntimeException $e) { mysqli_rollback($mysqli); $mensaje = $e->getMessage(); $tipoMensaje = 'error'; }
        catch (Exception $e) { mysqli_rollback($mysqli); $mensaje = 'No pudimos actualizar los datos. Intentá nuevamente.'; $tipoMensaje = 'error'; }
    }
}
$resultado = mysqli_query($mysqli, "SELECT e.DNI,e.nombre,e.apellido,e.celular,e.mail,e.salarioBruto,p.nombre puesto,d.nombre area FROM empleados e LEFT JOIN puestos p ON e.id_puestos=p.id_puestos LEFT JOIN departamento d ON p.id_departamento=d.id_departamento WHERE e.DNI='$dniSql' AND e.id_usuario='$usuarioSql' LIMIT 1");
$dato = $resultado ? mysqli_fetch_assoc($resultado) : null;
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Mis datos personales</title></head><body>
<main class="screen-card"><header class="screen-toolbar"><div class="title-block"><span class="eyebrow">Mi perfil</span><h1>Mis datos personales</h1><p>Revisá tu legajo y mantené actualizados tus datos de acceso.</p></div><a class="secondary-action" href="mostrarregistroempleado.php">Mis liquidaciones</a></header>
<?php if ($mensaje): ?><p class="profile-message <?php echo $tipoMensaje; ?>"><?php echo htmlspecialchars($mensaje); ?></p><?php endif; ?>
<?php if ($dato): ?><div class="profile-layout"><section class="locked-data"><h2>Datos del legajo</h2><p>Estos datos son informativos y solo pueden modificarse desde Administración.</p><dl><div><dt>Nombre y apellido</dt><dd><?php echo htmlspecialchars($dato['nombre'].' '.$dato['apellido']); ?></dd></div><div><dt>DNI</dt><dd><?php echo htmlspecialchars($dato['DNI']); ?></dd></div><div><dt>Puesto</dt><dd><?php echo htmlspecialchars($dato['puesto'] ?? 'Sin asignar'); ?></dd></div><div><dt>Área de trabajo</dt><dd><?php echo htmlspecialchars($dato['area'] ?? 'Sin asignar'); ?></dd></div><div><dt>Celular</dt><dd><?php echo htmlspecialchars($dato['celular']); ?></dd></div><div><dt>Salario bruto</dt><dd>$<?php echo number_format((float)$dato['salarioBruto'],2,',','.'); ?></dd></div></dl></section>
<form method="POST" class="editable-data"><h2>Datos editables</h2><p>Podés cambiar únicamente tu correo y contraseña.</p><label for="mail">Correo electrónico</label><input type="email" id="mail" name="mail" value="<?php echo htmlspecialchars($dato['mail']); ?>" required><label for="contrasenia">Nueva contraseña</label><input type="password" id="contrasenia" name="contrasenia" minlength="8" autocomplete="new-password" placeholder="Dejar vacío para conservar la actual"><small>Mínimo 8 caracteres.</small><div class="form-actions"><button type="submit">Guardar cambios</button></div></form></div>
<?php else: ?><div class="empty-panel"><h2>No encontramos tu legajo</h2><p>Contactá a Administración para revisar la vinculación del usuario.</p></div><?php endif; ?>
</main></body></html>
