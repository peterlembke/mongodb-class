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
        $this->create();
        $this->info();
    }

    protected function connect(): void {
        $this->mongodb->setUserName('root');
        $this->mongodb->setPassword('infohub');
        $this->mongodb->setHost('172.19.0.2');
        $this->mongodb->setPort(27017);
        $this->mongodb->setDatabaseName('local');
        $this->mongodb->setCollectionName('infohub');
        $result = $this->mongodb->connect();

        if ($result === false) {
            exit('Can not connect to database');
        }
    }

    protected function create(): void {
        $this->mongodb->collectionCreate();
        $this->mongodb->setCollectionName('example');
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
        $list = $this->mongodb->databaseList();
        var_dump($list);
        $list = $this->mongodb->collectionList();
        var_dump($list);
        $list = $this->mongodb->indexList();
        var_dump($list);
        $statistics = $this->mongodb->collectionStatistics();
        var_dump($statistics);
    }

}

$myMongoDbClass = new MongoDbClass();
$myExample = new example($myMongoDbClass);
$myExample->run();