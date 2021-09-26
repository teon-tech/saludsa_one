<?php
namespace App\Transformers;

use App\Repositories\SaleServiceLogRepository;
use Carbon\Carbon;

class SaleServiceTransformer
{

    public static function paymentHeaderEntry($sale): array
    {
        return [
            "Numero" => 0,
            "SucursalNombre" => config('services.salesService.succursaleName'),
            "TipoPago" => "TARJETA",
            "TipoDocumento" => "F",
            "ValorAplicaIva" => 4,
            "FechaCaja" => Carbon::now()->toDateString(),
            "Moneda" => "DOLAR",
            "CuotasAplicadas" => 1,
            "ValorAplicado" => $sale->total,
            "ValorRecibido" => $sale->total,
            "ContratoNumero" => $sale->contract_number,
            "ValorNoAplicaIva" => 0,
            "LugarPago" => config('services.salesService.placePayment'),
            "EstadoPago" => 24,
            "CodigoProducto" => $sale->details[0]->planPrice->plan->code,
            "Region" => "sierra",
            "FechaCreacion" => Carbon::now()->toDateString(),
            "HoraCreacion" => Carbon::now()->format('H:i'),
            "UsuarioCreacion" => config('services.salesService.userCreation'),
            "ProgramaCreacion" => config('services.salesService.programCreation'),
            "EsPrimeraCuota" => true,
            "BancoCaja" => "0",
            "TarjetaCaja" => "0",
            "NumeroCuentaCaja" => "0",
        ];

    }

    public static function createPaymentDetail($sale): array
    {
        $paymentNumber = SaleServiceLogRepository::getPaymentNumber($sale);
        return [
            "NumeroPago" => $paymentNumber,
            "NumeroLineaPago" => 1,
            "CodigoProducto" => $sale->details[0]->planPrice->plan->code,
            "ContratoNumero" => $sale->contract_number,
            "NumeroCuota" => 1,
            "Region" => "sierra",
            "EstatusDetallePago" => 24,
            "TotalCuota" => $sale->total,
            "ValorRemitido" => $sale->total,
            "FechaCaja" => Carbon::now()->toDateString(),
            "FechaCorte" => Carbon::now()->toDateString(),
            "HoraCreacion" => Carbon::now()->format('H:i'),
            "UsuarioCreacion" => config('services.salesService.userCreation'),
            "ProgramaCreacion" => config('services.salesService.programCreation'),
            "FechaCreacion" => Carbon::now()->toDateString(),
            "EsPrimeraCuota" => true,
        ];

    }

    public static function createPaymentMethod($sale): array
    {
        $bank = $sale->subscription[0]->bank_info['bank'];
        $paymentNumber = SaleServiceLogRepository::getPaymentNumber($sale);
        return [
            [
                "Numero" => $paymentNumber,
                "Linea" => 1,
                "ValorRecibido" => $sale->total,
                "Moneda" => "DOLAR",
                "DocumentoCaja" => "CODE", //$input['event']['transactionReference'],
                "TarjetaCaja" => "CODE", //$input['event']['transactionReference'],
                "FechaCreacion" => Carbon::now()->toDateString(),
                "HoraCreacion" => Carbon::now()->format('H:i'),
                "UsuarioCreacion" => config('services.salesService.userCreation'),
                "ProgramaCreacion" => config('services.salesService.programCreation'),
                "TarjetaCodigoBanco" => self::banksCatalog($bank),
                "TipoPago" => " TARJETA",
                "FechaCaja" => Carbon::now()->toDateString(),
                "LugarPago" => config('services.salesService.placePayment'),
                "Estado" => 24,
                "EsPrimeraCuota" => true,
                "BancoCaja" => self::banksCatalog($bank),
                "FormaTipoPago" => "19",
                "NumeroAutorizacion" => " ",
                "RucPrestador" => " ",
                "CodigoBotonPago" => "CODE", //,$input['event']['transactionReference'],
            ],
        ];

    }

    public static function banksCatalog($bank)
    {
        switch ($bank) {
            case (strpos($bank, "AUSTRO") !== false):
                return 15;
                break;
            case (strpos($bank, "BOLIVARIANO") !== false):
                return 27;
                break;
            case (strpos($bank, "GUAYAQUIL") !== false):
                return 10;
                break;
            case (strpos($bank, "INTERNACIONAL") !== false):
                return 16;
                break;
            case (strpos($bank, "MACHALA") !== false):
                return 20;
                break;
            case (strpos($bank, "MUTUALISTA PICHINCHA") !== false):
                return 18;
                break;
            case (strpos($bank, "PICHINCHA") !== false):
                return 4;
                break;
            case (strpos($bank, "PRODUCCION") !== false):
                return 5;
                break;
            case (strpos($bank, "RUMIÑAHUI") !== false):
                return 55;
                break;
            default:
                return 34;
                break;
        }

    }
}
