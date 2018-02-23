hola {{$user->name}}
Has cambiado tu correo electronico. Por favor verifica el mismo accediendo a este link.
{{route('verify', $user->verification_token)}}