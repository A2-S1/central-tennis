<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'plays_tennis' => 'nullable|in:0,1',
            'tennis_level' => 'nullable|in:iniciante,intermediario,avancado,especial',
            'usual_playing_location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'public_profile' => 'nullable|in:0,1',
            'avatar' => 'nullable|image|max:4096',
            'instagram' => 'nullable|string|max:100',
            'whatsapp' => 'nullable|string|max:30',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            // opcional: apagar o avatar anterior
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                try { Storage::disk('public')->delete($user->avatar); } catch (\Throwable $e) {}
            }
            $data['avatar'] = $path;

            // Crop/resize para quadrado 256x256 se GD estiver disponível
            try {
                $fullPath = Storage::disk('public')->path($path);
                if (function_exists('getimagesize') && function_exists('imagecreatefromstring')) {
                    [$w, $h, $type] = getimagesize($fullPath);
                    $srcData = file_get_contents($fullPath);
                    if ($srcData !== false) {
                        $src = @imagecreatefromstring($srcData);
                        if ($src !== false) {
                            $size = min($w, $h);
                            $srcX = max(0, (int)(($w - $size) / 2));
                            $srcY = max(0, (int)(($h - $size) / 2));
                            $dstSize = 256;
                            $dst = imagecreatetruecolor($dstSize, $dstSize);
                            imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $dstSize, $dstSize, $size, $size);
                            // Salvar conforme o tipo
                            switch ($type) {
                                case IMAGETYPE_PNG:
                                    // manter transparência
                                    imagealphablending($dst, false);
                                    imagesavealpha($dst, true);
                                    imagepng($dst, $fullPath, 6);
                                    break;
                                case IMAGETYPE_WEBP:
                                    imagewebp($dst, $fullPath, 85);
                                    break;
                                default:
                                    imagejpeg($dst, $fullPath, 85);
                            }
                            imagedestroy($dst);
                            imagedestroy($src);
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Se falhar o processamento, manter arquivo original
            }
        }

        $data['plays_tennis'] = isset($data['plays_tennis']) ? (bool)$data['plays_tennis'] : false;
        $data['public_profile'] = isset($data['public_profile']) ? (bool)$data['public_profile'] : false;

        // Atualizar slug se o nome mudou
        if ($user->name !== $data['name']) {
            $base = Str::slug($data['name']);
            $slug = $base ?: ('user-'.$user->id);
            $n = 1;
            while (DB::table('users')->where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
                $slug = $base.'-'.(++$n);
            }
            $data['slug'] = $slug;
        }

        $user->update($data);

        return redirect()->route('profile.edit')->with('status', 'Perfil atualizado com sucesso!');
    }
}
