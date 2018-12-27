<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/entity)
 *
 * @package kiwi-suite/entity
 * @link https://github.com/kiwi-suite/entity
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace IxocreateTest\Entity\Collection;

use Ixocreate\Entity\Collection\ArrayCollection;
use Ixocreate\Entity\Collection\CollectionCollection;
use Ixocreate\Entity\Collection\CollectionInterface;
use Ixocreate\Entity\Exception\EmptyException;
use Ixocreate\Entity\Exception\InvalidCollectionException;
use Ixocreate\Entity\Exception\InvalidTypeException;
use Ixocreate\Entity\Exception\KeysNotMatchException;
use PHPUnit\Framework\TestCase;

class AbstractCollectionTest extends TestCase
{
    protected $data = [
        [
            'id' => 1,
            'name' => 'Eddard Stark',
            'age' => 34,
        ],
        [
            'id' => 2,
            'name' => 'Catelyn Stark',
            'age' => 33,
        ],
        [
            'id' => 3,
            'name' => 'Daenerys Targaryen',
            'age' => 13,
        ],
        [
            'id' => 4,
            'name' => 'Tyrion Lannister',
            'age' => 24,
        ],
        [
            'id' => 5,
            'name' => 'Jon Snow',
            'age' => 14,
        ],
        [
            'id' => 6,
            'name' => 'Brandon Stark',
            'age' => 7,
        ],
        [
            'id' => 7,
            'name' => 'Sansa Stark',
            'age' => 11,
        ],
        [
            'id' => 8,
            'name' => 'Arya Stark',
            'age' => 9,
        ],
        [
            'id' => 9,
            'name' => 'Theon Greyjoy',
            'age' => 18,
        ],
        [
            'id' => 10,
            'name' => 'Davos Seaworth',
            'age' => 37,
        ],
        [
            'id' => 11,
            'name' => 'Jaime Lannister',
            'age' => 31,
        ],
        [
            'id' => 12,
            'name' => 'Samwell Tarly',
            'age' => 14,
        ],
        [
            'id' => 13,
            'name' => 'Cersei Lannister',
            'age' => 31,
        ],
        [
            'id' => 14,
            'name' => 'Brienne of Tarth',
            'age' => 17,
        ],
        [
            'id' => 15,
            'name' => 'Brandon Stark Twin',
            'age' => 7,
        ],
        [
            'id' => 16,
            'name' => 'Davos Seaworth Twin',
            'age' => 37,
        ],
    ];

    public function testDataIntegrity()
    {
        $collection = new ArrayCollection($this->data);
        $this->assertSame($this->data, $collection->all());

        $collection = new ArrayCollection($this->data, "id");
        $data = [];
        foreach ($this->data as $array) {
            $data[$array['id']] = $array;
        }
        $this->assertSame($data, $collection->all());

        $collection = new ArrayCollection($this->data, function ($item) {
            return $item['name'];
        });
        $data = [];
        foreach ($this->data as $array) {
            $data[$array['name']] = $array;
        }
        $this->assertSame($data, $collection->all());
    }

    public function testDataIntegrityMatchKeyException()
    {
        $this->expectException(KeysNotMatchException::class);
        new ArrayCollection([['id' => 1], ['id' => 1]], 'id');
    }

    public function testAvg()
    {
        $collection = new ArrayCollection($this->data, 'id');

        $avg = 0;
        foreach ($this->data as $array) {
            $avg += $array['age'];
        }
        $avg = (float) ($avg / \count($this->data));
        $this->assertSame($avg, $collection->avg('age'));

        $this->assertSame($avg, $collection->avg(function ($item) {
            return $item['age'];
        }));

        $this->expectException(EmptyException::class);
        $collection = new ArrayCollection([]);
        $collection->avg('id');
    }

    public function testSum()
    {
        $collection = new ArrayCollection($this->data, 'id');

        $sum = 0;
        foreach ($this->data as $array) {
            $sum += $array['age'];
        }
        $this->assertSame((float) $sum, $collection->sum('age'));

        $this->expectException(EmptyException::class);
        $collection = new ArrayCollection([]);
        $collection->avg('id');
    }

