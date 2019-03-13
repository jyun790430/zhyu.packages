<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:32
 */

namespace Zhyu\Controller;

use Illuminate\Pagination\Paginator;
use Zhyu\Datatables\DatatablesFactoryApp;
use Zhyu\Controller\Controller as ZhyuController;

abstract class CrudController extends ZhyuController
{

    protected $repository;

    public function __construct()
    {
        $this->middleware(['web', 'auth', 'checklogin']);

        $this->makeRepository();
    }

    abstract public function repository();

    abstract public function rules();

    private function makeRepository(){
        $repository = app()->make($this->repository());

        return $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = $this->repository->makeModel();
        $datatablesService = DatatablesFactoryApp::bind($this->table ? $this->table : $model->getTable());
        return $this->view('index', $model, ['datatablesService' => $datatablesService]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return parent::view(null, $this->repository->makeModel());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $rules = method_exists($this, 'rules_edit') ? $this->rules_create() : $this->rules();
        $this->validate(request(), $rules);

        return response()->json([], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Model  $logistic
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $title = null)
    {

        $model = $this->repository->find($id);
        return parent::view(null, $model, ['title' => $title]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Model  $logistic
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $model = $this->repository->find($id);

        $rules = method_exists($this, 'rules_edit') ? $this->rules_edit() : $this->rules();

        $this->validate(request(), $rules);
        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}