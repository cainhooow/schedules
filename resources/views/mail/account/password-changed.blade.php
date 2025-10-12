<div>
     <h1>
          Olá {{ $user->profile ? $user->profile->name : 'caro usuário' }}
     </h1>
     <p>
          A senha da sua conta foi alterada com sucesso, se não foi você,
          entre em contato com o suporte.
     </p>
</div>