    public function testMin()
    {
        $collection = new ArrayCollection($this->data, 'id');

        $youngster = [
            [
                'id' => 6,
                'name' => 'Brandon Stark',
                'age' => 7,
            ],
            [
                'id' => 15,
                'name' => 'Brandon Stark Twin',
                'age' => 7,
            ],
        ];
        $this->assertSame(
            (new ArrayCollection($youngster, 'id'))->all(),
            $collection->min('age')->all()
        );

        $this->assertInstanceOf(ArrayCollection::class, $collection->min('age'));

        $collection = new ArrayCollection($this->data);

        $youngster = [
            [
                'id' => 6,
                'name' => 'Brandon Stark',
                'age' => 7,
            ],
            [
                'id' => 15,
                'name' => 'Brandon Stark Twin',
                'age' => 7,
            ],
        ];
        $this->assertSame(
            (new ArrayCollection($youngster))->all(),
            $collection->min('age')->all()
        );

        $this->expectException(EmptyException::class);
        $collection = new ArrayCollection([]);
        $collection->min('id');
    }

    public function testMax()
    {
        $collection = new ArrayCollection($this->data, 'id');
        $oldGuys = [
            [
                'id' => 10,
                'name' => 'Davos Seaworth',
                'age' => 37,
            ],
            [
                'id' => 16,
                'name' => 'Davos Seaworth Twin',
                'age' => 37,
            ],
        ];
        $this->assertSame(
            (new ArrayCollection($oldGuys, 'id'))->all(),
            $collection->max('age')->all()
        );

        $this->assertInstanceOf(ArrayCollection::class, $collection->max('age'));


        $collection = new ArrayCollection($this->data);
        $oldGuys = [
            [
                'id' => 10,
                'name' => 'Davos Seaworth',
                'age' => 37,
            ],
            [
                'id' => 16,
                'name' => 'Davos Seaworth Twin',
                'age' => 37,
            ],
        ];
        $this->assertSame(
            (new ArrayCollection($oldGuys))->all(),
            $collection->max('age')->all()
        );

        $this->expectException(EmptyException::class);
        $collection = new ArrayCollection([]);
        $collection->max('id');
    }

    public function testKeys()
    {
        $collection = new ArrayCollection($this->data, 'id');
        $data = [];
        foreach ($this->data as $array) {
            $data[] = $array['id'];
        }

        $this->assertSame($data, $collection->keys());
    }

    public function testParts()
    {
        $collection = new ArrayCollection($this->data, 'id');
        $data = [];
        foreach ($this->data as $array) {
            $data[] = $array['name'];
        }

        $this->assertSame($data, $collection->parts("name"));

        $this->assertSame($data, $collection->parts(function ($item) {
            return $item['name'];
        }));
    }

    public function testGet()
    {
        $collection = new ArrayCollection($this->data, 'id');

        $this->assertSame(
            [
                'id' => 12,
                'name' => 'Samwell Tarly',
                'age' => 14,
            ],
            $collection->get(12)
        );

        $this->assertFalse($collection->get("doesntExists", false));
    }

    public function testHas()
    {
        $collection = new ArrayCollection($this->data, 'id');

        $this->assertTrue($collection->has(12));
        $this->assertFalse($collection->has("doesntExists"));
    }

    public function testRandom()
    {
        $collection = new ArrayCollection($this->data, 'id');
        $this->assertSame(['id', 'name', 'age'], \array_keys($collection->random()));

        $oneItem = [
            [
                'id' => 12,
                'name' => 'Samwell Tarly',
                'age' => 14,
            ],
        ];
        $collection = new ArrayCollection($oneItem, 'id');
        $this->assertSame(\current($oneItem), $collection->random());
    }

    public function testEach()
    {
        $collection = new ArrayCollection($this->data);

        $i = 0;
        $result = [];
        $collection->each(function ($item) use (&$result, &$i) {
            if ($i > 0) {
                return false;
            }
            $result[] = $item;
            $i++;
        });

        $this->assertSame([$this->data[0]], $result);
    }

    public function testFilter()
    {
        $collection = new ArrayCollection($this->data);
        $collection = $collection->filter(function ($item) {
            return $item['age'] < 8;
        });

        $this->assertInstanceOf(ArrayCollection::class, $collection);

        $this->assertSame(
            [
                [
                    'id' => 6,
                    'name' => 'Brandon Stark',
                    'age' => 7,
                ],
                [
                    'id' => 15,
                    'name' => 'Brandon Stark Twin',
                    'age' => 7,
                ],
            ],
            $collection->all()
        );

        $collection = new ArrayCollection($this->data, 'id');
        $collection = $collection->filter(function ($item) {
            return $item['age'] < 8;
        });

        $this->assertSame(
            [
                6 => [
                    'id' => 6,
                    'name' => 'Brandon Stark',
                    'age' => 7,
                ],
                15 => [
                    'id' => 15,
                    'name' => 'Brandon Stark Twin',
                    'age' => 7,
                ],
            ],
            $collection->all()
        );
    }

