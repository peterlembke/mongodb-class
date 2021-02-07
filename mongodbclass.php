<?php
/**
 * MongoDb class that use the new MongoDb driver to perform common tasks with minimum of options.
 *
 * @author      Peter Lembke <info@charzam.com>
 * @version     2021-02-06
 * @since       2021-02-06
 * @copyright   Copyright (c) 2021, Peter Lembke
 * @license     https://opensource.org/licenses/gpl-license.php GPL-3.0-or-later
 * @see         https://github.com/peterlembke/mongodb-class
 */
class MongoDbClass
{
    /** @var string $host */
    protected $host = '';

    /** @var string $port */
    protected $port = 27017;

    /** @var string $userName */
    protected $userName = '';

    /** @var string $password */
    protected $password = '';

    /** @var string $databaseName */
    protected $databaseName = '';

    /** @var string $collectionName */
    protected $collectionName = '';

    /** @var MongoDB\Driver\Manager $manager */
    protected $manager = '';

    /** @var array $bulk */
    protected $bulk = [];

    /** @var array $bulkIndex */
    protected $bulkIndex = [];

    /**
     * Set the host.
     * Can be a domain name or an ip number
     * @param string $host
     */
    public function setHost(
        string $host = ''
    ): void {
        $this->host = $host;
    }

    /**
     * Set the host.
     * Can be a domain name or an ip number
     * @param int $port
     */
    public function setPort(
        int $port = 0
    ): void {
        $this->port = $port;
    }

    /**
     * Set the user name
     * @param string $userName
     */
    public function setUserName(
        string $userName = ''
    ): void {
        $this->userName = $userName;
    }

    /**
     * Set the password
     * @param string $password
     */
    public function setPassword(
        string $password = ''
    ): void {
        $this->password = $password;
    }

    /**
     * Set the database name
     * @param string $databaseName
     */
    public function setDatabaseName(
        string $databaseName = ''
    ): void {
        $this->databaseName = $databaseName;
    }

    /**
     * Set the collection name
     * @param string $collectionName
     */
    public function setCollectionName(
        string $collectionName = ''
    ): void {
        $this->collectionName = $collectionName;
    }

