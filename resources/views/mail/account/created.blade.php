<div>
    <h1>Olá {{ $user->name }}, sua conta foi criada com sucesso na nossa plataforma!</h1>
    <p>Você deve seguir com mais alguns passos para completar a sua conta:</p>

    <ul>
        <li>Definir o tipo da sua conta: Você deseja prestar ou contratar serviços?</li>
        <li>Criar um perfil de usuário: Para seu perfil aparecer publicamente na plataforma.</li>
        <li>Criar um endereço padrão: Para recomendarmos serviços proximos a você em tempo real.</li>
    </ul>

    <p>
        Após completar todos os passos, você podera usar todos os recursos da nossa plataforma.
    </p>
</div>

<footer>
    {{ env('APP_NAME') }}
</footer>