    public function testSort()
    {
        $collection = new ArrayCollection($this->data);
        $collection = $collection->sort(function ($item1, $item2) {
            return $item1['age'] - $item2['age'];
        });

        $this->assertInstanceOf(ArrayCollection::class, $collection);

        $data = $this->data;
        \usort($data, function ($item1, $item2) {
            return $item1['age'] - $item2['age'];
        });

        $this->assertSame($data, $collection->all());


        $collection = new ArrayCollection($this->data, 'id');
        $collection = $collection->sort(function ($item1, $item2) {
            return $item1['age'] - $item2['age'];
        });

        $data = $this->data;
        \usort($data, function ($item1, $item2) {
            return $item1['age'] - $item2['age'];
        });
        $newData = [];
        foreach ($data as $item) {
            $newData[$item['id']] = $item;
        }

        $this->assertSame($newData, $collection->all());
    }

    public function testMerge()
    {
        $newData = [
            [
                'id' => 256,
                'name' => 'Someone else',
                'age' => 33,
            ],
        ];
        $collection1 = new ArrayCollection($this->data);
        $collection2 = new ArrayCollection($newData);

        $collection = $collection1->merge($collection2);
        $this->assertInstanceOf(ArrayCollection::class, $collection);

        $this->assertSame(
            \array_merge($this->data, $newData),
            $collection->all()
        );

        $newData = [
            [
                'id' => 256,
                'name' => 'Someone else',
                'age' => 33,
            ],
        ];
        $collection1 = new ArrayCollection($this->data, 'id');
        $collection2 = new ArrayCollection($newData);

        $collection = $collection1->merge($collection2);
        $tmp = \array_merge($this->data, $newData);
        $newData = [];
        foreach ($tmp as $item) {
            $newData[$item['id']] = $item;
        }

        $this->assertSame(
            $newData,
            $collection->all()
        );

        $this->expectException(InvalidCollectionException::class);
        $collection1->merge(new CollectionCollection([]));
    }

    public function testChunk()
    {
        $collection = new ArrayCollection($this->data);
        $collectionCollection = $collection->chunk(4);

        $this->assertSame(4, $collectionCollection->getCollectionCount());
        $this->assertSame(\count($this->data), $collectionCollection->count());
        $this->assertInstanceOf(CollectionCollection::class, $collectionCollection);

        $chunks = [];
        foreach (\array_chunk($this->data, 4) as $chunk) {
            $chunks[] = $chunk;
        }
        $i = 0;
        /** @var CollectionInterface $collection */
        foreach ($collectionCollection->getCollections() as $collection) {
            $this->assertSame($chunks[$i], $collection->all());
            $i++;
        }

        $collection = new ArrayCollection($this->data, 'id');
        $collectionCollection = $collection->chunk(4);

        $this->assertSame(4, $collectionCollection->getCollectionCount());
        $this->assertSame(\count($this->data), $collectionCollection->count());

        $data = [];
        foreach ($this->data as $key => $array) {
            $data[$array['id']] = $array;
        }

        $chunks = [];
        foreach (\array_chunk($data, 4, true) as $chunk) {
            $chunks[] = $chunk;
        }
        $i = 0;
        /** @var CollectionInterface $collection */
        foreach ($collectionCollection->getCollections() as $collection) {
            $this->assertSame($chunks[$i], $collection->all());
            $i++;
        }

        $collection = new ArrayCollection([], 'id');
        $collectionCollection = $collection->chunk(4);
        $this->assertSame(0, $collectionCollection->getCollectionCount());
    }

    public function testSplit()
    {
        $collection = new ArrayCollection($this->data);
        $collectionCollection = $collection->split(4);

        $this->assertInstanceOf(CollectionCollection::class, $collectionCollection);
        $this->assertSame(4, $collectionCollection->getCollectionCount());
        $this->assertSame(\count($this->data), $collectionCollection->count());

        $chunks = [];
        foreach (\array_chunk($this->data, 4) as $chunk) {
            $chunks[] = $chunk;
        }
        $i = 0;
        /** @var CollectionInterface $collection */
        foreach ($collectionCollection->getCollections() as $collection) {
            $this->assertSame($chunks[$i], $collection->all());
            $i++;
        }

        $collection = new ArrayCollection($this->data, 'id');
        $collectionCollection = $collection->split(4);

        $this->assertSame(4, $collectionCollection->getCollectionCount());
        $this->assertSame(\count($this->data), $collectionCollection->count());

        $data = [];
        foreach ($this->data as $key => $array) {
            $data[$array['id']] = $array;
        }

        $chunks = [];
        foreach (\array_chunk($data, 4, true) as $chunk) {
            $chunks[] = $chunk;
        }
        $i = 0;
        /** @var CollectionInterface $collection */
        foreach ($collectionCollection->getCollections() as $collection) {
            $this->assertSame($chunks[$i], $collection->all());
            $i++;
        }

        $collection = new ArrayCollection([], 'id');
        $collectionCollection = $collection->split(4);
        $this->assertSame(0, $collectionCollection->getCollectionCount());
    }

