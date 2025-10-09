<div>
    <h1>
        Olá {{ $contractor->profile->name }}, seu agendamento foi feito com sucesso!
    </h1>
    <p>
        Seu compromisso agora é:
    </p>
    <ul>
        <li>
            Cumprir o horario marcado com o prestador de serviço.
        </li>
        <li>
            Avisar previamente sobre qualquer imprevisto, para que o prestador possa se programar melhor.
        </li>
        <li>
            Caso queira desmarcar, faça isso previamente antes do dia do compromisso.
        </li>
    </ul>
</div>

<div>
    <a href="{{ env('FRONTEND_URL') }}"><button>Ver detalhes</button></a>
</div>

<footer>
    {{  env('APP_NAME')  }}
</footer>