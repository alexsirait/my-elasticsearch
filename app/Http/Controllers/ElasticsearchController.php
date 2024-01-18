<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\ClientBuilder as ElasticsearchClientBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ElasticsearchController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = ElasticsearchClientBuilder::create()
            ->setHosts(['http://127.0.0.1:9200/'])
            ->build();
    }

    public function index()
    {
        if (!$this->client->indices()->exists(['index' => 'i_siswa'])) {
            $this->client->indices()->create(['index' => 'i_siswa']);
        }
    }

    public function data()
    {
        $response = $this->client->search([
            'index' => 'i_siswa',
            'body' => [
                'query' => [
                    'match_all' => (object) [],
                ],
            ],
            'size' => 10000,
        ]);

        $hits = $response['hits']['hits'];

        $data = [];
        foreach ($hits as $hit) {
            $data[] = $hit['_source'];
        }

        dd($data);
    }

    public function insert()
    {
        $this->client->index([
            'index' => 'i_siswa',
            'body' => [
                'data' => [
                    'name' => "asddsa",
                    'email' => "asdt2t2",
                    'password' => Hash::make('password123'),
                ],
            ],
        ]);

        dd('success');
    }

    public function update()
    {
        $this->client->updateByQuery([
            'index' => 'i_siswa',
            'body' => [
                'script' => [
                    'source' => 'ctx._source.name = "ALEX"',
                ],
                'query' => [
                    'term' => [
                        'id' => 604,
                    ],
                ],
            ],
        ]);
    
        dd('success');
    }

    public function search()
    {
        $response = $this->client->search([
            'index' => 'i_siswa',
            'body' => [
                'query' => [
                    'match' => [
                        'name' => 'ALEX',
                    ],
                ],
            ],
        ]);

        $hits = $response['hits']['hits'];

        $data = [];
        foreach ($hits as $hit) {
            $data[] = $hit['_source'];
        }

        dd($data);
    }
    
    public function reindex()
    {
        if ($this->client->indices()->exists(['index' => 'i_siswa'])) {
            $this->client->deleteByQuery([
                'index' => 'i_siswa',
                'body' => [
                    'query' => [
                        'match_all' => new \stdClass(),
                    ],
                ],
            ]);
        } else {
            $this->client->indices()->create(['index' => 'i_siswa']);
        }
        
        $results = DB::select("SELECT * FROM users");
        
        foreach ($results as $user) {
            $params = [
                'index' => 'i_siswa',
                'body' => $user,
            ];
        
            $this->client->index($params);
        }
        
        dd("success");
    }
}
