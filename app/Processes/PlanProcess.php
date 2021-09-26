<?php

namespace App\Processes;

use App\Http\Resources\BasicPlanResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\PlanResource;
use App\Http\Resources\PlanWithPriceResource;
use App\Models\Coverage;
use App\Models\Message;
use App\Models\TypeCoverage;
use App\Models\TypePlan;
use App\Repositories\PlanRepository;
use App\Validators\PlanValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlanProcess
{
    /**
     * @var PlanRepository
     */
    private $planRepository;
    /**
     * @var PlanValidator
     */
    private $planValidator;

    /**
     * ProductProcess constructor.
     * @param PlanRepository $planRepository
     * @param PlanValidator $planValidator
     */
    public function __construct(PlanRepository $planRepository, PlanValidator $planValidator)
    {
        $this->planRepository = $planRepository;
        $this->planValidator = $planValidator;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comparativePlan(Request $request)
    {
        $planIds = explode(',', $request->get('planIds'));

        // Validation
        $this->planValidator->comparativePlan($planIds);
        $plans = [];
        foreach ($planIds as $planId) {
            $plans[] = $this->planRepository->findBy($planId, 'id');
        }
        $currentPlan = $plans[0];
        unset($plans[0]);

        $samePlans = [];
        $samePlans[] = $currentPlan;
        $otherPlans = [];

        foreach ($plans as $plan) {
            if ($plan->type_plan_id == $currentPlan->type_plan_id) {
                $samePlans[] = $plan;
            } else {
                $otherPlans[] = $plan;
            }
        }
        $auxCoverages = collect([]);
        $typesCoverage = [];
        foreach ($samePlans as $samePlan) {
            $coverages = Coverage::query()
                ->join('type_coverage', 'coverage.type_coverage_id', '=', 'type_coverage.id')
                ->where('plan_id', $samePlan->id)
                ->orderby('weight', 'asc')
                ->get();
            $auxCoverages = $coverages->merge($auxCoverages);
            $typesCoverage = $auxCoverages->unique('type_coverage_id');
        }
        $plans = [];
        foreach ($samePlans as $samePlan) {
            $coveragesResponse = [];
            foreach ($typesCoverage as $typeCoverage) {
                $coverageType = TypeCoverage::query()
                    ->select(['id', 'name'])
                    ->find($typeCoverage->type_coverage_id);

                $coverages = Coverage::query()
                    ->select(['coverage.id as id', 'coverage.name as name', 'coverage.description as description'])
                    ->join('plan', 'coverage.plan_id', '=', 'plan.id')
                    ->join('type_coverage', 'coverage.type_coverage_id', '=', 'type_coverage.id')
                    ->where('type_coverage.id', $typeCoverage->type_coverage_id)
                    ->where('plan.id', $samePlan->id)
                    ->first();
                $coverageInfo = null;
                if ($coverages !== null) {
                    $coverages->with(['images']);
                    $coverageInfo = [
                        'id' => $coverages->id,
                        'name' => $coverages->name,
                        'description' => $coverages->description,
                        'images' => ImageResource::collection($coverages->images),
                    ];
                } else {
                    $coverageInfo = null;
                }

                $coveragesResponse[] = [
                    'type' => $coverageType,
                    'data' => $coverageInfo,
                ];

            }

            $typeSame = TypePlan::query()
                ->select(['id', 'name'])
                ->find($samePlan->type_plan_id);
            $coveragesResponse[] = [
                'type' => 'url',
                'data' => 'Agregar data',
            ];
            $plans[] = [
                'id' => $samePlan->id,
                'name' => $samePlan->name,
                'subtitle' => $samePlan->subtitle,
                'default' => $samePlan->default == 'YES',
                'type' => $typeSame,
                'coverages' => $coveragesResponse,
            ];

        }
        foreach ($otherPlans as $otherPlan) {
            $typeOther = TypePlan::query()
                ->select(['id', 'name'])
                ->find($otherPlan->type_plan_id);
            $message = new Message();
            $plans[] = [
                'id' => $otherPlan->id,
                'name' => $otherPlan->name,
                'subtitle' => $otherPlan->subtitle,
                'default' => $otherPlan->default == 'YES',
                'type' => $typeOther,
                'coverages' => [
                    'type' => 'url',
                    'data' => '',
                ],
                "error" => $message->getMessage($typeSame->id, $typeOther->id),
            ];
        }
        $plansOrder = [];
        for ($i = 0; $i < count($planIds); $i++) {
            foreach ($plans as $plan) {
                if ($planIds[$i] == $plan['id']) {
                    $plansOrder[] = $plan;
                }
            }
        }

        return response()->json($plansOrder);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function findPlans(Request $request)
    {
        $input = $request->all();
        $plans = $this->planRepository->findAll($input);

        BasicPlanResource::withoutWrapping();

        return BasicPlanResource::collection($plans);
    }

    public function findById($id)
    {

        $this->planValidator->findById($id);

        $plan = $this->planRepository->findBy($id, 'id');
        if ($plan == null) {
            $plan = $this->planRepository->findBy($id, 'code');
        }

        PlanResource::withoutWrapping();

        return new PlanResource($plan);

    }

    /**
     * @param $code
     * @return PlanResource
     */
    public function findByCode($code)
    {
        $this->planValidator->findByCode($code);

        $plan = $this->planRepository->findBy($code, 'code');

        PlanResource::withoutWrapping();
        return new PlanResource($plan);
    }

    public function getPlanWithPrice(Request $request)
    {
        $input = $request->all();
        $this->planValidator->getPlanWithPrice($input);
        $pricePlans = $this->planRepository->getPlanWithPrice($input);

        PlanWithPriceResource::withoutWrapping();

        return PlanWithPriceResource::collection($pricePlans);
    }
}
