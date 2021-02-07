<?php
/**
 * Example file that use the MongoDb class
 *
 * @author      Peter Lembke <info@charzam.com>
 * @version     2021-02-06
 * @since       2021-02-06
 * @copyright   Copyright (c) 2021, Peter Lembke
 * @license     https://opensource.org/licenses/gpl-license.php GPL-3.0-or-later
 * @see         https://github.com/peterlembke/mongodb-class
 */

include_once "mongodbclass.php";

class example
{
    /** @var MongoDbClass */
    protected $mongodb;

    public function __construct(
        MongoDbClass $mongodb
    ) {
        $this->mongodb = $mongodb;
    }

    public function run(): void {
        $this->connect();
        // $this->create();
        // $this->info();
        $this->query();
    }

    protected function connect(): void {
        $this->mongodb->setUserName('root');
        $this->mongodb->setPassword('infohub');
        $this->mongodb->setHost('172.19.0.4');
        $this->mongodb->setPort(27017);
        $this->mongodb->setDatabaseName('local');
        $this->mongodb->setCollectionName('example');
        $result = $this->mongodb->connect();

        if ($result === false) {
            exit('Can not connect to database');
        }
    }

    protected function create(): void {
        $this->mongodb->collectionCreate();
        $item = ['first_name' => 'Adam', 'last_name' => 'Adamson', 'city' => 'Stockholm', 'born' => 1922, 'telephone' => '555-1234', 'club' => 'aik'];
        $this->mongodb->bulkInsert($item);
        $item = ['first_name' => 'Bertil', 'last_name' => 'Bertilson', 'city' => 'Uppsala', 'born' => 1945, 'telephone' => '555-2345', 'club' => 'aik'];
        $this->mongodb->bulkInsert($item);
        $item = ['first_name' => 'Cesar', 'last_name' => 'Cesarson', 'city' => 'Uppsala', 'born' => 1951, 'telephone' => '555-3456', 'club' => 'aik'];
        $this->mongodb->bulkInsert($item);
        $item = ['first_name' => 'David', 'last_name' => 'Davidson', 'city' => 'Uppsala', 'born' => 1965, 'telephone' => '555-4567', 'club' => 'hif'];
        $this->mongodb->bulkInsert($item);
        $item = ['first_name' => 'Erik', 'last_name' => 'Erikson', 'city' => 'Stockholm', 'born' => 1971, 'telephone' => '555-5678', 'club' => 'hif'];
        $this->mongodb->bulkInsert($item);
        $this->mongodb->bulkWrite();

        $this->mongodb->bulkIndexAdd('city', ['city' => 1]);
        $this->mongodb->bulkIndexAdd('born', ['born' => 1]);
        $this->mongodb->bulkIndexAdd('club', ['club' => 1]);
        $this->mongodb->bulkIndexCreate();
    }

    protected function info(): void {
        $databaseCollectionName = $this->mongodb->getDatabaseCollectionName();
        $this->titlePrint('Current database and collection name');
        $this->prettyPrint([$databaseCollectionName]);

        $list = $this->mongodb->databaseList(true);
        $this->titlePrint('database list');
        $this->prettyPrint($list);

        $list = $this->mongodb->collectionList(true);
        $this->titlePrint('collection list');
        $this->prettyPrint($list);

        $list = $this->mongodb->indexList(true);
        $this->titlePrint('index list');
        $this->prettyPrint($list);

        $statistics = $this->mongodb->collectionStatistics();
        $this->titlePrint('collection statistics');
        $this->prettyPrint($statistics);

        $statistics = $this->mongodb->getBuildInformation();
        $this->titlePrint('build information');
        $this->prettyPrint($statistics);
    }

    protected function query(): void {
        // $this->loadById();
        // $this->loadBorn1922();
        // $this->loadGreaterThanAndIn();
        $this->aggregate();
    }

    /**
     * Each document has a unique id
     */
    protected function loadById(): void {
        $queryResult = $this->mongodb->loadById('601ecc8db556b81ba2793944');
        $this->titlePrint('load by id');
        $this->prettyPrint($queryResult);
    }

    /**
     * Example of a match of value. You can have more matching fields in the array
     */
    protected function loadBorn1922(): void {
        $filter = ['born' => 1922];
        $options = []; // ['projection' => ['_id' => 0]];
        $queryResult = $this->mongodb->loadCollection($filter, $options);
        $this->titlePrint('loadCollection born = 1922');
        $this->prettyPrint($queryResult);
    }

    /**
     * Example of $gt - greater than, and $in - in array
     * @see https://docs.mongodb.com/manual/reference/operator/aggregation/
     * @example MQL {born: {$gt: 1950}, club: {$in: ['aik','hif']}}
     */
    protected function loadGreaterThanAndIn(): void {
        $filter = ['born' => ['$gt' => 1950], 'club' => ['$in' => ['aik', 'dif']]];
        $options = []; // ['projection' => ['_id' => 0]];
        $queryResult = $this->mongodb->loadCollection($filter, $options);
        $this->titlePrint('loadCollection born > 1950 & club = aik or dif');
        $this->prettyPrint($queryResult);
    }

    /**
     * Aggregation in a pipeline with stages is what MongoDb is about
     * This example show two stages
     * @see https://docs.mongodb.com/manual/reference/operator/aggregation/group/
     * @see https://docs.mongodb.com/manual/reference/operator/aggregation/match/
     */
    protected function aggregate(): void {
        $pipeline = [
            [
                '$match' => [
                    'born' => ['$gt' => 1950],
                    'club' => ['$in' => ['hif', 'aik']]
                ]
            ],
            [
                '$group' => [
                    '_id' => '$club',
                    'club_count' => ['$sum' => 1]
                ]
            ]
        ];
        $queryResult = $this->mongodb->collectionAggregate($pipeline);
        $this->titlePrint('Aggregate a collection');
        $this->prettyPrint($queryResult);
    }

    protected function titlePrint(string $title = ''): void {
        echo '<h2>' . $title . '</h2>';
    }

    protected function prettyPrint(array $data = []): void {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        echo '<pre>' . $jsonData . '</pre>';
    }

}

$myMongoDbClass = new MongoDbClass();
$myExample = new example($myMongoDbClass);
$myExample->run();