<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DocumentContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            'id' => ['required'],
            'name' => ['required'],
            'type' => ['required']
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        try{
            $document = DB::table('documents')->insert([
                'document_id' => $request['id'],
                'name' => $request['name'],
                'type' => $request['type'],
                'folder_id' => $request['folder_id'],
                'is_public' => true,
                'owner_id' => $request['owner_id'],
                'share' => json_encode($request['share']),
                'company_id' => 23,
                'timestamp' => $request['timestamp'],
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if($document == true) {
                if(count($request['content']) !== 0) {
                    $blocks = array();
                    $i = 0;
                    foreach ($request['content']['blocks'] as $conten) {
                        $document = DB::table('document_contents')->insert([
                            'document_id' => $request['id'],
                            'type' => $conten['type'],
                            'text' => $conten['text'],
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        $blocks[$i]['type'] = $conten['type'];
                        $blocks[$i]['text'] =  $conten['text'];

                        $i++;    
                    }
                }

                $response = [
                    'error' => false,
                    'message' => 'Success set document',
                    'data' => [
                        'document' => [
                            'id' => $request['id'],
                            'name' => $request['name'],
                            'type' => $request['type'],
                            'folder_id' => $request['folder_id'],
                            'content' => $blocks,
                            'timestamp' => $request['timestamp'],
                            'owner_id' => $request['owner_id'],
                            'company_id' => $request['share']
                        ]
                    ]
                ];

                return response()->json($response, Response::HTTP_CREATED);
            } else {

                $response = [
                    'error' => true,
                    'message' => 'create document Failed!'
                ];
                return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } catch (QueryException $e) {

            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);

        }
    }

    // Endpoint : POST ->  http://document-service.test/api/document-service/document
    // Body Payload
    // {
    //     "id" : "82b07a6f-60cc-4403-8fd2-329ef0de045n",
    //     "name" : "Document Job desc Tech n",
    //     "type" : "document",
    //     "folder_id" : "68uc9aux-8u9a-sj87-o031-qjz7rhgzyj3t",
    //     "content" : {
    //         "blocks" : [
    //             {
    //                 "type" : "paragraph",
    //                 "text" : "This is paragraph 1"
    //             },
    //             {
    //                 "type" : "paragraph",
    //                 "text" : "This is paragraph 2"
    //             }
    //             ]
    //     }, 
    //     "timestamp" : 1605081795,
    //     "owner_id" : 123, 
    //     "share" : [1,23,4232,121], 
    //     "company_id" : 23
    // }

    // Response
    // {
    //     "error": false,
    //     "message": "Success set document",
    //     "data": {
    //         "document": {
    //             "id": "82b07a6f-60cc-4403-8fd2-329ef0de045n",
    //             "name": "Document Job desc Tech n",
    //             "type": "document",
    //             "folder_id": "68uc9aux-8u9a-sj87-o031-qjz7rhgzyj3t",
    //             "content": [
    //                 {
    //                     "type": "paragraph",
    //                     "text": "This is paragraph 1"
    //                 },
    //                 {
    //                     "type": "paragraph",
    //                     "text": "This is paragraph 2"
    //                 }
    //             ],
    //             "timestamp": 1605081795,
    //             "owner_id": 123,
    //             "company_id": [
    //                 1,
    //                 23,
    //                 4232,
    //                 121
    //             ]
    //         }
    //     }
    // }




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
                ['document_id', '=', $request['id']],
                ['type', '=', 'document']])
            ->get();

        $data = array();      
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

            $data['id'] = $document->id;
            $data['name'] = $document->name;
            $data['type'] = $document->type;
            $data['folder_id'] = $document->folder_id;
            $data['content']['blocks'] = $data_content;
            $data['timestamp'] = $document->timestamp;
            $data['owner_id'] = $document->owner_id;
            $data['share'] = $document->share;
        }    

        $response = [
            'error' => false,
            'message' => "Success get document",
            'data' => $data
        ];

        return response()->json($response, Response::HTTP_OK);
    }
    // endpoint : http://document-service.test/api//document-service/document/:document_id // folder_id as a key



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'name' => ['required'],
            'type' => ['required']
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        try{
            $document = DB::table('documents')
                ->where('document_id', $request['id'])
                ->update([
                    'name' => $request['name'],
                    'type' => $request['type'],
                    'folder_id' => $request['folder_id'],
                    'is_public' => true,
                    'owner_id' => $request['owner_id'],
                    'share' => json_encode($request['share']),
                    'company_id' => 23,
                    'timestamp' => $request['timestamp'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);

            if($document == true) {
                if(count($request['content']) !== 0) {

                    DB::table('document_contents')->where('document_id', '=', $request['id'])->delete();
                    $blocks = array();
                    $i = 0;
                    foreach ($request['content']['blocks'] as $conten) {
                        $document = DB::table('document_contents')
                            ->where('document_id', $request['id'])
                            ->insert([
                                'document_id' => $request['id'],
                                'type' => $conten['type'],
                                'text' => $conten['text'],
                                'created_at' => date('Y-m-d H:i:s')
                            ]);

                        $blocks[$i]['type'] = $conten['type'];
                        $blocks[$i]['text'] =  $conten['text'];

                        $i++;    
                    }
                }

                $response = [
                    'error' => false,
                    'message' => 'Success set document',
                    'data' => [
                        'document' => [
                            'id' => $request['id'],
                            'name' => $request['name'],
                            'type' => $request['type'],
                            'folder_id' => $request['folder_id'],
                            'content' => $blocks,
                            'timestamp' => $request['timestamp'],
                            'owner_id' => $request['owner_id'],
                            'company_id' => $request['share']
                        ]
                    ]
                ];

                return response()->json($response, Response::HTTP_CREATED);
            } else {

                $response = [
                    'error' => true,
                    'message' => 'update document Failed!'
                ];
                return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } catch (QueryException $e) {

            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);

        }
    }
    // Endpoint : PUT ->  http://document-service.test/api/document-service/document
    // Body Payload
    // {
    //     "id" : "82b07a6f-60cc-4403-8fd2-329ef0de045n",
    //     "name" : "Document Job desc Tech n",
    //     "type" : "document",
    //     "folder_id" : "68uc9aux-8u9a-sj87-o031-qjz7rhgzyj3t",
    //     "content" : {
    //         "blocks" : [
    //             {
    //                 "type" : "paragraph",
    //                 "text" : "This is paragraph 1"
    //             },
    //             {
    //                 "type" : "paragraph",
    //                 "text" : "This is paragraph 2"
    //             }
    //             ]
    //     }, 
    //     "timestamp" : 1605081795,
    //     "owner_id" : 123, 
    //     "share" : [1,23,4232,121], 
    //     "company_id" : 23
    // }

    // Response
    // {
    //     "error": false,
    //     "message": "Success set document",
    //     "data": {
    //         "document": {
    //             "id": "82b07a6f-60cc-4403-8fd2-329ef0de045n",
    //             "name": "Document Job desc Tech n",
    //             "type": "document",
    //             "folder_id": "68uc9aux-8u9a-sj87-o031-qjz7rhgzyj3t",
    //             "content": [
    //                 {
    //                     "type": "paragraph",
    //                     "text": "This is paragraph 1"
    //                 },
    //                 {
    //                     "type": "paragraph",
    //                     "text": "This is paragraph 2"
    //                 }
    //             ],
    //             "timestamp": 1605081795,
    //             "owner_id": 123,
    //             "company_id": [
    //                 1,
    //                 23,
    //                 4232,
    //                 121
    //             ]
    //         }
    //     }
    // }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $document = DB::table('documents')->where([
            ['document_id', '=', $request['id']],
            ['type', '=', 'document']])->get();

        if(count($document) == 0) {
            $response = [
                'error' => true,
                'message' => "document not found!"
            ];
    
            return response()->json($response, Response::HTTP_OK);
        } 

        try{
            DB::table('documents')->where('document_id', '=', $request['id'])->delete();
            DB::table('document_contents')->where('document_id', '=', $request['id'])->delete();

            $response = [
                'error' => false,
                'message' => 'Success delete document'
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
    }

  // end-point : http://document-service.test/api/document-service/document
    //
    // Request Body:
    // {
    //         "id": "82b07a6f-60cc-4403-8fd2-329ef0de0d3d" //id document yang dihapus
    // }
    //
    // Response Body:
    // {
    //     "error": false,
    //     "message": "Success delete document"
    // }

}
