<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
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
            <a href="{{ route('posts.show', $post) }}"
                class="px-4 py-2 text-xs bg-amber-700 hover:bg-amber-600 text-amber-50 pixel-btn transition-colors duration-200 font-bold">
                [ BACK ]
            </a>
        </div>
    </header>

    <main class="flex-1 max-w-2xl mx-auto w-full px-4 py-8 relative z-0">
        <div class="border-2 border-amber-700 p-6 bg-gradient-to-b from-amber-950 to-transparent">
            <h1 class="text-xl font-bold mb-6 text-amber-100" style="letter-spacing: 2px;">[ EDIT POST ]</h1>

            @if ($errors->any())
                <div class="bg-red-950 border-2 border-red-700 text-red-200 px-4 py-3 rounded mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="text-xs">! {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('posts.update', $post) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="text" class="block text-xs font-bold text-amber-100 mb-2" style="letter-spacing: 1px;">
                        > MESSAGE (MAX 280)
                    </label>
                    <textarea 
                        id="text" 
                        name="text" 
                        class="w-full bg-amber-950 border-2 border-amber-700 text-amber-50 placeholder-amber-700 focus:outline-none focus:shadow-lg focus:shadow-amber-500/50 transition-all resize-none px-3 py-2 text-xs"
                        rows="8"
                        placeholder="> EDIT YOUR MESSAGE HERE..."
                        maxlength="280"
                        required
                    >{{ old('text', $post->text) }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 bg-amber-700 hover:bg-amber-600 text-amber-50 text-xs font-bold py-3 px-4 border-2 border-amber-500 transition-all pixel-btn hover:shadow-lg hover:shadow-amber-500/50" style="letter-spacing: 1px;">
                        [ SAVE ]
                    </button>
                    <a 
                        href="{{ route('posts.show', $post) }}" 
                        class="flex-1 text-center bg-gray-700 hover:bg-gray-600 text-white text-xs font-bold py-3 px-4 border-2 border-gray-500 transition-all pixel-btn"
                    >
                        [ CANCEL ]
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
