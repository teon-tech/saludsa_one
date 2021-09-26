<?php

namespace App\Processes;

use App\Components\Util;
use App\Http\Resources\SaleResource;
use App\Models\Config;
use App\Repositories\SaleRepository;
use App\Repositories\SaleServiceLogRepository;
use App\Services\SaleService;
use App\Validators\SaleValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\SigmepService;
use Illuminate\Support\Facades\Log;

class SaleProcess
{
    /**
     * @var SaleRepository
     */
    private $saleRepository;
    /**
     * @var SigmepService
     */
    private $sigmepService;

    /**
     * @var SaleValidator
     */
    private $saleValidator;

    /**
     * @var SaleService
     */
    private $saleService;

    /**
     * @var SaleServiceLogRepository
     */
    private $saleServiceLogRepository;

    /**
     *
     * @param SaleRepository $saleRepository
     */
    public function __construct(
        SaleRepository $saleRepository,
        SaleValidator $saleValidator,
        SaleService $saleService,
        SaleServiceLogRepository $saleServiceLogRepository,
        SigmepService $sigmepService)
    {
        $this->saleRepository = $saleRepository;
        $this->saleValidator = $saleValidator;
        $this->saleService = $saleService;
        $this->saleServiceLogRepository = $saleServiceLogRepository;
        $this->sigmepService = $sigmepService;
    }

    /**
     * @param Request $request
     * @return SaleResource
     * @throws \Exception
     */
    public function saveSale(Request $request)
    {
        $input = $request->all();
        $this->saleValidator->saveSale($input);
        $sale = $this->saleRepository->saveSale($input);
        SaleResource::withoutWrapping();
        return new SaleResource($sale);
    }

    public function view($saleId)
    {
        $sale = $this->saleRepository->view($saleId);
        SaleResource::withoutWrapping();
        return new SaleResource($sale);
    }

    public function generateContract($sale)
    {
        $sale->contract_number = Util::generateNumberContract();
        $sale->save();
    }

    public function serviceSigmep($sale, $idService = null)
    {
        $response = $this->sigmepService->createContract($sale);
        $this->saleServiceLogRepository->saveSigmep($response, $sale, $idService);
    }

    public function paymentHeaderEntry($sale, $idService = null)
    {
        $paymentHeaderEntry = $this->saleService->paymentHeaderEntry($sale);
        Log::info('PaymentHeaderEntry', $paymentHeaderEntry);
        $status = $paymentHeaderEntry['data']['Estado'];
        if ($status === 'OK') {
            $paymentNumber = $paymentHeaderEntry['data']['Datos']['Numero'];
            $service = 'paymentHeaderEntry';
            $this->saleServiceLogRepository->save($paymentHeaderEntry, $sale, $service, $idService);
            return $paymentNumber;
        } else {
            $service = 'paymentHeaderEntry';
            $this->saleServiceLogRepository->saveError($paymentHeaderEntry, $sale, $service, $idService);
            return null;
        }
    }

    public function createPaymentDetail($sale, $idService = null)
    {
        $createPaymentDetail = $this->saleService->createPaymentDetail($sale);
        Log::info('createPaymentDetail', $createPaymentDetail);
        $status = $createPaymentDetail['data']['Estado'];
        if ($status === 'OK') {
            $service = 'createPaymentDetail';
            $this->saleServiceLogRepository->save($createPaymentDetail, $sale, $service, $idService);
            return true;
        } else {
            $service = 'createPaymentDetail';
            $this->saleServiceLogRepository->saveError($createPaymentDetail, $sale, $service, $idService);
            return false;
        }
    }

    public function createPaymentMethod($sale, $idService = null)
    {
        $createPaymentMethod = $this->saleService->createPaymentMethod($sale);
        Log::info('createPaymentMethod', $createPaymentMethod);
        $status = $createPaymentMethod['data']['Estado'];
        if ($status === 'OK') {
            $service = 'createPaymentMethod';
            $this->saleServiceLogRepository->save($createPaymentMethod, $sale, $service, $idService);
            return true;
        } else {
            $service = 'createPaymentMethod';
            $this->saleServiceLogRepository->saveError($createPaymentMethod, $sale, $service, $idService);
            return false;
        }
    }

    public function salesServiceMethod($sale)
    {
        $paymentNumber = $this->paymentHeaderEntry($sale);
        if ($paymentNumber != null) {
            $createPaymentDetail = $this->createPaymentDetail($sale);
            if ($createPaymentDetail) {
                $this->createPaymentMethod($sale);
                $sale->is_contract_created = true;
            }
        }
    }

    public function salesServiceRetryMethod($sale, $idService = null)
    {
        $paymentNumber = $this->paymentHeaderEntry($sale, $idService);
        if ($paymentNumber != null) {
            $createPaymentDetail = $this->createPaymentDetail($sale, $id = null);
            if ($createPaymentDetail) {
                $this->createPaymentMethod($sale, $id = null);
                $sale->is_contract_created = true;
                $sale->save();
            }
        }
    }
}
