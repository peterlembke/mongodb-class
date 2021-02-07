# MongoDb Query Language

The main thing with MongoDb is how it find data. The [Aggregation pipeline](https://docs.mongodb.com/manual/core/aggregation-pipeline/) have one or more [stages](https://docs.mongodb.com/manual/reference/operator/aggregation-pipeline/#aggregation-pipeline-operator-reference).

In an aggregation stage you can use [operators](https://docs.mongodb.com/manual/reference/operator/aggregation/) to get what data you want in this stage.

* [stage types](https://docs.mongodb.com/manual/reference/operator/aggregation-pipeline/)
* [operators](https://docs.mongodb.com/manual/reference/operator/aggregation/)

## Create your aggregation with MongoDB Compass
You can use the [Aggregation Pipeline Builder](https://docs.mongodb.com/compass/master/aggregation-pipeline-builder#aggregation-pipeline-builder) in MongoDb Compass.

Save your aggregation and [export the aggregation](https://docs.mongodb.com/compass/master/export-pipeline-to-language).  
You can not export to PHP but you can export to Python and manually substitue all {} to [] and all : to =>    
Then you can paste the code into your PHP project.

## PHP Example
This example can be found in the example.php. 
Stage #1: Match all items born after 1950 and have club set to hif or aik.
Stage #2: Group on club and count how many of each club.
```
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
```
The result might look like
```
[
    {
        "_id": "hif",
        "club_count": 78
    },
    {
        "_id": "aik",
        "club_count": 39
    }
]
```
Observe that the order of the items can vary if you run the query again.

