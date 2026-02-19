<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pixel Post</title>
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
      .post-card {
         border: 2px solid #D4AF7F;
         background: linear-gradient(to bottom, rgba(120, 90, 50, 0.3), rgba(42, 36, 32, 0.5));
      }
   </style>
   </style>
</head>
<body class="text-white min-h-screen flex flex-col">
   <header class="sticky top-0 border-b-4 border-amber-700 bg-gradient-to-r from-amber-900 to-amber-800 z-10">
       <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
          <a class="flex items-center gap-3 group" href="{{ route('posts.index') }}">
               <div class="w-10 h-10 bg-amber-100 flex items-center justify-center pixel-border" style="transform: skewX(-10deg);">
                   <span class="text-amber-900 font-bold text-sm">PT</span>
               </div>
               <span class="text-xs font-bold text-amber-100 group-hover:text-amber-50 transition-colors" style="letter-spacing: 2px;">
                   PIXEL.POST
               </span>
           </a>

           <div class="flex items-center space-x-3">
               @auth
               <a href="{{ route('profile.show', auth()->user()) }}"
                   class="px-3 py-2 text-xs bg-amber-700 hover:bg-amber-600 text-amber-50 border-2 border-amber-500 pixel-btn transition-colors duration-200 font-bold">
                   [ PROFILE ]
               </a>
               <form method="POST" action="{{ route('logout') }}" class="inline">
                   @csrf
                   <button class="px-3 py-2 text-xs bg-red-700 hover:bg-red-600 text-amber-50 border-2 border-red-500 pixel-btn transition-colors duration-200 font-bold">
                       [ QUIT ]
                   </button>
               </form>
               @else
               <a href="{{ route('login_page') }}"
                   class="px-3 py-2 text-xs bg-amber-700 hover:bg-amber-600 text-amber-50 border-2 border-amber-500 pixel-btn transition-colors duration-200 font-bold">
                   [ LOGIN ]
               </a>
               <a href="{{ route('register_page') }}"
                   class="px-3 py-2 text-xs bg-amber-700 hover:bg-amber-600 text-amber-50 border-2 border-amber-500 pixel-btn transition-colors duration-200 font-bold">
                   [ SIGN UP ]
               </a>
               @endauth
           </div>
       </div>
   </header>


  
   <main class="flex-1 max-w-2xl mx-auto w-full relative z-0">
       @auth
       <div class="border-b-4 border-amber-700 p-6 bg-gradient-to-b from-amber-950 to-transparent">
           <form method="POST" action="{{ route('posts.store') }}" class="space-y-4">
               @csrf
               <label class="text-xs text-amber-100 block" style="letter-spacing: 1px;">[ NEW POST ]</label>
               <textarea placeholder="> TYPE YOUR MESSAGE..."
                   class="w-full bg-amber-950 border-2 border-amber-700 text-amber-50 placeholder-amber-700 resize-none outline-none focus:shadow-lg focus:shadow-amber-500/50 transition-all px-3 py-2 text-xs" rows="4"
                   name="text" required maxlength="280"></textarea>

               <div class="flex justify-between items-center">
                   <span class="text-xs text-amber-600">MAX 280 CHARS</span>
                   <button type="submit"
                       class="bg-amber-700 hover:bg-amber-600 text-amber-50 px-6 py-2 border-2 border-amber-500 text-xs font-bold pixel-btn transition-colors duration-200" style="letter-spacing: 1px;">
                       [ POST ]
                   </button>
               </div>
           </form>
       </div>
       @endauth

     
       @forelse($posts as $post)
       <article class="post-card p-6 border-b-4 border-amber-700 hover:bg-amber-950 hover:bg-opacity-30 transition-colors duration-200">
           <div class="flex gap-4">
               <a href="{{ route('profile.show', $post->user) }}"
                   class="w-10 h-10 bg-gradient-to-br from-amber-200 to-amber-600 border-2 border-amber-400 flex-shrink-0 flex items-center justify-center pixel-border hover:opacity-80 transition-opacity overflow-hidden rounded-full">
                   <img src="{{ $post->user->getAvatarUrl() }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
               </a>

               <div class="flex-1 min-w-0">
                   <div class="flex items-center gap-2 mb-2 flex-wrap">
                       <a href="{{ route('profile.show', $post->user) }}"
                           class="font-bold text-amber-50 text-xs hover:text-amber-200 transition-colors" style="letter-spacing: 1px;">{{ $post->user->name }}</a>
                       <span class="text-amber-700 text-xs">·</span>
                       <time class="text-amber-700 text-xs">{{ $post->created_at->diffForHumans() }}</time>
                   </div>

                   <p class="text-amber-50 mb-4 leading-relaxed text-xs">
                       {{ $post->text }}
                   </p>

                   <div class="flex gap-3 pt-3 border-t border-amber-700">
                       <a href="{{ route('posts.show', $post) }}" class="text-amber-600 hover:text-amber-500 text-xs font-bold pixel-btn">
                           [ VIEW ]
                       </a>
                       @auth
                           @if(auth()->id() === $post->user_id)
                           <a href="{{ route('posts.edit', $post) }}" class="text-amber-600 hover:text-amber-500 text-xs font-bold pixel-btn">
                               [ EDIT ]
                           </a>
                           <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline">
                               @csrf
                               @method('DELETE')
                               <button type="submit" class="text-red-500 hover:text-red-400 text-xs font-bold pixel-btn"
                                   onclick="return confirm('DELETE THIS POST?')">
                                   [ DEL ]
                               </button>
                           </form>
                           @endif
                       @endauth
                   </div>
               </div>
           </div>
       </article>
       @empty
       <div class="p-8 text-center text-amber-600 border-b-4 border-amber-700">
           <p class="text-xs" style="letter-spacing: 1px;">[ NO POSTS YET ]</p>
           <p class="text-xs text-amber-700 mt-2">BE THE FIRST TO POST!</p>
       </div>
       @endforelse
   </main>
</body>


</html>
