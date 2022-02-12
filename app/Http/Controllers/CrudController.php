<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Exports\CompaniesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Company;
use GuzzleHttp\Client;
use Yajra\Datatables\Facades\Datatables;

class CrudController extends Controller
{
    private static $hostName = 'http://127.0.0.1:8000/api';

    public function index()
    {
        $endpoint = self::$hostName . '/crud';

        $response = Http::get($endpoint);

        $list = json_decode($response->getBody(), true);

        return view('companies', ['data' => $list['data']]);
    }

    public function list()
    {
        $response = Http::get(self::$hostName . '/crud');
        $list = json_decode($response->getBody(), true);
        $list = $list['data'];
        return datatables()->of($list)
            ->addColumn('action', 'company-action')
            ->rawColumns(['action'])
            ->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        Http::asForm()->post(
            self::$hostName . '/store-company',
            [
                'id' => $request->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address
            ]
        );
    }

    public function edit(Request $request)
    {
        $payload = Http::asForm()->post(
            self::$hostName . '/edit-company',
            [
                'id' => $request->id
            ]
        );

        return $payload;
    }

    public function destroy(Request $request)
    {
        Http::asForm()->post(
            self::$hostName . '/delete-company',
            [
                'id' => $request->id
            ]
        );

        $ListCompanies = Http::get(self::$hostName . '/crud');

        return json_decode($ListCompanies->getBody(), true);
    }

    public function export()
    {
        return Excel::download(new CompaniesExport, 'Companies.xlsx');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        $rows = Excel::toArray(new \App\Imports\CompaniesImport, $file);

        $cleanData = array_map(function ($tag) {
            return array(
                'no' => $tag['no'],
                'id' => $tag['id'],
                'name' => $tag['nama'],
                'email' => $tag['email'],
                'phone' => $tag['phone'],
                'address' => $tag['address'],
                'created_at' => $tag['created'],
                'updated_at' => $tag['update']
            );
        }, $rows[0]);

        $response = Http::asForm()->post(
            self::$hostName . '/import-companies',
            [
                'data' => $cleanData
            ]
        );

        return $response;
    }
}