    /**
     * Connect to MongoDb using the credentials you have provided above
     * @return bool
     */
    public function connect(): bool {
        $user = [
            "username" => $this->userName,
            "password" => $this->password
        ];
        $connectionString = 'mongodb://' . $this->host . ':' . $this->port;

        try {
            $this->manager = new MongoDB\Driver\Manager($connectionString, $user);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Get a string with databaseName.collectionName
     * @return string
     */
    public function getDatabaseCollectionName(): string {
        return $this->databaseName . '.' . $this->collectionName;
    }

    /**
     * Execute a command and return the response array
     * @see https://www.php.net/manual/en/class.mongodb-driver-command.php
     * @see https://www.php.net/manual/en/mongodb-driver-manager.executecommand.php
     * @see https://www.php.net/manual/en/class.mongodb-driver-cursor.php
     * @param array $commandArray
     * @param array $options
     * @return array
     */
    protected function execute(array $commandArray = []): array {
        $command = new MongoDB\Driver\Command($commandArray);

        $responseArray = [
            'answer' => false,
            'message' => 'Got nothing back from the command',
            'code_name' => ''
        ];

        try {
            /** @var MongoDB\Driver\Cursor $cursor */
            $cursor = $this->manager->executeCommand($this->databaseName, $command);
            $cursorArray = $cursor->toArray();
            if (count($cursorArray) > 0) {
                $responseArray = json_decode(json_encode($cursorArray), true);
            }
        } catch (MongoDb\Driver\Exception\CommandException $e) {
            $responseArray = [
                'answer' => false,
                'message' => $e->getMessage(),
                'code_name' => $e->getResultDocument()->codeName
            ];
        }

        return $responseArray;
    }

    /**
     * Execute a command and return the response array
     * @param array $filter
     * @param array $options
     * @return array
     */
    protected function query(array $filter = [], array $options = []): array {
        $query = new \MongoDB\Driver\Query($filter, $options);
        $rows = $this->manager->executeQuery($this->getDatabaseCollectionName(), $query);
        $resposeArray = $rows->toArray();

        return $resposeArray;
    }

    /**
     * Get an id object from an id string
     * @param string $id
     * @return \MongoDB\BSON\ObjectId
     */
    protected function getObjectId(string $id = ''): \MongoDB\BSON\ObjectId {
        return new \MongoDB\BSON\ObjectId($id);
    }

    /**
     * Clear the bulk
     */
    public function bulkClear(
    ): void {
        $this->bulk = [];
    }

    /**
     * Add an item to insert
     * @see https://www.php.net/manual/en/mongodb-driver-bulkwrite.insert.php
     * @param array $item
     */
    public function bulkInsert(
        array $item = []
    ): void {
        $this->bulk['insert'][] = $item;
    }

    /**
     * Add a command to delete. You must run bulkWrite
     * @see https://www.php.net/manual/en/mongodb-driver-bulkwrite.delete.php
     * @param array $filter
     * @param array $options
     */
    public function bulkDelete(
        array $filter = [],
        array $options = []
    ): void {
        $this->bulk['delete'][] = [$filter,$options];
    }

    /**
     * Add a command to update. You must run bulkWrite
     * @see https://www.php.net/manual/en/mongodb-driver-bulkwrite.update.php
     * @example $filter = ['name' => 'Peter'], $set = ['age' => 50, 'updated_at' => '2021-02-06']
     * @param array $filter
     * @param array $set
     */
    public function bulkUpdate(
        array $filter = [],
        array $set = []
    ): void {
        $options = ['$set' => $set]; // Set new values
        $this->bulk['update'][] = [$filter,$options];
    }

    /**
     * Run all your delete, insert, update
     * @return array
     */
    public function bulkWrite(): array {
        $bulk = new MongoDB\Driver\BulkWrite;
        foreach ($this->bulk as $commandTypeString => $commandCollectionArray) {

            switch ($commandTypeString) {
                case 'insert':
                    foreach ($commandCollectionArray as $item) {
                        $bulk->insert($item);
                    }
                    break;
                case 'update':
                    foreach ($commandCollectionArray as $item) {
                        $bulk->update($item[0], $item[1]);
                    }
                    break;
                case 'delete':
                    foreach ($commandCollectionArray as $item) {
                        $bulk->delete($item[0], $item[1]);
                    }
                    break;
            }
        }

        /** @var MongoDB\Driver\WriteResult $writeResult */
        $writeResult = $this->manager->executeBulkWrite($this->getDatabaseCollectionName(), $bulk);

        $resultArray = [
            'inserted_count' => $writeResult->getInsertedCount(),
            'updated_count' => $writeResult->getModifiedCount(),
            'deleted_count' => $writeResult->getDeletedCount()
        ];

        return $resultArray;
    }

    /**
     * Load and return an item found by its id
     * @param string $itemId
     * @return array
     */
    public function loadById(string $itemId = ''): array {
        $objectId = $this->getObjectId($itemId);
        $filter = ['_id' => $objectId];
        $options = []; // ['projection' => ['_id' => 0]];

        return $this->query($filter, $options);
    }

    /**
     * Load and return a collection array with items
     * @example $filter = ['foo' => 'yes'], $options = ['projection' => ['_id' => 0]]
     * @param array $filter
     * @param array $options
     * @return array
     */
    public function loadCollection(array $filter = [], array $options = []): array {
        return $this->query($filter, $options);
    }

    /**
     * Get statistics for the collection you have set
     * @param bool $compact
     * @return array
     */
    public function collectionStatistics(bool $compact = true): array {
        $commandArray = ["collstats" => $this->collectionName];
        $response = $this->execute($commandArray);
        if ($compact === false) {
            return $response;
        }
        $result = [];
        foreach ($response as $item) {
            unset($item['wiredTiger']);
            unset($item['indexDetails']);
            $name = $item['ns'];
            $result[$name] = $item;
        }
        return $result;
    }

    /**
     * Add an index definition that you will create with bulkIndexCreate
     * @see https://stackoverflow.com/questions/46819481/php7-mongodb-create-index
     * @param string $indexName
     * @param array $keyArray
     * @param bool $unique
     * @param int $expireAfterSeconds
     */
    public function bulkIndexAdd(
        string $indexName = '',
        array $keyArray = [],
        bool $unique = false,
        int $expireAfterSeconds = 0
    ): void {

        $index = [
            "name" => $indexName,
            "key" => $keyArray,
            "ns" => $this->getDatabaseCollectionName(),
            'unique' => $unique
        ];

        if ($expireAfterSeconds > 0) {
            $index['expireAfterSeconds'] = $expireAfterSeconds;
        }

        $this->bulkIndex[] = $index;
    }

    /**
     * Create all indexes you have added with bulkIndexAdd
     * @see https://www.php.net/manual/en/class.mongodb-driver-cursor.php
     */
    public function bulkIndexCreate(): array {
        $commandArray = [
            "createIndexes" => $this->collectionName,
            "indexes" => $this->bulkIndex
        ];
        return $this->execute($commandArray);
    }

    /**
     * Delete an index
     * @param string $indexName
     * @return array
     */
    public function indexDelete(string $indexName = ''): array {
        $commandArray = [
            'dropIndexes' => $this->collectionName,
            'index' => $indexName
        ];
        return $this->execute($commandArray);
    }

    /**
     * Delete an index
     * @param bool $nameOnly
     * @return array
     */
    public function indexList(bool $nameOnly = true): array {
        $commandArray = ['listIndexes' => $this->collectionName];
        $response = $this->execute($commandArray);
        if ($nameOnly === false) {
            return $response;
        }
        $result = [];
        foreach ($response as $item) {
            $result[] = $item['name'];
        }
        return $result;
    }

    /**
     * Get a list with all database names on the server
     * @param bool $nameOnly
     * @return array
     */
    public function databaseList(bool $nameOnly = true): array {
        $currentDatabase = $this->databaseName;
        $this->databaseName = 'admin';
        $commandArray = ['listDatabases' => 1, 'nameOnly' => $nameOnly];
        $response = $this->execute($commandArray);
        $this->databaseName = $currentDatabase;
        if ($nameOnly === false) {
            return $response;
        }
        $result = [];
        foreach ($response[0]['databases'] as $item) {
            $result[] = $item['name'];
        }
        return $result;
    }

    /**
     * Get a list with all collection names in the database
     * @param bool $nameOnly
     * @return array
     */
    public function collectionList(bool $nameOnly = true): array {
        $commandArray = ['listCollections' => 1, 'nameOnly' => $nameOnly];
        $response = $this->execute($commandArray);
        if ($nameOnly === false) {
            return $response;
        }
        $result = [];
        foreach ($response as $item) {
            $result[] = $item['name'];
        }
        return $result;
    }

    /**
     * Delete a database
     * @return array
     */
    public function databaseDelete(): array {
        $commandArray = ['dropDatabase' => 1];
        return $this->execute($commandArray);
    }

    /**
     * Delete a collection
     * @return array
     */
    public function collectionDelete(): array {
        $commandArray = ['drop' => $this->collectionName];
        return $this->execute($commandArray);
    }

    /**
     * Create a database
     * It seems to be enough to create a collection and then the database are also created
     */
    public function databaseCreate(): void {

    }

    /**
     * Create a collection
     * @see https://www.php.net/manual/en/class.mongodb-driver-command.php
     * @return array
     */
    public function collectionCreate(
    ): array {
        $commandArray = ['create' => $this->collectionName];
        return $this->execute($commandArray);
    }

    /**
     * Get information about the MongoDb software
     * @see https://www.php.net/manual/en/mongodb-driver-command.construct.php
     * @param bool $compact
     * @return array
     */
    public function getBuildInformation(bool $compact = true): array {
        $commandArray = ["buildinfo" => 1];
        $response = $this->execute($commandArray);
        return $response[0];
    }

    /**
     * You can send in a pipeline with stages and MQL commands
     * to get the data you want from the collection
     * @see https://www.php.net/manual/en/class.mongodb-driver-command.php#123163
     * @see https://docs.mongodb.com/manual/reference/operator/aggregation-pipeline/
     * @see https://stackoverflow.com/questions/1869091/how-to-convert-an-array-to-object-in-php
     * @see https://docs.mongodb.com/manual/reference/command/aggregate/
     * @param array $pipeline
     * @return array
     */
    public function collectionAggregate(array $pipeline = []): array {
        $commandArray = [
            'aggregate' => $this->collectionName,
            'pipeline' => $pipeline,
            // 'explain' => true, // If aggregation do not work then uncomment to get more information
            'cursor' => new stdClass()
        ];
        return $this->execute($commandArray);
    }
}
