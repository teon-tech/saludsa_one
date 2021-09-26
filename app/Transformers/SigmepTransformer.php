<?php
namespace App\Transformers;

use Carbon\Carbon;

class SigmepTransformer
{

    public static function buildStructure($sale): array
    {
        $customer = $sale->customerData;
        $details = $sale->details[0];
        $billingData = $sale->billingData;
        $subscription = $sale->subscription[0];

        return ['saludsaSales' => [
                'datosSaludsaS' => [
                "contratoNumero" => $sale->contract_number,
                "personaTipoDocumentoSs" => $customer->document_type === 'PASSPORT' ? 'PS' : 'CI',
                "personaNumeroDocumentoSs" => $customer->document,
                "personaNombresSs" => $customer->name,
                "personaApellidoPaternoSs" => $customer->father_last_name,
                "personaApellidoMaternoSs" => $customer->mother_last_name,
                "personaFechaNacimientoSs" => $customer->birth_date,
                "personaGeneroSs" => $customer->gender === 'FEMALE' ? false : true,
                "personaRelacionSs" => "1",
                "contratoTitularConBeneficiosSs" => "SI",
                "personaEstadoCivilSs" => $customer->civil_status,
                "personaCelularSs" => $customer->phone,
                "personaTrabajoEmailSs" => $customer->email,
                "personaEmailSs" => $customer->email,
                "personaProvinciaSs" => $sale->province->external_code,
                "personaCiudadSs" => 1, //Verificar datos
                "personaCallePrincipalSs" => $customer->direction,
                "personaCalleTransversalSs" => "",
                "personaNumeroDomicilioSs" => "",
                "personaDomicilioReferenciaSs" => "",
                "personaDomicilioBarrioSs" => "",
                "contratoBancoSs" => 4, //Verificar
                "contratoTipoPagoSs" => 3,
                "contratoNumeroTarjetaSs" => $subscription->number_card,
                "contratFechaExpiracionTarjetaSs" => Carbon::now()->addYear()->toDateString(),
                "contratoCodigoPlanSs" => $details->planPrice->plan->code,
                "contratoRegionSs" => "SIERRA",
                "contratoFechaVentaSs" => $sale->created_at->format('Y-m-d'),
                "contratoCodigoVendedorSs" => "43210",
                "contratoServiciosAdicionalesSs" => "",
                "contratoPrimeraCuotaSs" => $details->subscription_type === 'MONTHLY' ? $details->monthly_price : $details->annual_price,
                "contratoTiempoCuotaDescuentoSs" => 0,
                "contratoPorcentajeDescuentoSs" => 0,
                "contratoValorDescuentoSs" => 0,
                "contratoCuotaSs" => $details->monthly_price,
                "contratoValorAnualSs" => $details->annual_price,
                "contratoPeriodoPagoSs" => $details->subscription_type === 'MONTHLY' ? 1 : 5,
                "contratoMaternidadSs" => $customer->gender === 'FEMALE' ? "SI" : "NO",
                "contratoEstadoSs" => "ACTIVO",
                "contratoFacturarASs" => $billingData->name . " " . $billingData->last_name,
                "contratofacturarANumeroDocumentoSs" => $billingData->document,
                "contratoFacturarATipoDocumentoSs" => $billingData->document_type === 'PASSPORT' ? 'PS' : 'CI',
                "contratoBancoPiSs" => "",
                "contratoTipoCuentaPiSs" => 3,
                "contratoNumeroCuentaPiSs" => $subscription->number_card,
                "contratoCuponSs" => "NO APLICA",
                "usuarioModificacion" => "FORMULARIO WEB",
                "contratoNombrePagador" => $customer->name . " " . $customer->father_last_name . " " . $customer->mother_last_name,
                "contratoTipoDocumentoPagador" => $customer->document_type === 'PASSPORT' ? 'PS' : 'CI',
                "contratoNumeroDocumentoPagador" => $customer->document,
                "beneficiarioTitular" => true,
                "contratoFechaVigencia" => $sale->created_at->format('Y-m-d'),
                "contratoObservaciones" => "",
                "codigoVPMS" => 0,
                "membershipID" => 0,
                "partyID" => 0,
                "codigoRegistro" => 0,
                "codigoPersona" => 0,
                "codigoTelefono" => 0,
                "codigoDireccion" => 0,
                "codigoDireccionElectronica" => 0,
                "codigoProductoPersona" => 0,
                "idTipoProductoVitality" => 0,
                "idTipoMembresiaVitality" => 0,
            ]
        ]
        ];
    }
}
