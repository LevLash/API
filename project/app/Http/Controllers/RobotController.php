<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFeedbackRequest;
use App\Http\Requests\TurnOnRequest;
use App\Services\RobotsService;
use Illuminate\Http\JsonResponse;

class RobotController extends Controller
{
    private $service;

    public function __construct(RobotsService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function getRobotsList()
    {
        return response()->json($this->service->getRobots());
    }

    /**
     * @return JsonResponse
     */
    public function createRobot()
    {
        $robot = $this->service->createRobot();
        return response()->json($robot);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getRobotStatus($id)
    {
        return response()->json($this->service->getRobotStatus($id));
    }

    /**
     * @param  TurnOnRequest  $request
     * @param $id
     * @return JsonResponse
     */
    public function turnOn(TurnOnRequest $request, $id)
    {
        return response()->json($this->service->turnOnRobot($id, $request->get('task')));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function turnOff($id)
    {
        return response()->json($this->service->turnOffRobot($id));
    }

    /**
     * @param  AddFeedbackRequest  $request
     * @return JsonResponse
     */
    public function addFeedback(AddFeedbackRequest $request)
    {
        return response()->json($this->service->addFeedback(
            $request->get('id'),
            $request->get('feedback'),
            $request->get('rating')
        ));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getFeedbacks($id)
    {
        return response()->json($this->service->getFeedbacks($id));
    }
}
