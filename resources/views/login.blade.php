
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Enter pixel post</title>
   <script src="https://cdn.tailwindcss.com"></script>
   <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
   <style>
      * {
         font-family: 'Press Start 2P', cursive;
      }
      body {
         background: linear-gradient(135deg, #2A2420 0%, #3D3531 50%, #2F2B27 100%);
         background-attachment: fixed;
         position: relative;
         overflow-x: hidden;
      }
      body::before {
         content: '';
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-image: 
            polygon(0% 0%, 20% 15%, 40% 5%, 60% 20%, 80% 10%, 100% 25%, 100% 45%, 80% 50%, 60% 40%, 40% 55%, 20% 45%, 0% 50%),
            polygon(0% 55%, 15% 65%, 35% 60%, 55% 70%, 75% 65%, 100% 75%, 100% 100%, 0% 100%);
         background-size: 500% 500%;
         background-position: 0% 0%;
         opacity: 0.15;
         pointer-events: none;
         filter: drop-shadow(0 0 10px rgba(220, 190, 140, 0.2));
      }
      .pixel-border {
         border: 3px solid #D4AF7F;
         box-shadow: inset 0 0 0 1px #A0826D, 0 0 20px rgba(212, 175, 127, 0.3);
      }
      .pixel-btn {
         text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
         transition: all 0.1s ease;
      }
      .pixel-btn:active {
         transform: translate(2px, 2px);
         box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.3);
      }
   </style>
</head>
<body class="text-white min-h-screen flex flex-col">
   <header class="border-b-4 border-amber-700 bg-gradient-to-r from-amber-900 to-amber-800 relative z-10">
       <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
           <a class="flex items-center gap-3 group" href="{{ route('posts.index') }}">
               <div class="w-10 h-10 bg-amber-100 flex items-center justify-center pixel-border" style="transform: skewX(-10deg);">
                   <span class="text-amber-900 font-bold text-sm">PT</span>
               </div>
               <span class="text-xs font-bold text-amber-100 group-hover:text-amber-50 transition-colors" style="letter-spacing: 2px;">
                   PIXEL.POST
               </span>
           </a>
           <a href="{{ route('posts.index') }}"
               class="px-4 py-2 text-xs bg-amber-700 hover:bg-amber-600 text-amber-50 pixel-btn transition-colors duration-200 font-bold">
               [ HOME ]
           </a>
       </div>
   </header>


   <main class="flex-1 flex items-center justify-center px-4 py-12 relative z-0">
       <div class="w-full max-w-sm">
           <div class="text-center mb-8">
               <h1 class="text-2xl font-bold mb-3 text-amber-100" style="letter-spacing: 3px;">[ LOGIN ]</h1>
               <p class="text-xs text-amber-100 opacity-70" style="letter-spacing: 1px;">ACCESS YOUR ACCOUNT</p>
           </div>

           <form class="space-y-5" method="POST" action="{{ route('login') }}">
               @csrf

               <div>
                   <label for="email" class="block text-xs font-bold text-amber-100 mb-2" style="letter-spacing: 1px;">
                       > EMAIL
                   </label>
                   <input type="email" id="email" name="email"
                       class="w-full px-3 py-2 bg-amber-950 border-2 border-amber-700 text-amber-50 placeholder-amber-700 text-xs outline-none focus:bg-amber-900 focus:shadow-lg focus:shadow-amber-500/50 transition-all"
                       placeholder="user@example.com"
                       value="{{ old('email') }}">
                   @error('email')
                       <p class="text-red-300 text-xs mt-1">! {{ $message }}</p>
                   @enderror
               </div>

               <div>
                   <label for="password" class="block text-xs font-bold text-amber-100 mb-2" style="letter-spacing: 1px;">
                       > PASSWORD
                   </label>
                   <input type="password" id="password" name="password"
                       class="w-full px-3 py-2 bg-amber-950 border-2 border-amber-700 text-amber-50 placeholder-amber-700 text-xs outline-none focus:bg-amber-900 focus:shadow-lg focus:shadow-amber-500/50 transition-all"
                       placeholder="••••••••">
                   @error('password')
                       <p class="text-red-300 text-xs mt-1">! {{ $message }}</p>
                   @enderror
               </div>

               @if (session('fail'))
                   <p class="text-red-300 text-xs text-center mt-2 bg-red-950 border-2 border-red-700 p-2">
                       ! {{ session('fail') }}
                   </p>
               @endif

               <button type="submit"
                   class="w-full bg-amber-700 hover:bg-amber-600 text-amber-50 text-xs font-bold py-3 px-4 border-2 border-amber-500 transition-all pixel-btn hover:shadow-lg hover:shadow-amber-500/50" style="letter-spacing: 2px;">
                   [ ENTER ]
               </button>
           </form>

           <p class="text-center text-amber-100 text-xs mt-8" style="letter-spacing: 1px;">
               NO ACCOUNT? <a href="{{ route('register_page') }}" class="text-amber-50 hover:text-white underline font-bold">[ REGISTER ]</a>
           </p>
       </div>
   </main>
</body>
</html>
