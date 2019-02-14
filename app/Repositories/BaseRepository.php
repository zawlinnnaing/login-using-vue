<?php
/**
 * Created by PhpStorm.
 * User: zhiyu1205
 * Date: 2019-02-11
 * Time: 13:03
 */

namespace App\Repositories;


use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }
}