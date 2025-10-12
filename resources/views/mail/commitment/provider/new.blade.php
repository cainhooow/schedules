<div>
     <h1>
          Olá {{ $serviceProvider->profile->name }}, um novo agendamento foi realizado!
     </h1>
     <p>
          {{ $contractor->profile->name }} fez um novo agendamento com você!
     </p>
</div>

<div>
     <a href="{{ env('FRONTEND_URL') }}">
          <button>Ver mais detalhes</button>
     </a>
</div>

<footer>
     {{ env('APP_NAME') }}
</footer>