    public function testNth()
    {
        $collection = new ArrayCollection($this->data);
        $collection = $collection->nth(2);

        $this->assertInstanceOf(ArrayCollection::class, $collection);

        $items = [];
        for ($i = 0; $i < \count($this->data); $i++) {
            if ($i % 2 !== 0) {
                continue;
            }
            $items[] = $this->data[$i];
        }

        $this->assertSame($items, $collection->all());

        $collection = new ArrayCollection($this->data);
        $collection = $collection->nth(3, 1);

        $items = [];
        for ($i = 0; $i < \count($this->data); $i++) {
            if ($i % 3 !== 1) {
                continue;
            }
            $items[] = $this->data[$i];
        }

        $this->assertSame($items, $collection->all());

        $collection = new ArrayCollection($this->data, 'id');
        $collection = $collection->nth(4, 1);

        $items = [];
        for ($i = 0; $i < \count($this->data); $i++) {
            if ($i % 4 !== 1) {
                continue;
            }
            $items[$this->data[$i]['id']] = $this->data[$i];
        }

        $this->assertSame($items, $collection->all());
    }

    public function testDiff()
    {
        $collection1 = new ArrayCollection($this->data);
        $data = $this->data;
        $last = \array_pop($data);
        $collection2 = new ArrayCollection($data);
        $collection = $collection1->diff($collection2);

        $this->assertInstanceOf(ArrayCollection::class, $collection);
        $this->assertSame([$last], $collection->all());

        $collection1 = new ArrayCollection($this->data, 'id');
        $data = $this->data;
        $last = \array_pop($data);
        $collection2 = new ArrayCollection($data);
        $collection = $collection1->diff($collection2);
        $this->assertSame([$last['id'] => $last], $collection->all());

        $this->expectException(InvalidCollectionException::class);
        $collection1->diff(new CollectionCollection([]));
    }

    public function testIntersect()
    {
        $collection1 = new ArrayCollection($this->data);
        $data = $this->data;
        $last = \array_pop($data);
        $collection2 = new ArrayCollection([$last]);
        $collection = $collection1->intersect($collection2);

        $this->assertInstanceOf(ArrayCollection::class, $collection);
        $this->assertSame([$last], $collection->all());

        $collection1 = new ArrayCollection($this->data, 'id');
        $data = $this->data;
        $last = \array_pop($data);
        $collection2 = new ArrayCollection([$last]);
        $collection = $collection1->intersect($collection2);
        $this->assertSame([$last['id'] => $last], $collection->all());

        $this->expectException(InvalidCollectionException::class);
        $collection1->intersect(new CollectionCollection([]));
    }

    public function testPop()
    {
        $collection = new ArrayCollection($this->data);

        $data = $this->data;
        $last = \array_pop($data);

        $this->assertSame($last, $collection->pop());
        $this->assertSame($data, $collection->all());

        $collection = new ArrayCollection($this->data, 'id');

        $data = $this->data;
        $last = \array_pop($data);
        $newData = [];
        foreach ($data as $item) {
            $newData[$item['id']] = $item;
        }

        $this->assertSame($last, $collection->pop());
        $this->assertSame($newData, $collection->all());
    }

    public function testShift()
    {
        $collection = new ArrayCollection($this->data);

        $data = $this->data;
        $first = \array_shift($data);

        $this->assertSame($first, $collection->shift());
        $this->assertSame($data, $collection->all());

        $collection = new ArrayCollection($this->data, 'id');

        $data = $this->data;
        $first = \array_shift($data);
        $newData = [];
        foreach ($data as $item) {
            $newData[$item['id']] = $item;
        }

        $this->assertSame($first, $collection->shift());
        $this->assertSame($newData, $collection->all());
    }

