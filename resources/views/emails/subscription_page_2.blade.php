<div style="width:100%; height:100%; padding: 5px 0px;">
            <div>
                <p class="title-14">Beneficiarios</p>
                <table style="margin-top:60px;" class="beneficiaryTable">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Nombres</th>
                                <th>Sexo</th>
                                <th>Parentesco Código</th>
                                <th>Fecha de nacimiento</th>
                                <th>Cuota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>inn_numerodocumentob</td>
                                <td>inn_primerapellido</td>
                                <td>inn_segundoapellido</td>
                                <td>inn_primernombre inn_segundonombre</td>
                                <td>inn_generohombre</td>
                                <td>inn_parentesconombre</td>
                                <td>inn_fechanacimiento</td>
                                <td>inn_montocotizado</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" style="background: #FFFFFF !important; border-top: 1px solid #FFFFFF !important;">&nbsp;</td>
                                <td style="border: 1px solid #000000;">1. PRECIO COTIZADO</td>
                                <td style="border: 1px solid #000000;">inn_preciostotalcotizados</td>
                            </tr>
                        </tfoot>
                </table>
                <p class="title-10">Parentesco: 1. Cónyuge  Conviviente *2. Hijos  *3. Padres.</p>
                <p class="title-10">Otros (hasta el cuarto grado de consanguinidad o segundo de afinidad.</p>
            </div>
            <div>
                <p class="title-14">Condiciones Especiales</p>
                <table style="margin-top:60px;" class="beneficiaryTable">
                        <thead>
                            <tr>
                                <th>Nro. Documento</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Servicio Adicional*</th>
                                <th>Categoría Servicio</th>
                                <th>Costo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>inn_numerodocumento</td>
                                <td>inn_primernombre  inn_segundonombre</td>
                                <td>inn_primerapellido  inn_segundoapellido</td>
                                <td>inn_nombreservicioadicional</td>
                                <td>inn_nombreparametroservicioadicional</td>
                                <td>inn_costoservicio</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="background: #FFFFFF !important; border-top: 1px solid #FFFFFF !important;">&nbsp;</td>
                                <td style="border: 1px solid #000000;">2. TOTAL CONDICIONES ESPECIALES</td>
                                <td style="border: 1px solid #000000;">inn_preciostotalcondicionesespeciales</td>
                            </tr>
                        </tfoot>
                </table>
            </div>
            <div>
                <div style="padding:0px 14px;margin-top:100px;" class="col12">
                    <div class="col6">
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            1. Precios Cotizados
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            2. Total Condiciones Especiales
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            3. Gasto Administrativo
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            4. Subtotal
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            5. Descuento  inn_descuentoformapago %
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            6. Seguro Campesino
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            7. Total Cuota Mensual
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            8. Gastos de Emisión
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            9. Seguro campesino por Gastos de Emisión
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            10. Total Primera Cuota
                        </div>
                        <div class="key-style-12" style="padding:0px 0px 0px 30px;">
                            11. Descuento inn_descuentoprimeracuota % Primera Cuota
                        </div>
                    </div>
                    <div class="col6">
                        <div class="value-style-12">
                            inn_preciostotalcotizados
                        </div>
                        <div class="value-style-12">
                            inn_preciostotalcondicionesespeciales
                        </div>
                        <div class="value-style-12">
                            inn_preciosgastosadministrativos
                        </div>
                        <div class="value-style-12">
                            inn_preciossubtotalcotizacion
                        </div>
                        <div class="value-style-12">
                            inn_preciosdescuentosubtotal
                        </div>
                        <div class="value-style-12">
                            inn_preciossegurocampesinosubtotal
                        </div>
                        <div class="value-style-12">
                            inn_preciossubtotalcuotamensual
                        </div>
                        <div class="value-style-12">
                            inn_preciosgastosemision
                        </div>
                        <div class="value-style-12">
                            inn_preciossegurocampesiongastosemision
                        </div>
                        <div class="value-style-12">
                            inn_preciostotalprimeracuota
                        </div>
                        <div class="value-style-12">
                            inn_preciosdescuentoprimeracuota
                        </div>
                    </div>
                </div>
                <div style="margin-top:30px;">
                    <p style="line-height: 1.2;" class="title-10">*Servicios adicionales, los cuales al adquirirse en conjunto reciben condiciones especiales en su precio.</p>
                    <p style="line-height: 1.2;" class="title-10">*Segunda Opinión Médica Clínica Universidad de Navarra</p>
                    <div style="line-height: 1.2;margin-top:-30px;" class="title-10">** El contrato a partir de la firma o aceptación digital se encontrará en proceso de revisión documental y emisión hasta que se procese el pago y
                    demás documentos en Saludsa. Una vez que estos se hayan procesados correctamente se notificará al cliente la confirmación de la activación del
                    producto y fecha de inicio de vigencia</div>
                </div>
            </div>
</div>