<div class="h-screen w-full flex justify-center items-center bg-[#201f24]">
      <div class="flex h-150 w-6xl items-center justify-center bg-[#08070c] gap-4 rounded-3xl p-5 text-white">
        <div class="w-130 h-full rounded-xl flex items-center">
            <img src="./images/mascote.png" alt="Mascote Login" class="rounded-xl">
        </div>
        <form class="flex flex-col h-full items-center flex-1 gap-4 p-8">
          <h1 class="font-bold text-5xl mb-3">Login<span class="text-[#efb44d]">.</span></h1>
          <div class="flex flex-col gap-2 w-90">
            <label htmlFor="email" class="font-semibold">Email</label>
            <div class="relative w-full">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 absolute text-gray-300 left-3 top-1/2 -translate-y-1/2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
              </svg>

              <span class="size-6 absolute text-gray-300 left-12 top-3.5 -translate-y-1/2">|</span>

              <input id="email" name="email" type="email" required
                placeholder="ex: seuemail@gmai.com"
                class="border border-[#919191] rounded-md h-8 w-full focus:outline-2 focus:outline-[#f2c257] focus:border-none px-16 font-medium placeholder:font-semibold" />
            </div>

            <label htmlFor="password" class="font-semibold">Password</label>
            <div class="relative w-full">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 absolute text-gray-300 left-3 top-1/2 -translate-y-1/2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              </svg>

              <span class="size-6 absolute text-gray-300 left-12 top-3.5 -translate-y-1/2">|</span>

              <input id="password" name="password" type="password" required
                placeholder="********"
                class="border border-[#919191] rounded-md h-8 w-full focus:outline-2 focus:outline-[#f2c257] focus:border-none px-16 font-medium placeholder:font-semibold" />
            </div>

            <h2 class="font-semibold underline text-white text-right my-3">Esqueceu a senha?</h2>
          </div>

          <button
            class="bg-[#efb44d] w-90 flex flex-col justify-center p-2 rounded-md text-white text-xl font-semibold">
            Log In
          </button>

          <div class="flex items-center">
            <span class="w-30 h-px bg-gray-500"></span>
            <h2 class="mx-4 font-semibold">Ou Continue Com</h2>
            <span class="w-30 h-px bg-gray-500"></span>
          </div>

          <div class="w-90 flex justify-center gap-6 mb-3">

            <a href="#">
              <span class="border border-[#efb44d] rounded-full p-3 flex items-center w-fit">
                <img src="/images/google-icon-logo.svg" alt="Imagem logo"
                  class="size-7" />
              </span>
            </a>
          </div>

          <p class="font-medium">Não tem conta? <button class="underline text-[#efb44d]">Cadastre-se aqui</button></p>
        </form>
      </div>
    </div>