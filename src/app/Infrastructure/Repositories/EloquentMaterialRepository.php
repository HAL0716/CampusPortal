<?php

namespace App\Infrastructure\Repositories;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Material\Entities\Material;
use App\Domain\Material\Exceptions\MaterialNotFoundException;
use App\Domain\Material\Repositories\MaterialRepository;
use App\Domain\Material\ValueObjects\MaterialId;
use App\Models\Material as MaterialModel;

final class EloquentMaterialRepository implements MaterialRepository
{
    public function save(Material $material): Material
    {
        $model = new MaterialModel;

        if ($material->id() !== null) {
            $model = MaterialModel::find($material->requireId()->value());

            if ($model === null) {
                throw new MaterialNotFoundException;
            }
        }

        $model->course_offering_id = $material->courseOfferingId()->value();
        $model->title = $material->title();
        $model->description = $material->description();
        $model->file_path = $material->filePath();
        $model->publish_date = $material->publishDate();

        $model->save();

        return $this->toEntity($model);
    }

    private function toEntity(MaterialModel $model): Material
    {
        return Material::reconstruct(
            id: new MaterialId($model->id),
            courseOfferingId: new CourseOfferingId($model->course_offering_id),
            title: $model->title,
            description: $model->description,
            filePath: $model->file_path,
            publishDate: $model->publish_date,
        );
    }
}
