<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->user->name }}'s Post</title>
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
                [ BACK ]
            </a>
        </div>
    </header>

    <main class="flex-1 max-w-2xl mx-auto w-full px-4 py-8 relative z-0">
        <div class="border-2 border-amber-700 p-6 bg-gradient-to-b from-amber-950 to-transparent">
            <div class="flex items-start justify-between mb-4 pb-4 border-b border-amber-700">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <a href="{{ route('profile.show', $post->user) }}"
                            class="text-xs font-bold text-amber-50 hover:text-amber-200 transition-colors" style="letter-spacing: 1px;">{{ $post->user->name }}</a>
                        <span class="text-amber-700 text-xs">@ {{ str($post->user->email)->before('@') }}</span>
                    </div>
                    <p class="text-amber-700 text-xs">{{ $post->created_at->diffForHumans() }}</p>
                </div>

                @auth
                    @if(auth()->id() === $post->user_id)
                    <div class="flex gap-2">
                        <a 
                            href="{{ route('posts.edit', $post) }}" 
                            class="px-3 py-2 bg-amber-700 hover:bg-amber-600 text-amber-50 rounded text-xs font-bold pixel-btn transition-colors duration-200"
                        >
                            [ EDIT ]
                        </a>
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="px-3 py-2 bg-red-600 hover:bg-red-500 text-white rounded text-xs font-bold pixel-btn transition-colors duration-200"
                                onclick="return confirm('DELETE THIS POST?')"
                            >
                                [ DEL ]
                            </button>
                        </form>
                    </div>
                    @endif
                @endauth
            </div>

            <p class="text-amber-50 text-xs leading-relaxed mb-4">{{ $post->text }}</p>

            <div class="pt-4 border-t border-amber-700">
                <p class="text-amber-600 text-xs">
                    {{ $post->created_at->format('M d, Y \a\t H:i') }}
                </p>
            </div>
        </div>
    </main>
</body>
</html>
