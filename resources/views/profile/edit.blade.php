<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
        }
        .avatar-preview img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body class="text-white min-h-screen flex flex-col">
    <!-- Header -->
    <header class="border-b-4 border-amber-700 bg-gradient-to-r from-amber-900 to-amber-800 relative z-10">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <a class="flex items-center gap-3 group" href="{{ route('posts.index') }}">
                <div class="w-10 h-10 bg-amber-100 flex items-center justify-center pixel-border" style="transform: skewX(-10deg);">
                    <span class="text-amber-900 font-bold text-sm">PT</span>
                </div>
                <span class="text-xs font-bold text-amber-100 group-hover:text-amber-50 transition-colors" style="letter-spacing: 2px;">
                    PIXEL.POST
                </span>
            </a>
            <!-- Back Button -->
            <a href="{{ route('profile.show', auth()->user()) }}"
                class="px-4 py-2 text-xs bg-amber-700 hover:bg-amber-600 text-amber-50 pixel-btn transition-colors duration-200 font-bold">
                [ BACK ]
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-2xl mx-auto w-full px-4 py-8 relative z-0">
        <div class="border-2 border-amber-700 p-6 bg-gradient-to-b from-amber-950 to-transparent">
            <h1 class="text-xl font-bold mb-8 text-amber-100" style="letter-spacing: 2px;">[ EDIT PROFILE ]</h1>

            @if ($errors->any())
                <div class="bg-red-950 border-2 border-red-700 text-red-200 px-4 py-3 rounded mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="text-xs">! {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-950 border-2 border-green-700 text-green-200 px-4 py-3 rounded mb-6">
                    <p class="text-xs">✓ {{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Avatar Upload -->
                <div>
                    <label class="block text-xs font-bold text-amber-100 mb-4" style="letter-spacing: 1px;">
                        > AVATAR
                    </label>
                    <div class="flex items-center gap-6">
                        <div class="avatar-preview pixel-border">
                            <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}">
                        </div>
                        <div class="flex-1">
                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                class="w-full px-3 py-2 bg-amber-950 border-2 border-amber-700 text-amber-50 text-xs outline-none focus:shadow-lg focus:shadow-amber-500/50 transition-all">
                            <p class="text-xs text-amber-300 mt-2" style="letter-spacing: 0.5px;">
                                JPEG, PNG, GIF (MAX 2MB)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-xs font-bold text-amber-100 mb-2" style="letter-spacing: 1px;">
                        > NAME
                    </label>
                    <input type="text" id="name" name="name"
                        class="w-full px-3 py-2 bg-amber-950 border-2 border-amber-700 text-amber-50 placeholder-amber-700 text-xs outline-none focus:bg-amber-900 focus:shadow-lg focus:shadow-amber-500/50 transition-all"
                        value="{{ old('name', $user->name) }}"
                        placeholder="YOUR NAME">
                    @error('name')
                        <p class="text-red-300 text-xs mt-1">! {{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Display (Read-only) -->
                <div>
                    <label for="email" class="block text-xs font-bold text-amber-100 mb-2" style="letter-spacing: 1px;">
                        > EMAIL
                    </label>
                    <input type="email" id="email" name="email" disabled
                        class="w-full px-3 py-2 bg-amber-900 border-2 border-amber-700 text-amber-300 text-xs opacity-50 cursor-not-allowed"
                        value="{{ $user->email }}">
                    <p class="text-xs text-amber-300 mt-1" style="letter-spacing: 0.5px;">
                        EMAIL CANNOT BE CHANGED
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="flex-1 bg-amber-700 hover:bg-amber-600 text-amber-50 text-xs font-bold py-3 px-4 border-2 border-amber-500 transition-all pixel-btn hover:shadow-lg hover:shadow-amber-500/50" style="letter-spacing: 1px;">
                        [ SAVE ]
                    </button>
                    <a href="{{ route('profile.show', $user) }}"
                        class="flex-1 text-center bg-gray-700 hover:bg-gray-600 text-white text-xs font-bold py-3 px-4 border-2 border-gray-500 transition-all pixel-btn">
                        [ CANCEL ]
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
