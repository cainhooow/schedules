<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Animado</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes glow {

      0%,
      100% {
        box-shadow: 0 0 5px rgba(239, 180, 77, 0.5), 0 0 10px rgba(239, 180, 77, 0.3);
      }

      50% {
        box-shadow: 0 0 10px rgba(239, 180, 77, 0.8), 0 0 20px rgba(239, 180, 77, 0.5);
      }
    }
  </style>
</head>

<body>
  <div class="h-screen w-full flex justify-center items-center bg-[#201f24]">
    <div
      class="flex h-150 w-6xl items-center justify-center bg-[#08070c] rounded-3xl p-5 text-white animate-[scaleIn_0.6s_ease-out] opacity-0 [animation-fill-mode:forwards]">
      <div class="h-full rounded-xl flex items-center flex-1 justify-center animate-[fadeInUp_0.3s_ease-out_both]">
        <img src="./images/mascote.png" alt="Mascote Login" class="rounded-xl w-130 transition-all duration-500">
      </div>
      <form class="flex flex-col h-full items-center flex-1 gap-4 p-8 animate-[fadeInUp_0.8s_ease-out_0.2s_both]">
        <h1 class="font-bold text-5xl mb-3">Login<span class="text-[#efb44d]">.</span></h1>

        <div class="flex flex-col gap-2 w-90">
          <label for="email" class="font-semibold">Email</label>
          <div class="relative w-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor"
              class="size-6 absolute text-gray-300 left-3 top-7 -translate-y-1/2 transition-all duration-300"
              id="email-icon">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
            <span
              class="size-6 absolute text-gray-300 left-12 top-[1.625rem] -translate-y-1/2 transition-all duration-300"
              id="email-divider">|</span>
            <input id="email" name="email" type="email" required placeholder="ex: seuemail@gmail.com"
              class="bg-transparent text-white rounded-md h-8 w-full outline-none px-16 font-medium placeholder:font-semibold placeholder:text-gray-400 border-2 border-[#919191] transition-all duration-300 hover:border-[#d4a043] hover:shadow-[0_0_8px_rgba(239,180,77,0.3)] focus:border-[#efb44d] focus:shadow-[0_0_15px_rgba(239,180,77,0.6),0_0_25px_rgba(239,180,77,0.3)] focus:-translate-y-0.5" />
          </div>

          <label for="password" class="font-semibold mt-2">Password</label>
          <div class="relative w-full">
            <span
              class="size-6 absolute text-gray-300 left-12 top-[1.625rem] -translate-y-1/2 transition-all duration-300"
              id="password-divider">|</span>
            <input id="password" name="password" type="password" required placeholder="********"
              class="bg-transparent text-white rounded-md h-8 w-full outline-none pl-16 pr-12 font-medium placeholder:font-semibold placeholder:text-gray-400 border-2 border-[#919191] transition-all duration-300 hover:border-[#d4a043] hover:shadow-[0_0_8px_rgba(239,180,77,0.3)] focus:border-[#efb44d] focus:shadow-[0_0_15px_rgba(239,180,77,0.6),0_0_25px_rgba(239,180,77,0.3)] focus:-translate-y-0.5" />
            
            <button type="button" id="toggle-password"
              class="absolute left-3 top-7 -translate-y-1/2 text-gray-300 hover:text-[#efb44d] transition-all duration-300 cursor-pointer focus:outline-none">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 transition-opacity duration-300" id="eye-open">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 absolute top-0 opacity-0 transition-opacity duration-300" id="eye-closed">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
              </svg>
            </button>
          </div>

          <h2
            class="font-semibold text-white my-3 max-w-max cursor-pointer relative transition-all duration-300 hover:text-[#f2c257] after:content-[''] after:absolute after:w-0 after:h-0.5 after:bottom-[-2px] after:left-0 after:bg-[#efb44d] after:transition-all after:duration-300 hover:after:w-full">
            Esqueceu a senha?</h2>
        </div>

        <button type="submit"
        class="relative w-90 flex justify-center items-center p-2 rounded-md text-white text-xl font-semibold
         bg-[#efb44d] overflow-hidden transition-all duration-300
         hover:-translate-y-1 hover:shadow-[0_10px_25px_rgba(239,180,77,0.4)]
         active:translate-y-[-1px]
         before:content-[''] before:absolute before:inset-0 before:scale-0 before:rounded-full
         before:bg-[#ffd77a] before:opacity-50 before:transition-transform before:duration-[1200ms]
         before:ease-out before:origin-center hover:before:scale-150">
          <span class="relative z-10">Log In</span>
        </button>


        <div class="flex items-center group">
          <span
            class="w-30 h-px bg-gray-500 transition-all duration-300 group-hover:bg-gradient-to-r group-hover:from-[#919191] group-hover:via-[#efb44d] group-hover:to-[#919191]"></span>
          <h2 class="mx-4 font-semibold">Ou Continue Com</h2>
          <span
            class="w-30 h-px bg-gray-500 transition-all duration-300 group-hover:bg-gradient-to-r group-hover:from-[#919191] group-hover:via-[#efb44d] group-hover:to-[#919191]"></span>
        </div>

        <div class="w-90 flex justify-center gap-6 mb-3">
          <a href="#"
            class="border border-[#efb44d] rounded-full p-3 flex items-center w-fit transition-all duration-300 hover:scale-110 hover:shadow-[0_0_15px_rgba(239,180,77,0.5)] hover:border-[#f2c257]">
            <svg class="size-7" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                fill="#4285F4" />
              <path
                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                fill="#34A853" />
              <path
                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                fill="#FBBC05" />
              <path
                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                fill="#EA4335" />
            </svg>
          </a>
        </div>

        <p class="font-medium">Não tem conta? <button type="button"
            class="text-[#efb44d] relative transition-all duration-300 hover:text-[#f2c257] after:content-[''] after:absolute after:w-0 after:h-0.5 after:bottom-[-2px] after:left-0 after:bg-[#efb44d] after:transition-all after:duration-300 hover:after:w-full">Cadastre-se
            aqui</button></p>
      </form>
    </div>
  </div>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          keyframes: {
            scaleIn: {
              '0%': { opacity: '0', transform: 'scale(0.9)' },
              '100%': { opacity: '1', transform: 'scale(1)' }
            },
            fadeInUp: {
              '0%': { opacity: '0', transform: 'translateY(30px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' }
            }
          }
        }
      }
    }

    // Função para ajustar a posição dos ícones quando o input está focado
    function setupInputFocusAnimation(inputId, iconId, dividerId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      const divider = document.getElementById(dividerId);

      input.addEventListener('focus', () => {
        icon.style.top = '1.5rem';
        divider.style.top = '1.35rem';
      });

      input.addEventListener('blur', () => {
        icon.style.top = '1.75rem';
        divider.style.top = '1.625rem';
      });
    }

    // Função para ajustar posição do botão toggle de senha
    function setupPasswordTogglePosition(inputId, toggleButtonId, dividerId) {
      const input = document.getElementById(inputId);
      const toggleButton = document.getElementById(toggleButtonId);
      const divider = document.getElementById(dividerId);

      input.addEventListener('focus', () => {
        toggleButton.style.top = '1.5rem';
        divider.style.top = '1.35rem';
      });

      input.addEventListener('blur', () => {
        toggleButton.style.top = '1.75rem';
        divider.style.top = '1.625rem';
      });
    }

    // Toggle para mostrar/ocultar senha
    function setupPasswordToggle() {
      const toggleButton = document.getElementById('toggle-password');
      const passwordInput = document.getElementById('password');
      const eyeOpen = document.getElementById('eye-open');
      const eyeClosed = document.getElementById('eye-closed');

      toggleButton.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        
        // Alterna o tipo do input
        passwordInput.type = isPassword ? 'text' : 'password';
        
        // Alterna a visibilidade dos ícones
        if (isPassword) {
          eyeOpen.style.opacity = '0';
          eyeClosed.style.opacity = '1';
        } else {
          eyeOpen.style.opacity = '1';
          eyeClosed.style.opacity = '0';
        }
      });
    }

    // Aplicar a animação para o input de email
    setupInputFocusAnimation('email', 'email-icon', 'email-divider');
    
    // Aplicar a animação para o botão toggle de senha e divider
    setupPasswordTogglePosition('password', 'toggle-password', 'password-divider');
    
    // Inicializar toggle de senha
    setupPasswordToggle();
  </script>
</body>

</html>