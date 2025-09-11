<form method="POST" action="{{ route('password.enviarCodigo') }}">
    @csrf
    <input type="email" name="correo_electronico" placeholder="Correo electrónico" required>
    <button type="submit">Enviar código de verificación</button>
</form>

<form method="POST" action="{{ route('password.verificarCodigo') }}">
    @csrf
    <input type="email" name="correo_electronico" placeholder="Correo electrónico" required>
    <input type="text" name="code" placeholder="Código de verificación" required>
    <button type="submit">Verificar código</button>
</form>