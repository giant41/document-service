<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FolderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::orderBy('id', 'DESC')->get();

        $doc = array();
        $i = 0;
        foreach ($documents as $document){
            $doc[$i]['id'] = $document->document_id;
            $doc[$i]['name'] = $document->name;
            $doc[$i]['type'] = $document->type;
            $doc[$i]['is_public'] = $document->is_public;
            $doc[$i]['owner_id'] = $document->owner_id;
            $doc[$i]['share'] = $document->share;
            $doc[$i]['timestamp'] = $document->timestamp;
            $doc[$i]['company_id'] = $document->company_id;
            $i++;
        }
        $response = [
            'error'=> false,
            'data' => $doc
        ];

        return response()->json($response, Response::HTTP_OK);
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
            'id' => ['required'],
            'name' => ['required'],
            'timestamp' => ['required']
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        try{
            $folder_id = $this->random_str(32);
            $document = DB::table('documents')->insert([
                'document_id' => $request['id'],
                'name' => $request['name'],
                'type' => "folder",
                'folder_id' => $folder_id,
                'is_public' => true,
                'owner_id' => 1,
                'share' => "",
                'company_id' => 130,
                'timestamp' => $request['timestamp'],
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if($document == true) {
                $path = public_path().'/folder/'.$request['name'];
                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode = 0755, true, true);
                }
    
                $response = [
                    'error' => false,
                    'message' => 'folder created',
                    'data' => [
                        'id' => $request['id'],
                        'name' => $request['name'],
                        'type' => "folder",
                        'content' => [],
                        'timestamp' => $request['timestamp'],
                        'owner_id' => 120,
                        'company_id' => 130
                    ]
                ];

                return response()->json($response, Response::HTTP_CREATED);
            } else {

                $response = [
                    'error' => true,
                    'message' => 'create folder Failed!'
                ];
                return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } catch (QueryException $e) {

            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);

        }
    }


    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $documents = DB::table('documents')
            ->where([
                ['folder_id', '=', $request['id']],
                ['type', '=', 'document']])->get();

        $data = array();
        $i = 0;        
        foreach ($documents as $document) {

            
            $document_blocks = DB::table('document_contents')
                ->where('document_id', '=', $document->document_id)
                ->get();
            $data_content = array();
            $j = 0; 
            foreach ($document_blocks as $content) {
                $data_content[$j]['type'] = $content->type;
                $data_content[$j]['text'] = $content->text;
                $j++;
            }    

            $data[$i]['id'] = $document->id;
            $data[$i]['name'] = $document->name;
            $data[$i]['type'] = $document->type;
            $data[$i]['folder_id'] = $document->folder_id;
            $data[$i]['content']['blocks'] = $data_content;
            $data[$i]['timestamp'] = $document->timestamp;
            $data[$i]['owner_id'] = $document->owner_id;
            $data[$i]['share'] = $document->share;
            $i++;
        }    

        $response = [
            'error' => false,
            'data' => $data
        ];

        return response()->json($response, Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // check if folder contain document
        $document = DB::table('documents')->where([
            ['folder_id', '=', $request['id']],
            ['type', '=', 'document']])->get();

        if(count($document) > 0) {
            $response = [
                'error' => true,
                'message' => "Unable to delete this folder, please delete the documents in the folder first.!"
            ];
            return response()->json($response, Response::HTTP_OK);
        } 

        try{
            $folder = DB::table('documents')->where([
                ['folder_id', '=', $request['id']],
                ['type', '=', 'folder']])->get();

                if(count($folder) < 1) {
                    $response = [
                        'error' => true,
                        'message' => "folder not found.!"
                    ];
                    return response()->json($response, Response::HTTP_OK);
                } 

            DB::table('documents')->where('folder_id', '=', $request['id'])->delete();
            $path = public_path().'/folder/'.$folder[0]->name;
            if (File::exists($path)) {
                File::deleteDirectory($path);
            }

            $response = [
                'error' => false,
                'message' => 'Success delete folder'
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
    }


    /**
     * function random string for id.
     *
     * @param  int  $length
     * @param  string  $keyspace
     * @return \Illuminate\Http\Response
     */
    public function random_str( int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyz') {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }

        $generated_key = implode('', $pieces);
        $split1 = substr($generated_key, 0, -24);
        $split2 = substr($generated_key, 8, -20);
        $split3 = substr($generated_key, 12, -16);
        $split4 = substr($generated_key, 16, -12);
        $split5 = substr($generated_key, -12);
        $key = $split1."-".$split2."-".$split3."-".$split4."-".$split5;
        return $key;
    }
    
}