    public function testPull()
    {
        $collection = new ArrayCollection($this->data);
        $pulledCollection = $collection->pull(function ($item) {
            return $item['id'] == 1;
        });

        $this->assertInstanceOf(ArrayCollection::class, $pulledCollection);

        $data = $this->data;
        $first = \array_shift($data);

        $this->assertSame($data, $collection->all());
        $this->assertSame([$first], $pulledCollection->all());


        $collection = new ArrayCollection($this->data, 'id');
        $pulledCollection = $collection->pull(function ($item) {
            return $item['id'] == 1;
        });

        $data = $this->data;
        $first = \array_shift($data);
        $newData = [];
        foreach ($data as $item) {
            $newData[$item['id']] = $item;
        }

        $this->assertSame($newData, $collection->all());
        $this->assertSame([1 => $first], $pulledCollection->all());
    }

    public function testReduce()
    {
        $collection = new ArrayCollection($this->data);

        $sumAge = $collection->reduce(function ($carry, $item) {
            return $carry + $item['age'];
        }, 0);

        $this->assertSame((float) $sumAge, $collection->sum('age'));
    }

    public function testPrepend()
    {
        $add = [
            'id' => 256,
            'name' => 'Someone else',
            'age' => 33,
        ];
        $collection = new ArrayCollection($this->data);
        $collection->prepend($add);
        $data = $this->data;
        \array_unshift($data, $add);
        $this->assertSame($data, $collection->all());

        $add = [
            'id' => 256,
            'name' => 'Someone else',
            'age' => 33,
        ];
        $collection = new ArrayCollection($this->data, "id");
        $collection->prepend($add);
        $data = $this->data;
        \array_unshift($data, $add);
        $newData = [];
        foreach ($data as $item) {
            $newData[$item['id']] = $item;
        }
        $this->assertSame($newData, $collection->all());

        $this->expectException(InvalidTypeException::class);
        $collection = new ArrayCollection($this->data, "id");
        $collection->prepend(false);
    }

    public function testPush()
    {
        $add = [
            'id' => 256,
            'name' => 'Someone else',
            'age' => 33,
        ];
        $collection = new ArrayCollection($this->data);
        $collection->push($add);
        $data = $this->data;
        \array_push($data, $add);
        $this->assertSame($data, $collection->all());

        $add = [
            'id' => 256,
            'name' => 'Someone else',
            'age' => 33,
        ];
        $collection = new ArrayCollection($this->data, "id");
        $collection->push($add);
        $data = $this->data;
        \array_push($data, $add);
        $newData = [];
        foreach ($data as $item) {
            $newData[$item['id']] = $item;
        }
        $this->assertSame($newData, $collection->all());

        $this->expectException(InvalidTypeException::class);
        $collection = new ArrayCollection($this->data, "id");
        $collection->push(false);
    }

    public function testFirst()
    {
        $data = $this->data;
        $first = \array_shift($data);
        $collection = new ArrayCollection($this->data);
        $this->assertSame($first, $collection->first());

        $this->assertSame(
            [
                'id' => 5,
                'name' => 'Jon Snow',
                'age' => 14,
            ],
            $collection->first(function ($item) {
                if ($item['id'] === 5) {
                    return $item;
                }
            })
        );

        $this->assertNull($collection->first(function ($item) {
            if ($item['id'] === 21380) {
                return $item;
            }
        }));
    }

    public function testLast()
    {
        $data = $this->data;
        $last = \array_pop($data);
        $collection = new ArrayCollection($this->data);
        $this->assertSame($last, $collection->last());

        $this->assertSame(
            [
                'id' => 5,
                'name' => 'Jon Snow',
                'age' => 14,
            ],
            $collection->last(function ($item) {
                if ($item['id'] === 5) {
                    return $item;
                }
            })
        );

        $this->assertNull($collection->last(function ($item) {
            if ($item['id'] === 21380) {
                return $item;
            }
        }));
    }

    public function testShuffle()
    {
        $collection = new ArrayCollection($this->data);
        $collectionShuffle = $collection->shuffle();
        $this->assertSame($collection->count(), $collectionShuffle->count());

        $this->assertInstanceOf(ArrayCollection::class, $collectionShuffle);

        $collection1 = $collection->sort(function ($item1, $item2) {
            return $item1['id'] - $item2['id'];
        });

        $collection2 = $collectionShuffle->sort(function ($item1, $item2) {
            return $item1['id'] - $item2['id'];
        });
        $this->assertSame($collection1->all(), $collection2->all());


        $collection = new ArrayCollection($this->data, 'id');
        $collectionShuffle = $collection->shuffle();
        $this->assertSame($collection->count(), $collectionShuffle->count());

        $collection1 = $collection->sort(function ($item1, $item2) {
            return $item1['id'] - $item2['id'];
        });

        $collection2 = $collectionShuffle->sort(function ($item1, $item2) {
            return $item1['id'] - $item2['id'];
        });
        $this->assertSame($collection1->all(), $collection2->all());
    }

