<?php

namespace App\Http\Controllers;

use App\Http\Resources\MobilResource;
use App\Models\Mobil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    /**
     * Daftar mobil
     *
     * Mengambil seluruh data mobil. Mendukung pencarian (`search`) dan
     * penyaringan berdasarkan `brand`, `status`, `transmisi`, dan `bahan_bakar`.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'status' => 'nullable|in:tersedia,sold',
            'transmisi' => 'nullable|in:Manual,Matic,CVT',
            'bahan_bakar' => 'nullable|in:Bensin,Diesel,Hybrid,Listrik',
        ], $this->pesanValidasi());

        $mobils = Mobil::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");
                });
            })
            ->when($filters['brand'] ?? null, fn ($query, $brand) => $query->where('brand', $brand))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['transmisi'] ?? null, fn ($query, $transmisi) => $query->where('transmisi', $transmisi))
            ->when($filters['bahan_bakar'] ?? null, fn ($query, $bbm) => $query->where('bahan_bakar', $bbm))
            ->orderBy('id')
            ->get();

        return response()->json([
            'message' => 'Daftar mobil berhasil diambil',
            'data' => MobilResource::collection($mobils),
        ]);
    }

    /**
     * Tambah mobil baru
     *
     * Menyimpan unit mobil baru ke database.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'harga' => 'required|integer|min:0',
            'tahun' => 'required|integer|between:1900,2100',
            'transmisi' => 'required|in:Manual,Matic,CVT',
            'bahan_bakar' => 'required|in:Bensin,Diesel,Hybrid,Listrik',
            'status' => 'required|in:tersedia,sold',
            'foto' => 'nullable|url|max:2048',
        ], $this->pesanValidasi());

        $mobil = Mobil::create($validated);

        return response()->json([
            'message' => 'Data mobil berhasil ditambahkan',
            'data' => new MobilResource($mobil),
        ], 201);
    }

    /**
     * Detail mobil
     *
     * Mengambil detail satu unit mobil berdasarkan ID.
     */
    public function show(Mobil $mobil): JsonResponse
    {
        return response()->json([
            'message' => 'Detail mobil berhasil diambil',
            'data' => new MobilResource($mobil),
        ]);
    }

    /**
     * Perbarui mobil
     *
     * Memperbarui data unit mobil. Field yang tidak dikirim tidak akan diubah,
     * sehingga method PUT maupun PATCH sama-sama dapat digunakan.
     */
    public function update(Request $request, Mobil $mobil): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'brand' => 'sometimes|required|string|max:100',
            'harga' => 'sometimes|required|integer|min:0',
            'tahun' => 'sometimes|required|integer|between:1900,2100',
            'transmisi' => 'sometimes|required|in:Manual,Matic,CVT',
            'bahan_bakar' => 'sometimes|required|in:Bensin,Diesel,Hybrid,Listrik',
            'status' => 'sometimes|required|in:tersedia,sold',
            'foto' => 'sometimes|nullable|url|max:2048',
        ], $this->pesanValidasi());

        $mobil->update($validated);

        return response()->json([
            'message' => 'Data mobil berhasil diperbarui',
            'data' => new MobilResource($mobil),
        ]);
    }

    /**
     * Hapus mobil
     *
     * Menghapus unit mobil dari database.
     */
    public function destroy(Mobil $mobil): JsonResponse
    {
        $mobil->delete();

        return response()->json([
            'message' => 'Data mobil berhasil dihapus',
            'data' => null,
        ]);
    }

    /**
     * Pesan validasi berbahasa Indonesia.
     *
     * @return array<string, string>
     */
    private function pesanValidasi(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'integer' => 'Kolom :attribute harus berupa angka.',
            'url' => 'Kolom :attribute harus berupa URL yang valid.',
            'in' => 'Kolom :attribute harus salah satu dari: :values.',
            'max' => 'Kolom :attribute maksimal :max.',
            'min' => 'Kolom :attribute minimal :min.',
            'between' => 'Kolom :attribute harus antara :min dan :max.',
        ];
    }
}
