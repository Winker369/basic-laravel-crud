<?php

namespace App\Http\Controllers;

use App\Models\DataProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class DataProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataProviders = DataProvider::paginate(10);

        return view('index', compact('dataProviders'));
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
            'name' => 'required',
            'url' => 'required',
        ]);
        $response = [
            'status_code' => 400,
            'body' => [
                'status_code' => 400,
                'message' =>  $validator->errors()->all()
            ]
        ];

        if (!$validator->fails()) {
            $dataProvider = new DataProvider;
            $dataProvider->name = $request->name;
            $dataProvider->url = $request->url;

            if ($dataProvider->save()) {
                $response = [
                    'status_code' => 200,
                    'body' => [
                        'status_code' => 200,
                        'message' => 'Data provider created successfully',
                    ]
                ];
            }
        }

        return response()->json($response, $response['status_code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = [
            'status_code' => 400,
            'body' => [
                'status_code' => 400,
                'message' =>  'Data provider is not valid'
            ]
        ];
        $dataProvider = DataProvider::find($id);

        if ($dataProvider) {
            $response = Http::get($dataProvider->url);
            $responseData = json_decode($response->body(), true);
            $response = [
                'status_code' => 200,
                'body' => [
                    'status_code' => 200,
                    'message' => 'Random data retreived successfully',
                    'data' => $responseData
                ]
            ];
        }

        return response()->json($response, $response['status_code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = [
            'status_code' => 400,
            'body' => [
                'status_code' => 400,
                'message' =>  'Data provider is not valid'
            ]
        ];
        $dataProvider = DataProvider::find($id);

        if ($dataProvider) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'url' => 'required',
            ]);
            $response = [
                'status_code' => 400,
                'body' => [
                    'status_code' => 400,
                    'message' =>  $validator->errors()->all()
                ]
            ];

            if (!$validator->fails()) {
                $dataProvider->name = $request->name;
                $dataProvider->url = $request->url;

                if ($dataProvider->save()) {
                    $response = [
                        'status_code' => 200,
                        'body' => [
                            'status_code' => 200,
                            'message' => 'Data provider updated successfully',
                            'data' => $dataProvider
                        ]
                    ];
                }
            }
        }

        return response()->json($response, $response['status_code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = [
            'status_code' => 400,
            'body' => [
                'status_code' => 400,
                'message' =>  'Data provider is not valid'
            ]
        ];
        $dataProvider = DataProvider::find($id);

        if ($dataProvider) {
            if ($dataProvider->delete()) {
                $response = [
                    'status_code' => 200,
                    'body' => [
                        'status_code' => 200,
                        'message' => 'Data provider deleted successfully',
                    ]
                ];
            }
        }

        return response()->json($response, $response['status_code']);
    }
}
