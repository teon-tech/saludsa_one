<div style="width:100%; height:100%; padding: 5px 0px;">
            <table style="margin-top:100px;" class="table-plans">
                <thead>
                    <tr>
                        <th style="width:20%;">Nombre del plan</th>
                        <th style="width:27%;">Tipo de plan por segmento de mercado</th>
                        <th style="width:20%;">Tipo de plan por modalidad</th>
                        <th style="width:18%;">C&oacute;digo de Clasificaci&oacute;n</th>
                        <th style="width:15%;">Producto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Plan Individual (P)</td>
                        <td>Persona Natural (I)</td>
                        <td>Mixto PIM (M)</td>
                        <td>PIM</td>
                        <td>{{$sale->details[0]->planPrice->plan->name}}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <p class="title-18">Suscripción del Contrato name</p>
            </div>
            <div class="col12">
                <div class="col5">  
                    <span class="key-style">Titular:</span>
                    <span class="value-style">{{$sale->customerData->name}} {{$sale->customerData->father_last_name}} {{$sale->customerData->mother_last_name}}</span>
                </div>
                <div class="col7">
                    <div>
                        <span class="key-style">Lugar:</span>
                        <span class="value-style">{{$sale->province->name}}</span>
                    </div>
                    <div>
                        <span class="key-style">Fecha de emisión de documentos:</span>
                        <span class="value-style">{{$sale->created_at}}</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="title-16">Contratantes</p>
            </div>
            <div>
                <p class="title-14">A.	Compañía de Servicios de Atención Integral de Salud Prepaga.</p>
                <p style="padding-left:17px;font-size:13px;line-height: 0.1;">Saludsa Sistema de Medicina Prepagada del Ecuadorsal S. A. (“SALUDSA”)</p>
            </div>
            <div>
                <p class="title-14">B.	Datos del titular / Contratante</p>
                <div style="padding:0px 14px;" class="col12">
                    <div class="col12">  
                        <span class="key-style">Nombres:</span>
                        <span class="value-style">{{$sale->customerData->name}}</span>
                    </div>
                    <div class="col6">
                        <div>
                            <span class="key-style">Apellido paterno:</span>
                            <span class="value-style">{{$sale->customerData->father_last_name}}</span>
                        </div>
                        <div>
                            <span class="key-style">Fecha de nacimiento:</span>
                            <span class="value-style">{{$sale->customerData->birth_date}}</span>
                        </div>
                        <div>
                            <span class="key-style">Cédula | Pasaporte:</span>
                            <span class="value-style">{{$sale->customerData->document}}</span>
                        </div>
                    </div>
                    <div class="col6">
                        <div>
                            <span class="key-style">Apellido materno:</span>
                            <span class="value-style">{{$sale->customerData->mother_last_name}}</span>
                        </div>
                        <div>
                            <span class="key-style">Nacionalidad:</span>
                            <span class="value-style"></span>
                        </div>
                        <div>
                            <span class="key-style">Estado civil:</span>
                            <span class="value-style">{{$sale->customerData->civil_status}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <p class="title-14">Notificaciones Titular / Contratante</p>
                <div style="padding:0px 14px;" class="col12">
                    <div class="col6">
                        <div>
                            <span class="key-style">E-mail:</span>
                            <span class="value-style">{{$sale->customerData->email}}</span>
                        </div>
                        <div>
                            <span class="key-style">Calle principal:</span>
                            <span class="value-style">{{$sale->customerData->direction}}</span>
                        </div>
                    </div>
                    <div class="col6">
                        <div>
                            <span class="key-style">E-mail de trabajo:</span>
                            <span class="value-style"></span>
                        </div>
                        <div>
                            <span class="key-style">Número:</span>
                            <span class="value-style"></span>
                        </div>
                    </div>
                    <div class="col12">
                        <div>
                            <span class="key-style">Calle transversal:</span>
                            <span class="value-style"></span>
                        </div>
                    </div>
                    <div class="col6">
                        <div>
                            <span class="key-style">Provincia:</span>
                            <span class="value-style">{{$sale->province->name}}</span>
                        </div>
                        <div>
                            <span class="key-style">Zona:</span>
                            <span class="value-style"></span>
                        </div>
                        <div>
                            <span class="key-style">Teléfono:</span>
                            <span class="value-style"></span>
                        </div>
                    </div>
                    <div class="col6">
                        <div>
                            <span class="key-style">Ciudad:</span>
                            <span class="value-style">Quito</span>
                        </div>
                        <div>
                            <span class="key-style">Barrio:</span>
                            <span class="value-style"></span>
                        </div>
                        <div>
                            <span class="key-style">Teléfono móvil:</span>
                            <span class="value-style">{{$sale->customerData->phone}}</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="title-8">* Autorizo a recibir de Saludsa todo tipo de notificaciones en la información de contacto proporcionada</p>
                    </div>
                </div>
            </div>
            <div>
                <div style="padding:0px 14px;" class="col12">
                    <div class="col6">
                        <p class="title-14">Forma de Pago: <span class="title-14-black">Tarjeta de Crédito</span></p>
                    </div>
                    <div class="col6">
                        <p class="title-14">Periodo de Pago: <span class="title-14-black">
                            @if($sale->subscription[0]->type=="ANNUAL")ANUAL @else MENSUAL @endif
                        </span></p>
                    </div>
                </div>
            </div>
            <div>
                <p class="title-14">Reembolso de gastos (datos de banco para transferencia / pago inteligente)</p>
                <div style="padding:0px 14px;" class="col12">
                    <div class="col6">
                        <div>
                            <span class="key-style">Nombre del Banco:</span>
                            <span class="value-style">{{$sale->subscription[0]->bank_info['bank']}}</span>
                        </div>
                        <div>
                            <span class="key-style">Tipo de Cuenta:</span>
                            <span class="value-style"></span>
                        </div>
                        <div style="margin-top:50px;">
                            <span class="key-style">Nombre del vendedor:</span>
                            <span class="value-style">inn_vendedo</span>
                        </div>
                    </div>
                    <div class="col6">
                        <div>
                            <span class="key-style">No. de cuenta: </span>
                            <span class="value-style"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>