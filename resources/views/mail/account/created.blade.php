<div>
    <h1>Olá {{ $user->profile->name }}, sua conta foi criada com sucesso na nossa plataforma!</h1>
    <p>Você deve seguir com mais alguns passos para completar o seu cadastro:</p>

    <ul>
        <li><strong>Definir o tipo da sua conta</strong>: Você deseja prestar ou contratar serviços?</li>
        <br>
        <li><strong>Criar um perfil de usuário</strong>: Para seu perfil aparecer publicamente na plataforma.</li>
        <br>
        <li><strong>Criar um endereço padrão</strong>: Para recomendarmos serviços proximos a você em tempo real.</li>
    </ul>

    <p>
        Após completar todos os passos, você podera usar todos os recursos da nossa plataforma.
    </p>
</div>

<footer>
    {{ env('APP_NAME') }}
</footer>