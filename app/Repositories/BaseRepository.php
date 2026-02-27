<?php

namespace App\Repositories;

abstract class BaseRepository implements RepositoryInterface{

// khởi tạo model
 protected $model;
    public function __construct()
    {
        $this->setModel();
    }
    public function setModel(){
       $this->model= app()->make($this->getModel());
    }
     abstract public function getModel();
    public function all(){
        return $this->model->all();
    }
     public function find($id){
        return $this->model->find($id);
    }
     public function create($arrtibutes=[]){
        return $this->model->create($arrtibutes);
    }
    public function update($id,$arrtibutes=[]){
        $result=$this->model->find($id);
        if($result){
            return $this->model->where('id',$id)->update($arrtibutes);
        }
        return false;
    }
     public function delete($id){
        if($this->model->find($id)){
            return $this->model->where('id',$id)->delete();
        }else{
            return false;
        }
     }
}
