<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;

class TiketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiket = Tiket::orderBy('id', 'DESC')->get();
        
        $response = [
            'message' => 'Tiket diurutkan berdasarkan data terbaru',
            'data' => $tiket
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'unique:tikets'],
            'number_phone' => ['required'],
            'jenis_kelamin' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $now = Carbon::now();
        $bulanHari = $now->day . $now->month;
        $cekTiket = Tiket::count();
        $nomor = 0;
        if($cekTiket == 0) {
            $nomorUrut = 10000001;
            $nomor = 'TK' . $bulanHari . $nomorUrut;
        } else {
            $ambil = Tiket::all()->last();
            $nomorUrut = (int)substr($ambil->id_tiket, -8) + 1;
            $nomor = 'TK' . $bulanHari . $nomorUrut;
        }
        
        try {
            $tiket = Tiket::create([
                'name' => $request->name,
                'email' => $request->email,
                'number_phone' => $request->number_phone,
                'jenis_kelamin' => $request->jenis_kelamin,
                'id_tiket' => $nomor,
                'status_checkin' => 0
            ]);
            $response = [
                'message' => 'Berhasil memesan tiket!',
                'data' => $tiket
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $err) {
            return response()->json([
                'message' => 'Failed ' . $err->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tiket  $tiket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tiket = Tiket::findOrFail($id);
        $response = [
            'message' => 'Detail dari tiket berdasarkan ID',
            'data' => $tiket
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tiket  $tiket
     * @return \Illuminate\Http\Response
     */
    public function edit(Tiket $tiket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tiket  $tiket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tiket = Tiket::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'number_phone' => ['required'],
            'jenis_kelamin' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $tiketBaru = $tiket->update([
                'name' => $request->name,
                'email' => $request->email,
                'number_phone' => $request->number_phone,
                'jenis_kelamin' => $request->jenis_kelamin,
                'id_tiket' => $request->id_tiket,
                'status_checkin' => 0
            ]);
            $response = [
                'message' => 'Berhasil mengubah tiket!',
                'data' => $tiketBaru
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $err) {
            return response()->json([
                'message' => 'Failed ' . $err->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tiket  $tiket
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tiket = Tiket::findOrFail($id);

        try {
            $tiket->delete();
            $response = [
                'message' => 'Tiket dihapus'
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $error) {
            return response()->json([
                'message' => 'Failed' . $error->errorInfo
            ]);
        }
    }

    // Searching by email
    public function searchByEmail($email) 
    {
        $tiket = Tiket::where('email', $email)->get();

        if (count($tiket) > 0) {
            $response = [
                'message' => 'Data ditemukan',
                'data' => $tiket
            ];
        } else {
            return response()->json([
                'messsage' => 'Tiket tidak ditemukan!'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($response, Response::HTTP_OK);
    }

    // Searching by ID tiket
    public function searchByIDTiket($id_tiket) 
    {
        $tiket = Tiket::where('id_tiket', $id_tiket)->get();

        if (count($tiket) > 0) {
            $response = [
                'message' => 'Data ditemukan',
                'data' => $tiket
            ];
        } else {
            return response()->json([
                'messsage' => 'Tiket tidak ditemukan!'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($response, Response::HTTP_OK);
    }

    // Update status checkin
    public function changeStatus($id)
    {
        $tiket = Tiket::findOrFail($id);

        $tiket->status_checkin = 1;

        $status = $tiket->save();

        $response = [
            'message' => 'Status berhasil diubah',
            'success' => $status
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
