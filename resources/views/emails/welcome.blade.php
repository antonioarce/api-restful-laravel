hola {{$user->name}}
Gracias por crear una cuenta. Por favor verifica tu cuenta accediendo a este link.
{{route('verify', $user->verification_token)}}