<?php

use Illuminate\Database\Seeder;
use Someline\Models\Category\SomelineCategory;
use Someline\Models\Foundation\User;

class SomelineCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('someline_categories')->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            SomelineCategory::TYPE_ALBUM => [
                '男频' => [
                    '都市', '玄幻', '奇幻', '仙侠', '校园',
                ],
                '女频' => [
                    '现代言情', '古代言情', '幻想言情', '青春校园',
                ],
                '出版' => [
                    '小说', '文学', '传记', '历史',
                ],
            ]
        ];

        foreach ($data as $type => $type_data) {
            array_walk($type_data, function ($value, $key, $type) {
                $this->createCategory($type, $value, $key);
            }, $type);
        }
    }

    public function createCategory($type, $value, $key, $category = false)
    {
        if (is_string($key) && is_array($value)) {
            $data = [
                'type' => $type,
                'category_name' => $key
            ];
            $category = $this->viaParentCategoryOrDirectly($data, $category);
            foreach ($value as $value_key => $value_value) {
                $this->createCategory($type, $value_value, $value_key, $category);
            }
            return;
        }
        if (is_integer($key) && is_string($value)) {
            $data = [
                'type' => $type,
                'category_name' => $value
            ];
            $category = $this->viaParentCategoryOrDirectly($data, $category);
            return;
        }
    }

    /**
     * @param $data
     * @param bool|SomelineCategory $category
     * @return SomelineCategory
     */
    public function viaParentCategoryOrDirectly($data, $category = false)
    {
        if ($category) {
            return $category->children_categories()->create($data);
        } else {
            return SomelineCategory::create($data);
        }
    }
}
