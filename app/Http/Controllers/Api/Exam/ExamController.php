<?php
namespace App\Http\Controllers\Api\Exam;



use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Exam\ExamInterface;
use App\Http\Requests\Api\Exam\ExamRequest;


class ExamController extends Controller
{
    public ExamInterface $interface;
    public function __construct(ExamInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
{
        return $this->interface->index($request);
    }
    public function store(ExamRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,ExamRequest $request,Exam $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,Exam $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,Exam $model)
    {
        return $this->interface->show($local,$model);
    }
    public function showDeleted()
    {
        return $this->interface->showDeleted();
    }
    public function restore($local,$id)
    {
        return $this->interface->restore($local,$id);
    }
    public function forceDelete($local,$id)
    {
        return $this->interface->forceDelete($local,$id);
    }
    public function multi_actions($local,Request $request)
    {
        return $this->interface->multi_actions($local,$request);
    }
}
