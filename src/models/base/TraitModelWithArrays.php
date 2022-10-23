<?php
namespace app\models\base;

// ожидает наличие в классе такого массива:
//    protected const ARRAYS = [
//        [
//            'baseModel' => Category::class,
//            'mtomModel' => ListCategories::class,
//            'fieldName' => 'categories'
//        ],
//        [
//            'baseModel' => Gift::class,
//            'mtomModel' =>  ListGifts::class,
//            'fieldName' => 'gifts'
//        ]
//    ];

trait TraitModelWithArrays {

    public static function findByValue($key, $value): array
    {
        $items = parent::findByValue($key, $value);

        foreach ($items as $item) {
            foreach (self::ARRAYS as $arr) {
                $item->{$arr['fieldName']} = $arr['mtomModel']::findByOwner($item->id);
            }
        }
        return $items;
    }


    public function insertLinkedItems($items, $itemsClass, $tableMtoM) {
        $values = [];
        if ($items && is_array($items)) {
            $ids = array_filter($items, 'is_int');

            $values = array_map(
                fn ($id) => $itemsClass::findById($id),
                $ids
            );

            $values = array_values(
                array_filter(
                    $values,
                    fn ($value) => $value && ($value->userId == $this->userId)
                )
            );

            $tableMtoM::insertObjects($this->id, $values);
        }
        return $values;
    }


    public function insert(): void
    {
        parent::insert();

        foreach (self::ARRAYS as $arr) {
            $res = [];
            if (isset($this->{$arr['fieldName']})) {
                $res = $this->insertLinkedItems(
                    $this->{$arr['fieldName']},
                    $arr['baseModel'],
                    $arr['mtomModel']
                );
            }

            $this->{$arr['fieldName']} = $res;
        }
    }

    public function update(): ?object
    {
        $updated = parent::update();

        foreach (self::ARRAYS as $arr) {
            $arr['mtomModel']::delete($this->id);
            $res = [];
            if (isset($this->{$arr['fieldName']})) {
                $res = $this->insertLinkedItems(
                    $this->{$arr['fieldName']},
                    $arr['baseModel'],
                    $arr['mtomModel']
                );
            }

            $updated->{$arr['fieldName']} = $res;
        }
        return $updated;
    }
}