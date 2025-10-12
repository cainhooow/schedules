<div>
     <h1>
          Olá {{ $user->profile ? $user->profile->name : 'caro usuário' }}
     </h1>
     <p>
          Uma solicitação para alteração de senha foi feita em sua conta, se não foi você,
          ignore este e-mail.
     </p>
</div>
