<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Profile</title>
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
        .avatar-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }
        .avatar-container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
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
                [ FEED ]
            </a>
        </div>
    </header>

  
    <main class="flex-1 max-w-4xl mx-auto w-full px-4 py-8 relative z-0">
        <div class="border-2 border-amber-700 p-8 bg-gradient-to-b from-amber-950 to-transparent mb-8">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-8 mb-6">
                <div class="avatar-container pixel-border">
                    <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}">
                </div>

                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-amber-100 mb-2" style="letter-spacing: 2px;">
                        {{ strtoupper($user->name) }}
                    </h1>
                    <p class="text-xs text-amber-200 mb-4" style="letter-spacing: 1px;">
                        > {{ $user->email }}
                    </p>
                    <div class="flex gap-3">
                        @auth
                            @if (auth()->id() === $user->id)
                                <a href="{{ route('profile.edit') }}"
                                    class="px-4 py-2 text-xs bg-amber-700 hover:bg-amber-600 text-amber-50 pixel-btn transition-colors duration-200 font-bold">
                                    [ EDIT ]
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t-2 border-amber-700 pt-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-amber-100">{{ $user->posts()->count() }}</div>
                    <div class="text-xs text-amber-200" style="letter-spacing: 1px;">POSTS</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-amber-100">{{ $user->created_at->format('M Y') }}</div>
                    <div class="text-xs text-amber-200" style="letter-spacing: 1px;">JOINED</div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-bold text-amber-100 mb-4" style="letter-spacing: 2px;">[ POSTS ]</h2>

            @if ($posts->count() > 0)
                <div class="space-y-4">
                    @foreach ($posts as $post)
                        <div class="border-2 border-amber-700 p-6 bg-gradient-to-b from-amber-950 to-transparent">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-200 to-amber-600 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    <img src="{{ $post->user->getAvatarUrl() }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-amber-100">{{ $user->name }}</p>
                                    <p class="text-xs text-amber-300">@ {{ strtolower(str_replace(' ', '', $user->name)) }}</p>
                                </div>
                            </div>

                            <p class="text-xs text-amber-50 mb-4 leading-relaxed">
                                {{ $post->text }}
                            </p>

                            <div class="flex justify-between items-center text-xs text-amber-300 border-t border-amber-700 pt-3">
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                <div class="flex gap-2">
                                    @auth
                                        @if (auth()->id() === $user->id)
                                            <a href="{{ route('posts.edit', $post) }}"
                                                class="px-3 py-1 bg-amber-700 hover:bg-amber-600 text-amber-50 pixel-btn transition-colors duration-200 font-bold">
                                                [ EDIT ]
                                            </a>
                                            <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-700 hover:bg-red-600 text-red-50 pixel-btn transition-colors duration-200 font-bold"
                                                    onclick="return confirm('Delete this post?')">
                                                    [ DEL ]
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="border-2 border-amber-700 p-12 text-center bg-gradient-to-b from-amber-950 to-transparent">
                    <p class="text-amber-200 text-xs" style="letter-spacing: 1px;">[ NO POSTS YET ]</p>
                </div>
            @endif
        </div>
    </main>
</body>
</html>
