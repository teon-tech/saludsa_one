<?php

namespace App\Processes;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Response;
use App\Validators\EmailValidator;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\View;

class EmailProcess
{

    /**
     * @var EmailValidator
     */
    private $emailValidator;

    /**
     * @var SaleRepository
     */
    private $saleRepository;

    public function __construct(EmailValidator $emailValidator, SaleRepository $saleRepository)
    {
        $this->emailValidator = $emailValidator;
        $this->saleRepository = $saleRepository;
    }


    public function sendEmailTermsAndConditions(Request $request)
    {
        $inputData = $request->all();
        $this->emailValidator->sendEmailTermsAndConditions($inputData);
        $ip = $request->ip();
        $currentDate = date("Y/m/d H:i:s");
        return Response::json([
            'ip' => $request->ip(),
            'currentDate' => $currentDate,
            'first_name' => $inputData['first_name'],
            'last_name' => $inputData['last_name'],
        ]);
    }

    public function sendEmailSummaryPurchase(Request $request)
    {
        $inputData = $request->all();
        $this->emailValidator->sendEmailSummaryPurchase($inputData);
        $saleId = $inputData['saleId'];
        $sale = $this->saleRepository->view($saleId);
        $currentDate = date("Y/m/d H:i:s");
        $this->renderPlansCard($sale);
        $this->renderSubscriptionPdf($sale);
        $this->renderIndividualContractPdf($sale);
        return Response::json([
            'ip' => $request->ip(),
            'currentDate' => $currentDate,
            'saleId' => $saleId,
            'sale' => $sale
        ]);
    }

    public function renderPlansCard(Sale $sale)
    {
        // 15KEJ
        $image15KEJ = imagecreatefrompng(public_path() . "/templates-pdf/15KEJ.png");
        $font = public_path() . '/fonts/fssilassansweb-regular-webfont.ttf';
        $color = imagecolorallocate($image15KEJ, 61, 63, 61);
        imagettftext($image15KEJ, 12, 0, 110, 257, $color, $font, $sale->contract_number);
        imagettftext($image15KEJ, 12, 0, 80, 273, $color, $font, $sale->details[0]->planPrice->plan->name);
        imagettftext($image15KEJ, 12, 0, 70, 289, $color, $font, '#Ref1234');
        imagepng($image15KEJ, public_path() . '/emails-pdf/plan15K_' . $sale->id . '.png');
        imagedestroy($image15KEJ);

        // 30KEJ
        $image30KEJ = imagecreatefrompng(public_path() . "/templates-pdf/30KEJ.png");
        $font = public_path() . '/fonts/fssilassansweb-regular-webfont.ttf';
        $color = imagecolorallocate($image30KEJ, 61, 63, 61);
        imagettftext($image30KEJ, 12, 0, 110, 257, $color, $font, $sale->contract_number);
        imagettftext($image30KEJ, 12, 0, 75, 273, $color, $font, $sale->details[0]->planPrice->plan->name);
        imagettftext($image30KEJ, 12, 0, 70, 289, $color, $font, '#Ref1234');
        imagepng($image30KEJ, public_path() . '/emails-pdf/plan30K_' . $sale->id . '.png');
        imagedestroy($image30KEJ);

        // 100KEJ
        $image100KEJ = imagecreatefrompng(public_path() . "/templates-pdf/100KEJ.png");
        $font = public_path() . '/fonts/fssilassansweb-regular-webfont.ttf';
        $color = imagecolorallocate($image100KEJ, 61, 63, 61);
        imagettftext($image100KEJ, 12, 0, 98, 261, $color, $font, $sale->contract_number);
        imagettftext($image100KEJ, 12, 0, 65, 277, $color, $font, $sale->details[0]->planPrice->plan->name);
        imagettftext($image100KEJ, 12, 0, 60, 293, $color, $font, '#Ref1234');
        imagepng($image100KEJ, public_path() . '/emails-pdf/plan100K_' . $sale->id . '.png');
        imagedestroy($image100KEJ);
    }

    public function renderSubscriptionPdf(Sale $sale)
    {
        $stylesheet = file_get_contents('css/subcription_email.css');
        $view_page_1 = View::make('emails.subscription_page_1', [
            'sale' => $sale,
        ])->render();
        $view_page_2 = View::make('emails.subscription_page_2', [
            'sale' => $sale,
        ])->render();
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [285, 210],
            'orientation' => 'L',
        ]);
        $mpdf->SetSourceFile('templates-pdf/Suscripcion.pdf');
        $tplId = $mpdf->ImportPage(1);
        $mpdf->UseTemplate($tplId);
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($view_page_1, 2);
        $mpdf->AddPage();
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($view_page_2, 2);
        $mpdf->Output('emails-pdf/suscripcion_' . $sale->id . '.pdf', "F");
    }

    public function renderIndividualContractPdf(Sale $sale)
    {
        $html = '
        <div style="width:100%; height:100%; padding: 10px 10px;">
            <div style="text-align:center; width:50%;padding-top: 68px;">
                <p style="line-height:0.1; font-family: fssilassansweb; ">' . $sale->customerData->name . '</p>
                <p style="line-height:0.1; font-family: fssilassansweb;">' . $sale->customerData->father_last_name . ' ' . $sale->customerData->mother_last_name . '</p>
          </div>
        </div>';
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [325, 236],
            'orientation' => 'L',
            'fontDir' => array_merge($fontDirs, [
                public_path() . '/fonts',
            ]),
            'fontdata' => $fontData + [
                    'fssilassansweb' => [
                        'R' => 'fssilassansweb-regular-webfont.ttf',
                        'B' => 'fssilassansweb-bold-webfont.ttf',
                    ]
                ],
            'default_font' => 'fssilassansweb'
        ]);
        $pagecount = $mpdf->SetSourceFile('templates-pdf/Contrato_Individual.pdf');
        // Import the last page of the source PDF file
        for ($i = 1; $i <= $pagecount; $i++) {
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);
            if ($i === 14) {
                $mpdf->WriteHTML($html);
            }
            if ($i < $pagecount) {
                $mpdf->WriteHTML('<pagebreak />');
            }
        }
        $mpdf->Output('emails-pdf/contrato_individual_' . $sale->id . '.pdf', "F");
    }

}
