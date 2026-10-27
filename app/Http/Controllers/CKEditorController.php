<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $relativeDir = 'uploads/ckeditor/temp';
            $fullDir = ($relativeDir);

            if (! file_exists($fullDir)) {
                mkdir($fullDir, 0755, true);
            }

            try {
                $filename = time().'_'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
                $destination = $fullDir.DIRECTORY_SEPARATOR.$filename;

                $img = ImageManager::gd()
                    ->read($file->getRealPath())
                    ->toWebp(80);

                file_put_contents($destination, (string) $img);

                $url = asset($relativeDir.'/'.$filename);
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $message = 'Upload berhasil';
            } catch (\Exception $e) {
                $filename = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $file->move($fullDir, $filename);

                $url = asset($relativeDir.'/'.$filename);
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $message = 'Upload berhasil (format original)';
            }

            if ($request->expectsJson() || ! $CKEditorFuncNum) {
                return response()->json([
                    'uploaded' => 1,
                    'fileName' => $filename,
                    'url' => $url,
                ]);
            }

            $safeMessage = addslashes($message);

            return response(
                "<script>
                    if (window.parent && window.parent.Swal) {
                        window.parent.Swal.fire({
                            icon: 'success',
                            title: '{$safeMessage}',
                            text: 'Gambar berhasil diunggah ke server!',
                            confirmButtonColor: '#28166f',
                            confirmButtonText: 'OK',
                            timer: 2500,
                            timerProgressBar: true
                        });
                    }
                    if (window.parent && window.parent.CKEDITOR && window.parent.CKEDITOR.tools) {
                        window.parent.CKEDITOR.tools.callFunction({$CKEditorFuncNum}, '{$url}', '');
                    }
                </script>"
            );
        }

        return response(
            "<script>
                if (window.parent && window.parent.Swal) {
                    window.parent.Swal.fire({
                        icon: 'error',
                        title: 'Upload Gagal',
                        text: 'Tidak ada file yang dipilih atau diunggah.',
                        confirmButtonColor: '#e5251f',
                        confirmButtonText: 'Tutup'
                    });
                }
            </script>",
            400
        );
    }
}