    public function testSlice()
    {
        $collection = new ArrayCollection($this->data);
        $this->assertSame(
            [
                [
                    'id' => 16,
                    'name' => 'Davos Seaworth Twin',
                    'age' => 37,
                ],
            ],
            $collection->slice(15)->all()
        );

        $this->assertInstanceOf(ArrayCollection::class, $collection->slice(15));

        $collection = new ArrayCollection($this->data, 'id');
        $this->assertSame(
            [
                16 => [
                    'id' => 16,
                    'name' => 'Davos Seaworth Twin',
                    'age' => 37,
                ],
            ],
            $collection->slice(15)->all()
        );


        $collection = new ArrayCollection($this->data);
        $this->assertSame(
            [
                [
                    'id' => 16,
                    'name' => 'Davos Seaworth Twin',
                    'age' => 37,
                ],
            ],
            $collection->slice(-1)->all()
        );

        $collection = new ArrayCollection($this->data, 'id');
        $this->assertSame(
            [
                16 => [
                    'id' => 16,
                    'name' => 'Davos Seaworth Twin',
                    'age' => 37,
                ],
            ],
            $collection->slice(-1)->all()
        );

        $collection = new ArrayCollection($this->data);
        $this->assertSame(
            [
                [
                    'id' => 2,
                    'name' => 'Catelyn Stark',
                    'age' => 33,
                ],
            ],
            $collection->slice(1, 1)->all()
        );

        $collection = new ArrayCollection($this->data, 'id');
        $this->assertSame(
            [
                2 => [
                    'id' => 2,
                    'name' => 'Catelyn Stark',
                    'age' => 33,
                ],
            ],
            $collection->slice(1, 1)->all()
        );

        $collection = new ArrayCollection($this->data);
        $this->assertSame(
            [
                [
                    'id' => 15,
                    'name' => 'Brandon Stark Twin',
                    'age' => 7,
                ],
            ],
            $collection->slice(14, -1)->all()
        );

        $collection = new ArrayCollection($this->data, 'id');
        $this->assertSame(
            [
                15 => [
                    'id' => 15,
                    'name' => 'Brandon Stark Twin',
                    'age' => 7,
                ],
            ],
            $collection->slice(14, -1)->all()
        );

        $collection = new ArrayCollection($this->data);
        $this->assertSame(
            [
                [
                    'id' => 15,
                    'name' => 'Brandon Stark Twin',
                    'age' => 7,
                ],
            ],
            $collection->slice(-2, -1)->all()
        );

        $collection = new ArrayCollection($this->data, 'id');
        $this->assertSame(
            [
                15 => [
                    'id' => 15,
                    'name' => 'Brandon Stark Twin',
                    'age' => 7,
                ],
            ],
            $collection->slice(-2, -1)->all()
        );
    }

    public function testIsEmpty()
    {
        $collection = new ArrayCollection($this->data);
        $this->assertFalse($collection->isEmpty());

        $collection = new ArrayCollection([]);
        $this->assertTrue($collection->isEmpty());
    }

    public function testCount()
    {
        $collection = new ArrayCollection($this->data);
        $this->assertSame(\count($this->data), $collection->count());

        $collection = new ArrayCollection([]);
        $this->assertSame(0, $collection->count());
    }

    public function testReverse()
    {
        $collection = new ArrayCollection($this->data);
        $this->assertSame(\array_reverse($this->data), $collection->reverse()->all());

        $this->assertInstanceOf(ArrayCollection::class, $collection->reverse());

        $data = [];
        foreach ($this->data as $array) {
            $data[$array['id']] = $array;
        }
        $collection = new ArrayCollection($this->data, 'id');
        $this->assertSame(\array_reverse($data, true), $collection->reverse()->all());
    }

    public function testGetIterator()
    {
        $collection = new ArrayCollection($this->data);
        $this->assertInstanceOf(\ArrayIterator::class, $collection->getIterator());

        $this->assertSame(\count($this->data), $collection->getIterator()->count());
    }
}
